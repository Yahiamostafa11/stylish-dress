<?php
/**
 * Tells the React storefront whether the visitor is signed in to WordPress as the owner / a manager,
 * so its header can show a "My dashboard" button. Answers {"manager":true|false}.
 *
 * The storefront and WordPress share the same domain, so the WordPress login cookie reaches this file.
 * Visitors without that cookie (almost everyone) get an answer without WordPress being loaded at all.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$answer = static function (bool $manager): void {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    echo json_encode(['manager' => $manager]);
    exit;
};

$hasLoginCookie = false;
foreach (array_keys($_COOKIE) as $name) {
    if (strpos((string) $name, 'wordpress_logged_in_') === 0) {
        $hasLoginCookie = true;
        break;
    }
}
if (!$hasLoginCookie) {
    $answer(false);
}

// WordPress's bootstrap can print a stray byte (BOM) on this install; buffer it away.
ob_start();
define('WP_USE_THEMES', false);
require __DIR__ . '/wp-load.php';

if (!function_exists('is_user_logged_in') || !is_user_logged_in()) {
    $answer(false);
}

$answer(function_exists('styliiiish_is_dashboard_user') && styliiiish_is_dashboard_user(get_current_user_id()));
