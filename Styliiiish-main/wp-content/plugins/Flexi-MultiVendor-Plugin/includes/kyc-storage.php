<?php

/**
 * @package   Styliiiish
 * @author    Yahia Mostafa — ZIJ Tech <https://zijtech.com/>
 * @copyright ZIJ Tech
 */
/**
 * KYC document storage — private-by-default.
 *
 * WHY THIS EXISTS
 * ---------------
 * Vendor KYC documents (national ID front/back, utility bill, commercial register,
 * tax card) used to be written straight into wp-content/uploads/YYYY/MM/ via
 * wp_handle_upload(), with the resulting PUBLIC url stored in user meta. Anyone who
 * knew or guessed the filename — and any crawler that ever saw it — could download a
 * vendor's identity documents. This module moves those files into a directory that
 * Apache/LiteSpeed refuse to serve, and puts an authenticated endpoint in front of them.
 *
 * STORAGE MODEL
 * -------------
 *   wp-content/uploads/styliiiish-kyc/        <- .htaccess "deny all" + empty index.html
 *   meta  taj_<doc>_file   = "12-taj_id_front-<random>.jpg"   (filename only, new model)
 *   meta  taj_<doc>        = "https://.../uploads/2026/03/x.jpg"  (legacy public url)
 *
 * Legacy meta is deliberately left alone by this file. wf_kyc_doc_url() prefers the new
 * private file and falls back to the legacy url, so the admin screens keep working
 * before and after migration. Moving the already-exposed files is the job of
 * tools/migrate-kyc-to-private.php, which is run manually.
 *
 * @package TaajVendor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Directory name created inside the uploads basedir. */
if ( ! defined( 'WF_KYC_DIR_NAME' ) ) {
	define( 'WF_KYC_DIR_NAME', 'styliiiish-kyc' );
}

/** Hard ceiling per document. Keep in step with the hosting upload_max_filesize. */
if ( ! defined( 'WF_KYC_MAX_BYTES' ) ) {
	define( 'WF_KYC_MAX_BYTES', 8 * 1024 * 1024 );
}

/**
 * The user-meta keys that hold KYC documents.
 *
 * @return string[]
 */
function wf_kyc_doc_keys() {
	return array(
		'taj_id_front',
		'taj_id_back',
		'taj_utility_bill',
		'taj_commercial_register',
		'taj_tax_card',
	);
}

/**
 * Allowlist of accepted document types.
 *
 * Deliberately narrow. Nothing that a browser will execute or that can carry script
 * (no svg, no html, no office macros) is accepted.
 *
 * @return array<string,string> ext-pattern => mime
 */
function wf_kyc_allowed_mimes() {
	return array(
		'jpg|jpeg' => 'image/jpeg',
		'png'      => 'image/png',
		'webp'     => 'image/webp',
		'pdf'      => 'application/pdf',
	);
}

/**
 * Map a stored extension to the mime type used when streaming it back.
 *
 * @param string $ext File extension, lowercase, no dot.
 * @return string Mime type, or '' when the extension is not one we store.
 */
function wf_kyc_mime_for_extension( $ext ) {
	$map = array(
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png'  => 'image/png',
		'webp' => 'image/webp',
		'pdf'  => 'application/pdf',
	);

	$ext = strtolower( (string) $ext );

	return isset( $map[ $ext ] ) ? $map[ $ext ] : '';
}

/**
 * Absolute path of the private KYC directory, creating and hardening it on first use.
 *
 * @return string|WP_Error Absolute path with no trailing slash, or WP_Error.
 */
function wf_kyc_private_dir() {
	$uploads = wp_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return new WP_Error( 'wf_kyc_uploads_unavailable', (string) $uploads['error'] );
	}

	$dir = rtrim( (string) $uploads['basedir'], '/\\' ) . '/' . WF_KYC_DIR_NAME;

	if ( ! is_dir( $dir ) && ! wp_mkdir_p( $dir ) ) {
		return new WP_Error( 'wf_kyc_mkdir_failed', 'Could not create the private KYC directory.' );
	}

	// Belt: web server level deny. Works on Apache and LiteSpeed (Hostinger).
	$htaccess = $dir . '/.htaccess';
	if ( ! file_exists( $htaccess ) ) {
		$rules = "# Vendor KYC documents. Never served directly.\n"
			. "# Access goes through admin-post.php?action=wf_kyc_doc, which checks capability + nonce.\n"
			. "<IfModule mod_authz_core.c>\n"
			. "    Require all denied\n"
			. "</IfModule>\n"
			. "<IfModule !mod_authz_core.c>\n"
			. "    Order allow,deny\n"
			. "    Deny from all\n"
			. "</IfModule>\n";
		@file_put_contents( $htaccess, $rules );
	}

	// Braces: no directory listing even if the deny rule is ever lost.
	$index = $dir . '/index.html';
	if ( ! file_exists( $index ) ) {
		@file_put_contents( $index, '' );
	}

	return $dir;
}

