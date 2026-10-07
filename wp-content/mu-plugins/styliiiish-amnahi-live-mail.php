<?php
/**
 * Plugin Name: Styliiiish – امنحي "your dress is live" email
 * Description: When a moderator publishes a seller's امنحي listing in wp-admin, emails the seller a link to it.
 *
 * Fires on either order of the moderator's workflow: publish a listing that is
 * already in the "حكايات امنحي" category, or publish first and file it under the
 * category afterwards. A post meta flag guarantees one email per listing, and the
 * mail is sent after the admin response has been flushed so bulk-publishing a page
 * of listings doesn't hang the screen on SMTP round trips.
 */

if (!defined('ABSPATH')) {
	exit;
}

const STYLIIISH_AMNAHI_CATEGORY_SLUG = 'amnahi';
const STYLIIISH_AMNAHI_MAILED_META   = '_styliiiish_amnahi_live_mailed';

/** Queue a listing for the "it's live" email if it qualifies and wasn't mailed yet. */
function styliiiish_amnahi_maybe_queue($post_id, $known_in_category = false) {
	$post = get_post($post_id);
	if (!$post || 'product' !== $post->post_type || 'publish' !== $post->post_status) {
		return;
	}
	if (get_post_meta($post_id, STYLIIISH_AMNAHI_MAILED_META, true)) {
		return;
	}

	$in_category = $known_in_category
		|| has_term(STYLIIISH_AMNAHI_CATEGORY_SLUG, 'product_cat', $post_id)
		|| get_post_meta($post_id, '_styliiiish_amnahi_listing', true);
	if (!$in_category) {
		return;
	}

	// Don't email staff about their own test listings.
	if (user_can((int) $post->post_author, 'edit_others_posts')) {
		return;
	}

	// The flag doubles as a lock: whichever hook gets here first wins.
	if (!add_post_meta($post_id, STYLIIISH_AMNAHI_MAILED_META, '1', true)) {
		return;
	}

	$GLOBALS['styliiiish_amnahi_mail_queue'][] = (int) $post_id;
}

add_action('transition_post_status', function ($new_status, $old_status, $post) {
	if ('publish' === $new_status && 'publish' !== $old_status) {
		styliiiish_amnahi_maybe_queue($post->ID);
	}
}, 20, 3);

add_action('set_object_terms', function ($object_id, $terms, $tt_ids, $taxonomy) {
	if ('product_cat' !== $taxonomy) {
		return;
	}
	$amnahi = get_term_by('slug', STYLIIISH_AMNAHI_CATEGORY_SLUG, 'product_cat');
	if ($amnahi && in_array((int) $amnahi->term_taxonomy_id, array_map('intval', (array) $tt_ids), true)) {
		styliiiish_amnahi_maybe_queue($object_id, true);
	}
}, 20, 4);

add_action('shutdown', function () {
	$queue = isset($GLOBALS['styliiiish_amnahi_mail_queue']) ? $GLOBALS['styliiiish_amnahi_mail_queue'] : array();
	if (!$queue) {
		return;
	}

	ignore_user_abort(true);
	if (function_exists('litespeed_finish_request')) {
		litespeed_finish_request();
	} elseif (function_exists('fastcgi_finish_request')) {
		fastcgi_finish_request();
	}
	@set_time_limit(180);

	foreach (array_unique($queue) as $post_id) {
		styliiiish_amnahi_send_live_mail($post_id);
	}
}, 0);

function styliiiish_amnahi_send_live_mail($post_id) {
	$post   = get_post($post_id);
	$author = $post ? get_userdata((int) $post->post_author) : false;
	if (!$post || !$author || !is_email($author->user_email)) {
		return;
	}

	$url   = home_url('/amnahi/' . (int) $post_id);
	$title = esc_html(get_the_title($post));
	$name  = esc_html($author->display_name ? $author->display_name : 'يا قمر');

	$html = '<div dir="rtl" style="font-family:Tahoma,Arial,sans-serif;max-width:480px;margin:0 auto;padding:24px;text-align:right">'
		. '<h2 style="color:#8E2F43;margin:0 0 16px">فستانك بقى على الموقع! 🎉</h2>'
		. '<div style="color:#433131;font-size:14px;line-height:1.9">'
		. '<p>أهلاً ' . $name . '،</p>'
		. '<p>إعلان <strong>«' . $title . '»</strong> اتراجع واتنشر، ودلوقتي أي حد بيتصفح امنحي يقدر يشوفه.</p>'
		. '<p>لو حد اهتم بالفستان هيوصلك إيميل، وهتلاقي رسائله في صفحة الرسايل.</p>'
		. '</div>'
		. '<p style="margin:24px 0"><a href="' . esc_url($url) . '" style="background:#BA5D70;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block">شوفي إعلانك</a></p>'
		. '<p style="color:#9C7F6C;font-size:12px;margin-top:28px">Styliiiish &middot; امنحي</p>'
		. '</div>';

	wp_mail(
		$author->user_email,
		'فستانك اتنشر على امنحي 🎉',
		$html,
		array('Content-Type: text/html; charset=UTF-8')
	);
}
