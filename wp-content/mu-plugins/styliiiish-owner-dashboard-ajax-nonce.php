<?php
/**
 * Plugin Name: Styliiiish – Owner Dashboard AJAX nonce bridge
 * Description: The owner dashboard prints `ajax_object` several times with different nonces and the last one
 *              ('styliiiish_nonce') wins in the browser, while most of the plugin's handlers verify 'ajax_nonce'
 *              — so Manage Products (and most other panels) were rejected and sat on their loading skeleton.
 *              This accepts either nonce from a logged-in user and presents the handler the one it verifies.
 *              Nothing is bypassed: the nonce must still be valid for the current user/session.
 */

if (!defined('ABSPATH')) {
	exit;
}

add_action('admin_init', function () {
	if (!wp_doing_ajax() || empty($_POST['action']) || empty($_POST['nonce']) || !is_user_logged_in()) {
		return;
	}

	$action = sanitize_key(wp_unslash($_POST['action']));
	if (0 !== strpos($action, 'styliiiish_')) {
		return;
	}

	// Handlers that verify the owner dashboard's own nonce; every other styliiiish_* handler verifies 'ajax_nonce'.
	$expects_dashboard_nonce = in_array($action, array('styliiiish_inline_update_status', 'styliiiish_inline_update'), true);
	$want  = $expects_dashboard_nonce ? 'styliiiish_nonce' : 'ajax_nonce';
	$other = $expects_dashboard_nonce ? 'ajax_nonce' : 'styliiiish_nonce';

	$sent = sanitize_text_field(wp_unslash($_POST['nonce']));

	if (wp_verify_nonce($sent, $want)) {
		return;
	}

	if (wp_verify_nonce($sent, $other)) {
		$fresh            = wp_create_nonce($want);
		$_POST['nonce']   = $fresh;
		$_REQUEST['nonce'] = $fresh;
	}
}, 1);
