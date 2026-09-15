<?php
if ( ! class_exists( 'WC_Paymob_5447696_CAGG_online_CAGG_EGP_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_5447696_CAGG_online_CAGG_EGP_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-5447696-cagg-online-cagg-egp';
		}
	}

}
