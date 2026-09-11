<?php
class Paymob_5447696_CAGG_online_CAGG_EGP_Gateway extends Paymob_Payment {

	public $id;
	public $method_title;
	public $method_description;
	public $has_fields;
	public function __construct() {
		$this->id                 = 'paymob-5447696-cagg-online-cagg-egp';
		$this->method_title       = $this->title = __( 'Kiosk', 'paymob-woocommerce' );
		$this->method_description = $this->description = __( 'Generate a reference number and pay through cash at outlets', 'paymob-woocommerce' );
		parent::__construct();
		// config
		$this->init_settings();
	}
	public function admin_options() {
		PaymobAutoGenerate::gateways_method_title( $this->method_title, $this, $this->get_option( 'single_integration_id' ) );
	}
}
