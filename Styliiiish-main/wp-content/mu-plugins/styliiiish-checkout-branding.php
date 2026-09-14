<?php
/**
 * Plugin Name: Styliiiish Checkout Branding
 * Description: Customers land here from the React storefront to pay for a
 * real order (see wc-order-bridge.php). The active theme's full storefront
 * chrome (topbar, promo bar, nav, category strip, footer widgets) looks like
 * a completely different, dated site right after the new React storefront —
 * jarring mid-checkout. This swaps the WHOLE page template for a minimal,
 * self-contained branded one on checkout/pay pages only (via template_include,
 * not by fighting theme-specific action hooks) — every other page is untouched.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('template_include', function (string $template): string {
    if (is_admin() || !function_exists('is_checkout') || !is_checkout()) {
        return $template;
    }

    return __DIR__ . '/styliiiish/checkout-template.php';
});
