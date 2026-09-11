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
            'page_title' => 'سياسة الاسترجاع والاستبدال | ستايلش',
            'meta_desc' => 'توضح سياسة الاسترجاع والاستبدال في Styliiiish شروط الإرجاع، الاستبدال، رد المبالغ، وتكاليف الشحن.',
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
            'hero_badge' => 'سياسات الطلبات',
            'hero_title' => 'سياسة الاسترجاع والاستبدال',
            'hero_desc' => 'في Styliiiish نحرص على رضاك الكامل عن طلبك. توضح هذه السياسة متى وكيف يمكنك الاسترجاع أو الاستبدال وآلية رد المبالغ.',
            's1_t' => '1) أهلية الاسترجاع والاستبدال',
            's1_p1' => 'يمكنك طلب استرجاع أو استبدال في الحالات التالية:',
            's1_l1' => 'وصول المنتج تالفًا أو به عيب.',
            's1_l2' => 'استلام منتج أو مقاس أو لون مختلف عن الطلب.',
            's1_l3' => 'عدم مطابقة المنتج للوصف أو الصور على الموقع.',
            's1_p2' => 'وللتأهل للاسترجاع أو الاستبدال، يجب أن يكون المنتج:',
            's1_l4' => 'غير مستخدم وغير مُرتدى وبنفس الحالة عند الاستلام.',
            's1_l5' => 'مع جميع البطاقات والملصقات والتغليف الأصلي.',
            's1_l6' => 'تقديم الطلب خلال 3 أيام من تاريخ التسليم.',
            's2_t' => '2) المنتجات غير القابلة للاسترجاع',
            's2_p1' => 'عادةً لا يمكن استرجاع أو استرداد المنتجات التالية:',
            's2_l1' => 'الفساتين المستخدمة أو المرتداة (إلا في وجود عيب تصنيع واضح يتم الإبلاغ عنه فورًا عند الاستلام).',
            's2_l2' => 'المنتجات المباعة كـ "بيع نهائي" أو المعلّمة بأنها غير قابلة للإرجاع.',
            's2_l3' => 'المنتجات المخصصة أو المعدلة بناءً على طلب العميل.',
            's2_l4' => 'طلبات التأجير بعد بدء فترة التأجير.',
            's2_p2' => 'تحتفظ Styliiiish بحق فحص المنتجات المرتجعة ورفض الإرجاع إذا لم تتحقق الشروط.',
            's3_t' => '3) كيفية طلب الاسترجاع أو الاستبدال',
            's3_p1' => 'لطلب الاسترجاع أو الاستبدال، يرجى اتباع الخطوات التالية:',
            's3_l1' => 'التواصل عبر email@styliiiish.com مع رقم الطلب والتفاصيل.',
            's3_l2' => 'إرفاق صور واضحة للمنتج (وللمشكلة إن وُجدت).',
            's3_l3' => 'سيقوم الفريق بمراجعة الطلب والرد بالموافقة أو التعليمات أو طلب توضيحات.',
            's3_p2' => 'يرجى عدم إرسال أي منتج قبل تأكيد مسبق من فريق الدعم.',
            's4_t' => '4) رد المبالغ (Refunds)',
            's4_p1' => 'بعد استلام وفحص المنتج المرتجع، سنقوم بإبلاغك بقبول أو رفض طلب رد المبلغ. في حال الموافقة:',
            's4_l1' => 'يتم رد المبلغ إلى وسيلة الدفع الأصلية قدر الإمكان.',
            's4_l2' => 'يشمل المبلغ المسترد سعر المنتج فقط ما لم يُتفق كتابةً على غير ذلك.',
            's4_l3' => 'قد تختلف مدة المعالجة حسب البنك أو مزود الدفع (عادة 3–4 أيام عمل).',
            's4_p2' => 'في بعض الحالات قد نوفّر رصيد متجر أو استبدال بدلًا من استرداد نقدي وفق الحالة وتفضيل العميل.',
            's5_t' => '5) تكاليف الشحن',
            's5_p1' => 'رسوم الشحن غير قابلة للاسترداد غالبًا، باستثناء الحالات التالية:',
            's5_l1' => 'استلام منتج خاطئ.',
            's5_l2' => 'وصول المنتج تالفًا أو معيبًا.',
            's5_p2' => 'في الحالات الأخرى، قد يتحمل العميل تكلفة شحن الإرجاع، وسيتم توضيح التفاصيل أثناء معالجة الطلب.',
            's6_t' => '6) التواصل والدعم',
            's6_p' => 'لأي استفسارات أو مساعدة بخصوص الاسترجاع أو رد المبالغ، تواصل معنا عبر:',
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
            'page_title' => 'Refund & Return Policy | Styliiiish',
            'meta_desc' => 'Refund & Return Policy for Styliiiish: eligibility, non-returnable items, return request process, refunds, and shipping costs.',
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
            'hero_badge' => 'Order Policies',
            'hero_title' => 'Refund & Return Policy',
            'hero_desc' => 'At Styliiiish, we want you to be completely satisfied with your order. This policy explains when and how you can return or exchange items, and how refunds are processed.',
            's1_t' => '1) Eligibility for Returns & Exchanges',
            's1_p1' => 'You may request a return or exchange in the following cases:',
            's1_l1' => 'The item arrived damaged or defective.',
            's1_l2' => 'You received the wrong product, size, or color.',
            's1_l3' => 'The item does not match the description or photos on the website.',
            's1_p2' => 'To be eligible for a return or exchange, items must:',
            's1_l4' => 'Be unused, unworn, and in the same condition as received.',
            's1_l5' => 'Have all original tags, labels, and packaging.',
            's1_l6' => 'Be requested within 3 days of the delivery date.',
            's2_t' => '2) Non-Returnable Items',
            's2_p1' => 'The following items are generally not eligible for return or refund:',
            's2_l1' => 'Used or worn dresses (unless there is a clear manufacturing defect reported immediately upon delivery).',
            's2_l2' => 'Items purchased as final sale or marked as non-returnable.',
            's2_l3' => 'Customized or altered items based on customer request.',
            's2_l4' => 'Rental orders after the rental period has started.',
            's2_p2' => 'Styliiiish reserves the right to inspect returned items and decline the return if conditions are not met.',
            's3_t' => '3) How to Request a Return or Exchange',
            's3_p1' => 'To request a return or exchange, please follow these steps:',
            's3_l1' => 'Contact us at email@styliiiish.com with your order number and details.',
            's3_l2' => 'Attach clear photos of the item (and the issue, if damaged or defective).',
            's3_l3' => 'Our team will review your request and respond with approval, instructions, or clarification.',
            's3_p2' => 'Please do not send any items back without prior confirmation from our support team.',
            's4_t' => '4) Refunds',
            's4_p1' => 'Once we receive and inspect your returned item, we will notify you of the approval or rejection of your refund. If approved:',
            's4_l1' => 'Refunds will be issued to the original payment method whenever possible.',
            's4_l2' => 'The refund amount will cover the product price only, unless otherwise agreed in writing.',
            's4_l3' => 'Processing time may vary depending on your bank or payment provider (usually 3–4 business days).',
            's4_p2' => 'In some cases, we may offer store credit or an exchange instead of a cash refund, based on the situation and your preference.',
            's5_t' => '5) Shipping Costs',
            's5_p1' => 'Shipping fees are generally non-refundable, except in cases where:',
            's5_l1' => 'You received the wrong item.',
            's5_l2' => 'The product is defective or damaged upon arrival.',
            's5_p2' => 'In other cases, the customer may be responsible for the cost of return shipping. Details will be clearly communicated during the return request process.',
            's6_t' => '6) Contact & Support',
            's6_p' => 'If you have any questions or need help with a return or refund, please contact us:',
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
    $canonicalPath = $localePrefix . '/refund-return-policy';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/refund-return-policy">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/refund-return-policy">
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
        .content-grid { display: grid; gap: 12px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 16px; box-shadow: 0 8px 20px rgba(23,39,59,.05); }
        .card h2 { margin: 0 0 8px; font-size: 22px; }
        .card p { margin: 0 0 8px; color: var(--muted); }
        .card ul { margin: 6px 0 2px; padding-inline-start: 20px; color: var(--muted); }
        .card li { margin-bottom: 6px; }
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
        <article class="card"><h2>{{ $t('s1_t') }}</h2><p>{{ $t('s1_p1') }}</p><ul><li>{{ $t('s1_l1') }}</li><li>{{ $t('s1_l2') }}</li><li>{{ $t('s1_l3') }}</li></ul><p>{{ $t('s1_p2') }}</p><ul><li>{{ $t('s1_l4') }}</li><li>{{ $t('s1_l5') }}</li><li>{{ $t('s1_l6') }}</li></ul></article>
        <article class="card"><h2>{{ $t('s2_t') }}</h2><p>{{ $t('s2_p1') }}</p><ul><li>{{ $t('s2_l1') }}</li><li>{{ $t('s2_l2') }}</li><li>{{ $t('s2_l3') }}</li><li>{{ $t('s2_l4') }}</li></ul><p>{{ $t('s2_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s3_t') }}</h2><p>{{ $t('s3_p1') }}</p><ul><li>{{ $t('s3_l1') }}</li><li>{{ $t('s3_l2') }}</li><li>{{ $t('s3_l3') }}</li></ul><p>{{ $t('s3_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s4_t') }}</h2><p>{{ $t('s4_p1') }}</p><ul><li>{{ $t('s4_l1') }}</li><li>{{ $t('s4_l2') }}</li><li>{{ $t('s4_l3') }}</li></ul><p>{{ $t('s4_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s5_t') }}</h2><p>{{ $t('s5_p1') }}</p><ul><li>{{ $t('s5_l1') }}</li><li>{{ $t('s5_l2') }}</li></ul><p>{{ $t('s5_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s6_t') }}</h2><p>{{ $t('s6_p') }}</p><p>📧 <a href="mailto:email@styliiiish.com">email@styliiiish.com</a></p><p>🌐 <a href="{{ $wpBaseUrl }}" target="_blank" rel="noopener">{{ $wpBaseUrl }}</a></p></article>
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

