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
            'page_title' => 'سياسة الشحن والتوصيل | ستايلش',
            'meta_desc' => 'تعرفي على سياسة الشحن والتوصيل في Styliiiish: مناطق الشحن، أوقات التجهيز، مدة التسليم، التتبع، الرسوم، والتأخيرات.',
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
            'hero_badge' => 'سياسات الشحن',
            'hero_title' => 'سياسة الشحن والتوصيل',
            'hero_desc' => 'في Styliiiish نسعى لتوصيل طلبك بأسرع وقت وبأفضل سلاسة ممكنة. توضح هذه السياسة مناطق الشحن، أوقات التجهيز، مدة التسليم، ورسوم وتفاصيل الشحن.',

            's1_t' => '1) مناطق الشحن',
            's1_p1' => 'نقدم حاليًا خدمات الشحن داخل مصر فقط عبر شركاء شحن موثوقين. وتشمل التغطية المدن الرئيسية ومعظم المحافظات.',
            's1_p2' => 'إذا تعذر التوصيل إلى عنوان محدد، سنتواصل معك لتوفير حل بديل أو إلغاء الطلب مع رد المبلغ عند الاقتضاء.',

            's2_t' => '2) وقت تجهيز الطلب',
            's2_h1' => 'المنتجات الجاهزة للشحن',
            's2_p1' => 'يتم تجهيز المنتجات الجاهزة وتسليمها لشركة الشحن خلال 1–2 يوم عمل بعد تأكيد الطلب.',
            's2_h2' => 'المنتجات المصنوعة حسب الطلب',
            's2_p2' => 'إذا كان المنتج يُنفذ حسب الطلب بسبب توفر المقاس، فإن التجهيز يستغرق 6–7 أيام عمل قبل الشحن. وقد تختلف المدة في مواسم الذروة والعروض أو الإجازات الرسمية.',

            's3_t' => '3) مدة التوصيل',
            's3_p1' => 'بعد تسليم الطلب لشركة الشحن، يستغرق التوصيل عادةً 1–3 أيام عمل، حسب موقع التسليم وخدمة الشحن.',
            's3_p2' => 'قد تختلف المدة بسبب عوامل خارجية مثل الأحوال الجوية، الإجازات الرسمية، أو تأخيرات شركات الشحن.',

            's4_t' => '4) المدة الإجمالية التقديرية للتسليم',
            's4_p1' => 'لتوضيح تجربة العميل، المدة الإجمالية التقديرية تكون كالتالي:',
            's4_l1' => 'المنتجات الجاهزة للشحن: تقريبًا 2–4 أيام عمل',
            's4_l2' => 'المنتجات المصنوعة حسب الطلب: تقريبًا 7–10 أيام عمل',
            's4_p2' => 'يتم عرض التوقيت التقديري في صفحة المنتج أو إبلاغ العميل به قبل تأكيد الطلب.',

            's5_t' => '5) رسوم الشحن',
            's5_p1' => 'يتم احتساب رسوم الشحن وإظهارها عند الدفع قبل إتمام الطلب. تختلف الرسوم حسب موقع التسليم وطريقة الشحن المختارة.',
            's5_p2' => 'أي ضرائب أو رسوم خدمة متعلقة بالشحن تُعرض بوضوح أثناء إتمام الطلب.',

            's6_t' => '6) التأخيرات والاستثناءات',
            's6_p1' => 'رغم التزامنا بالمواعيد المعلنة، قد تحدث تأخيرات أحيانًا بسبب ظروف خارجة عن إرادتنا.',
            's6_p2' => 'لا تتحمل Styliiiish مسؤولية التأخيرات الناتجة عن شركات الشحن، أو حالات القوة القاهرة، أو بيانات التسليم غير الصحيحة المقدمة من العميل.',
            's6_p3' => 'في حال وجود تأخير كبير، سيتواصل فريق الدعم معك لتحديث الحالة.',

            's7_t' => '7) تتبع الطلب',
            's7_p' => 'بعد شحن طلبك، ستصلك رسالة تتضمن رقم تتبع أو تحديث حالة التوصيل عبر البريد الإلكتروني أو الرسائل النصية، مما يتيح لك متابعة الشحنة مع شركة الشحن.',

            's8_t' => '8) التواصل والدعم',
            's8_p' => 'لأي استفسار بخصوص الشحن أو لتحديث بيانات التسليم، تواصل معنا عبر:',

            'faq_title' => 'أسئلة شائعة حول الشحن',
            'q1' => 'كم يستغرق شحن المنتجات الجاهزة للشحن؟',
            'a1' => 'يتم تجهيز المنتجات الجاهزة خلال 1–2 يوم عمل وتوصيلها خلال 1–3 أيام عمل. المدة الإجمالية التقديرية: 2–4 أيام عمل.',
            'q2' => 'كم تستغرق مدة التوصيل للفساتين المصنوعة حسب الطلب؟',
            'a2' => 'الفساتين المصنوعة حسب الطلب تحتاج 6–7 أيام عمل للتجهيز قبل الشحن، ثم 1–3 أيام عمل للتوصيل. المدة الإجمالية: 7–10 أيام عمل.',
            'q3' => 'هل يوجد شحن خارج مصر؟',
            'a3' => 'لا. تقدم Styliiiish حاليًا خدمات الشحن داخل مصر فقط عبر شركاء شحن موثوقين.',
            'q4' => 'كيف يمكنني تتبع طلبي؟',
            'a4' => 'بعد شحن الطلب، ستصلك بيانات التتبع أو تحديث حالة التوصيل عبر البريد الإلكتروني أو الرسائل النصية.',
            'q5' => 'ماذا يحدث إذا تأخر طلبي؟',
            'a5' => 'في حال التأخير الكبير، سيتواصل فريق الدعم معك بتحديثات الحالة. التأخيرات الناتجة عن الشحن أو الطقس أو الإجازات الرسمية خارج نطاق سيطرتنا.',

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
            'page_title' => 'Shipping & Delivery Policy | Styliiiish',
            'meta_desc' => 'Read Styliiiish Shipping & Delivery Policy covering shipping locations, preparation times, delivery timelines, tracking, fees, and delays.',
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
            'hero_badge' => 'Shipping Policy',
            'hero_title' => 'Shipping & Delivery Policy',
            'hero_desc' => 'At Styliiiish, we strive to deliver your order as quickly and smoothly as possible. This policy explains our shipping locations, preparation times, delivery timelines, and related details.',

            's1_t' => '1) Shipping Locations',
            's1_p1' => 'We currently offer shipping services across Egypt only through trusted courier partners. Coverage includes major cities and most governorates.',
            's1_p2' => 'If delivery to a specific address is unavailable, we will contact you to arrange an alternative solution or cancel the order with a refund where applicable.',

            's2_t' => '2) Order Preparation Time',
            's2_h1' => 'Ready-to-Ship Items',
            's2_p1' => 'Ready items are prepared and handed over to the courier within 1–2 business days after order confirmation.',
            's2_h2' => 'Made-to-Order Items',
            's2_p2' => 'If a product is made to order due to size availability, preparation takes 6–7 business days before shipment. Preparation times may vary during peak seasons, promotions, or public holidays.',

            's3_t' => '3) Delivery Timeframes',
            's3_p1' => 'After the order is handed over to the courier, delivery typically takes 1–3 business days, depending on the delivery location and courier service.',
            's3_p2' => 'Delivery times may vary due to external factors such as weather conditions, public holidays, or courier delays.',

            's4_t' => '4) Estimated Total Delivery Time',
            's4_p1' => 'For customer clarity, the estimated total delivery time is as follows:',
            's4_l1' => 'Ready-to-Ship Items: Approximately 2–4 business days',
            's4_l2' => 'Made-to-Order Items: Approximately 7–10 business days',
            's4_p2' => 'Estimated timelines are displayed on the product page or communicated to the customer before order confirmation.',

            's5_t' => '5) Shipping Fees',
            's5_p1' => 'Shipping fees are calculated and displayed at checkout before completing the order. Fees vary based on delivery location and the selected shipping method.',
            's5_p2' => 'Any applicable taxes or service charges related to shipping will be clearly shown during checkout.',

            's6_t' => '6) Delays & Exceptions',
            's6_p1' => 'While we aim to meet the stated delivery timelines, delays may occasionally occur due to circumstances beyond our control.',
            's6_p2' => 'Styliiiish is not responsible for delays caused by courier services, force majeure events, or incorrect delivery information provided by the customer.',
            's6_p3' => 'In case of significant delays, our support team will contact you with updates.',

            's7_t' => '7) Order Tracking',
            's7_p' => 'Once your order has been shipped, you will receive a tracking number or delivery status update via email or SMS, allowing you to track your shipment with the courier partner.',

            's8_t' => '8) Contact & Support',
            's8_p' => 'For any questions regarding shipping or to update delivery information, please contact us at:',

            'faq_title' => 'Frequently Asked Questions About Shipping',
            'q1' => 'How long does shipping take for ready-to-ship items?',
            'a1' => 'Ready-to-ship items are prepared within 1–2 business days and delivered within 1–3 business days. The estimated total delivery time is 2–4 business days.',
            'q2' => 'How long does delivery take for made-to-order dresses?',
            'a2' => 'Made-to-order dresses require 6–7 business days for preparation before shipment, followed by 1–3 business days for delivery. Total estimated delivery time is 7–10 business days.',
            'q3' => 'Do you ship outside Egypt?',
            'a3' => 'No. Styliiiish currently offers shipping services within Egypt only through trusted courier partners.',
            'q4' => 'How can I track my order?',
            'a4' => 'Once your order is shipped, you will receive a tracking number or delivery update via email or SMS.',
            'q5' => 'What happens if my order is delayed?',
            'a5' => 'In case of significant delays, our support team will contact you with updates. Delays caused by couriers, weather conditions, or public holidays are beyond our control.',

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
    $canonicalPath = $localePrefix . '/shipping-delivery-policy';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/shipping-delivery-policy">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/shipping-delivery-policy">
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
        .section { padding: 8px 0 18px; }
        .content-grid { display: grid; gap: 12px; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 16px; box-shadow: 0 8px 20px rgba(23,39,59,.05); }
        .card h2 { margin: 0 0 8px; font-size: 22px; }
        .card h3 { margin: 10px 0 6px; font-size: 17px; color: var(--secondary); }
        .card p { margin: 0 0 8px; color: var(--muted); }
        .card ul { margin: 6px 0 2px; padding-inline-start: 20px; color: var(--muted); }
        .faq-wrap { margin-top: 8px; }
        .faq-title { margin: 0 0 10px; font-size: 23px; }
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
        @media (max-width:640px) { .hero { padding-top: 20px; } .hero-box, .card { border-radius: 14px; padding: 14px; } .faq-btn { padding: 12px 14px; } .faq-content p { padding: 10px 14px 12px; } .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; } .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; } .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; } }
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
        <article class="card"><h2>{{ $t('s1_t') }}</h2><p>{{ $t('s1_p1') }}</p><p>{{ $t('s1_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s2_t') }}</h2><h3>{{ $t('s2_h1') }}</h3><p>{{ $t('s2_p1') }}</p><h3>{{ $t('s2_h2') }}</h3><p>{{ $t('s2_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s3_t') }}</h2><p>{{ $t('s3_p1') }}</p><p>{{ $t('s3_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s4_t') }}</h2><p>{{ $t('s4_p1') }}</p><ul><li>{{ $t('s4_l1') }}</li><li>{{ $t('s4_l2') }}</li></ul><p>{{ $t('s4_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s5_t') }}</h2><p>{{ $t('s5_p1') }}</p><p>{{ $t('s5_p2') }}</p></article>
        <article class="card"><h2>{{ $t('s6_t') }}</h2><p>{{ $t('s6_p1') }}</p><p>{{ $t('s6_p2') }}</p><p>{{ $t('s6_p3') }}</p></article>
        <article class="card"><h2>{{ $t('s7_t') }}</h2><p>{{ $t('s7_p') }}</p></article>
        <article class="card"><h2>{{ $t('s8_t') }}</h2><p>{{ $t('s8_p') }}</p><p>📧 <a href="mailto:email@styliiiish.com">email@styliiiish.com</a></p><p>🌐 <a href="{{ $wpBaseUrl }}" target="_blank" rel="noopener">{{ $wpBaseUrl }}</a></p></article>

        <div class="faq-wrap">
            <h2 class="faq-title">{{ $t('faq_title') }}</h2>
            <article class="faq-item open"><button class="faq-btn" type="button"><span>{{ $t('q1') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a1') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q2') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a2') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q3') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a3') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q4') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a4') }}</p></div></article>
            <article class="faq-item"><button class="faq-btn" type="button"><span>{{ $t('q5') }}</span><span class="faq-icon">+</span></button><div class="faq-content"><p>{{ $t('a5') }}</p></div></article>
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

