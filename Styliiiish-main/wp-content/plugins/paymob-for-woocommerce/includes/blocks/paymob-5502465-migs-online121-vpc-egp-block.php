<?php
if ( ! class_exists( 'WC_Paymob_5502465_MIGS_online121_VPC_EGP_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_5502465_MIGS_online121_VPC_EGP_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-5502465-migs-online121-vpc-egp';
		}
	}

}
