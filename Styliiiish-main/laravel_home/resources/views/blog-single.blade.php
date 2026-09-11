<!DOCTYPE html>
@php
    $currentLocale = $currentLocale ?? 'ar';
    $localePrefix = $localePrefix ?? '/ar';
    $isEnglish = $currentLocale === 'en';
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', request()->getSchemeAndHttpHost())), '/');
    $wpLogo = $wpBaseUrl . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpIcon = $wpBaseUrl . '/wp-content/uploads/2025/11/cropped-ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';

    $translations = [
        'ar' => [
            'page_title_suffix' => 'المدونة | ستايلش',
            'meta_desc_fallback' => 'اقرئي أحدث مقالات ستايلش عن الموضة والفساتين ونصائح اختيار الإطلالة المناسبة.',
            'published_on' => 'نُشر بتاريخ',
            'back_to_blog' => 'العودة إلى المدونة',
            'related_articles' => 'مقالات ذات صلة',
            'read_more' => 'قراءة المزيد',
            'no_related' => 'لا توجد مقالات إضافية حالياً.',
        ],
        'en' => [
            'page_title_suffix' => 'Blog | Styliiiish',
            'meta_desc_fallback' => 'Read the latest Styliiiish blog posts about dresses, fashion trends, and practical styling tips.',
            'published_on' => 'Published on',
            'back_to_blog' => 'Back to Blog',
            'related_articles' => 'Related Articles',
            'read_more' => 'Read More',
            'no_related' => 'No additional articles available right now.',
        ],
    ];

    $normalizeBrandText = fn (string $value) => $currentLocale === 'en'
        ? (preg_replace('/ستايلش/iu', 'Styliiiish', $value) ?? $value)
        : (preg_replace('/styliiiish/iu', 'ستايلش', $value) ?? $value);
    $t = fn (string $key) => $normalizeBrandText((string) ($translations[$currentLocale][$key] ?? $translations['ar'][$key] ?? $key));

    $title = trim((string) ($post->post_title ?? ''));
    $excerptSource = trim((string) ($post->post_excerpt ?: strip_tags((string) ($post->post_content ?? ''))));
    $metaDesc = $excerptSource !== ''
        ? (mb_strlen($excerptSource) > 170 ? mb_substr($excerptSource, 0, 170) . '…' : $excerptSource)
        : $t('meta_desc_fallback');

    $canonicalPath = $localePrefix . '/blog/' . rawurlencode(rawurldecode((string) ($post->post_name ?? '')));
    $fallbackImage = $wpBaseUrl . '/wp-content/uploads/woocommerce-placeholder.webp';

    $normalizeBlogImageUrl = function (?string $value) use ($wpBaseUrl, $fallbackImage): string {
        $candidate = trim(html_entity_decode((string) ($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($candidate === '') {
            return $fallbackImage;
        }

        if (str_starts_with($candidate, '//')) {
            $candidate = 'https:' . $candidate;
        } elseif (!preg_match('#^https?://#i', $candidate) && !str_starts_with($candidate, 'data:image/')) {
            $candidate = str_starts_with($candidate, '/')
                ? ($wpBaseUrl . $candidate)
                : ($wpBaseUrl . '/' . ltrim($candidate, '/'));
        }

        if (str_starts_with($candidate, 'data:image/')) {
            return $candidate;
        }

        $path = (string) parse_url($candidate, PHP_URL_PATH);
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        $isKnownImageExt = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg', 'bmp', 'ico', 'heic', 'heif'], true);
        $looksLikeMediaPath = str_contains($path, '/wp-content/uploads/') || str_contains($path, '/google-reviews/');

        if (!$isKnownImageExt && !$looksLikeMediaPath) {
            return $fallbackImage;
        }

        return $candidate;
    };

    $articleImage = $normalizeBlogImageUrl((string) ($post->image ?? ''));

    $contentHtml = (string) ($post->post_content ?? '');
    if (trim($contentHtml) === '') {
        $contentHtml = '<p>' . e($metaDesc) . '</p>';
    }
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDesc }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title !== '' ? $title : $t('page_title_suffix') }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url" content="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <meta property="og:image" content="{{ $articleImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title !== '' ? $title : $t('page_title_suffix') }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">
    <meta name="twitter:image" content="{{ $articleImage }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $wpIcon }}">
    <link rel="apple-touch-icon" href="{{ $wpIcon }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/blog/{{ rawurlencode(rawurldecode((string) ($post->post_name ?? ''))) }}">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/blog/{{ rawurlencode(rawurldecode((string) ($post->post_name ?? ''))) }}">
    <title>{{ $title !== '' ? $title . ' | Styliiiish' : $t('page_title_suffix') }}</title>
    @include('partials.shared-seo-meta')
    <style>
        :root { --wf-main-rgb: 213, 21, 34; --wf-main-color: rgb(var(--wf-main-rgb)); --wf-secondary-color: #17273B; --bg:#f6f7fb; --card:#fff; --line:rgba(189,189,189,.35); --text:#17273B; --muted:#5a6678; --primary: var(--wf-main-color); --secondary: var(--wf-secondary-color); }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background:
                radial-gradient(1200px 380px at 50% -120px, rgba(var(--wf-main-rgb), .08), transparent 60%),
                linear-gradient(180deg, #f9fbff 0%, var(--bg) 30%, #f3f6fb 100%);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1080px, 92%); margin: 0 auto; }
        .article-wrap { padding: 30px 0 20px; }
        .article-card {
            background: linear-gradient(180deg, rgba(255,255,255,.96) 0%, #fff 100%);
            border: 1px solid rgba(23, 39, 59, .10);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 14px 38px rgba(23,39,59,.10), 0 2px 8px rgba(23,39,59,.05);
            backdrop-filter: blur(5px);
            transition: transform .35s ease, box-shadow .35s ease;
            animation: fadeUp .55s ease both;
        }
        .article-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 42px rgba(23,39,59,.13), 0 4px 12px rgba(23,39,59,.07);
        }
        .article-cover {
            width: 100%;
            max-height: 440px;
            object-fit: cover;
            display: block;
            background: #edf1f7;
            transform-origin: center;
            transition: transform .65s ease, filter .45s ease;
        }
        .article-card:hover .article-cover {
            transform: scale(1.018);
            filter: saturate(1.03) contrast(1.02);
        }
        .article-body { padding: 24px; }
        .article-title {
            margin: 0 0 12px;
            font-size: clamp(24px, 3.2vw, 40px);
            line-height: 1.22;
            color: var(--wf-secondary-color);
            letter-spacing: -.2px;
        }
        .article-meta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 13px;
            color: var(--muted);
            border: 1px solid rgba(23,39,59,.16);
            border-radius: 999px;
            background: #f8fbff;
            padding: 7px 13px;
        }
        .article-content { color: #243247; font-size: 16px; line-height: 1.92; }
        .article-content h2, .article-content h3, .article-content h4 { color: var(--wf-secondary-color); margin-top: 22px; margin-bottom: 10px; }
        .article-content p { margin: 0 0 14px; }
        .article-content ul, .article-content ol { margin: 0 0 14px; padding-inline-start: 22px; }
        .article-content img {
            max-width: 100%;
            border-radius: 14px;
            height: auto;
            box-shadow: 0 10px 24px rgba(23,39,59,.12);
            transition: transform .35s ease;
        }
        .article-content img:hover { transform: translateY(-2px); }
        .article-actions { margin-top: 18px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 15px;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid var(--line);
            transition: transform .2s ease, box-shadow .25s ease, background .2s ease, color .2s ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: var(--wf-main-color); color: #fff; border-color: transparent; }
        .btn-primary:hover { box-shadow: 0 10px 20px rgba(var(--wf-main-rgb), .26); }
        .btn-light { background: #fff; color: var(--wf-secondary-color); }
        .btn-light:hover { border-color: rgba(var(--wf-main-rgb), .35); color: var(--wf-main-color); }
        .related {
            margin-top: 20px;
            background: linear-gradient(180deg, #fff 0%, #fcfdff 100%);
            border: 1px solid rgba(23,39,59,.12);
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(23,39,59,.08);
            animation: fadeUp .6s ease .08s both;
        }
        .related h3 { margin: 0 0 12px; font-size: 20px; }
        .related-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 10px; }
        .related-card {
            border: 1px solid rgba(23,39,59,.12);
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            transition: transform .3s ease, box-shadow .35s ease;
            animation: floatIn .45s ease both;
        }
        .related-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(23,39,59,.12);
        }
        .related-card img {
            width: 100%;
            aspect-ratio: 16/10;
            object-fit: cover;
            background: #edf1f7;
            transition: transform .45s ease;
        }
        .related-card:hover img { transform: scale(1.03); }
        .related-card .inner { padding: 12px; display: grid; gap: 8px; }
        .related-card h4 { margin: 0; font-size: 14px; line-height: 1.45; color: var(--wf-secondary-color); }
        .related-card .meta { font-size: 12px; color: var(--muted); }
        .related-grid .related-card:nth-child(2) { animation-delay: .08s; }
        .related-grid .related-card:nth-child(3) { animation-delay: .16s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes floatIn {
            from { opacity: 0; transform: translateY(8px) scale(.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }

        @media (max-width: 900px) { .related-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 620px) {
            .article-body { padding: 15px; }
            .article-wrap { padding-top: 20px; }
            .article-card { border-radius: 16px; }
            .related-grid { grid-template-columns: 1fr; }
        }
    </style>
    @include('partials.shared-home-header-styles')
</head>
<body>
@include('partials.shared-home-header')

<main class="container article-wrap">
    <article class="article-card">
        <img class="article-cover" src="{{ $articleImage }}" alt="{{ $title }}" loading="eager" onerror="this.onerror=null;this.src='{{ $wpBaseUrl }}/wp-content/uploads/woocommerce-placeholder.webp';">
        <div class="article-body">
            <h1 class="article-title">{{ $title }}</h1>
            <span class="article-meta">{{ $t('published_on') }} {{ \Carbon\Carbon::parse((string) ($post->post_date ?? now()))->format('Y-m-d') }}</span>

            <div class="article-content">
                {!! $contentHtml !!}
            </div>

            <div class="article-actions">
                <a class="btn btn-primary" href="{{ $localePrefix }}/blog">{{ $t('back_to_blog') }}</a>
            </div>
        </div>
    </article>

    <section class="related">
        <h3>{{ $t('related_articles') }}</h3>
        @if(($relatedPosts ?? collect())->isNotEmpty())
            <div class="related-grid">
                @foreach($relatedPosts as $related)
                    @php
                        $relatedSlug = rawurlencode(rawurldecode((string) ($related->post_name ?? '')));
                        $relatedUrl = $localePrefix . '/blog/' . $relatedSlug;
                        $relatedImage = $normalizeBlogImageUrl((string) ($related->image ?? ''));
                    @endphp
                    <article class="related-card">
                        <a href="{{ $relatedUrl }}">
                            <img src="{{ $relatedImage }}" alt="{{ $related->post_title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $wpBaseUrl }}/wp-content/uploads/woocommerce-placeholder.webp';">
                        </a>
                        <div class="inner">
                            <h4><a href="{{ $relatedUrl }}">{{ $related->post_title }}</a></h4>
                            <span class="meta">{{ \Carbon\Carbon::parse((string) ($related->post_date ?? now()))->format('Y-m-d') }}</span>
                            <a class="btn btn-light" href="{{ $relatedUrl }}">{{ $t('read_more') }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p>{{ $t('no_related') }}</p>
        @endif
    </section>
</main>

@include('partials.shared-home-footer')
</body>
</html>
