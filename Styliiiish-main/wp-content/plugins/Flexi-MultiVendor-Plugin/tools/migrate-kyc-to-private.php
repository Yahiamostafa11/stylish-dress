<?php
/**
 * One-time migration: move already-uploaded KYC documents out of the public uploads
 * tree and into private storage (wp-content/uploads/styliiiish-kyc/).
 *
 * Every document uploaded before includes/kyc-storage.php was deployed is still sitting
 * in wp-content/uploads/YYYY/MM/ and is publicly downloadable. This script relocates
 * those files and rewrites the user meta.
 *
 * CLI ONLY — it refuses to run over HTTP, so it adds no web-reachable attack surface.
 *
 * USAGE (from the WordPress root, on the server):
 *
 *   # 1. Report only. Changes nothing. Run this first and read the output.
 *   php wp-content/plugins/Flexi-MultiVendor-Plugin/tools/migrate-kyc-to-private.php
 *
 *   # 2. Perform the migration.
 *   php wp-content/plugins/Flexi-MultiVendor-Plugin/tools/migrate-kyc-to-private.php --commit
 *
 * If WP-CLI is available, this also works:
 *   wp eval-file wp-content/plugins/Flexi-MultiVendor-Plugin/tools/migrate-kyc-to-private.php --commit
 *
 * ORDER OF OPERATIONS per document (fail-safe: the original is only removed last):
 *   copy -> verify size matches -> write new meta -> delete original -> delete legacy meta
 *
 * BACK OUT: if something looks wrong before you delete anything, the legacy meta and the
 * original file are both still present during a dry run. After a --commit run, restore
 * from your pre-migration uploads backup. Take one first.
 *
 * @package TaajVendor
 */

if ( PHP_SAPI !== 'cli' ) {
	http_response_code( 403 );
	exit( "This migration script can only be run from the command line.\n" );
}

// --- Bootstrap WordPress (unless WP-CLI already did it) -----------------------------

if ( ! defined( 'ABSPATH' ) ) {
	$root = __DIR__;
	$found = '';

	for ( $i = 0; $i < 8; $i++ ) {
		$root = dirname( $root );
		if ( file_exists( $root . '/wp-load.php' ) ) {
			$found = $root . '/wp-load.php';
			break;
		}
	}

	if ( '' === $found ) {
		exit( "Could not locate wp-load.php. Run this from inside the WordPress install.\n" );
	}

	require_once $found;
}

if ( ! function_exists( 'wf_kyc_doc_keys' ) ) {
	exit( "includes/kyc-storage.php is not loaded. Activate the TaajVendor plugin first.\n" );
}

// --- Options -------------------------------------------------------------------------

$argv_list = isset( $argv ) && is_array( $argv ) ? $argv : array();
$commit    = in_array( '--commit', $argv_list, true );

$uploads = wp_upload_dir();
if ( ! empty( $uploads['error'] ) ) {
	exit( 'Uploads directory unavailable: ' . $uploads['error'] . "\n" );
}

$basedir = rtrim( (string) $uploads['basedir'], '/\\' );
$baseurl = rtrim( (string) $uploads['baseurl'], '/' );

$private_dir = wf_kyc_private_dir();
if ( is_wp_error( $private_dir ) ) {
	exit( 'Could not prepare private storage: ' . $private_dir->get_error_message() . "\n" );
}

echo $commit
	? "MODE: COMMIT — files will be moved and meta rewritten.\n\n"
	: "MODE: DRY RUN — nothing will be changed. Re-run with --commit to apply.\n\n";

echo 'Public uploads : ' . $basedir . "\n";
echo 'Private target : ' . $private_dir . "\n\n";

// --- Collect --------------------------------------------------------------------------

global $wpdb;

$doc_keys     = wf_kyc_doc_keys();
$placeholders = implode( ', ', array_fill( 0, count( $doc_keys ), '%s' ) );

