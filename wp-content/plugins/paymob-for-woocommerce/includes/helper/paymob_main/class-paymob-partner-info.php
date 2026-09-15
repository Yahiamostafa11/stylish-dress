<?php

class Paymob_Main_Partner_Info
{

	public static function partner_info()
	{
		global $wpdb;
		if (
			!empty(Paymob::filterVar('woocode', 'GET'))
		) {
			$main_settings = get_option('woocommerce_paymob-main_settings', array());
			$testPubKey = isset($main_settings['test_pub_key']) ? sanitize_text_field($main_settings['test_pub_key']) : '';
			$livePubKey = isset($main_settings['live_pub_key']) ? sanitize_text_field($main_settings['live_pub_key']) : '';
			$testSecKey = isset($main_settings['test_sec_key']) ? sanitize_text_field($main_settings['test_sec_key']) : '';
			$livesecKey = isset($main_settings['live_sec_key']) ? sanitize_text_field($main_settings['live_sec_key']) : '';
			$apiKey = isset($paymobOptions['api_key']) ? sanitize_text_field($main_settings['api_key']) : '';
			$main_settings['enabled'] = isset($main_settings['enabled']) ? ($main_settings['enabled']) :'yes';

			// if (
			// 	!empty(Paymob::filterVar('woocode', 'GET'))
			// ) {
			// echo Paymob::filterVar('woocode', 'GET');exit;
			try {
				$woo_code = Paymob::filterVar('woocode', 'GET');
				$data = [
					'partner' => 'woocommerce',
					'clt' => Paymob_Main_Partner_Info::get_public_ip(),
				];
				// echo "<pre>";print_r($data);exit;
				// Paymob Request
				$paymobReq = new Paymob('1', WC_LOG_DIR . 'paymob-auth.log');
				$status = $paymobReq->getPartnerInfo($woo_code, $data);
				$status = (array) $status;

				if (!empty($status['api_key'])) {
					get_plugin_data(__FILE__);
					$currentURL = str_replace('amp;', '', esc_attr(self_admin_url(('admin.php?page=wc-settings&tab=checkout&section=paymob-main&popup=true'))));
					$encoded_url = urlencode($currentURL);
					// Replace with your desired custom URL
					if (isset($status['error']) || isset($status['detail'])) {
						wp_redirect('https://onboarding.paymob.com/auth/country-selection?partner=woocommerce&redirect_url=' . $encoded_url);
						exit;
					}

					$conf['apiKey'] = $main_settings['api_key'] = $status['api_key'];
					$conf['pubKey'] = $main_settings['pub_key'] = $status['is_live'] ? $status['pk_key_live'] : $status['pk_key_test'];
					$conf['secKey'] = $main_settings['sec_key'] = $status['is_live'] ? $status['sk_key_live'] : $status['sk_key_test'];

					$result = $paymobReq->authToken($conf);
					Paymob::addLogs('1', WC_LOG_DIR . 'paymob-auth.log', __('Merchant configuration: ', 'paymob-woocommerce'), $result);
					$gatewayData = $paymobReq->getPaymobGateways($main_settings['sec_key'], PAYMOB_PLUGIN_PATH . 'assets/img/');
					update_option('woocommerce_paymob_gateway_data', $gatewayData);

					if ($testPubKey !== $status['pk_key_test'] && $livePubKey !== $status['pk_key_live']) {
						Paymob_Unset_Old_Setting::unset_old_settings();
						$main_settings['enabled'] = 'yes';
					}

					update_option('woocommerce_paymob_country', Paymob::getCountryCode($main_settings['pub_key']));
					delete_option('woocommerce_paymob_gateway_data_failure');
					delete_option('woocommerce_valu_widget_settings');
					// Generate gateways 
					PaymobAutoGenerate::create_gateways($result, 1, $gatewayData);

					$ids = array();
					$main_integration_id_hidden = array();
					$integration_id_hidden = array();
					foreach ($result['integrationIDs'] as $value) {
                        $text = $value['id'] . ' : ' . $value['name'] . ' (' . $value['type'] . ' : ' . $value['currency'] . ' : ' . $value['mode'] . ' : ' . $value['is_moto'] . ' : ' . $value['is_3DS'] . ' )';
						$main_integration_id_hidden[] = $text . ',';
						if (isset($value['mode']) && $value['mode'] == $status['is_live'] ? 'live' : 'test') {
							$integration_id_hidden[] = $text . ',';
							$ids[] = trim($value['id']);
						}
						// Update webhook Url by default
						$webhhok = $paymobReq->getIntegrationID($conf, $value['id']);
						if (str_contains($webhhok->transaction_processed_callback, 'api/acceptance/post_pay')) {
							$data = array(
								'transaction_processed_callback' => add_query_arg(array('wc-api' => 'paymob_callback'), home_url()),
								'transaction_response_callback' => add_query_arg(array('wc-api' => 'paymob_callback'), home_url())
							);

							$paymobReq->updateWebHookUrl($conf, $value['id'], $data);
						}
					}
					// var_dump($ids);die;
					if (!empty($ids)) {
						$main_integration_id_hidden = implode("\n", $main_integration_id_hidden);
						$integration_id_hidden = implode("\n", $integration_id_hidden);
						$paymob_default_settings = array(
							'enabled' => 'no',
							'sec_key' => $conf['secKey'],
							'pub_key' => $conf['pubKey'],
							'api_key' => $conf['apiKey'],
							'title' => 'Pay with Paymob',
							'description' => 'Pay with Paymob',
							'integration_id' => $ids,
							'integration_id_hidden' => $integration_id_hidden,
							'hmac_hidden' => $result['hmac'],
							'empty_cart' => 'no',
							'debug' => 'no',
							'logo' => plugins_url(PAYMOB_PLUGIN_NAME) . '/assets/img/paymob.png',
						);
						$main_settings['integration_id_hidden'] = $main_integration_id_hidden;
						$main_settings['enabled'] = 'yes';
						update_option('woocommerce_paymob_settings', $paymob_default_settings);

						Paymob_Unset_Old_Setting::paymob_setting($status['is_live'] ? 'live' : 'test', $ids);
					} else {
						$wpdb->delete($wpdb->prefix . 'paymob_gateways', array('gateway_id' => 'paymob'));
					}

					$main_settings['test_pub_key'] = $status['pk_key_test'];
					$main_settings['live_pub_key'] = $status['pk_key_live'];

					$main_settings['test_sec_key'] = $status['sk_key_test'];
					$main_settings['live_sec_key'] = $status['sk_key_live'];
					$main_settings['mode'] = $status['is_live'] ? 'live' : 'test';
					$main_settings['debug'] = 'yes';
					update_option('woocommerce_paymob-main_settings', $main_settings);
					Paymob_Manual_Setup_Save::pixel_settings($main_settings['mode']);
					Paymob_Manual_Setup_Save::deactive_payment($result);
					return true;
				} else {
					// Redirect with error message
					$currentURL = self_admin_url('admin.php?page=wc-settings&tab=checkout&section=paymob-main&error-msg=an error has been occured, please try again');
					wp_redirect($currentURL);
					exit; // Always call exit after wp_redirect

				}

			} catch (\Exception $e) {
				WC_Admin_Settings::add_error(__($e->getMessage(), 'paymob-woocommerce'));
			}
		}
	}
	public static function get_public_ip()
	{
		$response = wp_remote_get('https://api.ipify.org?format=json');
		$response = json_decode(wp_remote_retrieve_body($response));
		return !empty($response->ip)?$response->ip : WC_Admin_Settings::add_error(__('Error while retrieving the IP.', 'paymob-woocommerce')) ;
	}
}
