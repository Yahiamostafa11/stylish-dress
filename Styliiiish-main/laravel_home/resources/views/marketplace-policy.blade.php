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
            'page_title' => 'سياسة الماركت بليس | ستايلش',
            'meta_desc' => 'توضح سياسة الماركت بليس في Styliiiish آلية البيع، الرسوم، المدفوعات، الشحن، الإرجاع، ومسؤوليات البائع والمشتري.',
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
            'hero_badge' => 'سياسات البيع',
            'hero_title' => 'سياسة الماركت بليس',
            'hero_desc' => 'يوفّر Styliiiish Marketplace منصة تمكّن الأفراد من عرض وبيع فساتينهم للمشترين. توضح هذه السياسة آلية البيع، المدفوعات، الرسوم، التسليم، والمسؤوليات على المنصة.',
            'crumb_home' => 'الرئيسية',
            'crumb_current' => 'سياسة الماركت بليس',
            's1_t' => '1) دور Styliiiish',
            's1_p1' => 'تعمل Styliiiish كوسيط للماركت بليس وليست المالك الأصلي للفساتين المدرجة من المستخدمين.',
            's1_p2' => 'تكون Styliiiish مسؤولة عن:',
            's1_l1' => 'توفير المنصة الإلكترونية لإدراج المنتجات',
            's1_l2' => 'معالجة مدفوعات العملاء',
            's1_l3' => 'تنسيق تأكيد الطلب والتسليم',
            's1_l4' => 'تسهيل التواصل والدعم',
            's1_p3' => 'يبقى البائع الفردي هو المالك للفستان المدرج حتى يتم بيعه.',
            's2_t' => '2) أهلية البائع وتسجيل الحساب',
            's2_p1' => 'لإجراء البيع عبر Styliiiish Marketplace، يجب على المستخدمين:',
            's2_l1' => 'إنشاء حساب ببريد إلكتروني صالح',
            's2_l2' => 'تقديم معلومات دقيقة وكاملة',
            's2_l3' => 'أن يكون العمر 18 سنة على الأقل',
            's2_p2' => 'تحتفظ Styliiiish بحق تعليق أو حذف حسابات البائعين المخالفة لقواعد أو سياسات المنصة.',
            's3_t' => '3) إدراج فستان للبيع',
            's3_p1' => 'عند إدراج فستان، يجب على البائع:',
            's3_l1' => 'تقديم عنوان ووصف دقيقين للمنتج',
            's3_l2' => 'توضيح حالة الفستان بوضوح (جديد / مستعمل / مستعمل بحالة ممتازة)',
            's3_l3' => 'رفع صور واضحة وصادقة للقطعة الفعلية',
            's3_l4' => 'تحديد السمات الصحيحة مثل المقاس واللون والوزن',
            's3_p2' => 'يمكن إزالة أي إدراج مضلل أو غير دقيق دون إشعار مسبق.',
            's4_t' => '4) التسعير ورسوم الماركت بليس',
            's4_p1' => 'يحدد البائع صافي السعر المطلوب للفستان.',
            's4_p2' => 'تطبّق Styliiiish رسوم خدمة ماركت بليس بنسبة 50%، وتشمل:',
            's4_l1' => 'تشغيل المنصة',
            's4_l2' => 'التسويق والترويج',
            's4_l3' => 'معالجة المدفوعات',
            's4_l4' => 'تنسيق الاستلام والتسليم',
            's4_p3' => 'بالتالي، يكون السعر النهائي المعروض للمشتري أعلى من صافي سعر البائع.',
            's5_t' => '5) مدفوعات البائعين',
            's5_p1' => 'بعد بيع الفستان المدرج:',
            's5_l1' => 'تؤكد Styliiiish الطلب',
            's5_l2' => 'تنظّم الاستلام والتسليم',
            's5_l3' => 'تُعالج تحصيل الدفع من المشتري',
            's5_p2' => 'يتم صرف مستحقات البائع بعد نجاح التسليم، وفق جداول الصرف وإجراءات التحقق المعتمدة لدى Styliiiish.',
            's6_t' => '6) الشحن والتوصيل',
            's6_p1' => 'تقوم Styliiiish بتنسيق الاستلام والتوصيل لطلبات الماركت بليس عبر شركاء شحن موثوقين.',
            's6_p2' => 'يجب على البائع:',
            's6_l1' => 'تسليم الفستان بالحالة المتفق عليها',
            's6_l2' => 'ضمان مطابقة القطعة لوصف الإدراج',
            's6_p3' => 'عدم الالتزام قد يؤدي إلى إلغاء الطلب أو تطبيق جزاءات.',
            's7_t' => '7) الإرجاع والاسترداد والنزاعات',
            's7_p1' => 'تخضع طلبات الماركت بليس لسياسة الاسترجاع والاستبدال الخاصة بـ Styliiiish.',
            's7_p2' => 'يجوز لـ Styliiiish:',
            's7_l1' => 'طلب فحص المنتج',
            's7_l2' => 'تنسيق الإرجاع بين المشتري والبائع',
            's7_l3' => 'حل النزاعات بطريقة عادلة وشفافة',
            's7_p3' => 'قرار Styliiiish في حالات النزاع نهائي.',
            's8_t' => '8) العناصر والسلوكيات المحظورة',
            's8_p1' => 'لا يجوز للبائعين إدراج:',
            's8_l1' => 'منتجات مقلدة أو مسروقة',
            's8_l2' => 'منتجات لا يملكونها',
            's8_l3' => 'محتوى يخالف القوانين أو قواعد المنصة',
            's8_p2' => 'أي مخالفة قد تؤدي إلى تعليق الحساب أو إزالته نهائيًا.',
            's9_t' => '9) حدود المسؤولية',
            's9_p1' => 'تعمل Styliiiish كمنصة وسيطة وليست مسؤولة عن تحريف البائع للمعلومات خارج نطاق إجراءات التحقق المتاحة، أو عن الأضرار غير المباشرة أو التبعية.',
            's9_p2' => 'تُحدَّد المسؤولية في الحدود التي يسمح بها القانون المعمول به.',
            's10_t' => '10) القانون الحاكم',
            's10_p' => 'تخضع سياسة الماركت بليس هذه لقوانين جمهورية مصر العربية.',
            's11_t' => '11) معلومات التواصل',
            's11_p' => 'لأي استفسارات متعلقة بالماركت بليس، يرجى التواصل عبر:',
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
            'page_title' => 'Marketplace Policy | Styliiiish',
            'meta_desc' => 'Marketplace Policy for Styliiiish: seller role, listing rules, pricing fees, payouts, shipping, returns, disputes, and legal responsibilities.',
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
            'hero_badge' => 'Seller Policy',
            'hero_title' => 'Marketplace Policy',
            'hero_desc' => 'Styliiiish Marketplace provides a platform that allows individual users to list and sell their own dresses to buyers. This Marketplace Policy explains how selling, payments, fees, delivery, and responsibilities work on the platform.',
            'crumb_home' => 'Home',
            'crumb_current' => 'Marketplace Policy',
            's1_t' => '1) Role of Styliiiish',
            's1_p1' => 'Styliiiish operates as a marketplace intermediary, not the original owner of user-listed dresses.',
            's1_p2' => 'Styliiiish is responsible for:',
            's1_l1' => 'Providing the online platform for listings',
            's1_l2' => 'Processing customer payments',
            's1_l3' => 'Coordinating order confirmation and delivery',
            's1_l4' => 'Facilitating communication and support',
            's1_p3' => 'The individual seller remains the owner of the listed dress until it is sold.',
            's2_t' => '2) Seller Eligibility & Account Registration',
            's2_p1' => 'To sell on Styliiiish Marketplace, users must:',
            's2_l1' => 'Create an account with a valid email address',
            's2_l2' => 'Provide accurate and complete information',
            's2_l3' => 'Be at least 18 years old',
            's2_p2' => 'Styliiiish reserves the right to suspend or remove seller accounts that violate platform rules or policies.',
            's3_t' => '3) Listing a Dress',
            's3_p1' => 'When listing a dress, sellers must:',
            's3_l1' => 'Provide accurate product titles and descriptions',
            's3_l2' => 'Clearly state the condition of the dress (New / Used / Very Good – Used)',
            's3_l3' => 'Upload clear and honest photos of the actual item',
            's3_l4' => 'Select correct attributes such as size, color, and weight',
            's3_p2' => 'Misleading or inaccurate listings may be removed without notice.',
            's4_t' => '4) Pricing & Marketplace Fees',
            's4_p1' => 'Sellers set their desired net price for their dress.',
            's4_p2' => 'Styliiiish applies a 50% marketplace service fee, which covers:',
            's4_l1' => 'Platform operation',
            's4_l2' => 'Marketing and promotion',
            's4_l3' => 'Payment processing',
            's4_l4' => 'Pickup and delivery coordination',
            's4_p3' => 'As a result, the final price displayed to buyers is higher than the seller’s net price.',
            's5_t' => '5) Payments to Sellers',
            's5_p1' => 'Once a listed dress is sold:',
            's5_l1' => 'Styliiiish confirms the order',
            's5_l2' => 'Arranges pickup and delivery',
            's5_l3' => 'Processes payment collection from the buyer',
            's5_p2' => 'Seller payouts are processed after successful delivery, according to Styliiiish payout schedules and verification procedures.',
            's6_t' => '6) Shipping & Delivery',
            's6_p1' => 'Styliiiish coordinates pickup and delivery for Marketplace orders through trusted courier partners.',
            's6_p2' => 'Sellers must:',
            's6_l1' => 'Hand over the dress in the agreed condition',
            's6_l2' => 'Ensure the item matches the listing description',
            's6_p3' => 'Failure to comply may result in order cancellation or penalties.',
            's7_t' => '7) Returns, Refunds & Disputes',
            's7_p1' => 'Marketplace orders are subject to Styliiiish’s Refund & Return Policy.',
            's7_p2' => 'Styliiiish may:',
            's7_l1' => 'Request item inspection',
            's7_l2' => 'Coordinate returns between buyer and seller',
            's7_l3' => 'Resolve disputes in a fair and transparent manner',
            's7_p3' => 'Styliiiish’s decision in dispute cases is final.',
            's8_t' => '8) Prohibited Items & Conduct',
            's8_p1' => 'Sellers may not list:',
            's8_l1' => 'Counterfeit or stolen items',
            's8_l2' => 'Items they do not own',
            's8_l3' => 'Content that violates laws or platform rules',
            's8_p2' => 'Any violation may result in account suspension or permanent removal.',
            's9_t' => '9) Limitation of Liability',
            's9_p1' => 'Styliiiish acts as an intermediary platform and is not responsible for seller misrepresentation beyond provided verification, or for indirect or consequential damages.',
            's9_p2' => 'Liability is limited as permitted by applicable law.',
            's10_t' => '10) Governing Law',
            's10_p' => 'This Marketplace Policy is governed by the laws of the Arab Republic of Egypt.',
            's11_t' => '11) Contact Information',
            's11_p' => 'For questions related to the Marketplace, please contact:',
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
    $canonicalPath = $localePrefix . '/marketplace-policy';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/marketplace-policy">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/marketplace-policy">
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
        .breadcrumb { color: var(--muted); font-size: 13px; margin-bottom: 8px; }
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
        <div class="breadcrumb"><a href="{{ $localePrefix }}">{{ $t('crumb_home') }}</a> › <strong>{{ $t('crumb_current') }}</strong></div>
        <span class="badge">{{ $t('hero_badge') }}</span>
        <h1>{{ $t('hero_title') }}</h1>
        <p>{{ $t('hero_desc') }}</p>
    </div>
