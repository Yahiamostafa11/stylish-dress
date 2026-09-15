<?php
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * ===========================================================
 *  ADD "Sell Your Dress" Endpoint to WooCommerce My Account
 * ===========================================================
 */

/* 1) Register endpoint */
add_action('init', 'wf_mp_register_sell_endpoint');
function wf_mp_register_sell_endpoint() {
    add_rewrite_endpoint('sell-your-dress', EP_ROOT | EP_PAGES);
}

/* 2) Add link in My Account menu */
add_filter('woocommerce_account_menu_items', 'wf_mp_add_sell_menu_item');
function wf_mp_add_sell_menu_item($items) {

    // لو الماركت بليس مش متفعل → ممنوع يظهر اللينك
    if ( ! websiteflexi_is_marketplace_enabled() ) {
        return $items;
    }

    // نضيف اللينك قبل Logout
    $logout = $items['customer-logout'];
    unset($items['customer-logout']);

    $items['sell-your-dress'] = 'Sell Your Dress';

    $items['customer-logout'] = $logout;

    return $items;
}

/* 3) Content Callback */
add_action('woocommerce_account_sell-your-dress_endpoint', 'wf_mp_sell_your_dress_content');
function wf_mp_sell_your_dress_content() {

    if (!is_user_logged_in()) {
        echo "<p>You must be logged in to use this feature.</p>";
        return;
    }

    if ( ! websiteflexi_is_marketplace_enabled() ) {
        echo "<p>The marketplace is disabled by the website owner.</p>";
        return;
    }

    // هنعمل شورت كود لإضافة المنتج
    echo do_shortcode('[sell_your_dress]');
}