$rows = $wpdb->get_results(
	$wpdb->prepare(
		"SELECT user_id, meta_key, meta_value
		 FROM {$wpdb->usermeta}
		 WHERE meta_key IN ({$placeholders})
		   AND meta_value <> ''
		 ORDER BY user_id ASC",
		...$doc_keys
	)
);

if ( empty( $rows ) ) {
	exit( "No legacy KYC documents found. Nothing to migrate.\n" );
}

echo 'Found ' . count( $rows ) . " legacy document reference(s).\n\n";

$moved = $skipped = $failed = 0;

foreach ( $rows as $row ) {

	$user_id  = (int) $row->user_id;
	$doc_key  = (string) $row->meta_key;
	$legacy   = trim( (string) $row->meta_value );
	$label    = sprintf( 'user %d / %s', $user_id, $doc_key );

	// Already migrated? Leave it alone.
	$existing = (string) get_user_meta( $user_id, $doc_key . '_file', true );
	if ( '' !== $existing ) {
		echo "SKIP   {$label}: already has private file ({$existing})\n";
		$skipped++;
		continue;
	}

	// Resolve the stored url back to a path inside the uploads tree.
	if ( 0 !== strpos( $legacy, $baseurl ) ) {
		echo "SKIP   {$label}: value is not an uploads url ({$legacy})\n";
		$skipped++;
		continue;
	}

	$relative = ltrim( substr( $legacy, strlen( $baseurl ) ), '/' );
	$source   = $basedir . '/' . $relative;
	$real_src = realpath( $source );

	if ( false === $real_src || ! is_file( $real_src ) || 0 !== strpos( $real_src, realpath( $basedir ) ) ) {
		echo "SKIP   {$label}: file missing or outside uploads ({$source})\n";
		$skipped++;
		continue;
	}

	$ext = strtolower( pathinfo( $real_src, PATHINFO_EXTENSION ) );
	if ( '' === wf_kyc_mime_for_extension( $ext ) ) {
		echo "SKIP   {$label}: unsupported extension .{$ext} — move this one by hand\n";
		$skipped++;
		continue;
	}

	$filename = sprintf( '%d-%s-%s.%s', $user_id, $doc_key, wp_generate_password( 24, false, false ), $ext );
	$dest     = $private_dir . '/' . $filename;

	if ( ! $commit ) {
		echo 'WOULD  ' . $label . ': ' . $relative . ' -> ' . $filename . "\n";
		$moved++;
		continue;
	}

	if ( ! @copy( $real_src, $dest ) ) {
		echo "FAIL   {$label}: copy failed, original left in place\n";
		$failed++;
		continue;
	}

	// Verify before destroying anything.
	if ( ! is_file( $dest ) || filesize( $dest ) !== filesize( $real_src ) ) {
		@unlink( $dest );
		echo "FAIL   {$label}: copy verification failed, original left in place\n";
		$failed++;
		continue;
	}

	@chmod( $dest, 0640 );

	update_user_meta( $user_id, $doc_key . '_file', $filename );

	if ( @unlink( $real_src ) ) {
		delete_user_meta( $user_id, $doc_key );
		echo 'MOVED  ' . $label . ': ' . $relative . ' -> ' . $filename . "\n";
		$moved++;
	} else {
		// Private copy and meta are in place, so the admin screens are already using the
		// protected path; the public original just could not be removed.
		echo 'WARN   ' . $label . ": migrated, but the public original could not be deleted. Remove by hand: {$real_src}\n";
		$moved++;
	}
}

echo "\n--------------------------------------------\n";
echo ( $commit ? 'Migrated: ' : 'Would migrate: ' ) . $moved . "\n";
echo 'Skipped : ' . $skipped . "\n";
echo 'Failed  : ' . $failed . "\n";

if ( ! $commit ) {
	echo "\nRe-run with --commit to apply.\n";
} else {
	echo "\nDone. Now confirm a document opens from the vendor review screen,\n";
	echo "and that the old public url returns 403/404.\n";
}
