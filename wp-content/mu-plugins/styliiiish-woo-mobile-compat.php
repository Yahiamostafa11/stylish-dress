<?php
/**
 * Plugin Name: Styliiiish Woo Mobile Compatibility
 * Description: Keeps WooCommerce mobile app authentication paths available.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'wp_is_application_passwords_available', '__return_true', PHP_INT_MAX );
add_filter( 'xmlrpc_enabled', '__return_true', PHP_INT_MAX );
