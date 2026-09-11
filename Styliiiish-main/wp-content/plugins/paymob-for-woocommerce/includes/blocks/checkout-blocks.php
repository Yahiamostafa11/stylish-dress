<?php

class Checkout_Blocks {

	public function __construct() {
		// Load WooCommerce paymob_block.
		add_action( 'woocommerce_blocks_loaded', array( $this, 'paymob_woocommerce_block_support' ) );
	}
	public function paymob_woocommerce_block_support() {
		global $wpdb;

		if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) && class_exists( 'Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry' ) ) {

			foreach ( glob( PAYMOB_PLUGIN_PATH . 'includes/blocks/' . '*-block.php' ) as $filename ) {
				require_once $filename;
			}

			$gateways = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'paymob_gateways', OBJECT );
			foreach ( $gateways as $gateway ) {
				$gateway_class = 'WC_' . $gateway->class_name . '_Blocks';

				add_action(
					'woocommerce_blocks_payment_method_type_registration',
					function ( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) use ( $gateway_class ) {
						$container = Automattic\WooCommerce\Blocks\Package::container();
						$container->register(
							$gateway_class,
							function () use ( $gateway_class ) {
								return new $gateway_class();
							}
						);
						$payment_method_registry->register( $container->get( $gateway_class ) );
					}
				);
			}
		} else {
			// Debugging output
			error_log( 'WooCommerce Blocks classes not found.' );
		}
	}
}

new Checkout_Blocks();
