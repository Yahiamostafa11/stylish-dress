<?php
$sections                         = array();
$sections['paymob-main']          = __( 'Main configuration', 'paymob-woocommerce' );
if ( !empty( $pub_key ) && !empty( $sec_key ) && !empty( $api_key ) ) {	
    $sections['paymob_list_gateways'] = __( 'Payment Integrations', 'paymob-woocommerce' );
    $sections['paymob_pixel']   = __( 'Card Embedded Settings', 'paymob-woocommerce' );
    // if(isset($option_value)&& !empty($option_value) && $paymob_options['mode']==$mode)
    // {
    //     $sections['valu_widget']   = __( 'Valu Widget Settings', 'paymob-woocommerce' );
    // }
    $sections['paymob_add_gateway']   = __( 'Add Payment Integration', 'paymob-woocommerce' );    
}
return $sections; 
