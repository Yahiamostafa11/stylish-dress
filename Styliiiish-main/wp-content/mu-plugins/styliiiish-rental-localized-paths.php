<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('styliiiish_rental_localized_locale')) {
    function styliiiish_rental_localized_locale(): ?string
    {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        if ($requestUri === '') {
            return null;
        }

        $path = trim(rawurldecode((string) parse_url($requestUri, PHP_URL_PATH)), '/');
        if ($path === '') {
            return null;
        }

        if (in_array($path, ['ar/dress-rental-in-cairo', 'ar/تأجير-فساتين-في-القاهرة'], true)) {
            return 'ar';
        }

        if (in_array($path, ['en/dress-rental-in-cairo', 'dress-rental-in-cairo'], true)) {
            return 'en';
        }

        return null;
    }
}

if (!function_exists('styliiiish_rental_force_trp_language_cookie')) {
    function styliiiish_rental_force_trp_language_cookie(): void
    {
        $locale = styliiiish_rental_localized_locale();
        if ($locale === null || headers_sent()) {
            return;
        }

        setcookie('trp_language', $locale, [
            'expires' => time() + (30 * DAY_IN_SECONDS),
            'path' => '/',
            'secure' => is_ssl(),
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    }
}
add_action('init', 'styliiiish_rental_force_trp_language_cookie', 1);

add_filter('request', function (array $queryVars): array {
    $locale = styliiiish_rental_localized_locale();
    if ($locale === null) {
        return $queryVars;
    }

    $queryVars['pagename'] = 'dress-rental-in-cairo';
    unset($queryVars['name'], $queryVars['attachment'], $queryVars['page_id'], $queryVars['p']);

    return $queryVars;
}, 1);

add_filter('redirect_canonical', function ($redirectUrl, $requestedUrl) {
    $locale = styliiiish_rental_localized_locale();
    if ($locale !== null) {
        return false;
    }

    return $redirectUrl;
}, 10, 2);
