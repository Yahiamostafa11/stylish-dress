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

// Sign-in from My Account (WooCommerce form) → the storefront home. A form that asked for a specific page
// (anything other than the default My Account address) keeps that page.
add_filter('woocommerce_login_redirect', function ($redirect, $user) {
	if (!($user instanceof WP_User) || !styliiiish_is_dashboard_user($user->ID)) {
		return $redirect;
	}
	$account = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '';
	if ($account !== '' && untrailingslashit((string) $redirect) !== untrailingslashit($account)) {
		return $redirect;
	}

	return home_url('/');
}, 20, 2);

// Sign-in through the plain WordPress login screen (the hidden /secure-admin/ page): owner and managers land on
// the Owner Dashboard instead of the WordPress admin, unless they asked for a specific page.
// WordPress itself fills "redirect_to" with the wp-admin address when nothing was requested, so that
// value counts as "no request" too.
add_filter('login_redirect', function ($redirect_to, $requested, $user) {
	if (!($user instanceof WP_User) || !styliiiish_is_dashboard_user($user->ID)) {
		return $redirect_to;
	}

	$requested = trim((string) $requested);
	if ($requested === '' || untrailingslashit($requested) === untrailingslashit(admin_url())) {
		return home_url('/owner-dashboard/');
	}

	return $redirect_to;
}, 20, 3);

// /owner-dashboard/ for someone who is signed out: a login form that brings them straight back to the dashboard
// (instead of the plain "Please log in" line).
add_filter('do_shortcode_tag', function ($output, $tag) {
	if ($tag !== 'owner_dashboard' || is_user_logged_in()) {
		return $output;
	}

	$ar     = is_rtl();
	$target = home_url('/owner-dashboard/');
	$queried = get_queried_object_id();
	if ($queried && get_permalink($queried)) {
		$target = get_permalink($queried);
	}

	// Jetpack's brute-force protection asks for a small sum on every login. If the form comes without the answer it
	// shows an in-between page and the person has to log in all over again (and loses the "return to the dashboard"
	// part), so the question is part of this form.
	$math = '';
	$jp   = '\\Automattic\\Jetpack\\Waf\\Brute_Force_Protection\\Brute_Force_Protection_Math_Authenticate';
	if (class_exists($jp) && is_callable(array($jp, 'math_form'))) {
		ob_start();
		call_user_func(array($jp, 'math_form'));
		$math = (string) ob_get_clean();
	}
	$add_math = static function ($html) use ($math) {
		return $html . $math;
	};
	add_filter('login_form_middle', $add_math);

	$form = wp_login_form(array(
		'echo'           => false,
		'redirect'       => $target,
		'form_id'        => 'sty-od-loginform',
		'label_username' => $ar ? 'الإيميل أو اسم المستخدم' : 'Email or username',
		'label_password' => $ar ? 'كلمة المرور' : 'Password',
		'label_remember' => $ar ? 'فكّرني' : 'Remember me',
		'label_log_in'   => $ar ? 'دخول' : 'Log in',
		'remember'       => true,
	));
	remove_filter('login_form_middle', $add_math);

	return '<div class="sty-od-login"><h2>' . ($ar ? 'دخول لوحة التحكم' : 'Sign in to the dashboard') . '</h2>' . $form . '</div>';
}, 10, 2);
