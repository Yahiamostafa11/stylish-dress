<?php
if ( ! class_exists( 'WC_Paymob_5428106_Styliiiish_Test_VPC_EGP_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_5428106_Styliiiish_Test_VPC_EGP_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-5428106-styliiiish-test-vpc-egp';
		}
	}

}
