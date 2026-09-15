<?php
/**
 * Shopire functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Shopire
 */
 
if ( ! function_exists( 'shopire_theme_setup' ) ) :
function shopire_theme_setup() {
	
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Shopire, use a find and replace
	 * to change 'Shopire' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'shopire' );
	
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	
	add_theme_support( 'custom-header' );
	
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary_menu' => esc_html__( 'Primary Menu', 'shopire' )
	) );
	
	//Add selective refresh for sidebar widget
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	// woocommerce support
	add_theme_support( 'woocommerce' );
	
	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support('custom-logo');
	
	/**
	 * Custom background support.
	 */
	add_theme_support( 'custom-background', apply_filters( 'shopire_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	
	/**
	 * Set default content width.
	 */
	if ( ! isset( $content_width ) ) {
		$content_width = 800;
	}	
}
endif;
add_action( 'after_setup_theme', 'shopire_theme_setup' );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function shopire_widgets_init() {	
	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar( array(
			'name' => __( 'WooCommerce Widget Area', 'shopire' ),
			'id' => 'shopire-woocommerce-sidebar',
			'description' => __( 'This Widget area for WooCommerce Widget', 'shopire' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h5 class="widget-title">',
			'after_title' => '</h5>',
		) );
	}
	
	register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'shopire' ),
		'id' => 'shopire-sidebar-primary',
		'description' => __( 'The Primary Widget Area', 'shopire' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h5 class="widget-title"><span></span>',
		'after_title' => '</h5>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Header Top Bar Widget Area', 'shopire' ),
		'id' => 'shopire-header-top-sidebar',
		'description' => __( 'Header Top Bar Widget Area', 'shopire' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>'
	) );
	
	register_sidebar( array(
		'name' => __( 'Header Side Docker Area', 'shopire' ),
		'id' => 'shopire-header-docker-sidebar',
		'description' => __( 'Header Side Docker Area', 'shopire' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h5 class="widget-title"><span></span>',
		'after_title' => '</h5>',
	) );
	
	$shopire_footer_widget_column = get_theme_mod('shopire_footer_widget_column','4');
	for ($i=1; $i<=$shopire_footer_widget_column; $i++) {
		register_sidebar( array(
			'name' => __( 'Footer  ', 'shopire' )  . $i,
			'id' => 'shopire-footer-widget-' . $i,
			'description' => __( 'The Footer Widget Area', 'shopire' )  . $i,
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h5 class="widget-title">',
			'after_title' => '</h5>',
		) );
	}
}
add_action( 'widgets_init', 'shopire_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function shopire_scripts() {
	
	/**
	 * Styles.
	 */
	// Owl Crousel	
	wp_enqueue_style('owl-carousel-min',get_template_directory_uri().'/assets/vendors/css/owl.carousel.min.css');
	
	// Font Awesome
	wp_enqueue_style('all-css',get_template_directory_uri().'/assets/vendors/css/all.min.css');
	
	// Animate
	wp_enqueue_style('animate',get_template_directory_uri().'/assets/vendors/css/animate.css');

	// Fancybox
	wp_enqueue_style('Fancybox',get_template_directory_uri().'/assets/vendors/css/jquery.fancybox.min.css');
	
	// Shopire Core
	wp_enqueue_style('shopire-core',get_template_directory_uri().'/assets/css/core.css');

	// Shopire Theme
	wp_enqueue_style('shopire-theme', get_template_directory_uri() . '/assets/css/themes.css');
	
	// Shopire WooCommerce
	wp_enqueue_style('shopire-woocommerce',get_template_directory_uri().'/assets/css/woo-styles.css');
	
	// Shopire Style (parent theme stylesheet)
	wp_enqueue_style( 'shopire-style', get_template_directory_uri() . '/style.css' );
	
	// Scripts
	wp_enqueue_script( 'jquery' );
	
	// Imagesloaded
	wp_enqueue_script( 'imagesloaded' );
	
	// Owl Crousel
	wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/vendors/js/owl.carousel.js', array('jquery'), true);
	
	// Wow
	wp_enqueue_script('wow-min', get_template_directory_uri() . '/assets/vendors/js/wow.min.js', array('jquery'), false, true);
	
	// fancybox
	wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/vendors/js/jquery.fancybox.js', array('jquery'), false, true);
	
	// Shopire Theme
	wp_enqueue_script('shopire-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), false, true);

	// Shopire custom
	wp_enqueue_script(
   'shopire-custom-js',
   get_template_directory_uri() . '/assets/js/custom.js',
   array('jquery'),
   filemtime(get_template_directory() . '/assets/js/custom.js'),
   true
);

		
	  
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'shopire_scripts' );


/**
 * Enqueue admin scripts and styles.
 */
function shopire_admin_enqueue_scripts(){
	wp_enqueue_style('shopire-admin-style', get_template_directory_uri() . '/inc/admin/assets/css/admin.css');
	wp_enqueue_script( 'shopire-admin-script', get_template_directory_uri() . '/inc/admin/assets/js/shopire-admin-script.js', array( 'jquery' ), '', true );
    wp_localize_script( 'shopire-admin-script', 'shopire_ajax_object',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce('shopire_nonce')
        )
    );
}
add_action( 'admin_enqueue_scripts', 'shopire_admin_enqueue_scripts' );

