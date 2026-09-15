<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php echo esc_url(get_bloginfo( 'pingback_url' )); ?>">
		<?php endif; ?>

		<?php wp_head(); ?>
	</head>
<body <?php body_class('section-title-one btn--effect-six menu__active-one'); ?>>
<?php wp_body_open(); ?>

<?php
	$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';
	$request_path = parse_url($request_uri, PHP_URL_PATH);
	$request_path = is_string($request_path) ? $request_path : '/';
	$normalized_path = rawurldecode(strtolower(rtrim($request_path, '/')));
	if ($normalized_path === '') {
		$normalized_path = '/';
	}
	$is_english = preg_match('#^/ar(?:/|$)#i', $request_path) !== 1;
	$locale_prefix = $is_english ? '' : '/ar';
	$wp_base_url = rtrim((string) get_option('home'), '/');
	if ($wp_base_url === '') {
		$wp_base_url = rtrim(home_url('/'), '/');
	}

	$build_localized_url = static function (string $path = '') use ($wp_base_url, $locale_prefix): string {
		$clean_path = trim($path);
		$clean_path = $clean_path === '' ? '' : ('/' . ltrim($clean_path, '/'));
		$clean_path = preg_replace('#^/(ar|en)(?=/|$)#i', '', (string) $clean_path);
		$clean_path = $clean_path === null ? '' : $clean_path;

		return rtrim($wp_base_url, '/') . $locale_prefix . $clean_path;
	};
	$wp_logo = $wp_base_url . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
	$my_dresses_url = $is_english ? 'https://styliiiish.com/my-dresses/' : 'https://styliiiish.com/ar/%d9%81%d8%b3%d8%a7%d8%aa%d9%8a%d9%86%d9%8a/';
	$path_without_locale = preg_replace('#^/(ar|en)(?=/|$)#i', '', $request_path);
	$path_without_locale = is_string($path_without_locale) ? $path_without_locale : $request_path;
	$path_without_locale = '/' . ltrim($path_without_locale, '/');
	if ($path_without_locale === '//') {
		$path_without_locale = '/';
	}
	$is_account_path = in_array($normalized_path, ['/my-account', '/en/my-account', '/ar/حسابي'], true);
	$is_account_orders_path = in_array($normalized_path, ['/my-account/orders', '/en/my-account/orders', '/ar/حسابي/طلبات'], true);
	$is_account_edit_address_path = in_array($normalized_path, ['/my-account/edit-address', '/en/my-account/edit-address', '/ar/حسابي/تعديل-العنوان'], true);
	$is_account_edit_account_path = in_array($normalized_path, ['/my-account/edit-account', '/en/my-account/edit-account', '/ar/حسابي/تعديل-الحساب'], true);
	$is_account_saved_cards_path = in_array($normalized_path, ['/my-account/saved-cards', '/en/my-account/saved-cards', '/ar/حسابي/البطاقة-المحفوظة'], true);
	$is_account_moderate_site_path = in_array($normalized_path, ['/my-account/moderate-site', '/en/my-account/moderate-site', '/ar/حسابي/موقع-متوسط'], true);
	$is_my_dresses_path = in_array($normalized_path, ['/my-dresses', '/ar/فساتيني'], true);
	$is_owner_dashboard_path = in_array($normalized_path, ['/owner-dashboard', '/ar/لوحة-معلومات-المالك'], true);
	if ($is_account_path) {
		$ar_switch_path = '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/';
		$en_switch_path = '/my-account/';
	} elseif ($is_account_moderate_site_path) {
		$ar_switch_path = '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/%d9%85%d9%88%d9%82%d8%b9-%d9%85%d8%aa%d9%88%d8%b3%d8%b7/';
		$en_switch_path = '/my-account/moderate-site/';
	} elseif ($is_account_orders_path) {
		$ar_switch_path = '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/%d8%b7%d9%84%d8%a8%d8%a7%d8%aa/';
		$en_switch_path = '/my-account/orders/';
	} elseif ($is_account_edit_address_path) {
		$ar_switch_path = '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/%d8%aa%d8%b9%d8%af%d9%8a%d9%84-%d8%a7%d9%84%d8%b9%d9%86%d9%88%d8%a7%d9%86/';
		$en_switch_path = '/my-account/edit-address/';
	} elseif ($is_account_edit_account_path) {
		$ar_switch_path = '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/%d8%aa%d8%b9%d8%af%d9%8a%d9%84-%d8%a7%d9%84%d8%ad%d8%b3%d8%a7%d8%a8/';
		$en_switch_path = '/my-account/edit-account/';
	} elseif ($is_account_saved_cards_path) {
		$ar_switch_path = '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/%d8%a7%d9%84%d8%a8%d8%b7%d8%a7%d9%82%d8%a9-%d8%a7%d9%84%d9%85%d8%ad%d9%81%d9%88%d8%b8%d8%a9/';
		$en_switch_path = '/my-account/saved-cards/';
	} elseif ($is_my_dresses_path) {
		$ar_switch_path = '/ar/%d9%81%d8%b3%d8%a7%d8%aa%d9%8a%d9%86%d9%8a/';
		$en_switch_path = '/my-dresses/';
	} elseif ($is_owner_dashboard_path) {
		$ar_switch_path = '/ar/%d9%84%d9%88%d8%ad%d8%a9-%d9%85%d8%b9%d9%84%d9%88%d9%85%d8%a7%d8%aa-%d8%a7%d9%84%d9%85%d8%a7%d9%84%d9%83/';
		$en_switch_path = '/owner-dashboard/';
	} else {
		$ar_switch_path = '/ar' . ($path_without_locale === '/' ? '' : $path_without_locale);
		$en_switch_path = ($path_without_locale === '/' ? '/' : $path_without_locale);
	}
	$query_string = isset($_SERVER['QUERY_STRING']) && (string) $_SERVER['QUERY_STRING'] !== '' ? ('?' . (string) $_SERVER['QUERY_STRING']) : '';
	$ar_switch_url = rtrim($wp_base_url, '/') . $ar_switch_path . $query_string;
	$en_switch_url = rtrim($wp_base_url, '/') . $en_switch_path . $query_string;
	$wishlist_count = 0;
	$wishlist_items_payload = [];
	if (function_exists('fable_extra_woowishlist_get_list')) {
		$wishlist_raw_list = (array) fable_extra_woowishlist_get_list();
		$wishlist_count = count($wishlist_raw_list);
		foreach (array_slice($wishlist_raw_list, 0, 6) as $wishlist_product_id) {
			$product = function_exists('wc_get_product') ? wc_get_product((int) $wishlist_product_id) : null;
			if (!$product || !is_object($product)) {
				continue;
			}
			$wishlist_items_payload[] = [
				'name' => wp_strip_all_tags((string) $product->get_name()),
				'url' => get_permalink((int) $product->get_id()),
				'image' => wp_get_attachment_image_url((int) $product->get_image_id(), 'woocommerce_thumbnail') ?: (function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src('woocommerce_thumbnail') : ''),
			];
		}
	}
	$initial_cart_payload = null;
	if (function_exists('shopire_styliiiish_build_cart_payload')) {
		$initial_cart_payload = shopire_styliiiish_build_cart_payload();
	}
	$cart_summary_url = admin_url('admin-ajax.php?action=styliiiish_cart_summary');
	$request_lang = $is_english ? 'en' : 'ar';
