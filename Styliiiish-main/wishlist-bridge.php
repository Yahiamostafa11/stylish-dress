<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.',
    ]);
    exit;
}

$wpLoadPath = __DIR__ . '/wp-load.php';
if (!is_file($wpLoadPath)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'WordPress bootstrap not found.',
    ]);
    exit;
}

require_once $wpLoadPath;

if (!function_exists('fable_extra_woowishlist_get_list') || !function_exists('fable_extra_woowishlist_add') || !function_exists('fable_extra_woowishlist_remove')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Wishlist plugin is not available.',
    ]);
    exit;
}


$normalizeList = static function ($list): array {
    if (!is_array($list)) {
        return [];
    }

    return array_values(array_unique(array_map('intval', array_filter($list, static fn ($value) => (int) $value > 0))));
};

$translateNamesByTranslatePress = static function (array $names, string $locale): array {
    $locale = strtolower(trim($locale));
    if ($locale !== 'ar') {
        return [];
    }

    $settings = get_option('trp_settings');
    if (!is_array($settings)) {
        return [];
    }

    $defaultLanguage = trim((string) ($settings['default-language'] ?? ''));
    $translationLanguages = is_array($settings['translation-languages'] ?? null) ? $settings['translation-languages'] : [];

    if ($defaultLanguage === '' || empty($translationLanguages)) {
        return [];
    }

    $targetLanguage = '';
    foreach ($translationLanguages as $languageCode) {
        $code = trim((string) $languageCode);
        if ($code === '' || strcasecmp($code, $defaultLanguage) === 0) {
            continue;
        }
        if (stripos($code, 'ar') === 0) {
            $targetLanguage = $code;
            break;
        }
    }

    if ($targetLanguage === '') {
        return [];
    }

    global $wpdb;
    if (!isset($wpdb) || !is_object($wpdb)) {
        return [];
    }

    $dictionaryTable = $wpdb->prefix . 'trp_dictionary_' . $defaultLanguage . '_' . $targetLanguage;
    $tableExists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $dictionaryTable));
    if ((string) $tableExists !== $dictionaryTable) {
        return [];
    }

    $originals = [];
    foreach ($names as $name) {
        $value = trim((string) $name);
        if ($value === '') {
            continue;
        }

        $originals[] = $value;

        $decoded = trim(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($decoded !== '' && $decoded !== $value) {
            $originals[] = $decoded;
        }
    }

    $originals = array_values(array_unique($originals));
    if (empty($originals)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($originals), '%s'));
    $query = "SELECT original, translated FROM {$dictionaryTable} WHERE original IN ({$placeholders})";
    $prepared = $wpdb->prepare($query, ...$originals);
    $rows = $wpdb->get_results($prepared);

    if (!is_array($rows) || empty($rows)) {
        return [];
    }

    $map = [];
    foreach ($rows as $row) {
        $original = trim((string) ($row->original ?? ''));
        $translated = trim((string) ($row->translated ?? ''));
        if ($original === '' || $translated === '') {
            continue;
        }

        if (!array_key_exists($original, $map)) {
            $map[$original] = $translated;
        }

        $decodedOriginal = trim(html_entity_decode($original, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($decodedOriginal !== '' && !array_key_exists($decodedOriginal, $map)) {
            $map[$decodedOriginal] = $translated;
        }
    }

    return $map;
};

$action = isset($_POST['action']) ? (string) $_POST['action'] : '';

if ($action === 'add' || $action === 'remove') {
    $pid = isset($_POST['pid']) ? (int) $_POST['pid'] : 0;

    if ($pid <= 0) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid product id.',
        ]);
        exit;
    }

    if ($action === 'add') {
        fable_extra_woowishlist_add($pid);
    } else {
        fable_extra_woowishlist_remove($pid);
    }
} elseif (!in_array($action, ['count', 'list'], true)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Unknown action.',
    ]);
    exit;
}

$list = $normalizeList(fable_extra_woowishlist_get_list());

$count = count($list);

if ($action === 'list') {
    $requestedLocale = isset($_POST['locale']) ? (string) $_POST['locale'] : '';
    $limit = isset($_POST['limit']) ? (int) $_POST['limit'] : 8;
    $limit = max(1, min(20, $limit));

    $items = [];
    $ids = array_slice(array_reverse($list), 0, $limit);
    $rawNames = [];

    foreach ($ids as $productId) {
        $id = (int) $productId;
        if ($id <= 0) {
            continue;
        }

        $post = get_post($id);
        if (!$post || $post->post_type !== 'product') {
            continue;
        }

        $name = trim((string) get_the_title($id));
        $url = (string) get_permalink($id);
        $slug = trim((string) ($post->post_name ?? ''));
        $image = (string) get_the_post_thumbnail_url($id, 'woocommerce_thumbnail');

        if ($image === '') {
            $image = (string) wc_placeholder_img_src('woocommerce_thumbnail');
        }

        if ($image === '' || strpos($image, '/wp-content/plugins/woocommerce/assets/images/placeholder') !== false) {
            $image = rtrim((string) home_url(), '/') . '/wp-content/uploads/woocommerce-placeholder.webp';
        }

        if ($name === '' || $url === '') {
            continue;
        }

        $rawNames[] = $name;

        $items[] = [
            'id' => $id,
            'name' => $name,
            'url' => $url,
            'slug' => $slug,
            'image' => $image,
        ];
    }

    if (!empty($items)) {
        $translationMap = $translateNamesByTranslatePress($rawNames, $requestedLocale);

        if (!empty($translationMap)) {
            foreach ($items as $index => $item) {
                $originalName = trim((string) ($item['name'] ?? ''));
                $decodedName = trim(html_entity_decode($originalName, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                $translatedName = $translationMap[$originalName] ?? $translationMap[$decodedName] ?? null;

                if (is_string($translatedName) && trim($translatedName) !== '') {
                    $items[$index]['name'] = trim($translatedName);
                }
            }
        }
    }

    echo json_encode([
        'success' => true,
        'count' => max(0, $count),
        'items' => $items,
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'count' => max(0, $count),
]);