/**
 * Enqueue User Custom styles.
 */
 if( ! function_exists( 'shopire_user_custom_style' ) ):
    function shopire_user_custom_style() {

		$shopire_print_style = '';
		
		 /*=========================================
		 Shopire Page Title
		=========================================*/
		$shopire_print_style   .= shopire_customizer_value( 'shopire_breadcrumb_height_option', '.wf_pagetitle', array( 'padding-top' ), array( 12, 12, 12 ), 'rem' );
		$shopire_print_style   .= shopire_customizer_value( 'shopire_breadcrumb_height_option', '.wf_pagetitle', array( 'padding-bottom' ), array( 12, 12, 12 ), 'rem' );
		 $shopire_print_style   .=  shopire_customizer_value( 'shopire_breadcrumb_title_size', '.wf_pagetitle .wf_pagetitle_content .title > *', array( 'font-size' ), array( 6, 6, 6 ), 'rem' );
		  $shopire_print_style   .=  shopire_customizer_value( 'shopire_breadcrumb_content_size', '.wf_pagetitle .wf_pagetitle_content .wf_pagetitle_breadcrumb li', array( 'font-size' ), array( 2, 2, 2 ), 'rem' );
		
		
		$shopire_breadcrumb_bg_img 			= get_theme_mod('shopire_breadcrumb_bg_img');
		$shopire_breadcrumb_img_opacity 	= get_theme_mod('shopire_breadcrumb_img_opacity','0.5');
		$shopire_breadcrumb_opacity_color 	= get_theme_mod('shopire_breadcrumb_opacity_color','#000');
		if(!empty($shopire_breadcrumb_bg_img)):
			$shopire_print_style .=".wf_pagetitle{
				    background-image: url(" .esc_url($shopire_breadcrumb_bg_img). ");
			}.wf_pagetitle:before {
				content: '';
				position: absolute;
				left: 0;
				top: 0;
				right: 0;
				bottom: 0;
				opacity: " .esc_attr($shopire_breadcrumb_img_opacity). ";
				background: " .esc_attr($shopire_breadcrumb_opacity_color). ";
			}.wf_pagetitle .wf_pagetitle_content .title > *,
			.wf_pagetitle .wf_pagetitle_content .wf_pagetitle_breadcrumb li,
			.wf_pagetitle .wf_pagetitle_content .wf_pagetitle_breadcrumb li a:hover,
			.wf_pagetitle .wf_pagetitle_content .wf_pagetitle_breadcrumb li a:focus,
			.wf_pagetitle .wf_pagetitle_content .wf_pagetitle_breadcrumb li a{
				color:#fff;
			}\n";
		endif;		
		
	
		 /*=========================================
		 Shopire Logo Size
		=========================================*/
		$shopire_print_style   .= shopire_customizer_value( 'hdr_logo_size', '.site--logo img', array( 'max-width' ), array( 150, 150, 150 ), 'px !important' );
		$shopire_print_style   .= shopire_customizer_value( 'hdr_site_title_size', '.site--logo .site-title', array( 'font-size' ), array( 30, 30, 30 ), 'px !important' );
		$shopire_print_style   .= shopire_customizer_value( 'hdr_site_desc_size', '.site--logo .site-description', array( 'font-size' ), array( 16, 16, 16 ), 'px !important' );
		
		

				
		$shopire_site_container_width 			 = get_theme_mod('shopire_site_container_width','1440');
			if($shopire_site_container_width >=768 && $shopire_site_container_width <=3000){
				$shopire_print_style .=".wf-container,.wf_slider .wf_owl_carousel.owl-carousel .owl-nav,.wf_slider .wf_owl_carousel.owl-carousel .owl-dots {
						max-width: " .esc_attr($shopire_site_container_width). "px;
					}.header--eight .wf-container {
						max-width: calc(" .esc_attr($shopire_site_container_width). "px + 7.15rem);
					}\n";
			}
					
		/**
		 *  Sidebar Width
		 */
		$shopire_sidebar_width = get_theme_mod('shopire_sidebar_width',33);
		if($shopire_sidebar_width !== '') { 
			$shopire_primary_width   = absint( 100 - $shopire_sidebar_width );
				$shopire_print_style .="	@media (min-width: 992px) {#wf-main {
					max-width:" .esc_attr($shopire_primary_width). "%;
					flex-basis:" .esc_attr($shopire_primary_width). "%;
				}\n";
				$shopire_print_style .="#wf-sidebar {
					max-width:" .esc_attr($shopire_sidebar_width). "%;
					flex-basis:" .esc_attr($shopire_sidebar_width). "%;
				}}\n";
        }
		$shopire_print_style   .= shopire_customizer_value( 'shopire_widget_ttl_size', '.wf_widget-area .widget .widget-title', array( 'font-size' ), array( 20, 20, 20 ), 'px' );
		
		/**
		 *  Typography Body
		 */
		 $shopire_body_font_weight_option	 	 = get_theme_mod('shopire_body_font_weight_option','inherit');
		 $shopire_body_text_transform_option	 = get_theme_mod('shopire_body_text_transform_option','inherit');
		 $shopire_body_font_style_option	 	 = get_theme_mod('shopire_body_font_style_option','inherit');
		 $shopire_body_txt_decoration_option	 = get_theme_mod('shopire_body_txt_decoration_option','none');
		
		 $shopire_print_style   .= shopire_customizer_value( 'shopire_body_font_size_option', 'body', array( 'font-size' ), array( 16, 16, 16 ), 'px' );
		 $shopire_print_style   .= shopire_customizer_value( 'shopire_body_line_height_option', 'body', array( 'line-height' ), array( 1.6, 1.6, 1.6 ) );
		 $shopire_print_style   .= shopire_customizer_value( 'shopire_body_ltr_space_option', 'body', array( 'letter-spacing' ), array( 0, 0, 0 ), 'px' );
		 $shopire_print_style .=" body{ 
			font-weight: " .esc_attr($shopire_body_font_weight_option). ";
			text-transform: " .esc_attr($shopire_body_text_transform_option). ";
			font-style: " .esc_attr($shopire_body_font_style_option). ";
			text-decoration: " .esc_attr($shopire_body_txt_decoration_option). ";
		}\n";		 
		
		/**
		 *  Typography Heading
		 */
		 for ( $i = 1; $i <= 6; $i++ ) {
			 $shopire_heading_font_weight_option	 	= get_theme_mod('shopire_h' . $i . '_font_weight_option','700');
			 $shopire_heading_text_transform_option 	= get_theme_mod('shopire_h' . $i . '_text_transform_option','inherit');
			 $shopire_heading_font_style_option	 	= get_theme_mod('shopire_h' . $i . '_font_style_option','inherit');
			 $shopire_heading_txt_decoration_option	= get_theme_mod('shopire_h' . $i . '_txt_decoration_option','inherit');
			 
			 $shopire_print_style   .= shopire_customizer_value( 'shopire_h' . $i . '_font_size_option', 'h' . $i .'', array( 'font-size' ), array( 36, 36, 36 ), 'px' );
			 $shopire_print_style   .= shopire_customizer_value( 'shopire_h' . $i . '_line_height_option', 'h' . $i . '', array( 'line-height' ), array( 1.2, 1.2, 1.2 ) );
			 $shopire_print_style   .= shopire_customizer_value( 'shopire_h' . $i . '_ltr_space_option', 'h' . $i . '', array( 'letter-spacing' ), array( 0, 0, 0 ), 'px' );
			 $shopire_print_style .=" h" . $i . "{ 
				font-weight: " .esc_attr($shopire_heading_font_weight_option). ";
				text-transform: " .esc_attr($shopire_heading_text_transform_option). ";
				font-style: " .esc_attr($shopire_heading_font_style_option). ";
				text-decoration: " .esc_attr($shopire_heading_txt_decoration_option). ";
			}\n";
		 }
		
		
		/*=========================================
		Footer 
		=========================================*/
		$shopire_footer_bg_color			= get_theme_mod('shopire_footer_bg_color','#efefef');
		if(!empty($shopire_footer_bg_color)):
			 $shopire_print_style .=".wf_footer--one{ 
				    background-color: ".esc_attr($shopire_footer_bg_color).";
			}\n";
		endif;
        wp_add_inline_style( 'shopire-style', $shopire_print_style );
    }
