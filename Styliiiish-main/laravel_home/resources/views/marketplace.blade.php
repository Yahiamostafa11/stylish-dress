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
            'page_title' => 'ماركت بليس الفساتين المستعملة | ستايلش',
            'meta_desc' => 'تصفحي الماركت بليس لاكتشاف فساتين مستعملة بحالة ممتازة مع تجربة احترافية وسهلة وسلسة.',
            'brand_tag' => 'لأن كل امرأة تستحق أن تتألق',
            'nav_home' => 'الرئيسية',
            'nav_shop' => 'المتجر',
            'nav_blog' => 'المدونة',
            'nav_about' => 'من نحن',
            'nav_marketplace' => 'الماركت بليس',
            'nav_sell' => 'بيعي فستانك',
            'nav_contact' => 'تواصل معنا',
            'lang_switch' => 'تبديل اللغة',
            'hero_badge' => 'Marketplace Dresses',
            'hero_title' => 'فساتين مستعملة بلمسة راقية',
            'hero_desc' => 'اختيارات منتقاة من فساتين الماركت بليس بتجربة عرض احترافية وسهلة، مع بحث سريع وفرز ذكي للوصول للفستان المناسب أسرع.',
            'search_placeholder' => 'ابحثي باسم الفستان...',
            'sort_newest' => 'الأحدث',
            'sort_low' => 'السعر: من الأقل للأعلى',
            'sort_high' => 'السعر: من الأعلى للأقل',
            'results' => 'عرض :from - :to من :total منتج',
            'no_results' => 'لا توجد منتجات مطابقة حاليًا. جرّبي كلمات بحث أخرى.',
            'price_unknown' => 'تواصل لمعرفة السعر',
            'view_product' => 'معاينة المنتج',
            'buy_now' => 'اطلبي الآن',
            'save_label' => 'توفير :amount ج.م',
            'discount_label' => 'خصم :percent%',
            'prev' => 'السابق',
            'next' => 'التالي',
            'page' => 'صفحة :current من :last',
            'footer_title' => 'ستيليش فاشون هاوس',
            'footer_desc' => 'نعمل بشغف على تقديم أحدث تصاميم الفساتين لتناسب كل مناسبة خاصة بك.',
            'footer_hours' => 'مواعيد العمل: السبت إلى الجمعة من 11:00 صباحًا حتى 7:00 مساءً.',
            'contact_us' => 'تواصلي معنا',
            'direct_call' => 'اتصال مباشر',
            'quick_links' => 'روابط سريعة',
            'official_info' => 'معلومات رسمية',
            'policies' => 'سياسات وقوانين',
            'about_us' => 'من نحن',
            'privacy' => 'سياسة الخصوصية',
            'terms' => 'الشروط والأحكام',
            'market_policy' => 'سياسة الماركت بليس',
            'refund_policy' => 'سياسة الاسترجاع والاستبدال',
            'faq' => 'الأسئلة الشائعة',
            'shipping_policy' => 'سياسة الشحن والتوصيل',
            'cookies' => 'سياسة ملفات الارتباط',
            'categories' => 'الأقسام',
            'official_address' => '1 شارع نبيل خليل، مدينة نصر، القاهرة، مصر',
            'rights' => 'جميع الحقوق محفوظة © :year Styliiiish | تشغيل وتطوير',
            'home_mini' => 'الرئيسية',
            'shop_mini' => 'المتجر',
            'cart_mini' => 'السلة',
            'account_mini' => 'حسابي',
            'fav_mini' => 'المفضلة',
        ],
        'en' => [
            'page_title' => 'Used Dresses Marketplace | Styliiiish',
            'meta_desc' => 'Browse used marketplace dresses with a premium, smooth, and user-friendly shopping experience.',
            'brand_tag' => 'Because every woman deserves to shine',
            'nav_home' => 'Home',
            'nav_shop' => 'Shop',
            'nav_blog' => 'Blog',
            'nav_about' => 'About Us',
            'nav_marketplace' => 'Marketplace',
            'nav_sell' => 'Sell Your Dress',
            'nav_contact' => 'Contact Us',
            'lang_switch' => 'Language Switcher',
            'hero_badge' => 'Marketplace Dresses',
            'hero_title' => 'Premium Pre-Loved Dresses',
            'hero_desc' => 'Curated marketplace dresses in a smooth and elegant browsing experience with quick search and smart sorting.',
            'search_placeholder' => 'Search dresses...',
            'sort_newest' => 'Newest',
            'sort_low' => 'Price: Low to High',
            'sort_high' => 'Price: High to Low',
            'results' => 'Showing :from - :to of :total products',
            'no_results' => 'No matching dresses right now. Try another search keyword.',
            'price_unknown' => 'Contact for price',
            'view_product' => 'View Product',
            'buy_now' => 'Order Now',
            'save_label' => 'Save :amount EGP',
            'discount_label' => ':percent% OFF',
            'prev' => 'Previous',
            'next' => 'Next',
            'page' => 'Page :current of :last',
            'footer_title' => 'Styliiiish Fashion House',
            'footer_desc' => 'We are passionate about offering the latest dress designs for every special occasion.',
            'footer_hours' => 'Working hours: Saturday to Friday from 11:00 AM to 7:00 PM.',
            'contact_us' => 'Contact Us',
            'direct_call' => 'Direct Call',
            'quick_links' => 'Quick Links',
            'official_info' => 'Official Info',
            'policies' => 'Policies & Legal',
            'about_us' => 'About Us',
            'privacy' => 'Privacy Policy',
            'terms' => 'Terms & Conditions',
            'market_policy' => 'Marketplace Policy',
            'refund_policy' => 'Refund & Return Policy',
            'faq' => 'FAQ',
            'shipping_policy' => 'Shipping & Delivery Policy',
            'cookies' => 'Cookie Policy',
            'categories' => 'Categories',
            'official_address' => '1 Nabil Khalil St, Nasr City, Cairo, Egypt',
            'rights' => 'All rights reserved © :year Styliiiish | Powered by',
            'home_mini' => 'Home',
            'shop_mini' => 'Shop',
            'cart_mini' => 'Cart',
            'account_mini' => 'Account',
            'fav_mini' => 'Wishlist',
        ],
    ];

    $normalizeBrandText = fn (string $value) => $currentLocale === 'en'
        ? (preg_replace('/ستايلش/iu', 'Styliiiish', $value) ?? $value)
        : (preg_replace('/styliiiish/iu', 'ستايلش', $value) ?? $value);
    $t = fn (string $key) => $normalizeBrandText((string) ($translations[$currentLocale][$key] ?? $translations['ar'][$key] ?? $key));
    $canonicalPath = $localePrefix . '/marketplace';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);

    $formatNumber = fn ($value) => number_format((float) $value, 0);
    $productsCount = $products->total();
    $from = $products->firstItem() ?? 0;
    $to = $products->lastItem() ?? 0;
    $resultsText = str_replace([':from', ':to', ':total'], [(string) $from, (string) $to, (string) $productsCount], $t('results'));
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/marketplace">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/marketplace">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $t('page_title') }}">
    <meta property="og:description" content="{{ $t('meta_desc') }}">
    <meta property="og:url" content="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <meta property="og:image" content="{{ $wpIcon }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $t('page_title') }}">
    <meta name="twitter:description" content="{{ $t('meta_desc') }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $wpIcon }}">
    <link rel="apple-touch-icon" href="{{ $wpIcon }}">
    <title>{{ $t('page_title') }}</title>
    @include('partials.shared-seo-meta')
    <style>
        :root { --wf-main-rgb: 213, 21, 34; --wf-main-color: rgb(var(--wf-main-rgb)); --wf-secondary-color: #17273B; --bg: #f6f7fb; --card: #ffffff; --text: #17273B; --muted: #5a6678; --line: rgba(189, 189, 189, 0.4); --primary: var(--wf-main-color); --secondary: var(--wf-secondary-color); }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.65; }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1180px, 92%); margin: 0 auto; }
        .main-header { background: #fff; border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 40; box-shadow: 0 8px 24px rgba(23,39,59,.06); }
        .main-header-inner { min-height: 84px; display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 16px; }
        .brand { display: flex; flex-direction: column; gap: 2px; }
        .brand-logo { height: 40px; width: auto; max-width: min(220px,38vw); object-fit: contain; }
        .brand-tag { color: var(--muted); font-size: 12px; font-weight: 600; }
        .main-nav { display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap; background: #f9fbff; border: 1px solid var(--line); border-radius: 12px; padding: 6px; }
        .main-nav a { color: var(--secondary); font-size: 14px; font-weight: 700; padding: 8px 12px; border-radius: 8px; transition: .2s ease; }
        .main-nav a:hover, .main-nav a.active { color: var(--primary); background: #fff4f5; }
        .lang-switch { position: relative; display: inline-grid; grid-template-columns: 1fr 1fr; align-items: center; direction: ltr; width: 110px; height: 34px; background: rgba(23,39,59,.1); border: 1px solid rgba(23,39,59,.18); border-radius: 999px; padding: 3px; overflow: hidden; }
        .lang-indicator { position: absolute; top: 3px; width: calc(50% - 3px); height: calc(100% - 6px); background: #fff; border-radius: 999px; transition: .25s ease; z-index: 1; }
        .lang-switch.is-ar .lang-indicator { left: 3px; }
        .lang-switch.is-en .lang-indicator { right: 3px; }
        .lang-switch a { position: relative; z-index: 2; text-align: center; font-size: 12px; font-weight: 800; color: var(--secondary); opacity: .75; padding: 5px 0; }
        .lang-switch a.active { opacity: 1; }

        .hero { padding: 28px 0 12px; }
        .hero-box { background: linear-gradient(160deg,#ffffff 0%,#fff4f5 100%); border: 1px solid var(--line); border-radius: 18px; padding: 24px; box-shadow: 0 10px 30px rgba(23,39,59,.07); animation: fadeUp .45s ease both; }
        .badge { display: inline-flex; align-items: center; background: #ffeef0; color: var(--primary); border-radius: 999px; padding: 7px 12px; font-size: 13px; font-weight: 700; margin-bottom: 10px; }
        .hero h1 { margin: 0 0 8px; font-size: clamp(28px,4vw,42px); line-height: 1.2; }
        .hero p { margin: 0; color: var(--muted); max-width: 860px; }

        .controls { margin-top: 14px; display: grid; grid-template-columns: 1fr auto; gap: 8px; }
        .search-form { display: grid; grid-template-columns: 1fr auto auto; gap: 8px; }
        .search { border: 1px solid var(--line); border-radius: 12px; padding: 10px 12px; font-size: 14px; background: #fff; color: var(--text); }
        .sort { border: 1px solid var(--line); border-radius: 12px; background: #fff; min-height: 42px; padding: 0 10px; font-size: 14px; color: var(--secondary); }
        .btn-filter { border: 0; border-radius: 12px; background: var(--secondary); color: #fff; min-height: 42px; padding: 0 14px; font-weight: 800; font-size: 13px; cursor: pointer; }
        .results { border: 1px dashed rgba(var(--wf-main-rgb), .35); border-radius: 12px; padding: 10px 12px; background: #fff; color: var(--secondary); font-size: 13px; font-weight: 700; white-space: nowrap; text-align: center; }

        .products { padding: 8px 0 22px; }
        .grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 14px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 16px; overflow: hidden; box-shadow: 0 8px 20px rgba(23,39,59,.05); display: flex; flex-direction: column; transform: translateY(0); transition: transform .25s ease, box-shadow .25s ease; animation: fadeUp .45s ease both; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(23,39,59,.1); }
        .media { position: relative; background: #f2f5fb; }
        .thumb { width: 100%; aspect-ratio: 3/4; object-fit: cover; display: block; }
        .discount { position: absolute; top: 10px; inset-inline-end: 10px; background: rgba(213,21,34,.92); color: #fff; border-radius: 999px; padding: 5px 10px; font-size: 11px; font-weight: 800; }
        .card-body { padding: 12px; display: grid; gap: 8px; height: 100%; }
        .title { margin: 0; font-size: 15px; line-height: 1.45; min-height: 44px; color: var(--secondary); display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; }
        .prices { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .price { color: var(--primary); font-weight: 900; font-size: 18px; }
        .old { color: #8b8b97; text-decoration: line-through; font-size: 13px; }
        .save { display: inline-flex; width: fit-content; padding: 4px 8px; border-radius: 999px; background: #fff3f4; color: var(--primary); font-size: 11px; font-weight: 800; }
        .actions { margin-top: auto; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .btn-buy, .btn-view { min-height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; }
        .btn-buy { background: var(--primary); color: #fff; }
        .btn-view { border: 1px solid var(--line); color: var(--secondary); background: #fff; }

        .empty { background: #fff; border: 1px dashed var(--line); border-radius: 12px; padding: 14px; color: var(--muted); }

        .pager { margin: 16px 0 6px; display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .page-link { border: 1px solid var(--line); border-radius: 10px; padding: 8px 12px; background: #fff; color: var(--secondary); font-size: 13px; font-weight: 700; }
        .page-link.disabled { opacity: .5; pointer-events: none; }

        .site-footer { margin-top: 8px; background: #0f1a2a; color: #fff; border-top: 4px solid var(--primary); }
        .footer-grid { display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr; gap: 18px; padding: 34px 0 22px; }
        .footer-brand, .footer-col { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); border-radius: 14px; padding: 16px; }
        .footer-brand-logo { width: auto; height: 34px; max-width: min(220px,100%); object-fit: contain; display: block; margin-bottom: 12px; filter: brightness(0) invert(1); opacity: .96; }
        .footer-brand h4, .footer-col h5 { margin: 0 0 10px; font-size: 18px; color: #fff; }
        .footer-brand p { margin: 0 0 10px; color: #b8c2d1; font-size: 14px; }
        .footer-links { list-style: none; margin: 0; padding: 0; display: grid; gap: 7px; }
        .footer-links a { color: #b8c2d1; font-size: 14px; transition: .2s ease; }
        .footer-links a:hover { color: #fff; }
        .footer-contact-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .footer-contact-row a { color: #fff; background: rgba(213,21,34,.16); border: 1px solid rgba(213,21,34,.35); border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 700; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.14); padding: 12px 0 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; color: #b8c2d1; font-size: 13px; }
        .footer-mini-nav { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; padding-bottom: 18px; }
        .footer-mini-nav a { color: #b8c2d1; font-size: 13px; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width:1100px) { .grid { grid-template-columns: repeat(3,minmax(0,1fr)); } }
        @media (max-width:900px) { .main-header-inner { grid-template-columns: 1fr; padding: 12px 0; } .brand, .main-nav, .header-tools { justify-content: center; text-align: center; } .controls, .search-form { grid-template-columns: 1fr; } .grid { grid-template-columns: repeat(2,minmax(0,1fr)); } .footer-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width:620px) { .grid { grid-template-columns: 1fr; } .actions { grid-template-columns: 1fr; } .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; } .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; } .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; } }
    </style>
    @include('partials.shared-home-header-styles')
</head>
<body>
@include('partials.shared-home-header')

<section class="hero">
    <div class="container hero-box">
        <span class="badge">{{ $t('hero_badge') }}</span>
        <h1>{{ $t('hero_title') }}</h1>
        <p>{{ $t('hero_desc') }}</p>
        <div class="controls">
            <form class="search-form" method="GET" action="{{ $localePrefix }}/marketplace">
                <input class="search" type="search" name="q" value="{{ $search }}" placeholder="{{ $t('search_placeholder') }}" aria-label="{{ $t('search_placeholder') }}">
                <select class="sort" name="sort" aria-label="Sort">
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>{{ $t('sort_newest') }}</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>{{ $t('sort_low') }}</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>{{ $t('sort_high') }}</option>
                </select>
                <button class="btn-filter" type="submit">{{ $isEnglish ? 'Apply' : 'تطبيق' }}</button>
            </form>
            <div class="results">{{ $resultsText }}</div>
        </div>
    </div>
</section>

<section class="products">
    <div class="container">
        @if($products->count() > 0)
            <div class="grid">
                @foreach($products as $product)
                    @php
                        $price = (float) ($product->price ?? 0);
                        $regular = (float) ($product->regular_price ?? 0);
                        $isSale = $regular > 0 && $price > 0 && $regular > $price;
                        $discount = $isSale ? (int) round((($regular - $price) / $regular) * 100) : 0;
                        $saving = $isSale ? ($regular - $price) : 0;
                        $productLink = $localePrefix . '/item/' . rawurlencode((string) $product->post_name);
                        $image = $product->image ?: ($wpBaseUrl . '/wp-content/uploads/woocommerce-placeholder.webp');
                    @endphp
                    <article class="card" style="animation-delay: {{ (($loop->index % 8) * 0.04) }}s;">
                        <div class="media">
                            <img class="thumb" src="{{ $image }}" alt="{{ $product->post_title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $wpBaseUrl }}/wp-content/uploads/woocommerce-placeholder.webp';">
                            @if($isSale)
                                <span class="discount">{{ str_replace(':percent', (string) $discount, $t('discount_label')) }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h2 class="title">{{ $product->post_title }}</h2>
                            <div class="prices">
                                <span class="price">{{ $price > 0 ? ($formatNumber($price) . ' ج.م') : $t('price_unknown') }}</span>
                                @if($isSale)
                                    <span class="old">{{ $formatNumber($regular) }} ج.م</span>
                                @endif
                            </div>
                            @if($isSale)
                                <span class="save">{{ str_replace(':amount', $formatNumber($saving), $t('save_label')) }}</span>
                            @endif
                            <div class="actions">
                                <a class="btn-buy" href="{{ $productLink }}" target="_blank" rel="noopener">{{ $t('buy_now') }}</a>
                                <a class="btn-view" href="{{ $productLink }}" target="_blank" rel="noopener">{{ $t('view_product') }}</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($products->lastPage() > 1)
                <div class="pager">
                    <a class="page-link {{ $products->onFirstPage() ? 'disabled' : '' }}" href="{{ $products->onFirstPage() ? '#' : $products->previousPageUrl() }}">{{ $t('prev') }}</a>
                    <span class="page-link">{{ str_replace([':current', ':last'], [(string) $products->currentPage(), (string) $products->lastPage()], $t('page')) }}</span>
                    <a class="page-link {{ $products->hasMorePages() ? '' : 'disabled' }}" href="{{ $products->hasMorePages() ? $products->nextPageUrl() : '#' }}">{{ $t('next') }}</a>
                </div>
            @endif
        @else
            <div class="empty">{{ $t('no_results') }}</div>
        @endif
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

