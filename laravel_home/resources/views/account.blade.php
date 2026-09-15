<!DOCTYPE html>
@php
    $currentLocale = $currentLocale ?? 'ar';
    $localePrefix = $localePrefix ?? '/ar';
    $isEnglish = $currentLocale === 'en';
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? (config('styliiiish.wp_public_url') ?: request()->getSchemeAndHttpHost())), '/');
    $wpLogo = $wpBaseUrl . '/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpIcon = $wpBaseUrl . '/wp-content/uploads/2025/11/cropped-ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpAccountUrl = $isEnglish ? ($wpBaseUrl . '/my-account/') : ($wpBaseUrl . '/ar/%d8%ad%d8%b3%d8%a7%d8%a8%d9%8a/');
    $canonicalPath = $isEnglish ? '/en/my-account' : '/ar/my-account';
    $seoUrl = $wpBaseUrl . $canonicalPath;

    $translations = [
        'ar' => [
            'meta_title' => 'حسابي | إدارة الطلبات والعناوين والبيانات | Styliiiish',
            'meta_desc' => 'ادخلي إلى حسابك في Styliiiish لإدارة الطلبات والعناوين وبيانات الحساب بسهولة. صفحة حساب خاصة بالمستخدمين المسجلين.',
            'page_title' => 'حسابي | ستايلش',
            'dashboard' => 'لوحة التحكم',
            'orders' => 'الطلبات',
            'addresses' => 'العناوين',
            'account_details' => 'تفاصيل الحساب',
            'saved_cards' => 'البطاقات المحفوظة',
            'logout' => 'تسجيل الخروج',
            'welcome' => 'مرحباً بك، ',
            'dashboard_desc' => 'من خلال لوحة تحكم حسابك، يمكنك استعراض أحدث طلباتك، إدارة عناوين الشحن والفواتير الخاصة بك، وتعديل كلمة المرور وتفاصيل حسابك.',
            'no_orders' => 'لم تقم بأي طلبات بعد.',
            'order_number' => 'الطلب #',
            'date' => 'التاريخ',
            'status' => 'الحالة',
            'total' => 'الإجمالي',
            'actions' => 'إجراءات',
            'view' => 'عرض',
            'billing_address' => 'عنوان الفاتورة',
            'shipping_address' => 'عنوان الشحن',
            'edit' => 'تعديل',
            'save_changes' => 'حفظ التغييرات',
            'first_name' => 'الاسم الأول',
            'last_name' => 'الاسم الأخير',
            'display_name' => 'اسم العرض',
            'email' => 'البريد الإلكتروني',
            'password_change' => 'تغيير كلمة المرور',
            'current_password' => 'كلمة المرور الحالية (اتركها فارغة لعدم التغيير)',
            'new_password' => 'كلمة المرور الجديدة',
            'confirm_password' => 'تأكيد كلمة المرور الجديدة',
            'no_cards' => 'لا توجد بطاقات محفوظة.',
            'card_ending' => 'بطاقة تنتهي بـ',
            'delete' => 'حذف',
            'loading' => 'جاري التحميل...',
            'error_loading' => 'حدث خطأ أثناء تحميل البيانات. يرجى تسجيل الدخول.',
            'login_required' => 'يجب تسجيل الدخول للوصول إلى هذه الصفحة.',
            'go_to_login' => 'تسجيل الدخول',
            'success_update' => 'تم التحديث بنجاح!',
            'guest_auth_title' => 'سجّل الدخول أو أنشئ حسابًا جديدًا',
            'guest_login_tab' => 'تسجيل الدخول',
            'guest_register_tab' => 'إنشاء حساب',
            'login_username' => 'اسم المستخدم أو البريد الإلكتروني',
            'login_password' => 'كلمة المرور',
            'remember_me' => 'تذكرني',
            'sign_in' => 'تسجيل الدخول',
            'register_username' => 'اسم المستخدم',
            'register_email' => 'البريد الإلكتروني',
            'create_account' => 'إنشاء حساب',
            'sign_in_google' => 'المتابعة عبر Google',
            'password_mismatch' => 'كلمتا المرور غير متطابقتين.',
            'auth_failed' => 'تعذر تسجيل الدخول. تحقق من البيانات وحاول مرة أخرى.',
            'register_disabled' => 'إنشاء الحساب غير متاح حالياً.',
        ],
        'en' => [
            'meta_title' => 'My Account | Manage Orders, Addresses & Profile | Styliiiish',
            'meta_desc' => 'Access your Styliiiish account to manage orders, addresses, and account details. This page is intended for logged-in users.',
            'page_title' => 'My Account | Styliiiish',
            'dashboard' => 'Dashboard',
            'orders' => 'Orders',
            'addresses' => 'Addresses',
            'account_details' => 'Account Details',
            'saved_cards' => 'Saved Cards',
            'logout' => 'Logout',
            'welcome' => 'Hello, ',
            'dashboard_desc' => 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.',
            'no_orders' => 'No order has been made yet.',
            'order_number' => 'Order #',
            'date' => 'Date',
            'status' => 'Status',
            'total' => 'Total',
            'actions' => 'Actions',
            'view' => 'View',
            'billing_address' => 'Billing Address',
            'shipping_address' => 'Shipping Address',
            'edit' => 'Edit',
            'save_changes' => 'Save changes',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'display_name' => 'Display name',
            'email' => 'Email address',
            'password_change' => 'Password change',
            'current_password' => 'Current password (leave blank to leave unchanged)',
            'new_password' => 'New password',
            'confirm_password' => 'Confirm new password',
            'no_cards' => 'No saved cards found.',
            'card_ending' => 'Card ending in',
            'delete' => 'Delete',
            'loading' => 'Loading...',
            'error_loading' => 'Error loading data. Please log in.',
            'login_required' => 'You must be logged in to access this page.',
            'go_to_login' => 'Log in',
            'success_update' => 'Updated successfully!',
            'guest_auth_title' => 'Log in or create a new account',
            'guest_login_tab' => 'Sign In',
            'guest_register_tab' => 'Create account',
            'login_username' => 'Username or email',
            'login_password' => 'Password',
            'remember_me' => 'Remember me',
            'sign_in' => 'Sign In',
            'register_username' => 'Username',
            'register_email' => 'Email address',
            'create_account' => 'Create account',
            'sign_in_google' => 'Continue with Google',
            'password_mismatch' => 'Passwords do not match.',
            'auth_failed' => 'Unable to sign in. Please check your credentials and try again.',
            'register_disabled' => 'Registration is currently unavailable.',
        ],
    ];

    $t = fn (string $key) => $translations[$currentLocale][$key] ?? $key;
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, follow">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $seoUrl }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/my-account">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/my-account">
    <link rel="alternate" hreflang="x-default" href="{{ $wpBaseUrl }}/ar/my-account">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $isEnglish ? 'Styliiiish' : 'ستايلش' }}">
    <meta property="og:locale" content="{{ $isEnglish ? 'en_US' : 'ar_EG' }}">
    <meta property="og:locale:alternate" content="{{ $isEnglish ? 'ar_EG' : 'en_US' }}">
    <meta property="og:title" content="{{ $t('meta_title') }}">
    <meta property="og:description" content="{{ $t('meta_desc') }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $wpIcon }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $t('meta_title') }}">
    <meta name="twitter:description" content="{{ $t('meta_desc') }}">
    <meta name="twitter:image" content="{{ $wpIcon }}">
    <title>{{ $t('meta_title') }}</title>
    @include('partials.shared-seo-meta')
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $wpIcon }}">
    <link rel="apple-touch-icon" href="{{ $wpIcon }}">
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
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: radial-gradient(1200px 380px at 50% -120px, rgba(var(--wf-main-rgb), .08), transparent 60%), linear-gradient(180deg, #f9fbff 0%, var(--bg) 30%, #f3f6fb 100%);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1180px, 92%); margin: 0 auto; }
        
        .account-wrap {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            padding: 40px 0 60px;
            min-height: 60vh;
        }

        .account-nav {
            background: rgba(255,255,255,0.8);
            border: 1px solid rgba(23,39,59,.08);
            border-radius: 20px;
            padding: 20px 10px;
            box-shadow: 0 10px 30px rgba(23,39,59,.04);
            backdrop-filter: blur(10px);
            align-self: start;
            position: sticky;
            top: 120px;
        }

        .account-nav button, .account-nav a {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 14px 20px;
            background: transparent;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            color: var(--muted);
            text-align: start;
            cursor: pointer;
            transition: all .3s ease;
            font-family: inherit;
        }

        .account-nav button:hover, .account-nav a:hover {
            background: rgba(23,39,59,.04);
            color: var(--secondary);
        }

        .account-nav button.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 8px 20px rgba(var(--wf-main-rgb), .2);
        }

        .account-content {
            background: #fff;
            border: 1px solid rgba(23,39,59,.08);
            border-radius: 20px;
            padding: 34px;
            box-shadow: 0 14px 38px rgba(23,39,59,.06);
            position: relative;
            overflow: hidden;
        }

        .tab-pane {
            display: none;
            animation: fadeUp .4s ease both;
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { margin: 0 0 20px; font-size: 24px; color: var(--secondary); }
        p { line-height: 1.7; color: var(--muted); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 14px; text-align: start; border-bottom: 1px solid var(--line); }
        th { font-weight: 700; color: var(--secondary); background: #f9fbff; }
        td { color: var(--muted); font-size: 14px; }
        
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 700;
            border: 1px solid transparent; cursor: pointer; transition: all .2s ease;
            font-family: inherit;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { box-shadow: 0 8px 20px rgba(var(--wf-main-rgb), .25); transform: translateY(-1px); }
        .btn-light { background: #fff; color: var(--secondary); border-color: var(--line); }
        .btn-light:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger { background: #fff0f0; color: #d51522; border-color: #ffd6d6; }
        .btn-danger:hover { background: #d51522; color: #fff; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--secondary); }
        .form-control {
            width: 100%; padding: 12px 16px; border: 1px solid var(--line);
            border-radius: 10px; font-size: 15px; font-family: inherit;
            transition: border-color .2s ease, box-shadow .2s ease;
            background: #fbfcff;
        }
        .form-control:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(var(--wf-main-rgb), .1);
            background: #fff;
        }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .address-card {
            border: 1px solid var(--line); border-radius: 14px; padding: 20px;
            background: #fbfcff;
        }
        .address-card h3 { margin: 0 0 14px; font-size: 18px; }
        .address-card address { font-style: normal; color: var(--muted); line-height: 1.8; margin-bottom: 16px; }

        .card-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px; border: 1px solid var(--line); border-radius: 12px;
            margin-bottom: 12px; background: #fff;
        }
        .card-info { display: flex; align-items: center; gap: 12px; font-weight: 600; }
        .card-icon { font-size: 24px; }

        .loader {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 60px 0; color: var(--muted); gap: 16px;
        }
        .spinner {
            width: 40px; height: 40px; border: 3px solid rgba(var(--wf-main-rgb), .2);
            border-top-color: var(--primary); border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .alert { padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; font-size: 14px; display: none; }
        .alert-success { background: #e6f9f0; color: #0a8f5b; border: 1px solid #b3ebd2; }
        .alert-error { background: #fff0f0; color: #d51522; border: 1px solid #ffd6d6; }

        .guest-auth-tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .guest-auth-tab {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 16px;
            background: #fff;
            color: var(--secondary);
            font-weight: 700;
            cursor: pointer;
        }
        .guest-auth-tab.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .guest-auth-pane { display: none; }
        .guest-auth-pane.active { display: block; }
        .guest-auth-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .guest-auth-google { margin-top: 16px; }
        .guest-auth-divider { text-align: center; color: var(--muted); margin: 14px 0; }
        .auth-google-fallback { display: inline-flex; align-items: center; justify-content: center; width: 100%; }

        @media (max-width: 900px) {
            .account-wrap { grid-template-columns: 1fr; }
            .account-nav {
                position: static; display: flex; overflow-x: auto;
                padding: 10px; border-radius: 14px; scrollbar-width: none;
            }
            .account-nav button, .account-nav a { width: auto; white-space: nowrap; padding: 10px 16px; }
            .grid-2 { grid-template-columns: 1fr; }
            .guest-auth-grid { grid-template-columns: 1fr; }
            .account-content { padding: 20px; }
        }
    </style>
    <script>
        window.disableWishlistRequests = true;
    </script>
    @include('partials.shared-home-header-styles')
</head>
<body>
@include('partials.shared-home-header')

<main class="container account-wrap" id="accountApp">
    <div class="loader" id="mainLoader">
        <div class="spinner"></div>
        <span>{{ $t('loading') }}</span>
    </div>

    <div id="guestAuth" style="display: none; grid-column: 1 / -1;">
        <section class="account-content" style="max-width: 960px; margin: 0 auto;">
            <h2>{{ $t('guest_auth_title') }}</h2>
            <div class="alert alert-error" id="guestAuthError"></div>

            <div class="guest-auth-tabs">
                <button type="button" class="guest-auth-tab active" data-auth-tab="login">{{ $t('guest_login_tab') }}</button>
                <button type="button" class="guest-auth-tab" data-auth-tab="register" id="guestRegisterTab">{{ $t('guest_register_tab') }}</button>
            </div>

            <div class="guest-auth-pane active" id="guestAuthPane-login">
                <form id="guestLoginForm" class="guest-auth-grid" autocomplete="on" action="{{ $wpAccountUrl }}" method="post">
                    <input type="hidden" id="guestLoginNonce" name="woocommerce-login-nonce" value="">
                    <input type="hidden" id="guestLoginRedirect" name="redirect" value="{{ request()->fullUrl() }}">
                    <input type="hidden" name="login" value="Log in">
                    <div class="form-group">
                        <label for="guestLoginUsername">{{ $t('login_username') }}</label>
                        <input id="guestLoginUsername" class="form-control" type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="guestLoginPassword">{{ $t('login_password') }}</label>
                        <input id="guestLoginPassword" class="form-control" type="password" name="password" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1; margin-top: -8px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; margin: 0; font-weight: 600; color: var(--muted);">
                            <input id="guestRemember" type="checkbox" name="rememberme" value="forever">
                            <span>{{ $t('remember_me') }}</span>
                        </label>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1; margin-bottom: 0;">
                        <button class="btn btn-primary" id="guestLoginSubmit" type="submit">{{ $t('sign_in') }}</button>
                    </div>
                </form>
            </div>

            <div class="guest-auth-pane" id="guestAuthPane-register">
                <form id="guestRegisterForm" class="guest-auth-grid" autocomplete="on" action="{{ $wpAccountUrl }}" method="post">
                    <input type="hidden" id="guestRegisterNonce" name="woocommerce-register-nonce" value="">
                    <input type="hidden" id="guestRegisterRedirect" name="redirect" value="{{ request()->fullUrl() }}">
                    <input type="hidden" name="register" value="Register">
                    <div class="form-group">
                        <label for="guestRegisterUsername">{{ $t('register_username') }}</label>
                        <input id="guestRegisterUsername" class="form-control" type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="guestRegisterEmail">{{ $t('register_email') }}</label>
                        <input id="guestRegisterEmail" class="form-control" type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="guestRegisterPassword">{{ $t('new_password') }}</label>
                        <input id="guestRegisterPassword" class="form-control" type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="guestRegisterPasswordConfirm">{{ $t('confirm_password') }}</label>
                        <input id="guestRegisterPasswordConfirm" class="form-control" type="password" required>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1; margin-bottom: 0;">
                        <button class="btn btn-primary" id="guestRegisterSubmit" type="submit">{{ $t('create_account') }}</button>
                    </div>
                </form>
            </div>

            <div class="guest-auth-divider">or</div>
            <div class="guest-auth-google">
                <div class="googlesitekit-sign-in-with-google__frontend-output-button" id="accountGoogleButton" data-googlesitekit-siwg-shape="pill" data-googlesitekit-siwg-text="continue_with" data-googlesitekit-siwg-theme="filled_blue" aria-label="{{ $t('sign_in_google') }}"></div>
                <a class="btn btn-light auth-google-fallback" id="accountGoogleFallback" href="{{ $wpAccountUrl }}">{{ $t('sign_in_google') }}</a>
            </div>
        </section>
    </div>

    <aside class="account-nav" id="accountNav" style="display: none;">
        <button class="active" data-target="dashboard">{{ $t('dashboard') }}</button>
        <button data-target="orders">{{ $t('orders') }}</button>
        <button data-target="addresses">{{ $t('addresses') }}</button>
        <button data-target="details">{{ $t('account_details') }}</button>
        <button data-target="cards">{{ $t('saved_cards') }}</button>
        <a href="{{ $wpBaseUrl }}/my-account/customer-logout/">{{ $t('logout') }}</a>
    </aside>

    <section class="account-content" id="accountContent" style="display: none;">
        <div class="alert alert-success" id="successMsg"></div>
        <div class="alert alert-error" id="errorMsg"></div>

        <!-- Dashboard -->
        <div class="tab-pane active" id="tab-dashboard">
            <h2>{{ $t('dashboard') }}</h2>
            <p><strong>{{ $t('welcome') }} <span id="dashName"></span></strong></p>
            <p>{{ $t('dashboard_desc') }}</p>
        </div>

        <!-- Orders -->
        <div class="tab-pane" id="tab-orders">
            <h2>{{ $t('orders') }}</h2>
            <div class="table-wrap">
                <table id="ordersTable">
                    <thead>
                        <tr>
                            <th>{{ $t('order_number') }}</th>
                            <th>{{ $t('date') }}</th>
                            <th>{{ $t('status') }}</th>
                            <th>{{ $t('total') }}</th>
                            <th>{{ $t('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <p id="noOrdersMsg" style="display: none;">{{ $t('no_orders') }}</p>
        </div>

        <!-- Addresses -->
        <div class="tab-pane" id="tab-addresses">
            <h2>{{ $t('addresses') }}</h2>
            <div class="grid-2">
                <div class="address-card">
                    <h3>{{ $t('billing_address') }}</h3>
                    <address id="billingAddressDisplay"></address>
                    <button class="btn btn-light" onclick="editAddress('billing')">{{ $t('edit') }}</button>
                </div>
                <div class="address-card">
                    <h3>{{ $t('shipping_address') }}</h3>
                    <address id="shippingAddressDisplay"></address>
                    <button class="btn btn-light" onclick="editAddress('shipping')">{{ $t('edit') }}</button>
                </div>
            </div>

            <form id="addressForm" style="display: none; margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--line);">
                <h3 id="addressFormTitle"></h3>
                <input type="hidden" id="addressType">
                <div class="grid-2">
                    <div class="form-group"><label>{{ $t('first_name') }}</label><input type="text" class="form-control" id="addr_first_name" required></div>
                    <div class="form-group"><label>{{ $t('last_name') }}</label><input type="text" class="form-control" id="addr_last_name" required></div>
                </div>
                <div class="form-group"><label>Company</label><input type="text" class="form-control" id="addr_company"></div>
                <div class="form-group"><label>Country</label><input type="text" class="form-control" id="addr_country" required></div>
                <div class="form-group"><label>Street address</label><input type="text" class="form-control" id="addr_address_1" required></div>
                <div class="form-group"><input type="text" class="form-control" id="addr_address_2" placeholder="Apartment, suite, unit, etc. (optional)"></div>
                <div class="grid-2">
                    <div class="form-group"><label>Town / City</label><input type="text" class="form-control" id="addr_city" required></div>
                    <div class="form-group"><label>State / County</label><input type="text" class="form-control" id="addr_state"></div>
                </div>
                <div class="grid-2">
                    <div class="form-group"><label>Postcode / ZIP</label><input type="text" class="form-control" id="addr_postcode"></div>
                    <div class="form-group" id="addrPhoneGroup"><label>Phone</label><input type="text" class="form-control" id="addr_phone"></div>
                </div>
                <div class="form-group" id="addrEmailGroup"><label>Email address</label><input type="email" class="form-control" id="addr_email"></div>
                <button type="submit" class="btn btn-primary">{{ $t('save_changes') }}</button>
                <button type="button" class="btn btn-light" onclick="document.getElementById('addressForm').style.display='none'">Cancel</button>
            </form>
        </div>

        <!-- Account Details -->
        <div class="tab-pane" id="tab-details">
            <h2>{{ $t('account_details') }}</h2>
            <form id="detailsForm">
                <div class="grid-2">
                    <div class="form-group"><label>{{ $t('first_name') }}</label><input type="text" class="form-control" id="det_first_name" required></div>
                    <div class="form-group"><label>{{ $t('last_name') }}</label><input type="text" class="form-control" id="det_last_name" required></div>
                </div>
                <div class="form-group"><label>{{ $t('display_name') }}</label><input type="text" class="form-control" id="det_display_name" required></div>
                <div class="form-group"><label>{{ $t('email') }}</label><input type="email" class="form-control" id="det_email" required></div>
                
                <fieldset style="border: 1px solid var(--line); border-radius: 14px; padding: 20px; margin: 30px 0;">
                    <legend style="padding: 0 10px; font-weight: 700; color: var(--secondary);">{{ $t('password_change') }}</legend>
                    <div class="form-group"><label>{{ $t('current_password') }}</label><input type="password" class="form-control" id="det_pass_current"></div>
                    <div class="form-group"><label>{{ $t('new_password') }}</label><input type="password" class="form-control" id="det_pass_1"></div>
                    <div class="form-group"><label>{{ $t('confirm_password') }}</label><input type="password" class="form-control" id="det_pass_2"></div>
                </fieldset>

                <button type="submit" class="btn btn-primary">{{ $t('save_changes') }}</button>
            </form>
        </div>

        <!-- Saved Cards -->
        <div class="tab-pane" id="tab-cards">
            <h2>{{ $t('saved_cards') }}</h2>
            <div id="cardsList"></div>
            <p id="noCardsMsg" style="display: none;">{{ $t('no_cards') }}</p>
        </div>
    </section>
</main>

@include('partials.shared-home-footer')

<script>
document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = '{{ $wpBaseUrl }}/wp-json/styliiiish/v1/account';

    // Required by the account API's CSRF guard (mu-plugins/styliiiish-account-api.php).
    // A custom header cannot be set by a cross-site <form>, and a cross-origin fetch that
    // sets one must pass a CORS preflight that the API never answers. Keep this in sync
    // with the header name checked server-side.
    const ACCOUNT_API_HEADERS = { 'X-Styliiiish-Account': '1' };
    const wpAccountUrl = '{{ $wpAccountUrl }}';
    const authFailedText = @json($t('auth_failed'));
    const passwordMismatchText = @json($t('password_mismatch'));
    const registerDisabledText = @json($t('register_disabled'));
    let accountData = null;
    let authContext = { loginNonce: '', registerNonce: '', canRegister: true, googleConfig: null, isLoggedIn: false };
    let accountGoogleInitialized = false;

    const decodeGoogleInlineValue = (value) => String(value || '')
        .replace(/\\\//g, '/')
        .replace(/\\u0026/gi, '&')
        .trim();

    const isValidGoogleClientId = (clientId) => {
        const value = String(clientId || '').trim();
        return /^\d+-[a-z0-9-]+\.apps\.googleusercontent\.com$/i.test(value);
    };

    const showMsg = (id, text) => {
        const el = document.getElementById(id);
        el.textContent = text;
        el.style.display = 'block';
        setTimeout(() => el.style.display = 'none', 5000);
    };

    const showGuestAuthError = (text) => {
        const errorBox = document.getElementById('guestAuthError');
        if (!errorBox) return;
        errorBox.textContent = text || authFailedText;
        errorBox.style.display = 'block';
    };

    const clearGuestAuthError = () => {
        const errorBox = document.getElementById('guestAuthError');
        if (!errorBox) return;
        errorBox.textContent = '';
        errorBox.style.display = 'none';
    };

    const isLoggedInFromDoc = (doc) => {
        if (!doc) return false;
        const hasLogoutLink = !!doc.querySelector('a[href*="customer-logout"]');
        const hasEditAccountForm = !!doc.querySelector('form.woocommerce-EditAccountForm');
        const hasAccountEmailField = !!doc.querySelector('input[name="account_email"]');
        return hasLogoutLink || hasEditAccountForm || hasAccountEmailField;
    };

    const parseWooErrors = (doc) => {
        if (!doc) return '';
        const selectors = ['.woocommerce-error li', 'ul.woocommerce-error li', '.woocommerce-error', '.woocommerce-notices-wrapper .woocommerce-error li'];
        for (const selector of selectors) {
            const node = doc.querySelector(selector);
            const text = (node?.textContent || '').trim();
            if (text) return text;
        }
        return '';
    };

    const extractSiteKitGoogleConfig = (doc) => {
        if (!doc) return null;
        const scripts = Array.from(doc.querySelectorAll('script'));
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

    const fetchAuthContext = async () => {
        const response = await fetch(`${wpAccountUrl}?_=${Date.now()}`, {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) return;
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        authContext.isLoggedIn = isLoggedInFromDoc(doc);

        authContext.loginNonce = doc.querySelector('input[name="woocommerce-login-nonce"]')?.value || '';
        authContext.registerNonce = doc.querySelector('input[name="woocommerce-register-nonce"]')?.value || '';
        authContext.canRegister = authContext.registerNonce !== '';
        authContext.googleConfig = extractSiteKitGoogleConfig(doc);

        const loginNonceInput = document.getElementById('guestLoginNonce');
        const registerNonceInput = document.getElementById('guestRegisterNonce');
        if (loginNonceInput) loginNonceInput.value = authContext.loginNonce;
        if (registerNonceInput) registerNonceInput.value = authContext.registerNonce;

        const registerTab = document.getElementById('guestRegisterTab');
        if (registerTab) {
            registerTab.style.display = authContext.canRegister ? 'inline-flex' : 'none';
        }
    };

    const setSiteKitRedirectCookie = (redirectUrl) => {
        const expires = new Date(Date.now() + (5 * 60 * 1000)).toUTCString();
        document.cookie = `googlesitekit_auth_redirect_to=${redirectUrl};expires=${expires};path=/`;
    };

    const initAccountGoogleButton = async () => {
        const googleButton = document.getElementById('accountGoogleButton');
        const googleFallback = document.getElementById('accountGoogleFallback');
        if (!googleButton || !authContext.googleConfig) return;
        if (!isValidGoogleClientId(authContext.googleConfig.clientId)) {
            if (googleFallback) googleFallback.style.display = 'inline-flex';
            return;
        }

        await loadGoogleIdentityScript();
        if (!window.google?.accounts?.id) return;

        const endpointUrl = String(authContext.googleConfig.endpoint || '');
        if (!endpointUrl) return;

        let absoluteEndpoint = '';
        try {
            absoluteEndpoint = endpointUrl.startsWith('http') ? endpointUrl : new URL(endpointUrl, wpAccountUrl).toString();
        } catch (error) {
            return;
        }

        const handleGoogleCredentialResponse = async (response) => {
            response.integration = 'woocommerce';
            const redirectTarget = window.location.href;
            setSiteKitRedirectCookie(redirectTarget);
            try {
                const result = await fetch(absoluteEndpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(response),
                    credentials: 'same-origin'
                });
                if (result.ok && result.redirected) {
                    location.assign(result.url);
                    return;
                }
            } catch (error) {}
            location.assign(redirectTarget);
        };

        if (!accountGoogleInitialized) {
            window.google.accounts.id.initialize({ client_id: authContext.googleConfig.clientId, callback: handleGoogleCredentialResponse, library_name: 'Site-Kit' });
            accountGoogleInitialized = true;
        }
        googleButton.innerHTML = '';
        window.google.accounts.id.renderButton(googleButton, {
            shape: googleButton.getAttribute('data-googlesitekit-siwg-shape') || 'pill',
            text: googleButton.getAttribute('data-googlesitekit-siwg-text') || 'continue_with',
            theme: googleButton.getAttribute('data-googlesitekit-siwg-theme') || 'filled_blue'
        });
        if (googleFallback) googleFallback.style.display = 'none';
    };

    const setupGuestAuth = async () => {
        const guestAuth = document.getElementById('guestAuth');
        if (!guestAuth) return;
        guestAuth.style.display = 'block';

        const tabButtons = Array.from(document.querySelectorAll('[data-auth-tab]'));
        const switchTab = (tab) => {
            tabButtons.forEach((button) => button.classList.toggle('active', button.dataset.authTab === tab));
            document.querySelectorAll('.guest-auth-pane').forEach((pane) => pane.classList.remove('active'));
            const targetPane = document.getElementById(`guestAuthPane-${tab}`);
            if (targetPane) targetPane.classList.add('active');
            clearGuestAuthError();
        };
        tabButtons.forEach((button) => button.addEventListener('click', () => switchTab(button.dataset.authTab)));

        await fetchAuthContext();
        await initAccountGoogleButton();

        const loginForm = document.getElementById('guestLoginForm');
        const loginSubmit = document.getElementById('guestLoginSubmit');
        if (loginForm) {
            loginForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearGuestAuthError();
                if (!authContext.loginNonce) await fetchAuthContext();
                if (!authContext.loginNonce) {
                    showGuestAuthError(authFailedText);
                    return;
                }
                const loginNonceInput = document.getElementById('guestLoginNonce');
                const loginRedirectInput = document.getElementById('guestLoginRedirect');
                if (loginNonceInput) loginNonceInput.value = authContext.loginNonce;
                if (loginRedirectInput) loginRedirectInput.value = window.location.href;

                if (loginSubmit) loginSubmit.disabled = true;
                loginForm.submit();
            });
        }

        const registerForm = document.getElementById('guestRegisterForm');
        const registerSubmit = document.getElementById('guestRegisterSubmit');
        if (registerForm) {
            registerForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearGuestAuthError();

                if (!authContext.canRegister) {
                    showGuestAuthError(registerDisabledText);
                    return;
                }

                if (!authContext.registerNonce) await fetchAuthContext();
                if (!authContext.registerNonce) {
                    showGuestAuthError(registerDisabledText);
                    return;
                }

                const password = document.getElementById('guestRegisterPassword').value;
                const passwordConfirm = document.getElementById('guestRegisterPasswordConfirm').value;
                if (password !== passwordConfirm) {
                    showGuestAuthError(passwordMismatchText);
                    return;
                }

                const registerNonceInput = document.getElementById('guestRegisterNonce');
                const registerRedirectInput = document.getElementById('guestRegisterRedirect');
                if (registerNonceInput) registerNonceInput.value = authContext.registerNonce;
                if (registerRedirectInput) registerRedirectInput.value = window.location.href;

                if (registerSubmit) registerSubmit.disabled = true;
                registerForm.submit();
            });
        }
    };

    const fetchAccountData = async () => {
        try {
            await fetchAuthContext();
            if (!authContext.isLoggedIn) {
                document.getElementById('mainLoader').style.display = 'none';
                await setupGuestAuth();
                return;
            }

            const res = await fetch(apiUrl, { headers: ACCOUNT_API_HEADERS, credentials: 'same-origin' });
            if (res.status === 401) {
                document.getElementById('mainLoader').style.display = 'none';
                await setupGuestAuth();
                return;
            }
            if (!res.ok) throw new Error('Not logged in');
            const json = await res.json();
            if (!json.success) throw new Error('Failed');
            accountData = json.data;
            renderData();
            document.getElementById('mainLoader').style.display = 'none';
            document.getElementById('accountNav').style.display = 'block';
            document.getElementById('accountContent').style.display = 'block';
        } catch (e) {
            document.getElementById('mainLoader').style.display = 'none';
            await setupGuestAuth();
        }
    };

    const renderData = () => {
        // Dashboard
        document.getElementById('dashName').textContent = accountData.details.display_name;

        // Orders
        const tbody = document.querySelector('#ordersTable tbody');
        tbody.innerHTML = '';
        if (accountData.orders.length === 0) {
            document.getElementById('ordersTable').style.display = 'none';
            document.getElementById('noOrdersMsg').style.display = 'block';
        } else {
            accountData.orders.forEach(o => {
                tbody.innerHTML += `<tr>
                    <td>#${o.order_number}</td>
                    <td>${o.date}</td>
                    <td>${o.status}</td>
                    <td>${o.total}</td>
                    <td><a href="${o.view_url}" class="btn btn-light btn-sm">{{ $t('view') }}</a></td>
                </tr>`;
            });
        }

        // Addresses
        const formatAddr = (a) => {
            return [a.first_name + ' ' + a.last_name, a.company, a.address_1, a.address_2, a.city, a.state, a.postcode, a.country].filter(x => x).join('<br>');
        };
        document.getElementById('billingAddressDisplay').innerHTML = formatAddr(accountData.addresses.billing) || 'Not set';
        document.getElementById('shippingAddressDisplay').innerHTML = formatAddr(accountData.addresses.shipping) || 'Not set';

        // Details
        document.getElementById('det_first_name').value = accountData.details.first_name;
        document.getElementById('det_last_name').value = accountData.details.last_name;
        document.getElementById('det_display_name').value = accountData.details.display_name;
        document.getElementById('det_email').value = accountData.details.email;

        // Cards
        const cardsList = document.getElementById('cardsList');
        cardsList.innerHTML = '';
        if (accountData.paymob_cards.length === 0) {
            document.getElementById('noCardsMsg').style.display = 'block';
        } else {
            accountData.paymob_cards.forEach(c => {
                cardsList.innerHTML += `<div class="card-item">
                    <div class="card-info">
                        <span class="card-icon"></span>
                        <span>${c.card_subtype} {{ $t('card_ending') }} ${c.masked_pan.slice(-4)}</span>
                    </div>
                    <button class="btn btn-danger" onclick="deleteCard(${c.id})">{{ $t('delete') }}</button>
                </div>`;
            });
        }
    };

    // Tabs
    document.querySelectorAll('.account-nav button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.account-nav button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            e.target.classList.add('active');
            document.getElementById('tab-' + e.target.dataset.target).classList.add('active');
        });
    });

    // Edit Address
    window.editAddress = (type) => {
        const form = document.getElementById('addressForm');
        form.style.display = 'block';
        document.getElementById('addressType').value = type;
        document.getElementById('addressFormTitle').textContent = type === 'billing' ? '{{ $t('billing_address') }}' : '{{ $t('shipping_address') }}';
        
        const a = accountData.addresses[type];
        document.getElementById('addr_first_name').value = a.first_name || '';
        document.getElementById('addr_last_name').value = a.last_name || '';
        document.getElementById('addr_company').value = a.company || '';
        document.getElementById('addr_country').value = a.country || '';
        document.getElementById('addr_address_1').value = a.address_1 || '';
        document.getElementById('addr_address_2').value = a.address_2 || '';
        document.getElementById('addr_city').value = a.city || '';
        document.getElementById('addr_state').value = a.state || '';
        document.getElementById('addr_postcode').value = a.postcode || '';
        
        if (type === 'billing') {
            document.getElementById('addrPhoneGroup').style.display = 'block';
            document.getElementById('addrEmailGroup').style.display = 'block';
            document.getElementById('addr_phone').value = a.phone || '';
            document.getElementById('addr_email').value = a.email || '';
        } else {
            document.getElementById('addrPhoneGroup').style.display = 'none';
            document.getElementById('addrEmailGroup').style.display = 'none';
        }
        form.scrollIntoView({ behavior: 'smooth' });
    };

    // Submit Address
    document.getElementById('addressForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const type = document.getElementById('addressType').value;
        const payload = {
            type,
            address: {
                first_name: document.getElementById('addr_first_name').value,
                last_name: document.getElementById('addr_last_name').value,
                company: document.getElementById('addr_company').value,
                country: document.getElementById('addr_country').value,
                address_1: document.getElementById('addr_address_1').value,
                address_2: document.getElementById('addr_address_2').value,
                city: document.getElementById('addr_city').value,
                state: document.getElementById('addr_state').value,
                postcode: document.getElementById('addr_postcode').value,
                phone: document.getElementById('addr_phone').value,
                email: document.getElementById('addr_email').value,
            }
        };

        try {
            const res = await fetch(apiUrl + '/addresses', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', ...ACCOUNT_API_HEADERS },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (json.success) {
                showMsg('successMsg', '{{ $t('success_update') }}');
                document.getElementById('addressForm').style.display = 'none';
                fetchAccountData();
            } else {
                showMsg('errorMsg', json.message || 'Error');
            }
        } catch (err) {
            showMsg('errorMsg', 'Network error');
        }
    });

    // Submit Details
    document.getElementById('detailsForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = {
            first_name: document.getElementById('det_first_name').value,
            last_name: document.getElementById('det_last_name').value,
            display_name: document.getElementById('det_display_name').value,
            email: document.getElementById('det_email').value,
            password_current: document.getElementById('det_pass_current').value,
            password_1: document.getElementById('det_pass_1').value,
            password_2: document.getElementById('det_pass_2').value,
        };

        try {
            const res = await fetch(apiUrl + '/details', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', ...ACCOUNT_API_HEADERS },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (json.success) {
                showMsg('successMsg', '{{ $t('success_update') }}');
                document.getElementById('det_pass_current').value = '';
                document.getElementById('det_pass_1').value = '';
                document.getElementById('det_pass_2').value = '';
                fetchAccountData();
            } else {
                showMsg('errorMsg', json.message || 'Error');
            }
        } catch (err) {
            showMsg('errorMsg', 'Network error');
        }
    });

    // Delete Card
    window.deleteCard = async (id) => {
        if (!confirm('Are you sure you want to delete this card?')) return;
        try {
            const res = await fetch(apiUrl + '/paymob-card/' + id, {
                method: 'DELETE',
                headers: ACCOUNT_API_HEADERS,
                credentials: 'same-origin'
            });
            const json = await res.json();
            if (json.success) {
                showMsg('successMsg', 'Card deleted');
                fetchAccountData();
            } else {
                showMsg('errorMsg', json.message || 'Error');
            }
        } catch (err) {
            showMsg('errorMsg', 'Network error');
        }
    };

    fetchAccountData();
});
</script>
</body>
</html>

