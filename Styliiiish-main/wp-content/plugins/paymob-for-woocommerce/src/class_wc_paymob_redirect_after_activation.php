<?php
/**
 * Paymob Redirect Url
 */
class WC_Paymob_RedirectUrl
{
		// Check the redirect flag and perform redirect if true
        public static function redirect_after_activation() {
            $gatewayData = get_option('woocommerce_paymob_gateway_data');
            $mainOptions = get_option('woocommerce_paymob-main_settings');
            
            if (empty($gatewayData) && empty($mainOptions)) {
                // Check and delete the flag to prevent future redirects
                if ( get_option( 'paymob_activation_redirect', false ) ) {
                    delete_option( 'paymob_activation_redirect' );

                    // Data for Paymob request
                    $data = [
                        'partner' => 'woocommerce',
                         'clt'     => Paymob_Main_Partner_Info::get_public_ip(),
                    ];
                    // Paymob Request
                    $paymobReq = new Paymob('1', WC_LOG_DIR . 'paymob-auth.log');
                    $response = $paymobReq->getOnboardingUrl('egy', $data);
                    // Check for errors in Paymob response
                    $currentURL = str_replace('amp;', '', esc_attr( self_admin_url(('admin.php?page=wc-settings&tab=checkout&section=paymob-main&popup=true') )));
                    $encoded_url=urlencode($currentURL);
                    $url='https://onboarding.paymob.com/auth/country-selection?partner=woocommerce&redirect_url='.$encoded_url;
                    // If successful, return the URL
                    if (isset($response->url)) {
                        $url = $response->url.'&redirect_url='.$encoded_url;
                    } 
                    wp_redirect( $url);
                    exit;
                }
            }
        }
}
