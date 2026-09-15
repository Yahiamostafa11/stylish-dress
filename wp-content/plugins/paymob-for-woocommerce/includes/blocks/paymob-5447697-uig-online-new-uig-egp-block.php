<?php
if ( ! class_exists( 'WC_Paymob_5447697_UIG_online_new_UIG_EGP_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_5447697_UIG_online_new_UIG_EGP_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-5447697-uig-online-new-uig-egp';
		}
	}

}