/**
 * Validate and store one uploaded KYC document.
 *
 * @param array  $file    One entry from $_FILES.
 * @param int    $user_id Owning vendor.
 * @param string $doc_key One of wf_kyc_doc_keys().
 * @return string|WP_Error Stored filename (not a path, not a url), or WP_Error.
 */
function wf_kyc_store_uploaded_file( $file, $user_id, $doc_key ) {
	$user_id = (int) $user_id;

	if ( $user_id <= 0 || ! in_array( $doc_key, wf_kyc_doc_keys(), true ) ) {
		return new WP_Error( 'wf_kyc_bad_request', 'Invalid KYC document request.' );
	}

	if ( ! is_array( $file ) || empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
		return new WP_Error( 'wf_kyc_no_file', 'No uploaded file received.' );
	}

	if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== (int) $file['error'] ) {
		return new WP_Error( 'wf_kyc_upload_error', 'Upload failed. Please try again.' );
	}

	$size = (int) ( isset( $file['size'] ) ? $file['size'] : filesize( $file['tmp_name'] ) );
	if ( $size <= 0 ) {
		return new WP_Error( 'wf_kyc_empty_file', 'The uploaded file is empty.' );
	}
	if ( $size > WF_KYC_MAX_BYTES ) {
		return new WP_Error(
			'wf_kyc_too_large',
			sprintf( 'File is larger than the %d MB limit.', (int) ( WF_KYC_MAX_BYTES / 1024 / 1024 ) )
		);
	}

	// Checks the real content against the claimed extension, and rejects anything
	// outside the allowlist. Also normalises misleading names like "id.pdf.php".
	$checked = wp_check_filetype_and_ext(
		$file['tmp_name'],
		(string) $file['name'],
		wf_kyc_allowed_mimes()
	);

	$ext  = isset( $checked['ext'] ) ? strtolower( (string) $checked['ext'] ) : '';
	$type = isset( $checked['type'] ) ? (string) $checked['type'] : '';

	if ( '' === $ext || '' === $type || ! in_array( $type, array_values( wf_kyc_allowed_mimes() ), true ) ) {
		return new WP_Error( 'wf_kyc_bad_type', 'Only JPG, PNG, WEBP and PDF documents are accepted.' );
	}

	// Second opinion straight from the bytes, where the extension is available.
	if ( function_exists( 'finfo_open' ) ) {
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		if ( false !== $finfo ) {
			$detected = finfo_file( $finfo, $file['tmp_name'] );
			finfo_close( $finfo );

			if ( is_string( $detected ) && $detected !== $type ) {
				return new WP_Error( 'wf_kyc_type_mismatch', 'The file content does not match its extension.' );
			}
		}
	}

	$dir = wf_kyc_private_dir();
	if ( is_wp_error( $dir ) ) {
		return $dir;
	}

	// Unguessable name: even if the deny rule is ever misconfigured, the path is not enumerable.
	$filename = sprintf(
		'%d-%s-%s.%s',
		$user_id,
		$doc_key,
		wp_generate_password( 24, false, false ),
		$ext
	);

	$destination = $dir . '/' . $filename;

	if ( ! move_uploaded_file( $file['tmp_name'], $destination ) ) {
		return new WP_Error( 'wf_kyc_move_failed', 'Could not store the uploaded document.' );
	}

	@chmod( $destination, 0640 );

	// Replace the previous private file for this slot, if any.
	$previous = (string) get_user_meta( $user_id, $doc_key . '_file', true );
	if ( '' !== $previous && $previous !== $filename ) {
		wf_kyc_delete_private_file( $previous );
	}

	update_user_meta( $user_id, $doc_key . '_file', $filename );

	return $filename;
}

/**
 * Delete a file from the private KYC directory.
 *
 * Refuses anything that is not a bare filename resolving inside that directory, so a
 * poisoned meta value can never be used to unlink an arbitrary path.
 *
 * @param string $filename Stored filename.
 * @return bool
 */
function wf_kyc_delete_private_file( $filename ) {
	$filename = (string) $filename;

	if ( '' === $filename || basename( $filename ) !== $filename ) {
		return false;
	}

	$dir = wf_kyc_private_dir();
	if ( is_wp_error( $dir ) ) {
		return false;
	}

	$path = $dir . '/' . $filename;

	if ( ! is_file( $path ) ) {
		return false;
	}

	$real_path = realpath( $path );
	$real_dir  = realpath( $dir );

	if ( false === $real_path || false === $real_dir || 0 !== strpos( $real_path, $real_dir ) ) {
		return false;
	}

	return @unlink( $real_path );
}