endif;
add_action( 'wp_enqueue_scripts', 'shopire_user_custom_style' );


/**
 * Define Constants
 */
 
$shopire_theme = wp_get_theme();
define( 'SHOPIRE_THEME_VERSION', $shopire_theme->get( 'Version' ) );

// Root path/URI.
define( 'SHOPIRE_THEME_DIR', get_template_directory() );
define( 'SHOPIRE_THEME_URI', get_template_directory_uri() );

// Root path/URI.
define( 'SHOPIRE_THEME_INC_DIR', SHOPIRE_THEME_DIR . '/inc');
define( 'SHOPIRE_THEME_INC_URI', SHOPIRE_THEME_URI . '/inc');


/**
 * Implement the Custom Header feature.
 */
require_once get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/inc/customizer/shopire-customizer.php';
require get_template_directory() . '/inc/customizer/controls/code/customizer-repeater/inc/customizer.php';
/**
 * Nav Walker for Bootstrap Dropdown Menu.
 */
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Control Style
 */

require SHOPIRE_THEME_INC_DIR . '/customizer/controls/code/control-function/style-functions.php';

/**
 * Getting Started
 */
require SHOPIRE_THEME_INC_DIR . '/admin/getting-started.php';

add_action('template_redirect', function () {
	if (is_admin() || wp_doing_ajax()) {
		return;
	}

	$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
	$request_path = (string) parse_url($request_uri, PHP_URL_PATH);
	$path = trim($request_path, '/');
	$decoded_path = trim(rawurldecode($path), '/');

	if ($decoded_path === '') {
		return;
	}

	$aliases = [
		'en/payment' => 'checkout',
		'payment' => 'checkout',
		'ar/الدفع' => 'checkout',
		'الدفع' => 'checkout',
	];

	$target_path = null;
	foreach ($aliases as $source_prefix => $target_prefix) {
		if ($decoded_path !== $source_prefix && strpos($decoded_path, $source_prefix . '/') !== 0) {
			continue;
		}

		$tail = ltrim((string) substr($decoded_path, strlen($source_prefix)), '/');
		if ($tail !== '') {
			$tail_parts = array_values(array_filter(explode('/', $tail), static function ($part) {
				return $part !== '';
			}));

			if (!empty($tail_parts)) {
				$endpoint_aliases = [
					'تم-استلام-الطلب' => 'order-received',
					'تم-استلام-طلبك' => 'order-received',
					'order-received' => 'order-received',
				];

				$first_part = rawurldecode((string) $tail_parts[0]);
				if (isset($endpoint_aliases[$first_part])) {
					$tail_parts[0] = $endpoint_aliases[$first_part];
					$tail = implode('/', $tail_parts);
				}
			}
		}

		$rebuilt_path = trim($target_prefix . ($tail !== '' ? '/' . $tail : ''), '/');
		$target_path = '/' . $rebuilt_path . '/';
		break;
	}

	if (!$target_path) {
		return;
	}

	$current_path = '/' . ltrim($request_path, '/');
	$target_url = home_url($target_path);
	if (!empty($_GET) && is_array($_GET)) {
		$target_url = add_query_arg(wp_unslash($_GET), $target_url);
	}

	$target_only_path = (string) parse_url($target_url, PHP_URL_PATH);
	if (untrailingslashit(rawurldecode($current_path)) === untrailingslashit(rawurldecode($target_only_path))) {
		return;
	}

	wp_safe_redirect($target_url, 301);
	exit;
}, 0);

