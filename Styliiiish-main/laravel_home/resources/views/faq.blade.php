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
            'page_title' => 'الأسئلة الشائعة | ستايلش',
            'meta_desc' => 'اعرفي إجابات واضحة عن الشحن، الاسترجاع، البيع عبر الماركت بليس، الطلبات، وخدمة التأجير في Styliiiish.',
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
            'hero_badge' => 'مساعدة ودعم',
            'hero_title' => 'الأسئلة الشائعة',
            'hero_desc' => 'هنا ستجدين إجابات واضحة لأكثر الأسئلة شيوعًا حول الشراء، الشحن، الاسترجاع، البيع عبر الماركت بليس، والخدمات في Styliiiish.',
            'section_shipping' => 'الشحن والتوصيل',
            'section_returns' => 'الاسترجاع والاسترداد',
            'section_marketplace' => 'الماركت بليس (بيعي فستانك)',
            'section_orders' => 'المنتجات والطلبات',
            'section_rental' => 'خدمات تأجير الفساتين',
            'section_support' => 'التواصل والدعم',

            'q1' => 'كم تستغرق مدة التوصيل للمنتجات الجاهزة للشحن؟',
            'a1' => 'يتم تجهيز المنتجات الجاهزة خلال 1–2 يوم عمل، ويتم التوصيل خلال 1–3 أيام عمل. المدة الإجمالية المتوقعة: 2–4 أيام عمل.',
            'q2' => 'كم تستغرق مدة التوصيل للمنتجات المصنوعة حسب الطلب؟',
            'a2' => 'المنتجات المصنوعة حسب الطلب تحتاج 6–7 أيام عمل للتجهيز قبل الشحن، ثم 1–3 أيام عمل للتوصيل. المدة الإجمالية المتوقعة: 7–10 أيام عمل.',
            'q3' => 'هل توفرون شحنًا خارج مصر؟',
            'a3' => 'لا. حاليًا خدمات الشحن في Styliiiish متاحة داخل مصر فقط عبر شركاء شحن موثوقين.',

            'q4' => 'هل يمكنني استرجاع أو استبدال طلبي؟',
            'a4' => 'نعم، يمكن طلب الاسترجاع أو الاستبدال خلال 3 أيام من تاريخ التسليم بشرط أن يكون المنتج غير مستخدم، غير مُرتدى، وفي حالته الأصلية.',
            'q5' => 'ما المنتجات غير القابلة للاسترجاع؟',
            'a5' => 'الفساتين المستخدمة أو المرتداة، والمنتجات المخصصة أو المصنوعة حسب الطلب، والمنتجات المعلّمة بأنها بيع نهائي، غير قابلة للاسترجاع أو الاسترداد.',

            'q6' => 'كيف يعمل Styliiiish Marketplace؟',
            'a6' => 'يتيح Styliiiish Marketplace للأفراد عرض وبيع فساتينهم. وتقوم Styliiiish بتسهيل معالجة المدفوعات، تنسيق التوصيل، ودعم العملاء.',
            'q7' => 'ما نسبة عمولة الماركت بليس؟',
            'a7' => 'تطبق Styliiiish رسوم خدمة ماركت بليس بنسبة 50%. يحدد البائع صافي السعر، والسعر النهائي المعروض للمشتري يشمل رسوم الخدمة.',
            'q8' => 'متى يستلم البائع مستحقاته؟',
            'a8' => 'يتم صرف مستحقات البائع بعد نجاح التسليم وفق إجراءات التحقق وجدولة الصرف المعتمدة لدى Styliiiish.',

            'q9' => 'هل كل المنتجات متاحة بمقاسات متعددة؟',
            'a9' => 'كل منتج متاح بمقاس جاهز واحد. وإذا طُلب مقاس آخر، يتم تنفيذ القطعة حسب الطلب.',
            'q10' => 'هل يمكن إلغاء الطلب بعد إتمامه؟',
            'a10' => 'يمكن إلغاء الطلب فقط قبل بدء التجهيز أو التفصيل. يرجى التواصل مع الدعم في أسرع وقت بعد تأكيد الطلب.',

            'q11' => 'هل يمكن تأجير الفساتين أونلاين؟',
            'a11' => 'لا. خدمات تأجير الفساتين متاحة حصريًا داخل الفرع الفعلي ولا تتم عبر الموقع الإلكتروني.',

            'q12' => 'كيف يمكنني التواصل مع دعم Styliiiish؟',
            'a12' => 'يمكنك التواصل معنا في أي وقت عبر: email@styliiiish.com',

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
            'page_title' => 'Styliiiish FAQ | Styliiiish',
            'meta_desc' => 'Find clear answers to common questions about shopping, shipping, returns, marketplace selling, and services at Styliiiish.',
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
            'hero_badge' => 'Help & Support',
            'hero_title' => 'Frequently Asked Questions (FAQ)',
            'hero_desc' => 'Find clear answers to the most common questions about shopping, shipping, returns, marketplace selling, and services at Styliiiish.',
            'section_shipping' => 'Shipping & Delivery',
            'section_returns' => 'Returns & Refunds',
            'section_marketplace' => 'Marketplace (Sell Your Dress)',
            'section_orders' => 'Products & Orders',
            'section_rental' => 'Dress Rental Services',
            'section_support' => 'Contact & Support',

            'q1' => 'How long does delivery take for ready-to-ship items?',
            'a1' => 'Ready-to-ship items are prepared within 1–2 business days and delivered within 1–3 business days. Total estimated delivery time is 2–4 business days.',
            'q2' => 'How long does delivery take for made-to-order items?',
            'a2' => 'Made-to-order items require 6–7 business days for preparation before shipment, followed by 1–3 business days for delivery. Total estimated delivery time is 7–10 business days.',
            'q3' => 'Do you ship outside Egypt?',
            'a3' => 'No. Styliiiish currently provides shipping services within Egypt only through trusted courier partners.',

            'q4' => 'Can I return or exchange my order?',
            'a4' => 'Yes, returns or exchanges are accepted within 3 days of delivery if the item is unused, unworn, and in its original condition.',
            'q5' => 'Which items are non-returnable?',
            'a5' => 'Used or worn dresses, customized or made-to-order items, and products marked as final sale are not eligible for return or refund.',

            'q6' => 'How does Styliiiish Marketplace work?',
            'a6' => 'Styliiiish Marketplace allows individuals to list and sell their own dresses. Styliiiish facilitates payment processing, delivery coordination, and customer support.',
            'q7' => 'What is the marketplace commission?',
            'a7' => 'Styliiiish applies a 50% marketplace service fee. Sellers enter their net price, and the final price shown to buyers includes the service fee.',
            'q8' => 'When do sellers receive their payment?',
            'a8' => 'Seller payouts are processed after successful delivery, according to Styliiiish verification and payout procedures.',

            'q9' => 'Are all products available in multiple sizes?',
            'a9' => 'Each product is available in one ready size. If another size is requested, the item will be made to order.',
            'q10' => 'Can I cancel my order after placing it?',
            'a10' => 'Orders can only be canceled before preparation or tailoring begins. Please contact support as soon as possible after placing your order.',

            'q11' => 'Can I rent dresses online?',
            'a11' => 'No. Dress rental services are available exclusively at our physical branch and are not processed through the website.',

            'q12' => 'How can I contact Styliiiish support?',
            'a12' => 'You can contact us anytime at email@styliiiish.com.',

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
    $canonicalPath = $localePrefix . '/faq';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/faq">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/faq">
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
        .hero { padding: 30px 0 14px; }
        .hero-box { background: linear-gradient(160deg,#ffffff 0%,#fff4f5 100%); border: 1px solid var(--line); border-radius: 18px; padding: 24px; box-shadow: 0 10px 30px rgba(23,39,59,.07); }
        .badge { display: inline-flex; align-items: center; background: #ffeef0; color: var(--primary); border-radius: 999px; padding: 7px 12px; font-size: 13px; font-weight: 700; margin-bottom: 10px; }
        .hero h1 { margin: 0 0 8px; font-size: clamp(28px,4vw,42px); line-height: 1.2; }
        .hero p { margin: 0; color: var(--muted); max-width: 920px; }
        .section { padding: 8px 0 22px; }
        .faq-group { margin-bottom: 14px; }
        .faq-title { margin: 0 0 8px; font-size: 20px; }
        .faq-item { background: #fff; border: 1px solid var(--line); border-radius: 12px; margin-bottom: 8px; overflow: hidden; box-shadow: 0 8px 20px rgba(23,39,59,.04); }
        .faq-btn { all: unset; display: flex; width: 100%; cursor: pointer; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 16px; font-weight: 700; color: var(--secondary); }
        .faq-btn:hover { background: #fff7f8; }
        .faq-icon { width: 26px; height: 26px; border-radius: 999px; border: 1px solid rgba(213,21,34,.25); display: inline-flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; line-height: 1; transition: transform .25s ease; }
        .faq-item.open .faq-icon { transform: rotate(45deg); }
        .faq-content { max-height: 0; overflow: hidden; transition: max-height .26s ease; border-top: 1px solid transparent; }
        .faq-item.open .faq-content { border-top-color: var(--line); }
        .faq-content p { margin: 0; padding: 12px 16px 14px; color: var(--muted); }
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
        @media (max-width:980px) { .main-header-inner { grid-template-columns: 1fr; padding: 12px 0; } .brand, .main-nav, .header-tools { justify-content: center; text-align: center; } .footer-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width:640px) { .hero { padding-top: 20px; } .hero-box { border-radius: 14px; padding: 14px; } .faq-btn { padding: 12px 14px; } .faq-content p { padding: 10px 14px 12px; } .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; } .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; } .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; } }
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
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="faq-group">
            <h2 class="faq-title">{{ $t('section_shipping') }}</h2>
            <article class="faq-item open"><button class="faq-btn" type="button"><span>{{ $t('q1') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a1') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q2') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a2') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q3') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a3') }}</p></div></article>
        </div>

        <div class="faq-group">
            <h2 class="faq-title">{{ $t('section_returns') }}</h2>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q4') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a4') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q5') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a5') }}</p></div></article>
        </div>

        <div class="faq-group">
            <h2 class="faq-title">{{ $t('section_marketplace') }}</h2>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q6') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a6') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q7') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a7') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q8') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a8') }}</p></div></article>
        </div>

        <div class="faq-group">
            <h2 class="faq-title">{{ $t('section_orders') }}</h2>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q9') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a9') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q10') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a10') }}</p></div></article>
        </div>

        <div class="faq-group">
            <h2 class="faq-title">{{ $t('section_rental') }}</h2>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q11') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a11') }}</p></div></article>
        </div>

        <div class="faq-group">
            <h2 class="faq-title">{{ $t('section_support') }}</h2>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q12') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a12') }}</p></div></article>
        </div>
    </div>
</section>

@include('partials.shared-home-footer')

<script>
    (function () {
        const items = Array.from(document.querySelectorAll('.faq-item'));
        const openItem = (item) => {
            const content = item.querySelector('.faq-content');
            item.classList.add('open');
            content.style.maxHeight = content.scrollHeight + 'px';
        };
        const closeItem = (item) => {
            const content = item.querySelector('.faq-content');
            item.classList.remove('open');
            content.style.maxHeight = '0px';
        };

        items.forEach((item) => {
            const btn = item.querySelector('.faq-btn');
            const content = item.querySelector('.faq-content');
            if (item.classList.contains('open')) {
                content.style.maxHeight = content.scrollHeight + 'px';
            }
            btn.addEventListener('click', () => {
                const isOpen = item.classList.contains('open');
                items.forEach(closeItem);
                if (!isOpen) openItem(item);
            });
        });

        window.addEventListener('resize', () => {
            items.filter(item => item.classList.contains('open')).forEach((item) => {
                const content = item.querySelector('.faq-content');
                content.style.maxHeight = content.scrollHeight + 'px';
            });
        });
    })();
</script>
</body>
</html>

