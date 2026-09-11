<!DOCTYPE html>
@php
    $currentLocale = $currentLocale ?? 'ar';
    $localePrefix = $localePrefix ?? '/ar';
    $isEnglish = $currentLocale === 'en';
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', request()->getSchemeAndHttpHost())), '/');
    $canonicalPath = $localePrefix . '/ads';

    $translations = [
        'ar' => [
            'meta_title' => 'عروض فساتين سهرة وزفاف وخطوبة بخصومات قوية | Styliiiish مصر',
            'meta_desc' => 'اكتشفي أفضل عروض Styliiiish على فساتين السهرة والزفاف والخطوبة في مصر بخصومات حقيقية لفترة محدودة، شحن سريع، ودعم قبل وبعد الطلب.',
            'meta_keywords' => 'فساتين سهرة, فساتين زفاف, فساتين خطوبة, عروض فساتين, خصومات فساتين, شراء فستان اونلاين, ستايلش مصر, styliiiish',
            'og_title' => 'عروض خاصة على الفساتين الآن | Styliiiish Egypt',
            'og_desc' => 'عروض محدودة على فساتين السهرة والزفاف والخطوبة مع توصيل سريع داخل مصر وتجربة شراء موثوقة.',
            'twitter_title' => 'عروض فساتين حصرية الآن | Styliiiish',
            'twitter_desc' => 'خصومات قوية لفترة محدودة على موديلات مختارة مع شحن سريع داخل مصر.',
            'og_image_alt' => 'عروض Styliiiish على فساتين المناسبات في مصر',
            'badge_offer' => 'عرض خاص من Styliiiish',
            'hero_title' => 'احجزي إطلالتك الآن بخصم يصل إلى 50%',
            'hero_lead' => 'اكتشفي موديلات السهرة والزفاف والخطوبة الأكثر طلبًا مع توصيل سريع داخل مصر وسياسات واضحة.',
            'offer_urgency' => '⏳ العروض الحالية لفترة محدودة — احجزي قبل نفاد الكميات',
            'hero_p1' => '✔️ خصومات حقيقية على منتجات مختارة',
            'hero_p2' => '✔️ شحن داخل مصر خلال 2–10 أيام',
            'hero_p3' => '✔️ شراء آمن وتجربة سلسة',
            'shop_now' => 'تسوقي الآن',
            'sell_dress' => 'بيعي فستانك',
            'order_whatsapp' => 'اطلبي عبر واتساب',
            'call_now' => 'اتصال سريع',
            'discount_selected' => 'خصم على مختارات مميزة',
            'products_ready' => 'منتج جاهز للطلب الآن',
            'delivery_time' => '2-10 أيام',
            'delivery_egypt' => 'توصيل داخل مصر',
            'section_title' => 'اشتري الآن مباشرة',
            'section_sub' => 'منتجات ظاهرة فورًا لتسهيل الشراء من الإعلانات بدون أي خطوات إضافية.',
            'view_all_products' => 'عرض كل المنتجات',
            'badge_brand' => 'ستايلش',
            'badge_marketplace' => 'ماركت بليس',
            'available_label' => 'متوفر الآن',
            'delivery_label' => 'توصيل سريع',
            'trust_1' => '💯 خامات موثوقة واختيار مدروس',
            'trust_2' => '🚚 توصيل سريع لكل المحافظات',
            'trust_3' => '📦 دعم قبل وبعد الطلب',
            'legal_title' => 'سياسات واضحة قبل الشراء',
            'legal_privacy' => 'الخصوصية',
            'legal_terms' => 'الشروط',
            'legal_refund' => 'الاسترجاع',
            'legal_shipping' => 'الشحن',
            'sale_badge' => 'خصم',
            'view_product' => 'تفاصيل الفستان',
            'contact_for_price' => 'تواصل لمعرفة السعر',
            'save_prefix' => 'وفّري',
            'buy_now' => 'اشتري الآن',
            'preview' => 'معاينة',
            'empty_title' => 'يتم تجهيز أقوى العروض الآن',
            'empty_desc' => 'تصفحي المتجر الكامل الآن لاختيار الفستان المناسب فورًا.',
            'bottom_cta_title' => 'جاهزة تختاري فستانك الآن؟',
            'bottom_cta_sub' => 'أكملي الشراء فورًا أو تصفحي المتجر بالكامل للحصول على خيارات أكثر.',
            'go_full_shop' => 'الانتقال للمتجر الكامل',
            'card_1_t' => 'تجربة تسوق سريعة',
            'card_1_d' => 'صفحة محسنة للحملات الإعلانية لتحقيق أعلى معدل تحويل.',
            'card_2_t' => 'أسعار تنافسية',
            'card_2_d' => 'مزيج قوي بين الجودة والسعر مع عروض متجددة يوميًا.',
            'card_3_t' => 'ثقة ووضوح',
            'card_3_d' => 'روابط وسياسات واضحة لدعم قرار الشراء بسرعة.',
            'currency' => 'ج.م',
            'sticky_shop' => 'ابدئي الشراء الآن',
        ],
        'en' => [
            'meta_title' => 'Evening, Bridal & Engagement Dress Deals | Styliiiish Egypt',
            'meta_desc' => 'Discover top Styliiiish deals on evening, bridal, and engagement dresses in Egypt with real limited-time discounts, fast delivery, and trusted support.',
            'meta_keywords' => 'evening dresses egypt, bridal dresses egypt, engagement dresses, dress deals, discounted dresses, buy dress online, styliiiish egypt',
            'og_title' => 'Special Dress Offers Live Now | Styliiiish Egypt',
            'og_desc' => 'Limited-time offers on evening, bridal, and engagement dresses with fast Egypt-wide delivery.',
            'twitter_title' => 'Exclusive Dress Offers | Styliiiish',
            'twitter_desc' => 'Shop selected styles with strong discounts and quick delivery across Egypt.',
            'og_image_alt' => 'Styliiiish special offers on occasion dresses in Egypt',
            'badge_offer' => 'Special Offer from Styliiiish',
            'hero_title' => 'Book Your Look Now with Up to 50% Off',
            'hero_lead' => 'Discover the most requested evening, bridal, and engagement styles with fast delivery across Egypt and clear policies.',
            'offer_urgency' => '⏳ Limited-time offers — reserve your style before stock runs out',
            'hero_p1' => '✔️ Real discounts on selected products',
            'hero_p2' => '✔️ Egypt-wide shipping in 2–10 days',
            'hero_p3' => '✔️ Secure checkout and smooth experience',
            'shop_now' => 'Shop Now',
            'sell_dress' => 'Sell Your Dress',
            'order_whatsapp' => 'Order via WhatsApp',
            'call_now' => 'Quick Call',
            'discount_selected' => 'Discount on selected picks',
            'products_ready' => 'Products ready to order now',
            'delivery_time' => '2-10 Days',
            'delivery_egypt' => 'Delivery across Egypt',
            'section_title' => 'Buy Directly Now',
            'section_sub' => 'Visible products instantly to simplify ad-driven purchases without extra steps.',
            'view_all_products' => 'View All Products',
            'badge_brand' => 'Styliiiish',
            'badge_marketplace' => 'Marketplace',
            'available_label' => 'In Stock',
            'delivery_label' => 'Fast Delivery',
            'trust_1' => '💯 Trusted quality and curated picks',
            'trust_2' => '🚚 Fast delivery across all governorates',
            'trust_3' => '📦 Support before and after order',
            'legal_title' => 'Clear policies before checkout',
            'legal_privacy' => 'Privacy',
            'legal_terms' => 'Terms',
            'legal_refund' => 'Returns',
            'legal_shipping' => 'Shipping',
            'sale_badge' => 'OFF',
            'view_product' => 'Dress Details',
            'contact_for_price' => 'Contact for price',
            'save_prefix' => 'Save',
            'buy_now' => 'Buy Now',
            'preview' => 'Preview',
            'empty_title' => 'Top offers are being refreshed now',
            'empty_desc' => 'Browse the full store now and pick the right dress instantly.',
            'bottom_cta_title' => 'Ready to pick your dress now?',
            'bottom_cta_sub' => 'Complete your purchase instantly or browse the full store for more options.',
            'go_full_shop' => 'Go to Full Store',
            'card_1_t' => 'Fast Shopping Experience',
            'card_1_d' => 'A campaign-optimized page built for higher conversion rates.',
            'card_2_t' => 'Competitive Prices',
            'card_2_d' => 'A strong mix of quality and value with daily refreshed offers.',
            'card_3_t' => 'Trust & Clarity',
            'card_3_d' => 'Clear links and policies that support faster purchase decisions.',
            'currency' => 'EGP',
            'sticky_shop' => 'Start Shopping Now',
        ],
    ];

    $normalizeBrandText = fn (string $value) => $currentLocale === 'en'
        ? (preg_replace('/ستايلش/iu', 'Styliiiish', $value) ?? $value)
        : (preg_replace('/styliiiish/iu', 'ستايلش', $value) ?? $value);
    $t = fn (string $key) => $normalizeBrandText((string) ($translations[$currentLocale][$key] ?? $translations['ar'][$key] ?? $key));

    $wpLogo = 'https://styliiiish.com/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpIcon = 'https://styliiiish.com/wp-content/uploads/2025/11/cropped-ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $whatsappLink = 'https://wa.me/201050874255';
    $callLink = 'tel:+201050874255';
    $wpLocalizedMyDressesUrl = $isEnglish
        ? ($wpBaseUrl . '/my-dresses/')
        : ($wpBaseUrl . '/ar/%d9%81%d8%b3%d8%a7%d8%aa%d9%8a%d9%86%d9%8a/');
    $privacyPolicyUrl = $localePrefix . '/privacy-policy';
    $termsPolicyUrl = $localePrefix . '/terms-conditions';
    $refundPolicyUrl = $localePrefix . '/refund-return-policy';
    $shippingPolicyUrl = $localePrefix . '/shipping-delivery-policy';

    $trackingKeys = [
        'gclid',
        'gbraid',
        'wbraid',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'fbclid',
    ];
    $requestQuery = request()->query();
    $trackingQuery = [];
    foreach ($trackingKeys as $trackingKey) {
        if (array_key_exists($trackingKey, $requestQuery) && (string) $requestQuery[$trackingKey] !== '') {
            $trackingQuery[$trackingKey] = (string) $requestQuery[$trackingKey];
        }
    }
    $trackingQueryString = http_build_query($trackingQuery);

    $schemaProducts = collect($products ?? [])->values()->map(function ($product) use ($localePrefix, $wpBaseUrl) {
        $price = (float) ($product->price ?? 0);
        $regular = (float) ($product->regular_price ?? 0);
        $normalizedPrice = $price > 0 ? $price : $regular;
        $slug = trim((string) ($product->post_name ?? ''));
        $image = trim((string) ($product->image ?? ''));

        return [
            'name' => trim((string) ($product->post_title ?? '')),
            'url' => $wpBaseUrl . $localePrefix . '/item/' . rawurlencode($slug),
            'image' => $image !== '' ? $image : ($wpBaseUrl . '/wp-content/uploads/woocommerce-placeholder.webp'),
            'price' => $normalizedPrice > 0 ? number_format($normalizedPrice, 2, '.', '') : null,
        ];
    })->filter(fn ($item) => ($item['name'] ?? '') !== '' && ($item['url'] ?? '') !== '')->values();

    $schemaItemList = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $t('section_title'),
        'url' => $wpBaseUrl . $canonicalPath,
        'numberOfItems' => $schemaProducts->count(),
        'itemListOrder' => 'https://schema.org/ItemListUnordered',
        'itemListElement' => $schemaProducts->map(function ($item, $index) {
            $product = [
                '@type' => 'Product',
                'name' => (string) ($item['name'] ?? ''),
                'url' => (string) ($item['url'] ?? ''),
                'image' => [(string) ($item['image'] ?? '')],
            ];

            if (!empty($item['price'])) {
                $product['offers'] = [
                    '@type' => 'Offer',
                    'priceCurrency' => 'EGP',
                    'price' => (string) ($item['price'] ?? '0.00'),
                    'availability' => 'https://schema.org/InStock',
                    'url' => (string) ($item['url'] ?? ''),
                ];
            }

            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $product,
            ];
        })->all(),
    ];

    $schemaBreadcrumb = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $isEnglish ? 'Home' : 'الرئيسية',
                'item' => $wpBaseUrl . $localePrefix,
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $isEnglish ? 'Shop' : 'المتجر',
                'item' => $wpBaseUrl . $localePrefix . '/shop',
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $isEnglish ? 'Offers' : 'العروض',
                'item' => $wpBaseUrl . $canonicalPath,
            ],
        ],
    ];
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="title" content="{{ $t('meta_title') }}">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <meta name="keywords" content="{{ $t('meta_keywords') }}">
    <meta name="author" content="Styliiiish">
    <meta name="publisher" content="Styliiiish">
    <meta name="language" content="{{ $isEnglish ? 'English' : 'Arabic' }}">
    <meta name="theme-color" content="#d51522">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="bingbot" content="index, follow">
    <link rel="preconnect" href="https://styliiiish.com" crossorigin>
    <link rel="dns-prefetch" href="//styliiiish.com">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/ads">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/ads">
    <link rel="alternate" hreflang="x-default" href="{{ $wpBaseUrl }}/ar/ads">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $isEnglish ? 'Styliiiish' : 'ستايلش' }}">
    <meta property="og:locale" content="{{ $isEnglish ? 'en_US' : 'ar_EG' }}">
    <meta property="og:locale:alternate" content="{{ $isEnglish ? 'ar_EG' : 'en_US' }}">
    <meta property="og:title" content="{{ $t('og_title') }}">
    <meta property="og:description" content="{{ $t('og_desc') }}">
    <meta property="og:url" content="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <meta property="og:image" content="{{ $wpIcon }}">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">
    <meta property="og:image:alt" content="{{ $t('og_image_alt') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $t('twitter_title') }}">
    <meta name="twitter:description" content="{{ $t('twitter_desc') }}">
    <meta name="twitter:image" content="{{ $wpIcon }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $wpIcon }}">
    <link rel="apple-touch-icon" href="{{ $wpIcon }}">
    <title>{{ $t('meta_title') }}</title>
    @include('partials.shared-seo-meta')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $t('meta_title'),
            'description' => $t('meta_desc'),
            'url' => $wpBaseUrl . $canonicalPath,
            'inLanguage' => $isEnglish ? 'en' : 'ar',
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => 'Styliiiish',
                'url' => $wpBaseUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Styliiiish',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $wpLogo,
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">{!! json_encode($schemaItemList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    <script type="application/ld+json">{!! json_encode($schemaBreadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    <style>
        :root {
            --primary: #d51522;
            --secondary: #17273B;
            --bg: #f6f7fb;
            --card: #ffffff;
            --line: rgba(189, 189, 189, .4);
            --muted: #5a6678;
            --success: #0a8f5b;
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, Arial, sans-serif; background: var(--bg); color: var(--secondary); }
        a { text-decoration: none; color: inherit; }
        .container { width: min(1100px, 92%); margin: 0 auto; }

        .hero { padding: 10px 0 8px; }
        .hero-wrap {
            background: linear-gradient(160deg, #ffffff 0%, #fff4f5 100%);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            display: grid;
            grid-template-columns: 1.45fr .55fr;
            gap: 10px;
            align-items: center;
            box-shadow: 0 10px 20px rgba(23,39,59,.06);
        }

        .badge { display: inline-flex; background: #fff; border: 1px solid var(--line); border-radius: 999px; padding: 6px 10px; font-size: 11px; font-weight: 800; color: var(--primary); }
        .landing-brand {
            display: inline-flex;
            margin-bottom: 8px;
            align-items: center;
        }
        .landing-brand img {
            height: 36px;
            width: auto;
            max-width: min(220px, 55vw);
            object-fit: contain;
        }
        h1 { margin: 8px 0 6px; font-size: clamp(22px, 3.3vw, 32px); line-height: 1.2; }
        .lead { margin: 0 0 8px; color: var(--muted); font-size: 14px; }
        .urgency { margin: 0 0 10px; padding: 8px 10px; border-radius: 10px; background: rgba(213,21,34,.08); border: 1px dashed rgba(213,21,34,.32); color: #99121b; font-size: 12px; font-weight: 800; }

        .points { margin: 0 0 10px; padding: 0; list-style: none; display: grid; gap: 6px; }
        .points li { background: #fff; border: 1px solid var(--line); border-radius: 10px; padding: 6px 8px; font-size: 12px; font-weight: 700; }

        .trust-row { margin: 12px 0 0; padding: 0; list-style: none; display: flex; gap: 8px; flex-wrap: wrap; }
        .trust-row li { background: #fff; border: 1px solid var(--line); border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 700; }

        .legal-row { margin: 10px 0 0; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .legal-row .label { font-size: 12px; font-weight: 800; color: #d7e0ed; }
        .legal-row a { font-size: 12px; color: #fff; border-bottom: 1px solid rgba(255,255,255,.65); }

        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn { min-height: 38px; padding: 0 14px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-light { background: #fff; border: 1px solid var(--line); }
        .btn-wa { background: #25D366; color: #fff; }
        .btn-call { background: #fff; color: var(--secondary); border: 1px solid rgba(23,39,59,.22); }

        .promo-box {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            display: grid;
            gap: 8px;
        }

        .promo-box strong { display: block; font-size: 24px; color: var(--primary); line-height: 1; }
        .promo-box span { display: block; font-size: 12px; color: var(--muted); margin-top: 2px; }

        .mini-stat { border: 1px solid var(--line); border-radius: 10px; padding: 8px; background: #fff; }
        .mini-stat b { color: var(--primary); font-size: 16px; display: block; }
        .mini-stat small { color: var(--muted); font-size: 11px; }

        .section { padding: 4px 0 28px; }
        .section-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 12px; }
        .section-head h2 { margin: 0; font-size: clamp(22px, 3vw, 31px); }
        .section-head p { margin: 0; color: var(--muted); font-size: 14px; }

        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }

        .ads-products .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 20px rgba(23, 39, 59, 0.04);
            transition: .25s ease;
        }

        .ads-products .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 28px rgba(23, 39, 59, 0.12);
            border-color: rgba(213,21,34,.35);
        }

        .ads-products .product-media {
            position: relative;
            aspect-ratio: 3 / 4;
            min-height: 360px;
            max-height: none;
            overflow: hidden;
        }
        .ads-products .thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #f2f2f5;
            transition: .35s ease;
            display: block;
        }
        .ads-products .card:hover .thumb {
            transform: scale(1.03);
        }

        .ads-products .card-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 2;
        }

        .ads-products .badge-chip {
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 800;
            line-height: 1;
            backdrop-filter: blur(3px);
            width: fit-content;
        }

        .ads-products .badge-brand {
            background: linear-gradient(135deg, rgba(213, 21, 34, 0.96), rgba(183, 15, 26, 0.96));
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.28);
            box-shadow: 0 6px 14px rgba(213, 21, 34, 0.28);
        }

        .ads-products .badge-marketplace {
            background: rgba(23, 39, 59, 0.9);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .ads-products .badge-discount {
            background: rgba(213,21,34,.9);
            color: #fff;
        }

        .ads-products .content { padding: 12px; display: flex; flex-direction: column; gap: 8px; }
        .ads-products .name {
            margin: 0;
            font-size: 15px;
            line-height: 1.45;
            min-height: 46px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ads-products .meta {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .ads-products .prices {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 0;
        }

        .ads-products .price { color: var(--primary); font-size: 17px; font-weight: 900; }
        .ads-products .old { color: #4e5665; text-decoration: line-through; font-size: 14px; }

        .ads-products .sale {
            display: inline-flex;
            background: #0b7a4e;
            color: #ffffff;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .ads-products .sale,
        .ads-products .sale * {
            color: #ffffff !important;
        }

        .ads-products .save {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: 4px 9px;
            border-radius: 999px;
            background: #fff3f4;
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
        }

        .ads-products .card-actions { margin-top: auto; display: grid; grid-template-columns: 1fr; gap: 8px; }

        .ads-products .product-details-btn {
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: #fff;
            padding: 10px;
            border-radius: 10px;
            min-height: 44px;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
            text-align: center;
        }

        .ads-products .product-details-btn:hover { background: #b8101c; }

        .bottom-cta {
            margin-top: 16px;
            background: linear-gradient(120deg, var(--secondary), #22354a);
            color: #fff;
            border-radius: 16px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .bottom-cta strong { font-size: 20px; }
        .bottom-cta p { margin: 4px 0 0; color: #d7e0ed; font-size: 14px; }
        .bottom-cta .btn-light {
            min-width: 180px;
            color: var(--secondary);
        }

        .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
        .benefit-card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 14px; }
        .benefit-card h3 { margin: 0 0 6px; font-size: 17px; }
        .benefit-card p { margin: 0; color: var(--muted); font-size: 14px; }

        .empty-state {
            border: 1px dashed rgba(23,39,59,.25);
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
        }
        .empty-state h3 { margin: 0 0 8px; font-size: 20px; }
        .empty-state p { margin: 0 0 12px; color: var(--muted); }

        .ads-sticky-cta {
            position: sticky;
            bottom: 10px;
            z-index: 25;
            margin: 0 auto 10px;
            width: min(520px, 94%);
            display: none;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px;
            border-radius: 999px;
            background: rgba(23,39,59,.92);
            box-shadow: 0 10px 30px rgba(23,39,59,.22);
        }
        .ads-sticky-cta .btn { min-height: 40px; }

        .site-footer { margin-top: 10px; background: #0f1a2a; color: #fff; border-top: 4px solid var(--primary); }
        .footer-grid { padding: 32px 0 20px; display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.1fr; gap: 20px; }
        .footer-brand-logo { width: 156px; max-width: 100%; object-fit: contain; margin-bottom: 10px; display: block; }
        .footer-brand h4 { margin: 0 0 8px; font-size: 18px; }
        .footer-brand p { margin: 0 0 8px; color: #c8d3e2; font-size: 14px; line-height: 1.7; }
        .footer-contact-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .footer-contact-row a { color: #fff; border: 1px solid rgba(255,255,255,.25); border-radius: 999px; padding: 6px 10px; font-size: 12px; }
        .footer-col h5 { margin: 0 0 10px; font-size: 15px; }
        .footer-links { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
        .footer-links a { color: #d7e0ed; font-size: 14px; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.18); padding: 12px 0; display: flex; justify-content: space-between; gap: 10px; color: #b8c5d8; font-size: 13px; flex-wrap: wrap; }
        .footer-bottom a { color: #fff; }

        @media (max-width: 900px) {
            .hero-wrap { grid-template-columns: 1fr; }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .cards { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 640px) {
            .hero { padding-top: 6px; }
            .hero-wrap { padding: 12px; border-radius: 12px; }
            .landing-brand img { height: 30px; max-width: 180px; }
            .lead { font-size: 13px; }
            .actions .btn { width: 100%; }
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .card-actions { grid-template-columns: 1fr; }
            .bottom-cta .btn-light { width: 100%; }
            .ads-sticky-cta { display: flex; }
            .ads-products .product-media { min-height: 280px; }
        }

        @media (max-width: 390px) {
            .grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
    @include('partials.shared-home-header-styles')
    <style>
        .site-footer .footer-bottom a {
            text-decoration: underline;
            text-underline-offset: 2px;
            text-decoration-thickness: 1.5px;
            font-weight: 700;
        }
    </style>
</head>
<body>
    @include('partials.shared-home-header')

    <main class="container">
        <section class="hero">
            <div class="hero-wrap">
                <div>
                    <a class="landing-brand" href="{{ $localePrefix }}" aria-label="Styliiiish Home">
                        <img src="{{ $wpLogo }}" alt="Styliiiish" width="320" height="72" decoding="async" fetchpriority="high" onerror="this.onerror=null;this.src='/brand/logo.png';">
                    </a>
                    <span class="badge">{{ $t('badge_offer') }}</span>
                    <h1>{{ $t('hero_title') }}</h1>
                    <p class="lead">{{ $t('hero_lead') }}</p>
                    <p class="urgency">{{ $t('offer_urgency') }}</p>

                    <ul class="points">
                        <li>{{ $t('hero_p1') }}</li>
                        <li>{{ $t('hero_p2') }}</li>
                        <li>{{ $t('hero_p3') }}</li>
                    </ul>

                    <div class="actions">
                        <a class="btn btn-primary" href="{{ $localePrefix }}/shop" data-track-link="1">{{ $t('shop_now') }}</a>
                        <a class="btn btn-wa" href="{{ $whatsappLink }}" target="_blank" rel="noopener" data-track-link="1">{{ $t('order_whatsapp') }}</a>
                        <a class="btn btn-call" href="{{ $callLink }}">{{ $t('call_now') }}</a>
                        <a class="btn btn-light" href="{{ $wpLocalizedMyDressesUrl }}" target="_blank" rel="noopener">{{ $t('sell_dress') }}</a>
                    </div>

                    <ul class="trust-row">
                        <li>{{ $t('trust_1') }}</li>
                        <li>{{ $t('trust_2') }}</li>
                        <li>{{ $t('trust_3') }}</li>
                    </ul>
                </div>

                <aside class="promo-box">
                    <strong>50%</strong>
                    <span>{{ $t('discount_selected') }}</span>
                    <div class="mini-stat">
                        <b>{{ number_format((int) ($total ?? 0)) }}+</b>
                        <small>{{ $t('products_ready') }}</small>
                    </div>
                    <div class="mini-stat">
                        <b>{{ $t('delivery_time') }}</b>
                        <small>{{ $t('delivery_egypt') }}</small>
                    </div>
                </aside>
            </div>
        </section>

        <section class="section ads-products">
            <div class="section-head">
                <div>
                    <h2>{{ $t('section_title') }}</h2>
                    <p>{{ $t('section_sub') }}</p>
                </div>
                <a class="btn btn-light" href="{{ $localePrefix }}/shop" data-track-link="1">{{ $t('view_all_products') }}</a>
            </div>

            @if(($products ?? collect())->count() > 0)
            <div class="grid">
                @foreach(($products ?? collect()) as $product)
                    @php
                        $price = (float) ($product->price ?? 0);
                        $regular = (float) ($product->regular_price ?? 0);
                        $isSale = $regular > 0 && $price > 0 && $regular > $price;
                        $discount = $isSale ? round((($regular - $price) / $regular) * 100) : 0;
                        $saving = $isSale ? ($regular - $price) : 0;
                        $image = $product->image ?: 'https://styliiiish.com/wp-content/uploads/woocommerce-placeholder.webp';
                        $isMarketplace = (int) ($product->is_marketplace ?? 0) === 1;
                        $primaryBadge = $isMarketplace ? $t('badge_marketplace') : $t('badge_brand');
                    @endphp

                    <article class="card">
                        <div class="product-media">
                            <img class="thumb" src="{{ $image }}" alt="{{ $product->post_title }}" width="600" height="800" sizes="(max-width: 640px) 48vw, (max-width: 900px) 31vw, 23vw" loading="{{ $loop->index < 2 ? 'eager' : 'lazy' }}" fetchpriority="{{ $loop->first ? 'high' : 'low' }}" decoding="async">
                            <div class="card-badges">
                                <span class="badge-chip {{ $isMarketplace ? 'badge-marketplace' : 'badge-brand' }}">{{ $primaryBadge }}</span>
                                @if($isSale)
                                    <span class="badge-chip badge-discount">{{ $t('sale_badge') }} {{ $discount }}%</span>
                                @endif
                            </div>
                        </div>
                        <div class="content">
                            <h3 class="name">{{ $product->post_title }}</h3>
                            <div class="meta">
                                <span>{{ $t('available_label') }}</span>
                                <span>{{ $t('delivery_label') }}</span>
                            </div>

                            <div class="prices">
                                <span class="price">
                                    @if($price > 0)
                                        {{ number_format($price) }} {{ $t('currency') }}
                                    @else
                                        {{ $t('contact_for_price') }}
                                    @endif
                                </span>
                                @if($isSale)
                                    <span class="old">{{ number_format($regular) }} {{ $t('currency') }}</span>
                                    <span class="sale">{{ $t('sale_badge') }} {{ $discount }}%</span>
                                @endif
                            </div>

                            @if($isSale)
                                <span class="save">{{ $t('save_prefix') }} {{ number_format($saving) }} {{ $t('currency') }}</span>
                            @endif

                            <div class="card-actions">
                                <a class="product-details-btn" href="{{ $localePrefix }}/item/{{ $product->post_name }}" data-track-link="1" aria-label="{{ $t('view_product') }}: {{ $product->post_title }}">{{ $t('view_product') }}</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <h3>{{ $t('empty_title') }}</h3>
                <p>{{ $t('empty_desc') }}</p>
                <a class="btn btn-primary" href="{{ $localePrefix }}/shop" data-track-link="1">{{ $t('view_all_products') }}</a>
            </div>
            @endif

            <div class="bottom-cta">
                <div>
                    <strong>{{ $t('bottom_cta_title') }}</strong>
                    <p>{{ $t('bottom_cta_sub') }}</p>
                    <div class="legal-row" aria-label="Policy quick links">
                        <span class="label">{{ $t('legal_title') }}:</span>
                        <a href="{{ $privacyPolicyUrl }}" data-track-link="1">{{ $t('legal_privacy') }}</a>
                        <a href="{{ $termsPolicyUrl }}" data-track-link="1">{{ $t('legal_terms') }}</a>
                        <a href="{{ $refundPolicyUrl }}" data-track-link="1">{{ $t('legal_refund') }}</a>
                        <a href="{{ $shippingPolicyUrl }}" data-track-link="1">{{ $t('legal_shipping') }}</a>
                    </div>
                </div>
                <a class="btn btn-light" href="{{ $localePrefix }}/shop" data-track-link="1">{{ $t('go_full_shop') }}</a>
            </div>
        </section>

        <section class="section">
            <div class="cards">
                <article class="benefit-card">
                    <h3>{{ $t('card_1_t') }}</h3>
                    <p>{{ $t('card_1_d') }}</p>
                </article>
                <article class="benefit-card">
                    <h3>{{ $t('card_2_t') }}</h3>
                    <p>{{ $t('card_2_d') }}</p>
                </article>
                <article class="benefit-card">
                    <h3>{{ $t('card_3_t') }}</h3>
                    <p>{{ $t('card_3_d') }}</p>
                </article>
            </div>
        </section>
    </main>

    <div class="ads-sticky-cta" aria-label="Ads quick actions">
        <a class="btn btn-primary" href="{{ $localePrefix }}/shop" data-track-link="1">{{ $t('sticky_shop') }}</a>
        <a class="btn btn-wa" href="{{ $whatsappLink }}" target="_blank" rel="noopener" data-track-link="1">{{ $t('order_whatsapp') }}</a>
    </div>

    @if(!empty($trackingQueryString))
    <script>
        (() => {
            const trackingQuery = @json($trackingQueryString);
            if (!trackingQuery) return;

            const shouldSkip = (href) => {
                if (!href) return true;
                const lower = href.toLowerCase();
                return (
                    lower.startsWith('#') ||
                    lower.startsWith('javascript:') ||
                    lower.startsWith('mailto:') ||
                    lower.startsWith('tel:')
                );
            };

            document.querySelectorAll('a[data-track-link="1"]').forEach((link) => {
                const href = link.getAttribute('href') || '';
                if (shouldSkip(href)) return;
                if (href.includes('wa.me')) return;

                const separator = href.includes('?') ? '&' : '?';
                if (!href.includes('gclid=') && !href.includes('utm_source=')) {
                    link.setAttribute('href', `${href}${separator}${trackingQuery}`);
                }
            });
        })();
    </script>
    @endif

    <script>
        (() => {
            const emitEvent = (eventName, payload = {}) => {
                const safePayload = {
                    page_type: 'ads_landing',
                    locale: @json($currentLocale),
                    ...payload,
                };

                if (typeof window.styliiiishTrackEvent === 'function') {
                    window.styliiiishTrackEvent(eventName, safePayload);
                }

                if (typeof window.gtag === 'function') {
                    window.gtag('event', eventName, safePayload);
                }

                if (Array.isArray(window.dataLayer)) {
                    window.dataLayer.push({ event: eventName, ...safePayload });
                }
            };

            const classifyLink = (href) => {
                const lower = (href || '').toLowerCase();
                if (lower.startsWith('tel:')) return { eventName: 'generate_lead', click_type: 'call' };
                if (lower.includes('wa.me')) return { eventName: 'generate_lead', click_type: 'whatsapp' };
                if (lower.includes('/shop')) return { eventName: 'view_item_list', click_type: 'shop' };
                if (lower.includes('/item/')) return { eventName: 'select_item', click_type: 'product' };
                return { eventName: 'ads_landing_click', click_type: 'other' };
            };

            document.querySelectorAll('a[data-track-link="1"]').forEach((link) => {
                link.addEventListener('click', () => {
                    const href = link.getAttribute('href') || '';
                    const text = (link.textContent || '').trim();
                    const { eventName, click_type: clickType } = classifyLink(href);

                    emitEvent(eventName, {
                        click_type: clickType,
                        link_text: text,
                        destination: href,
                    });
                }, { passive: true });
            });
        })();
    </script>

    @include('partials.shared-home-footer')
</body>
</html>
