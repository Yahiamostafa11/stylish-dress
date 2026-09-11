@php
    $currentLocale = strtolower((string) ($currentLocale ?? 'ar'));
    if (!in_array($currentLocale, ['ar', 'en'], true)) {
        $currentLocale = 'ar';
    }

    $isEnglish = ($isEnglish ?? ($currentLocale === 'en')) === true;
    $rawLocalePrefix = (string) ($localePrefix ?? ($isEnglish ? '/en' : '/ar'));
    $rawLocalePrefix = '/' . trim($rawLocalePrefix, '/');
    $localePrefix = preg_match('#^/(ar|en)(?:/.*)?$#i', $rawLocalePrefix, $localeMatch)
        ? ('/' . strtolower((string) $localeMatch[1]))
        : ($isEnglish ? '/en' : '/ar');
    $currentLocale = $localePrefix === '/en' ? 'en' : 'ar';
    $isEnglish = $currentLocale === 'en';
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', 'https://styliiiish.com')), '/');
    $wpLogo = $wpLogo ?? 'https://styliiiish.com/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';

    $wpMyAccountUrl = $wpMyAccountUrl ?? ($wpBaseUrl . '/my-account/');
    $headerSearchUrl = $localePrefix . '/shop';
    $wpLocalizedAccountUrl = $wpLocalizedAccountUrl ?? ($isEnglish
        ? ($wpBaseUrl . '/my-account/')
        : ($wpBaseUrl . '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/'));
    $wpLocalizedMyDressesUrl = $wpLocalizedMyDressesUrl ?? ($isEnglish
        ? ($wpBaseUrl . '/my-dresses/')
        : ($wpBaseUrl . '/ar/%d9%81%d8%b3%d8%a7%d8%aa%d9%8a%d9%86%d9%8a/'));
    $normalizePath = function (string $path): string {
        $trimmed = trim($path);
        if ($trimmed === '' || $trimmed === '/') {
            return '/';
        }

        return '/' . trim($trimmed, '/');
    };
    $currentPath = $normalizePath(request()->getPathInfo());
    $homePath = $normalizePath($localePrefix);
    $isHomeRoute = $currentPath === $homePath;

    $pathWithoutLocale = preg_replace('#^/(ar|en)(?=/|$)#i', '', $currentPath) ?? $currentPath;
    $pathWithoutLocale = $normalizePath($pathWithoutLocale);
    $localePathTail = $pathWithoutLocale === '/' ? '' : $pathWithoutLocale;
    $arSwitchUrl = '/ar' . $localePathTail;
    $enSwitchUrl = '/en' . $localePathTail;

    $decodedCurrentPath = $normalizePath(rawurldecode($currentPath));

    $accountPaths = [
        '/my-account',
        '/en/my-account',
        '/ar/حسابي',
    ];

    if (in_array($currentPath, $accountPaths, true) || in_array($decodedCurrentPath, $accountPaths, true)) {
        $arSwitchUrl = '/ar/حسابي/';
        $enSwitchUrl = '/my-account/';
    }

    $translate = (isset($t) && is_callable($t)) ? $t : fn (string $key) => $key;
    $ht = function (string $key, string $arFallback, string $enFallback) use ($translate, $isEnglish): string {
        $value = (string) $translate($key);
        if ($value === '' || $value === $key) {
            return $isEnglish ? $enFallback : $arFallback;
        }

        return $value;
    };
@endphp