add_action('template_redirect', function () {
	if (is_admin() || wp_doing_ajax() || !is_singular('product')) {
		return;
	}

	$slug = get_post_field('post_name', get_queried_object_id());
	if (!$slug) {
		return;
	}

	$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
	$locale = (strpos($request_uri, '/en/') === 0) ? 'en' : 'ar';

	$target_path = '/' . $locale . '/item/' . rawurlencode((string) $slug);
	$target_url = home_url($target_path);

	$current_path = '/' . ltrim((string) parse_url($request_uri, PHP_URL_PATH), '/');
	$target_only_path = (string) parse_url($target_url, PHP_URL_PATH);
	if (untrailingslashit(rawurldecode($current_path)) === untrailingslashit(rawurldecode($target_only_path))) {
		return;
	}

	wp_safe_redirect($target_url, 302);
	exit;
}, 1);

if (!function_exists('shopire_styliiiish_build_cart_payload')) {
	function shopire_styliiiish_build_cart_payload() {
		if (!function_exists('WC') || !WC()->cart) {
			return [
				'count' => 0,
				'subtotal_html' => '',
				'total_html' => '',
				'shipping_lines' => [],
				'shipping_total_html' => '',
				'shipping_destination' => '',
				'change_address_url' => home_url('/cart/'),
				'items' => [],
				'cart_url' => home_url('/cart/'),
				'checkout_url' => home_url('/checkout/'),
			];
		}

		$items = [];
		foreach (WC()->cart->get_cart() as $cart_key => $cart_item) {
			$product = isset($cart_item['data']) ? $cart_item['data'] : null;
			if (!$product || !is_object($product)) {
				continue;
			}

			$product_name = html_entity_decode(wp_strip_all_tags($product->get_name()), ENT_QUOTES, 'UTF-8');
			if (function_exists('trp_translate')) {
				$target_locale = shopire_styliiiish_resolve_requested_locale();
				if ($target_locale !== '') {
					$translated_name = trp_translate($product_name, $target_locale, false);
					if (is_string($translated_name) && $translated_name !== '') {
						$product_name = html_entity_decode(wp_strip_all_tags($translated_name), ENT_QUOTES, 'UTF-8');
					}
				}
			}

			$items[] = [
				'key' => $cart_key,
				'name' => $product_name,
				'qty' => (int) ($cart_item['quantity'] ?? 1),
				'price_html' => wc_price((float) $product->get_price()),
				'line_total_html' => wc_price((float) ($cart_item['line_total'] ?? 0)),
				'image' => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail') ?: wc_placeholder_img_src('woocommerce_thumbnail'),
				'url' => get_permalink($product->get_id()),
				'remove_url' => wc_get_cart_remove_url($cart_key),
			];
		}

		$shipping_lines = [];
		$shipping_destination = '';

		if (WC()->customer && is_object(WC()->customer)) {
			$destination_parts = array_filter([
				trim((string) WC()->customer->get_shipping_address()),
				trim((string) WC()->customer->get_shipping_address_2()),
				trim((string) WC()->customer->get_shipping_city()),
				trim((string) WC()->customer->get_shipping_state()),
			]);

			if (empty($destination_parts)) {
				$destination_parts = array_filter([
					trim((string) WC()->customer->get_billing_address()),
					trim((string) WC()->customer->get_billing_address_2()),
					trim((string) WC()->customer->get_billing_city()),
					trim((string) WC()->customer->get_billing_state()),
				]);
			}

			$shipping_destination = implode(', ', $destination_parts);
		}

		if (WC()->cart->needs_shipping()) {
			$chosen_methods = WC()->session ? (array) WC()->session->get('chosen_shipping_methods', []) : [];
			$packages = WC()->shipping() ? WC()->shipping()->get_packages() : [];

			foreach ($packages as $package_index => $package) {
				$rates = isset($package['rates']) && is_array($package['rates']) ? $package['rates'] : [];
				if (empty($rates)) {
					continue;
				}

				$chosen_rate_id = isset($chosen_methods[$package_index]) ? (string) $chosen_methods[$package_index] : '';
				$rate = null;

				if ($chosen_rate_id !== '' && isset($rates[$chosen_rate_id])) {
					$rate = $rates[$chosen_rate_id];
				} else {
					$first_rate = reset($rates);
					$rate = $first_rate ?: null;
				}

				if (!$rate || !is_object($rate) || !method_exists($rate, 'get_label')) {
					continue;
				}

				$rate_cost = (float) $rate->get_cost();
				$rate_taxes = method_exists($rate, 'get_taxes') ? array_sum(array_map('floatval', (array) $rate->get_taxes())) : 0.0;

				$shipping_lines[] = [
					'label' => html_entity_decode(wp_strip_all_tags((string) __((string) $rate->get_label(), 'woocommerce')), ENT_QUOTES, 'UTF-8'),
					'cost_html' => wc_price($rate_cost + $rate_taxes),
				];
			}
		}

		$shipping_total_value = (float) WC()->cart->get_shipping_total() + (float) WC()->cart->get_shipping_tax();
		$shipping_total_html = $shipping_total_value > 0 ? wc_price($shipping_total_value) : '';

		return [
			'count' => (int) WC()->cart->get_cart_contents_count(),
			'subtotal_html' => WC()->cart->get_cart_subtotal(),
			'total_html' => WC()->cart->get_total(),
			'shipping_lines' => $shipping_lines,
			'shipping_total_html' => $shipping_total_html,
			'shipping_destination' => $shipping_destination,
			'change_address_url' => wc_get_cart_url(),
			'items' => $items,
			'cart_url' => wc_get_cart_url(),
			'checkout_url' => wc_get_checkout_url(),
		];
	}
}

