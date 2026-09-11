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
            'page_title' => 'الشروط والأحكام | ستايلش',
            'meta_desc' => 'الشروط والأحكام الخاصة باستخدام موقع Styliiiish والشراء من المتجر، بما يشمل الطلبات والأسعار والشحن والمرتجعات وسياسات الماركت بليس.',
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
            'hero_badge' => 'قانوني',
            'hero_title' => 'الشروط والأحكام',
            'hero_desc' => 'تنظّم هذه الشروط استخدامك لموقع Styliiiish وعمليات الشراء من متجرنا الإلكتروني. باستخدام الموقع أو إجراء طلب، فإنك توافق على الالتزام بهذه الشروط.',
            's1_t' => '1) قبول الشروط',
            's1_p' => 'باستخدامك هذا الموقع، فإنك تقر بأنك قرأت وفهمت ووافقت على هذه الشروط والأحكام بالكامل.',
            's2_t' => '2) الأهلية',
            's2_p' => 'يجب أن يكون عمرك 18 عامًا على الأقل لإجراء طلب. إذا كنت أقل من 18 عامًا، فيجب استخدام الموقع بمشاركة وموافقة ولي الأمر أو الوصي القانوني.',
            's3_t' => '3) معلومات المنتجات',
            's3_p' => 'يبذل Styliiiish كل جهد لعرض أوصاف وصور وتفاصيل المنتجات بدقة. قد تحدث فروقات طفيفة في اللون أو المظهر بسبب التصوير أو إعدادات الشاشة.',
            's4_t' => '4) الأسعار والتوفر',
            's4_p' => 'جميع الأسعار بالجنيه المصري (EGP) ما لم يُذكر خلاف ذلك. المنتجات تخضع للتوفر، ونحتفظ بحق تعديل الأسعار أو تحديث المنتجات أو إيقافها دون إشعار مسبق.',
            's5_t' => '5) الطلبات وقبول الطلب',
            's5_p' => 'بعد إتمام الطلب، ستصلك رسالة تأكيد عبر البريد الإلكتروني. هذا التأكيد لا يعني قبولًا نهائيًا للطلب. يتم قبول الطلب فقط بعد المعالجة والشحن.',
            's6_t' => '6) الشحن والاسترجاع واسترداد الأموال',
            's6_p1' => 'تخضع شروط الشحن والتسليم لسياسة الشحن والتوصيل.',
            's6_p2' => 'تخضع المرتجعات والاستبدال والاسترداد لسياسة الاسترجاع والاستبدال.',
            's7_t' => '7) شروط الماركت بليس',
            's7_p1' => 'يوفر Styliiiish منصة ماركت بليس تتيح للأفراد عرض وبيع فساتينهم. يتحمل البائع مسؤولية دقة بيانات المنتج وملكيته وحالته.',
            's7_p2' => 'تعمل Styliiiish كمنصة وسيطة وتسهّل المدفوعات وتنسيق التسليم ودعم العملاء. وتُعد سياسة الماركت بليس جزءًا لا يتجزأ من هذه الشروط.',
            's8_t' => '8) حسابات المستخدمين',
            's8_p' => 'إذا قمت بإنشاء حساب، فأنت مسؤول عن سرية بيانات الدخول وجميع الأنشطة التي تتم عبر حسابك.',
            's9_t' => '9) الملكية الفكرية',
            's9_p' => 'جميع محتويات الموقع، بما في ذلك النصوص والصور والشعارات والتصميمات والرسومات، مملوكة لـ Styliiiish ومحمية بقوانين الملكية الفكرية. يُحظر الاستخدام أو النسخ دون تصريح.',
            's10_t' => '10) حدود المسؤولية',
            's10_p' => 'لا يتحمل Styliiiish مسؤولية الأضرار غير المباشرة أو العرضية أو التبعية الناتجة عن استخدام الموقع أو شراء المنتجات، إلا بالقدر الذي يفرضه القانون المعمول به.',
            's11_t' => '11) القانون الحاكم',
            's11_p' => 'تخضع هذه الشروط والأحكام وتُفسر وفقًا لقوانين جمهورية مصر العربية.',
            's12_t' => '12) خدمات التأجير الأوفلاين',
            's12_p' => 'خدمات تأجير الفساتين متاحة حصريًا داخل الفرع الفعلي ولا تُحجز عبر الموقع. سياسات البيع والشحن والاسترجاع الإلكترونية لا تنطبق على خدمات التأجير داخل الفرع.',
            's13_t' => '13) توفر المنتجات والطلبات الخاصة',
            's13_p' => 'بعض المنتجات تُنفذ حسب الطلب وفق توفر المقاس. وقد تختلف مدة التسليم، وسيتم إبلاغ العميل بذلك قبل تأكيد الطلب.',
            's14_t' => '14) معلومات التواصل',
            's14_p' => 'لأي استفسار بخصوص الشروط والأحكام، تواصل معنا عبر:',
            'email_label' => 'البريد الإلكتروني',
            'web_label' => 'الموقع',
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
            'page_title' => 'Terms & Conditions | Styliiiish',
            'meta_desc' => 'Terms & Conditions for using Styliiiish and purchasing from our online store, including pricing, orders, marketplace terms, and liability.',
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
            'hero_badge' => 'Legal',
            'hero_title' => 'Terms & Conditions',
            'hero_desc' => 'These Terms & Conditions govern your use of the Styliiiish website and your purchases from our online store. By accessing the site or placing an order, you agree to be bound by these terms.',
            's1_t' => '1) Acceptance of Terms',
            's1_p' => 'By using this website, you acknowledge that you have read, understood, and agree to these Terms & Conditions in full.',
            's2_t' => '2) Eligibility',
            's2_p' => 'You must be at least 18 years old to place an order. If you are under 18, you may use the website only with the involvement and consent of a parent or legal guardian.',
            's3_t' => '3) Product Information',
            's3_p' => 'Styliiiish offers dresses for sale online. We strive to display product descriptions, images, and details accurately; however, minor variations may occur due to photography or screen settings.',
            's4_t' => '4) Pricing & Availability',
            's4_p' => 'All prices are listed in Egyptian Pounds (EGP) unless stated otherwise. Products are subject to availability, and we reserve the right to update prices, modify listings, or discontinue items without prior notice.',
            's5_t' => '5) Orders & Order Acceptance',
            's5_p' => 'After placing an order, you will receive an order confirmation email. This confirmation does not constitute acceptance. An order is accepted only after processing and dispatch.',
            's6_t' => '6) Shipping, Returns & Refunds',
            's6_p1' => 'Shipping and delivery terms are outlined in our Shipping & Delivery Policy.',
            's6_p2' => 'Returns, exchanges, and refunds are governed by our Refund & Return Policy.',
            's7_t' => '7) Marketplace Terms',
            's7_p1' => 'Styliiiish provides a marketplace platform for individuals to list and sell dresses. Sellers are responsible for the accuracy, ownership, and condition of listed items.',
            's7_p2' => 'Styliiiish acts as an intermediary platform facilitating payments, delivery coordination, and customer support. The Marketplace Policy forms an integral part of these Terms & Conditions.',
            's8_t' => '8) User Accounts',
            's8_p' => 'If you create an account, you are responsible for maintaining the confidentiality of your login credentials and all activities under your account.',
            's9_t' => '9) Intellectual Property',
            's9_p' => 'All website content, including text, images, logos, designs, and graphics, is the property of Styliiiish and protected by applicable intellectual property laws. Unauthorized use is prohibited.',
            's10_t' => '10) Limitation of Liability',
            's10_p' => 'Styliiiish is not liable for any indirect, incidental, or consequential damages arising from website use or product purchases, except where required by applicable law.',
            's11_t' => '11) Governing Law',
            's11_p' => 'These Terms & Conditions are governed by and interpreted in accordance with the laws of the Arab Republic of Egypt.',
            's12_t' => '12) Offline Rental Services',
            's12_p' => 'Dress rental services are available exclusively at our physical branch and are not processed or booked through the website. Online policies for sales, shipping, and refunds do not apply to in-store rental services.',
            's13_t' => '13) Product Availability & Custom Orders',
            's13_p' => 'Some products may be made to order based on size availability. Delivery timelines may vary accordingly and will be communicated before order confirmation.',
            's14_t' => '14) Contact Information',
            's14_p' => 'If you have any questions regarding these Terms & Conditions, please contact us at:',
            'email_label' => 'Email',
            'web_label' => 'Website',
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
    $canonicalPath = $localePrefix . '/terms-conditions';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/terms-conditions">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/terms-conditions">
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
        .hero { padding: 34px 0 16px; }
        .hero-box { background: linear-gradient(160deg,#ffffff 0%,#fff4f5 100%); border: 1px solid var(--line); border-radius: 18px; padding: 24px; box-shadow: 0 10px 30px rgba(23,39,59,.07); }
        .badge { display: inline-flex; align-items: center; background: #ffeef0; color: var(--primary); border-radius: 999px; padding: 7px 12px; font-size: 13px; font-weight: 700; margin-bottom: 10px; }
        .hero h1 { margin: 0 0 8px; font-size: clamp(28px,4vw,42px); line-height: 1.2; }
        .hero p { margin: 0; color: var(--muted); max-width: 880px; }
        .section { padding: 8px 0 22px; }
        .content-grid { display: grid; gap: 12px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 16px; box-shadow: 0 8px 20px rgba(23,39,59,.05); }
        .card h2 { margin: 0 0 8px; font-size: 22px; }
        .card p { margin: 0 0 8px; color: var(--muted); }
        .notice { border: 1px solid rgba(var(--wf-main-rgb), .25); background: #fff6f7; border-radius: 12px; padding: 10px 12px; }
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
        @media (max-width:640px) { .hero { padding-top: 20px; } .hero-box, .card { border-radius: 14px; padding: 14px; } .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; } .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; } .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; } }
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
    <div class="container content-grid">
        <article class="card"><h2>{{ $t('s1_t') }}</h2><p>{{ $t('s1_p') }}</p></article>
        <article class="card"><h2>{{ $t('s2_t') }}</h2><p>{{ $t('s2_p') }}</p></article>
        <article class="card"><h2>{{ $t('s3_t') }}</h2><p>{{ $t('s3_p') }}</p></article>
        <article class="card"><h2>{{ $t('s4_t') }}</h2><p>{{ $t('s4_p') }}</p></article>
        <article class="card"><h2>{{ $t('s5_t') }}</h2><p>{{ $t('s5_p') }}</p></article>
        <article class="card"><h2>{{ $t('s6_t') }}</h2><p>{{ $t('s6_p1') }}</p><p>{{ $t('s6_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s7_t') }}</h2><p>{{ $t('s7_p1') }}</p><p>{{ $t('s7_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s8_t') }}</h2><p>{{ $t('s8_p') }}</p></article>
        <article class="card"><h2>{{ $t('s9_t') }}</h2><p>{{ $t('s9_p') }}</p></article>
        <article class="card"><h2>{{ $t('s10_t') }}</h2><p>{{ $t('s10_p') }}</p></article>
        <article class="card"><h2>{{ $t('s11_t') }}</h2><p>{{ $t('s11_p') }}</p></article>
        <article class="card"><h2>{{ $t('s12_t') }}</h2><p>{{ $t('s12_p') }}</p></article>
        <article class="card"><h2>{{ $t('s13_t') }}</h2><p>{{ $t('s13_p') }}</p></article>
        <article class="card"><h2>{{ $t('s14_t') }}</h2><p>{{ $t('s14_p') }}</p><p>📧 <a href="mailto:email@styliiiish.com">email@styliiiish.com</a></p><p>🌐 <a href="{{ $wpBaseUrl }}" target="_blank" rel="noopener">{{ $wpBaseUrl }}</a></p></article>
        <div class="notice"><strong>{{ $t('nav_terms') }}</strong> — {{ $t('hero_desc') }}</div>
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

