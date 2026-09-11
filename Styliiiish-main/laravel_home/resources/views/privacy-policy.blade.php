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
            'page_title' => 'سياسة الخصوصية | ستايلش',
            'meta_desc' => 'سياسة الخصوصية الخاصة بـ Styliiiish: ما البيانات التي نجمعها، كيف نستخدمها، مدة الاحتفاظ، وحقوقك في الوصول والتصحيح والحذف والاعتراض.',
            'brand_tag' => 'لأن كل امرأة تستحق أن تتألق',
            'nav_home' => 'الرئيسية',
            'nav_shop' => 'المتجر',
            'nav_blog' => 'المدونة',
            'nav_about' => 'من نحن',
            'nav_marketplace' => 'الماركت بليس',
            'nav_sell' => 'بيعي فستانك',
            'nav_contact' => 'تواصل معنا',
            'lang_switch' => 'تبديل اللغة',
            'hero_badge' => 'قانوني',
            'hero_title' => 'سياسة الخصوصية',
            'hero_desc' => 'في ستايليش، نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية. توضح هذه السياسة ما نجمعه، ولماذا نجمعه، وكيف يمكنك التحكم فيه.',
            'updated' => 'آخر تحديث',
            'updated_value' => 'ديسمبر 2025',
            'contact' => 'اتصال',
            'toc' => 'في هذه الصفحة',
            'need_help' => 'هل تحتاج إلى مساعدة؟',
            'help_desc' => 'تواصل مع فريق الخصوصية لدينا على privacy@styliiiish.com',
            's1_t' => '1) مراقب البيانات',
            's1_p1' => 'تدير Styliiiish هذا الموقع الإلكتروني وتعمل كمراقب بيانات بالنسبة للبيانات الشخصية التي يتم جمعها من خلال الموقع.',
            's1_p2' => 'صندوق بريد الخصوصية: privacy@styliiiish.com',
            's1_p3' => 'لا يُطلب من Styliiiish تعيين مسؤول حماية بيانات (DPO) بموجب المادة 37 من اللائحة العامة لحماية البيانات (GDPR).',
            's2_t' => '2) المعلومات التي نجمعها',
            's2_p' => 'قد نقوم بجمع البيانات الشخصية التالية عند استخدامك لموقعنا أو خدماتنا:',
            's2_1' => 'الاسم الكامل',
            's2_2' => 'عنوان البريد الإلكتروني',
            's2_3' => 'رقم الهاتف',
            's2_4' => 'عنوان الفاتورة والشحن',
            's2_5' => 'تفاصيل الطلب والمعاملة',
            's2_6' => 'نشاط الحساب والسوق',
            's2_7' => 'الرسائل والاستفسارات التي ترسلها إلينا',
            's3_t' => '3) كيف نستخدم معلوماتك',
            's3_p' => 'نقوم بمعالجة البيانات الشخصية من أجل:',
            's3_1' => 'معالجة الطلبات وتنفيذها',
            's3_2' => 'تقديم دعم العملاء',
            's3_3' => 'إرسال تحديثات متعلقة بالخدمة (حالة الطلب، التأكيدات)',
            's3_4' => 'تشغيل وظائف السوق الإلكترونية وتلبية طلبات البائعين',
            's3_5' => 'تحسين أداء الموقع وتجربة المستخدم',
            's3_6' => 'الامتثال للالتزامات القانونية والمحاسبية',
            's4_t' => '4) الأساس القانوني للمعالجة (GDPR)',
            's4_p' => 'نقوم بمعالجة البيانات الشخصية بناءً على واحد أو أكثر من الأسس القانونية التالية:',
            's4_1' => 'ضرورة التعاقد (معالجة الطلبات والخدمات)',
            's4_2' => 'الالتزامات القانونية (الضرائب، المحاسبة، الامتثال)',
            's4_3' => 'المصالح المشروعة (الأمن، منع الاحتيال، التحليلات)',
            's4_4' => 'الموافقة (الاتصالات التسويقية وملفات تعريف الارتباط عند الاقتضاء)',
            's5_t' => '5) المدفوعات والأمان',
            's5_p1' => 'تتم معالجة المدفوعات بشكل آمن بواسطة مزودي خدمات دفع معتمدين من جهات خارجية (مثل Paymob أو PayPal). لا يقوم Styliiiish بتخزين أو معالجة بيانات البطاقات على خوادمه.',
            's5_p2' => 'نطبق تدابير أمنية تقنية وتنظيمية مناسبة مثل تشفير SSL، وبيئات استضافة آمنة، وضوابط وصول، وتحديثات دورية.',
            's6_t' => '6) مشاركة البيانات والأطراف الثالثة',
            's6_p' => 'لا نشارك البيانات الشخصية إلا عند الضرورة لتشغيل الخدمة، بما في ذلك مع:',
            's6_1' => 'مقدمي خدمات الدفع (مثل Paymob، PayPal)',
            's6_2' => 'شركاء الشحن والتوصيل',
            's6_3' => 'بائعي السوق لتنفيذ الطلبات الخاصة بهم',
            's6_4' => 'الاستضافة والبنية التحتية (مثل Hostinger)',
            's6_5' => 'التحليلات والإعلانات (مثل Google Analytics وGoogle Ads)',
            's6_6' => 'مستشارون قانونيون ومحاسبيون للامتثال والتقارير',
            's6_note' => 'نشارك الحد الأدنى من البيانات اللازمة، ونتوقع من جميع الأطراف الثالثة حماية البيانات بما يتوافق مع القوانين المطبقة.',
            's7_t' => '7) وسائل التواصل الاجتماعي',
            's7_p1' => 'تحتفظ Styliiiish بصفحات رسمية على منصات مثل فيسبوك، إنستغرام، تيك توك، وGoogle Business Profile.',
            's7_p2' => 'عند التفاعل معنا على هذه المنصات، قد نتلقى بيانات محدودة مثل اسم المستخدم العام ومحتوى الرسائل بهدف الدعم وخدمة العملاء.',
            's7_p3' => 'كل منصة تواصل اجتماعي تعمل كجهة تحكم بيانات مستقلة وتطبق سياسة الخصوصية الخاصة بها.',
            's8_t' => '8) ملفات تعريف الارتباط والتتبع',
            's8_p1' => 'نستخدم ملفات تعريف الارتباط وتقنيات مشابهة لتمكين وظائف الموقع الأساسية، وقياس الأداء، ودعم الأنشطة التسويقية.',
            's8_p2' => 'قد تتضمن بيانات التحليلات معلومات استخدام غير مباشرة مثل الصفحات التي تمت زيارتها، نوع الجهاز والمتصفح، وأنماط التفاعل.',
            's8_p3' => 'يمكنك إدارة تفضيلاتك عبر إشعار ملفات الارتباط أو إعدادات المتصفح.',
            's8_p4' => 'رابط سياسة ملفات الارتباط: https://styliiiish.com/cookie-policy/',
            's9_t' => '9) الاحتفاظ بالبيانات',
            's9_1' => 'الطلبات والفواتير: وفقًا للالتزامات القانونية والضريبية والمحاسبية.',
            's9_2' => 'بيانات الحساب: حتى حذف الحساب أو طلب الحذف.',
            's9_3' => 'بيانات التسويق: حتى سحب الموافقة.',
            's10_t' => '10) حقوقك',
            's10_p' => 'قد يكون لك الحق في:',
            's10_1' => 'الوصول إلى بياناتك الشخصية',
            's10_2' => 'تصحيح البيانات غير الدقيقة',
            's10_3' => 'طلب حذف بياناتك',
            's10_4' => 'تقييد المعالجة أو الاعتراض عليها',
            's10_5' => 'طلب نقل البيانات',
            's10_6' => 'سحب الموافقة في أي وقت',
            's10_7' => 'تقديم شكوى إلى جهة إشرافية',
            's10_p2' => 'لأي طلب متعلق بحقوقك، راسلنا على privacy@styliiiish.com.',
            's11_t' => '11) السلطة الإشرافية',
            's11_p' => 'إذا كنت مقيمًا في الاتحاد الأوروبي، يمكنك تقديم شكوى إلى هيئة حماية البيانات المحلية لديك.',
            's12_t' => '12) تحديثات هذه السياسة',
            's12_p' => 'قد نقوم بتحديث هذه السياسة من وقت لآخر. سيتم نشر أي تغييرات في هذه الصفحة مع تاريخ التحديث.',
            's13_t' => '13) عمليات نقل البيانات الدولية',
            's13_p' => 'قد يعالج بعض مزودي الخدمة (مثل Google) البيانات خارج بلدك أو خارج المنطقة الاقتصادية الأوروبية. عندها نضمن وجود ضمانات مناسبة، مثل البنود التعاقدية القياسية المعتمدة من المفوضية الأوروبية.',
            'questions' => 'أسئلة؟',
            'questions_desc' => 'راسلنا عبر البريد الإلكتروني في أي وقت: privacy@styliiiish.com',
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
            'page_title' => 'Privacy Policy | Styliiiish',
            'meta_desc' => 'Styliiiish Privacy Policy: what data we collect, why we collect it, legal basis, retention periods, third parties, and your privacy rights.',
            'brand_tag' => 'Because every woman deserves to shine',
            'nav_home' => 'Home',
            'nav_shop' => 'Shop',
            'nav_blog' => 'Blog',
            'nav_about' => 'About Us',
            'nav_marketplace' => 'Marketplace',
            'nav_sell' => 'Sell Your Dress',
            'nav_contact' => 'Contact Us',
            'lang_switch' => 'Language Switcher',
            'hero_badge' => 'Legal',
            'hero_title' => 'Privacy Policy',
            'hero_desc' => 'At Styliiiish, we respect your privacy and are committed to protecting your personal data. This policy explains what we collect, why we collect it, and how you can control it.',
            'updated' => 'Last Updated',
            'updated_value' => 'December 2025',
            'contact' => 'Contact',
            'toc' => 'On this page',
            'need_help' => 'Need help?',
            'help_desc' => 'Contact our privacy team at privacy@styliiiish.com',
            's1_t' => '1) Data Controller',
            's1_p1' => 'Styliiiish operates this website and acts as the data controller for personal data collected through it.',
            's1_p2' => 'Privacy mailbox: privacy@styliiiish.com',
            's1_p3' => 'Styliiiish is not required to appoint a Data Protection Officer (DPO) under Article 37 GDPR.',
            's2_t' => '2) Information We Collect',
            's2_p' => 'We may collect the following personal data when you use our website or services:',
            's2_1' => 'Full name',
            's2_2' => 'Email address',
            's2_3' => 'Phone number',
            's2_4' => 'Billing and shipping address',
            's2_5' => 'Order and transaction details',
            's2_6' => 'Account and marketplace activity',
            's2_7' => 'Messages and inquiries you send us',
            's3_t' => '3) How We Use Your Information',
            's3_p' => 'We process personal data to:',
            's3_1' => 'Process and fulfill orders',
            's3_2' => 'Provide customer support',
            's3_3' => 'Send service-related updates (order status, confirmations)',
            's3_4' => 'Operate marketplace functions and seller workflows',
            's3_5' => 'Improve website performance and user experience',
            's3_6' => 'Comply with legal and accounting obligations',
            's4_t' => '4) Legal Basis for Processing (GDPR)',
            's4_p' => 'We process personal data based on one or more of the following legal bases:',
            's4_1' => 'Contractual necessity (orders and services)',
            's4_2' => 'Legal obligations (tax, accounting, compliance)',
            's4_3' => 'Legitimate interests (security, fraud prevention, analytics)',
            's4_4' => 'Consent (marketing communications and cookies where applicable)',
            's5_t' => '5) Payments and Security',
            's5_p1' => 'Payments are processed securely by authorized third-party providers (e.g., Paymob or PayPal). Styliiiish does not store or process card details on its servers.',
            's5_p2' => 'We apply appropriate technical and organizational security measures, including SSL encryption, secure hosting environments, access controls, and regular updates.',
            's6_t' => '6) Data Sharing and Third Parties',
            's6_p' => 'We share personal data only when required to operate the service, including with:',
            's6_1' => 'Payment providers (e.g., Paymob, PayPal)',
            's6_2' => 'Shipping and delivery partners',
            's6_3' => 'Marketplace sellers for order fulfillment',
            's6_4' => 'Hosting and infrastructure providers (e.g., Hostinger)',
            's6_5' => 'Analytics and advertising providers (e.g., Google Analytics, Google Ads)',
            's6_6' => 'Legal and accounting advisors for compliance and reporting',
            's6_note' => 'We share only what is necessary and expect all third parties to handle data securely and lawfully.',
            's7_t' => '7) Social Media Presence',
            's7_p1' => 'Styliiiish maintains official pages on platforms such as Facebook, Instagram, TikTok, and Google Business Profile.',
            's7_p2' => 'When you interact with us there, we may receive limited data such as your public profile name/username and message content to provide support.',
            's7_p3' => 'Each social media platform acts as an independent data controller and applies its own privacy policy.',
            's8_t' => '8) Cookies and Tracking Technologies',
            's8_p1' => 'We use cookies and similar technologies for core website functionality, performance measurement, and marketing support.',
            's8_p2' => 'Analytics data may include non-directly identifiable usage data such as visited pages, device type, browser type, and interaction patterns.',
            's8_p3' => 'You can manage your preferences at any time via the cookie notice or browser settings.',
            's8_p4' => 'Read our Cookie Policy here: https://styliiiish.com/cookie-policy/',
            's9_t' => '9) Data Retention',
            's9_1' => 'Orders and invoices: retained as required by legal/tax/accounting obligations.',
            's9_2' => 'Account data: retained until account deletion or deletion request.',
            's9_3' => 'Marketing data: retained until consent is withdrawn.',
            's10_t' => '10) Your Rights',
            's10_p' => 'You may have the right to:',
            's10_1' => 'Access your personal data',
            's10_2' => 'Correct inaccurate data',
            's10_3' => 'Request deletion of your data',
            's10_4' => 'Restrict or object to processing',
            's10_5' => 'Request data portability',
            's10_6' => 'Withdraw consent at any time',
            's10_7' => 'Lodge a complaint with a supervisory authority',
            's10_p2' => 'To exercise any of the above rights, contact us at privacy@styliiiish.com.',
            's11_t' => '11) Supervisory Authority',
            's11_p' => 'If you are an EU resident, you may file a complaint with your local data protection authority.',
            's12_t' => '12) Policy Updates',
            's12_p' => 'We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated date.',
            's13_t' => '13) International Data Transfers',
            's13_p' => 'Some service providers (such as Google) may process data outside your country or outside the EEA. In such cases, we ensure appropriate safeguards, such as Standard Contractual Clauses approved by the European Commission.',
            'questions' => 'Questions?',
            'questions_desc' => 'Email us anytime: privacy@styliiiish.com',
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
    $canonicalPath = $localePrefix . '/privacy-policy';
    $wpDisplayHost = preg_replace('#^https?://#', '', $wpBaseUrl);
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $wpBaseUrl }}{{ $canonicalPath }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/privacy-policy">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/privacy-policy">
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
        .hero { padding: 34px 0 14px; }
        .hero-box { background: linear-gradient(160deg,#ffffff 0%,#fff4f5 100%); border: 1px solid var(--line); border-radius: 18px; padding: 24px; box-shadow: 0 10px 30px rgba(23,39,59,.07); }
        .badge { display: inline-flex; align-items: center; background: #ffeef0; color: var(--primary); border-radius: 999px; padding: 7px 12px; font-size: 13px; font-weight: 700; margin-bottom: 10px; }
        .hero h1 { margin: 0 0 8px; font-size: clamp(28px,4vw,42px); line-height: 1.2; }
        .hero p { margin: 0; color: var(--muted); max-width: 860px; }
        .meta-row { margin-top: 12px; display: flex; gap: 14px; flex-wrap: wrap; font-size: 14px; }
        .meta-row strong { color: var(--secondary); }
        .section { padding: 10px 0 24px; }
        .layout { display: grid; grid-template-columns: .9fr 1.1fr; gap: 16px; align-items: start; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 16px; box-shadow: 0 8px 20px rgba(23,39,59,.05); }
        .card h2 { margin: 0 0 10px; font-size: 22px; }
        .toc-list, .bullets { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
        .toc-list li, .bullets li { border: 1px solid var(--line); background: #fbfcff; border-radius: 10px; padding: 9px 11px; font-size: 14px; color: var(--secondary); }
        .toc-list li { padding: 0; overflow: hidden; }
        .toc-list a { display: block; padding: 9px 11px; font-weight: 700; }
        .toc-list a:hover { background: #fff4f5; color: var(--primary); }
        .help-box { margin-top: 12px; background: #fff6f7; border: 1px solid rgba(var(--wf-main-rgb), .2); border-radius: 12px; padding: 10px 12px; }
        .content article { margin-bottom: 12px; }
        .content article[id] { scroll-margin-top: 110px; }
        .content h3 { margin: 0 0 6px; font-size: 20px; }
        .content p { margin: 0 0 8px; color: var(--muted); }
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
        @media (max-width:980px) { .main-header-inner { grid-template-columns: 1fr; padding: 12px 0; } .brand, .main-nav, .header-tools { justify-content: center; text-align: center; } .layout { grid-template-columns: 1fr; } .footer-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
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
        <div class="meta-row">
            <span><strong>{{ $t('updated') }}:</strong> {{ $t('updated_value') }}</span>
            <span><strong>{{ $t('contact') }}:</strong> <a href="mailto:privacy@styliiiish.com">privacy@styliiiish.com</a></span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container layout">
        <aside class="card">
            <h2>{{ $t('toc') }}</h2>
            <ul class="toc-list">
                <li><a href="#pp-1">{{ $t('s1_t') }}</a></li>
                <li><a href="#pp-2">{{ $t('s2_t') }}</a></li>
                <li><a href="#pp-3">{{ $t('s3_t') }}</a></li>
                <li><a href="#pp-4">{{ $t('s4_t') }}</a></li>
                <li><a href="#pp-5">{{ $t('s5_t') }}</a></li>
                <li><a href="#pp-6">{{ $t('s6_t') }}</a></li>
                <li><a href="#pp-7">{{ $t('s7_t') }}</a></li>
                <li><a href="#pp-8">{{ $t('s8_t') }}</a></li>
                <li><a href="#pp-9">{{ $t('s9_t') }}</a></li>
                <li><a href="#pp-10">{{ $t('s10_t') }}</a></li>
                <li><a href="#pp-11">{{ $t('s11_t') }}</a></li>
                <li><a href="#pp-12">{{ $t('s12_t') }}</a></li>
                <li><a href="#pp-13">{{ $t('s13_t') }}</a></li>
            </ul>
            <div class="help-box">
                <strong>{{ $t('need_help') }}</strong>
                <p>{{ $t('help_desc') }}</p>
            </div>
        </aside>

        <div class="content">
            <article id="pp-1" class="card"><h3>{{ $t('s1_t') }}</h3><p>{{ $t('s1_p1') }}</p><p>{{ $t('s1_p2') }}</p><p>{{ $t('s1_p3') }}</p></article>
            <article id="pp-2" class="card"><h3>{{ $t('s2_t') }}</h3><p>{{ $t('s2_p') }}</p><ul class="bullets"><li>{{ $t('s2_1') }}</li><li>{{ $t('s2_2') }}</li><li>{{ $t('s2_3') }}</li><li>{{ $t('s2_4') }}</li><li>{{ $t('s2_5') }}</li><li>{{ $t('s2_6') }}</li><li>{{ $t('s2_7') }}</li></ul></article>
            <article id="pp-3" class="card"><h3>{{ $t('s3_t') }}</h3><p>{{ $t('s3_p') }}</p><ul class="bullets"><li>{{ $t('s3_1') }}</li><li>{{ $t('s3_2') }}</li><li>{{ $t('s3_3') }}</li><li>{{ $t('s3_4') }}</li><li>{{ $t('s3_5') }}</li><li>{{ $t('s3_6') }}</li></ul></article>
            <article id="pp-4" class="card"><h3>{{ $t('s4_t') }}</h3><p>{{ $t('s4_p') }}</p><ul class="bullets"><li>{{ $t('s4_1') }}</li><li>{{ $t('s4_2') }}</li><li>{{ $t('s4_3') }}</li><li>{{ $t('s4_4') }}</li></ul></article>
            <article id="pp-5" class="card"><h3>{{ $t('s5_t') }}</h3><p>{{ $t('s5_p1') }}</p><p>{{ $t('s5_p2') }}</p></article>
            <article id="pp-6" class="card"><h3>{{ $t('s6_t') }}</h3><p>{{ $t('s6_p') }}</p><ul class="bullets"><li>{{ $t('s6_1') }}</li><li>{{ $t('s6_2') }}</li><li>{{ $t('s6_3') }}</li><li>{{ $t('s6_4') }}</li><li>{{ $t('s6_5') }}</li><li>{{ $t('s6_6') }}</li></ul><p>{{ $t('s6_note') }}</p></article>
            <article id="pp-7" class="card"><h3>{{ $t('s7_t') }}</h3><p>{{ $t('s7_p1') }}</p><p>{{ $t('s7_p2') }}</p><p>{{ $t('s7_p3') }}</p></article>
            <article id="pp-8" class="card"><h3>{{ $t('s8_t') }}</h3><p>{{ $t('s8_p1') }}</p><p>{{ $t('s8_p2') }}</p><p>{{ $t('s8_p3') }}</p><p>{{ $t('s8_p4') }}</p></article>
            <article id="pp-9" class="card"><h3>{{ $t('s9_t') }}</h3><ul class="bullets"><li>{{ $t('s9_1') }}</li><li>{{ $t('s9_2') }}</li><li>{{ $t('s9_3') }}</li></ul></article>
            <article id="pp-10" class="card"><h3>{{ $t('s10_t') }}</h3><p>{{ $t('s10_p') }}</p><ul class="bullets"><li>{{ $t('s10_1') }}</li><li>{{ $t('s10_2') }}</li><li>{{ $t('s10_3') }}</li><li>{{ $t('s10_4') }}</li><li>{{ $t('s10_5') }}</li><li>{{ $t('s10_6') }}</li><li>{{ $t('s10_7') }}</li></ul><p>{{ $t('s10_p2') }}</p></article>
            <article id="pp-11" class="card"><h3>{{ $t('s11_t') }}</h3><p>{{ $t('s11_p') }}</p></article>
            <article id="pp-12" class="card"><h3>{{ $t('s12_t') }}</h3><p>{{ $t('s12_p') }}</p></article>
            <article id="pp-13" class="card"><h3>{{ $t('s13_t') }}</h3><p>{{ $t('s13_p') }}</p><p><strong>{{ $t('questions') }}</strong> {{ $t('questions_desc') }}</p></article>
        </div>
    </div>
</section>

@include('partials.shared-home-footer')
</body>
</html>

