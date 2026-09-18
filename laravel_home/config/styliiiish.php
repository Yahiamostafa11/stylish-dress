<?php

/**
 * @package   Styliiiish
 * @author    Yahia Mostafa — ZIJ Tech <https://zijtech.com/>
 * @copyright ZIJ Tech
 */

/*
|--------------------------------------------------------------------------
| Styliiiish application settings
|--------------------------------------------------------------------------
|
| WHY THIS FILE EXISTS (audit item H6)
|
| routes/web.php called env() 25 times, WP_PUBLIC_URL alone 22 times. Laravel
| only reads the .env file when the config has NOT been cached. The moment
| anyone runs `php artisan config:cache` — the standard production deploy step,
| and something Laravel's own docs tell you to do — every one of those calls
| returns null, and every WordPress link on the storefront breaks at once.
|
| Reading env() inside a config file is the supported pattern. Everywhere else
| in the application, read these values through config('styliiiish.*').
|
*/

return [

    /*
     | Public base URL of the WordPress/WooCommerce site. The storefront links
     | back to it for product pages, cart, checkout and account.
     |
     | Falls back to the current request host so a misconfigured .env degrades
     | to same-host links rather than to null, which is what used to happen.
     */
    'wp_public_url' => rtrim(
        (string) (env('WP_PUBLIC_URL') ?: ''),
        '/'
    ),

    // React storefront's own public URL — for links back into it from emails.
    'frontend_url' => rtrim((string) (env('FRONTEND_URL') ?: 'http://localhost:5173'), '/'),

    'blog' => [
        // Minutes to cache a single blog post render.
        'single_cache_minutes' => (int) env('BLOG_SINGLE_CACHE_MINUTES', 15),

        // Whether a missing blog post may be fetched from the live WP site.
        // Kept as a string to preserve the original 'false' default semantics.
        'single_remote_scrape' => env('BLOG_SINGLE_REMOTE_SCRAPE', 'false'),

        // Path of the Arabic blog archive on the WordPress side.
        'ar_archive_path' => env('WP_AR_BLOG_ARCHIVE_PATH', '/ar/%d9%85%d8%af%d9%88%d9%86%d8%a9/'),
    ],

    /*
     | Rate limits for public write endpoints (audit item H3), as
     | "attempts,minutes" strings passed to Laravel's throttle middleware.
     |
     | Reviews and reports are deliberately tight: they are rare, human actions
     | and were previously unlimited. Wishlist is generous because it is a normal
     | UI interaction that must stay responsive.
     */
    'throttle' => [
        'review'      => env('THROTTLE_REVIEW', '5,60'),
        'report'      => env('THROTTLE_REPORT', '10,60'),
        'wishlist'    => env('THROTTLE_WISHLIST', '60,1'),
        'amnahi'      => env('THROTTLE_AMNAHI', '30,1'),
        'testimonial' => env('THROTTLE_TESTIMONIAL', '5,60'),
    ],

    /*
     | Reviews page testimonial box (routes/reviews.php).
     |
     | These are general "what customers think of us" testimonials, not
     | WooCommerce product reviews — they're emailed to the owner rather than
     | published automatically. She curates them onto the page as a
     | screenshot herself, same as the pre-existing static ones.
     */
    'reviews' => [
        'notify_email' => env('REVIEWS_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@styliiiish.com')),
    ],

    /*
     | Peer-to-peer "امنحي" marketplace (routes/amnahi.php).
     |
     | New listings are real WooCommerce products written straight into the
     | wp_posts/wp_postmeta tables (post_status + product_cat 'used-dress'),
     | so the existing owner-dashboard vendor moderation screen picks them up
     | now that WordPress is reachable locally.
     |
     | auto_publish defaults to false: every new listing goes to 'pending' and
     | must be approved from wp-admin (Products, or the Flexi-MultiVendor
     | vendor screen) before it appears on the marketplace. Only flip this to
     | true for a throwaway local demo where wp-admin genuinely can't be reached.
     */
    'amnahi' => [
        'auto_publish' => filter_var(env('AMNAHI_AUTO_PUBLISH', false), FILTER_VALIDATE_BOOL),
        'max_images' => 2,
        'max_image_kb' => 5120,
    ],

    /*
     | Checkout → WooCommerce order creation.
     |
     | The React storefront builds its own cart client-side, then hands the
     | cart + shipping details to routes/checkout.php, which forwards them to
     | a small trusted bridge script on the WordPress box (wc-order-bridge.php)
     | that boots WordPress directly and calls wc_create_order() — the real
     | WooCommerce API, not a hand-rolled table insert. That script returns a
     | native WooCommerce "pay for order" URL, and the browser is redirected
     | there to complete payment through whatever gateway is enabled
     | (Paymob is already configured on the WordPress side) — Laravel/React
     | never touch card data or gateway credentials directly.
     |
     | order_bridge_secret is a shared secret (not the WC REST keys) verified
     | by wc-order-bridge.php via a request header, so that endpoint can't be
     | used to create arbitrary free orders by anyone who finds the URL.
     */
    'commerce' => [
        'wc_api_url' => rtrim((string) (env('WC_API_URL') ?: env('WP_PUBLIC_URL') ?: ''), '/'),
        'wc_consumer_key' => (string) env('WC_CONSUMER_KEY', ''),
        'wc_consumer_secret' => (string) env('WC_CONSUMER_SECRET', ''),
        'order_bridge_secret' => (string) env('WC_ORDER_BRIDGE_SECRET', ''),
    ],

];
