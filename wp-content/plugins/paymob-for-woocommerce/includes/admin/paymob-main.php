<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
return array(
	'tabs'       => array(
		'title'       =>'',
		'description' => include PAYMOB_PLUGIN_PATH . '/includes/admin/paymob-admin-tabs.php',
		'type'        => 'title',
	),
	'buttons'       => array(
		'title'       =>'',
		'description' => include PAYMOB_PLUGIN_PATH . '/includes/admin/views/htmlsviews/html_reconnect_buttons.php',
		'type'        => 'title',
	),
   'has_items'         => array(
		'title'   => __( "Show Product Details on Paymob's Checkout", 'paymob-woocommerce' ),
		'label'   => ' ',
		'type'    => 'checkbox',
		'default' => 'yes',
		'description'=>'<div  style="width:50%" id="-description"><div style="background-color: #f0f8ff;border: 1px solid #ddd;padding: 15px;margin-top: 20px;border-radius: 8px;font-family: Arial, sans-serif;color: #333">
                <div>
                <ol>
                    <li>' . __( 'Enable the checkbox in this section.', 'paymob-woocommerce' ) . '</li>
                    <li>' . __( 'Log in to the Paymob Merchant Dashboard.', 'paymob-woocommerce' ) . '</li>
                    <li>' . __( 'Navigate to "Checkout Customization" → "Payment Methods."', 'paymob-woocommerce' ) . '</li>
                    <li>' . __( 'Under the "Additional Information" section, enable the "Show Item/Product" option and click "Apply Changes."', 'paymob-woocommerce' ) . '</li>
                </ol>
            </div></div></div>',
	),
	'debug'             => array(
		'title'   => __( 'Debug Log', 'paymob-woocommerce' ),
		'label'   => ' ',
		'type'    => 'checkbox',
		'default' => 'yes',
		'description'=>'<div  style="width:50%" id="-description"><div style="background-color: #f0f8ff;border: 1px solid #ddd;padding: 15px;margin-top: 20px;border-radius: 8px;font-family: Arial, sans-serif;color: #333">
                ' . __( 'Enabling the Debug Log checkbox in this section will log all actions in Paymob files. These files will be saved in the directory', 'paymob-woocommerce' ) . ' <b>' . ( defined( 'WC_LOG_DIR' ) ? WC_LOG_DIR : WC()->plugin_path() . '/logs/' ) . '</b>.</div></div>',
	),
);
