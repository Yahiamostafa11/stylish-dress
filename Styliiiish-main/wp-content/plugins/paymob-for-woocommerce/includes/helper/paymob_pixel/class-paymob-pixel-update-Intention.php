<?php
class Paymob_Pixel_Update_Intention {
	public static function update_intention($order_id,$order)
	{
		$payment_method_id = $order->get_payment_method();
		// Retrieve plugin options
		$paymobOptions = get_option('woocommerce_paymob-main_settings', []);
		$secKey = $paymobOptions['sec_key'] ?? '';
		$debug = !empty($paymobOptions['debug']) ? '1' : '0';
		$log_file = WC_LOG_DIR . $payment_method_id . '.log';
		$intention_order_id = WC()->session->get('intention_order_id');
		$cs = WC()->session->get('cs');
		// Retrieve the order object
		
		$billing = [
			'email' => $order->get_billing_email(),
			'first_name' => $order->get_billing_first_name() ?: 'NA',
			'last_name' => $order->get_billing_last_name() ?: 'NA',
			'street' => $order->get_billing_address_1() . ' - ' . ($order->get_billing_address_2() ?: ''),
			'phone_number' => $order->get_billing_phone() ?: 'NA',
			'city' => $order->get_billing_city() ?: 'NA',
			'country' => $order->get_billing_country() ?: 'NA',
			'state' => $order->get_billing_state() ?: 'NA',
			'postal_code' => $order->get_billing_postcode() ?: 'NA',
		];

		// Calculate the amount in cents
		$country = Paymob::getCountryCode($secKey);
		$cents_multiplier = $country === 'omn' ? 1000 : 100;
		
		// Prepare data for Paymob API
		$data = [
			'accept_order_id' => $intention_order_id,
			'billing_data' => $billing,
			// 'special_reference' => $order_id . '_' . time(),
		];
        $final_total = WC()->session->get('paymob_final_total');
        $discount_value = WC()->session->get('paymob_discount');
        $amount = round($final_total , $country === 'omn' ? 3 : 2) * $cents_multiplier;
 
        if ($final_total && $final_total > 0) {
            // Update WooCommerce order total directly
            $data['amount'] = $amount;
            $order->set_total($final_total);
            $order->set_discount_total( $discount_value );
            $order->save();
        }
		
		// Send the request to Paymob
		$paymobReq = new Paymob($debug, $log_file);
		$response = $paymobReq->createIntention($secKey, $data, $order_id, $cs,'PUT');
		return $response;
	}
}
