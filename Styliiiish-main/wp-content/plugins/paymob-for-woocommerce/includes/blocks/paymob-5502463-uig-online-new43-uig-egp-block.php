<?php
if ( ! class_exists( 'WC_Paymob_5502463_UIG_online_new43_UIG_EGP_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_5502463_UIG_online_new43_UIG_EGP_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-5502463-uig-online-new43-uig-egp';
		}
	}

}
