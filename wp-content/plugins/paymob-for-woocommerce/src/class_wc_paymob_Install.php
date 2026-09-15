<?php
/**
 * Paymob Installing Plugin
 */
class WC_Paymob_Install {

	public static function install() {
		global $wpdb;
		if ( is_dir( WP_LANG_DIR . '/plugins/' ) ) {
			$arTrans         = 'paymob-woocommerce-ar';
			$transPath       = WP_LANG_DIR . '/plugins/' . $arTrans;
			$pluginTransPath = PAYMOB_PLUGIN_PATH . 'i18n/languages/' . $arTrans;
			copy( $pluginTransPath . '.mo', $transPath . '.mo' );
			copy( $pluginTransPath . '.po', $transPath . '.po' );
		}
		// Require parent plugin
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && ! array_key_exists( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_site_option( 'active_sitewide_plugins' ) ) ) ) {
			wp_die( esc_html__( 'Sorry, PayMob plugin requires WooCommerce to be installed and active.', 'paymob-woocommerce' ) );
		}
		WC_Paymob_Tables::create_paymob_gateways_table();
		WC_Paymob_Tables::create_paymob_pixel_table();
	}
}
