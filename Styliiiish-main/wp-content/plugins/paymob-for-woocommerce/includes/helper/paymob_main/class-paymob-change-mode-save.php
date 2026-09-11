<?php
class Paymob_Change_Mode_Save {

    public static function change_mode_save() { 
        global $wpdb;
        check_ajax_referer( 'your_nonce_action', '_ajax_nonce' );
        try{
            $main_settings = get_option('woocommerce_paymob-main_settings');
            $paymob_settings = get_option('woocommerce_paymob_settings');
            if($main_settings['mode']=='live'){
                if(!empty($main_settings['test_pub_key']) && !empty($main_settings['test_sec_key'])){
                        $main_settings['mode']   = 'test';
                        $paymob_settings['sec_key']=$main_settings['sec_key']=$main_settings['test_sec_key'];
                        $paymob_settings['pub_key']=$main_settings['pub_key']=$main_settings['test_pub_key'];
                        $results = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT * FROM {$wpdb->prefix}paymob_gateways 
                                WHERE mode = %s 
                                ORDER BY CASE WHEN gateway_id = 'paymob-pixel' THEN 0 ELSE 1 END, ordering",
                                $main_settings['mode'] 
                            ),
                            OBJECT

                        );
                        if(empty($results))
                        {
                            wp_send_json_error(['message' => __('No Test Payment Method Integrations Available.','paymob-woocommerce'),'mode'=>'live']);

                        }
                }else{
                    wp_send_json_error(['message' => __('Test Keys are not available','paymob-woocommerce')]);
                }
            }
            else if($main_settings['mode']=='test')
            { 
                if(!empty($main_settings['live_pub_key']) && !empty($main_settings['live_sec_key'])){

                    $main_settings['mode']   = 'live';
                    $paymob_settings['sec_key']=$main_settings['sec_key']=$main_settings['live_sec_key'];
                    $paymob_settings['pub_key']=$main_settings['pub_key']=$main_settings['live_pub_key'];
                    $results = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT * FROM {$wpdb->prefix}paymob_gateways 
                            WHERE mode = %s 
                            ORDER BY CASE WHEN gateway_id = 'paymob-pixel' THEN 0 ELSE 1 END, ordering",
                            $main_settings['mode'] 
                        ),
                        OBJECT

                    );
                    if(empty($results))
                    {
                        wp_send_json_error(['message' => __('No Live Payment Method Integrations Available.','paymob-woocommerce'),'mode'=>'test']);

                    }

                }else{
                    wp_send_json_error(['message' => __('Live Keys are not available','paymob-woocommerce')]);

                }            
            }


            update_option('woocommerce_paymob-main_settings', $main_settings); 
            update_option('woocommerce_paymob_settings', $paymob_settings);
            delete_option('woocommerce_valu_widget_settings');
            // wp_send_json_success(['message' => __('Paymob Mode has changed sucessfully.','paymob-woocommerce'),
            // 'redirect_url'=>admin_url('admin.php?page=wc-settings&tab=checkout&section=paymob-main')]);
            wp_send_json_success(['message' => __('Paymob Mode has changed sucessfully.','paymob-woocommerce'),
            'redirect_url' => home_url( $_SERVER['REQUEST_URI'] )]);
            

        } catch ( \Exception $e ) {

             wp_send_json_error(['message' => $e->getMessage()]);
        }
    }

}
