<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$tabs = include PAYMOB_PLUGIN_PATH . '/includes/admin/paymob-admin-tabs.php';
            
return array(
	array(
        'name' => '',
        'type' => 'title',
        'desc' => $tabs,
    ),
	array(
		'type' => 'title',
		'name' => __( 'Add Payment Integration', 'paymob-woocommerce' ),
	),
	array(
		'name'     => __( 'Enable', 'paymob-woocommerce' ),
		'type'     => 'checkbox',
		'id'       => 'payment_enabled',
		'desc_tip' => true,
		'default'  => 'no',
	),
	array(
		'name'              => __( 'Payment Method', 'paymob-woocommerce' ),
		'type'              => 'text',
		'id'                => 'payment_integrations_type',
		'desc_tip'          => true,
		'custom_attributes' => array( 'required' => 'required' ),
	),
	array(
		'name'              => __( 'Paymob Integration ID', 'paymob-woocommerce' ),
		'type'              => 'select',
		'id'                => 'integration_id',
		'desc_tip'          => true,
		'custom_attributes' => array( 'required' => 'required' ),
		'options'           => PaymobAutoGenerate::get_integration_ids(), // Dynamically loaded options.
	),
	array(
		'name'              => __( 'Payment Method -  Title', 'paymob-woocommerce' ),
		'type'              => 'text',
		'id'                => 'checkout_title',
		'desc_tip'          => true,
		'custom_attributes' => array( 'required' => 'required' ),
	),
	array(
		'name'              => __( 'Payment Method -  Description', 'paymob-woocommerce' ),
		'type'              => 'textarea',
		'id'                => 'checkout_description',
		'desc_tip'          => true,
		'custom_attributes' => array( 'required' => 'required' ),
	),
	array(
		'name'              => __( 'Payment Method - Logo URL', 'paymob-woocommerce' ),
		'type'              => 'text',
		'id'                => 'payment_logo',
		'desc_tip'          => true,
		'default'           => plugins_url( PAYMOB_PLUGIN_NAME ) . '/assets/img/paymob.png',
		'custom_attributes' => array( 'required' => 'required' ),
	),
	array(
		'type' => 'sectionend',
		'id'   => 'paymob_add_gateway',
	),
);
