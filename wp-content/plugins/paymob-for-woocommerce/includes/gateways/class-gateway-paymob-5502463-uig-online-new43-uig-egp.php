<?php
class Paymob_5502463_UIG_online_new43_UIG_EGP_Gateway extends Paymob_Payment {

	public $id;
	public $method_title;
	public $method_description;
	public $has_fields;
	public function __construct() {
		$this->id                 = 'paymob-5502463-uig-online-new43-uig-egp';
		$this->method_title       = $this->title = __( 'Mobile Wallets', 'paymob-woocommerce' );
		$this->method_description = $this->description = __( 'Mobile Wallets', 'paymob-woocommerce' );
		parent::__construct();
		// config
		$this->init_settings();
	}
	public function admin_options() {
		PaymobAutoGenerate::gateways_method_title( $this->method_title, $this, $this->get_option( 'single_integration_id' ) );
	}
}