if (!function_exists('shopire_styliiiish_resolve_requested_locale')) {
	function shopire_styliiiish_resolve_requested_locale() {
		if (!function_exists('determine_locale')) {
			return '';
		}

		$lang = isset($_REQUEST['lang']) ? sanitize_key(wp_unslash((string) $_REQUEST['lang'])) : '';
		if ($lang !== 'ar' && $lang !== 'en') {
			$referer = isset($_SERVER['HTTP_REFERER']) ? (string) $_SERVER['HTTP_REFERER'] : '';
			if (is_string($referer) && $referer !== '') {
				if (strpos($referer, '/en/') !== false) {
					$lang = 'en';
				} elseif (strpos($referer, '/ar/') !== false) {
					$lang = 'ar';
				}
			}
		}

		if ($lang === 'ar') {
			return 'ar';
		}

		if ($lang === 'en') {
			return 'en_US';
		}

		return '';
	}
}

if (!function_exists('shopire_styliiiish_apply_request_locale')) {
	function shopire_styliiiish_apply_request_locale() {
		if (!function_exists('switch_to_locale') || !function_exists('determine_locale')) {
			return;
		}

		$target_locale = shopire_styliiiish_resolve_requested_locale();

		if ($target_locale === '') {
			return;
		}

		$current_locale = determine_locale();
		if ($current_locale !== $target_locale) {
			switch_to_locale($target_locale);
		}
	}
}

