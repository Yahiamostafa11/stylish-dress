<?php

class Paymob_Save_Gateway_Settings {
	public static function save_paymob_add_gateway_settings() {
		global $current_section, $wpdb;

		if ( 'paymob_add_gateway' !== $current_section ) {
			return;
		}

		$paymob_options = get_option( 'woocommerce_paymob_settings' );
		$mainOptions = get_option('woocommerce_paymob-main_settings');
		$mode       = isset($mainOptions['mode']) ? $mainOptions['mode'] : 'test';

		$pub_key = isset( $paymob_options['pub_key'] ) ? $paymob_options['pub_key'] : '';
		$sec_key = isset( $paymob_options['sec_key'] ) ? $paymob_options['sec_key'] : '';
		$api_key = isset( $paymob_options['api_key'] ) ? $paymob_options['api_key'] : '';

		if ( empty( $pub_key ) || empty( $sec_key ) || empty( $api_key ) ) {
			WC_Admin_Settings::add_error( __( 'Please ensure you are entering API, public and secret keys in the main Paymob configuration.', 'paymob-woocommerce' ) );
		} else {
			$integration_id = Paymob::filterVar( 'integration_id', 'POST' ) ? sanitize_text_field( Paymob::filterVar( 'integration_id', 'POST' ) ) : '';
			$payment_enabled = Paymob::filterVar( 'payment_enabled', 'POST' ) ? 'yes' : 'no';
			$payment_integrations_type = Paymob::filterVar( 'payment_integrations_type', 'POST' ) ? sanitize_text_field( Paymob::filterVar( 'payment_integrations_type', 'POST' ) ) : '';
			$checkout_title = Paymob::filterVar( 'checkout_title', 'POST' ) ? sanitize_text_field( Paymob::filterVar( 'checkout_title', 'POST' ) ) : '';
			$checkout_description = Paymob::filterVar( 'checkout_description', 'POST' ) ? sanitize_text_field( Paymob::filterVar( 'checkout_description', 'POST' ) ) : '';

			$payment_integrations_type = 'paymob-' . preg_replace( '/[^a-zA-Z0-9]+/', '-', strtolower( $payment_integrations_type ) );
			$file_name = 'class-gateway-' .$payment_integrations_type. '.php';

			$gateway = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}paymob_gateways WHERE gateway_id = %s", $payment_integrations_type ), OBJECT );

			if ( ! $gateway && ! empty( $payment_integrations_type ) ) {
				$ordering = $wpdb->get_var( "SELECT max(ordering) FROM {$wpdb->prefix}paymob_gateways" );
				++$ordering;

				$inserted = $wpdb->insert(
					$wpdb->prefix . 'paymob_gateways',
					array(
						'gateway_id' => $payment_integrations_type,
						'file_name' => $file_name,
						'checkout_title' => sanitize_text_field( $checkout_title ),
						'checkout_description' => sanitize_text_field( $checkout_description ),
						'integration_id' => $integration_id,
						'is_manual' => '1',
						'ordering' => $ordering,
						'mode' => $mode
					)
				);

				if ( false !== $inserted ) {
					// Save default settings for the new gateway.
					$default_settings = array(
						'enabled' => $payment_enabled,
						'single_integration_id' => $integration_id,
						'title' => $checkout_title,
						'description' => $checkout_description,
					);
					update_option( 'woocommerce_' . $payment_integrations_type . '_settings', $default_settings );

					// Redirect to the list of gateways page.
					wp_safe_redirect( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=paymob_list_gateways' ) );
					exit;
				} else {
					WC_Admin_Settings::add_error( __( 'Failed to insert gateway into database.', 'paymob-woocommerce' ) );
				}
			} else {
				WC_Admin_Settings::add_error( __( 'Gateway Already Exists.', 'paymob-woocommerce' ) );
			}
		}
	}

