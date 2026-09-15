<?php
class Paymob_Pixel_Gateway extends Paymob_Payment {

	public $id;
	public $method_title;
	public $method_description;
	public $has_fields;
	public function __construct() {
		$this->id                 = 'paymob-pixel';
		$this->method_title       = $this->title = __( 'Paymob Payment', 'paymob-woocommerce' );
		$this->method_description = $this->description = __( 'Paymob Payment', 'paymob-woocommerce' );
		parent::__construct();
		// config
		$this->init_settings();
	}
}