/**
 * May the current user view this vendor's KYC documents?
 *
 * @param int $owner_id Vendor the documents belong to.
 * @return bool
 */
function wf_kyc_current_user_can_view( $owner_id ) {
	$owner_id = (int) $owner_id;
	$viewer   = get_current_user_id();

	if ( $viewer <= 0 || $owner_id <= 0 ) {
		return false;
	}

	// A vendor may always see their own submitted documents.
	if ( $viewer === $owner_id ) {
		return true;
	}

	if ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ) {
		return true;
	}

	if ( function_exists( 'wf_od_is_user_plugin_admin' ) && wf_od_is_user_plugin_admin( $viewer ) ) {
		return true;
	}

	if ( function_exists( 'wf_od_get_user_type' ) && 'manager' === wf_od_get_user_type( $viewer ) ) {
		return true;
	}

	return false;
}

/**
 * A viewable url for one KYC document.
 *
 * Returns a freshly nonced admin-post url when the document lives in private storage,
 * and falls back to the legacy public url while migration is still pending. Returns ''
 * when there is no document, so existing `if ( $url )` guards keep working.
 *
 * @param int    $user_id Owning vendor.
 * @param string $doc_key One of wf_kyc_doc_keys().
 * @return string
 */
function wf_kyc_doc_url( $user_id, $doc_key ) {
	$user_id = (int) $user_id;

	if ( $user_id <= 0 || ! in_array( $doc_key, wf_kyc_doc_keys(), true ) ) {
		return '';
	}

	$filename = (string) get_user_meta( $user_id, $doc_key . '_file', true );

	if ( '' !== $filename ) {
		return wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'wf_kyc_doc',
					'user'   => $user_id,
					'doc'    => $doc_key,
				),
				admin_url( 'admin-post.php' )
			),
			'wf_kyc_doc_' . $user_id . '_' . $doc_key
		);
	}

	// Legacy, still-public document. Left readable on purpose so the review screens do
	// not go blank between deploying this file and running the migration script.
	return (string) get_user_meta( $user_id, $doc_key, true );
}

/**
 * Authenticated document viewer.
 *
 * No admin_post_nopriv twin: logged-out requests are rejected by WordPress before
 * reaching this callback.
 */
function wf_kyc_serve_document() {
	$owner_id = isset( $_GET['user'] ) ? absint( wp_unslash( $_GET['user'] ) ) : 0;
	$doc_key  = isset( $_GET['doc'] ) ? sanitize_key( wp_unslash( $_GET['doc'] ) ) : '';

	if ( $owner_id <= 0 || ! in_array( $doc_key, wf_kyc_doc_keys(), true ) ) {
		wp_die( esc_html__( 'Invalid document request.', 'taajvendor' ), '', array( 'response' => 400 ) );
	}

	check_admin_referer( 'wf_kyc_doc_' . $owner_id . '_' . $doc_key );

	if ( ! wf_kyc_current_user_can_view( $owner_id ) ) {
		wp_die( esc_html__( 'You are not allowed to view this document.', 'taajvendor' ), '', array( 'response' => 403 ) );
	}

	$filename = (string) get_user_meta( $owner_id, $doc_key . '_file', true );

	if ( '' === $filename || basename( $filename ) !== $filename ) {
		wp_die( esc_html__( 'Document not found.', 'taajvendor' ), '', array( 'response' => 404 ) );
	}

	$dir = wf_kyc_private_dir();
	if ( is_wp_error( $dir ) ) {
		wp_die( esc_html__( 'Document storage is unavailable.', 'taajvendor' ), '', array( 'response' => 500 ) );
	}

	$path      = $dir . '/' . $filename;
	$real_path = realpath( $path );
	$real_dir  = realpath( $dir );

	if ( false === $real_path || false === $real_dir || 0 !== strpos( $real_path, $real_dir ) || ! is_file( $real_path ) ) {
		wp_die( esc_html__( 'Document not found.', 'taajvendor' ), '', array( 'response' => 404 ) );
	}

	$mime = wf_kyc_mime_for_extension( pathinfo( $real_path, PATHINFO_EXTENSION ) );
	if ( '' === $mime ) {
		wp_die( esc_html__( 'Document not found.', 'taajvendor' ), '', array( 'response' => 404 ) );
	}

	nocache_headers();
	header( 'Content-Type: ' . $mime );
	header( 'Content-Length: ' . filesize( $real_path ) );
	header( 'Content-Disposition: inline; filename="' . $doc_key . '.' . pathinfo( $real_path, PATHINFO_EXTENSION ) . '"' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Cache-Control: private, no-store, max-age=0' );
	header( 'Referrer-Policy: no-referrer' );

	readfile( $real_path );
	exit;
}
add_action( 'admin_post_wf_kyc_doc', 'wf_kyc_serve_document' );