if (!function_exists('shopire_styliiiish_wc_ajax_summary')) {
	function shopire_styliiiish_wc_ajax_summary() {
		shopire_styliiiish_apply_request_locale();
		wp_send_json_success(shopire_styliiiish_build_cart_payload());
	}
}
add_action('wc_ajax_styliiiish_cart_summary', 'shopire_styliiiish_wc_ajax_summary');
add_action('wc_ajax_nopriv_styliiiish_cart_summary', 'shopire_styliiiish_wc_ajax_summary');
add_action('wp_ajax_styliiiish_cart_summary', 'shopire_styliiiish_wc_ajax_summary');
add_action('wp_ajax_nopriv_styliiiish_cart_summary', 'shopire_styliiiish_wc_ajax_summary');

if (!function_exists('shopire_styliiiish_wc_ajax_add_to_cart')) {
	function shopire_styliiiish_wc_ajax_add_to_cart() {
		shopire_styliiiish_apply_request_locale();
		if (!function_exists('WC') || !WC()->cart) {
			wp_send_json_error(['message' => 'Cart is unavailable'], 500);
		}

		$product_id = isset($_REQUEST['product_id']) ? absint($_REQUEST['product_id']) : 0;
		$variation_id = isset($_REQUEST['variation_id']) ? absint($_REQUEST['variation_id']) : 0;
		$quantity = isset($_REQUEST['quantity']) ? max(1, absint($_REQUEST['quantity'])) : 1;
		$request_token = isset($_REQUEST['_sty_add_token']) ? wc_clean(wp_unslash((string) $_REQUEST['_sty_add_token'])) : '';
		$legacy_add_to_cart = isset($_REQUEST['add-to-cart']) ? absint($_REQUEST['add-to-cart']) : 0;

		if (!$product_id) {
			wp_send_json_error(['message' => 'Invalid product'], 400);
		}

		if ($legacy_add_to_cart > 0 && $legacy_add_to_cart === $product_id) {
			if (WC()->cart) {
				WC()->cart->calculate_totals();
			}
			wp_send_json_success(shopire_styliiiish_build_cart_payload());
		}

		if ($request_token !== '' && WC()->session) {
			$used_tokens = WC()->session->get('styliiiish_add_used_tokens');
			if (!is_array($used_tokens)) {
				$used_tokens = [];
			}

			if (in_array($request_token, $used_tokens, true)) {
				wp_send_json_success(shopire_styliiiish_build_cart_payload());
			}

			$used_tokens[] = $request_token;
			if (count($used_tokens) > 40) {
				$used_tokens = array_slice($used_tokens, -40);
			}
			WC()->session->set('styliiiish_add_used_tokens', $used_tokens);
		}

		$variation = [];
		foreach ($_REQUEST as $request_key => $request_value) {
			if (strpos((string) $request_key, 'attribute_pa_') === 0) {
				$variation[sanitize_key((string) $request_key)] = wc_clean(wp_unslash((string) $request_value));
			}
		}

		$duplicate_guard_state = null;
		if (WC()->session) {
			$variation_for_hash = $variation;
			ksort($variation_for_hash);

			$fingerprint = md5(wp_json_encode([
				'product_id' => $product_id,
				'variation_id' => $variation_id,
				'quantity' => $quantity,
				'variation' => $variation_for_hash,
			]));

			$duplicate_guard_state = WC()->session->get('styliiiish_add_guard');
			$last_hash = is_array($duplicate_guard_state) ? ($duplicate_guard_state['hash'] ?? '') : '';
			$last_time = is_array($duplicate_guard_state) ? (int) ($duplicate_guard_state['time'] ?? 0) : 0;

			if ($last_hash === $fingerprint && (time() - $last_time) <= 2) {
				wp_send_json_success(shopire_styliiiish_build_cart_payload());
			}

			WC()->session->set('styliiiish_add_guard', [
				'hash' => $fingerprint,
				'time' => time(),
			]);
		}

		$cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
		if (!$cart_item_key) {
			wp_send_json_error([
				'message' => __('Unable to add this product to cart.', 'shopire'),
			], 422);
		}

		WC()->cart->calculate_totals();
		wp_send_json_success(shopire_styliiiish_build_cart_payload());
	}
}
add_action('wc_ajax_styliiiish_add_to_cart', 'shopire_styliiiish_wc_ajax_add_to_cart');
add_action('wc_ajax_nopriv_styliiiish_add_to_cart', 'shopire_styliiiish_wc_ajax_add_to_cart');
add_action('wp_ajax_styliiiish_add_to_cart', 'shopire_styliiiish_wc_ajax_add_to_cart');
add_action('wp_ajax_nopriv_styliiiish_add_to_cart', 'shopire_styliiiish_wc_ajax_add_to_cart');