	public static function save_paymob_valu_widget_settings() {
		global $current_section, $wpdb;
	
		if ('valu_widget' !== $current_section) {
			return;
		}
	
		// Get form data
		$payment_enabled = Paymob::filterVar('enable', 'POST') ? 'yes' : 'no';
		$integration_id  = Paymob::filterVar('integration_id', 'POST') ? sanitize_text_field(Paymob::filterVar('integration_id', 'POST')) : '';
		$dark_mode       = Paymob::filterVar('dark_mode', 'POST') ? 'yes' : 'no';
	
		// Check if the ValU Widget is enabled
		if ($payment_enabled !== 'yes') {
			// Redirect back with an error message
			wp_redirect(add_query_arg(array(
				'page'              => 'wc-settings',
				'tab'               => 'checkout',
				'section'           => 'valu_widget',
				'settings-error'    => 'valu_widget_disabled'
			), admin_url('admin.php')));
			exit;
		}
	
		// Save settings
		$default_settings = array(
			'enabled_widget'  => $payment_enabled,
			'integration_id'  => $integration_id,
			'dark_mode'       => $dark_mode
		);
	
		update_option('woocommerce_valu_widget_settings', $default_settings);
	
		// Redirect back with success message
		wp_redirect(add_query_arg(array(
			'page'              => 'wc-settings',
			'tab'               => 'checkout',
			'section'           => 'valu_widget',
			'settings-updated'  => 'true'
		), admin_url('admin.php')));
		exit;
	}

	public static function save_paymob_subscription_settings() {
		global $current_section, $wpdb;

		if ( 'paymob_subscription' !== $current_section ) {
			return;
		}

		$mainOptions = get_option('woocommerce_paymob-main_settings');
		$mode       = isset($mainOptions['mode']) ? $mainOptions['mode'] : 'test';

		// Get subscription settings
		$subscription_settings = Paymob::filterVar('woocommerce_paymob-subscription_settings', 'POST');
		$enabled = (!empty($subscription_settings['enabled']) && $subscription_settings['enabled'] === '1') ? 'yes' : 'no';
		$title        = !empty($subscription_settings['title']) ? sanitize_text_field($subscription_settings['title']) : 'Paymob Subscription';
		$description  = !empty($subscription_settings['description']) ? sanitize_text_field($subscription_settings['description']) : 'Recurring payment via Paymob.';
		$moto_id      = !empty($subscription_settings['moto_integration_id']) ? sanitize_text_field($subscription_settings['moto_integration_id']) : '';
		$threeds_ids  = !empty($subscription_settings['ds3_integration_ids']) ? sanitize_text_field($subscription_settings['ds3_integration_ids']) : '';
		$allow_cancel = (!empty($subscription_settings['allow_cancel']) && $subscription_settings['allow_cancel'] === '1')
		? 'yes'
		: 'no';

		if (empty($moto_id) || empty($threeds_ids)) {
			WC_Admin_Settings::add_error(__('Please select both MOTO and 3DS Integration IDs.', 'paymob-woocommerce'));
			return;
		}

		$gateway_id = 'paymob-subscription';

		// Insert into custom DB table if needed
		$exists = $wpdb->get_var($wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->prefix}paymob_gateways WHERE gateway_id = %s",
			$gateway_id
		));

		if (!$exists) {
			$ordering = $wpdb->get_var("SELECT MAX(ordering) FROM {$wpdb->prefix}paymob_gateways");
			$ordering++;

			$wpdb->insert("{$wpdb->prefix}paymob_gateways", array(
				'gateway_id'        => $gateway_id,
				'class_name'        => 'Paymob_Subscription',
				'file_name'         => 'class-gateway-paymob-subscription.php',
				'checkout_title'    => sanitize_text_field($title),
				'checkout_description' => sanitize_text_field($description),
				'integration_id'    => implode(',', (array)$threeds_ids),
				'is_manual'         => '1',
				'ordering'          => $ordering,
				'mode'              => $mode,
			));
		}

		// Save the WooCommerce gateway settings
		$default_settings = array(
			'enabled'                  => $enabled,
			'title'                    => $title,
			'description'              => $description,
			'moto_integration_id'      => $moto_id,
			'ds3_integration_ids'      => $threeds_ids,
			'allow_cancel'             => $allow_cancel,
		);

		update_option('woocommerce_paymob-subscription_settings', $default_settings);

		// Redirect back to the same section with success message
		wp_safe_redirect(admin_url('admin.php?page=wc-settings&tab=checkout&section=paymob_subscription&settings-updated=true'));
		exit;
	}

	
}

