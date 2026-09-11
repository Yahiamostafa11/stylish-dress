<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
return array(
	'config_note'       => array(
		'title'       => __( 'Connect Paymob account', 'paymob-woocommerce' ),
		'description' => include PAYMOB_PLUGIN_PATH . '/includes/admin/views/htmlsviews/html_connect_paymob.php',
		'type'        => 'title',
	)
);