if (!function_exists('shopire_styliiiish_wc_ajax_remove_from_cart')) {
	function shopire_styliiiish_wc_ajax_remove_from_cart() {
		shopire_styliiiish_apply_request_locale();
		if (!function_exists('WC') || !WC()->cart) {
			wp_send_json_error(['message' => 'Cart is unavailable'], 500);
		}

		$cart_key = isset($_REQUEST['cart_key']) ? wc_clean(wp_unslash((string) $_REQUEST['cart_key'])) : '';
		if ($cart_key === '') {
			wp_send_json_error(['message' => 'Missing cart key'], 400);
		}

		$removed = WC()->cart->remove_cart_item($cart_key);
		if (!$removed) {
			wp_send_json_error(['message' => 'Unable to remove item'], 422);
		}

		WC()->cart->calculate_totals();
		wp_send_json_success(shopire_styliiiish_build_cart_payload());
	}
}
add_action('wc_ajax_styliiiish_remove_from_cart', 'shopire_styliiiish_wc_ajax_remove_from_cart');
add_action('wc_ajax_nopriv_styliiiish_remove_from_cart', 'shopire_styliiiish_wc_ajax_remove_from_cart');
add_action('wp_ajax_styliiiish_remove_from_cart', 'shopire_styliiiish_wc_ajax_remove_from_cart');
add_action('wp_ajax_nopriv_styliiiish_remove_from_cart', 'shopire_styliiiish_wc_ajax_remove_from_cart');

if (!function_exists('shopire_styliiiish_wc_ajax_update_cart_qty')) {
	function shopire_styliiiish_wc_ajax_update_cart_qty() {
		shopire_styliiiish_apply_request_locale();
		if (!function_exists('WC') || !WC()->cart) {
			wp_send_json_error(['message' => 'Cart is unavailable'], 500);
		}

		$cart_key = isset($_REQUEST['cart_key']) ? wc_clean(wp_unslash((string) $_REQUEST['cart_key'])) : '';
		$qty = isset($_REQUEST['qty']) ? max(1, absint($_REQUEST['qty'])) : 1;

		if ($cart_key === '') {
			wp_send_json_error(['message' => 'Missing cart key'], 400);
		}

		$updated = WC()->cart->set_quantity($cart_key, $qty, true);
		if ($updated === false) {
			wp_send_json_error(['message' => 'Unable to update quantity'], 422);
		}

		WC()->cart->calculate_totals();
		wp_send_json_success(shopire_styliiiish_build_cart_payload());
	}
}
add_action('wc_ajax_styliiiish_update_cart_qty', 'shopire_styliiiish_wc_ajax_update_cart_qty');
add_action('wc_ajax_nopriv_styliiiish_update_cart_qty', 'shopire_styliiiish_wc_ajax_update_cart_qty');
add_action('wp_ajax_styliiiish_update_cart_qty', 'shopire_styliiiish_wc_ajax_update_cart_qty');
add_action('wp_ajax_nopriv_styliiiish_update_cart_qty', 'shopire_styliiiish_wc_ajax_update_cart_qty');

