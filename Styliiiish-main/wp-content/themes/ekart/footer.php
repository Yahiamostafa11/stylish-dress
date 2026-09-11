</div></div>
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
	$account_url = $is_english ? 'https://styliiiish.com/my-account/' : 'https://styliiiish.com/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/';
	$my_dresses_url = $is_english ? 'https://styliiiish.com/my-dresses/' : 'https://styliiiish.com/ar/%d9%81%d8%b3%d8%a7%d8%aa%d9%8a%d9%86%d9%8a/';
	$hour_now = (int) current_time('G');
	$is_open_now = ($hour_now >= 11 && $hour_now < 19);
?>

<style>
	.site-footer{margin-top:8px;background:#0f1a2a;color:#fff;border-top:4px solid #d51522}
	.footer-wrap{width:min(1180px,92%);margin:0 auto}
	.footer-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr 1fr;gap:18px;padding:34px 0 22px}
	.footer-brand,.footer-col{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
	.footer-brand h4,.footer-col h5{margin:0 0 10px;font-size:18px;color:#fff}
	.footer-brand p{margin:0 0 10px;color:#b8c2d1;font-size:14px}
	.footer-status,.footer-open-hours{margin:0 0 10px;color:#b8c2d1;font-size:14px}
	.status-pill{display:inline-flex;align-items:center;justify-content:center;padding:3px 9px;border-radius:999px;font-size:12px;font-weight:800;border:1px solid transparent;line-height:1.2}
	.status-pill.is-open{color:#0a8f5b;border-color:rgba(10,143,91,.45);background:rgba(10,143,91,.14)}
	.status-pill.is-closed{color:#d51522;border-color:rgba(213,21,34,.45);background:rgba(213,21,34,.14)}
	.footer-links{list-style:none;margin:0;padding:0;display:grid;gap:7px}
	.footer-links a{color:#b8c2d1;font-size:14px;text-decoration:none;transition:.2s ease}
	.footer-links a:hover{color:#fff}
	.footer-contact-row{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
	.footer-contact-row a{color:#fff;background:rgba(213,21,34,.16);border:1px solid rgba(213,21,34,.35);border-radius:999px;padding:6px 10px;font-size:12px;font-weight:700;text-decoration:none}
	.footer-bottom{border-top:1px solid rgba(255,255,255,.14);padding:12px 0 20px;display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;color:#b8c2d1;font-size:13px}
	.footer-bottom a{color:#fff;text-decoration:none}
	.footer-mini-nav{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;padding-bottom:18px}
	.footer-mini-nav a{color:#b8c2d1;font-size:13px;text-decoration:none}
	.footer-mini-nav a:hover{color:#fff}
	@media (max-width:900px){.footer-grid{grid-template-columns:1fr}}
</style>
<footer class="site-footer">
	<div class="footer-wrap footer-grid">
		<div class="footer-brand">
			<img src="<?php echo esc_url($wp_logo); ?>" alt="Styliiiish" style="height:62px;width:auto;max-width:min(360px,100%);margin-bottom:10px;">
			<h4>Styliiiish Fashion House</h4>
			<p>We are passionate about offering the latest dress designs for every special occasion.</p>
			<p class="footer-status">Status : <span class="status-pill <?php echo $is_open_now ? 'is-open' : 'is-closed'; ?>"><?php echo $is_open_now ? 'Open' : 'Closed'; ?></span></p>
			<p class="footer-open-hours"><strong>Working Hours:</strong> Saturday – Friday: 11:00 AM – 7:00 PM</p>
			<div class="footer-contact-row">
				<a href="<?php echo esc_url($build_localized_url('/contact-us')); ?>">Contact Us</a>
				<a href="tel:+201050874255">Direct Call</a>
			</div>
		</div>
		<div class="footer-col">
			<h5>Quick Links</h5>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url($build_localized_url('/')); ?>">Home</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/blog')); ?>">Blog</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/shop')); ?>">Shop Dresses Now</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/shop')); ?>">Shop</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/marketplace')); ?>">Marketplace</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/categories')); ?>">Categories</a></li>
				<li><a href="<?php echo esc_url($my_dresses_url); ?>" target="_blank" rel="noopener">Sell Your Dress</a></li>
				<li><a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener">My Account</a></li>
			</ul>
		</div>
		<div class="footer-col">
			<h5>Official Info</h5>
			<ul class="footer-links">
				<li><a href="https://maps.app.goo.gl/MCdcFEcFoR4tEjpT8" target="_blank" rel="noopener">1 Nabil Khalil St, Nasr City, Cairo, Egypt</a></li>
				<li><a href="tel:+201050874255">+2 010-5087-4255</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/contact-us')); ?>">Contact Us</a></li>
			</ul>
		</div>
		<div class="footer-col">
			<h5>Policies & Legal</h5>
			<ul class="footer-links">
				<li><a href="<?php echo esc_url($build_localized_url('/about-us')); ?>">About Us</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/privacy-policy')); ?>">Privacy Policy</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/terms-conditions')); ?>">Terms & Conditions</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/marketplace-policy')); ?>">Marketplace Policy</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/refund-return-policy')); ?>">Refund & Return Policy</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/faq')); ?>">FAQ</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/shipping-delivery-policy')); ?>">Shipping & Delivery Policy</a></li>
				<li><a href="<?php echo esc_url($build_localized_url('/cookie-policy')); ?>">Cookie Policy</a></li>
			</ul>
		</div>
	</div>
	<div class="footer-wrap footer-bottom">
		<span><?php echo 'All rights reserved © ' . esc_html(date('Y')) . ' Styliiiish | Powered by '; ?><a href="https://websiteflexi.com/" target="_blank" rel="noopener">Website Flexi</a></span>
		<span><a href="<?php echo esc_url($build_localized_url('/')); ?>">styliiiish.com</a></span>
	</div>
	<div class="footer-wrap footer-mini-nav">
		<a href="<?php echo esc_url($build_localized_url('/')); ?>">Home</a>
		<a href="<?php echo esc_url($build_localized_url('/shop')); ?>">Shop</a>
		<a href="<?php echo esc_url($build_localized_url('/cart')); ?>">Cart</a>
		<a href="<?php echo esc_url($account_url); ?>" target="_blank" rel="noopener">Account</a>
		<a href="<?php echo esc_url($build_localized_url('/wishlist')); ?>">Wishlist</a>
	</div>
</footer>
<?php 
	// Top Scroller
	do_action('shopire_top_scroller');
?>
<?php 
wp_footer(); ?>
</body>
</html>
