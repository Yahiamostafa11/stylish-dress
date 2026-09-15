<?php
/**
 * Plugin Name: Styliiiish Unified Sitemap
 * Description: Serves a unified sitemap index and Laravel-facing sitemap without requiring SEO plugins.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('styliiiish_sitemap_escape')) {
    function styliiiish_sitemap_escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}

if (!function_exists('styliiiish_get_sitemap_request_path')) {
    function styliiiish_get_sitemap_request_path(): string
    {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = is_string($path) ? $path : '/';
        return ltrim(rawurldecode($path), '/');
    }
}

if (!function_exists('styliiiish_normalize_sitemap_url')) {
    function styliiiish_normalize_sitemap_url(string $url, string $base): string
    {
        $trimmed = trim($url);
        if ($trimmed === '') {
            return '';
        }

        $baseParts = wp_parse_url($base);
        $baseScheme = isset($baseParts['scheme']) ? (string) $baseParts['scheme'] : 'https';
        $baseHost = isset($baseParts['host']) ? strtolower((string) $baseParts['host']) : '';

        $parts = wp_parse_url($trimmed);
        if (!is_array($parts)) {
            return '';
        }

        $scheme = isset($parts['scheme']) ? strtolower((string) $parts['scheme']) : '';
        $host = isset($parts['host']) ? strtolower((string) $parts['host']) : '';
        $path = isset($parts['path']) ? (string) $parts['path'] : '';

        if ($scheme !== '' && $scheme !== 'http' && $scheme !== 'https') {
            return '';
        }

        if ($host === '' && str_starts_with($trimmed, '/')) {
            $host = $baseHost;
            $scheme = $baseScheme;
        }

        if ($host === '' || $baseHost === '' || $host !== $baseHost) {
            return '';
        }

        $normalizedPath = $path !== '' ? preg_replace('#/+#', '/', $path) : '/';
        if (!is_string($normalizedPath) || $normalizedPath === '') {
            $normalizedPath = '/';
        }

        // Normalize pre-encoded and double-encoded path segments into a canonical URL form.
        $decodedPath = $normalizedPath;
        for ($i = 0; $i < 8; $i++) {
            $nextDecoded = rawurldecode($decodedPath);
            if ($nextDecoded === $decodedPath) {
                break;
            }

            $decodedPath = $nextDecoded;
        }

        $segments = explode('/', $decodedPath);
        $encodedSegments = [];

        foreach ($segments as $segment) {
            $encodedSegments[] = rawurlencode($segment);
        }

        $normalizedPath = implode('/', $encodedSegments);

        if (!str_starts_with($normalizedPath, '/')) {
            $normalizedPath = '/' . $normalizedPath;
        }

        if ($normalizedPath !== '/') {
            $normalizedPath = rtrim($normalizedPath, '/');
        }

        return $scheme . '://' . $host . $normalizedPath;
    }
}

if (!function_exists('styliiiish_should_exclude_sitemap_url')) {
    function styliiiish_should_exclude_sitemap_url(string $url): bool
    {
        $parts = wp_parse_url($url);
        if (!is_array($parts)) {
            return true;
        }

        $path = isset($parts['path']) ? rawurldecode((string) $parts['path']) : '/';
        $path = strtolower($path);

        if ($path === '') {
            $path = '/';
        }

        if (
            preg_match('#^/(?:cart|wishlist|checkout|my-account|my-dresses|owner-dashboard)(?:/|$)#', $path) ||
            preg_match('#^/(?:ar|en|ara)/(?:cart|wishlist|checkout|my-account|my-dresses|owner-dashboard)(?:/|$)#', $path) ||
            preg_match('#^/(?:حسابي|فساتيني)(?:/|$)#u', $path) ||
            preg_match('#^/(?:ar|ara)/(?:حسابي|فساتيني|لوحة-معلومات-المالك)(?:/|$)#u', $path)
        ) {
            return true;
        }

        if (str_contains($path, '/feed') || str_contains($path, '/tag/')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('styliiiish_prepare_sitemap_slug')) {
    function styliiiish_prepare_sitemap_slug(string $slug): string
    {
        $normalized = trim($slug);
        if ($normalized === '') {
            return '';
        }

        // Some slugs are already percent-encoded by upstream imports.
        if (preg_match('/%[0-9a-f]{2}/i', $normalized) === 1) {
            for ($i = 0; $i < 8; $i++) {
                $decoded = rawurldecode($normalized);
                if ($decoded === $normalized) {
                    break;
                }

                $normalized = $decoded;
            }
        }

        $normalized = trim($normalized, "/ \t\n\r\0\x0B");
        if ($normalized === '') {
            return '';
        }

        return rawurlencode($normalized);
    }
}

if (!function_exists('styliiiish_output_sitemap_index')) {
    function styliiiish_output_sitemap_index(): void
    {
        $lastmod = gmdate('c');
        $items = [
            home_url('/wp-sitemap.xml'),
            home_url('/laravel-sitemap.xml'),
        ];

        status_header(200);
        nocache_headers();
        header('Content-Type: application/xml; charset=UTF-8');

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($items as $loc) {
            echo '<sitemap>';
            echo '<loc>' . styliiiish_sitemap_escape((string) $loc) . '</loc>';
            echo '<lastmod>' . styliiiish_sitemap_escape($lastmod) . '</lastmod>';
            echo '</sitemap>';
        }

        echo '</sitemapindex>';
        exit;
    }
}

if (!function_exists('styliiiish_output_laravel_sitemap')) {
    function styliiiish_output_laravel_sitemap(): void
    {
        global $wpdb;

        $base = rtrim(home_url('/'), '/');
        $now = gmdate('c');
        $urls = [];

        $addUrl = static function (string $url, string $lastmod = '') use (&$urls, $now, $base): void {
            $normalized = styliiiish_normalize_sitemap_url($url, $base);

            if ($normalized === '' || styliiiish_should_exclude_sitemap_url($normalized)) {
                return;
            }

            $dedupeKey = strtolower($normalized);
            if (isset($urls[$dedupeKey])) {
                return;
            }

            $urls[$dedupeKey] = [
                'loc' => $normalized,
                'lastmod' => $lastmod !== '' ? $lastmod : $now,
            ];
        };

        $staticPaths = [
            '/',
            '/ar',
            '/en',
            '/shop',
            '/ar/shop',
            '/en/shop',
            '/ads',
            '/ar/ads',
            '/en/ads',
            '/blog',
            '/ar/blog',
            '/en/blog',
            '/contact-us',
            '/ar/contact-us',
            '/en/contact-us',
            '/about-us',
            '/ar/about-us',
            '/en/about-us',
            '/privacy-policy',
            '/ar/privacy-policy',
            '/en/privacy-policy',
            '/terms-conditions',
            '/ar/terms-conditions',
            '/en/terms-conditions',
            '/marketplace-policy',
            '/ar/marketplace-policy',
            '/en/marketplace-policy',
            '/refund-return-policy',
            '/ar/refund-return-policy',
            '/en/refund-return-policy',
            '/faq',
            '/ar/faq',
            '/en/faq',
            '/shipping-delivery-policy',
            '/ar/shipping-delivery-policy',
            '/en/shipping-delivery-policy',
            '/cookie-policy',
            '/ar/cookie-policy',
            '/en/cookie-policy',
            '/categories',
            '/ar/categories',
            '/en/categories',
            '/marketplace',
            '/ar/marketplace',
            '/en/marketplace',
        ];

        foreach ($staticPaths as $path) {
            $addUrl($base . $path);
        }

        $productRows = $wpdb->get_results(
            "SELECT ID, post_name, post_modified_gmt FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish'"
        );

        if (is_array($productRows)) {
            foreach ($productRows as $row) {
                $slug = isset($row->post_name) ? trim((string) $row->post_name) : '';
                if ($slug === '') {
                    continue;
                }

                $encodedSlug = styliiiish_prepare_sitemap_slug($slug);
                if ($encodedSlug === '') {
                    continue;
                }

                $lastmod = !empty($row->post_modified_gmt) ? gmdate('c', strtotime((string) $row->post_modified_gmt . ' UTC')) : $now;

                $addUrl($base . '/product/' . $encodedSlug . '/', $lastmod);
                $addUrl($base . '/item/' . $encodedSlug, $lastmod);
                $addUrl($base . '/ar/item/' . $encodedSlug, $lastmod);
                $addUrl($base . '/en/item/' . $encodedSlug, $lastmod);
            }
        }

        $postRows = $wpdb->get_results(
            "SELECT ID, post_name, post_modified_gmt FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish'"
        );

        if (is_array($postRows)) {
            foreach ($postRows as $row) {
                $slug = isset($row->post_name) ? trim((string) $row->post_name) : '';
                if ($slug === '') {
                    continue;
                }

                $encodedSlug = styliiiish_prepare_sitemap_slug($slug);
                if ($encodedSlug === '') {
                    continue;
                }

                $lastmod = !empty($row->post_modified_gmt) ? gmdate('c', strtotime((string) $row->post_modified_gmt . ' UTC')) : $now;

                $addUrl($base . '/blog/' . $encodedSlug, $lastmod);
                $addUrl($base . '/ar/blog/' . $encodedSlug, $lastmod);
                $addUrl($base . '/en/blog/' . $encodedSlug, $lastmod);
            }
        }

        $pageRows = $wpdb->get_results(
            "SELECT ID, post_modified_gmt FROM {$wpdb->posts} WHERE post_type = 'page' AND post_status = 'publish'"
        );

        if (is_array($pageRows)) {
            foreach ($pageRows as $row) {
                $pageId = isset($row->ID) ? (int) $row->ID : 0;
                if ($pageId <= 0) {
                    continue;
                }

                $permalink = get_permalink($pageId);
                if (!is_string($permalink) || $permalink === '') {
                    continue;
                }

                $lastmod = !empty($row->post_modified_gmt) ? gmdate('c', strtotime((string) $row->post_modified_gmt . ' UTC')) : $now;
                $addUrl($permalink, $lastmod);
            }
        }

        status_header(200);
        nocache_headers();
        header('Content-Type: application/xml; charset=UTF-8');

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $item) {
            echo '<url>';
            echo '<loc>' . styliiiish_sitemap_escape((string) $item['loc']) . '</loc>';
            echo '<lastmod>' . styliiiish_sitemap_escape((string) $item['lastmod']) . '</lastmod>';
            echo '</url>';
        }

        echo '</urlset>';
        exit;
    }
}

add_action('template_redirect', function (): void {
    if (is_admin()) {
        return;
    }

    $path = strtolower(styliiiish_get_sitemap_request_path());

    if ($path === 'sitemap_index.xml' || $path === 'en/sitemap_index.xml') {
        styliiiish_output_sitemap_index();
    }

    if ($path === 'laravel-sitemap.xml') {
        styliiiish_output_laravel_sitemap();
    }
}, 0);