if (!function_exists('shopire_styliiiish_fix_woocommerce_ajax_endpoint')) {
	function shopire_styliiiish_fix_woocommerce_ajax_endpoint($endpoint, $request) {
		if (!is_string($request) || $request === '') {
			return $endpoint;
		}

		$action = sanitize_key($request);
		if (strpos($action, 'styliiiish_') !== 0) {
			return $endpoint;
		}

		return add_query_arg('action', $action, admin_url('admin-ajax.php'));
	}
}
add_filter('woocommerce_ajax_get_endpoint', 'shopire_styliiiish_fix_woocommerce_ajax_endpoint', 20, 2);

if (!function_exists('shopire_styliiiish_rewrite_broken_checkout_domains')) {
	function shopire_styliiiish_rewrite_broken_checkout_domains($html) {
		if (!is_string($html) || $html === '') {
			return $html;
		}

		$siteBase = rtrim((string) home_url('/'), '/');
		$html = str_replace(
			['https://l.styliiiish.com', 'http://l.styliiiish.com', '//l.styliiiish.com'],
			$siteBase,
			$html
		);

		$checkoutBannerMap = [
			'"banner-img-1.webp"' => '"' . $siteBase . '/wp-content/uploads/2025/12/banner-img-1.webp"',
			'"banner-img-2.webp"' => '"' . $siteBase . '/wp-content/uploads/2025/12/banner-img-2.webp"',
			'"banner-img-3.webp"' => '"' . $siteBase . '/wp-content/uploads/2025/12/banner-img-3.webp"',
			"'banner-img-1.webp'" => "'" . $siteBase . "/wp-content/uploads/2025/12/banner-img-1.webp'",
			"'banner-img-2.webp'" => "'" . $siteBase . "/wp-content/uploads/2025/12/banner-img-2.webp'",
			"'banner-img-3.webp'" => "'" . $siteBase . "/wp-content/uploads/2025/12/banner-img-3.webp'",
		];

		return str_replace(array_keys($checkoutBannerMap), array_values($checkoutBannerMap), $html);
	}
}

if (!function_exists('shopire_styliiiish_checkout_output_buffer')) {
	function shopire_styliiiish_is_checkout_like_request() {
		if (function_exists('is_checkout') && is_checkout()) {
			return true;
		}

		$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
		$decoded_uri = rawurldecode($request_uri);

		$needles = ['/checkout', '/payment', '/الدفع'];
		foreach ($needles as $needle) {
			if (strpos($request_uri, $needle) !== false || strpos($decoded_uri, $needle) !== false) {
				return true;
			}
		}

		return false;
	}
}

if (!function_exists('shopire_styliiiish_checkout_output_buffer')) {
	function shopire_styliiiish_checkout_output_buffer() {
		if (is_admin() || wp_doing_ajax() || !shopire_styliiiish_is_checkout_like_request()) {
			return;
		}

		ob_start('shopire_styliiiish_rewrite_broken_checkout_domains');
	}
}
add_action('template_redirect', 'shopire_styliiiish_checkout_output_buffer', 0);

if (!function_exists('shopire_styliiiish_checkout_permissions_policy')) {
	function shopire_styliiiish_checkout_permissions_policy() {
		if (is_admin()) {
			return;
		}

		if (!shopire_styliiiish_is_checkout_like_request()) {
			return;
		}

		header('Permissions-Policy: payment=*', true);
	}
}
add_action('send_headers', 'shopire_styliiiish_checkout_permissions_policy', 20);

if (!function_exists('shopire_styliiiish_dequeue_checkout_theme_scripts')) {
	function shopire_styliiiish_dequeue_checkout_theme_scripts() {
		if (is_admin() || wp_doing_ajax()) {
			return;
		}

		if (!function_exists('shopire_styliiiish_is_checkout_like_request') || !shopire_styliiiish_is_checkout_like_request()) {
			return;
		}

		wp_dequeue_script('shopire-theme');
		wp_dequeue_script('shopire-custom-js');
	}
}
add_action('wp_enqueue_scripts', 'shopire_styliiiish_dequeue_checkout_theme_scripts', 999);