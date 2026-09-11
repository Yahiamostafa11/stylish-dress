<?php
/**
 * Paymob Gateway Data
 */
class WC_Paymob_GatewayData
{

	public static function getPaymobGatewayData()
	{
		$gatewayData = get_option('woocommerce_paymob_gateway_data');
		$lastFailure = get_option('woocommerce_paymob_gateway_data_failure');

		// Only proceed if there's no cached data and no recent failure
		if (empty($gatewayData) && empty($lastFailure)) {
			$mainOptions = get_option('woocommerce_paymob-main_settings');
			if (!empty($mainOptions)) {
				$debug = isset($mainOptions['debug']) ? $mainOptions['debug'] : '';
				$debug = 'yes' === $debug ? '1' : '0';
				try {
					$paymobReq = new Paymob($debug, WC_LOG_DIR . 'paymob-auth.log');
					$conf['secKey'] = isset($mainOptions['sec_key']) ? $mainOptions['sec_key'] : '';
					$gatewayData = $paymobReq->getPaymobGateways($conf['secKey'], PAYMOB_PLUGIN_PATH . 'assets/img/');
					update_option('woocommerce_paymob_gateway_data', $gatewayData);
					delete_option( 'woocommerce_paymob_gateway_data_failure' );
				} catch (\Exception $e) {
					WC_Admin_Settings::add_error(__($e->getMessage(), 'paymob-woocommerce'));
					update_option('woocommerce_paymob_gateway_data_failure', current_time('timestamp')); // Record failure time
				}
			}
		}else {
			if (!empty($gatewayData)) {
				foreach ($gatewayData as $key => $gateway) {
					$logoPath = PAYMOB_PLUGIN_PATH . 'assets/img/' . strtolower($key) . '.png';
					// Skip downloading the logo if the logo URL is empty
					if (!empty($gateway['logo'])) {
						if (!file_exists($logoPath)) {
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_URL, $gateway['logo']);
							$data = curl_exec($ch);
							curl_close($ch);
							file_put_contents($logoPath, $data);
						}
					}
				}
			}
		}
	}
}