<?php
/**
 * Theme functions and definitions
 *
 * @package eKart
 */

/**
 * After setup theme hook
 */
function ekart_theme_setup(){
    /*
     * Make child theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'ekart' );	
}
add_action( 'after_setup_theme', 'ekart_theme_setup' );

function ekart_disable_shopire_preloader() {
	remove_action( 'shopire_site_preloader', 'shopire_site_preloader' );
}
add_action( 'after_setup_theme', 'ekart_disable_shopire_preloader', 20 );

function ekart_disable_shopire_wow_animations() {
	if ( is_admin() ) {
		return;
	}

	wp_dequeue_script( 'wow-min' );
	wp_deregister_script( 'wow-min' );

	wp_dequeue_style( 'animate' );
	wp_deregister_style( 'animate' );
}
add_action( 'wp_enqueue_scripts', 'ekart_disable_shopire_wow_animations', 1000 );

/**
 * Load assets.
 */

function ekart_is_account_like_request() {
	if ( is_admin() ) {
		return false;
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return true;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url( $request_uri, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '/';
	$normalized   = rawurldecode( strtolower( rtrim( $request_path, '/' ) ) );

	if ( $normalized === '' ) {
		$normalized = '/';
	}

	$account_prefixes = [
		'/my-account',
		'/en/my-account',
		'/ar/حسابي',
		'/حسابي',
	];

	foreach ( $account_prefixes as $prefix ) {
		if ( $normalized === $prefix || strpos( $normalized, $prefix . '/' ) === 0 ) {
			return true;
		}
	}

	return false;
}

function ekart_theme_css() {
	$child_style_path = get_stylesheet_directory() . '/style.css';
	$child_style_ver  = file_exists( $child_style_path ) ? (string) filemtime( $child_style_path ) : null;
	wp_enqueue_style( 'ekart-style', get_stylesheet_uri(), [ 'shopire-style' ], $child_style_ver );

	if ( ekart_is_account_like_request() ) {
		$account_style_rel  = '/assets/css/account-ui.css';
		$account_script_rel = '/assets/js/account-ajax.js';
		$account_style_path = get_stylesheet_directory() . $account_style_rel;
		$account_script_path = get_stylesheet_directory() . $account_script_rel;

		if ( file_exists( $account_style_path ) ) {
			wp_enqueue_style(
				'ekart-account-ui',
				get_stylesheet_directory_uri() . $account_style_rel,
				[ 'ekart-style', 'shopire-woocommerce' ],
				(string) filemtime( $account_style_path )
			);
		}

		if ( file_exists( $account_script_path ) ) {
			wp_enqueue_script(
				'ekart-account-ajax',
				get_stylesheet_directory_uri() . $account_script_rel,
				[],
				(string) filemtime( $account_script_path ),
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ekart_theme_css', 99);

function ekart_is_flexi_vendor_endpoint_request() {
	if ( is_admin() ) {
		return false;
	}

	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return false;
	}

	if ( function_exists( 'is_wc_endpoint_url' ) ) {
		if (
			is_wc_endpoint_url( 'vendor-dashboard' )
			|| is_wc_endpoint_url( 'vendor_dashboard' )
			|| is_wc_endpoint_url( 'vendor-orders' )
			|| is_wc_endpoint_url( 'vendor_orders' )
			|| is_wc_endpoint_url( 'store-profile' )
			|| is_wc_endpoint_url( 'store_profile' )
		) {
			return true;
		}
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url( $request_uri, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '/';
	$normalized   = rawurldecode( strtolower( rtrim( $request_path, '/' ) ) );

	if ( $normalized === '' ) {
		$normalized = '/';
	}

	$needles = [
		'/my-account/vendor-dashboard',
		'/en/my-account/vendor-dashboard',
		'/my-account/vendor_orders',
		'/en/my-account/vendor_orders',
		'/my-account/vendor-orders',
		'/en/my-account/vendor-orders',
		'/my-account/store-profile',
		'/en/my-account/store-profile',
	];

	foreach ( $needles as $needle ) {
		if ( $normalized === $needle || strpos( $normalized, $needle . '/' ) === 0 ) {
			return true;
		}
	}

	return false;
}

function ekart_force_flexi_account_styles() {
	if ( ! ekart_is_account_like_request() ) {
		return;
	}

	if ( ! defined( 'WF_OWNER_DASHBOARD_URL' ) ) {
		return;
	}

	wp_enqueue_style( 'woocommerce-layout' );
	wp_enqueue_style( 'woocommerce-smallscreen' );
	wp_enqueue_style( 'woocommerce-general' );

	if ( ! defined( 'WF_OWNER_DASHBOARD_PATH' ) || ! is_dir( WF_OWNER_DASHBOARD_PATH ) ) {
		return;
	}

	$base_path = rtrim( (string) WF_OWNER_DASHBOARD_PATH, '/\\' );
	$base_url  = rtrim( (string) WF_OWNER_DASHBOARD_URL, '/' );

	try {
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $base_path, FilesystemIterator::SKIP_DOTS )
		);

		foreach ( $iterator as $file_info ) {
			if ( ! $file_info instanceof SplFileInfo || ! $file_info->isFile() ) {
				continue;
			}

			if ( strtolower( $file_info->getExtension() ) !== 'css' ) {
				continue;
			}

			$absolute_path = str_replace( '\\', '/', $file_info->getPathname() );
			$relative_path = ltrim( str_replace( '\\', '/', substr( $absolute_path, strlen( $base_path ) ) ), '/' );

			if ( $relative_path === '' ) {
				continue;
			}

			$handle = 'wf-flexi-css-' . md5( $relative_path );
			$src    = $base_url . '/' . $relative_path;
			$ver    = (string) $file_info->getMTime();

			wp_enqueue_style( $handle, $src, [], $ver );
		}
	} catch ( Exception $e ) {
		return;
	}
}
add_action( 'wp_print_styles', 'ekart_force_flexi_account_styles', 10001 );

function ekart_prioritize_flexi_account_styles() {
	if ( ! ekart_is_account_like_request() ) {
		return;
	}

	global $wp_styles;
	if ( ! ( $wp_styles instanceof WP_Styles ) || ! is_array( $wp_styles->queue ) ) {
		return;
	}

	$plugin_queue = [];
	$other_queue  = [];
	$plugin_path_needle = '/wp-content/plugins/flexi-multivendor-plugin/';

	foreach ( $wp_styles->queue as $handle ) {
		if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
			$other_queue[] = $handle;
			continue;
		}

		$src = (string) ( $wp_styles->registered[ $handle ]->src ?? '' );
		$src = strtolower( $src );

		$is_plugin_style = strpos( $src, $plugin_path_needle ) !== false
			|| strpos( $handle, 'wf-flexi-css-' ) === 0
			|| in_array(
				$handle,
				[
					'sty-owner-css',
					'sty-owner-mobile-css',
					'wf-add-modal',
					'styliiiish-myaccount-css',
					'wf-vendor-orders',
				],
				true
			);

		if ( $is_plugin_style ) {
			$plugin_queue[] = $handle;
		} else {
			$other_queue[] = $handle;
		}
	}

	$wp_styles->queue = array_merge( $other_queue, $plugin_queue );
}
add_action( 'wp_print_styles', 'ekart_prioritize_flexi_account_styles', 10050 );

function ekart_prioritize_flexi_vendor_endpoint_styles() {
	if ( ! ekart_is_flexi_vendor_endpoint_request() ) {
		return;
	}

	global $wp_styles;
	if ( ! ( $wp_styles instanceof WP_Styles ) || ! is_array( $wp_styles->queue ) ) {
		return;
	}

	$plugin_queue = [];
	$other_queue  = [];
	$plugin_path_needle = '/wp-content/plugins/flexi-multivendor-plugin/';

	foreach ( $wp_styles->queue as $handle ) {
		if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
			$other_queue[] = $handle;
			continue;
		}

		$src = (string) ( $wp_styles->registered[ $handle ]->src ?? '' );
		$src = strtolower( $src );

		$is_plugin_style = strpos( $src, $plugin_path_needle ) !== false
			|| strpos( $handle, 'wf-flexi-css-' ) === 0
			|| in_array(
				$handle,
				[
					'sty-owner-css',
					'sty-owner-mobile-css',
					'wf-vendor-orders',
					'select2-css',
				],
				true
			);

		if ( $is_plugin_style ) {
			$plugin_queue[] = $handle;
		} else {
			$other_queue[] = $handle;
		}
	}

	$wp_styles->queue = array_merge( $other_queue, $plugin_queue );
}
add_action( 'wp_print_styles', 'ekart_prioritize_flexi_vendor_endpoint_styles', 10060 );

function ekart_is_ar_fasatini_request() {
	if ( is_admin() ) {
		return false;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url( $request_uri, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '/';
	$normalized   = rawurldecode( strtolower( rtrim( $request_path, '/' ) ) );

	return $normalized === '/ar/فساتيني';
}

function ekart_disable_trp_dom_changes_on_ar_fasatini() {
	if ( ! ekart_is_ar_fasatini_request() ) {
		return;
	}

	global $wp_scripts;

	if ( ! ( $wp_scripts instanceof WP_Scripts ) || ! is_array( $wp_scripts->queue ) ) {
		return;
	}

	foreach ( $wp_scripts->queue as $handle ) {
		$src = isset( $wp_scripts->registered[ $handle ] ) ? (string) ( $wp_scripts->registered[ $handle ]->src ?? '' ) : '';
		$is_dom_changes_script = ( strpos( $handle, 'trp-translate-dom-changes' ) !== false )
			|| ( strpos( $src, 'trp-translate-dom-changes' ) !== false );

		if ( $is_dom_changes_script ) {
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ekart_disable_trp_dom_changes_on_ar_fasatini', 1001 );

function ekart_customize_my_account_menu_items( $items ) {
	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url( $request_uri, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '/';
	$is_english   = preg_match( '#^/ar(?:/|$)#i', $request_path ) !== 1;

	$labels = $is_english
		? [
			'dashboard'       => 'Dashboard',
			'orders'          => 'Orders',
			'edit-address'    => 'Addresses',
			'edit-account'    => 'Account Details',
			'saved-cards'     => 'Saved Cards',
			'customer-logout' => 'Logout',
		]
		: [
			'dashboard'       => 'لوحة التحكم',
			'orders'          => 'الطلبات',
			'edit-address'    => 'العنوان',
			'edit-account'    => 'تفاصيل الحساب',
			'saved-cards'     => 'البطاقات المحفوظة',
			'customer-logout' => 'تسجيل الخروج',
		];

	foreach ( $labels as $endpoint => $label ) {
		if ( isset( $items[ $endpoint ] ) ) {
			$items[ $endpoint ] = $label;
		}
	}

	$desired_order = [ 'dashboard', 'orders', 'edit-address', 'edit-account', 'saved-cards', 'customer-logout' ];
	$ordered_items = [];

	foreach ( $desired_order as $endpoint ) {
		if ( isset( $items[ $endpoint ] ) ) {
			$ordered_items[ $endpoint ] = $items[ $endpoint ];
		}
	}

	foreach ( $items as $endpoint => $label ) {
		if ( ! isset( $ordered_items[ $endpoint ] ) ) {
			$ordered_items[ $endpoint ] = $label;
		}
	}

	return $ordered_items;
}
add_filter( 'woocommerce_account_menu_items', 'ekart_customize_my_account_menu_items', 20 );

require get_stylesheet_directory() . '/theme-functions/controls/class-customize.php';

/**
 * Import Options From Parent Theme
 *
 */
function ekart_parent_theme_options() {
	$ekart_mods = get_option( 'theme_mods_shopire' );
	if ( ! empty( $ekart_mods ) ) {
		foreach ( $ekart_mods as $ekart_mod_k => $ekart_mod_v ) {
			set_theme_mod( $ekart_mod_k, $ekart_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'ekart_parent_theme_options' );

/**
 * Fix Arabic my-account endpoint 404s by redirecting endpoint tails
 * to WooCommerce my-account endpoint paths while preserving query args.
 */
function ekart_fix_arabic_myaccount_endpoints_404() {
	if ( is_admin() ) {
		return;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url( $request_uri, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '/';
	$normalized   = rawurldecode( strtolower( rtrim( $request_path, '/' ) ) );
	if ( $normalized === '' ) {
		$normalized = '/';
	}

	$arabic_account_bases = [
		'/ar/حسابي',
		'/ara/حسابي',
		'/حسابي',
	];

	$endpoint_tail = '';
	foreach ( $arabic_account_bases as $account_base ) {
		if ( $normalized === $account_base ) {
			return;
		}

		$prefix = $account_base . '/';
		if ( str_starts_with( $normalized, $prefix ) ) {
			$endpoint_tail = ltrim( substr( $normalized, strlen( $prefix ) ), '/' );
			break;
		}
	}

	if ( $endpoint_tail === '' ) {
		return;
	}

	$my_account_permalink = function_exists( 'wc_get_page_permalink' )
		? (string) wc_get_page_permalink( 'myaccount' )
		: (string) home_url( '/my-account/' );

	$my_account_path = wp_parse_url( $my_account_permalink, PHP_URL_PATH );
	$my_account_path = is_string( $my_account_path ) ? rtrim( $my_account_path, '/' ) : '/my-account';
	if ( $my_account_path === '' ) {
		$my_account_path = '/my-account';
	}

	$target = home_url( $my_account_path . '/' . $endpoint_tail . '/' );

	if ( ! empty( $_GET ) ) {
		$sanitized_query = [];
		foreach ( $_GET as $key => $value ) {
			$clean_key = sanitize_key( (string) $key );
			if ( $clean_key === '' ) {
				continue;
			}

			if ( is_array( $value ) ) {
				$sanitized_query[ $clean_key ] = array_map(
					static function ( $item ) {
						return sanitize_text_field( wp_unslash( (string) $item ) );
					},
					$value
				);
			} else {
				$sanitized_query[ $clean_key ] = sanitize_text_field( wp_unslash( (string) $value ) );
			}
		}

		if ( ! empty( $sanitized_query ) ) {
			$target = add_query_arg( $sanitized_query, $target );
		}
	}

	wp_safe_redirect( $target, 302 );
	exit;
}
add_action( 'template_redirect', 'ekart_fix_arabic_myaccount_endpoints_404', 1 );

/**
 * Replace broken legacy asset host on my-account pages.
 */
function ekart_rewrite_legacy_assets_host_for_account_pages() {
	if ( is_admin() ) {
		return;
	}

	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url( $request_uri, PHP_URL_PATH );
	$request_path = is_string( $request_path ) ? $request_path : '/';
	$normalized   = rawurldecode( strtolower( rtrim( $request_path, '/' ) ) );

	$account_prefixes = [
		'/my-account',
		'/en/my-account',
		'/ar/حسابي',
		'/ara/حسابي',
		'/حسابي',
	];

	$is_account_request = false;
	foreach ( $account_prefixes as $prefix ) {
		if ( $normalized === $prefix || str_starts_with( $normalized, $prefix . '/' ) ) {
			$is_account_request = true;
			break;
		}
	}

	if ( ! $is_account_request ) {
		return;
	}

	ob_start(
		static function ( $html ) {
			if ( ! is_string( $html ) || $html === '' ) {
				return $html;
			}

			$replacements = [
				'https://styliiiish.com/' => 'https://styliiiish.com/',
				'http://l.styliiiish.com/'  => 'https://styliiiish.com/',
				'//l.styliiiish.com/'       => '//styliiiish.com/',
			];

			return strtr( $html, $replacements );
		}
	);
}
add_action( 'template_redirect', 'ekart_rewrite_legacy_assets_host_for_account_pages', 2 );

function ekart_should_inject_no_translation_guard_script() {
	return false;
}

function ekart_output_no_translation_guard_script() {
	if ( ! ekart_should_inject_no_translation_guard_script() ) {
		return;
	}
	?>
	<script id="ekart-no-translation-guard">
	(function(){
		var markerSelector='[data-no-translation]';
		var arabicPattern=/[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/;
		var skipTagMap={SCRIPT:1,STYLE:1,NOSCRIPT:1,TEXTAREA:1,INPUT:1,OPTION:1};
		var arabicTextLocks=new WeakMap();
		var arabicElementLocks=new WeakMap();
		var markNode=function(node){
			if(!node||node.nodeType!==1){return;}
			if(node.closest&&node.closest('[data-allow-ar-translation]')){return;}
			node.setAttribute('translate','no');
			node.classList.add('notranslate','trp-no-translate');
		};
		var lockElementArabicText=function(element){
			if(!element||element.nodeType!==1){return;}
			if(skipTagMap[element.tagName]){return;}
			if(element.closest&&element.closest('[data-allow-ar-translation]')){return;}
			if(element.children&&element.children.length===0){
				var textValue=String(element.textContent||'').trim();
				if(textValue!==''&&arabicPattern.test(textValue)&&!arabicElementLocks.has(element)){
					arabicElementLocks.set(element,textValue);
					markNode(element);
				}
			}
		};
		var restoreLockedElement=function(element){
			if(!element||element.nodeType!==1){return;}
			var original=arabicElementLocks.get(element);
			if(typeof original==='string'){
				var current=String(element.textContent||'').trim();
				if(current!==original){
					element.textContent=original;
				}
				markNode(element);
			}
		};
		var markArabicTextContainers=function(root){
			if(!root){return;}
			if(root.nodeType===3){
				var textValue=String(root.nodeValue||'').trim();
				if(textValue!==''&&arabicPattern.test(textValue)&&root.parentElement){
					if(!arabicTextLocks.has(root)){
						arabicTextLocks.set(root,String(root.nodeValue||''));
					}
					lockElementArabicText(root.parentElement);
					markNode(root.parentElement);
				}
				return;
			}
			if(root.nodeType!==1){return;}
			var startNode=root;
			var walker=document.createTreeWalker(startNode,NodeFilter.SHOW_TEXT,null);
			var current;
			while((current=walker.nextNode())){
				var parent=current.parentElement;
				if(!parent||skipTagMap[parent.tagName]){continue;}
				var value=String(current.nodeValue||'').trim();
				if(value!==''&&arabicPattern.test(value)){
					if(!arabicTextLocks.has(current)){
						arabicTextLocks.set(current,String(current.nodeValue||''));
					}
					lockElementArabicText(parent);
					markNode(parent);
				}
			}
		};
		var markTree=function(root){
			if(!root||root.nodeType!==1){return;}
			if(root.matches&&root.matches(markerSelector)){markNode(root);}
			if(root.querySelectorAll){
				root.querySelectorAll(markerSelector).forEach(markNode);
			}
			markArabicTextContainers(root);
		};
		markTree(document.documentElement||document.body);
		markArabicTextContainers(document.body||document.documentElement);
		if(!window.MutationObserver){return;}
		var observer=new MutationObserver(function(mutations){
			mutations.forEach(function(mutation){
				if(mutation.type==='characterData'){
					var textNode=mutation.target;
					var lockedText=arabicTextLocks.get(textNode);
					if(typeof lockedText==='string'){
						if(String(textNode.nodeValue||'')!==lockedText){
							textNode.nodeValue=lockedText;
						}
						if(textNode.parentElement){
							restoreLockedElement(textNode.parentElement);
						}
						return;
					}
					if(arabicPattern.test(String(textNode.nodeValue||''))){
						arabicTextLocks.set(textNode,String(textNode.nodeValue||''));
						if(textNode.parentElement){
							lockElementArabicText(textNode.parentElement);
							markNode(textNode.parentElement);
						}
					}
					return;
				}
				restoreLockedElement(mutation.target&&mutation.target.nodeType===1?mutation.target:null);
				if(!mutation.addedNodes||!mutation.addedNodes.length){return;}
				mutation.addedNodes.forEach(function(added){
					markTree(added);
					markArabicTextContainers(added);
					if(added&&added.nodeType===1){
						restoreLockedElement(added);
					}
				});
			});
		});
		observer.observe(document.documentElement,{childList:true,subtree:true,characterData:false});
		setTimeout(function(){
			try{observer.disconnect();}catch(e){}
		},8000);
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'ekart_output_no_translation_guard_script', 0 );