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
    $baseTranslator = (isset($t) && is_callable($t)) ? $t : null;
    $headerTranslator = (isset($ht) && is_callable($ht)) ? $ht : null;

    $modalFallbacks = [
        'cart_title' => ['ar' => 'سلة التسوق', 'en' => 'Shopping Cart'],
        'close' => ['ar' => 'إغلاق', 'en' => 'Close'],
        'subtotal' => ['ar' => 'الإجمالي الفرعي', 'en' => 'Subtotal'],
        'view_cart' => ['ar' => 'عرض السلة', 'en' => 'View Cart'],
        'checkout' => ['ar' => 'الدفع', 'en' => 'Checkout'],
        'login_title' => ['ar' => 'تسجيل الدخول', 'en' => 'Sign In'],
        'login_subtitle' => ['ar' => 'سجلي الدخول للوصول إلى حسابك', 'en' => 'Sign in to access your account'],
        'login_username' => ['ar' => 'اسم المستخدم أو البريد الإلكتروني', 'en' => 'Username or email'],
        'login_password' => ['ar' => 'كلمة المرور', 'en' => 'Password'],
        'remember_me' => ['ar' => 'تذكرني', 'en' => 'Remember me'],
        'forgot_password' => ['ar' => 'نسيت كلمة المرور؟', 'en' => 'Forgot password?'],
        'sign_in' => ['ar' => 'تسجيل الدخول', 'en' => 'Sign In'],
        'sign_in_google' => ['ar' => 'المتابعة عبر Google', 'en' => 'Continue with Google'],
        'register' => ['ar' => 'إنشاء حساب', 'en' => 'Create account'],
        'wishlist_loading' => ['ar' => 'جاري تحميل المفضلة…', 'en' => 'Loading wishlist…'],
        'wishlist_empty' => ['ar' => 'لا توجد عناصر في المفضلة حالياً', 'en' => 'Your wishlist is empty'],
        'go_to_product' => ['ar' => 'الذهاب إلى المنتج', 'en' => 'Go to product'],
        'cart_empty' => ['ar' => 'السلة فارغة حالياً', 'en' => 'Your cart is empty'],
        'loading_cart' => ['ar' => 'جاري تحميل السلة…', 'en' => 'Loading cart…'],
        'remove' => ['ar' => 'حذف', 'en' => 'Remove'],
        'qty_short' => ['ar' => 'الكمية', 'en' => 'Qty'],
        'account_loading' => ['ar' => 'جاري تحميل بيانات الحساب…', 'en' => 'Loading account details…'],
        'account_logged_in' => ['ar' => 'مستخدم مسجل دخول', 'en' => 'Logged-in user'],
    ];

    $translate = static function (string $key) use ($baseTranslator, $headerTranslator, $modalFallbacks, $isEnglish): string {
        $fallback = $modalFallbacks[$key] ?? ['ar' => $key, 'en' => $key];

        if ($baseTranslator) {
            try {
                $value = (string) $baseTranslator($key);
                if ($value !== '' && $value !== $key) {
                    return $value;
                }
            } catch (\Throwable $e) {
            }
        }

        if ($headerTranslator) {
            try {
                return (string) $headerTranslator($key, $fallback['ar'], $fallback['en']);
            } catch (\Throwable $e) {
            }
        }

        return $isEnglish ? $fallback['en'] : $fallback['ar'];
    };
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', 'https://styliiiish.com')), '/');
    $wpMyAccountUrl = $wpMyAccountUrl ?? ($wpBaseUrl . '/my-account/');
    $wpLocalizedAccountUrl = $wpLocalizedAccountUrl ?? ($isEnglish ? ($wpBaseUrl . '/my-account/') : ($wpBaseUrl . '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/'));
    $wpLoginUrl = $wpLoginUrl ?? $wpMyAccountUrl;
    $wpRegisterUrl = $wpRegisterUrl ?? ($wpMyAccountUrl . '?register=1');
    $wpForgotPasswordUrl = $wpForgotPasswordUrl ?? ($wpMyAccountUrl . 'lost-password/');
    $wpCheckoutUrl = $wpCheckoutUrl ?? ($isEnglish ? ($wpBaseUrl . '/en/checkout/') : ($wpBaseUrl . '/ar/الدفع/'));