// ✅ Move this outside the class!
add_action('admin_notices', function () {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
        echo '<div class="updated notice is-dismissible"><p>Your settings have been saved.</p></div>';
    }
    
    if (isset($_GET['settings-error']) && $_GET['settings-error'] === 'valu_widget_disabled') {
        echo '<div class="notice notice-error is-dismissible"><p>You must enable the ValU Widget before saving settings.</p></div>';
    }
});


add_action('admin_footer', function () {
    // Show the message only on the ValU Widget settings page
    if (isset($_GET['page']) && $_GET['page'] === 'wc-settings' && isset($_GET['tab']) && $_GET['tab'] === 'checkout' && isset($_GET['section']) && $_GET['section'] === 'valu_widget') {
        $valu_integration_ids = PaymobAutoGenerate::get_valu_integration_ids();
        if (empty($valu_integration_ids)) {
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Create the overlay background
                    let overlay = document.createElement("div");
                    overlay.className = "valu-overlay";
                    overlay.style.position = "fixed";
                    overlay.style.top = "0";
                    overlay.style.left = "0";
                    overlay.style.width = "100%";
                    overlay.style.height = "100%";
                    overlay.style.background = "rgba(0, 0, 0, 0.3)"; // Semi-transparent background
                    overlay.style.zIndex = "999";

                    // Create the warning box
                    let warningBox = document.createElement("div");
                    warningBox.className = "valu-warning-box";
                    warningBox.innerHTML = `
                        <span class="valu-close-btn">×</span>
                        <strong>Please enable ValU Integration ID from the Payment Integrations section to use the ValU Widget!!</strong>
                    `;

                    // Style the warning box
                    warningBox.style.position = "fixed";
                    warningBox.style.top = "50%";
                    warningBox.style.left = "50%";
                    warningBox.style.transform = "translate(-50%, -50%)"; /* Centering */
                    warningBox.style.width = "450px";
                    warningBox.style.background = "#dff0d8"; /* Light green */
                    warningBox.style.color = "#3c763d";
                    warningBox.style.padding = "15px";
                    warningBox.style.borderRadius = "8px";
                    warningBox.style.border = "2px solid #3c763d"; /* Green border */
                    warningBox.style.textAlign = "center";
                    warningBox.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
                    warningBox.style.zIndex = "1000";

                    // Style the close button (X)
                    let closeButton = warningBox.querySelector(".valu-close-btn");
                    closeButton.style.position = "absolute";
                    closeButton.style.top = "5px";
                    closeButton.style.right = "10px";
                    closeButton.style.background = "transparent";
                    closeButton.style.color = "#3c763d"; /* Match border color */
                    closeButton.style.border = "none"; /* Remove circle */
                    closeButton.style.cursor = "pointer";
                    closeButton.style.fontSize = "20px"; /* Bigger close button */
                    closeButton.style.fontWeight = "bold";

                    // Close box and redirect when clicking X
                    closeButton.addEventListener("click", function () {
                        document.body.removeChild(overlay); // Remove overlay
                        window.location.href = "admin.php?page=wc-settings&tab=checkout&section=paymob_list_gateways"; // Redirect
                    });

                    // Append elements to the page
                    overlay.appendChild(warningBox);
                    document.body.appendChild(overlay);
                });
            </script>
            <?php
        }
    }
});

