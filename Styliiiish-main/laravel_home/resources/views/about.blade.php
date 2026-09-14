<!DOCTYPE html>
@php
    $currentLocale = $currentLocale ?? 'ar';
    $localePrefix = $localePrefix ?? '/ar';
    $isEnglish = $currentLocale === 'en';
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? (config('styliiiish.wp_public_url') ?: request()->getSchemeAndHttpHost())), '/');

    $wpLogo = $wpBaseUrl . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpIcon = $wpBaseUrl . '/wp-content/uploads/2025/11/cropped-ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';

    $translations = [
        'ar' => [
            'page_title' => 'من نحن | ستايلش',
            'meta_desc' => 'تعرفي على Styliiiish Fashion House: خبرة في تصميم وبيع وتأجير الفساتين، وخدمات تفصيل احترافية وتجربة تسوق موثوقة داخل مصر.',
            'brand_tag' => 'لأن كل امرأة تستحق أن تتألق',
            'nav_home' => 'الرئيسية',
            'nav_shop' => 'المتجر',
            'nav_blog' => 'المدونة',
            'nav_marketplace' => 'الماركت بليس',
            'nav_sell' => 'بيعي فستانك',
            'nav_contact' => 'تواصل معنا',
            'nav_about' => 'من نحن',
            'lang_switch' => 'تبديل اللغة',
            'hero_badge' => '✨ أناقة تثقين بها',
            'hero_title' => 'عن Styliiiish Fashion House',
            'hero_desc' => 'مرحبًا بكِ في Styliiiish حيث تلتقي الأناقة بالحِرفية. نصمّم قطعًا تبرز الثقة والذوق وتحوّل كل مناسبة إلى لحظة لا تُنسى.',
            'our_story_title' => 'قصتنا',
            'our_story_p1' => 'منذ أكثر من 5 سنوات، تعمل Styliiiish Fashion House على إعادة تعريف الأناقة وتمكين المرأة من خلال الموضة.',
            'our_story_p2' => 'وبخبرة تتجاوز 11 عامًا في عالم الأزياء، نقدم تصاميم عالية الجودة تناسب مختلف الأذواق والمقاسات والمناسبات.',
            'our_story_p3' => 'من فساتين الزفاف والسهرة والخطوبة إلى الإطلالات الرسمية والتخرج، نختار كل تفصيلة بعناية.',
            'online_title' => 'ماذا نقدم عبر الموقع؟',
            'online_1' => 'فساتين جاهزة متاحة للشراء مباشرة أونلاين',
            'online_2' => 'تنفيذ تصميمات حسب الطلب عند الحاجة لمقاسات خاصة',
            'online_3' => 'ماركت بليس منظم لعرض وبيع فساتين الأفراد عبر Styliiiish',
            'online_4' => 'شفافية كاملة، مدفوعات آمنة، وتنسيق موثوق للشحن والتسليم',
            'market_title' => 'Styliiiish Marketplace',
            'market_desc' => 'نوفر منصة تتيح للأفراد بيع فساتينهم لجمهور واسع داخل مصر مع تجربة موثوقة.',
            'market_1' => 'معالجة مدفوعات آمنة',
            'market_2' => 'تأكيد الطلبات ومتابعتها',
            'market_3' => 'تنسيق الاستلام والتسليم',
            'market_4' => 'دعم العملاء وحل النزاعات',
            'market_note' => 'مسؤولية البائع: كل بائع مسؤول عن دقة بيانات المنتج وملكيته وحالته، بينما تضمن Styliiiish تجربة بيع آمنة ومنظمة.',
            'learn_sell' => 'تعرفي على البيع في الماركت بليس',
            'market_policy' => 'سياسة الماركت بليس',
            'offline_title' => 'خدماتنا في الفرع (أوفلاين)',
            'offline_desc' => 'إلى جانب منصتنا الإلكترونية، نوفر في فرعنا بمدينة نصر خدمات حصرية داخل الأتيليه.',
            'offline_1' => 'تأجير فساتين (يشمل first-wear)',
            'offline_2' => 'تفصيل وتعديل حسب المقاس',
            'offline_3' => 'تشكيلات جاهزة فاخرة',
            'offline_note' => 'مهم: خدمات التأجير والتفصيل داخل الفرع فقط، ولا يتم حجزها عبر الموقع.',
            'excellence_title' => 'مصنوعة بإتقان',
            'excellence_desc' => 'كل فستان في Styliiiish يتم تنفيذه بعناية ودقة باستخدام خامات عالية الجودة.',
            'ex_1' => 'خامات فاخرة من دبي',
            'ex_2' => 'مقاسات شاملة',
            'ex_3' => 'مصممون داخل الأتيليه',
            'ex_4' => 'تفصيل خلال 6–7 أيام',
            'why_title' => 'لماذا Styliiiish؟',
            'why_1' => 'تصميمات مميزة لكل مناسبة',
            'why_2' => 'تفصيل حسب المقاس',
            'why_3' => 'حِرفية وخبرة عالية',
            'why_4' => 'شحن موثوق داخل مصر',
            'why_5' => 'حلول أزياء تناسب كل القوام',
            'visit_title' => 'زوري Styliiiish Fashion House',
            'branch_label' => 'موقع الفرع',
            'branch_name' => 'Styliiiish Fashion House – Tailoring & Ready-Made Branch',
            'address_1' => '1 شارع نبيل خليل، متفرع من حسنين هيكل، متفرع من عباس العقاد،',
            'address_2' => 'خلف مطعم جاد، عمارة الحلبي للأقمشة، بجوار سوق الذهب،',
            'address_3' => 'أمام محلات الأنصاري – مدينة نصر، القاهرة، مصر',
            'hours_label' => 'ساعات العمل',
            'hours_1' => 'يوميًا: 11:00 صباحًا – 7:00 مساءً',
            'hours_2' => 'بدون إجازات طوال الأسبوع',
            'hours_3' => 'يرجى حجز موعد قبل الزيارة',
            'contact_label' => 'وسائل التواصل',
            'open_maps' => 'فتح الموقع على خرائط جوجل',
            'promise_title' => 'وعدنا لكِ',
            'promise_desc' => 'بخبرة 11 عامًا، نواصل تقديم تصميمات وتفصيلات راقية، وبيع وخدمات ماركت بليس موثوقة تساعد كل امرأة على التألق بثقة.',
            'promise_line' => 'Styliiiish Fashion House — أناقة، ثقة، وأسلوب لا يبهت.',
            'cta_contact' => 'تواصل معنا',
            'cta_terms' => 'الشروط والأحكام',
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
            'page_title' => 'About Styliiiish | Elegant Dresses & Custom Designs',
            'meta_desc' => 'Discover Styliiiish Fashion House: elegant ready-to-wear dresses, custom tailoring, trusted marketplace, and premium fashion services in Egypt.',
            'brand_tag' => 'Because every woman deserves to shine',
            'nav_home' => 'Home',
            'nav_shop' => 'Shop',
            'nav_blog' => 'Blog',
            'nav_marketplace' => 'Marketplace',
            'nav_sell' => 'Sell Your Dress',
            'nav_contact' => 'Contact Us',
            'nav_about' => 'About Us',
            'lang_switch' => 'Language Switcher',
            'hero_badge' => '✨ Elegance you can trust',
            'hero_title' => 'About Styliiiish Fashion House',
            'hero_desc' => 'Welcome to Styliiiish, where elegance meets craftsmanship. We create timeless dresses that celebrate confidence, grace, and individuality.',
            'our_story_title' => 'Our Story',
            'our_story_p1' => 'For over 5 years, Styliiiish Fashion House has been redefining elegance and empowering women through fashion.',
            'our_story_p2' => 'With more than 11 years of experience in the fashion industry, we design and deliver high-quality dresses for every style and occasion.',
            'our_story_p3' => 'From bridal and evening looks to engagement, graduation, and formal wear, every detail is crafted with care.',
            'online_title' => 'What We Offer Online',
            'online_1' => 'Ready-to-wear dresses available for direct online purchase',
            'online_2' => 'Made-to-order designs when special sizes are required',
            'online_3' => 'A curated marketplace where individuals can list and sell dresses',
            'online_4' => 'Transparent process, secure payments, and reliable delivery coordination',
            'market_title' => 'Styliiiish Marketplace',
            'market_desc' => 'Our marketplace helps individuals sell dresses to a wide audience across Egypt through a trusted process.',
            'market_1' => 'Secure payment processing',
            'market_2' => 'Order confirmation and tracking',
            'market_3' => 'Pickup and delivery coordination',
            'market_4' => 'Customer support and dispute resolution',
            'market_note' => 'Seller responsibility: Each seller is responsible for item accuracy, ownership, and condition, while Styliiiish ensures a smooth marketplace experience.',
            'learn_sell' => 'Learn how to sell on Marketplace',
            'market_policy' => 'Marketplace Policy',
            'offline_title' => 'Offline Services at Our Fashion House',
            'offline_desc' => 'In addition to our online platform, our Nasr City branch offers exclusive in-store services.',
            'offline_1' => 'Dress rental services (including first-wear rentals)',
            'offline_2' => 'Custom tailoring and made-to-measure designs',
            'offline_3' => 'Ready-made luxury collections',
            'offline_note' => 'Please note: Dress rental and in-store tailoring are available only at our physical branch and are not booked through the website.',
            'excellence_title' => 'Crafted with Excellence',
            'excellence_desc' => 'Every Styliiiish dress is made with care, precision, and premium materials.',
            'ex_1' => 'Premium fabrics from Dubai',
            'ex_2' => 'Inclusive sizing',
            'ex_3' => 'In-house designers',
            'ex_4' => 'Tailoring in 6–7 days',
            'why_title' => 'Why Choose Styliiiish?',
            'why_1' => 'Unique dresses for every occasion',
            'why_2' => 'Made-to-measure designs',
            'why_3' => 'Expert craftsmanship',
            'why_4' => 'Reliable delivery across Egypt',
            'why_5' => 'Fashion for every body type',
            'visit_title' => 'Visit Styliiiish Fashion House',
            'branch_label' => 'Branch Location',
            'branch_name' => 'Styliiiish Fashion House – Tailoring & Ready-Made Branch',
            'address_1' => '1 Nabil Khalil St., off Hassanein Heikal St., off Abbas El Akkad St.,',
            'address_2' => 'Behind Gad Restaurant, El Helaly Fabrics Building, next to the Gold Market,',
            'address_3' => 'Opposite Al Ansary Stores – Nasr City, Cairo, Egypt',
            'hours_label' => 'Working Hours',
            'hours_1' => 'Open daily: 11:00 AM – 7:00 PM',
            'hours_2' => 'No days off — open all week',
            'hours_3' => 'Please book an appointment before visiting',
            'contact_label' => 'Contact',
            'open_maps' => 'Open location on Google Maps',
            'promise_title' => 'Our Promise',
            'promise_desc' => 'With 11 years of experience, Styliiiish continues to lead in designing, tailoring, selling, and facilitating access to elegant dresses.',
            'promise_line' => 'Styliiiish Fashion House — Elegance, Confidence, and Timeless Style.',
            'cta_contact' => 'Contact Us',
            'cta_terms' => 'Terms & Conditions',
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

    $canonicalPath = $localePrefix . '/about-us';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/about-us">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/about-us">
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

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Styliiiish Fashion House',
            'url' => $wpBaseUrl . $canonicalPath,
            'logo' => $wpLogo,
            'description' => $t('meta_desc'),
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'telephone' => '+20 105 087 4255',
                'contactType' => 'customer service',
                'areaServed' => 'EG'
            ]],
            'sameAs' => [
                $wpBaseUrl . '/blog/',
                $wpBaseUrl . '/about-us/',
                $wpBaseUrl . '/contact-us/'
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    <style>
        :root {
            --wf-main-rgb: 213, 21, 34;
            --wf-main-color: rgb(var(--wf-main-rgb));
            --wf-secondary-color: #17273B;
            --bg: #f6f7fb;
            --card: #ffffff;
            --text: #17273B;
            --muted: #5a6678;
            --line: rgba(189, 189, 189, 0.4);
            --primary: var(--wf-main-color);
            --secondary: var(--wf-secondary-color);
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, Arial, sans-serif; background: var(--bg); color: var(--text); line-height: 1.65; }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1180px, 92%); margin: 0 auto; }

        .main-header { background: #fff; border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 40; box-shadow: 0 8px 24px rgba(23, 39, 59, 0.06); }
        .main-header-inner { min-height: 84px; display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 16px; }
        .brand { display: flex; flex-direction: column; gap: 2px; }
        .brand-logo { height: 40px; width: auto; max-width: min(220px, 38vw); object-fit: contain; }
        .brand-tag { color: var(--muted); font-size: 12px; font-weight: 600; }

        .main-nav { display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap; background: #f9fbff; border: 1px solid var(--line); border-radius: 12px; padding: 6px; }
        .main-nav a { color: var(--secondary); font-size: 14px; font-weight: 700; padding: 8px 12px; border-radius: 8px; transition: .2s ease; }
        .main-nav a:hover, .main-nav a.active { color: var(--primary); background: #fff4f5; }

        .lang-switch { position: relative; display: inline-grid; grid-template-columns: 1fr 1fr; align-items: center; direction: ltr; width: 110px; height: 34px; background: rgba(23, 39, 59, 0.1); border: 1px solid rgba(23, 39, 59, 0.18); border-radius: 999px; padding: 3px; overflow: hidden; }
        .lang-indicator { position: absolute; top: 3px; width: calc(50% - 3px); height: calc(100% - 6px); background: #fff; border-radius: 999px; transition: .25s ease; z-index: 1; }
        .lang-switch.is-ar .lang-indicator { left: 3px; }
        .lang-switch.is-en .lang-indicator { right: 3px; }
        .lang-switch a { position: relative; z-index: 2; text-align: center; font-size: 12px; font-weight: 800; color: var(--secondary); opacity: .75; padding: 5px 0; }
        .lang-switch a.active { opacity: 1; }

        .hero { padding: 34px 0 18px; }
        .hero-box { background: linear-gradient(160deg, #ffffff 0%, #fff4f5 100%); border: 1px solid var(--line); border-radius: 18px; padding: 26px; box-shadow: 0 10px 30px rgba(23, 39, 59, 0.07); }
        .badge { display: inline-flex; align-items: center; background: #ffeef0; color: var(--primary); border-radius: 999px; padding: 7px 12px; font-size: 13px; font-weight: 700; margin-bottom: 12px; }
        .hero h1 { margin: 0 0 10px; font-size: clamp(28px, 4vw, 44px); line-height: 1.2; }
        .hero p { margin: 0; color: var(--muted); max-width: 880px; }

        .section { padding: 8px 0 24px; }
        .grid-two { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 18px; box-shadow: 0 8px 20px rgba(23, 39, 59, 0.05); }
        .card h2 { margin: 0 0 8px; font-size: 24px; }
        .card p { margin: 0 0 10px; color: var(--muted); }

        .list { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
        .list li { background: #fbfcff; border: 1px solid var(--line); border-radius: 10px; padding: 10px 12px; font-weight: 600; color: var(--secondary); }

        .note { background: #fff6f7; border: 1px solid rgba(var(--wf-main-rgb), .2); color: var(--secondary); border-radius: 12px; padding: 11px 12px; font-size: 14px; }

        .cta-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .btn { border: 1px solid var(--line); border-radius: 10px; min-height: 42px; padding: 10px 14px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; transition: .2s ease; }
        .btn-primary { color: #fff; background: var(--primary); border-color: var(--primary); }
        .btn-light { color: var(--secondary); background: #fff; }
        .btn-light:hover { border-color: var(--primary); color: var(--primary); }

        .site-footer { margin-top: 8px; background: #0f1a2a; color: #fff; border-top: 4px solid var(--primary); }
        .footer-grid { display: grid; grid-template-columns: 1.2fr 1fr 1fr 1fr; gap: 18px; padding: 34px 0 22px; }
        .footer-brand, .footer-col { background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 14px; padding: 16px; }
        .footer-brand-logo { width: auto; height: 34px; max-width: min(220px, 100%); object-fit: contain; display: block; margin-bottom: 12px; filter: brightness(0) invert(1); opacity: 0.96; }
        .footer-brand h4, .footer-col h5 { margin: 0 0 10px; font-size: 18px; color: #fff; }
        .footer-brand p { margin: 0 0 10px; color: #b8c2d1; font-size: 14px; }
        .footer-links { list-style: none; margin: 0; padding: 0; display: grid; gap: 7px; }
        .footer-links a { color: #b8c2d1; font-size: 14px; transition: .2s ease; }
        .footer-links a:hover { color: #fff; }
        .footer-brand .footer-contact-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .footer-brand .footer-contact-row a { color: #fff; background: rgba(213, 21, 34, 0.16); border: 1px solid rgba(213, 21, 34, 0.35); border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 700; }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.14); padding: 12px 0 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; color: #b8c2d1; font-size: 13px; }
        .footer-mini-nav { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; padding-bottom: 18px; }
        .footer-mini-nav a { color: #b8c2d1; font-size: 13px; }

        @media (max-width: 980px) {
            .main-header-inner { grid-template-columns: 1fr; padding: 12px 0; }
            .brand, .main-nav, .header-tools { justify-content: center; text-align: center; }
            .grid-two { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .hero { padding-top: 20px; }
            .hero-box, .card { border-radius: 14px; padding: 14px; }
            .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; }
            .footer-brand, .footer-col { padding: 12px; }
            .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; }
            .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; }
        }
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
    <div class="container grid-two">
        <article class="card">
            <h2>{{ $t('our_story_title') }}</h2>
            <p>{{ $t('our_story_p1') }}</p>
            <p>{{ $t('our_story_p2') }}</p>
            <p>{{ $t('our_story_p3') }}</p>
        </article>
        <article class="card">
            <h2>{{ $t('online_title') }}</h2>
            <ul class="list">
                <li>{{ $t('online_1') }}</li>
                <li>{{ $t('online_2') }}</li>
                <li>{{ $t('online_3') }}</li>
                <li>{{ $t('online_4') }}</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="container grid-two">
        <article class="card">
            <h2>{{ $t('market_title') }}</h2>
            <p>{{ $t('market_desc') }}</p>
            <ul class="list">
                <li>{{ $t('market_1') }}</li>
                <li>{{ $t('market_2') }}</li>
                <li>{{ $t('market_3') }}</li>
                <li>{{ $t('market_4') }}</li>
            </ul>
            <p class="note" style="margin-top:10px;">{{ $t('market_note') }}</p>
            <div class="cta-row">
                <a class="btn btn-light" href="{{ $wpBaseUrl }}/my-dresses/" target="_blank" rel="noopener">{{ $t('learn_sell') }}</a>
                <a class="btn btn-primary" href="{{ $localePrefix }}/marketplace-policy">{{ $t('market_policy') }}</a>
            </div>
        </article>

        <article class="card">
            <h2>{{ $t('offline_title') }}</h2>
            <p>{{ $t('offline_desc') }}</p>
            <ul class="list">
                <li>{{ $t('offline_1') }}</li>
                <li>{{ $t('offline_2') }}</li>
                <li>{{ $t('offline_3') }}</li>
            </ul>
            <p class="note" style="margin-top:10px;">{{ $t('offline_note') }}</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="container grid-two">
        <article class="card">
            <h2>{{ $t('excellence_title') }}</h2>
            <p>{{ $t('excellence_desc') }}</p>
            <ul class="list">
                <li>{{ $t('ex_1') }}</li>
                <li>{{ $t('ex_2') }}</li>
                <li>{{ $t('ex_3') }}</li>
                <li>{{ $t('ex_4') }}</li>
            </ul>
        </article>

        <article class="card">
            <h2>{{ $t('why_title') }}</h2>
            <ul class="list">
                <li>{{ $t('why_1') }}</li>
                <li>{{ $t('why_2') }}</li>
                <li>{{ $t('why_3') }}</li>
                <li>{{ $t('why_4') }}</li>
                <li>{{ $t('why_5') }}</li>
            </ul>
        </article>
    </div>
</section>

<section class="section">
    <div class="container grid-two">
        <article class="card">
            <h2>{{ $t('visit_title') }}</h2>
            <p><strong>{{ $t('branch_label') }}:</strong> {{ $t('branch_name') }}</p>
            <p>{{ $t('address_1') }}</p>
            <p>{{ $t('address_2') }}</p>
            <p>{{ $t('address_3') }}</p>

            <p style="margin-top:12px;"><strong>{{ $t('hours_label') }}:</strong></p>
            <ul class="list">
                <li>{{ $t('hours_1') }}</li>
                <li>{{ $t('hours_2') }}</li>
                <li>{{ $t('hours_3') }}</li>
            </ul>

            <p style="margin-top:12px;"><strong>{{ $t('contact_label') }}:</strong></p>
            <p>📞 <a href="tel:+201050874255">+20 105 087 4255</a></p>
            <p>📧 <a href="mailto:email@styliiiish.com">email@styliiiish.com</a></p>
            <div class="cta-row">
                <a class="btn btn-light" href="https://maps.app.goo.gl/MCdcFEcFoR4tEjpT8" target="_blank" rel="noopener">{{ $t('open_maps') }}</a>
            </div>
        </article>

        <article class="card">
            <h2>{{ $t('promise_title') }}</h2>
            <p>{{ $t('promise_desc') }}</p>
            <p class="note">{{ $t('promise_line') }}</p>
            <div class="cta-row">
                <a class="btn btn-primary" href="{{ $localePrefix }}/contact-us">{{ $t('cta_contact') }}</a>
                <a class="btn btn-light" href="{{ $localePrefix }}/terms-conditions">{{ $t('cta_terms') }}</a>
            </div>
        </article>
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

