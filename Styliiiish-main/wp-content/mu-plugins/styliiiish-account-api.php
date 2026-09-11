<?php
/**
 * Plugin Name: Styliiiish Account API
 * Description: Custom REST API endpoints for Laravel My Account page.
 */

if (!defined('ABSPATH')) {
    exit;
}

function styliiiish_resolve_account_user_id(): int {
    if (is_user_logged_in()) {
        return (int) get_current_user_id();
    }

    if (!defined('LOGGED_IN_COOKIE') || empty($_COOKIE[LOGGED_IN_COOKIE])) {
        return 0;
    }

    $cookie = wp_unslash((string) $_COOKIE[LOGGED_IN_COOKIE]);
    $user_id = (int) wp_validate_auth_cookie($cookie, 'logged_in');
    if ($user_id <= 0) {
        return 0;
    }

    wp_set_current_user($user_id);
    return $user_id;
}

/**
 * Hosts that are allowed to originate a call to these endpoints.
 *
 * @return string[] lowercase hostnames
 */
function styliiiish_account_allowed_origins(): array {
    $hosts = [];

    foreach ([ home_url(), site_url(), 'https://' . (string) ($_SERVER['HTTP_HOST'] ?? '') ] as $candidate) {
        $host = strtolower((string) parse_url((string) $candidate, PHP_URL_HOST));
        if ($host !== '') {
            $hosts[$host] = true;
        }
    }

    /**
     * Add any extra front-end host that legitimately calls the account API
     * (for example a separate Laravel hostname).
     *
     * @param string[] $hosts
     */
    $extra = apply_filters('styliiiish_account_api_allowed_hosts', []);
    foreach ((array) $extra as $candidate) {
        $host = strtolower(trim((string) $candidate));
        if ($host !== '') {
            $hosts[$host] = true;
        }
    }

    return array_keys($hosts);
}

/**
 * CSRF guard.
 *
 * WHY: these endpoints authenticate from the LOGGED_IN cookie alone (see
 * styliiiish_resolve_account_user_id). When a request carries no REST nonce, WordPress
 * deliberately drops the current user to 0 and lets the request through as anonymous —
 * so re-reading the raw cookie here re-authenticates the victim with no CSRF token in
 * play. Without this guard, a third-party page could POST to /account/details with the
 * victim's cookies attached and change their email address, which is account takeover
 * via the password-reset flow.
 *
 * TWO CHECKS, both cheap:
 *
 *  1. A custom request header is required. An HTML <form> cannot set one, and a
 *     cross-origin fetch that sets one triggers a CORS preflight that we never answer.
 *     This alone defeats both classic CSRF shapes.
 *  2. When Origin or Referer is present it must be one of our own hosts. Same-origin
 *     browsers always send Origin on POST/DELETE, so this costs nothing legitimate.
 *
 * A valid WordPress REST nonce is accepted as an alternative proof, so first-party
 * WordPress callers keep working unchanged.
 */
function styliiiish_account_request_is_same_origin(): bool {
    $allowed = styliiiish_account_allowed_origins();

    foreach (['HTTP_ORIGIN' => PHP_URL_HOST, 'HTTP_REFERER' => PHP_URL_HOST] as $key => $part) {
        if (empty($_SERVER[$key])) {
            continue;
        }

        $host = strtolower((string) parse_url((string) wp_unslash($_SERVER[$key]), $part));

        // Present but unparseable or foreign -> reject.
        if ($host === '' || !in_array($host, $allowed, true)) {
            return false;
        }
    }

    return true;
}

function styliiiish_account_csrf_ok(): bool {
    if (!styliiiish_account_request_is_same_origin()) {
        return false;
    }

    // A valid REST nonce is proof enough on its own.
    $nonce = $_SERVER['HTTP_X_WP_NONCE'] ?? ($_REQUEST['_wpnonce'] ?? '');
    if (is_string($nonce) && $nonce !== '' && wp_verify_nonce(wp_unslash($nonce), 'wp_rest')) {
        return true;
    }

    // Otherwise require the custom header. Unforgeable by <form> and blocked by preflight.
    return !empty($_SERVER['HTTP_X_STYLIIIISH_ACCOUNT']);
}