@endphp

<div class="mini-cart" id="miniCart" aria-hidden="true">
    <div class="mini-cart-backdrop" data-close-mini-cart></div>
    <div class="mini-cart-panel" role="dialog" aria-modal="true" aria-label="{{ $translate('cart_title') }}">
        <div class="mini-cart-head">
            <h3>{{ $translate('cart_title') }}</h3>
            <button class="mini-cart-close" type="button" data-close-mini-cart>{{ $translate('close') }}</button>
        </div>
        <div class="mini-cart-list" id="miniCartList"></div>
        <div class="mini-cart-foot">
            <div class="mini-cart-subtotal"><span>{{ $translate('subtotal') }}</span><strong id="miniCartSubtotal">—</strong></div>
            <div class="mini-cart-actions">
                <a class="mini-cart-view" id="miniCartView" href="{{ $localePrefix }}/cart">{{ $translate('view_cart') }}</a>
                <a class="mini-cart-checkout" id="miniCartCheckout" href="{{ $wpCheckoutUrl }}">{{ $translate('checkout') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="auth-modal" id="authModal" aria-hidden="true">
    <div class="auth-modal-backdrop" data-close-auth-modal></div>
    <section class="auth-modal-panel" role="dialog" aria-modal="true" aria-label="{{ $translate('login_title') }}">
        <div class="auth-head">
            <div class="auth-title-wrap">
                <h3>{{ $translate('login_title') }}</h3>
                <p>{{ $translate('login_subtitle') }}</p>
            </div>
            <button class="auth-close" type="button" data-close-auth-modal aria-label="{{ $translate('close') }}">×</button>
        </div>

        <form class="auth-form" id="authLoginForm" action="{{ $wpLoginUrl }}" method="post" autocomplete="on">
            <input type="hidden" name="redirect" value="{{ $wpMyAccountUrl }}">
            <input type="hidden" name="login" value="Log in">
            <input type="hidden" id="authWooLoginNonce" name="woocommerce-login-nonce" value="">

            <div class="auth-field">
                <label for="authLoginField">{{ $translate('login_username') }}</label>
                <input id="authLoginField" type="text" name="username" required>
            </div>

            <div class="auth-field">
                <label for="authPasswordField">{{ $translate('login_password') }}</label>
                <input id="authPasswordField" type="password" name="password" required>
            </div>

            <div class="auth-row">
                <label class="auth-remember" for="authRememberField">
                    <input id="authRememberField" type="checkbox" name="rememberme" value="forever">
                    <span>{{ $translate('remember_me') }}</span>
                </label>
                <a class="auth-forgot" href="{{ $wpForgotPasswordUrl }}" target="_blank" rel="noopener">{{ $translate('forgot_password') }}</a>
            </div>

            <button class="auth-submit" type="submit">{{ $translate('sign_in') }}</button>

            <div class="auth-divider">or</div>

            <div class="auth-google-wrap">
                <div class="googlesitekit-sign-in-with-google__frontend-output-button" id="authGoogleButton" data-googlesitekit-siwg-shape="pill" data-googlesitekit-siwg-text="continue_with" data-googlesitekit-siwg-theme="filled_blue" aria-label="{{ $translate('sign_in_google') }}"></div>
                <a class="auth-google-fallback" id="authGoogleFallback" href="{{ $wpMyAccountUrl }}" target="_blank" rel="noopener">{{ $translate('sign_in_google') }}</a>
            </div>

            <a class="auth-register" href="{{ $wpRegisterUrl }}" target="_blank" rel="noopener">{{ $translate('register') }}</a>
        </form>
    </section>
</div>

<script>
    (() => {
        const adminAjaxUrl = @json($wpBaseUrl . '/wp-admin/admin-ajax.php');
        const localePrefix = @json($localePrefix);
        const wpCheckoutUrl = @json($wpCheckoutUrl);
        const wpMyAccountUrl = @json($wpMyAccountUrl);
        const wpLocalizedAccountUrl = @json($wpLocalizedAccountUrl);
        const wishlistLoadingText = @json($translate('wishlist_loading'));
        const wishlistEmptyText = @json($translate('wishlist_empty'));
        const goToProductText = @json($translate('go_to_product'));
        const cartEmptyText = @json($translate('cart_empty'));
        const loadingCartText = @json($translate('loading_cart'));
        const removeText = @json($translate('remove'));
        const qtyShortText = @json($translate('qty_short'));
        const accountLoadingText = @json($translate('account_loading'));
        const accountLoggedInText = @json($translate('account_logged_in'));
        const wishlistImageFallback = @json($wpBaseUrl . '/wp-content/uploads/woocommerce-placeholder.webp');
        const wishlistImageUltimateFallback = '/brand/logo.png';

        const wishlistTrigger = document.getElementById('wishlistTrigger');
        const wishlistBadge = document.getElementById('wishlistCountBadge');
        const wishlistPlusOne = document.getElementById('wishlistPlusOne');
        const wishlistDropdown = document.getElementById('wishlistDropdown');
        const wishlistDropdownList = document.getElementById('wishlistDropdownList');

        const cartTrigger = document.getElementById('miniCartTrigger');
        const cartBadge = document.getElementById('cartCountBadge');
        const plusOne = document.getElementById('cartPlusOne');
        const miniCart = document.getElementById('miniCart');
        const miniCartList = document.getElementById('miniCartList');
        const miniCartSubtotal = document.getElementById('miniCartSubtotal');
        const miniCartView = document.getElementById('miniCartView');
        const miniCartCheckout = document.getElementById('miniCartCheckout');
        const miniCartClosers = miniCart ? miniCart.querySelectorAll('[data-close-mini-cart]') : [];

        const accountLoginTrigger = document.getElementById('accountLoginTrigger');
        const accountMenu = document.getElementById('accountMenu');
        const accountMenuName = document.getElementById('accountMenuName');
        const accountMenuMeta = document.getElementById('accountMenuMeta');
        const accountMenuManage = document.getElementById('accountMenuManage');
        const accountMenuLogout = document.getElementById('accountMenuLogout');
        const hasAccountMiniMenu = !!accountMenu;

        const authModal = document.getElementById('authModal');
        const authModalClosers = authModal ? authModal.querySelectorAll('[data-close-auth-modal]') : [];
        const authLoginForm = document.getElementById('authLoginForm');
        const authWooLoginNonce = document.getElementById('authWooLoginNonce');
        const authGoogleButton = document.getElementById('authGoogleButton');
        const authGoogleFallback = document.getElementById('authGoogleFallback');
        const authRedirectField = authLoginForm ? authLoginForm.querySelector('input[name="redirect"]') : null;

        let siteKitGoogleConfig = null;
        let siteKitGoogleInitialized = false;
        let accountSummaryLoaded = false;
        let accountAuthState = 'unknown';
        let currentWishlistCount = 0;
        let currentCartCount = 0;
        let wishlistItemsCache = [];
        let cartPayloadCache = null;
        const analyticsCurrency = 'EGP';
        const disableWishlistRequests = !!window.disableWishlistRequests;

        const getSafeCurrentUrl = () => {
            try {
                const parsed = new URL(window.location.href);
                if (parsed.origin === window.location.origin) return parsed.toString();
            } catch (error) {}
            return wpMyAccountUrl;
        };

        const setSiteKitRedirectCookie = (redirectUrl) => {
            const expires = new Date(Date.now() + (5 * 60 * 1000)).toUTCString();
            document.cookie = `googlesitekit_auth_redirect_to=${redirectUrl};expires=${expires};path=/`;
        };

        const updateBodyScrollLock = () => {
            const isMiniCartOpen = !!(miniCart && miniCart.classList.contains('is-open'));
            const isAuthOpen = !!(authModal && authModal.classList.contains('is-open'));
            document.body.style.overflow = (isMiniCartOpen || isAuthOpen) ? 'hidden' : '';
        };

        const closeAccountMenu = () => {
            if (!accountMenu || !accountLoginTrigger) return;
            accountMenu.classList.remove('is-open');
            accountMenu.setAttribute('aria-hidden', 'true');
            accountLoginTrigger.setAttribute('aria-expanded', 'false');
        };

        const openAccountMenu = () => {
            if (!accountMenu || !accountLoginTrigger) return;
            accountMenu.classList.add('is-open');
            accountMenu.setAttribute('aria-hidden', 'false');
            accountLoginTrigger.setAttribute('aria-expanded', 'true');
        };

        const openMiniCart = () => {
            if (!miniCart) return;
            miniCart.classList.add('is-open');
            miniCart.setAttribute('aria-hidden', 'false');
            updateBodyScrollLock();
        };

        const closeMiniCart = () => {
            if (!miniCart) return;
            miniCart.classList.remove('is-open');
            miniCart.setAttribute('aria-hidden', 'true');
            updateBodyScrollLock();
        };

        const openAuthModal = () => {
            if (!authModal) return;
            authModal.classList.add('is-open');
            authModal.setAttribute('aria-hidden', 'false');
            if (accountLoginTrigger) accountLoginTrigger.setAttribute('aria-expanded', 'true');
            updateBodyScrollLock();
        };

        const closeAuthModal = () => {
            if (!authModal) return;
            authModal.classList.remove('is-open');
            authModal.setAttribute('aria-hidden', 'true');
            if (accountLoginTrigger) accountLoginTrigger.setAttribute('aria-expanded', 'false');
            updateBodyScrollLock();
        };

        const escapeHtml = (value) => String(value ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');

        const setWishlistCount = (count) => {
            currentWishlistCount = Math.max(0, Number(count) || 0);
            if (!wishlistBadge) return;
            wishlistBadge.textContent = String(currentWishlistCount);
            wishlistBadge.style.display = currentWishlistCount > 0 ? 'inline-block' : 'none';
        };

        const setCartCount = (count) => {
            currentCartCount = Math.max(0, Number(count) || 0);
            if (!cartBadge) return;
            cartBadge.textContent = String(currentCartCount);
            cartBadge.style.display = currentCartCount > 0 ? 'inline-block' : 'none';
        };

        const animatePlusOne = (node) => {
            if (!node) return;
            node.classList.remove('show');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    node.classList.add('show');
                });
            });
        };

        const parseAccountSummary = (doc) => {
            const summary = { name: accountLoggedInText, meta: '', logoutUrl: wpMyAccountUrl };
            if (!doc) return summary;

            const firstName = doc.querySelector('input[name="account_first_name"]')?.value?.trim() || '';
            const lastName = doc.querySelector('input[name="account_last_name"]')?.value?.trim() || '';
            const displayName = doc.querySelector('input[name="account_display_name"]')?.value?.trim() || '';
            const username = doc.querySelector('input[name="account_username"]')?.value?.trim() || '';
            const email = doc.querySelector('input[name="account_email"]')?.value?.trim() || '';
            const logoutUrl = doc.querySelector('a[href*="customer-logout"]')?.getAttribute('href') || '';
            const accountText = doc.querySelector('.woocommerce-MyAccount-content')?.textContent || '';

            const englishHelloMatch = accountText.match(/Hello\s+([^\(\!\n\r]+)\s*(?:\(|\!|,|\.|$)/i);
            const arabicHelloMatch = accountText.match(/مرحب(?:ا|اً|ًا)?\s+([^\(\!\n\r،,\.]+)\s*(?:\(|\!|،|,|\.|$)/i);
            const helloName = (englishHelloMatch?.[1] || arabicHelloMatch?.[1] || '').trim();

            const mergedName = [firstName, lastName].filter(Boolean).join(' ').trim();
            summary.name = mergedName || displayName || helloName || username || summary.name;
            summary.meta = email || username || accountLoggedInText;
            summary.logoutUrl = logoutUrl || summary.logoutUrl;
            return summary;
        };

        const isLoggedInFromAccountDoc = (doc) => !!(doc && (doc.querySelector('a[href*="customer-logout"]') || doc.querySelector('form.woocommerce-EditAccountForm') || doc.querySelector('input[name="account_email"]')));

        const applyAccountSummary = (summary) => {
            if (!summary) return;
            if (accountMenuName) accountMenuName.textContent = summary.name || accountLoggedInText;
            if (accountMenuMeta) accountMenuMeta.textContent = summary.meta || accountLoggedInText;
            if (accountMenuManage) accountMenuManage.href = wpLocalizedAccountUrl;
            if (accountMenuLogout) accountMenuLogout.href = summary.logoutUrl || wpMyAccountUrl;
        };

        const loadAccountSummary = async (forceReload = false) => {
            if (!hasAccountMiniMenu) return false;
            if (!forceReload && accountSummaryLoaded && accountAuthState === 'logged-in') return true;
            if (accountMenuName) accountMenuName.textContent = accountLoadingText;
            if (accountMenuMeta) accountMenuMeta.textContent = accountLoggedInText;

            const editAccountResponse = await fetch(`${wpMyAccountUrl}edit-account/?_=${Date.now()}`, { method: 'GET', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!editAccountResponse.ok) return false;

            const html = await editAccountResponse.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            if (!isLoggedInFromAccountDoc(doc)) {
                accountAuthState = 'guest';
                accountSummaryLoaded = false;
                return false;
            }

            const summary = parseAccountSummary(doc);
            applyAccountSummary(summary);
            accountSummaryLoaded = true;
            accountAuthState = 'logged-in';
            return true;
        };

        const extractSiteKitGoogleConfig = (doc) => {
            if (!doc) return null;
            const scripts = Array.from(doc.querySelectorAll('script'));

            const decodeGoogleInlineValue = (value) => String(value || '')
                .replace(/\\\//g, '/')
                .replace(/\\u0026/gi, '&')
                .trim();

            const isValidGoogleClientId = (clientId) => /^\d+-[a-z0-9-]+\.apps\.googleusercontent\.com$/i.test(String(clientId || '').trim());

            for (const scriptTag of scripts) {
                const text = String(scriptTag.textContent || '');
                if (!text.includes('googlesitekit_auth') || !text.includes('google.accounts.id.initialize')) continue;

                const endpointMatch = text.match(/fetch\(\s*(['"])([^'"]*action=googlesitekit_auth[^'"]*)\1/);
                const clientMatch = text.match(/client_id\s*:\s*(['"])([^'"]+)\1/);
                if (!endpointMatch || !clientMatch) continue;

                const endpoint = decodeGoogleInlineValue(endpointMatch[2]);
                const clientId = decodeGoogleInlineValue(clientMatch[2]);
                if (!endpoint || !isValidGoogleClientId(clientId)) continue;

                return { endpoint, clientId };
            }
            return null;
        };

        const loadGoogleIdentityScript = async () => {
            if (window.google?.accounts?.id) return;
            const existingScript = document.querySelector('script[src="https://accounts.google.com/gsi/client"]');
            if (existingScript) {
                await new Promise((resolve) => {
                    if (window.google?.accounts?.id) return resolve();
                    existingScript.addEventListener('load', resolve, { once: true });
                    existingScript.addEventListener('error', resolve, { once: true });
                });
                return;
            }
            await new Promise((resolve) => {
                const script = document.createElement('script');
                script.src = 'https://accounts.google.com/gsi/client';
                script.async = true;
                script.defer = true;
                script.onload = resolve;
                script.onerror = resolve;
                document.head.appendChild(script);
            });
        };

        const initGoogleButton = async () => {
            if (!authGoogleButton || !siteKitGoogleConfig) return;
            if (!/^\d+-[a-z0-9-]+\.apps\.googleusercontent\.com$/i.test(String(siteKitGoogleConfig.clientId || '').trim())) {
                if (authGoogleFallback) authGoogleFallback.style.display = 'inline-flex';
                return;
            }
            await loadGoogleIdentityScript();
            if (!window.google?.accounts?.id) return;

            const endpointUrl = String(siteKitGoogleConfig.endpoint || '');
            if (!endpointUrl) return;

            let absoluteEndpoint = '';
            try {
                absoluteEndpoint = endpointUrl.startsWith('http') ? endpointUrl : new URL(endpointUrl, wpMyAccountUrl).toString();
            } catch (error) {
                return;
            }

            const handleGoogleCredentialResponse = async (response) => {
                response.integration = 'woocommerce';
                const redirectTarget = getSafeCurrentUrl();
                setSiteKitRedirectCookie(redirectTarget);
                try {
                    const result = await fetch(absoluteEndpoint, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(response), credentials: 'same-origin' });
                    if (result.ok && result.redirected) return location.assign(result.url);
                } catch (error) {}
                location.assign(redirectTarget);
            };

            if (!siteKitGoogleInitialized) {
                window.google.accounts.id.initialize({ client_id: siteKitGoogleConfig.clientId, callback: handleGoogleCredentialResponse, library_name: 'Site-Kit' });
                siteKitGoogleInitialized = true;
            }

            authGoogleButton.innerHTML = '';
            window.google.accounts.id.renderButton(authGoogleButton, { shape: authGoogleButton.getAttribute('data-googlesitekit-siwg-shape') || 'pill', text: authGoogleButton.getAttribute('data-googlesitekit-siwg-text') || 'continue_with', theme: authGoogleButton.getAttribute('data-googlesitekit-siwg-theme') || 'filled_blue' });
            if (authGoogleFallback) authGoogleFallback.style.display = 'none';
        };

        const fetchWooLoginNonce = async () => {
            if (!hasAccountMiniMenu) return;
            if (!authWooLoginNonce) return;
            if (authWooLoginNonce.value && siteKitGoogleConfig) return;
            const response = await fetch(`${wpMyAccountUrl}?_=${Date.now()}`, { method: 'GET', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) return;
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const nonceField = doc.querySelector('input[name="woocommerce-login-nonce"]');
            if (nonceField?.value) authWooLoginNonce.value = nonceField.value;
            const extractedConfig = extractSiteKitGoogleConfig(doc);
            if (extractedConfig) {
                siteKitGoogleConfig = extractedConfig;
                await initGoogleButton();
            }
        };

        const renderWishlistDropdown = (items = []) => {
            if (!wishlistDropdownList) return;
            const safeItems = Array.isArray(items) ? items : [];
            if (safeItems.length === 0) {
                wishlistDropdownList.innerHTML = `<p class="wishlist-dropdown-empty">${escapeHtml(wishlistEmptyText)}</p>`;
                return;
            }
            wishlistDropdownList.innerHTML = safeItems.map((item) => {
                const safeFallback = escapeHtml(wishlistImageFallback || wishlistImageUltimateFallback);
                const image = escapeHtml(item.image || wishlistImageFallback || wishlistImageUltimateFallback);
                const name = escapeHtml(item.name || '');
                const url = escapeHtml(item.url || '#');
                return `<article class="wishlist-dropdown-item"><a href="${url}"><img src="${image}" alt="${name}" onerror="this.onerror=null;this.src='${safeFallback}';"></a><div><h4 class="wishlist-dropdown-name">${name}</h4><a class="wishlist-dropdown-link" href="${url}">${escapeHtml(goToProductText)}</a></div></article>`;
            }).join('');
        };

        const loadWishlistItems = async (forceReload = false) => {
            if (!wishlistDropdownList) return wishlistItemsCache;
            if (disableWishlistRequests) {
                setWishlistCount(0);
                wishlistItemsCache = [];
                renderWishlistDropdown(wishlistItemsCache);
                return wishlistItemsCache;
            }
            if (!forceReload && wishlistItemsCache.length > 0) {
                renderWishlistDropdown(wishlistItemsCache);
                return wishlistItemsCache;
            }
            wishlistDropdownList.innerHTML = `<p class="wishlist-dropdown-empty">${escapeHtml(wishlistLoadingText)}</p>`;
            const response = await fetch(`${localePrefix}/item/wishlist/items?_=${Date.now()}`, { method: 'GET', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('wishlist_items_failed');
            const result = await response.json();
            if (!result?.success) throw new Error('wishlist_items_failed');
            setWishlistCount(Math.max(0, Number(result.count || 0)));
            wishlistItemsCache = Array.isArray(result.items) ? result.items : [];
            renderWishlistDropdown(wishlistItemsCache);
            return wishlistItemsCache;
        };

        const refreshWishlistCount = async (withAnimation = false) => {
            if (disableWishlistRequests) {
                setWishlistCount(0);
                return;
            }
            const response = await fetch(`${localePrefix}/item/wishlist/count?_=${Date.now()}`, { method: 'GET', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('wishlist_count_failed');
            const result = await response.json();
            if (!result?.success) throw new Error('wishlist_count_failed');
            const nextCount = Math.max(0, Number(result.count || 0));
            const shouldAnimate = withAnimation && nextCount > currentWishlistCount;
            setWishlistCount(nextCount);
            if (shouldAnimate) animatePlusOne(wishlistPlusOne);
        };

        const resolveCountFromPayload = (payload) => {
            if (!payload) return 0;
            const items = Array.isArray(payload.items) ? payload.items : [];
            if (items.length > 0) return items.reduce((total, item) => total + Math.max(0, Number(item.qty || 0)), 0);
            return Math.max(0, Number(payload.count || 0));
        };

        const parseNumericPrice = (value) => {
            const numeric = Number.parseFloat(String(value || '').replace(/[^\d.-]/g, ''));
            return Number.isFinite(numeric) ? Math.max(0, numeric) : 0;
        };

        const buildEcommerceItemsFromPayload = (payload) => {
            const items = Array.isArray(payload?.items) ? payload.items : [];
            return items.map((item, index) => {
                const unitPrice = parseNumericPrice(item?.price_html || item?.line_total_html || '');
                const quantity = Math.max(1, Number(item?.qty) || 1);

                return {
                    item_id: String(item?.product_id || item?.id || item?.key || `cart_item_${index + 1}`),
                    item_name: String(item?.name || `Product ${index + 1}`),
                    price: unitPrice > 0 ? unitPrice : undefined,
                    quantity,
                };
            });
        };

        const trackBeginCheckout = () => {
            if (typeof window.styliiiishTrackEvent !== 'function') {
                return;
            }

            const payload = cartPayloadCache || {};
            const items = buildEcommerceItemsFromPayload(payload);
            const value = parseNumericPrice(payload?.total_html || payload?.subtotal_html || '');

            const eventPayload = {
                currency: analyticsCurrency,
                items,
            };

            if (value > 0) {
                eventPayload.value = value;
            }

            window.styliiiishTrackEvent('begin_checkout', eventPayload);
        };

        const renderMiniCart = (payload) => {
            if (!payload) return;
            cartPayloadCache = payload;
            const count = resolveCountFromPayload(payload);
            const shouldAnimate = count > currentCartCount;
            setCartCount(count);
            if (shouldAnimate) animatePlusOne(plusOne);
            if (miniCartSubtotal) miniCartSubtotal.innerHTML = payload.subtotal_html || '—';
            if (miniCartView) miniCartView.href = `${localePrefix}/cart`;
            if (miniCartCheckout) miniCartCheckout.href = wpCheckoutUrl;
            if (!miniCartList) return;
            const items = Array.isArray(payload.items) ? payload.items : [];
            if (items.length === 0) {
                miniCartList.innerHTML = `<p class="mini-cart-empty">${escapeHtml(cartEmptyText)}</p>`;
                return;
            }
            miniCartList.innerHTML = items.map((item) => `<article class="mini-cart-item"><a href="${escapeHtml(item.url || '#')}"><img src="${escapeHtml(item.image || '')}" alt="${escapeHtml(item.name || '')}"></a><div class="mini-cart-info"><h4>${escapeHtml(item.name || '')}</h4><div class="mini-cart-meta"><span>${escapeHtml(qtyShortText)}:</span><strong>${Number(item.qty || 1)}</strong></div><div class="mini-cart-price">${item.line_total_html || item.price_html || ''}</div></div><button type="button" class="mini-cart-remove" data-remove-cart-key="${escapeHtml(item.key || '')}">${escapeHtml(removeText)}</button></article>`).join('');
        };

        const getCartSummary = async () => {
            const response = await fetch(`${adminAjaxUrl}?action=styliiiish_cart_summary`, { method: 'GET', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('summary_failed');
            const result = await response.json();
            if (!result?.success) throw new Error('summary_failed');
            renderMiniCart(result.data);
        };

        if (wishlistTrigger) {
            wishlistTrigger.addEventListener('click', async (event) => {
                event.preventDefault();
                if (!wishlistDropdown) return;
                if (wishlistDropdown.classList.contains('is-open')) {
                    wishlistDropdown.classList.remove('is-open');
                    wishlistDropdown.setAttribute('aria-hidden', 'true');
                    wishlistTrigger.setAttribute('aria-expanded', 'false');
                    return;
                }
                closeAccountMenu();
                wishlistDropdown.classList.add('is-open');
                wishlistDropdown.setAttribute('aria-hidden', 'false');
                wishlistTrigger.setAttribute('aria-expanded', 'true');
                try { await loadWishlistItems(true); } catch (error) { renderWishlistDropdown([]); }
            });
        }

        document.addEventListener('click', (event) => {
            if (wishlistDropdown && wishlistTrigger && !wishlistTrigger.contains(event.target) && !wishlistDropdown.contains(event.target)) {
                wishlistDropdown.classList.remove('is-open');
                wishlistDropdown.setAttribute('aria-hidden', 'true');
                wishlistTrigger.setAttribute('aria-expanded', 'false');
            }
            if (hasAccountMiniMenu && accountMenu && accountLoginTrigger && !accountLoginTrigger.contains(event.target) && !accountMenu.contains(event.target)) {
                closeAccountMenu();
            }
        });

        if (cartTrigger) {
            cartTrigger.addEventListener('click', () => {
                closeAccountMenu();
                openMiniCart();
                if (cartPayloadCache) {
                    renderMiniCart(cartPayloadCache);
                } else if (miniCartList && miniCartList.innerHTML.trim() === '') {
                    miniCartList.innerHTML = `<div class="mini-cart-loading">${escapeHtml(loadingCartText)}</div>`;
                }
                getCartSummary().catch(() => {});
            });
        }

        if (miniCartClosers.length > 0) miniCartClosers.forEach((node) => node.addEventListener('click', closeMiniCart));

        if (miniCartCheckout) {
            miniCartCheckout.addEventListener('click', () => {
                trackBeginCheckout();
            });
        }

        if (accountLoginTrigger && hasAccountMiniMenu) {
            accountLoginTrigger.addEventListener('click', async (event) => {
                if (event) event.preventDefault();
                if (accountMenu?.classList.contains('is-open')) return closeAccountMenu();
                let isLoggedIn = accountAuthState === 'logged-in';
                if (!isLoggedIn) isLoggedIn = await loadAccountSummary(true).catch(() => false);
                if (isLoggedIn) return openAccountMenu();
                if (authRedirectField) authRedirectField.value = getSafeCurrentUrl();
                openAuthModal();
                fetchWooLoginNonce().catch(() => {});
                const firstField = authModal ? authModal.querySelector('input[name="username"]') : null;
                if (firstField) setTimeout(() => firstField.focus(), 40);
            });
        }

        if (authModalClosers.length > 0) authModalClosers.forEach((node) => node.addEventListener('click', closeAuthModal));

        if (authLoginForm) {
            authLoginForm.addEventListener('submit', async () => {
                if (authRedirectField) authRedirectField.value = getSafeCurrentUrl();
                await fetchWooLoginNonce().catch(() => {});
            });
        }

        if (miniCartList) {
            miniCartList.addEventListener('click', async (event) => {
                const removeBtn = event.target.closest('[data-remove-cart-key]');
                if (!removeBtn) return;
                const cartKey = removeBtn.getAttribute('data-remove-cart-key') || '';
                if (!cartKey) return;

                const params = new URLSearchParams();
                params.set('action', 'styliiiish_remove_from_cart');
                params.set('cart_key', cartKey);

                const response = await fetch(adminAjaxUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8', 'X-Requested-With': 'XMLHttpRequest' }, body: params.toString() });
                const result = await response.json();
                if (response.ok && result?.success) renderMiniCart(result.data);
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            if (miniCart?.classList.contains('is-open')) closeMiniCart();
            if (wishlistDropdown?.classList.contains('is-open')) {
                wishlistDropdown.classList.remove('is-open');
                wishlistDropdown.setAttribute('aria-hidden', 'true');
                wishlistTrigger?.setAttribute('aria-expanded', 'false');
            }
            if (authModal?.classList.contains('is-open')) closeAuthModal();
            if (accountMenu?.classList.contains('is-open')) closeAccountMenu();
        });

        const runDeferredHeaderRequests = () => {
            setCartCount(0);
            setWishlistCount(0);

            const execute = () => {
                getCartSummary().catch(() => setCartCount(0));
                refreshWishlistCount(false).catch(() => {});
            };

            if ('requestIdleCallback' in window) {
                window.requestIdleCallback(execute, { timeout: 2500 });
                return;
            }

            setTimeout(execute, 1600);
        };

        if (document.readyState === 'complete') {
            runDeferredHeaderRequests();
        } else {
            window.addEventListener('load', runDeferredHeaderRequests, { once: true });
        }
    })();
</script>
