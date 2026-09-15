<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tabs = include PAYMOB_PLUGIN_PATH . '/includes/admin/paymob-admin-tabs.php';
            
return array(
	'tabs'=> array(
        'name' => '',
        'type' => 'title',
        'description' => $tabs,
    ),
	'integration_id' => array(
		'title'             => __( 'Paymob Integration ID(s)', 'paymob-woocommerce' ),
		'type'              => 'multiselect',
		'options'           => PaymobAutoGenerate::get_integration_ids(),
		'custom_attributes' => array(
			'required' => 'required',
			'multiple' => 'multiple',
        ),
	),
	'title'          => array(
		'title'             => __( 'Payment Method - Title', 'paymob-woocommerce' ),
		'type'              => 'text',
		'description'       => __( 'This controls the title which the user sees during checkout.', 'paymob-woocommerce' ),
		'default'           => __( 'Pay with Paymob', 'paymob-woocommerce' ),
		'sanitize_callback' => 'sanitize_text_field',
		'custom_attributes' => array( 'required' => 'required' ),
	),
	'description'    => array(
		'title'             => __( 'Payment Method - Description', 'paymob-woocommerce' ),
		'type'              => 'textarea',
		'default'           => __( 'Pay with Paymob', 'paymob-woocommerce' ),
		'description'       => __( 'This controls the description which the user sees during checkout.', 'paymob-woocommerce' ),
		'sanitize_callback' => 'sanitize_text_field',
		'custom_attributes' => array( 'required' => 'required' ),
	),
	'logo'           => array(
		'title'             => __( 'Payment Method - Logo URL', 'paymob-woocommerce' ),
		'default'           => plugins_url( PAYMOB_PLUGIN_NAME ) . '/assets/img/' . $this->id . '.png',
		'type'              => 'text',
		'description'       => __( 'Add a Logo URL for checkout icon.', 'paymob-woocommerce' ),
		'sanitize_callback' => 'sanitize_url',
		'custom_attributes' => array( 'required' => 'required' ),
	),
);