function styliiiish_account_permission_check() {
    if (!styliiiish_account_csrf_ok()) {
        return new WP_Error(
            'styliiiish_csrf_failed',
            'Request rejected: missing same-origin verification.',
            ['status' => 403]
        );
    }

    if (styliiiish_resolve_account_user_id() <= 0) {
        return new WP_Error('not_authenticated', 'Authentication required.', ['status' => 401]);
    }

    return true;
}

add_action('rest_api_init', function () {
    register_rest_route('styliiiish/v1', '/account', [
        'methods' => 'GET',
        'callback' => 'styliiiish_get_account_data',
        'permission_callback' => 'styliiiish_account_permission_check'
    ]);

    register_rest_route('styliiiish/v1', '/account/details', [
        'methods' => 'POST',
        'callback' => 'styliiiish_update_account_details',
        'permission_callback' => 'styliiiish_account_permission_check'
    ]);

    register_rest_route('styliiiish/v1', '/account/addresses', [
        'methods' => 'POST',
        'callback' => 'styliiiish_update_account_addresses',
        'permission_callback' => 'styliiiish_account_permission_check'
    ]);

    register_rest_route('styliiiish/v1', '/account/paymob-card/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'styliiiish_delete_paymob_card',
        'permission_callback' => 'styliiiish_account_permission_check'
    ]);
});

function styliiiish_get_account_data() {
    $user_id = styliiiish_resolve_account_user_id();
    if ($user_id <= 0) {
        return new WP_Error('not_authenticated', 'Authentication required.', ['status' => 401]);
    }
    $user = get_userdata($user_id);
    $customer = new WC_Customer($user_id);

    $details = [
        'first_name' => $customer->get_first_name(),
        'last_name' => $customer->get_last_name(),
        'display_name' => $customer->get_display_name(),
        'email' => $customer->get_email(),
    ];

    $addresses = [
        'billing' => $customer->get_billing(),
        'shipping' => $customer->get_shipping(),
    ];

    $orders_data = [];
    $orders = wc_get_orders([
        'customer' => $user_id,
        'limit' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    foreach ($orders as $order) {
        $orders_data[] = [
            'id' => $order->get_id(),
            'order_number' => $order->get_order_number(),
            'date' => $order->get_date_created()->date('Y-m-d'),
            'status' => wc_get_order_status_name($order->get_status()),
            'status_raw' => $order->get_status(),
            'total' => $order->get_formatted_order_total(),
            'item_count' => $order->get_item_count(),
            'view_url' => $order->get_view_order_url(),
        ];
    }

    global $wpdb;
    $cards = [];
    $table_name = $wpdb->prefix . 'paymob_cards_token';
    if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) === $table_name) {
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d", $user_id));
        foreach ($results as $row) {
            $cards[] = [
                'id' => $row->id,
                'masked_pan' => $row->masked_pan,
                'card_subtype' => $row->card_subtype,
            ];
        }
    }

    return rest_ensure_response([
        'success' => true,
        'data' => [
            'details' => $details,
            'addresses' => $addresses,
            'orders' => $orders_data,
            'paymob_cards' => $cards,
        ]
    ]);
}

function styliiiish_update_account_details($request) {
    $user_id = styliiiish_resolve_account_user_id();
    if ($user_id <= 0) {
        return new WP_Error('not_authenticated', 'Authentication required.', ['status' => 401]);
    }
    $customer = new WC_Customer($user_id);

    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));
    $display_name = sanitize_text_field($request->get_param('display_name'));
    $email = sanitize_email($request->get_param('email'));

    $customer->set_first_name($first_name);
    $customer->set_last_name($last_name);
    $customer->set_display_name($display_name);
    
    if (is_email($email) && (email_exists($email) == false || email_exists($email) == $user_id)) {
        $customer->set_email($email);
    }

    $password_current = $request->get_param('password_current');
    $password_1 = $request->get_param('password_1');
    $password_2 = $request->get_param('password_2');

    if (!empty($password_1)) {
        if (empty($password_current)) {
            return new WP_Error('missing_current_password', 'Please enter your current password.', ['status' => 400]);
        }
        $user = get_userdata($user_id);
        if (!wp_check_password($password_current, $user->user_pass, $user_id)) {
            return new WP_Error('invalid_current_password', 'Current password is incorrect.', ['status' => 400]);
        }
        if ($password_1 !== $password_2) {
            return new WP_Error('password_mismatch', 'New passwords do not match.', ['status' => 400]);
        }
        wp_set_password($password_1, $user_id);
        wp_set_auth_cookie($user_id);
    }

    $customer->save();
    return rest_ensure_response(['success' => true, 'message' => 'Account details updated successfully.']);
}

