@php
    $currentLocale = $currentLocale ?? 'ar';
    $isEnglish = ($isEnglish ?? ($currentLocale === 'en')) === true;
    $localePrefix = $localePrefix ?? ($isEnglish ? '/en' : '/ar');
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', 'https://styliiiish.com')), '/');
    $wpLogo = $wpLogo ?? ($wpBaseUrl . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547-300x128.png');
    $wpLogo2x = $wpBaseUrl . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547-600x255.png';
    $wpLogoOriginal = $wpBaseUrl . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpLocalizedAccountUrl = $wpLocalizedAccountUrl ?? ($isEnglish
        ? ($wpBaseUrl . '/my-account/')
        : ($wpBaseUrl . '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/'));

    $translate = (isset($t) && is_callable($t)) ? $t : fn (string $key) => $key;
    $ft = function (string $key, string $arFallback, string $enFallback) use ($translate, $isEnglish): string {
        $value = (string) $translate($key);
        if ($value === '' || $value === $key) {
            return $isEnglish ? $enFallback : $arFallback;
        }

        return $value;
    };

    $timeNow = \Carbon\Carbon::now('Africa/Cairo');
    $opening = \Carbon\Carbon::createFromTime(11, 0, 0, 'Africa/Cairo');
    $closing = \Carbon\Carbon::createFromTime(19, 0, 0, 'Africa/Cairo');
    $isOpenNow = isset($isOpenNow) ? (bool) $isOpenNow : $timeNow->betweenIncluded($opening, $closing);
@endphp

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <img class="footer-brand-logo" src="{{ $wpLogo }}" srcset="{{ $wpLogo }} 1x, {{ $wpLogo2x }} 2x" sizes="156px" width="156" height="66" loading="lazy" decoding="async" alt="Styliiiish" onerror="this.onerror=null;this.src='{{ $wpLogoOriginal }}';">
            <h4>{{ $ft('footer_title', 'ستيليش فاشون هاوس', 'Styliiiish Fashion House') }}</h4>
            <p>{{ $ft('footer_desc', 'نعمل بشغف على تقديم أحدث تصاميم الفساتين لتناسب كل مناسبة خاصة بك.', 'We are passionate about offering the latest dress designs for every special occasion.') }}</p>
            <p class="footer-status">
                {{ $ft('status_label', 'الحالة', 'Status') }} :
                <span class="status-pill {{ $isOpenNow ? 'is-open' : 'is-closed' }}">{{ $isOpenNow ? $ft('open_now', 'مفتوح', 'Open') : $ft('closed_now', 'مغلق', 'Closed') }}</span>
            </p>
            <p class="footer-open-hours"><strong>{{ $ft('open_hours_label', 'ساعات العمل', 'Working Hours') }}:</strong> {{ $ft('open_hours_value', 'السبت – الجمعة: 11:00 ص – 7:00 م', 'Saturday – Friday: 11:00 AM – 7:00 PM') }}</p>
            <div class="footer-contact-row">
                <a href="{{ $localePrefix }}/contact-us">{{ $ft('contact_us', 'تواصلي معنا', 'Contact Us') }}</a>
                <a href="tel:+201050874255">{{ $ft('direct_call', 'اتصال مباشر', 'Direct Call') }}</a>
            </div>
        </div>

        <div class="footer-col">
            <h5>{{ $ft('quick_links', 'روابط سريعة', 'Quick Links') }}</h5>
            <ul class="footer-links">
                <li><a href="{{ $localePrefix }}">{{ $ft('nav_home', 'الرئيسية', 'Home') }}</a></li>
                <li><a href="{{ $localePrefix }}/blog">{{ $ft('nav_blog', 'المدونة', 'Blog') }}</a></li>
                <li><a href="{{ $localePrefix }}/shop">{{ $ft('shop_now', 'تسوقي الفساتين الآن', 'Shop Dresses Now') }}</a></li>
                <li><a href="{{ $localePrefix }}/shop">{{ $ft('nav_shop', 'المتجر', 'Shop') }}</a></li>
                <li><a href="{{ $localePrefix }}/marketplace">{{ $ft('footer_marketplace', 'فساتين مستعملة', 'Marketplace') }}</a></li>
                <li><a href="{{ $localePrefix }}/categories">{{ $ft('categories', 'الأقسام', 'Categories') }}</a></li>
                <li><a href="{{ $isEnglish ? ($wpBaseUrl . '/dress-rental-in-cairo/') : ($wpBaseUrl . '/ar/dress-rental-in-cairo/') }}">{{ $ft('nav_rental', 'قسم الإيجار', 'Rental Section') }}</a></li>
                <li><a href="https://styliiiish.com/my-dresses/" target="_blank" rel="noopener">{{ $ft('nav_sell', 'بيعي فستانك', 'Sell Your Dress') }}</a></li>
                <li><a href="{{ $wpLocalizedAccountUrl }}" target="_blank" rel="noopener">{{ $ft('account', 'حسابي', 'My Account') }}</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h5>{{ $ft('official_info', 'معلومات رسمية', 'Official Info') }}</h5>
            <ul class="footer-links">
                <li><a href="https://maps.app.goo.gl/MCdcFEcFoR4tEjpT8" target="_blank" rel="noopener">{{ $ft('official_address', '1 شارع نبيل خليل، مدينة نصر، القاهرة، مصر', '1 Nabil Khalil St, Nasr City, Cairo, Egypt') }}</a></li>
                <li><a href="tel:+201050874255">+2 010-5087-4255</a></li>
                <li><a href="{{ $localePrefix }}/contact-us">{{ $ft('nav_contact', 'تواصل معنا', 'Contact Us') }}</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h5>{{ $ft('policies', 'سياسات وقوانين', 'Policies & Legal') }}</h5>
            <ul class="footer-links">
                <li><a href="{{ $localePrefix }}/about-us">{{ $ft('about_us', 'من نحن', 'About Us') }}</a></li>
                <li><a href="{{ $localePrefix }}/privacy-policy">{{ $ft('privacy', 'سياسة الخصوصية', 'Privacy Policy') }}</a></li>
                <li><a href="{{ $localePrefix }}/terms-conditions">{{ $ft('terms', 'الشروط والأحكام', 'Terms & Conditions') }}</a></li>
                <li><a href="{{ $localePrefix }}/marketplace-policy">{{ $ft('market_policy', 'سياسة الماركت بليس', 'Marketplace Policy') }}</a></li>
                <li><a href="{{ $localePrefix }}/refund-return-policy">{{ $ft('refund_policy', 'سياسة الاسترجاع والاستبدال', 'Refund & Return Policy') }}</a></li>
                <li><a href="{{ $localePrefix }}/faq">{{ $ft('faq', 'الأسئلة الشائعة', 'FAQ') }}</a></li>
                <li><a href="{{ $localePrefix }}/shipping-delivery-policy">{{ $ft('shipping_policy', 'سياسة الشحن والتوصيل', 'Shipping & Delivery Policy') }}</a></li>
                <li><a href="{{ $localePrefix }}/cookie-policy">{{ $ft('cookies', 'سياسة ملفات الارتباط', 'Cookie Policy') }}</a></li>
            </ul>
        </div>
    </div>

    <div class="container footer-bottom">
        <span>{{ str_replace(':year', (string) date('Y'), $ft('rights', 'جميع الحقوق محفوظة © :year Styliiiish | تشغيل وتطوير', 'All rights reserved © :year Styliiiish | Powered by')) }} <a href="https://websiteflexi.com/" target="_blank" rel="noopener">Website Flexi</a></span>
        <span><a href="{{ $localePrefix }}">styliiiish.com</a></span>
    </div>

    <div class="container footer-mini-nav">
        <a href="{{ $localePrefix }}">{{ $ft('home_mini', 'الرئيسية', 'Home') }}</a>
        <a href="{{ $localePrefix }}/shop">{{ $ft('shop_mini', 'المتجر', 'Shop') }}</a>
        <a href="{{ $localePrefix }}/cart">{{ $ft('cart_mini', 'السلة', 'Cart') }}</a>
        <a href="{{ $wpLocalizedAccountUrl }}" target="_blank" rel="noopener">{{ $ft('account_mini', 'حسابي', 'Account') }}</a>
        <a href="{{ $localePrefix }}/wishlist">{{ $ft('fav_mini', 'المفضلة', 'Wishlist') }}</a>
    </div>
</footer>
