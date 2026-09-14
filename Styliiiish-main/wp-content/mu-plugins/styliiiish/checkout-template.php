<?php
/**
 * Minimal, self-contained checkout/pay page — served via template_include
 * from styliiiish-checkout-branding.php instead of the active theme's full
 * page template. Deliberately does NOT call get_header()/get_footer(): those
 * pull in the theme's storefront chrome, which is exactly what this avoids.
 * Still calls wp_head()/wp_footer() so WooCommerce/Paymob's own scripts,
 * styles, and nonces keep working — only the surrounding decoration changes.
 */

if (!defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title(''); ?></title>
<?php wp_head(); ?>
<style>
    body{margin:0;background:#FEFDFA;font-family:"Cairo","Tajawal","Segoe UI",system-ui,sans-serif;color:#433131}
    .styliiiish-minimal-header{
        display:flex;align-items:center;justify-content:space-between;
        padding:18px 24px;background:#fff;border-bottom:1px solid #EFE7E4;
    }
    .styliiiish-minimal-brand{display:flex;align-items:center;gap:8px;font-size:20px;font-weight:700;color:#2F1014;text-decoration:none}
    .styliiiish-minimal-secure{
        display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;
        color:#3F7160;background:#F1F7F3;padding:6px 12px;border-radius:999px;
    }
    .styliiiish-minimal-main{max-width:900px;margin:0 auto;padding:32px 24px}
    .styliiiish-minimal-footer{background:#2F1014;padding:18px 24px;text-align:center;font-size:11.5px;color:#C7ABA6;margin-top:40px}
    .styliiiish-minimal-footer a{color:#fff;text-decoration:none}
</style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="styliiiish-minimal-header">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="styliiiish-minimal-brand">
        <svg width="26" height="29" viewBox="0 0 40 44" fill="none" aria-hidden="true">
            <path d="M28 7c-4-3-11-3-14 1-3 3.4-2 8 2 10.4l7 4.2c4 2.4 5 7 2 10.4-3 4-10 4-14 1" stroke="#BA5D70" stroke-width="3.4" stroke-linecap="round" />
            <path d="M11 37c4 3 11 3 14-1 3-3.4 2-8-2-10.4l-7-4.2C12 19 11 14.4 14 11c3-4 10-4 14-1" stroke="#3F7160" stroke-width="3.4" stroke-linecap="round" opacity=".85" />
        </svg>
        <span>Styliiiish</span>
    </a>
    <span class="styliiiish-minimal-secure">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2" /><path d="M8 10V7a4 4 0 0 1 8 0v3" /></svg>
        <?php echo esc_html__('Secure Payment', 'styliiiish'); ?>
    </span>
</div>

<main class="styliiiish-minimal-main">
    <?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
</main>

<div class="styliiiish-minimal-footer">
    &copy; <?php echo esc_html(date('Y')); ?> Styliiiish &middot;
    <a href="mailto:hello@styliiiish.com">hello@styliiiish.com</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
