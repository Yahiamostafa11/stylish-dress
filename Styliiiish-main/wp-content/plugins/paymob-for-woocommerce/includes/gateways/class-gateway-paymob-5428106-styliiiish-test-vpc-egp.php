<?php
class Paymob_5428106_Styliiiish_Test_VPC_EGP_Gateway extends Paymob_Payment {

	public $id;
	public $method_title;
	public $method_description;
	public $has_fields;
	public function __construct() {
		$this->id                 = 'paymob-5428106-styliiiish-test-vpc-egp';
		$this->method_title       = $this->title = __( 'Debit/Credit Card', 'paymob-woocommerce' );
		$this->method_description = $this->description = __( 'Secure Payment via Paymob Checkout', 'paymob-woocommerce' );
		parent::__construct();
		// config
		$this->init_settings();
	}
	public function admin_options() {
		PaymobAutoGenerate::gateways_method_title( $this->method_title, $this, $this->get_option( 'single_integration_id' ) );
	}
}