<div class="topbar">
    <div class="container topbar-inner">
        <div class="topbar-right">
            <div class="topbar-desktop-contact">
                <strong>{{ $ht('contact_anytime', 'اتصلي بنا في أي وقت:', 'Call us anytime:') }}</strong>
                <a class="topbar-phone" href="tel:+201050874255" dir="ltr" lang="en">+20 010 5087 4255</a>
            </div>
            <div class="topbar-mobile-lang">
                <button class="topbar-lang-toggle" id="topbarLangToggle" type="button" aria-label="{{ $ht('menu', 'اللغة', 'Language') }}" title="{{ $ht('menu', 'اللغة', 'Language') }}" aria-controls="topbarLangPanel" aria-expanded="false">{{ strtoupper($currentLocale) }}</button>
                <div class="topbar-lang-panel" id="topbarLangPanel" aria-hidden="true">
                    <a class="{{ $currentLocale === 'ar' ? 'active' : '' }}" href="{{ $arSwitchUrl }}">AR</a>
                    <a class="{{ $currentLocale === 'en' ? 'active' : '' }}" href="{{ $enSwitchUrl }}">EN</a>
                </div>
            </div>
            <div class="topbar-mobile-social">
                <button class="topbar-social-toggle" id="topbarSocialToggle" type="button" aria-label="{{ $ht('contact_anytime', 'اتصلي بنا في أي وقت:', 'Call us anytime:') }}" title="{{ $ht('contact_anytime', 'اتصلي بنا في أي وقت:', 'Call us anytime:') }}" aria-controls="topbarSocialPanel" aria-expanded="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 14a3 3 0 0 0-2.24 1l-4.27-2.14a3.1 3.1 0 0 0 0-1.72l4.27-2.14a3 3 0 1 0-.9-1.8L9.59 9.34a3 3 0 1 0 0 5.32l4.27 2.14A3 3 0 1 0 17 14z"/></svg>
                </button>
                <div class="topbar-mobile-icons" id="topbarSocialPanel" aria-label="{{ $ht('contact_anytime', 'اتصلي بنا في أي وقت:', 'Call us anytime:') }}" aria-hidden="true">
                <a class="topbar-mobile-icon icon-call" href="tel:+201050874255" aria-label="{{ $ht('direct_call', 'اتصال مباشر', 'Direct Call') }}" title="{{ $ht('direct_call', 'اتصال مباشر', 'Direct Call') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.2 15.2 0 0 0 6.6 6.6l2.2-2.2a1.5 1.5 0 0 1 1.5-.37c1.1.36 2.28.55 3.5.55A1.5 1.5 0 0 1 22 16.9V21a1.5 1.5 0 0 1-1.5 1.5C11.94 22.5 1.5 12.06 1.5 3.5A1.5 1.5 0 0 1 3 2h4.1a1.5 1.5 0 0 1 1.5 1.27c.1 1.22.3 2.4.66 3.5a1.5 1.5 0 0 1-.37 1.52l-2.3 2.5z"/></svg>
                </a>
                <a class="topbar-mobile-icon icon-whatsapp" href="https://wa.me/201050874255" target="_blank" rel="noopener" aria-label="WhatsApp" title="WhatsApp">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.52 3.48A11.9 11.9 0 0 0 12.07 0C5.5 0 .17 5.33.17 11.9c0 2.1.55 4.16 1.6 5.98L0 24l6.33-1.66a11.87 11.87 0 0 0 5.73 1.46h.01c6.57 0 11.9-5.33 11.9-11.9 0-3.18-1.24-6.17-3.45-8.42zM12.08 21.8h-.01a9.87 9.87 0 0 1-5.03-1.38l-.36-.22-3.76.98 1-3.66-.23-.37a9.88 9.88 0 0 1 8.38-15.1 9.9 9.9 0 0 1 7.02 2.9 9.83 9.83 0 0 1 2.9 7.01c0 5.45-4.44 9.88-9.9 9.88zm5.42-7.42c-.3-.15-1.79-.88-2.07-.98-.27-.1-.47-.15-.67.15-.2.3-.77.98-.95 1.18-.17.2-.35.22-.65.07-.3-.15-1.24-.46-2.36-1.47a8.86 8.86 0 0 1-1.64-2.03c-.17-.3-.02-.47.13-.62.14-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.57-.49-.5-.67-.5h-.57c-.2 0-.52.07-.8.37-.27.3-1.05 1.02-1.05 2.48s1.08 2.87 1.23 3.07c.15.2 2.1 3.2 5.08 4.49.71.31 1.27.5 1.7.64.72.23 1.38.2 1.9.12.58-.09 1.79-.73 2.04-1.44.25-.71.25-1.31.17-1.44-.07-.12-.27-.2-.57-.35z"/></svg>
                </a>
                <a class="topbar-mobile-icon icon-facebook" href="https://www.facebook.com/Styliiish.Egypt/" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.87.25-1.46 1.5-1.46h1.6V5.02c-.28-.04-1.23-.12-2.33-.12-2.3 0-3.87 1.4-3.87 4v2.1H8v3h2.4v8h3.1z"/></svg>
                </a>
                <a class="topbar-mobile-icon icon-instagram" href="https://www.instagram.com/styliiiish.egypt/" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.8A3.95 3.95 0 0 0 3.8 7.75v8.5a3.95 3.95 0 0 0 3.95 3.95h8.5a3.95 3.95 0 0 0 3.95-3.95v-8.5a3.95 3.95 0 0 0-3.95-3.95h-8.5zm8.95 1.35a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.8A3.2 3.2 0 1 0 12 15.2 3.2 3.2 0 0 0 12 8.8z"/></svg>
                </a>
                <a class="topbar-mobile-icon icon-tiktok" href="https://www.tiktok.com/@styliiish_?_r=1&_t=ZS-94HEUy9a0RE" target="_blank" rel="noopener" aria-label="TikTok" title="TikTok">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.13V2h-3.1v12.4a2.74 2.74 0 1 1-1.88-2.6V8.67a5.84 5.84 0 1 0 5 5.79V8.17a7.91 7.91 0 0 0 4.62 1.48V6.69h-.87z"/></svg>
                </a>
                <a class="topbar-mobile-icon icon-google" href="https://g.page/styliish" target="_blank" rel="noopener" aria-label="Google" title="Google">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 12.27c0-.82-.07-1.4-.23-2H12v4.26h6.61c-.13 1.06-.86 2.66-2.47 3.74l-.02.14 3.58 2.77.25.03c2.28-2.1 3.55-5.2 3.55-8.94zM12 24c3.24 0 5.95-1.07 7.93-2.92l-3.78-2.92c-1.01.7-2.37 1.19-4.15 1.19-3.17 0-5.86-2.1-6.82-5l-.13.01-3.73 2.88-.04.12A12 12 0 0 0 12 24zM5.18 14.35A7.2 7.2 0 0 1 4.78 12c0-.82.14-1.62.38-2.35l-.01-.16L1.38 6.57l-.12.06A11.98 11.98 0 0 0 0 12c0 1.93.46 3.75 1.27 5.37l3.91-3.02zM12 4.65c2.24 0 3.75.97 4.61 1.78l3.36-3.28C17.94 1.25 15.24 0 12 0A12 12 0 0 0 1.27 6.63l3.9 3.02c.97-2.9 3.66-5 6.83-5z"/></svg>
                </a>
                </div>
            </div>
        </div>
        <div class="topbar-left">
            <div class="lang-switch {{ $isEnglish ? 'is-en' : 'is-ar' }}" aria-label="Language Switcher">
                <span class="lang-indicator" aria-hidden="true"></span>
                <a class="{{ $currentLocale === 'ar' ? 'active' : '' }}" href="{{ $arSwitchUrl }}">AR</a>
                <a class="{{ $currentLocale === 'en' ? 'active' : '' }}" href="{{ $enSwitchUrl }}">EN</a>
            </div>
            <span class="topbar-note">{{ $ht('daily_deals', '⚡ خصومات يومية', '⚡ Daily Deals') }}</span>
            <a href="https://www.facebook.com/Styliiish.Egypt/" target="_blank" rel="noopener">{{ $ht('facebook', 'فيسبوك', 'Facebook') }}</a>
            <a href="https://www.instagram.com/styliiiish.egypt/" target="_blank" rel="noopener">{{ $ht('instagram', 'إنستجرام', 'Instagram') }}</a>
            <a href="https://g.page/styliish" target="_blank" rel="noopener">{{ $ht('google', 'جوجل', 'Google') }}</a>
        </div>
    </div>
