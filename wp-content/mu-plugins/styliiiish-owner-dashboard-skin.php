<?php
/**
 * Plugin Name: Styliiiish – Owner Dashboard skin
 * Description: Gives the owner dashboard page (/owner-dashboard/) the same look as the new storefront:
 *              new header + slim footer, Cairo type, rose palette, rounded cards. Appearance only —
 *              the Flexi/TaajVendor plugin's markup and behaviour are untouched.
 */

if (!defined('ABSPATH')) {
	exit;
}

/** True on the owner dashboard page (works for the English and Arabic permalinks). */
function styliiiish_od_is_dashboard() {
	return function_exists('is_page') && (is_page('owner-dashboard') || is_page(1893));
}

/** True on WooCommerce "My account" and all of its routes (orders, addresses, details, saved cards, moderate-site…). */
function styliiiish_od_is_account() {
	return function_exists('is_account_page') && is_account_page();
}

/** Both share the same chrome (header, footer, type, palette). */
function styliiiish_od_skin_active() {
	return styliiiish_od_is_dashboard() || styliiiish_od_is_account();
}

add_filter('body_class', function ($classes) {
	if (styliiiish_od_skin_active()) {
		$classes[] = 'sty-od';
	}
	if (styliiiish_od_is_account()) {
		$classes[] = 'sty-acct';
	}

	return $classes;
});

add_action('wp_enqueue_scripts', function () {
	if (!styliiiish_od_skin_active()) {
		return;
	}

	$rel  = 'mu-plugins/styliiiish/owner-dashboard-skin.css';
	$file = WP_CONTENT_DIR . '/' . $rel;

	// Loads after the plugin's own sheets so equal-specificity rules resolve in our favour.
	wp_enqueue_style(
		'styliiiish-od-skin',
		content_url($rel),
		array_filter(array(
			wp_style_is('sty-owner-css', 'registered') ? 'sty-owner-css' : null,
			wp_style_is('sty-owner-mobile-css', 'registered') ? 'sty-owner-mobile-css' : null,
		)),
		file_exists($file) ? (string) filemtime($file) : null
	);
}, 100);

/** Labels in the viewer's language (the site is Arabic-first, the dashboard can be viewed in either). */
function styliiiish_od_skin_labels() {
	if (is_rtl()) {
		return array(
			'tag'   => 'أزياء تحكي قصتك',
			'nav'   => array(
				'/'            => 'الرئيسية',
				'/shop'        => 'فساتين',
				'/rent'        => 'إيجار',
				'/sell'        => 'بيعي فستانك',
				'/reviews'     => 'آراء عملائنا',
			),
			'owner' => 'لوحة المالك',
			'acct'  => 'حسابي',
			'store' => 'الرجوع للمتجر',
			'out'   => 'تسجيل خروج',
			'foot'  => 'جميع الحقوق محفوظة',
		);
	}

	return array(
		'tag'   => 'Fashion that tells your story',
		'nav'   => array(
			'/'        => 'Home',
			'/shop'    => 'Dresses',
			'/rent'    => 'Rent',
			'/sell'    => 'Sell Your Dress',
			'/reviews' => 'Reviews',
		),
		'owner' => 'Owner dashboard',
		'acct'  => 'My account',
		'store' => 'Back to store',
		'out'   => 'Log out',
		'foot'  => 'All rights reserved',
	);
}

add_action('wp_body_open', function () {
	if (!styliiiish_od_skin_active()) {
		return;
	}

	$l       = styliiiish_od_skin_labels();
	$account = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/');
	?>
	<header class="sty-od-header">
		<div class="sty-od-bar">
			<a class="sty-od-brand" href="<?php echo esc_url(home_url('/')); ?>">
				<img src="<?php echo esc_url(home_url('/brand-mark.svg')); ?>" alt="" width="40" height="44">
				<span class="sty-od-brand-txt">
					<span class="sty-od-brand-name">Styliiiish</span>
					<span class="sty-od-brand-tag"><?php echo esc_html($l['tag']); ?></span>
				</span>
			</a>
			<nav class="sty-od-nav" aria-label="Main">
				<?php foreach ($l['nav'] as $path => $label) : ?>
					<a href="<?php echo esc_url(home_url($path)); ?>"><?php echo esc_html($label); ?></a>
				<?php endforeach; ?>
			</nav>
			<div class="sty-od-actions">
				<?php if (is_user_logged_in()) : ?>
					<?php if (!styliiiish_od_is_account()) : ?>
						<a class="sty-od-chip" href="<?php echo esc_url($account); ?>"><?php echo esc_html($l['acct']); ?></a>
					<?php endif; ?>
					<a class="sty-od-chip" href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><?php echo esc_html($l['out']); ?></a>
				<?php endif; ?>
				<a class="sty-od-chip solid" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($l['store']); ?></a>
			</div>
		</div>
	</header>
	<?php
}, 5);

add_action('wp_footer', function () {
	if (!styliiiish_od_skin_active()) {
		return;
	}

	$l = styliiiish_od_skin_labels();
	?>
	<div class="sty-od-footer">
		&copy; <?php echo esc_html(gmdate('Y')); ?> <a href="<?php echo esc_url(home_url('/')); ?>">Styliiiish</a> &middot; <?php echo esc_html($l['foot']); ?>
	</div>
	<?php
}, 1);
