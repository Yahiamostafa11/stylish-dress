<?php
/**
 * Paymob WooCommerce Class
 */
class Paymob_WooCommerce {

	/**
	 * Constructor
	 */
	public $gateway;
	public $id;
	public $hmac_hidden;

	public function __construct( $id ) {
		$this->id      = $id;
		$this->gateway = ucwords( str_replace( '-', '_', $id ), '_' ) . '_Gateway';
		// filters
		add_filter( 'plugin_action_links_' . PAYMOB_PLUGIN, array( $this, 'add_plugin_links' ) );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'register' ), 0 );
		add_action( 'woocommerce_api_paymob_callback', array( $this, 'callback' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_enqueue_scripts' ) );
		add_action( 'admin_head', array( $this, 'hide_block_main_gateway' ) );
		$paymob_u_Options  = get_option( 'woocommerce_paymob_settings' );
		$this->hmac_hidden = isset( $paymob_u_Options['hmac_hidden'] ) ? sanitize_text_field( $paymob_u_Options['hmac_hidden'] ) : '';
	}

	/**
	 * Register the gateway to WooCommerce
	 */
	public function register( $gateways ) {
		include_once PAYMOB_PLUGIN_PATH . '/includes/gateways/class-paymob-payment.php';
		include_once PAYMOB_PLUGIN_PATH . '/includes/gateways/class-gateway-' .$this->id. '.php';
		if ( ! isset( $gateways[ $this->id ] ) ) {
			$gateways[] = $this->gateway;
		}
		return $gateways;
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return array
	 */
	public function add_plugin_links( $links ) {
		$paymobSetting = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=paymob-main' ) . '">' . __( 'PayMob Settings', 'paymob-woocommerce' ) . '</a>';
		$plugin_links  = array( __( 'Paymob Settings', 'paymob-woocommerce' ) => $paymobSetting );
		return ( array_merge( $links, $plugin_links ) );
	}

	public function callback() {
		$this->gateway = new Paymob_Gateway();
		if ( Paymob::filterVar( 'REQUEST_METHOD', 'SERVER' ) === 'POST' ) {
			$this->callWebhookAction();
		} elseif ( Paymob::filterVar( 'REQUEST_METHOD', 'SERVER' ) === 'GET' ) {
			$this->callReturnAction();
		}
	}

	public function callWebhookAction() {
		$post_data = file_get_contents( 'php://input' );
		$json_data = json_decode( $post_data, true );
		$country = Paymob::getCountryCode( $this->gateway->sec_key );
		$url     = Paymob::getApiUrl( $country );
	
	    if(isset( $json_data['subscription_data'] )){
            $this->subscriptionWebhook( $json_data, $url, $country);
		}
		elseif ( isset( $json_data['type'] ) && Paymob::filterVar( 'hmac', 'REQUEST' ) && 'TRANSACTION' === $json_data['type'] ) {
			$this->acceptWebhook( $json_data, $url );

		}
		elseif(isset( $json_data['type'] ) && $json_data['obj']['payment_key_claims']['subscription_plan_id'] && 'TRANSACTION' === $json_data['type']){
            $this->subscriptionTransactionWebhook( $json_data, $url, $country );
		}
		elseif ( isset( $json_data['type'] ) && 'TOKEN' === $json_data['type'] ) {
			$addlog          = WC_LOG_DIR  . 'paymob-token.log';
			Paymob::addLogs( $this->gateway->debug, $addlog, ' In TOKEN REQUEST >>>> ', wp_json_encode( $json_data ) );
			$this->saveCardToken( $json_data );

		} else {

			$this->flashWebhook( $json_data, $url, $country );
		}
	}

	public function acceptWebhook( $json_data, $url ) {
		$obj     = $json_data['obj'];
		$type    = $json_data['type'];
		$orderId = substr( $obj['order']['merchant_order_id'], 0, -11 );
		$merchant_order_id= $obj['order']['merchant_order_id'];
		if(strpos($orderId,'pixel') !== false){
			global $wpdb;
			$orderId = $wpdb->get_var(
				
				"SELECT  merchant_order_id FROM {$wpdb->prefix}paymob_pixel_intentions WHERE pixel_identifier ='" .$merchant_order_id."'"
		    );

		}
		if ( Paymob::verifyHmac( $this->hmac_hidden, $json_data, null, Paymob::filterVar( 'hmac', 'REQUEST' ) ) ) {

			$order           = wc_get_order( $orderId );
			$PaymobPaymentId = $order->get_meta( 'PaymobPaymentId', true );
			$addlog          = WC_LOG_DIR . $PaymobPaymentId . '.log';
			Paymob::addLogs( $this->gateway->debug, $addlog, ' In Webhook action, for order# ' . $orderId, wp_json_encode( $json_data ) );
			$order  = PaymobOrder::validateOrderInfo( $orderId, $PaymobPaymentId );
			$status = $order->get_status();

			if ( 'pending' != $status && 'failed' != $status && 'on-hold' != $status ) {
				die( esc_html( "can not change status of order: $orderId" ) );
			}

			$integrationId = $obj['integration_id'];
			$type          = $obj['source_data']['type'];
			$subType       = $obj['source_data']['sub_type'];
			$transaction   = $obj['id'];
			$paymobId      = $obj['order']['id'];

			$msg = __( 'Paymob  Webhook for Order #', 'paymob-woocommerce' ) . $orderId;
			if (
				true  === $obj['success'] &&
				false === $obj['is_voided'] &&
				false === $obj['is_refunded'] &&
				false === $obj['pending'] &&
				false === $obj['is_void'] &&
				false === $obj['is_refund'] &&
				false === $obj['error_occured']
			) {
				$note = __( 'Paymob  Webhook: Transaction Approved', 'paymob-woocommerce' );
				$msg  = $msg . ' ' . $note;
				Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
				Paymob::addLogs( $this->gateway->debug, $addlog, 'aml fares accept ');
				$note .= "<br/>Payment Method ID: { $integrationId } <br/>Transaction done by: { $type } / { $subType }</br> Transaction ID:  <b style='color:DodgerBlue;'>{ $transaction }</b></br> Order ID: <b style='color:DodgerBlue;'>{ $paymobId }</b> </br> <a href=' {$url} portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
				$order->add_order_note( $note );
				$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
				$order->add_order_note( $note2);
				// Handle CAF logic
				$this->update_order_total_after_discount( $order, $obj );
				$this->handle_caf_logic( $order, $json_data );
				$this->handle_instant_refund_logic( $order, $obj);
				$order->payment_complete( $orderId );


				$paymentMethod      = $order->get_payment_method();
				$paymentMethodTitle = 'Paymob - ' . ucwords( $type );
				$order->set_payment_method_title( $paymentMethodTitle );
			} else {
				$order->update_status( 'failed' );
				$note = __( 'Paymob Webhook: Payment is not completed ', 'paymob-woocommerce' );
				$msg  = $msg . ' ' . $note;
				Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
				$note .= "<br/>Payment Method ID: { $integrationId } <br/>Transaction done by: { $type } / { $subType }</br> Transaction ID:  <b style='color:DodgerBlue;'>{ $transaction }</b></br> Order ID: <b style='color:DodgerBlue;'>{ $paymobId }</b> </br> <a href=' {$url} portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
				$order->add_order_note( $note );
				$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
				$order->add_order_note( $note2);
			}
			$order->update_meta_data( 'PaymobTransactionId', $transaction );
			$order->update_meta_data( 'PaymobMerchantOrderID',$merchant_order_id);
			update_post_meta( $orderId, 'PaymobMerchantOrderID', $merchant_order_id );
            update_post_meta( $orderId, 'PaymobTransactionId', $transaction );

			$order->save();
			die( esc_html( "Order updated: $orderId" ) );
		} else {
			die( esc_html( "can not verify order: $orderId" ) );
		}
	}

	public function flashWebhook( $json_data, $url, $country ) {
		$orderId          = Paymob::getIntentionId( $json_data['intention']['extras']['creation_extras']['merchant_intention_id'] );
		$merchant_order_id=$json_data['intention']['extras']['creation_extras']['merchant_intention_id'];
		if(strpos($orderId,'pixel') !== false){
			global $wpdb;
			$orderId = $wpdb->get_var(
				
				"SELECT  merchant_order_id FROM {$wpdb->prefix}paymob_pixel_intentions WHERE pixel_identifier ='" .$merchant_order_id."'"
		   );

		}
		$order            = wc_get_order( $orderId );
		$OrderIntensionId = $order->get_meta( 'PaymobIntentionId', true );
		$OrderAmount      = $order->get_meta( 'PaymobCentsAmount', true );
		$PaymobPaymentId  = $order->get_meta( 'PaymobPaymentId', true );
		$addlog           = WC_LOG_DIR . $PaymobPaymentId . '.log';

		Paymob::addLogs( $this->gateway->debug, $addlog, ' In Webhook action, for order# ' . $orderId, wp_json_encode( $json_data ) );
	   
		if ( $OrderIntensionId != $json_data['intention']['id'] ) {
			die( esc_html( "intention ID is not matched for order: $orderId" ) );
		}

		if ( $OrderAmount != $json_data['intention']['intention_detail']['amount'] ) {
			die( esc_html( "intension amount are not matched for order : $orderId" ) );
		}

		$cents = 100;
		if ( 'omn' == $country ) {
			$cents = 1000;
		}
		if (
			! Paymob::verifyHmac(
				$this->hmac_hidden,
				$json_data,
				array(
					'id'     => $OrderIntensionId,
					'amount' => $OrderAmount,
					'cents'  => $cents,
				)
			)
		) {
			die( esc_html( "can not verify order: $orderId" ) );
		}

		$order  = PaymobOrder::validateOrderInfo( $orderId, $PaymobPaymentId );
		$status = $order->get_status();

		if ( 'pending' != $status && 'failed' != $status && 'on-hold' != $status ) {
			die( esc_html( "can not change status of order: $orderId" ) );
		}
		$msg = __( 'Paymob  Webhook for Order #', 'paymob-woocommerce' ) . $orderId;
		if ( ! empty( $json_data['transaction'] ) ) {
			$trans         = $json_data['transaction'];
			$integrationId = $json_data['transaction']['integration_id'];
			$type          = $json_data['transaction']['source_data']['type'];
			$subType       = $json_data['transaction']['source_data']['sub_type'];
			if (
				true === $trans['success'] &&
				false === $trans['is_voided'] &&
				false === $trans['is_refunded'] &&
				false === $trans['is_capture']
			) {
				$note = __( 'Paymob  Webhook: Transaction Approved', 'paymob-woocommerce' );
				$msg  = $msg . ' ' . $note;
				Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
				$transaction = $json_data['transaction']['id'];
				$paymobId    = $json_data['transaction']['order']['id'];
				$note       .= "<br/>Payment Method IDs: { $integrationId } <br/>Transaction done by: { $type } / { $subType }</br> Transaction ID:  <b style='color:DodgerBlue;'>{ $transaction }</b></br> Order ID: <b style='color:DodgerBlue;'>{ $paymobId }</b> </br> <a href=' {$url} portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
				$order->add_order_note( $note );
				$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
				$order->add_order_note( $note2);
				$this->update_order_total_after_discount( $order, $json_data );
				$this->handle_caf_logic( $order, $json_data);
				$this->handle_instant_refund_logic( $order, $json_data);
				


				$order->payment_complete( $orderId );
				$paymentMethod = $order->get_payment_method();

				$paymentMethodTitle = 'Paymob - ' . ucwords( $type );
				$order->set_payment_method_title( $paymentMethodTitle );

			} elseif (
				false === $trans['success'] &&
				true === $trans['is_refunded'] &&
				false === $trans['is_voided'] &&
				false === $trans['is_capture']
			) {
				$order->update_status( 'refunded' );
				$note = __( 'Paymob  Webhook: Payment Refunded', 'paymob-woocommerce' );
				$msg  = $msg . ' ' . $note;
				Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
				$order->add_order_note( $note );
			} elseif (
				false === $trans['success'] &&
				false === $trans['is_voided'] &&
				false === $trans['is_refunded'] &&
				false === $trans['is_capture']
			) {
				$order->update_status( 'failed' );
				$note = __( 'Paymob Webhook: Payment is not completed ', 'paymob-woocommerce' );
				$msg  = $msg . ' ' . $note;
				Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
				$transaction = $json_data['transaction']['id'];
				$paymobId    = $json_data['transaction']['order']['id'];
				$note       .= "<br/>Payment Method ID: { $integrationId } <br/>Transaction done by: { $type } / { $subType }</br> Transaction ID:  <b style='color:DodgerBlue;'>{ $transaction }</b></br> Order ID: <b style='color:DodgerBlue;'>{ $paymobId }</b> </br> <a href=' {$url} portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
				$order->add_order_note( $note );
				$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
				$order->add_order_note( $note2);
			}
			$order->update_meta_data( 'PaymobTransactionId', $transaction );
			$order->update_meta_data( 'PaymobMerchantOrderID',$merchant_order_id);
			update_post_meta( $orderId, 'PaymobMerchantOrderID', $merchant_order_id );
            update_post_meta( $orderId, 'PaymobTransactionId', $transaction );

			$order->save();
			die( esc_html( "Order updated: $orderId" ) );
		}
	}
	public function saveCardToken( $json_data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'paymob_cards_token';
		$obj        = $json_data['obj'];
		$addlog     = WC_LOG_DIR . 'paymob-auth.log';
		Paymob::addLogs( $this->gateway->debug, $addlog, ' In save Card Token Webhook , for User -- ' . $obj['email'], wp_json_encode( $json_data ) );
		$user = get_user_by( 'email', $obj['email'] );
		if ( $user ) {
			$token = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}paymob_cards_token WHERE user_id = %d AND card_subtype = %s AND masked_pan = %s",
					$user->ID,
					$obj['card_subtype'],
					$obj['masked_pan']
				),
				OBJECT
			);
			if ( ! $token ) {
				$wpdb->insert(
					$table_name,
					array(
						'user_id'      => $user->ID,
						'token'        => $obj['token'],
						'masked_pan'   => $obj['masked_pan'],
						'card_subtype' => $obj['card_subtype'],
					)
				);
			} else {
				$wpdb->update(
					$table_name,
					array(
						'token' => $obj['token'],
					),
					array(
						'user_id'      => $user->ID,
						'card_subtype' => $obj['card_subtype'],
						'masked_pan'   => $obj['masked_pan'],
					)
				);
			}
			die( esc_html( "Token Saved: user id: $user->ID, user email: " . $obj['email'] ) );
		} else {
			die( esc_html( 'No User Found with this email: ' . $obj['email'] ) );
		}
	}

	public function subscriptionTransactionWebhook( $json_data, $url, $country ) {
		$obj               = $json_data['obj'];
		$type              = $json_data['type'];
		$orderId           = Paymob::getIntentionId( $json_data['obj']['order']['merchant_order_id'] );
		$merchant_order_id = $json_data['obj']['order']['merchant_order_id'];
		
		$order            = wc_get_order( $orderId );
		$OrderIntensionId = $order->get_meta( 'PaymobIntentionId', true );
		$OrderAmount      = $order->get_meta( 'PaymobCentsAmount', true );
		$PaymobPaymentId  = $order->get_meta( 'PaymobPaymentId', true );
		$addlog           = WC_LOG_DIR . $PaymobPaymentId . '.log';

		Paymob::addLogs( $this->gateway->debug, $addlog, ' In Webhook action, for order# ' . $orderId, wp_json_encode( $json_data ) );
		$cents = 100;
		if ( 'omn' == $country ) {
			$cents = 1000;
		}
	  
		$order           = wc_get_order( $orderId );
		$PaymobPaymentId = $order->get_meta( 'PaymobPaymentId', true );
		$addlog          = WC_LOG_DIR . $PaymobPaymentId . '.log';
		Paymob::addLogs( $this->gateway->debug, $addlog, ' In Webhook action, for order# ' . $orderId, wp_json_encode( $json_data ) );
		$order  = PaymobOrder::validateOrderInfo( $orderId, $PaymobPaymentId );
		$status = $order->get_status();

		if ( 'pending' != $status && 'failed' != $status && 'on-hold' != $status ) {
			die( esc_html( "can not change status of order: $orderId" ) );
		}

		$integrationId = $obj['integration_id'];
		$type          = $obj['source_data']['type'];
		$subType       = $obj['source_data']['sub_type'];
		$transaction   = $obj['id'];
		$paymobId      = $obj['order']['id'];

		$msg = __( 'Paymob  Webhook for Order #', 'paymob-woocommerce' ) . $orderId;
		if (
			true ===  $obj['success'] &&
			false === $obj['is_voided'] &&
			false === $obj['is_refunded'] &&
			false === $obj['pending'] &&
			false === $obj['is_void'] &&
			false === $obj['is_refund'] &&
			false === $obj['error_occured']
		) {
			$note = __( 'Paymob  Webhook: Transaction Approved', 'paymob-woocommerce' );
			$msg  = $msg . ' ' . $note;
			Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
			$note .= "<br/>Payment Method ID: { $integrationId } <br/>Transaction done by: { $type } / { $subType }</br> Transaction ID:  <b style='color:DodgerBlue;'>{ $transaction }</b></br> Order ID: <b style='color:DodgerBlue;'>{ $paymobId }</b> </br> <a href=' {$url} portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
			$order->add_order_note( $note );
			$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
			$order->add_order_note( $note2);
			$order->payment_complete( $orderId );
			$paymentMethod      = $order->get_payment_method();
			$paymentMethodTitle = 'Paymob - ' . ucwords( $type );
			$order->set_payment_method_title( $paymentMethodTitle );
		} else {
			$order->update_status( 'failed' );
			$note = __( 'Paymob Webhook: Payment is not completed ', 'paymob-woocommerce' );
			$msg  = $msg . ' ' . $note;
			Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
			$note .= "<br/>Payment Method ID: { $integrationId } <br/>Transaction done by: { $type } / { $subType }</br> Transaction ID:  <b style='color:DodgerBlue;'>{ $transaction }</b></br> Order ID: <b style='color:DodgerBlue;'>{ $paymobId }</b> </br> <a href=' {$url} portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
			$order->add_order_note( $note );
			$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
			$order->add_order_note( $note2);
		}
		$order->update_meta_data( 'PaymobTransactionId', $transaction );
		$order->update_meta_data( 'PaymobMerchantOrderID',$merchant_order_id);
		update_post_meta( $orderId, 'PaymobMerchantOrderID', $merchant_order_id );
		update_post_meta( $orderId, 'PaymobTransactionId', $transaction );
		$order->save();
		die( esc_html( "Order updated: $orderId" ) );
	}

	public function subscriptionWebhook( $json_data, $url, $country ) {

		$subscription_data = $json_data['subscription_data'] ?? [];
		$trigger_type      = $json_data['trigger_type'] ?? '';
		$transaction_id    = intval( $json_data['transaction_id'] ?? 0 );
		$storedHmac        = $json_data['hmac'] ?? '';
		$is_subscription   = true;

		$normalized = strtolower( trim( $trigger_type ) );

		$is_success = ( $normalized === 'successful transaction' ||
		                $normalized ==="resumed"||
						$normalized ==="updated"||
						str_contains( $normalized, 'successful' ));

		$is_failed  = ( $normalized === 'failed transaction' ||
						$normalized === 'failed overdue transaction' ||
						$normalized === 'suspended' ||
						$normalized === 'canceled'||
					    str_contains( $normalized, 'failed' ) );

		$is_failed_payment = $is_failed;

		
		$next_billing_str = $subscription_data['next_billing'] ?? null;
		$next_billing     = $next_billing_str ? strtotime( $next_billing_str ) : 0;
		$today            = strtotime( date( 'Y-m-d' ) );

		// ===== LOG FILE =====
		$addlog = WC_LOG_DIR . 'paymob-subscription.log';

		Paymob::addLogs(
			$this->gateway->debug,
			$addlog,
			'Incoming Subscription Webhook',
			wp_json_encode( $json_data )
		);
		Paymob::addLogs(
			$this->gateway->debug,
			$addlog,
			'Trigger Evaluation',
			'normalized=' . $normalized .
			' | success=' . ( $is_success ? 'yes' : 'no' ) .
			' | failed=' . ( $is_failed_payment ? 'yes' : 'no' )
		);

		// ===== VERIFY HMAC =====
		if ( ! Paymob::verifyHmac( $this->hmac_hidden, $json_data, null, $storedHmac, $is_subscription ) ) {
			Paymob::addLogs( $this->gateway->debug, $addlog, 'Invalid HMAC – webhook rejected' );
			die( 'Invalid HMAC' );
		}

		// ===== TRANSACTION CHECK =====
		if ( empty( $transaction_id ) ) {
			Paymob::addLogs( $this->gateway->debug, $addlog, 'No transaction_id – skipping webhook' );
			die( 'No transaction id' );
		}

		if ( $this->paymob_renewal_exists( $transaction_id ) ) {
			Paymob::addLogs(
				$this->gateway->debug,
				$addlog,
				'Renewal already exists for transaction ' . $transaction_id
			);
			die( 'Renewal already exists' );
		}

		$initial_transaction = intval( $subscription_data['initial_transaction'] ?? 0 );
		if ( ! $initial_transaction || $initial_transaction === $transaction_id ) {
			Paymob::addLogs(
				$this->gateway->debug,
				$addlog,
				'Initial transaction webhook – no renewal needed'
			);
			die( 'Initial transaction' );
		}

		// ===== FIND ORIGINAL ORDER =====
		$orders = wc_get_orders([
			'limit'      => 1,
			'meta_key'   => 'PaymobTransactionId',
			'meta_value' => $initial_transaction,
			'return'     => 'objects',
		]);

		if ( empty( $orders ) ) {
			Paymob::addLogs(
				$this->gateway->debug,
				$addlog,
				'Original order not found for transaction ' . $initial_transaction
			);
			die( 'Original order not found' );
		}

		$order = $orders[0];

		Paymob::addLogs(
			$this->gateway->debug,
			$addlog,
			'Processing subscriptions for order #' . $order->get_id()
		);

		$subscriptions = wcs_get_subscriptions_for_order( $order, [ 'order_type' => 'any' ] );

		foreach ( $subscriptions as $subscription ) {

			Paymob::addLogs(
				$this->gateway->debug,
				$addlog,
				'Handling subscription #' . $subscription->get_id()
			);

			// ===== SUCCESSFUL PAYMENT =====
			if ( $is_success ) {

				Paymob::addLogs(
					$this->gateway->debug,
					$addlog,
					'Successful renewal detected – updating subscription & creating renewal order'
				);

				// Update dates ONLY on success
				$subscription->update_dates( array_filter([
					'start'        => ! empty( $subscription_data['starts_at'] )
						? gmdate( 'Y-m-d H:i:s', strtotime( $subscription_data['starts_at'] ) )
						: null,

					'next_payment' => ! empty( $subscription_data['next_billing'] )
						? gmdate( 'Y-m-d H:i:s', strtotime( $subscription_data['next_billing'] ) )
						: null,

					'end'          => ! empty( $subscription_data['ends_at'] )
						? gmdate( 'Y-m-d H:i:s', strtotime( $subscription_data['ends_at'] ) )
						: null,
				]) );

				$subscription->save();
				$renewal_order_id = $this->paymob_create_renewal_order(
					$subscription_data,
					$json_data,
					$subscription->get_id()
				);

				if ( $renewal_order_id ) {
					$subscription->add_order_note(
						'Paymob Renewal Successful. Order ID: ' . $renewal_order_id
					);

					Paymob::addLogs(
						$this->gateway->debug,
						$addlog,
						'Renewal order created: ' . $renewal_order_id
					);
				}

			// ===== FAILED PAYMENT =====
			} elseif ( $is_failed_payment ) {

				Paymob::addLogs(
					$this->gateway->debug,
					$addlog,
					'Failed renewal detected – creating failed order only'
				);

				$failed_order_id = $this->paymob_create_failed_renewal_order(
					$subscription_data,
					$json_data,
					$subscription->get_id()
				);

				if ( $failed_order_id ) {
					$subscription->add_order_note(
						'Paymob Renewal Failed. Failed Order ID: ' . $failed_order_id
					);

					Paymob::addLogs(
						$this->gateway->debug,
						$addlog,
						'Failed renewal order created: ' . $failed_order_id
					);
				}
			} else {
				Paymob::addLogs(
					$this->gateway->debug,
					$addlog,
					'Webhook ignored – trigger_type: ' . $trigger_type
				);
			}
		}

		Paymob::addLogs(
			$this->gateway->debug,
			$addlog,
			'Subscription webhook finished successfully'
		);

		die( 'Subscription webhook processed' );
	}

	function paymob_create_renewal_order( $subscription_data, $json_data, $subscription_id ) {

		$transaction_id = intval( $json_data['transaction_id'] );
		if ( $this->paymob_renewal_exists( $transaction_id ) ) {
			return false;
		}

		$subscription = wcs_get_subscription( $subscription_id );
		if ( ! $subscription ) {
			return false;
		}

		$renewal_order = wcs_create_renewal_order( $subscription );
		if ( ! $renewal_order ) {
			return false;
		}

		$total = $subscription_data['amount_cents'] / 100;

		foreach ( $renewal_order->get_items() as $item ) {
			$item->set_subtotal( $total );
			$item->set_total( $total );
			$item->save();
		}

		$renewal_order->set_total( $total );

		$renewal_order->update_meta_data( 'PaymobTransactionId', $transaction_id );
		$renewal_order->update_meta_data( '_paymob_is_renewal', 'yes' );

		$renewal_order->update_status( 'processing', 'Paymob renewal webhook' );
		$renewal_order->save();

		return $renewal_order->get_id();
	}

	private function paymob_renewal_exists( $transaction_id ) {
		$orders = wc_get_orders([
			'limit'      => 1,
			'meta_key'   => 'PaymobTransactionId',
			'meta_value' => $transaction_id,
			'return'     => 'ids',
		]);

		return ! empty( $orders );
	}

	// create a failed renewal order
	function paymob_create_failed_renewal_order( $subscription_data, $json_data, $subscription_id ) {

		$transaction_id = intval( $json_data['transaction_id'] );

		$subscription = wcs_get_subscription( $subscription_id );
		if ( ! $subscription ) {
			return false;
		}

		$renewal_order = wcs_create_renewal_order( $subscription );
		if ( ! $renewal_order ) {
			return false;
		}

		$total = $subscription_data['amount_cents'] / 100;

		foreach ( $renewal_order->get_items() as $item ) {
			$item->set_subtotal( $total );
			$item->set_total( $total );
			$item->save();
		}

		$renewal_order->set_total( $total );

		$renewal_order->update_meta_data( 'PaymobTransactionId', $transaction_id );
		$renewal_order->update_meta_data( '_paymob_is_renewal', 'yes' );

		// Temporarily remove the hook that sets status to on-hold
		remove_action( 'woocommerce_order_status_changed', 
			'WC_Subscriptions_Renewal_Order::maybe_record_subscription_payment', 10 );

		$renewal_order->update_status( 'failed', 'Paymob renewal failed webhook' );
		$renewal_order->save();

		// Re-add the hook
		add_action( 'woocommerce_order_status_changed', 
			'WC_Subscriptions_Renewal_Order::maybe_record_subscription_payment', 10, 3 );

		return $renewal_order->get_id();
	}

	public function callReturnAction() {
		
		$orderId         = Paymob::getIntentionId( Paymob::filterVar( 'merchant_order_id' ) );
		
		$merchant_order_id=Paymob::filterVar( 'merchant_order_id' );
		Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log',' --------- merchant order id '. $merchant_order_id );
		if(strpos($orderId,'pixel') !== false){
			global $wpdb;
			$orderId = $wpdb->get_var(
				
					"SELECT  merchant_order_id FROM {$wpdb->prefix}paymob_pixel_intentions WHERE pixel_identifier ='" .$merchant_order_id."'"
		     );
			
		}
		Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log', ' --------- order id'.$orderId );
		Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log', ' --------- GET'.print_r($_GET,1));
		Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log', ' --------- POST'.print_r($_POST,1));
		
		Paymob::addLogs( "1", WC_LOG_DIR . 'paymob-pixel.log', ' --------- errorrrrr'.Paymob::filterVar( 'errmsg' ) );
		$order           = wc_get_order( $orderId );
		
		if(!$order ){
			wp_safe_redirect(wc_get_checkout_url().'?gatewayerror='. __( 'Sorry, no order found. Please try again.', 'paymob-woocommerce' ));
			exit();
		}
		$amount_cents = Paymob::filterVar('amount_cents');
		$amount = $amount_cents / 100;

		if ( floatval( $order->get_total() ) == 0 && $amount > 0 ) {
			$order->set_total( $amount );
			$order->save();
		}
		
		if(Paymob::filterVar( 'errmsg' ) && Paymob::filterVar( 'errmsg' ) !=='undefined'){
			$error = Paymob::filterVar( 'errmsg' );
			$order->update_status( 'failed' );
			$order->add_order_note( 'Paymob :' . $error );
			$order->update_meta_data( 'PaymobMerchantOrderID',$merchant_order_id);
			update_post_meta( $orderId, 'PaymobMerchantOrderID', $merchant_order_id );

			$err = '?gatewayerror='.$error ;
			$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
			$order->add_order_note( $note2);
			$order->save();
			wp_safe_redirect(wc_get_checkout_url().$err);
			exit();
		}
		$PaymobPaymentId = $order->get_meta( 'PaymobPaymentId', true );
		$addlog          = WC_LOG_DIR . $PaymobPaymentId . '.log';

		if ( ! Paymob::verifyHmac( $this->hmac_hidden, Paymob::sanitizeVar() ) ) {
			$checkout_url = wc_get_checkout_url().'?gatewayerror='. __( 'Sorry, you are accessing wrong data due to mismatch verification.', 'paymob-woocommerce' );
			if(Paymob::filterVar( 'afterpayment' )){
				wp_send_json_success(array('url' => $checkout_url));
			}
			else{
				wp_safe_redirect( $checkout_url );
			}
			exit();
		}
		$err = null;
		Paymob::addLogs( $this->gateway->debug, $addlog, ' In Callback action, for order# ' . $orderId, wp_json_encode( Paymob::sanitizeVar() ) );

		$order         = PaymobOrder::validateOrderInfo( $orderId, $PaymobPaymentId );
		$country       = Paymob::getCountryCode( $this->gateway->sec_key );
		$url           = Paymob::getApiUrl( $country );
		$integrationId = Paymob::filterVar( 'integration_id' );
		$type          = Paymob::filterVar( 'source_data_type' );
		$subType       = Paymob::filterVar( 'source_data_sub_type' );
		$id            = Paymob::filterVar( 'id' );
		$paymobOrdr    = Paymob::filterVar( 'order' );
		$info          = "<br/>Payment Method ID: {$integrationId}<br/>Transaction done by: {$type} /  {$subType}</br>Transaction ID: <b style='color:DodgerBlue;'>{$id}</b> </br> Order ID:  <b style='color:DodgerBlue;'>{$paymobOrdr}</b></br><a href='{$url}portal2/en/transactions' target='_blank'>Visit Paymob Dashboard</a>";
		if (
			'true' === Paymob::filterVar( 'success' ) &&
			'false' === Paymob::filterVar( 'is_voided' ) &&
			'false' === Paymob::filterVar( 'is_refunded' )
		) {
			$status = $order->get_status();
			if ( 'pending' !== $status && 'failed' !== $status && 'on-hold' !== $status ) {
				$received_url=$order->get_checkout_order_received_url();
				if(Paymob::filterVar( 'afterpayment' )){
					wp_send_json_success(array('url' => $received_url));
				}else{
					wp_safe_redirect( $order->get_checkout_order_received_url() );
				}
				exit();
			}
			$note = __( 'Paymob : Transaction ', 'paymob-woocommerce' ) . Paymob::filterVar( 'data_message' );
			$msg  = __( 'In callback action, for order #', 'paymob-woocommerce' ) . ' ' . $orderId . ' ' . $note;
			Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
			$order->add_order_note( $note . $info );
			$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
			$order->add_order_note( $note2);
			$order->payment_complete( $orderId );
			$paymentMethod      = $order->get_payment_method();
			$paymentMethodTitle = 'Paymob - ' . ucwords( $type );
			$order->set_payment_method_title( $paymentMethodTitle );
			$redirect_url = $order->get_checkout_order_received_url();
		} else {
			$redirect_url = wc_get_checkout_url();
			$gatewayError = Paymob::filterVar( 'data_message' );
			$error        = __( 'Payment is not completed due to ', 'paymob-woocommerce' ) . $gatewayError;
			$msg          = __( 'In callback action, for order #', 'paymob-woocommerce' ) . ' ' . $orderId . ' ' . $error;
			Paymob::addLogs( $this->gateway->debug, $addlog, $msg );
			$order->update_status( 'failed' );
			$order->add_order_note( 'Paymob :' . $error . $info );
			$err = '?gatewayerror='.$error ;
			$note2= __( 'Paymob : Merchant Order ID Is ', 'paymob-woocommerce' ) . $merchant_order_id; 
			$order->add_order_note( $note2);
			$order->save();
		}
		$order->update_meta_data( 'PaymobTransactionId', $id ); 
		$order->update_meta_data( 'PaymobMerchantOrderID',$merchant_order_id);
		update_post_meta( $orderId, 'PaymobMerchantOrderID', $merchant_order_id );
        update_post_meta( $orderId, 'PaymobTransactionId', $id );

		$order->save();
		$existing_subscription_id = $order->get_meta('PaymobSubscriptionID');
		if ( empty( $existing_subscription_id ) ) {
			$this->TransactionSubscriptionID( $order, $id );
		}
	    WC()->session->set( 'cart', WC()->cart->get_cart() );
		WC()->session->set( 'chosen_shipping_methods', array() );
        WC()->session->set( 'chosen_payment_method', '' );
	    WC()->session->set( 'order_awaiting_payment', null );

		if(Paymob::filterVar( 'afterpayment' )){
			$session = WC()->session;     // Unset the order 
			$session->__unset('order_id');
   			wp_send_json_success(array('url' => $redirect_url.$err));
		}else{
			wp_safe_redirect( $redirect_url.$err );
		}
		
		exit();
	}
	public function add_enqueue_scripts() {

		Paymob_Style::paymob_enqueue();
	}

	public function hide_block_main_gateway() {

		Paymob_Style::hide_main_gateway_enqueue();
	}

	public function TransactionSubscriptionID($order, $transactionID) {

		$mainOptions = get_option('woocommerce_paymob-main_settings');
		$conf['apiKey'] = $mainOptions['api_key'] ?? '';
		$conf['pubKey'] = $mainOptions['pub_key'] ?? '';
		$conf['secKey'] = $mainOptions['sec_key'] ?? '';
		$PaymobPaymentId = $order->get_meta('PaymobPaymentId', true);
		$addlog = WC_LOG_DIR . $PaymobPaymentId . '.log';
		$paymobReq = new Paymob($this->debug, $this->addlog);
		// Get auth token
		$token = $paymobReq->authToken($conf);
		if (empty($token['token'])) {
			return ['error' => 'Unable to authenticate with Paymob.'];
		}
	
		// Get subscription data by transaction ID
		$response = $paymobReq->TransactionSubscriptionID($token['token'], $conf['secKey'], $transactionID);
		if (!empty($response->results) && is_array($response->results)) {
			$subscription = $response->results[0];
			$order->update_meta_data('PaymobSubscriptionID', $subscription->id);
			$order->save();
			return $subscription->id;
			
		}
		else{
			return false;
		}


	}

	public function updateSubscriptionamount($order,$subscription_total,$sub_id) {

		$mainOptions = get_option('woocommerce_paymob-main_settings');
		$conf['apiKey'] = $mainOptions['api_key'] ?? '';
		$conf['pubKey'] = $mainOptions['pub_key'] ?? '';
		$conf['secKey'] = $mainOptions['sec_key'] ?? '';
		$PaymobPaymentId = $order->get_meta('PaymobPaymentId', true);
		$addlog = WC_LOG_DIR . $PaymobPaymentId . '.log';
		$paymobReq = new Paymob($this->debug, $this->addlog);
		// Get auth token
		$token = $paymobReq->authToken($conf);
		if (empty($token['token'])) {
			return ['error' => 'Unable to authenticate with Paymob.'];
		}
		$country      = Paymob::getCountryCode( $conf['secKey']);
		$cents   = 100;
		$round   = 2;
		if ( 'omn' === $country ) {
			$round = 3;
			$cents = 1000;
		}
		
		$data = [
			'amount_cents' => round( $subscription_total, $round ) * $cents
		];
		//update subscription amount 
		$response = $paymobReq->updateSubscription($token['token'], $conf['secKey'],$data, $sub_id);
		return $response;
	}

	private function handle_caf_logic( WC_Order $order, $json_data ) {

		if ( $order->get_meta( '_paymob_caf_handled' ) ) {
			return;
		}

		if ( empty( $json_data['caf'] ) ) {
			// $order->add_order_note( 'Paymob CAF: Not applied' );
			$order->update_meta_data( '_paymob_caf_handled', 1 );
			$order->save();
			return;
		}

		$caf = $json_data['caf'];

		$note  = "<b>Paymob CAF Details</b><br/>";
		$note .= "CAF Applied: " . ( $caf['convenience_fee_applied'] ? 'Yes' : 'No' ) . "<br/>";
		$note .= "Initial Amount: " . wc_price( $caf['initial_order_amount'] / 100 ) . "<br/>";
		$note .= "CAF Amount: " . wc_price( $caf['convenience_fee_amount'] / 100 ) . "<br/>";
		$note .= "CAF Percentage: {$caf['convenience_fee_percentage']}%<br/>";
		$note .= "Transaction Amount: " . wc_price( $caf['transaction_amount'] / 100 ) . "<br/>";
		$note .= "Refund CAF Amount: " . ( $caf['refund_caf_amount'] ? 'Yes' : 'No' );

		if ( true === $caf['refund_caf_amount'] ) {
			$order->set_total( $caf['transaction_amount'] / 100 );
			$order->add_order_note(
				'Paymob CAF: Order total updated based on CAF refund logic.<br/>' . $note
			);
			$order->update_meta_data( 'paymob_caf_fee', $caf['convenience_fee_amount'] );
		} else {
			$order->add_order_note(
				'Paymob CAF applied (no order total change).<br/>' . $note
			);
		}

		$order->update_meta_data( 'paymob_caf_applied', $caf['convenience_fee_applied'] );
		
		$order->update_meta_data( '_paymob_caf_handled', 1 );
		$order->save();
	}

	private function handle_instant_refund_logic( WC_Order $order, $json_data ) {

		// Guard: instant refund not enabled
		if ( empty( $json_data['source_data']['instant_refund'] ) ) {
			return;
		}

		// Prevent duplicate notes (important for webhooks)
		if ( $order->get_meta( '_paymob_instant_refund_handled' ) ) {
			return;
		}

		$transaction = $json_data;

		// ✅ FIX: payment_key_claims is NOT inside transaction
		$extra = $json_data['payment_key_claims']['extra'] ?? [];

		$instant_refund         = (bool) $transaction['source_data']['instant_refund'];
		$instant_refund_applied = (bool) ( $extra['instant_refund_applied'] ?? false );
		$instant_refund_fees    = (int) ( $extra['instant_refund_fees'] ?? 0 );
		$original_amount_cents  = (int) ( $extra['original_amount_cents'] ?? 0 );

		$note  = '<b>Paymob Instant Refund</b><br/>';
		$note .= 'Instant Refund: Yes<br/>';
		$note .= 'Applied: ' . ( $instant_refund_applied ? 'Yes' : 'No' ) . '<br/>';

		if ( $original_amount_cents > 0 ) {
			$note .= 'Original Amount: ' . wc_price( $original_amount_cents / 100 ) . '<br/>';
		}

		if ( $instant_refund_fees > 0 ) {
			$note .= 'Instant Refund Fees: ' . wc_price( $instant_refund_fees / 100 ) . '<br/>';
		}

		$order->add_order_note( $note );

		// Save meta
		$order->update_meta_data( '_paymob_instant_refund', 1 );
		$order->update_meta_data( '_paymob_instant_refund_applied', $instant_refund_applied );
		$order->update_meta_data( '_paymob_instant_refund_fees', $instant_refund_fees );
		$order->update_meta_data( '_paymob_original_amount_cents', $original_amount_cents );
		$order->update_meta_data( '_paymob_instant_refund_handled', 1 );

		$order->save();
	}



	

	function paymob_add_fees_to_order_totals_display( $totals, $order ) {

		if ( ! $order instanceof WC_Order ) {
			return $totals;
		}

		$caf_fee             = (int) $order->get_meta( 'paymob_caf_fee' );
		$instant_refund_fees = (int) $order->get_meta( '_paymob_instant_refund_fees' );
		$discount_cents      = (int) $order->get_meta( '_paymob_discount_amount_cents' );

		// Nothing to show
		if ( $caf_fee <= 0 && $instant_refund_fees <= 0 && $discount_cents <= 0) {
			return $totals;
		}

		$new_totals = [];

		foreach ( $totals as $key => $total ) {

			$new_totals[ $key ] = $total;

			// Insert after shipping
			// if ( 'shipping' === $key ) {

				if ( $caf_fee > 0 ) {
					$new_totals['paymob_caf_fee'] = [
						'label' => __( 'Convenience Fee', 'paymob' ),
						'value' => wc_price( $caf_fee / 100 ),
					];
				}

				if ( $instant_refund_fees > 0 ) {
					$new_totals['paymob_instant_refund_fee'] = [
						'label' => __( 'Instant Refund Fee', 'paymob' ),
						'value' => wc_price( $instant_refund_fees / 100 ),
					];
				}

				if ( $discount_cents > 0 ) {
					$new_totals['paymob_discount'] = [
						'label' => __( 'Paymob Discount', 'paymob' ),
						'value' => wc_price( - $discount_cents / 100 ), // negative value
					];
				}
			// }
		}

		return $new_totals;
	}


	private function update_order_total_after_discount( WC_Order $order, $json_data ) {

		// Prevent duplicate execution
		if ( $order->get_meta( '_paymob_discount_applied' ) ) {
			return;
		}

		$discount_details = $json_data['discount_details'] ?? [];

		if ( empty( $discount_details[0]['discount_amount_cents'] ) ) {
			return;
		}

		$discount_cents  = (int) $discount_details[0]['discount_amount_cents'];
		$discount_amount = $discount_cents / 100;

		if ( $discount_amount <= 0 ) {
			return;
		}

		// Current order total
		$current_total = (float) $order->get_total();

		// New total after discount
		$new_total = max( 0, $current_total - $discount_amount );

		// ✅ Update total (subtract discount, not override)
		$order->set_total( $new_total );

		// Store meta for reference
		$order->update_meta_data( '_paymob_discount_amount_cents', $discount_cents );
		$order->update_meta_data( '_paymob_discount_applied', 1 );

		// Add order note (audit-safe)
		$order->add_order_note(
			sprintf(
				'Paymob Discount Applied: -%s (Old total: %s → New total: %s)',
				wc_price( $discount_amount ),
				wc_price( $current_total ),
				wc_price( $new_total )
			)
		);

		$order->save();
	}




}
