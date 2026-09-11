@php
    $resolveTranslation = function (string $key): string {
        if (!isset($t) || !is_callable($t)) {
            return '';
        }

        $value = trim((string) $t($key));
        return $value === $key ? '' : $value;
    };

    $detectedLocale = strtolower((string) ($currentLocale ?? ($locale ?? app()->getLocale() ?? 'ar')));
    $isEnglishLocale = $detectedLocale === 'en';

    $seoTitleValue = trim((string) (
        $metaTitle
        ?? $seoTitle
        ?? $resolveTranslation('meta_title')
        ?? $resolveTranslation('page_title')
        ?? $resolveTranslation('title')
        ?? 'Styliiiish'
    ));

    if ($seoTitleValue === '') {
        $seoTitleValue = 'Styliiiish';
    }

    $seoDescriptionValue = trim((string) (
        $metaDescription
        ?? $seoDescription
        ?? $metaDesc
        ?? $resolveTranslation('meta_desc')
        ?? 'Styliiiish - Evening, bridal, and engagement dresses in Egypt with fast delivery and trusted service.'
    ));

    if ($seoDescriptionValue === '') {
        $seoDescriptionValue = 'Styliiiish - Evening, bridal, and engagement dresses in Egypt with fast delivery and trusted service.';
    }

    $seoKeywordsValue = trim((string) (
        $metaKeywords
        ?? $resolveTranslation('meta_keywords')
        ?? 'styliiiish, فساتين سهرة, فساتين زفاف, فساتين خطوبة, evening dresses egypt, bridal dresses egypt, engagement dresses'
    ));

    $seoBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', request()->getSchemeAndHttpHost())), '/');
    $seoCanonicalPath = (string) ($canonicalPath ?? request()->getPathInfo());
    if ($seoCanonicalPath === '' || $seoCanonicalPath[0] !== '/') {
        $seoCanonicalPath = '/' . ltrim($seoCanonicalPath, '/');
    }

    $seoUrlValue = (string) ($metaUrl ?? $seoUrl ?? ($seoBaseUrl . $seoCanonicalPath));
    $seoImageValue = (string) ($metaImage ?? $seoImage ?? $wpIcon ?? $wpLogo ?? ($seoBaseUrl . '/favicon.ico'));

    $seoLocaleCode = $isEnglishLocale ? 'en_US' : 'ar_EG';
    $seoAltLocaleCode = $isEnglishLocale ? 'ar_EG' : 'en_US';
    $seoLanguageCode = $isEnglishLocale ? 'en' : 'ar';
    $seoOrganizationName = $isEnglishLocale ? 'Styliiiish' : 'ستايلش';
    $seoOrganizationDescription = $isEnglishLocale
        ? 'Styliiiish is an online fashion platform for evening, bridal, and engagement dresses with trusted support and Egypt-wide delivery.'
        : 'ستايلش منصة أزياء إلكترونية لفساتين السهرة والزفاف والخطوبة مع دعم موثوق وتوصيل داخل مصر.';
    $seoSameAs = [
        'https://www.facebook.com/Styliiish.Egypt/',
        'https://www.instagram.com/styliiiish.egypt/',
        'https://www.tiktok.com/@styliiish_?_r=1&_t=ZS-94HEUy9a0RE',
        'https://g.page/styliish',
        'https://wa.me/201050874255',
    ];

    $ga4MeasurementId = trim((string) config('services.analytics.ga4_measurement_id', ''));
    $googleAdsTagId = trim((string) config('services.analytics.google_ads_tag_id', ''));
    $gtmContainerId = trim((string) config('services.analytics.gtm_container_id', ''));
    $trackingTagId = $ga4MeasurementId !== '' ? $ga4MeasurementId : $googleAdsTagId;
