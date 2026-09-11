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
            'page_title' => 'سياسة ملفات الارتباط | ستايلش',
            'meta_desc' => 'توضح سياسة ملفات الارتباط لدى Styliiiish كيفية استخدام الكوكيز، Google Consent Mode v2، وإدارة التفضيلات بطريقة متوافقة مع GDPR.',
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
            'hero_badge' => '🍪 Cookie Policy',
            'hero_title' => 'سياسة ملفات الارتباط',
            'hero_desc' => 'توضح هذه السياسة كيف تستخدم Styliiiish ملفات الارتباط والتقنيات المشابهة على styliiiish.com. تساعد الكوكيز في تشغيل الميزات الأساسية (السلة والدفع)، تحسين الأداء، ودعم التحليلات والإعلانات بعد الموافقة فقط.',
            'gdpr' => '✅ متوافق مع GDPR',
            'consent_mode' => '✅ Google Consent Mode v2',
            'cookieyes' => '✅ متوافق مع CookieYes',
            'updated' => 'آخر تحديث',
            'updated_value' => 'ديسمبر 2025',
            'on_page' => '📌 في هذه الصفحة',

            'toc1' => 'ما هي ملفات الارتباط؟',
            'toc2' => 'لماذا نستخدم ملفات الارتباط',
            'toc3' => 'أنواع ملفات الارتباط المستخدمة',
            'toc4' => 'Google Consent Mode',
            'toc5' => 'إدارة التفضيلات',
            'toc6' => 'ملفات ارتباط الطرف الثالث',
            'toc7' => 'تحديثات السياسة',
            'toc8' => 'التواصل',

            's1_t' => '1) ما هي ملفات الارتباط؟',
            's1_p' => 'ملفات الارتباط هي ملفات نصية صغيرة تُخزّن على جهازك عند زيارة موقع إلكتروني. تساعد المواقع على تذكّر الإجراءات والتفضيلات (مثل تسجيل الدخول، عناصر السلة، أو إعدادات اللغة).',

            's2_t' => '2) لماذا نستخدم ملفات الارتباط',
            's2_h1' => 'تشغيل وظائف الموقع الأساسية',
            's2_p1' => 'تسجيل الدخول، الأمان، السلة والدفع، وحفظ تفضيلات الموافقة.',
            's2_h2' => 'الأداء وتجربة الاستخدام',
            's2_p2' => 'تحسين السرعة والاستقرار وتجربة المستخدم عبر الأجهزة.',
            's2_h3' => 'التحليلات (بعد الموافقة)',
            's2_p3' => 'فهم كيفية استخدام الزوار للموقع لتحسين المحتوى والتنقل.',
            's2_h4' => 'الإعلانات (بعد الموافقة)',
            's2_p4' => 'قياس أداء الإعلانات وتمكين الميزات التسويقية ذات الصلة.',

            's3_t' => '3) أنواع ملفات الارتباط المستخدمة',
            's3_n_title' => '🔒 ضرورية (مفعّلة دائمًا)',
            's3_n_desc' => 'مطلوبة لتشغيل الموقع بشكل صحيح وحمايته. هذه لا تحتاج موافقة.',
            's3_n_1' => 'PHPSESSID — إدارة الجلسة للميزات الأساسية.',
            's3_n_2' => 'woocommerce_items_in_cart — تتبع عناصر السلة خلال الجلسة.',
            's3_n_3' => 'woocommerce_cart_hash — اكتشاف تغيّرات محتوى السلة.',
            's3_n_4' => 'wp_woocommerce_session_* — حفظ معرف جلسة WooCommerce.',
            's3_n_5' => 'cookieyes-consent — حفظ اختياراتك الخاصة بالكوكيز للزيارات القادمة.',
            's3_n_6' => 'wp_consent_preferences — حفظ قرارات الموافقة عبر WP Consent API.',
            's3_a_title' => '📊 التحليلات (تحتاج موافقة)',
            's3_a_desc' => 'تساعدنا على فهم استخدام الموقع وتحسين UX. تُفعل فقط بعد الموافقة.',
            's3_a_1' => '_ga / _ga_# — كوكيز Google Analytics لقياس الاستخدام.',
            's3_a_2' => 'tk_r3d / tk_or / tk_lr — مؤشرات إحالة واستخدام Jetpack/WooCommerce.',
            's3_m_title' => '📣 الإعلانات (تحتاج موافقة)',
            's3_m_desc' => 'تُستخدم لقياس الإعلانات ومنع التكرار وتمكين إعادة الاستهداف عند الاقتضاء. تُفعل فقط بعد الموافقة.',
            's3_m_1' => '_gcl_au / _gcl_ls — قياس التحويلات والأداء لإعلانات Google.',
            's3_m_2' => 'IDE / test_cookie — قياس إعلانات DoubleClick وفحص التوافق.',
            's3_note' => '⚠️ ملاحظة: قد تختلف أسماء الكوكيز ومددها حسب تحديثات WooCommerce أو CookieYes أو خدمات Google أو إعدادات الموقع.',

            's4_t' => '4) Google Consent Mode',
            's4_p1' => 'نستخدم Google Consent Mode v2 لضمان أن ملفات التحليلات والإعلانات تكون معطّلة افتراضيًا حتى تمنح الموافقة.',
            's4_p2' => 'قبل الموافقة، قد ترسل وسوم Google إشارات محدودة بدون تعريفات (cookieless) لقياس الأداء. بعد الموافقة، تعمل الوسوم وفق الأذونات التي تختارها.',

            's5_t' => '5) كيفية إدارة تفضيلات الكوكيز',
            's5_p1' => 'يمكنك تعديل اختيارات الكوكيز في أي وقت عبر لوحة تفضيلات الكوكيز المقدمة من CookieYes.',
            's5_p2' => 'يمكنك أيضًا التحكم في الكوكيز من إعدادات المتصفح، لكن تعطيل بعض الكوكيز قد يؤثر على وظائف الموقع.',

            's6_t' => '6) ملفات ارتباط الطرف الثالث',
            's6_p1' => 'يتم ضبط بعض الكوكيز بواسطة خدمات خارجية نستخدمها (مثل Google وJetpack).',
            's6_p2' => 'كل طرف ثالث مسؤول عن ممارسات البيانات الخاصة به وفق سياسة الخصوصية التابعة له.',
            's6_ref' => 'مرجع: سياسة خصوصية Google',

            's7_t' => '7) تحديثات هذه السياسة',
            's7_p' => 'قد نقوم بتحديث سياسة ملفات الارتباط لتعكس تغييرات قانونية أو تقنية أو تشغيلية. سيتم نشر التحديثات على هذه الصفحة مع تاريخ "آخر تحديث" الجديد.',

            's8_t' => '8) التواصل',
            's8_p1' => 'استفسارات الخصوصية وملفات الارتباط',
            's8_p2' => 'البريد الإلكتروني: privacy@styliiiish.com',
            's8_p3' => '© Styliiiish — سياسة ملفات الارتباط',

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
            'page_title' => 'Cookie Policy | Styliiiish',
            'meta_desc' => 'Learn how Styliiiish uses cookies and similar technologies, including GDPR-friendly practices, Google Consent Mode v2, and CookieYes controls.',
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
            'hero_badge' => '🍪 Cookie Policy',
            'hero_title' => 'Cookie Policy',
            'hero_desc' => 'This Cookie Policy explains how Styliiiish uses cookies and similar technologies on styliiiish.com. Cookies help us run core site features (cart, checkout), improve performance, and (only after consent) support analytics and advertising.',
            'gdpr' => '✅ GDPR-friendly',
            'consent_mode' => '✅ Google Consent Mode v2',
            'cookieyes' => '✅ CookieYes Compatible',
            'updated' => 'Last updated',
            'updated_value' => 'December 2025',
            'on_page' => '📌 On this page',

            'toc1' => 'What are cookies?',
            'toc2' => 'Why we use cookies',
            'toc3' => 'Types of cookies we use',
            'toc4' => 'Google Consent Mode',
            'toc5' => 'Manage preferences',
            'toc6' => 'Third-party cookies',
            'toc7' => 'Policy updates',
            'toc8' => 'Contact',

            's1_t' => '1) What are cookies?',
            's1_p' => 'Cookies are small text files stored on your device when you visit a website. They help websites remember your actions and preferences (like login, cart items, or language settings).',

            's2_t' => '2) Why we use cookies',
            's2_h1' => 'Core website functionality',
            's2_p1' => 'Login, security, cart and checkout, and saving your consent preferences.',
            's2_h2' => 'Performance & experience',
            's2_p2' => 'Improving speed, stability, and user experience across devices.',
            's2_h3' => 'Analytics (after consent)',
            's2_p3' => 'Understanding how visitors use the site to improve content and navigation.',
            's2_h4' => 'Advertising (after consent)',
            's2_p4' => 'Measuring ad performance and enabling relevant marketing features.',

            's3_t' => '3) Types of cookies we use',
            's3_n_title' => '🔒 Necessary (always active)',
            's3_n_desc' => 'Required to run the site properly and keep it secure. These do not require consent.',
            's3_n_1' => 'PHPSESSID — Session management for core site features.',
            's3_n_2' => 'woocommerce_items_in_cart — Tracks cart items during your session.',
            's3_n_3' => 'woocommerce_cart_hash — Detects changes to cart contents.',
            's3_n_4' => 'wp_woocommerce_session_* — Stores cart/session identifier for WooCommerce.',
            's3_n_5' => 'cookieyes-consent — Saves your cookie preferences for future visits.',
            's3_n_6' => 'wp_consent_preferences — Stores your consent choices via WP Consent API.',
            's3_a_title' => '📊 Analytics (consent required)',
            's3_a_desc' => 'Helps us understand site usage and improve UX. Activated only after consent.',
            's3_a_1' => '_ga / _ga_# — Google Analytics cookies for usage measurement.',
            's3_a_2' => 'tk_r3d / tk_or / tk_lr — Jetpack/WooCommerce referral & usage metrics.',
            's3_m_title' => '📣 Advertising (consent required)',
            's3_m_desc' => 'Used to measure ads, prevent repetition, and enable remarketing where applicable. Activated only after consent.',
            's3_m_1' => '_gcl_au / _gcl_ls — Google Ads conversion & performance measurement.',
            's3_m_2' => 'IDE / test_cookie — DoubleClick ads measurement & compatibility check.',
            's3_note' => '⚠️ Note: Cookie names and durations may vary depending on updates from WooCommerce, CookieYes, Google services, or your website configuration.',

            's4_t' => '4) Google Consent Mode',
            's4_p1' => 'We use Google Consent Mode v2 to ensure that analytics and advertising cookies are disabled by default until you give consent.',
            's4_p2' => 'Before consent, Google tags may send limited cookieless signals to measure performance without setting identifiers. After consent, tags can operate according to the permissions you choose.',

            's5_t' => '5) How to manage your cookie preferences',
            's5_p1' => 'You can change your cookie choices anytime using the cookie preferences panel provided by CookieYes.',
            's5_p2' => 'You can also control cookies through your browser settings, but disabling some cookies may impact site features.',

            's6_t' => '6) Third-party cookies',
            's6_p1' => 'Some cookies are set by third-party services we use (e.g., Google, Jetpack).',
            's6_p2' => 'Each third party is responsible for its own data practices under its privacy policy.',
            's6_ref' => 'Reference: Google Privacy Policy',

            's7_t' => '7) Updates to this policy',
            's7_p' => 'We may update this Cookie Policy to reflect legal, technical, or operational changes. Any updates will appear on this page with a revised Last updated date.',

            's8_t' => '8) Contact',
            's8_p1' => 'Privacy & Cookie Inquiries',
            's8_p2' => 'Email: privacy@styliiiish.com',
            's8_p3' => '© Styliiiish — Cookie Policy',

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
    $canonicalPath = $localePrefix . '/cookie-policy';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/cookie-policy">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/cookie-policy">
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
        html { scroll-behavior: smooth; }
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
        .hero p { margin: 0 0 10px; color: var(--muted); max-width: 920px; }
        .hero-flags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .hero-flag { border: 1px solid rgba(23,39,59,.13); background: #fff; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 700; color: var(--secondary); }
        .updated { margin-top: 8px; color: var(--muted); font-size: 13px; }

        .section { padding: 8px 0 22px; }
        .layout { display: grid; grid-template-columns: 1fr 320px; gap: 14px; align-items: start; }
        .content-grid { display: grid; gap: 12px; }
        .card { scroll-margin-top: 110px; background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 16px; box-shadow: 0 8px 20px rgba(23,39,59,.05); }
        .card h2 { margin: 0 0 8px; font-size: 22px; }
        .card h3 { margin: 10px 0 6px; font-size: 17px; color: var(--secondary); }
        .card p { margin: 0 0 8px; color: var(--muted); }
        .card ul { margin: 6px 0 2px; padding-inline-start: 20px; color: var(--muted); }
        .notice { border: 1px solid rgba(var(--wf-main-rgb), .25); background: #fff6f7; border-radius: 12px; padding: 10px 12px; color: #5a6678; }

        .toc { position: sticky; top: 100px; background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 14px; box-shadow: 0 8px 20px rgba(23,39,59,.05); }
        .toc h3 { margin: 0 0 10px; font-size: 18px; }
        .toc-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 7px; }
        .toc-list a { display: flex; align-items: center; justify-content: space-between; gap: 8px; border: 1px solid var(--line); border-radius: 10px; padding: 8px 10px; color: var(--secondary); font-size: 14px; transition: .2s ease; }
        .toc-list a:hover { border-color: rgba(var(--wf-main-rgb), .35); background: #fff7f8; }
        .toc-num { font-size: 12px; font-weight: 800; color: var(--primary); }

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

        @media (max-width:1080px) { .layout { grid-template-columns: 1fr; } .toc { position: static; } }
        @media (max-width:980px) { .main-header-inner { grid-template-columns: 1fr; padding: 12px 0; } .brand, .main-nav, .header-tools { justify-content: center; text-align: center; } .footer-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width:640px) { .hero { padding-top: 20px; } .hero-box, .card, .toc { border-radius: 14px; padding: 14px; } .footer-grid { grid-template-columns: 1fr; gap: 14px; padding: 22px 0 14px; } .footer-bottom { flex-direction: column; align-items: flex-start; gap: 6px; padding: 10px 0 14px; } .footer-mini-nav { justify-content: flex-start; overflow-x: auto; white-space: nowrap; scrollbar-width: none; padding-bottom: 12px; } }
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
        <div class="hero-flags"><span class="hero-flag">{{ $t('gdpr') }}</span><span class="hero-flag">{{ $t('consent_mode') }}</span><span class="hero-flag">{{ $t('cookieyes') }}</span></div>
        <div class="updated">{{ $t('updated') }}: {{ $t('updated_value') }}</div>
    </div>
</section>

<section class="section">
    <div class="container layout">
        <div class="content-grid">
            <article id="s1" class="card"><h2>{{ $t('s1_t') }}</h2><p>{{ $t('s1_p') }}</p></article>
            <article id="s2" class="card"><h2>{{ $t('s2_t') }}</h2><h3>{{ $t('s2_h1') }}</h3><p>{{ $t('s2_p1') }}</p><h3>{{ $t('s2_h2') }}</h3><p>{{ $t('s2_p2') }}</p><h3>{{ $t('s2_h3') }}</h3><p>{{ $t('s2_p3') }}</p><h3>{{ $t('s2_h4') }}</h3><p>{{ $t('s2_p4') }}</p></article>
            <article id="s3" class="card"><h2>{{ $t('s3_t') }}</h2><h3>{{ $t('s3_n_title') }}</h3><p>{{ $t('s3_n_desc') }}</p><ul><li>{{ $t('s3_n_1') }}</li><li>{{ $t('s3_n_2') }}</li><li>{{ $t('s3_n_3') }}</li><li>{{ $t('s3_n_4') }}</li><li>{{ $t('s3_n_5') }}</li><li>{{ $t('s3_n_6') }}</li></ul><h3>{{ $t('s3_a_title') }}</h3><p>{{ $t('s3_a_desc') }}</p><ul><li>{{ $t('s3_a_1') }}</li><li>{{ $t('s3_a_2') }}</li></ul><h3>{{ $t('s3_m_title') }}</h3><p>{{ $t('s3_m_desc') }}</p><ul><li>{{ $t('s3_m_1') }}</li><li>{{ $t('s3_m_2') }}</li></ul><div class="notice">{{ $t('s3_note') }}</div></article>
            <article id="s4" class="card"><h2>{{ $t('s4_t') }}</h2><p>{{ $t('s4_p1') }}</p><p>{{ $t('s4_p2') }}</p></article>
            <article id="s5" class="card"><h2>{{ $t('s5_t') }}</h2><p>{{ $t('s5_p1') }}</p><p>{{ $t('s5_p2') }}</p></article>
            <article id="s6" class="card"><h2>{{ $t('s6_t') }}</h2><p>{{ $t('s6_p1') }}</p><p>{{ $t('s6_p2') }}</p><p><a href="https://policies.google.com/privacy" target="_blank" rel="noopener">{{ $t('s6_ref') }}</a></p></article>
            <article id="s7" class="card"><h2>{{ $t('s7_t') }}</h2><p>{{ $t('s7_p') }}</p></article>
            <article id="s8" class="card"><h2>{{ $t('s8_t') }}</h2><p>{{ $t('s8_p1') }}</p><p>{{ $t('s8_p2') }}</p><p>{{ $t('s8_p3') }}</p></article>
        </div>

        <aside class="toc">
            <h3>{{ $t('on_page') }}</h3>
            <ul class="toc-list">
                <li><a href="#s1"><span>{{ $t('toc1') }}</span><span class="toc-num">01</span></a></li>
                <li><a href="#s2"><span>{{ $t('toc2') }}</span><span class="toc-num">02</span></a></li>
                <li><a href="#s3"><span>{{ $t('toc3') }}</span><span class="toc-num">03</span></a></li>
                <li><a href="#s4"><span>{{ $t('toc4') }}</span><span class="toc-num">04</span></a></li>
                <li><a href="#s5"><span>{{ $t('toc5') }}</span><span class="toc-num">05</span></a></li>
                <li><a href="#s6"><span>{{ $t('toc6') }}</span><span class="toc-num">06</span></a></li>
                <li><a href="#s7"><span>{{ $t('toc7') }}</span><span class="toc-num">07</span></a></li>
                <li><a href="#s8"><span>{{ $t('toc8') }}</span><span class="toc-num">08</span></a></li>
            </ul>
        </aside>
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