function styliiiish_update_account_addresses($request) {
    $user_id = styliiiish_resolve_account_user_id();
    if ($user_id <= 0) {
        return new WP_Error('not_authenticated', 'Authentication required.', ['status' => 401]);
    }
    $customer = new WC_Customer($user_id);
    $type = sanitize_text_field($request->get_param('type'));

    if (!in_array($type, ['billing', 'shipping'])) {
        return new WP_Error('invalid_type', 'Invalid address type.', ['status' => 400]);
    }

    $address = $request->get_param('address');
    if (!is_array($address)) {
        return new WP_Error('invalid_data', 'Invalid address data.', ['status' => 400]);
    }

    if ($type === 'billing') {
        $customer->set_billing_first_name(sanitize_text_field($address['first_name'] ?? ''));
        $customer->set_billing_last_name(sanitize_text_field($address['last_name'] ?? ''));
        $customer->set_billing_company(sanitize_text_field($address['company'] ?? ''));
        $customer->set_billing_country(sanitize_text_field($address['country'] ?? ''));
        $customer->set_billing_address_1(sanitize_text_field($address['address_1'] ?? ''));
        $customer->set_billing_address_2(sanitize_text_field($address['address_2'] ?? ''));
        $customer->set_billing_city(sanitize_text_field($address['city'] ?? ''));
        $customer->set_billing_state(sanitize_text_field($address['state'] ?? ''));
        $customer->set_billing_postcode(sanitize_text_field($address['postcode'] ?? ''));
        $customer->set_billing_phone(sanitize_text_field($address['phone'] ?? ''));
        $customer->set_billing_email(sanitize_email($address['email'] ?? ''));
    } else {
        $customer->set_shipping_first_name(sanitize_text_field($address['first_name'] ?? ''));
        $customer->set_shipping_last_name(sanitize_text_field($address['last_name'] ?? ''));
        $customer->set_shipping_company(sanitize_text_field($address['company'] ?? ''));
        $customer->set_shipping_country(sanitize_text_field($address['country'] ?? ''));
        $customer->set_shipping_address_1(sanitize_text_field($address['address_1'] ?? ''));
        $customer->set_shipping_address_2(sanitize_text_field($address['address_2'] ?? ''));
        $customer->set_shipping_city(sanitize_text_field($address['city'] ?? ''));
        $customer->set_shipping_state(sanitize_text_field($address['state'] ?? ''));
        $customer->set_shipping_postcode(sanitize_text_field($address['postcode'] ?? ''));
    }

    $customer->save();
    return rest_ensure_response(['success' => true, 'message' => ucfirst($type) . ' address updated successfully.']);
}

function styliiiish_delete_paymob_card($request) {
    $user_id = styliiiish_resolve_account_user_id();
    if ($user_id <= 0) {
        return new WP_Error('not_authenticated', 'Authentication required.', ['status' => 401]);
    }
    $card_id = (int) $request->get_param('id');

    global $wpdb;
    $table_name = $wpdb->prefix . 'paymob_cards_token';
    
    $deleted = $wpdb->delete($table_name, ['id' => $card_id, 'user_id' => $user_id], ['%d', '%d']);

    if ($deleted) {
        return rest_ensure_response(['success' => true, 'message' => 'Card deleted successfully.']);
    }

    return new WP_Error('delete_failed', 'Could not delete card or card not found.', ['status' => 400]);
}

