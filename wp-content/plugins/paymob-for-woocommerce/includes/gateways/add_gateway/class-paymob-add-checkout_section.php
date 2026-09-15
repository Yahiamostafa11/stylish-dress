<?php

class Paymob_Checkout_Section
{

	public static function add_paymob_checkout_section($sections)
	{

		global $wpdb;
		$sections = array();
		$gateway_ids = array();
		$paymob_options = get_option('woocommerce_paymob-main_settings');
		$pub_key = isset($paymob_options['pub_key']) ? $paymob_options['pub_key'] : '';
		$sec_key = isset($paymob_options['sec_key']) ? $paymob_options['sec_key'] : '';
		$api_key = isset($paymob_options['api_key']) ? $paymob_options['api_key'] : '';
		$mode = "";
		$option_names = $wpdb->get_col("
			SELECT option_name 
			FROM {$wpdb->options} 
			WHERE option_name LIKE 'woocommerce_paymob-%valu%_settings'
		");

		if (!empty($option_names)) {
			foreach ($option_names as $option_name) {
				$option_value = get_option($option_name);
				
				// Ensure 'mode' exists in the array to prevent errors
				if (isset($option_value['mode']) && $option_value['mode'] == $paymob_options['mode']) {
					$mode = $option_value['mode'];
					break; // Stop looping if a match is found
				}
			}
		}
		$gateways = PaymobAutoGenerate::get_db_gateways_data();
		foreach ($gateways as $gateway) {
			$gateway_ids[] = $gateway->gateway_id;
		}
		if (
			Paymob::filterVar('section') && (in_array(Paymob::filterVar('section'), $gateway_ids, true) || 'paymob-main' === Paymob::filterVar('section') ||
				'paymob_add_gateway' === Paymob::filterVar('section') ||
				'paymob_list_gateways' === Paymob::filterVar('section') ||
				'paymob_pixel' === Paymob::filterVar('section')||
				'valu_widget' === Paymob::filterVar('section'))
		) {

			$sections = include PAYMOB_PLUGIN_PATH . 'includes/admin/paymob_checkout_sections.php';
		}
		return $sections; 
	}
}
