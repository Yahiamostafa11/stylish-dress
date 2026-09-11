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
            'page_title' => 'الأقسام | ستايلش',
            'meta_desc' => 'استكشف أقسام منتجات Styliiiish بسهولة عبر صفحة احترافية تساعدك على الوصول السريع للقسم المناسب.',
            'brand_tag' => 'لأن كل امرأة تستحق أن تتألق',
            'nav_home' => 'الرئيسية',
            'nav_shop' => 'المتجر',
            'nav_blog' => 'المدونة',
            'nav_about' => 'من نحن',
            'nav_marketplace' => 'الماركت بليس',
            'nav_sell' => 'بيعي فستانك',
            'nav_contact' => 'تواصل معنا',
            'nav_terms' => 'الشروط والأحكام',
            'lang_switch' => 'تبديل اللغة',
            'hero_badge' => 'تصنيفات المنتجات',
            'hero_title' => 'تصفحي الأقسام',
            'hero_desc' => 'صفحة أقسام احترافية تساعدك على اكتشاف المنتجات بسرعة، مع عرض عدد المنتجات داخل كل قسم لتجربة تسوّق أوضح وأسهل.',
            'search_placeholder' => 'ابحثي باسم القسم...',
            'results' => 'عدد الأقسام المعروضة',
            'products_count' => 'منتج',
            'view_category' => 'تصفح القسم',
            'empty' => 'لا توجد نتائج مطابقة لبحثك الآن.',
            'back_shop' => 'العودة إلى المتجر',
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
            'page_title' => 'Categories | Styliiiish',
            'meta_desc' => 'Explore Styliiiish product categories through a professional categories page for faster browsing and discovery.',
            'brand_tag' => 'Because every woman deserves to shine',
            'nav_home' => 'Home',
            'nav_shop' => 'Shop',
            'nav_blog' => 'Blog',
            'nav_about' => 'About Us',
            'nav_marketplace' => 'Marketplace',
            'nav_sell' => 'Sell Your Dress',
            'nav_contact' => 'Contact Us',
            'nav_terms' => 'Terms & Conditions',
            'lang_switch' => 'Language Switcher',
            'hero_badge' => 'Product Categories',
            'hero_title' => 'Browse Categories',
            'hero_desc' => 'A professional categories page that helps you discover products faster, with clear product counts in each category for a smoother shopping experience.',
            'search_placeholder' => 'Search category name...',
            'results' => 'Visible categories',
            'products_count' => 'products',
            'view_category' => 'View Category',
            'empty' => 'No categories match your search right now.',
            'back_shop' => 'Back to Shop',
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
    $canonicalPath = $localePrefix . '/categories';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/categories">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/categories">
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

        .hero { padding: 28px 0 14px; }
        .hero-box { background: linear-gradient(160deg,#ffffff 0%,#fff4f5 100%); border: 1px solid var(--line); border-radius: 18px; padding: 24px; box-shadow: 0 10px 30px rgba(23,39,59,.07); }
        .badge { display: inline-flex; align-items: center; background: #ffeef0; color: var(--primary); border-radius: 999px; padding: 7px 12px; font-size: 13px; font-weight: 700; margin-bottom: 10px; }
        .hero h1 { margin: 0 0 8px; font-size: clamp(28px,4vw,42px); line-height: 1.2; }
        .hero p { margin: 0; color: var(--muted); max-width: 860px; }

        .controls { margin-top: 14px; display: grid; grid-template-columns: 1fr auto auto; gap: 8px; }
        .search { border: 1px solid var(--line); border-radius: 12px; padding: 10px 12px; font-size: 14px; background: #fff; color: var(--text); }
        .stat { border: 1px dashed rgba(var(--wf-main-rgb), .35); border-radius: 12px; padding: 10px 12px; background: #fff; color: var(--secondary); font-size: 13px; font-weight: 700; white-space: nowrap; }
        .btn-shop { display: inline-flex; align-items: center; justify-content: center; border: 1px solid var(--line); border-radius: 12px; padding: 10px 12px; background: #fff; color: var(--secondary); font-size: 13px; font-weight: 700; }

        .section { padding: 6px 0 20px; }
        .cards { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 12px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 14px; overflow: hidden; box-shadow: 0 8px 20px rgba(23,39,59,.05); display: flex; flex-direction: column; }
        .thumb-wrap { aspect-ratio: 4/3; overflow: hidden; background: #f2f5fb; }
        .thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
        .card-body { padding: 12px; display: grid; gap: 7px; }
        .name { margin: 0; font-size: 17px; line-height: 1.35; color: var(--secondary); }
        .desc { margin: 0; color: var(--muted); font-size: 13px; min-height: 38px; }
        .meta { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .count { font-size: 12px; font-weight: 800; color: var(--primary); }
        .cta { display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: var(--primary); color: #fff; padding: 8px 10px; font-size: 12px; font-weight: 800; }
        .empty { display: none; margin-top: 8px; background: #fff; border: 1px dashed var(--line); border-radius: 12px; padding: 12px; color: var(--muted); }

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

        @media (max-width:1100px) { .cards { grid-template-columns: repeat(3,minmax(0,1fr)); } }
        @media (max-width:900px) { .main-header-inner { grid-template-columns: 1fr; padding: 12px 0; } .brand, .main-nav, .header-tools { justify-content: center; text-align: center; } .controls { grid-template-columns: 1fr; } .cards { grid-template-columns: repeat(2,minmax(0,1fr)); } .footer-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width:620px) { .hero-box, .card { border-radius: 14px; } .cards { grid-template-columns: 1fr; } .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; } .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; } .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; } }
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
            <input id="catSearch" class="search" type="search" placeholder="{{ $t('search_placeholder') }}" aria-label="{{ $t('search_placeholder') }}">
            <div class="stat"><span>{{ $t('results') }}: </span><span id="visibleCount">0</span></div>
            <a class="btn-shop" href="{{ $localePrefix }}/shop">{{ $t('back_shop') }}</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cards" id="categoriesGrid">
            @foreach($categories as $category)
                @php
                    $desc = trim(strip_tags((string) ($category->description ?? '')));
                    $shortDesc = $desc !== '' ? (mb_strlen($desc) > 90 ? mb_substr($desc, 0, 90) . '…' : $desc) : '';
                    $image = $category->image ?: ($wpBaseUrl . '/wp-content/uploads/woocommerce-placeholder.webp');
                    $link = $localePrefix . '/shop?category=' . rawurlencode((string) $category->slug);
                @endphp
                <article class="card" data-name="{{ mb_strtolower((string) $category->name) }}" data-desc="{{ mb_strtolower($desc) }}">
                    <div class="thumb-wrap"><img class="thumb" src="{{ $image }}" alt="{{ $category->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $wpBaseUrl }}/wp-content/uploads/woocommerce-placeholder.webp';"></div>
                    <div class="card-body">
                        <h2 class="name">{{ $category->name }}</h2>
                        <p class="desc">{{ $shortDesc !== '' ? $shortDesc : '—' }}</p>
                        <div class="meta"><span class="count">{{ (int) $category->products_count }} {{ $t('products_count') }}</span><a class="cta" href="{{ $link }}">{{ $t('view_category') }}</a></div>
                    </div>
                </article>
            @endforeach
        </div>
        <div id="emptyState" class="empty">{{ $t('empty') }}</div>
    </div>
</section>

@include('partials.shared-home-footer')

<script>
    (function () {
        const input = document.getElementById('catSearch');
        const cards = Array.from(document.querySelectorAll('#categoriesGrid .card'));
        const visibleCount = document.getElementById('visibleCount');
        const emptyState = document.getElementById('emptyState');

        function applyFilter() {
            const q = (input.value || '').toLowerCase().trim();
            let shown = 0;

            cards.forEach((card) => {
                const haystack = (card.dataset.name + ' ' + card.dataset.desc).toLowerCase();
                const match = q === '' || haystack.includes(q);
                card.style.display = match ? '' : 'none';
                if (match) shown += 1;
            });

            visibleCount.textContent = String(shown);
            emptyState.style.display = shown === 0 ? 'block' : 'none';
        }

        applyFilter();
        input.addEventListener('input', applyFilter);
    })();
</script>
</body>
</html>