@endphp
<meta name="keywords" content="{{ $seoKeywordsValue }}">
<meta name="author" content="Styliiiish">
<meta name="publisher" content="Styliiiish">
<meta name="application-name" content="{{ $seoOrganizationName }}">
<meta property="og:site_name" content="{{ $seoOrganizationName }}">
<meta name="apple-mobile-web-app-title" content="{{ $seoOrganizationName }}">
<meta name="theme-color" content="#d51522">
<link rel="preconnect" href="https://styliiiish.com" crossorigin>
<link rel="dns-prefetch" href="//styliiiish.com">
@if($gtmContainerId !== '')
<!-- Google Tag Manager -->
<script>
    (function(w,d,s,l,i){
        w[l]=w[l]||[];
        w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
        var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),
            dl=l!=='dataLayer'?'&l='+l:'';
        j.async=true;
        j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
        f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer',@json($gtmContainerId));
</script>
<!-- End Google Tag Manager -->
@endif
@if($trackingTagId !== '')
<script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($trackingTagId) }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    window.gtag = window.gtag || gtag;

    gtag('js', new Date());

    @if($ga4MeasurementId !== '')
    gtag('config', @json($ga4MeasurementId), {
        send_page_view: true,
    });
    @endif

    @if($googleAdsTagId !== '')
    gtag('config', @json($googleAdsTagId));
    @endif

    window.styliiiishTrackEvent = function(eventName, payload) {
        if (!eventName || typeof window.gtag !== 'function') {
            return;
        }

        const eventPayload = payload && typeof payload === 'object' ? payload : {};
        window.gtag('event', eventName, eventPayload);
    };

    window.styliiiishTrackPurchase = function(payload) {
        if (!payload || typeof payload !== 'object') {
            return;
        }

        const transactionId = String(payload.transaction_id || '').trim();
        if (!transactionId) {
            return;
        }

        const dedupeKey = 'styliiiish_purchase_' + transactionId;
        if (window.sessionStorage && window.sessionStorage.getItem(dedupeKey)) {
            return;
        }

        window.styliiiishTrackEvent('purchase', payload);
        if (window.sessionStorage) {
            window.sessionStorage.setItem(dedupeKey, '1');
        }
    };

    if (window.styliiiishPendingPurchasePayload && typeof window.styliiiishPendingPurchasePayload === 'object') {
        window.styliiiishTrackPurchase(window.styliiiishPendingPurchasePayload);
    }
</script>
@endif
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $seoOrganizationName,
    'url' => $seoBaseUrl,
    'logo' => $seoImageValue,
    'description' => $seoOrganizationDescription,
    'sameAs' => $seoSameAs,
    'contactPoint' => [[
        '@type' => 'ContactPoint',
        'telephone' => '+20 105 087 4255',
        'contactType' => 'customer support',
        'availableLanguage' => ['ar', 'en'],
        'areaServed' => ['EG', 'CH'],
    ]],
    'areaServed' => [
        ['@type' => 'Country', 'name' => 'Egypt'],
        ['@type' => 'Country', 'name' => 'Switzerland'],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $seoOrganizationName,
    'alternateName' => ['styliiiish.com', $isEnglishLocale ? 'ستايلش' : 'Styliiiish'],
    'url' => $seoBaseUrl,
    'inLanguage' => [$seoLanguageCode, $isEnglishLocale ? 'ar' : 'en'],
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => $seoBaseUrl . '/' . $seoLanguageCode . '/shop?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $seoOrganizationName,
        'url' => $seoBaseUrl,
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $seoTitleValue,
    'description' => $seoDescriptionValue,
    'url' => $seoUrlValue,
    'inLanguage' => $seoLanguageCode,
    'isAccessibleForFree' => true,
    'isPartOf' => [
        '@type' => 'WebSite',
        'name' => $seoOrganizationName,
        'url' => $seoBaseUrl,
    ],
    'about' => [
        '@type' => 'Organization',
        'name' => $seoOrganizationName,
        'url' => $seoBaseUrl,
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $seoOrganizationName,
        'url' => $seoBaseUrl,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $seoImageValue,
        ],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