</div>

<header class="main-header">
    <div class="container main-header-inner">
        <a class="brand" href="{{ $localePrefix }}">
            <img class="brand-logo" src="{{ $wpLogo }}" alt="Styliiiish" onerror="this.onerror=null;this.src='/brand/logo.png';">
            <span class="brand-tag">{{ $ht('brand_tag', 'لأن كل امرأة تستحق أن تتألق', 'Because every woman deserves to shine') }}</span>
        </a>

        @include('partials.shared-header-nav', ['navClass' => 'main-nav', 'navId' => 'headerMainNav'])

        <div class="header-actions">
            <button class="icon-btn nav-toggle action-nav-toggle" id="headerNavToggle" type="button" aria-label="{{ $ht('menu', 'القائمة', 'Menu') }}" title="{{ $ht('menu', 'القائمة', 'Menu') }}" aria-controls="headerMainNav" aria-expanded="false">
                <span class="icon" aria-hidden="true">☰</span>
            </button>
            <form class="search-form" action="{{ $headerSearchUrl }}" method="get">
                <input class="search-input" type="search" name="q" required placeholder="{{ $ht('search_placeholder', 'ابحثي عن فستانك...', 'Search for your dress...') }}" aria-label="{{ $ht('search_placeholder', 'ابحثي عن فستانك...', 'Search for your dress...') }}">
                <button class="search-btn" type="submit">{{ $ht('search_btn', 'بحث', 'Search') }}</button>
            </form>
            <span class="account-trigger-wrap action-account">
                <a class="icon-btn account-trigger" id="accountLoginTrigger" href="{{ $wpLocalizedAccountUrl }}" target="_blank" rel="noopener" aria-label="{{ $ht('account', 'حسابي', 'My Account') }}" title="{{ $ht('account', 'حسابي', 'My Account') }}"><span class="icon" aria-hidden="true">👤</span></a>
                <div class="account-menu" id="accountMenu" role="dialog" aria-label="{{ $ht('account', 'حسابي', 'My Account') }}" aria-hidden="true">
                    <div class="account-menu-head">
                        <strong id="accountMenuName">{{ $ht('account_logged_in', 'مستخدم مسجل دخول', 'Logged-in user') }}</strong>
                        <span id="accountMenuMeta">{{ $ht('account_loading', 'جاري تحميل بيانات الحساب…', 'Loading account details…') }}</span>
                    </div>
                    <a id="accountMenuManage" href="{{ $wpLocalizedAccountUrl }}" target="_blank" rel="noopener">{{ $ht('manage_account', 'إدارة حسابك', 'Manage your account') }}</a>
                    <a id="accountMenuLogout" href="{{ $wpMyAccountUrl }}">{{ $ht('logout', 'تسجيل خروج', 'Log out') }}</a>
                </div>
            </span>
            <span class="wishlist-trigger-wrap action-wishlist">
                <button class="icon-btn wishlist-trigger" id="wishlistTrigger" type="button" aria-label="{{ $ht('wishlist', 'قائمة الأمنيات', 'Wishlist') }}" title="{{ $ht('wishlist', 'قائمة الأمنيات', 'Wishlist') }}" aria-expanded="false" aria-controls="wishlistDropdown"><span class="icon" aria-hidden="true">❤</span>
                    <span class="wishlist-count" id="wishlistCountBadge">0</span>
                </button>
                <span class="wishlist-plus-one" id="wishlistPlusOne">+1</span>
                <div class="wishlist-dropdown" id="wishlistDropdown" role="dialog" aria-label="{{ $ht('wishlist', 'قائمة الأمنيات', 'Wishlist') }}" aria-hidden="true">
                    <div class="wishlist-dropdown-list" id="wishlistDropdownList">
                        <p class="wishlist-dropdown-empty" id="wishlistDropdownLoading">{{ $ht('wishlist_loading', 'جاري تحميل المفضلة…', 'Loading wishlist…') }}</p>
                    </div>
                    <div class="wishlist-dropdown-footer">
                        <a class="wishlist-dropdown-all" href="{{ $localePrefix }}/wishlist">{{ $ht('view_all_wishlist', 'عرض كل المفضلة', 'View full wishlist') }}</a>
                    </div>
                </div>
            </span>
            <span class="cart-trigger-wrap action-cart">
                <button class="icon-btn cart-trigger" id="miniCartTrigger" type="button" aria-label="{{ $ht('cart', 'السلة', 'Cart') }}" title="{{ $ht('cart', 'السلة', 'Cart') }}"><span class="icon" aria-hidden="true">🛒</span>
                    <span class="cart-count" id="cartCountBadge">0</span>
                </button>
                <span class="cart-plus-one" id="cartPlusOne">+1</span>
            </span>
            <a class="btn btn-primary header-cta action-sell" href="{{ $wpLocalizedMyDressesUrl }}" target="_blank" rel="noopener">{{ $ht('start_selling', 'ابدئي البيع', 'Start Selling') }}</a>
        </div>
    </div>
