<?php
/**
 * Plugin Name: Styliiiish – Manager login shortcut
 * Description: When the owner / a manager signs in from My Account they land on the storefront
 *              (not on the account page), where the header shows a "My dashboard" button.
 *              Also tells the storefront (sty-session.php) who counts as a dashboard user.
 */

if (!defined('ABSPATH')) {
	exit;
}

/** Owner, managers and anyone the Flexi dashboard lets in. One definition for the redirect and the header button. */
function styliiiish_is_dashboard_user($user_id) {
	$user_id = (int) $user_id;
	if ($user_id <= 0) {
		return false;
	}

	if (function_exists('wf_od_is_user_plugin_admin') && wf_od_is_user_plugin_admin($user_id)) {
		return true;
	}
	if (function_exists('wf_od_get_manager_ids') && in_array($user_id, array_map('intval', (array) wf_od_get_manager_ids()), true)) {
		return true;
	}
	if (function_exists('wf_od_get_dashboard_ids') && in_array($user_id, array_map('intval', (array) wf_od_get_dashboard_ids()), true)) {
		return true;
	}

	return user_can($user_id, 'manage_options');
}

// Sign-in from My Account (WooCommerce form) → the storefront home.
add_filter('woocommerce_login_redirect', function ($redirect, $user) {
	if ($user instanceof WP_User && styliiiish_is_dashboard_user($user->ID)) {
		return home_url('/');
	}

	return $redirect;
}, 20, 2);

// Sign-in through the plain WordPress login screen behaves the same for them.
add_filter('login_redirect', function ($redirect_to, $requested, $user) {
	if ($user instanceof WP_User && styliiiish_is_dashboard_user($user->ID)) {
		return home_url('/');
	}

	return $redirect_to;
}, 20, 3);
