<?php

$is_https_request =
    (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') ||
    (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) ||
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && stripos((string) $_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false);

if (!headers_sent()) {
    header('X-Hybrid-Router: active');
    header_remove('X-Powered-By');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), usb=()');

    if ($is_https_request) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// ===== Laravel Routing =====
$request_uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');

// Normalize trailing slash except root
$path = rtrim($request_uri, '/');
$path = $path === '' ? '/' : $path;

// Ensure Arabic my-account logout endpoint never 404s before WP boot.
if (in_array($path, ['/ar/حسابي/customer-logout', '/ara/حسابي/customer-logout', '/حسابي/customer-logout'], true)) {
    $target = '/my-account/customer-logout/';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $target .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('Location: ' . $target, true, 302);
    exit;
}

// Always serve favicon from known files before any framework routing.
if ($path === '/favicon.ico') {
    $favicon_candidates = [
        __DIR__ . '/wp-content/uploads/2025/11/cropped-ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png',
        __DIR__ . '/laravel_home/public/favicon.ico',
        __DIR__ . '/laravel_home/public/brand/icons.png',
    ];

    foreach ($favicon_candidates as $candidate) {
        if (is_file($candidate)) {
            $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
            $mime = $ext === 'ico' ? 'image/x-icon' : ($ext === 'png' ? 'image/png' : 'application/octet-stream');
            header('Content-Type: ' . $mime);
            header('Cache-Control: public, max-age=604800');
            readfile($candidate);
            exit;
        }
    }
}

// Keep rental landing bilingual under WordPress + TranslatePress context.
if (in_array($path, ['/ar/dress-rental-in-cairo', '/ar/dress-rental-in-cairo/', '/ar/تأجير-فساتين-في-القاهرة', '/ar/تأجير-فساتين-في-القاهرة/'], true)) {
    setcookie('trp_language', 'ar', [
        'expires' => time() + (30 * 24 * 60 * 60),
        'path' => '/',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
}

if (in_array($path, ['/dress-rental-in-cairo', '/dress-rental-in-cairo/'], true)) {
    setcookie('trp_language', 'en', [
        'expires' => time() + (30 * 24 * 60 * 60),
        'path' => '/',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
}

/**
 * Single source of truth for what this router is allowed to stream with readfile().
 *
 * SECURITY: this is an allowlist, not a convenience map. Anything whose extension is
 * absent from this table is never streamed — otherwise the router happily returns the
 * plain-text source of .php / .env / .bak / .sql files that happen to sit under one of
 * the served roots. Do not "just add" an extension here without asking whether serving
 * that file type as-is could disclose source or secrets.
 */
$styliiiish_servable_mime_types = [
    // images
    'png'   => 'image/png',
    'jpg'   => 'image/jpeg',
    'jpeg'  => 'image/jpeg',
    'gif'   => 'image/gif',
    'svg'   => 'image/svg+xml',
    'ico'   => 'image/x-icon',
    'webp'  => 'image/webp',
    'avif'  => 'image/avif',
    'bmp'   => 'image/bmp',
    // styles / scripts
    'css'   => 'text/css; charset=UTF-8',
    'js'    => 'application/javascript; charset=UTF-8',
    'mjs'   => 'application/javascript; charset=UTF-8',
    'json'  => 'application/json; charset=UTF-8',
    'map'   => 'application/json; charset=UTF-8',
    // text
    'txt'   => 'text/plain; charset=UTF-8',
    'xml'   => 'application/xml; charset=UTF-8',
    // fonts
    'woff'  => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf'   => 'font/ttf',
    'otf'   => 'font/otf',
    'eot'   => 'application/vnd.ms-fontobject',
    // media / documents
    'mp4'   => 'video/mp4',
    'webm'  => 'video/webm',
    'ogg'   => 'audio/ogg',
    'mp3'   => 'audio/mpeg',
    'pdf'   => 'application/pdf',
];

/**
 * True only when this exact file is safe for the router to stream verbatim.
 */
$styliiiish_is_servable_static = static function (string $file) use ($styliiiish_servable_mime_types): bool {
    $basename = basename($file);

    // Dotfiles (.env, .htaccess, .git*) have no meaningful extension and must never be streamed.
    if ($basename === '' || $basename[0] === '.') {
        return false;
    }

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    return $ext !== '' && isset($styliiiish_servable_mime_types[$ext]);
};

// Fix localized static asset URLs like /ar/wp-content/... -> /wp-content/...
if (preg_match('#^/(ar|en|ara)/wp-content/(.+)$#u', $request_uri, $matches)) {
    $normalized_asset_path = '/wp-content/' . $matches[2];
    $normalized_asset_file = realpath(__DIR__ . $normalized_asset_path);

    if (
        $normalized_asset_file !== false
        && strpos($normalized_asset_file, realpath(__DIR__ . '/wp-content')) === 0
        && is_file($normalized_asset_file)
        && $styliiiish_is_servable_static($normalized_asset_file)
    ) {
        $ext = strtolower(pathinfo($normalized_asset_file, PATHINFO_EXTENSION));

        header('Content-Type: ' . $styliiiish_servable_mime_types[$ext]);
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: public, max-age=604800');
        readfile($normalized_asset_file);
        exit;
    }

    // Not a streamable static asset (missing, outside wp-content, or executable/sensitive).
    // Hand it back to the normal URL so Apache/PHP handles it the way it would without the prefix.
    header('Location: ' . $normalized_asset_path, true, 302);
    exit;
}

// ===== React storefront cutover =====
//
// The React SPA (react-frontend/, built into react-dist/) replaces the old
// Laravel-Blade storefront (home/shop/item/categories/blog/etc.) as of this
// cutover. Laravel itself is NOT removed — it still serves /api/* (the
// checkout, products, امنحي, chat endpoints the React app calls) and a
// couple of machine-consumed feeds that have no React equivalent. Everything
// else that isn't an explicit WordPress carve-out now falls through to the
// React app's index.html and lets its client-side router take over.
//
// Known, accepted gaps from this cutover (no React page exists yet):
// /blog, /blog/{slug}, /ads, /google-reviews, /brand, and old /item/{slug}
// product links (the new product route is /product/{id}, a numeric id, and
// there is no slug->id table to redirect through). Revisit if/when React
// grows equivalents for these.
$react_dist_dir = __DIR__ . '/react-dist';

// Always-Laravel: JSON API the React app calls, plus feeds no React page can
// generate (Google Merchant Center polls these directly by URL).
if (
    strpos($request_uri, '/api/') === 0 ||
    $path === '/api' ||
    in_array($path, ['/merchant-feed.xml', '/merchant-feed-en.xml'], true)
) {
    require __DIR__ . '/laravel_home/public/index.php';
    exit;
}

// Legacy URL -> new React route. 301s (not redirects inside the SPA) so
// search engines and old bookmarks transfer to the new address instead of
// silently 404ing.
$legacy_redirect_map = [
    '/about-us' => '/about',
    '/ar/about-us' => '/about',
    '/en/about-us' => '/about',
    '/contact-us' => '/contact',
    '/ar/contact-us' => '/contact',
    '/en/contact-us' => '/contact',
    '/refund-return-policy' => '/refund-policy',
    '/ar/refund-return-policy' => '/refund-policy',
    '/en/refund-return-policy' => '/refund-policy',
    '/Refund-Return-Policy' => '/refund-policy',
    '/shipping-delivery-policy' => '/shipping-policy',
    '/ar/shipping-delivery-policy' => '/shipping-policy',
    '/en/shipping-delivery-policy' => '/shipping-policy',
    '/styliiiish-faq' => '/faq',
    '/ar/faq' => '/faq',
    '/en/faq' => '/faq',
    '/🍪-cookie-policy' => '/cookie-policy',
    '/ar/cookie-policy' => '/cookie-policy',
    '/en/cookie-policy' => '/cookie-policy',
    '/categories' => '/shop',
    '/ar/categories' => '/shop',
    '/en/categories' => '/shop',
    '/ar/shop' => '/shop',
    '/en/shop' => '/shop',
    '/ar/marketplace' => '/marketplace',
    '/en/marketplace' => '/marketplace',
    '/ar/privacy-policy' => '/privacy-policy',
    '/en/privacy-policy' => '/privacy-policy',
    '/ar/سياسة-الخصوصية' => '/privacy-policy',
    '/ar/terms-conditions' => '/terms-conditions',
    '/en/terms-conditions' => '/terms-conditions',
    '/ar/marketplace-policy' => '/marketplace-policy',
    '/en/marketplace-policy' => '/marketplace-policy',
    '/Marketplace-Policy' => '/marketplace-policy',
    '/ar' => '/',
    '/en' => '/',
];

if (isset($legacy_redirect_map[$path])) {
    $target = $legacy_redirect_map[$path];
    if (!empty($_SERVER['QUERY_STRING'])) {
        $target .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('Location: ' . $target, true, 301);
    exit;
}

// Routes that must stay on WordPress (e.g. translated plugin endpoints)
$wordpress_exact_routes = [
    '/dress-rental-in-cairo',
    '/dress-rental-in-cairo/',
    '/ar/dress-rental-in-cairo',
    '/ar/dress-rental-in-cairo/',
    '/ar/تأجير-فساتين-في-القاهرة',
    '/ar/تأجير-فساتين-في-القاهرة/',
    '/en/dress-rental-in-cairo',
    '/en/dress-rental-in-cairo/',
    '/ar/الدفع',
    '/ar/الدفع/',
    '/ara/الدفع',
    '/ara/الدفع/',
    '/حسابي',
    '/حسابي/',
    '/ar/حسابي',
    '/ar/حسابي/',
    '/ara/حسابي',
    '/ara/حسابي/',
    '/my-account',
    '/my-account/',
    '/en/my-account',
    '/en/my-account/',
    '/فساتيني',
    '/فساتيني/',
    '/ar/فساتيني',
    '/ar/فساتيني/',
    '/ara/فساتيني',
    '/ara/فساتيني/',
    '/owner-dashboard',
    '/owner-dashboard/',
    '/ar/لوحة-معلومات-المالك',
    '/ar/لوحة-معلومات-المالك/',
    '/ara/لوحة-معلومات-المالك',
    '/ara/لوحة-معلومات-المالك/',
];

$wordpress_prefix_routes = [
    '/dress-rental-in-cairo/',
    '/ar/dress-rental-in-cairo/',
    '/ar/تأجير-فساتين-في-القاهرة/',
    '/en/dress-rental-in-cairo/',
    '/wp-json/',
    '/ar/wp-json/',
    '/en/wp-json/',
    '/ara/wp-json/',
    '/wc-auth/',
    '/wp-admin/',
    '/wp-login.php',
    '/xmlrpc.php',
    // These duplicate what .htaccess's own RewriteCond exclusions already
    // handle in real production (Apache never even invokes this script for
    // /wp-admin/, /wp-json/, /wp-login.php, etc. — see the "HYBRID ROUTER"
    // block in .htaccess). They're listed here anyway as a safety net: PHP's
    // built-in dev server (`php -S ... hybrid-router.php`, used for local
    // testing) has no equivalent to those Apache conditions and would send
    // bare /wp-json/ etc. to the React SPA shell without this.
    '/ar/حسابي/',
    '/ara/حسابي/',
    '/حسابي/',
    '/my-account/',
    '/en/my-account/',
    '/ar/فساتيني/',
    '/ara/فساتيني/',
    '/فساتيني/',
    '/owner-dashboard/',
    '/ar/لوحة-معلومات-المالك/',
    '/ara/لوحة-معلومات-المالك/',
];

$send_to_wordpress = false;

if (isset($_GET['wc-ajax']) && (string) $_GET['wc-ajax'] !== '') {
    $send_to_wordpress = true;
}

if (in_array($request_uri, $wordpress_exact_routes, true) || in_array($path, $wordpress_exact_routes, true)) {
    $send_to_wordpress = true;
}

if (!$send_to_wordpress) {
    foreach ($wordpress_prefix_routes as $wp_prefix) {
        if (strpos($request_uri, $wp_prefix) === 0) {
            $send_to_wordpress = true;
            break;
        }
    }
}

if ($send_to_wordpress) {
    // ===== WordPress normal loading =====
    define('WP_USE_THEMES', true);
    require __DIR__ . '/wp-blog-header.php';
    exit;
}

// ===== Everything else -> the React SPA =====
// Try a real static file inside react-dist/ first (the Vite build's JS/CSS/
// images); otherwise serve the SPA shell and let React Router decide what
// the path means client-side (this is what makes deep links like /shop or
// /product/123 work on a full page load, not just client-side navigation).
$react_requested_file = realpath($react_dist_dir . $request_uri);

if (
    $react_requested_file !== false &&
    strpos($react_requested_file, realpath($react_dist_dir)) === 0 &&
    is_file($react_requested_file) &&
    // SECURITY: same allowlist as the Laravel/WP asset serving above — never
    // stream a file whose extension isn't recognized as safe to return as-is.
    $styliiiish_is_servable_static($react_requested_file)
) {
    $ext = strtolower(pathinfo($react_requested_file, PATHINFO_EXTENSION));
    header('Content-Type: ' . $styliiiish_servable_mime_types[$ext]);
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: public, max-age=604800');
    readfile($react_requested_file);
    exit;
}

$react_index = $react_dist_dir . '/index.html';
if (is_file($react_index)) {
    header('Content-Type: text/html; charset=UTF-8');
    readfile($react_index);
    exit;
}

// react-dist/ hasn't been built/deployed yet on this environment — fail safe
// to the old Laravel storefront rather than showing a blank/broken page.
require __DIR__ . '/laravel_home/public/index.php';
