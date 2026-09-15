<?php
class Paymob_Pixel_Settings {
	public static function paymob_pixel_settings_option($settings, $current_section) {
		if ($current_section == 'paymob_pixel') {
            $pixel_settings = get_option('woocommerce_paymob-pixel_settings', array());
            $appleArray=array();
            $omanNet = PaymobAutoGenerate::get_pixel_integration_ids('OMANNET');
            $card= PaymobAutoGenerate::get_pixel_integration_ids('Card');
            $all_cards=$card+$omanNet ;
            $ggle1= PaymobAutoGenerate::get_pixel_integration_ids('google-pay');
            $ggle2= PaymobAutoGenerate::get_pixel_integration_ids('Google-pay');
            $googlepayIDs = $ggle1+ $ggle2;

            $applepayIDs = PaymobAutoGenerate::get_pixel_integration_ids('apple_pay');

            if(!empty($applepayIDs) && count($applepayIDs)>1){
                $appleArray=array(
                        'name' => __('Apple Pay', 'paymob-woocommerce'),
                        'type' => 'select',
                        'id' => 'apple_pay_integration_id',
                        'options' => $applepayIDs,
                        'desc_tip' => true,
                        'default' => !empty($pixel_settings['apple_pay_integration_id'])?$pixel_settings['apple_pay_integration_id']:null
                    );
            }

            $googleArray=array();
            if(!empty($googlepayIDs)&& count($googlepayIDs)>1){
                $googleArray=array(
                        'name' => __('Google Pay', 'paymob-woocommerce'),
                        'type' => 'select',
                        'id' => 'google_pay_integration_id',
                        'options' => $googlepayIDs,
                        'desc_tip' => true,
                        'default' => !empty($pixel_settings['google_pay_integration_id'])?$pixel_settings['google_pay_integration_id']:null
                    );
            }
        $tabs = include PAYMOB_PLUGIN_PATH . '/includes/admin/paymob-admin-tabs.php';
            $custom_settings = array(
                array(
                    'name' => '',
                    'type' => 'title',
                    'desc' => $tabs,
                ),
                array(
                    'type'  => 'title',
                    'class' => 'payment-feature-description',
                    'name'  => __( 'About Feature', 'paymob-woocommerce' ),
                    'desc'  => '<div style="background-color: #f0f8ff; border: 1px solid #ddd; padding: 15px; margin-top: 20px; border-radius: 8px; font-family: Arial, sans-serif; color: #333; width:70%; margin-left:1%">
                                    '.__( 'Feature enables consumers to complete their payments directly on your WooCommerce store. It is enabled by default on your store. To disable it, navigate to the Payment Integrations section and disable "paymob-pixel". If you wish to hide a specific payment method, simply avoid selecting its integration ID.<br/><br/>
                                    
                                    For card payments, select the required integration ID. By default, all integration IDs will be pre-selected. <br/><br/>
                                    
                                    <span style="font-weight: bold; color: #007bff;">ℹ️ For Apple Pay and Google Pay:</span> Certain actions must be completed on Paymob\'s side. Please reach out to your account manager or <span style="white-space: nowrap;">contact us at <a href="mailto:support@paymob.com" style="color: #007bff; font-weight: bold;">support@paymob.com</a></span>. Make sure to receive confirmation from Paymob before enabling Apple Pay or Google Pay.' ).'
                                </div>',
                ),
                array(
                    'type' => 'title',
                    'name' => __('Section : Payment Methods', 'paymob-woocommerce'),
                ),
                array(
                    'name' => __('Payment Method -  Title', 'paymob-woocommerce'),
                    'type' => 'text',
                    'id' => 'title',
                    'desc_tip' => true,
                    'default' => isset($pixel_settings['title']) ? $pixel_settings['title'] : '',
                    'custom_attributes' => array('required' => 'required'),
                ),
                array(
                    'name' => __('Cards', 'paymob-woocommerce'),
                    'type' => 'multiselect',
                    'id' => 'cards_integration_id',
                    'options' => $all_cards,
                    'desc_tip' => true,
                    'custom_attributes' => array(
                            'multiple' => 'multiple',
                        ),
                    'default' => isset($pixel_settings['cards_integration_id']) ? $pixel_settings['cards_integration_id'] : ''
                ),
                $appleArray,
                $googleArray,
                array(
                    'type' => 'sectionend',
                    'id' => 'payment_methods_end',
                ),
                array(
                    'type' => 'title',
                    'name' => __('Section : Settings', 'paymob-woocommerce'),
                ),
                array(
                    'name' => __('Show Save Card', 'paymob-woocommerce'),
                    'type' => 'checkbox',
                    'id' => 'show_save_card',
                    'desc_tip' => true,
                    'default' => isset($pixel_settings['show_save_card']) ? $pixel_settings['show_save_card'] : 'yes'
                ),
                array(
                    'name' => __('Force Save Card', 'paymob-woocommerce'),
                    'type' => 'checkbox',
                    'id' => 'force_save_card',
                    'desc_tip' => true,
                    'default' => isset($pixel_settings['force_save_card']) ? $pixel_settings['force_save_card'] : 'no'
                ),
                // array(
                //     'name' => __('Customize Pixel', 'paymob-woocommerce'),
                //     'type' => 'checkbox',
                //     'id' => 'customization_div',
                //     'desc_tip' => true,
                //     'default' => isset($pixel_settings['customization_div']) ? $pixel_settings['customization_div'] : ''
                // ),
                array(
                    'type' => 'custom_html',
                    'id' => 'paymob_pixel_customization',
                    'css' => ' ',
                ),
                array(
                    'type' => 'sectionend',
                    'id' => 'settings_end',
                ),
            );
    
            // Merge custom settings with existing settings.
            $settings = array_merge($settings, $custom_settings);
        }
    
        return $settings;
	}
}
