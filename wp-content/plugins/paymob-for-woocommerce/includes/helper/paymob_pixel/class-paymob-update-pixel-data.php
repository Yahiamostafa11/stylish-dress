<?php
class Paymob_Update_Pixel_Data {
	public static function update_pixel_data() {
		// Verify AJAX request and get data
        check_ajax_referer('update_checkout', 'security');
        if (!Paymob::filterVar('billing_data', 'POST') || !Paymob::filterVar('total_amount', 'POST')) {
            wp_send_json_error('Missing data');
            return;
        }
        if (sizeof(Paymob_Saved_Cards_Tokens::getUserTokens()) > 3) {
			$url = wc_get_endpoint_url('saved-cards', '', get_permalink(wc_get_page_id('myaccount')));
			$url = '<a href="' . $url . '">'.esc_html( __( 'Paymob Saved Cards', 'paymob-woocommerce' ) ).'</a>';
			$url = esc_html( __( 'Please remove your cards from', 'paymob-woocommerce' ) ).' ' . $url . ' '.esc_html( __( 'to complete your purchase', 'paymob-woocommerce' ) );
			$msg = esc_html( __( 'Ops,Max number of card tokens is 3.', 'paymob-woocommerce' ) ).'<br>'.$url;
            wp_send_json_error($msg);
		} else {
            //echo "<pre>";print_r($_POST);exit;
            $paymobOptions = get_option('woocommerce_paymob-main_settings');
            $secKey = isset($paymobOptions['sec_key']) ? $paymobOptions['sec_key'] : '';
            $debug = isset($paymobOptions['debug']) ? sanitize_text_field($paymobOptions['debug']) : '';
            $debug = $debug ? '1' : '0';
            $addlog = WC_LOG_DIR . 'paymob-pixel.log';
            // Retrieve the updated billing data and amount
            $billing_data = wc_clean(Paymob::filterVar('billing_data', 'POST'));
            WC()->cart->calculate_totals();
            $total_amount = WC()->cart->get_total('edit');

            if(Paymob::filterVar('total_amount', 'POST')>= $total_amount){
              $total_amount=Paymob::filterVar('total_amount', 'POST');
            }
            // if($total_amount <=1){
            //     $msg = esc_html( __( 'Ops, can not create Paymob Embedded Payment with amount less than ', 'paymob-woocommerce' ) ).get_woocommerce_currency().' 1.<br>';
            //     wp_send_json_error($msg);
            // }
            $street = 'NA';

			if ( ! empty( $billing_data['address'] ) ) {
				$street = $billing_data['address'];
			} elseif ( ! empty( $billing_data['address_1'] ) ) {
				$street = $billing_data['address_1'];

				if ( ! empty( $billing_data['address_2'] ) ) {
					$street .= ' - ' . $billing_data['address_2'];
				}
			} elseif ( ! empty( $billing_data['address_2'] ) ) {
				$street = $billing_data['address_2'];
			}
            $billing = array(
                'email' => !empty($billing_data['email']) ? $billing_data['email'] : 'customer@example.com',
                'first_name' => !empty($billing_data['first_name']) ? $billing_data['first_name'] : 'NA',
                'last_name' => !empty($billing_data['last_name']) ? $billing_data['last_name'] : 'NA',
                'street'   => $street,
                'phone_number' => ($billing_data['phone']) ? $billing_data['phone'] : 'NA',
                'city' => !empty($billing_data['city']) ? $billing_data['city'] : 'NA',
                'country' => !empty($billing_data['country']) ? $billing_data['country'] : 'NA',
                'state' => !empty($billing_data['state']) ? $billing_data['state'] : 'NA',
                'postal_code' => !empty($billing_data['postcode']) ? $billing_data['postcode'] : 'NA',
            );
            $country = Paymob::getCountryCode($secKey);
            $cents = 100;
            $round = 2;
            if ('omn' == $country) {
                $round = 3;
                $cents = 1000;
            }
        
           // $amount =  intval( round($total_amount, $round) * $cents);
            $amount = intval( round( (float) $total_amount, (int) $round ) * (int) $cents );
        //    var_dump($amount);die;
            $paymobReq = new Paymob($debug, $addlog);
            $session_id = session_id() ?: uniqid('', true); 
            $pixel_identifier = 'pixel_' . $session_id . '_' . time(); 
            
         
            $data = array(
                'amount' => $amount,
                'currency' => get_woocommerce_currency(),
                'payment_methods' => self::getIntegrationIds(), // Define this function accordingly
                'billing_data' => $billing,
                'extras'            => array( 'merchant_intention_id' => $pixel_identifier ),
				'special_reference' => $pixel_identifier,
            );
            // var_dump($data);die;
            $data['card_tokens'] = Paymob_Saved_Cards_Tokens::getUserTokens();
            $status = $paymobReq->createIntention($secKey, $data, 'Loading Pixel',$cs='','POST');
            if(empty($status['cs'])){
                if(!empty($status['message'])){
                    $errorMsg = $status['message'];
                    wp_send_json_error($errorMsg);
                }else{
                    wp_send_json_error(__('Error in creating Paymob Intension, please try again.','paymob-woocommerce'));
                }
            }

            $session=WC()->session;
            $session->__unset('cs');
            $session->__unset('pixel_identifier');
            $session->__unset('PaymobIntentionId');
            $session->__unset('PaymobCentsAmount');
            /////////////////

            WC()->session->set('cs', $status['cs']);
            WC()->session->set('pixel_identifier',   $pixel_identifier);
            WC()->session->set('PaymobIntentionId',  $status['intentionId']);
            WC()->session->set('PaymobCentsAmount',  $status['centsAmount']);
            Paymob_Pixel_Checkout::create_paymob_intention_and_insert($status['cs'],$pixel_identifier);

            WC()->session->set('intention_order_id', $status['intention_order_id']);
            WC()->session->set('cs', $status['cs']);
            wp_send_json_success($status);
        }
	}
    public static function getIntegrationIds()
    {

        $pixelOptions = get_option('woocommerce_paymob-pixel_settings');

        $integration_ids = array();
        $cards_integration_id = isset($pixelOptions['cards_integration_id']) ? $pixelOptions['cards_integration_id'] : '';
        $apple_pay_integration_id = isset($pixelOptions['apple_pay_integration_id']) ? $pixelOptions['apple_pay_integration_id'] : '';
        $google_pay_integration_id = isset($pixelOptions['google_pay_integration_id']) ? $pixelOptions['google_pay_integration_id'] : '';


        if (!empty($cards_integration_id)) {
            foreach ($cards_integration_id as $id) {
                $id = (int) $id;
                if ($id > 0) {
                    array_push($integration_ids, $id);
                }
            }
        }

        if (!empty($apple_pay_integration_id)) {
            $integration_ids[] = (int) $apple_pay_integration_id;
        }
        if (!empty($google_pay_integration_id)) {
            $integration_ids[] = (int) $google_pay_integration_id;
        }

        return $integration_ids;
    }
}