</section>

<section class="section">
    <div class="container content-grid">
        <article class="card"><h2>{{ $t('s1_t') }}</h2><p>{{ $t('s1_p1') }}</p><p>{{ $t('s1_p2') }}</p><ul><li>{{ $t('s1_l1') }}</li><li>{{ $t('s1_l2') }}</li><li>{{ $t('s1_l3') }}</li><li>{{ $t('s1_l4') }}</li></ul><p>{{ $t('s1_p3') }}</p></article>
        <article class="card"><h2>{{ $t('s2_t') }}</h2><p>{{ $t('s2_p1') }}</p><ul><li>{{ $t('s2_l1') }}</li><li>{{ $t('s2_l2') }}</li><li>{{ $t('s2_l3') }}</li></ul><p>{{ $t('s2_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s3_t') }}</h2><p>{{ $t('s3_p1') }}</p><ul><li>{{ $t('s3_l1') }}</li><li>{{ $t('s3_l2') }}</li><li>{{ $t('s3_l3') }}</li><li>{{ $t('s3_l4') }}</li></ul><p>{{ $t('s3_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s4_t') }}</h2><p>{{ $t('s4_p1') }}</p><p>{{ $t('s4_p2') }}</p><ul><li>{{ $t('s4_l1') }}</li><li>{{ $t('s4_l2') }}</li><li>{{ $t('s4_l3') }}</li><li>{{ $t('s4_l4') }}</li></ul><p>{{ $t('s4_p3') }}</p></article>
        <article class="card"><h2>{{ $t('s5_t') }}</h2><p>{{ $t('s5_p1') }}</p><ul><li>{{ $t('s5_l1') }}</li><li>{{ $t('s5_l2') }}</li><li>{{ $t('s5_l3') }}</li></ul><p>{{ $t('s5_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s6_t') }}</h2><p>{{ $t('s6_p1') }}</p><p>{{ $t('s6_p2') }}</p><ul><li>{{ $t('s6_l1') }}</li><li>{{ $t('s6_l2') }}</li></ul><p>{{ $t('s6_p3') }}</p></article>
        <article class="card"><h2>{{ $t('s7_t') }}</h2><p>{{ $t('s7_p1') }}</p><p>{{ $t('s7_p2') }}</p><ul><li>{{ $t('s7_l1') }}</li><li>{{ $t('s7_l2') }}</li><li>{{ $t('s7_l3') }}</li></ul><p>{{ $t('s7_p3') }}</p></article>
        <article class="card"><h2>{{ $t('s8_t') }}</h2><p>{{ $t('s8_p1') }}</p><ul><li>{{ $t('s8_l1') }}</li><li>{{ $t('s8_l2') }}</li><li>{{ $t('s8_l3') }}</li></ul><p>{{ $t('s8_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s9_t') }}</h2><p>{{ $t('s9_p1') }}</p><p>{{ $t('s9_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s10_t') }}</h2><p>{{ $t('s10_p') }}</p></article>
        <article class="card"><h2>{{ $t('s11_t') }}</h2><p>{{ $t('s11_p') }}</p><p>📧 <a href="mailto:email@styliiiish.com">email@styliiiish.com</a></p><p>🌐 <a href="{{ $wpBaseUrl }}" target="_blank" rel="noopener">{{ $wpBaseUrl }}</a></p></article>
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

