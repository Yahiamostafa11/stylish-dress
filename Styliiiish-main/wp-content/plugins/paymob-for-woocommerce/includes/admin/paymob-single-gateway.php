<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$single_integration_id_properties = PaymobAutoGenerate::disable_single_integration_id_field();
$tabs = include PAYMOB_PLUGIN_PATH . '/includes/admin/paymob-admin-tabs.php';
            
    
$settings = array(
    'tabs'=> array(
        'name' => '',
        'type' => 'title',
        'description' => $tabs,
    ),
    'single_integration_id' => array(
        'title'             => $single_integration_id_properties['title'],
        'type'              => $single_integration_id_properties['type'],  // Set the type dynamically
        'options'           => PaymobAutoGenerate::get_integration_ids(),
        'custom_attributes' => $single_integration_id_properties['custom_attributes'],  // Set custom attributes dynamically
    ),
    'title' => array(
        'title'             => __( 'Payment Method - Title', 'paymob-woocommerce' ),
        'type'              => 'text',
        'description'       => __( 'This controls the title which the user sees during checkout.', 'paymob-woocommerce' ),
        'default'           => $this->title,
        'sanitize_callback' => 'sanitize_text_field',
        'custom_attributes' => array( 'required' => 'required' ),
    ),
    'description' => array(
        'title'             => __( 'Payment Method - Description', 'paymob-woocommerce' ),
        'type'              => 'textarea',
        'default'           => $this->description,
        'description'       => __( 'This controls the description which the user sees during checkout.', 'paymob-woocommerce' ),
        'sanitize_callback' => 'sanitize_text_field',
        'custom_attributes' => array( 'required' => 'required' ),
    ),
    'logo' => array(
        'title'             => __( 'Payment Method - Logo URL', 'paymob-woocommerce' ),
        'default'           => plugins_url( PAYMOB_PLUGIN_NAME ) . '/assets/img/paymob.png',
        'type'              => 'text',
        'description'       => __( 'Add a Logo URL for checkout icon.', 'paymob-woocommerce' ),
        'sanitize_callback' => 'sanitize_url',
        'custom_attributes' => array( 'required' => 'required' ),
    ),
);

return $settings;