</header>

<script>
    (() => {
        const navToggle = document.getElementById('headerNavToggle');
        const nav = document.getElementById('headerMainNav');
        const langToggle = document.getElementById('topbarLangToggle');
        const langPanel = document.getElementById('topbarLangPanel');
        const socialToggle = document.getElementById('topbarSocialToggle');
        const socialPanel = document.getElementById('topbarSocialPanel');

        const closeNav = () => {
            if (!navToggle || !nav) return;
            nav.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
        };

        const closeSocial = () => {
            if (!socialToggle || !socialPanel) return;
            socialPanel.classList.remove('is-open');
            socialPanel.setAttribute('aria-hidden', 'true');
            socialToggle.setAttribute('aria-expanded', 'false');
        };

        const closeLang = () => {
            if (!langToggle || !langPanel) return;
            langPanel.classList.remove('is-open');
            langPanel.setAttribute('aria-hidden', 'true');
            langToggle.setAttribute('aria-expanded', 'false');
        };

        if (navToggle && nav) {
            navToggle.addEventListener('click', () => {
                const willOpen = !nav.classList.contains('is-open');
                nav.classList.toggle('is-open', willOpen);
                navToggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });

            nav.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', closeNav);
            });
        }

        if (socialToggle && socialPanel) {
            socialToggle.addEventListener('click', () => {
                const willOpen = !socialPanel.classList.contains('is-open');
                socialPanel.classList.toggle('is-open', willOpen);
                socialPanel.setAttribute('aria-hidden', willOpen ? 'false' : 'true');
                socialToggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                if (willOpen) closeLang();
            });

            socialPanel.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', closeSocial);
            });

            document.addEventListener('click', (event) => {
                const wrap = socialToggle.closest('.topbar-mobile-social');
                if (!wrap) return;
                if (!wrap.contains(event.target)) closeSocial();
            });
        }

        if (langToggle && langPanel) {
            langToggle.addEventListener('click', () => {
                const willOpen = !langPanel.classList.contains('is-open');
                langPanel.classList.toggle('is-open', willOpen);
                langPanel.setAttribute('aria-hidden', willOpen ? 'false' : 'true');
                langToggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                if (willOpen) closeSocial();
            });

            langPanel.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', closeLang);
            });

            document.addEventListener('click', (event) => {
                const wrap = langToggle.closest('.topbar-mobile-lang');
                if (!wrap) return;
                if (!wrap.contains(event.target)) closeLang();
            });
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth > 640) {
                closeNav();
                closeSocial();
                closeLang();
            }
        });
    })();
</script>

<div class="promo">{{ $ht('promo_line', 'لأن كل امرأة تستحق أن تتألق • خصومات تصل إلى 50% • توصيل داخل مصر خلال 2–10 أيام عمل', 'Because every woman deserves to shine • Up to 50% OFF • Delivery across Egypt in 2–10 business days') }}</div>

@include('partials.shared-header-categories-strip')

@unless($isHomeRoute)
    @include('partials.shared-home-header-interactions', ['ht' => $ht])
@endunless
