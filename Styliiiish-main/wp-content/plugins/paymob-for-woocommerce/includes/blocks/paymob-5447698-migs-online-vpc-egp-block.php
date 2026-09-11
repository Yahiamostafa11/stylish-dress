<?php
if ( ! class_exists( 'WC_Paymob_5447698_MIGS_online_VPC_EGP_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_5447698_MIGS_online_VPC_EGP_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-5447698-migs-online-vpc-egp';
		}
	}

}