?>

<style>
	:root{--wf-main-rgb:213,21,34;--wf-main-color:rgb(var(--wf-main-rgb));--wf-secondary-color:#17273B;--line:rgba(189,189,189,.4);--primary:var(--wf-main-color);--secondary:var(--wf-secondary-color);--muted:#5a6678}
	.container{width:min(1180px,92%);margin:0 auto}
	.topbar{background:var(--secondary);color:#fff;font-size:13px;border-bottom:1px solid rgba(255,255,255,.12)}
	.topbar-inner{min-height:42px;display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap}
	.topbar-left,.topbar-right{display:flex;align-items:center;gap:14px;flex-wrap:wrap}
	.topbar a{color:#fff;opacity:.92;text-decoration:none}.topbar a:hover{opacity:1}
	.topbar-desktop-contact{display:inline-flex;align-items:center;gap:8px}
	.topbar-note{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.14);border-radius:999px;padding:4px 10px;font-weight:700;font-size:12px}
	.lang-switch{position:relative;display:inline-grid;grid-template-columns:1fr 1fr;align-items:center;direction:ltr;width:110px;height:34px;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.28);border-radius:999px;padding:3px;overflow:hidden}
	.lang-switch .lang-indicator{position:absolute;top:3px;width:calc(50% - 3px);height:calc(100% - 6px);background:#fff;border-radius:999px;transition:.25s ease;z-index:1}
	.lang-switch.is-ar .lang-indicator{left:3px}.lang-switch.is-en .lang-indicator{right:3px}
	.lang-switch a{position:relative;z-index:2;text-align:center;font-size:12px;font-weight:800;opacity:.95;color:#fff;padding:5px 0;text-decoration:none}
	.lang-switch a.active{color:var(--secondary);opacity:1}
	.topbar-mobile-social,.topbar-mobile-lang{display:none;position:fixed;inset-inline-end:14px;z-index:10002;pointer-events:auto}
	.topbar-mobile-social{bottom:18px;z-index:120}.topbar-mobile-lang{bottom:86px}
	.topbar-lang-toggle{min-width:56px;height:44px;border-radius:999px;border:1px solid rgba(255,255,255,.72);background:rgba(15,26,42,.98);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;letter-spacing:.6px;box-shadow:0 10px 24px rgba(10,17,30,.35);cursor:pointer;touch-action:manipulation}
	.topbar-lang-panel{position:absolute;bottom:0;inset-inline-end:calc(100% + 8px);display:grid;gap:6px;padding:8px;min-width:56px;border-radius:12px;border:1px solid rgba(255,255,255,.22);background:rgba(15,26,42,.94);box-shadow:0 12px 28px rgba(10,17,30,.34);opacity:0;transform:translateX(10px);pointer-events:none;transition:opacity .2s ease,transform .25s ease;z-index:10003}
	.topbar-lang-panel.is-open{opacity:1;transform:translateX(0);pointer-events:auto}
	.topbar-lang-panel a{height:30px;border-radius:999px;border:1px solid rgba(255,255,255,.24);background:rgba(255,255,255,.12);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;text-decoration:none}
	.topbar-lang-panel a.active{background:#fff;color:var(--secondary)}
	.topbar-social-toggle{width:56px;height:56px;border-radius:999px;border:1px solid rgba(255,255,255,.72);background:rgba(15,26,42,.98);color:#fff;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 14px 32px rgba(10,17,30,.45);cursor:pointer;touch-action:manipulation}
	.topbar-social-toggle svg{width:24px;height:24px;fill:currentColor}
	.topbar-mobile-icons{position:absolute;bottom:0;inset-inline-end:calc(100% + 8px);z-index:10003;min-width:48px;padding:8px;border-radius:14px;border:1px solid rgba(255,255,255,.22);background:rgba(15,26,42,.94);box-shadow:0 14px 30px rgba(10,17,30,.32);display:flex;flex-direction:column;align-items:center;gap:9px;opacity:0;transform:translateX(16px) scale(.9);pointer-events:none;transition:opacity .24s ease,transform .38s ease}
	.topbar-mobile-icons.is-open{opacity:1;transform:translateX(0) scale(1);pointer-events:auto}
	.topbar-mobile-icon{width:32px;height:32px;border-radius:999px;border:1px solid rgba(255,255,255,.35);background:rgba(255,255,255,.14);display:inline-flex;align-items:center;justify-content:center;color:#fff;text-decoration:none}
	.topbar-mobile-icon svg{width:16px;height:16px;fill:currentColor}
	.topbar-mobile-icon.icon-call{color:#fff}
	.topbar-mobile-icon.icon-whatsapp{color:#25D366}
	.topbar-mobile-icon.icon-facebook{color:#1877F2}
	.topbar-mobile-icon.icon-instagram{color:#E1306C}
	.topbar-mobile-icon.icon-tiktok{color:#111111}
	.topbar-mobile-icon.icon-google{color:#4285F4}
	.main-header{background:#fff;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:40;box-shadow:0 8px 24px rgba(23,39,59,.06)}
	.main-header-inner{min-height:96px;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:16px}
	.brand{display:flex;flex-direction:column;gap:2px;text-decoration:none}
	.brand-logo{height:56px!important;max-height:56px!important;width:auto!important;max-width:min(260px,42vw)!important;object-fit:contain!important;display:block}
	.brand-tag{color:var(--muted);font-size:12px;font-weight:600}
	.main-nav{display:flex;justify-content:center;align-items:center;gap:8px;flex-wrap:wrap;background:#f9fbff;border:1px solid var(--line);border-radius:12px;padding:6px}
	.main-nav a{color:var(--secondary);font-size:14px;font-weight:700;padding:8px 12px;border-radius:8px;transition:.2s ease;text-decoration:none}
	.main-nav a:hover{color:var(--primary);background:#fff4f5}
	.header-actions{display:flex;align-items:center;gap:8px;justify-content:flex-end;flex-wrap:wrap}
	.search-form{display:flex;align-items:center;gap:6px}
	.search-input{height:38px;min-width:190px;border:1px solid var(--line);border-radius:10px;padding:0 12px;font-size:13px;outline:none}
	.search-btn{height:38px;border:none;border-radius:10px;background:#17273B;color:#fff;padding:0 12px;font-size:13px;font-weight:700;cursor:pointer}
	.icon-wrap{position:relative;display:inline-flex;align-items:center}
	.icon-btn{position:relative;width:40px;height:40px;min-width:40px;min-height:40px;border:1px solid rgba(23,39,59,.16);border-radius:12px;background:linear-gradient(180deg,#fff 0%,#f8faff 100%);display:inline-flex;align-items:center;justify-content:center;text-decoration:none;color:var(--secondary);font-size:16px;box-shadow:0 4px 12px rgba(23,39,59,.08);transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease,color .18s ease}
	.icon-btn:hover{border-color:rgba(213,21,34,.38);color:var(--primary);box-shadow:0 8px 18px rgba(23,39,59,.12);transform:translateY(-1px)}
	.icon-btn:active{transform:translateY(0)}
	.icon-plus-one{position:absolute;top:-12px;right:-4px;background:var(--primary);color:#fff;border-radius:999px;padding:1px 6px;font-size:10px;font-weight:900;line-height:1.2;box-shadow:0 6px 14px rgba(213,21,34,.35);opacity:0;transform:translateY(0);pointer-events:none}
	.cart-trigger-wrap,.account-trigger-wrap{position:relative}
	.account-menu{position:absolute;top:calc(100% + 8px);inset-inline-end:0;width:min(260px,78vw);background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:0 12px 26px rgba(23,39,59,.18);padding:10px;display:none;z-index:70}
	.account-menu.is-open{display:grid;gap:6px}
	.account-menu-head{border-bottom:1px solid var(--line);padding-bottom:8px;margin-bottom:2px;display:grid;gap:2px}
	.account-menu-head strong{color:var(--secondary);font-size:13px;line-height:1.35}
	.account-menu-head span{color:var(--muted);font-size:12px}
	.account-menu a{min-height:34px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;text-decoration:none}
	#accountMenuManage{background:var(--secondary);color:#fff}
	#accountMenuLogout{background:#fff;border:1px solid var(--line);color:var(--secondary)}
	.wishlist-trigger-wrap{position:relative}
	.wishlist-dropdown{position:absolute;top:calc(100% + 10px);inset-inline-end:0;width:min(360px,82vw);background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:0 12px 30px rgba(23,39,59,.14);padding:10px;display:none;z-index:90}
	.wishlist-dropdown.is-open{display:block}
	.wishlist-dropdown-list{display:grid;gap:8px;max-height:360px;overflow:auto}
	.wishlist-dropdown-item{display:grid;grid-template-columns:56px 1fr;gap:10px;border:1px solid var(--line);border-radius:10px;padding:8px;background:#fff}
	.wishlist-dropdown-item img{width:56px;height:56px;object-fit:cover;border-radius:8px;background:#f2f2f5}
	.wishlist-dropdown-name{font-size:13px;font-weight:800;color:var(--secondary);margin:0 0 6px;line-height:1.35}
	.wishlist-dropdown-link{display:inline-flex;align-items:center;justify-content:center;min-height:30px;padding:0 10px;border-radius:8px;background:var(--primary);color:#fff;font-size:12px;font-weight:700;text-decoration:none}
	.wishlist-dropdown-empty{margin:0;font-size:13px;color:var(--muted);text-align:center;padding:12px 8px;border:1px dashed var(--line);border-radius:10px;background:#fbfcff}
	.wishlist-dropdown-footer{display:flex;justify-content:center;margin-top:10px;padding-top:10px;border-top:1px solid var(--line)}
	.wishlist-dropdown-all{font-size:13px;color:var(--primary);font-weight:800;text-decoration:none}
	.wishlist-count{position:absolute;top:-7px;right:-7px;min-width:18px;height:18px;border-radius:999px;background:var(--primary);color:#fff;font-size:10px;line-height:18px;text-align:center;font-weight:900;padding:0 4px;display:inline-block;border:2px solid #fff;box-shadow:0 4px 10px rgba(213,21,34,.35)}
	.cart-count{position:absolute;top:-7px;right:-7px;min-width:18px;height:18px;border-radius:999px;background:var(--primary);color:#fff;font-size:10px;line-height:18px;text-align:center;font-weight:900;padding:0 4px;display:inline-block;border:2px solid #fff;box-shadow:0 4px 10px rgba(213,21,34,.35)}
	.mini-cart{position:fixed;inset:0;z-index:110;pointer-events:none}
	.mini-cart.is-open{pointer-events:auto}
	.mini-cart-backdrop{position:absolute;inset:0;background:rgba(15,26,42,.52);opacity:0;transition:.2s ease}
	.mini-cart.is-open .mini-cart-backdrop{opacity:1}
	.mini-cart-panel{position:absolute;top:0;right:0;width:min(430px,92vw);height:100%;background:#fff;border-inline-start:1px solid var(--line);display:grid;grid-template-rows:auto 1fr auto;transform:translateX(100%);transition:.24s ease;box-shadow:-10px 0 30px rgba(23,39,59,.14)}
	.mini-cart.is-open .mini-cart-panel{transform:translateX(0)}
	[dir="rtl"] .mini-cart-panel{right:auto;left:0;border-inline-start:0;border-inline-end:1px solid var(--line);transform:translateX(-100%)}
	[dir="rtl"] .mini-cart.is-open .mini-cart-panel{transform:translateX(0)}
	.mini-cart-head{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:12px;border-bottom:1px solid var(--line)}
	.mini-cart-head h3{margin:0;font-size:17px;color:var(--secondary)}
	.mini-cart-close{border:1px solid var(--line);border-radius:8px;background:#fff;color:var(--secondary);padding:6px 10px;cursor:pointer;font-family:inherit;font-weight:700}
	.mini-cart-list{overflow:auto;padding:12px;display:grid;gap:10px;align-content:start}
	.mini-cart-item{display:grid;grid-template-columns:70px 1fr auto;gap:10px;border:1px solid var(--line);border-radius:10px;padding:9px;align-items:center}
	.mini-cart-item img{width:70px;height:92px;object-fit:cover;border-radius:9px;background:#f2f2f5}
	.mini-cart-item h4{margin:0 0 4px;font-size:13px;line-height:1.35;color:var(--secondary);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
	.mini-cart-meta{font-size:12px;color:var(--muted);display:flex;gap:6px;align-items:center}
	.mini-cart-price{font-size:12px;color:var(--primary);font-weight:800;margin-top:4px}
	.mini-cart-remove{border:1px solid rgba(var(--wf-main-rgb),.2);background:#fff;color:var(--primary);border-radius:8px;min-width:34px;min-height:34px;font-weight:800;cursor:pointer}
	.mini-cart-empty{color:var(--muted);font-size:14px;padding:8px 0;text-align:center;border:1px dashed var(--line);border-radius:10px;background:#fbfcff}
	.mini-cart-foot{border-top:1px solid var(--line);padding:12px;display:grid;gap:8px}
	.mini-cart-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px}
	.mini-cart-actions a{min-height:40px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;text-decoration:none}
	.mini-cart-view{border:1px solid var(--line);background:#fff;color:var(--secondary)}
	.mini-cart-checkout{background:var(--primary);color:#fff}
	.header-cta{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:10px;font-size:14px;font-weight:700;background:var(--primary);color:#fff;text-decoration:none}
	.action-nav-toggle{display:none}
	.promo{background:linear-gradient(90deg,var(--secondary),#24384f);color:#fff;text-align:center;padding:10px 16px;font-size:12px;font-weight:600}
	.header-categories-strip{background:linear-gradient(180deg,#ffffff 0%,#fbfcff 100%);border-bottom:1px solid rgba(23,39,59,.08)}
	.categories-strip-inner{display:flex;align-items:center;gap:8px;padding:10px 0;overflow-x:auto;scrollbar-width:none}
	.categories-strip-inner::-webkit-scrollbar{display:none}
	.category-strip-group{flex:0 0 auto;display:inline-flex;align-items:center;gap:6px;padding-inline-end:6px;border-inline-end:1px dashed rgba(23,39,59,.14)}
	.category-strip-group:last-child{border-inline-end:0;padding-inline-end:0}
	.category-strip-chip{flex:0 0 auto;min-height:36px;border:1px solid rgba(23,39,59,.12);border-radius:999px;background:#fff;color:var(--secondary);padding:0 14px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;text-decoration:none;box-shadow:0 2px 8px rgba(23,39,59,.05);transition:transform .2s ease,border-color .2s ease,color .2s ease,background-color .2s ease,box-shadow .2s ease}
	.category-strip-chip:hover{border-color:var(--primary);color:var(--primary);background:#fff4f5;box-shadow:0 6px 14px rgba(23,39,59,.1);transform:translateY(-2px)}
	@media (max-width:980px){.main-header-inner{grid-template-columns:1fr auto;gap:8px;min-height:auto;padding:10px 0}.brand{align-items:center;text-align:center}.brand-logo{height:44px!important;max-height:44px!important;max-width:240px!important}.brand-tag{font-size:11px}.main-nav{grid-column:1 / -1;margin-top:4px;border-radius:10px;padding:5px;gap:6px;-webkit-overflow-scrolling:touch;scroll-snap-type:x proximity;scrollbar-width:none;display:none;justify-content:flex-start}.main-nav.is-open{display:flex}.main-nav::-webkit-scrollbar{display:none}.main-nav a{font-size:12px;padding:7px 10px;scroll-snap-align:start}.header-actions{justify-content:flex-end;gap:6px;flex-wrap:nowrap}.action-nav-toggle{display:inline-flex}.search-form{display:none}.icon-btn{width:36px;height:36px;min-width:36px;min-height:36px;padding:0;font-size:14px;border-radius:10px}.wishlist-count,.cart-count{top:-6px;right:-6px;min-width:16px;height:16px;line-height:16px;font-size:9px}.action-sell{display:none}.action-account,.action-cart,.action-wishlist{min-width:46px;justify-content:center}}
	@media (max-width:980px){#wpadminbar{display:none!important}.mobile-bottom-nav-wrapper,.mobile-bottom-nav{display:none!important;visibility:hidden!important;opacity:0!important;pointer-events:none!important}}
	@media (max-width:640px){.topbar{background:transparent;border-bottom:0;height:0;overflow:visible}.topbar-inner{min-height:0;padding:0}.topbar-left,.topbar-desktop-contact{display:none}.topbar-right{width:100%;justify-content:flex-end}.topbar-mobile-social,.topbar-mobile-lang{display:inline-flex!important}.topbar-mobile-social{inset-inline-end:10px;bottom:12px}.topbar-mobile-lang{inset-inline-end:10px;bottom:80px}.categories-strip-inner{padding:8px 0;gap:6px}.category-strip-group{gap:5px;padding-inline-end:5px}.category-strip-chip{min-height:32px;padding:0 10px;font-size:12px}}
	@media (max-width:390px){.action-account,.action-cart,.action-wishlist{min-width:42px;font-size:11px}.brand-logo{height:36px!important;max-width:200px!important}.main-nav a{font-size:11px;padding:6px 9px}}
</style>

	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ekart' ); ?></a>

		<div class="topbar">
			<div class="container topbar-inner">
				<div class="topbar-right">
					<div class="topbar-desktop-contact">
						<strong>Call us anytime:</strong>
						<a href="tel:+201050874255" dir="ltr" lang="en">+20 010 5087 4255</a>
					</div>
					<div class="topbar-mobile-lang" data-no-translation>
						<button class="topbar-lang-toggle" id="topbarLangToggle" type="button" aria-controls="topbarLangPanel" aria-expanded="false"><?php echo esc_html($is_english ? 'EN' : 'AR'); ?></button>
						<div class="topbar-lang-panel" id="topbarLangPanel" aria-hidden="true">
							<a class="<?php echo !$is_english ? 'active' : ''; ?>" href="<?php echo esc_url($ar_switch_url); ?>" onclick="window.location.href='<?php echo esc_js($ar_switch_url); ?>';return false;">AR</a>
							<a class="<?php echo $is_english ? 'active' : ''; ?>" href="<?php echo esc_url($en_switch_url); ?>" onclick="window.location.href='<?php echo esc_js($en_switch_url); ?>';return false;">EN</a>
						</div>
					</div>
					<div class="topbar-mobile-social">
						<button class="topbar-social-toggle" id="topbarSocialToggle" type="button" aria-controls="topbarSocialPanel" aria-expanded="false">
							<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 14a3 3 0 0 0-2.24 1l-4.27-2.14a3.1 3.1 0 0 0 0-1.72l4.27-2.14a3 3 0 1 0-.9-1.8L9.59 9.34a3 3 0 1 0 0 5.32l4.27 2.14A3 3 0 1 0 17 14z"/></svg>
						</button>
						<div class="topbar-mobile-icons" id="topbarSocialPanel" aria-hidden="true">
							<a class="topbar-mobile-icon icon-call" href="tel:+201050874255" aria-label="Call"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.2 15.2 0 0 0 6.6 6.6l2.2-2.2a1.5 1.5 0 0 1 1.5-.37c1.1.36 2.28.55 3.5.55A1.5 1.5 0 0 1 22 16.9V21a1.5 1.5 0 0 1-1.5 1.5C11.94 22.5 1.5 12.06 1.5 3.5A1.5 1.5 0 0 1 3 2h4.1a1.5 1.5 0 0 1 1.5 1.27c.1 1.22.3 2.4.66 3.5a1.5 1.5 0 0 1-.37 1.52l-2.3 2.5z"/></svg></a>
							<a class="topbar-mobile-icon icon-whatsapp" href="https://wa.me/201050874255" target="_blank" rel="noopener" aria-label="WhatsApp"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.52 3.48A11.9 11.9 0 0 0 12.07 0C5.5 0 .17 5.33.17 11.9c0 2.1.55 4.16 1.6 5.98L0 24l6.33-1.66a11.87 11.87 0 0 0 5.73 1.46h.01c6.57 0 11.9-5.33 11.9-11.9 0-3.18-1.24-6.17-3.45-8.42z"/></svg></a>
							<a class="topbar-mobile-icon icon-facebook" href="https://www.facebook.com/Styliiish.Egypt/" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.87.25-1.46 1.5-1.46h1.6V5.02c-.28-.04-1.23-.12-2.33-.12-2.3 0-3.87 1.4-3.87 4v2.1H8v3h2.4v8h3.1z"/></svg></a>
							<a class="topbar-mobile-icon icon-instagram" href="https://www.instagram.com/styliiiish.egypt/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2z"/></svg></a>
							<a class="topbar-mobile-icon icon-tiktok" href="https://www.tiktok.com/@styliiish_?_r=1&_t=ZS-94HEUy9a0RE" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.13V2h-3.1v12.4a2.74 2.74 0 1 1-1.88-2.6V8.67a5.84 5.84 0 1 0 5 5.79V8.17a7.91 7.91 0 0 0 4.62 1.48V6.69h-.87z"/></svg></a>
							<a class="topbar-mobile-icon icon-google" href="https://g.page/styliish" target="_blank" rel="noopener" aria-label="Google"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 12.27c0-.82-.07-1.4-.23-2H12v4.26h6.61c-.13 1.06-.86 2.66-2.47 3.74l3.81 2.8c2.28-2.1 3.55-5.2 3.55-8.94z"/></svg></a>
						</div>
					</div>
				</div>
				<div class="topbar-left">
					<div class="lang-switch <?php echo $is_english ? 'is-en' : 'is-ar'; ?>" aria-label="Language Switcher" data-no-translation data-en-url="<?php echo esc_attr($en_switch_url); ?>" data-ar-url="<?php echo esc_attr($ar_switch_url); ?>">
						<span class="lang-indicator" aria-hidden="true"></span>
						<a class="<?php echo !$is_english ? 'active' : ''; ?>" href="<?php echo esc_url($ar_switch_url); ?>" onclick="var sw=this.parentElement;sw.classList.remove('is-en');sw.classList.add('is-ar');this.classList.add('active');this.nextElementSibling&&this.nextElementSibling.classList.remove('active');window.location.href='<?php echo esc_js($ar_switch_url); ?>';return false;">AR</a>
						<a class="<?php echo $is_english ? 'active' : ''; ?>" href="<?php echo esc_url($en_switch_url); ?>" onclick="var sw=this.parentElement;sw.classList.remove('is-ar');sw.classList.add('is-en');this.classList.add('active');this.previousElementSibling&&this.previousElementSibling.classList.remove('active');window.location.href='<?php echo esc_js($en_switch_url); ?>';return false;">EN</a>
					</div>
					<script>
					(function(){
						var sw = document.currentScript && document.currentScript.previousElementSibling;
						if(!sw || !sw.classList || !sw.classList.contains('lang-switch')) return;
						var current = (window.location.pathname || '').replace(/\/+$/,'').toLowerCase();
						var isEnglishPath = (current === '/my-account' || current === '/en/my-account');
						var links = sw.querySelectorAll('a');
						if(links.length !== 2) return;
						var arLink = links[0];
						var enLink = links[1];
						if(isEnglishPath){
							sw.classList.remove('is-ar');
							sw.classList.add('is-en');
							enLink.classList.add('active');
							arLink.classList.remove('active');
						}else{
							sw.classList.remove('is-en');
							sw.classList.add('is-ar');
							arLink.classList.add('active');
							enLink.classList.remove('active');
						}
					})();
					</script>
					<span class="topbar-note">⚡ Daily Deals</span>
					<a href="https://www.facebook.com/Styliiish.Egypt/" target="_blank" rel="noopener">Facebook</a>
					<a href="https://www.instagram.com/styliiiish.egypt/" target="_blank" rel="noopener">Instagram</a>
					<a href="https://g.page/styliish" target="_blank" rel="noopener">Google</a>
				</div>
			</div>
		</div>
		<header class="main-header">
			<div class="container main-header-inner">
				<a class="brand" href="<?php echo esc_url($build_localized_url('/')); ?>">
					<img class="brand-logo" src="<?php echo esc_url($wp_logo); ?>" alt="Styliiiish">
					<span class="brand-tag">Because every woman deserves to shine</span>
				</a>
				<nav class="main-nav" id="headerMainNav" aria-label="Main Navigation">
					<a href="<?php echo esc_url($build_localized_url('/')); ?>">Home</a>
					<a href="<?php echo esc_url($build_localized_url('/shop')); ?>">Shop</a>
					<a href="<?php echo esc_url($build_localized_url('/blog')); ?>">Blog</a>
					<a href="<?php echo esc_url($build_localized_url('/about-us')); ?>">About</a>
					<a href="<?php echo esc_url($build_localized_url('/contact-us')); ?>">Contact</a>
				</nav>
				<div class="header-actions">
					<button class="icon-btn action-nav-toggle" id="headerNavToggle" type="button" aria-label="Menu" aria-controls="headerMainNav" aria-expanded="false">☰</button>
					<form class="search-form" action="<?php echo esc_url($build_localized_url('/shop')); ?>" method="get">
						<input class="search-input" type="search" name="q" required placeholder="<?php echo esc_attr('Search for your dress...'); ?>" aria-label="<?php echo esc_attr('Search for your dress...'); ?>">
						<button class="search-btn" type="submit">Search</button>
					</form>
					<span class="account-trigger-wrap action-account" data-no-translation>
						<a class="icon-btn account-trigger" id="accountLoginTrigger" href="<?php echo esc_url($is_english ? 'https://styliiiish.com/my-account/' : 'https://styliiiish.com/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/'); ?>" aria-label="<?php echo esc_attr('My Account'); ?>" title="<?php echo esc_attr('My Account'); ?>" aria-expanded="false" aria-controls="accountMenu">👤</a>
						<div class="account-menu" id="accountMenu" role="dialog" aria-label="<?php echo esc_attr('My Account'); ?>" aria-hidden="true">
							<div class="account-menu-head">
								<strong><?php echo esc_html($is_english ? 'My Account' : 'حسابي'); ?></strong>
								<span><?php echo esc_html($is_english ? 'Quick access to your account' : 'وصول سريع إلى حسابك'); ?></span>
							</div>
							<a id="accountMenuManage" href="<?php echo esc_url($is_english ? 'https://styliiiish.com/my-account/' : 'https://styliiiish.com/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/'); ?>"><?php echo esc_html($is_english ? 'Manage account' : 'إدارة الحساب'); ?></a>
							<a id="accountMenuLogout" href="<?php echo esc_url($build_localized_url('/my-account')); ?>"><?php echo esc_html($is_english ? 'Open account page' : 'فتح صفحة الحساب'); ?></a>
						</div>
					</span>
					<span class="icon-wrap wishlist-trigger-wrap action-wishlist" data-no-translation>
						<button class="icon-btn wishlist-trigger" id="wishlistTrigger" type="button" aria-label="<?php echo esc_attr('Wishlist'); ?>" title="<?php echo esc_attr('Wishlist'); ?>" aria-expanded="false" aria-controls="wishlistDropdown">❤<span class="wishlist-count" id="wishlistCountBadge"><?php echo esc_html((string) $wishlist_count); ?></span></button>
						<span class="icon-plus-one">+1</span>
						<div class="wishlist-dropdown" id="wishlistDropdown" role="dialog" aria-label="<?php echo esc_attr('Wishlist'); ?>" aria-hidden="true">
							<div class="wishlist-dropdown-list" id="wishlistDropdownList">
								<p class="wishlist-dropdown-empty"><?php echo esc_html($is_english ? 'Loading wishlist…' : 'جاري تحميل المفضلة…'); ?></p>
							</div>
							<div class="wishlist-dropdown-footer">
								<a class="wishlist-dropdown-all" href="<?php echo esc_url($build_localized_url('/wishlist')); ?>"><?php echo esc_html($is_english ? 'View full wishlist' : 'عرض كل المفضلة'); ?></a>
							</div>
						</div>
					</span>
					<span class="icon-wrap cart-trigger-wrap action-cart" data-no-translation>
						<button class="icon-btn cart-trigger" id="miniCartTrigger" type="button" aria-label="<?php echo esc_attr('Cart'); ?>" title="<?php echo esc_attr('Cart'); ?>" aria-expanded="false" aria-controls="miniCart">🛒<span class="cart-count" id="cartCountBadge"><?php echo esc_html((string) ((int) ($initial_cart_payload['count'] ?? 0))); ?></span></button>
						<span class="icon-plus-one">+1</span>
					</span>
					<a class="header-cta action-sell" href="<?php echo esc_url($my_dresses_url); ?>" target="_blank" rel="noopener">Start Selling</a>
				</div>
			</div>
		</header>
		<div class="mini-cart" id="miniCart" aria-hidden="true" data-no-translation>
			<div class="mini-cart-backdrop" data-close-mini-cart></div>
			<aside class="mini-cart-panel" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr($is_english ? 'Shopping Cart' : 'سلة التسوق'); ?>">
				<div class="mini-cart-head" data-no-translation>
					<h3 data-no-translation><?php echo esc_html($is_english ? 'Shopping Cart' : 'سلة التسوق'); ?></h3>
					<button class="mini-cart-close" type="button" data-close-mini-cart data-no-translation><?php echo esc_html($is_english ? 'Close' : 'إغلاق'); ?></button>
				</div>
				<div class="mini-cart-list" id="miniCartList">
					<p class="mini-cart-empty"><?php echo esc_html($is_english ? 'Loading cart…' : 'جاري تحميل السلة…'); ?></p>
				</div>
				<div class="mini-cart-foot" data-no-translation>
					<div class="mini-cart-actions">
						<a class="mini-cart-view" href="<?php echo esc_url($build_localized_url('/cart')); ?>"><?php echo esc_html($is_english ? 'View Cart' : 'عرض السلة'); ?></a>
						<a class="mini-cart-checkout" href="<?php echo esc_url($build_localized_url('/checkout')); ?>"><?php echo esc_html($is_english ? 'Checkout' : 'الدفع'); ?></a>
					</div>
				</div>
			</aside>
		</div>
		<script>
		(function(){
			var initialWishlistItems=<?php echo wp_json_encode($wishlist_items_payload); ?>;
			var initialWishlistCount=<?php echo (int) $wishlist_count; ?>;
			var initialCartPayload=<?php echo wp_json_encode($initial_cart_payload ?: ['count' => 0, 'items' => []]); ?>;
			var cartSummaryUrl=<?php echo wp_json_encode($cart_summary_url); ?>;
			var requestLang=<?php echo wp_json_encode($request_lang); ?>;
			var wishlistLoadingText=<?php echo wp_json_encode($is_english ? 'Loading wishlist…' : 'جاري تحميل المفضلة…'); ?>;
			var wishlistEmptyText=<?php echo wp_json_encode($is_english ? 'Your wishlist is empty.' : 'لا توجد منتجات في المفضلة.'); ?>;
			var goToProductText=<?php echo wp_json_encode($is_english ? 'Go to product' : 'الذهاب للمنتج'); ?>;
			var cartEmptyText=<?php echo wp_json_encode($is_english ? 'Your cart is empty.' : 'السلة فارغة.'); ?>;
			var qtyText=<?php echo wp_json_encode($is_english ? 'Qty' : 'الكمية'); ?>;
			var removeText=<?php echo wp_json_encode($is_english ? 'Remove' : 'حذف'); ?>;
			var loadingCartText=<?php echo wp_json_encode($is_english ? 'Loading cart…' : 'جاري تحميل السلة…'); ?>;
			var navToggle=document.getElementById('headerNavToggle');
			var nav=document.getElementById('headerMainNav');
			var langToggle=document.getElementById('topbarLangToggle');
			var langPanel=document.getElementById('topbarLangPanel');
			var socialToggle=document.getElementById('topbarSocialToggle');
			var socialPanel=document.getElementById('topbarSocialPanel');
			var wishlistTrigger=document.getElementById('wishlistTrigger');
			var wishlistDropdown=document.getElementById('wishlistDropdown');
			var wishlistDropdownList=document.getElementById('wishlistDropdownList');
			var wishlistCountBadge=document.getElementById('wishlistCountBadge');
			var accountTrigger=document.getElementById('accountLoginTrigger');
			var accountMenu=document.getElementById('accountMenu');
			var cartTrigger=document.getElementById('miniCartTrigger');
			var cartCountBadge=document.getElementById('cartCountBadge');
			var miniCart=document.getElementById('miniCart');
			var miniCartList=document.getElementById('miniCartList');
			var miniCartClosers=miniCart?miniCart.querySelectorAll('[data-close-mini-cart]'):[];
			var cartPayloadCache=initialCartPayload||null;
			var escapeHtml=function(value){return String(value||'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');};
			var setWishlistCount=function(count){var n=Math.max(0,Number(count)||0);if(!wishlistCountBadge)return;wishlistCountBadge.textContent=String(n);wishlistCountBadge.style.display=n>0?'inline-block':'none';};
			var setCartCount=function(count){var n=Math.max(0,Number(count)||0);if(!cartCountBadge)return;cartCountBadge.textContent=String(n);cartCountBadge.style.display=n>0?'inline-block':'none';};
			var renderWishlist=function(items){if(!wishlistDropdownList)return;var list=Array.isArray(items)?items:[];if(list.length===0){wishlistDropdownList.innerHTML='<p class="wishlist-dropdown-empty">'+escapeHtml(wishlistEmptyText)+'</p>';return;}wishlistDropdownList.innerHTML=list.map(function(item){var image=escapeHtml(item.image||'');var name=escapeHtml(item.name||'');var url=escapeHtml(item.url||'#');return '<article class="wishlist-dropdown-item"><a href="'+url+'"><img src="'+image+'" alt="'+name+'"></a><div><h4 class="wishlist-dropdown-name">'+name+'</h4><a class="wishlist-dropdown-link" href="'+url+'">'+escapeHtml(goToProductText)+'</a></div></article>';}).join('');};
			var renderMiniCart=function(payload){if(!miniCartList)return;var data=payload||{};var items=Array.isArray(data.items)?data.items:[];setCartCount(Number(data.count||0));if(items.length===0){miniCartList.innerHTML='<p class="mini-cart-empty">'+escapeHtml(cartEmptyText)+'</p>';return;}miniCartList.innerHTML=items.map(function(item){var name=escapeHtml(item.name||'');var url=escapeHtml(item.url||'#');var image=escapeHtml(item.image||'');var qty=Math.max(1,Number(item.qty||1));var priceHtml=String(item.line_total_html||item.price_html||'');return '<article class="mini-cart-item"><a href="'+url+'"><img src="'+image+'" alt="'+name+'"></a><div class="mini-cart-info"><h4>'+name+'</h4><div class="mini-cart-meta"><span>'+escapeHtml(qtyText)+':</span><strong>'+qty+'</strong></div><div class="mini-cart-price">'+priceHtml+'</div></div><button type="button" class="mini-cart-remove" disabled>'+escapeHtml(removeText)+'</button></article>';}).join('');};
			var fetchCartSummary=function(){if(!cartSummaryUrl)return Promise.resolve();var sep=cartSummaryUrl.indexOf('?')>-1?'&':'?';var url=cartSummaryUrl+sep+'lang='+encodeURIComponent(requestLang||'')+'&_='+(Date.now());return fetch(url,{method:'GET',credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}}).then(function(res){if(!res.ok)throw new Error('cart_summary_failed');return res.json();}).then(function(json){if(!json||!json.success)return;cartPayloadCache=json.data||null;renderMiniCart(cartPayloadCache);});};
			var closeNav=function(){if(!navToggle||!nav)return;nav.classList.remove('is-open');navToggle.setAttribute('aria-expanded','false');};
			var closeLang=function(){if(!langToggle||!langPanel)return;langPanel.classList.remove('is-open');langPanel.setAttribute('aria-hidden','true');langToggle.setAttribute('aria-expanded','false');};
			var closeSocial=function(){if(!socialToggle||!socialPanel)return;socialPanel.classList.remove('is-open');socialPanel.setAttribute('aria-hidden','true');socialToggle.setAttribute('aria-expanded','false');};
			var closeWishlist=function(){if(!wishlistTrigger||!wishlistDropdown)return;wishlistDropdown.classList.remove('is-open');wishlistDropdown.setAttribute('aria-hidden','true');wishlistTrigger.setAttribute('aria-expanded','false');};
			var openWishlist=function(){if(!wishlistTrigger||!wishlistDropdown)return;wishlistDropdown.classList.add('is-open');wishlistDropdown.setAttribute('aria-hidden','false');wishlistTrigger.setAttribute('aria-expanded','true');};
			var closeAccountMenu=function(){if(!accountTrigger||!accountMenu)return;accountMenu.classList.remove('is-open');accountMenu.setAttribute('aria-hidden','true');accountTrigger.setAttribute('aria-expanded','false');};
			var openAccountMenu=function(){if(!accountTrigger||!accountMenu)return;accountMenu.classList.add('is-open');accountMenu.setAttribute('aria-hidden','false');accountTrigger.setAttribute('aria-expanded','true');};
			var closeMiniCart=function(){if(!miniCart)return;miniCart.classList.remove('is-open');miniCart.setAttribute('aria-hidden','true');if(cartTrigger)cartTrigger.setAttribute('aria-expanded','false');document.body.style.overflow='';};
			var openMiniCart=function(){if(!miniCart)return;miniCart.classList.add('is-open');miniCart.setAttribute('aria-hidden','false');if(cartTrigger)cartTrigger.setAttribute('aria-expanded','true');document.body.style.overflow='hidden';};
			if(navToggle&&nav){navToggle.addEventListener('click',function(){var open=!nav.classList.contains('is-open');nav.classList.toggle('is-open',open);navToggle.setAttribute('aria-expanded',open?'true':'false');});}
			if(langToggle&&langPanel){langToggle.addEventListener('click',function(e){if(e){e.preventDefault();e.stopPropagation();}var open=!langPanel.classList.contains('is-open');langPanel.classList.toggle('is-open',open);langPanel.setAttribute('aria-hidden',open?'false':'true');langToggle.setAttribute('aria-expanded',open?'true':'false');if(open)closeSocial();});document.addEventListener('click',function(e){var wrap=langToggle.closest('.topbar-mobile-lang');if(wrap&&!wrap.contains(e.target))closeLang();});}
			if(socialToggle&&socialPanel){socialToggle.addEventListener('click',function(e){if(e){e.preventDefault();e.stopPropagation();}var open=!socialPanel.classList.contains('is-open');socialPanel.classList.toggle('is-open',open);socialPanel.setAttribute('aria-hidden',open?'false':'true');socialToggle.setAttribute('aria-expanded',open?'true':'false');if(open)closeLang();});document.addEventListener('click',function(e){var wrap=socialToggle.closest('.topbar-mobile-social');if(wrap&&!wrap.contains(e.target))closeSocial();});}
			if(wishlistTrigger&&wishlistDropdown){wishlistTrigger.addEventListener('click',function(e){e.preventDefault();if(wishlistDropdown.classList.contains('is-open')){closeWishlist();return;}closeAccountMenu();closeMiniCart();openWishlist();renderWishlist(initialWishlistItems);});}
			if(accountTrigger&&accountMenu){accountTrigger.addEventListener('click',function(e){e.preventDefault();if(accountMenu.classList.contains('is-open')){closeAccountMenu();return;}closeWishlist();closeMiniCart();openAccountMenu();});}
			if(cartTrigger&&miniCart){cartTrigger.addEventListener('click',function(){closeWishlist();closeAccountMenu();openMiniCart();if(miniCartList&&(!cartPayloadCache||!Array.isArray(cartPayloadCache.items))){miniCartList.innerHTML='<p class="mini-cart-empty">'+escapeHtml(loadingCartText)+'</p>';}if(cartPayloadCache){renderMiniCart(cartPayloadCache);}fetchCartSummary().catch(function(){});});}
			if(miniCartClosers.length){miniCartClosers.forEach(function(node){node.addEventListener('click',closeMiniCart);});}
			document.addEventListener('click',function(e){if(wishlistTrigger&&wishlistDropdown&&!wishlistTrigger.contains(e.target)&&!wishlistDropdown.contains(e.target))closeWishlist();if(accountTrigger&&accountMenu&&!accountTrigger.contains(e.target)&&!accountMenu.contains(e.target))closeAccountMenu();});
			document.addEventListener('keydown',function(e){if(e.key!=='Escape')return;closeMiniCart();closeWishlist();closeAccountMenu();closeLang();closeSocial();});
			window.addEventListener('resize',function(){if(window.innerWidth>640){closeNav();closeLang();closeSocial();}if(window.innerWidth>980){closeWishlist();closeAccountMenu();closeMiniCart();}});
			setWishlistCount(initialWishlistCount);
			renderWishlist(initialWishlistItems);
			if(cartPayloadCache){renderMiniCart(cartPayloadCache);}else{setCartCount(0);} 
			fetchCartSummary().catch(function(){});
		})();
		</script>
		<div class="promo">Because every woman deserves to shine • Up to 50% OFF • Delivery across Egypt in 2–10 business days</div>
		<div class="header-categories-strip">
			<div class="container categories-strip-inner">
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=dress')); ?>">Dress</a></div>
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=bridesmaid-dresses')); ?>">Bridesmaid Dresses</a></div>
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=evening-dresses')); ?>">Evening Dresses</a></div>
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=final-clearance-dresses')); ?>">Final Clearance Dresses</a></div>
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=plus-size-dresses')); ?>">Plus Size Dresses</a></div>
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=mothers-dresses')); ?>">Mother of the Bride Dresses</a></div>
				<div class="category-strip-group"><a class="category-strip-chip" href="<?php echo esc_url($build_localized_url('/shop?category=pre-loved-dresses')); ?>">Pre-Loved Dresses</a></div>
			</div>
		</div>
	
	<div id="content" class="site-content">
	