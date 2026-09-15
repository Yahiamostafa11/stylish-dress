<?php
/**
 * Plugin Name: Styliiiish Paymob Checkout Guard
 * Description: Targeted Paymob Pixel freeze guard (without editing payment plugin files).
 */

if (!defined('ABSPATH')) {
    exit;
}

function styliiiish_is_checkout_like_request(): bool {
    if (is_admin()) {
        return false;
    }

    if (function_exists('is_checkout') && is_checkout()) {
        if (function_exists('is_order_received_page') && is_order_received_page()) {
            return false;
        }

        return true;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
    if ($request_uri === '') {
        return false;
    }

    $decoded_uri = rawurldecode($request_uri);
    $needles = ['/checkout', '/payment', '/الدفع'];

    foreach ($needles as $needle) {
        if (strpos($request_uri, $needle) !== false || strpos($decoded_uri, $needle) !== false) {
            if (strpos($request_uri, 'order-received') !== false || strpos($decoded_uri, 'order-received') !== false) {
                return false;
            }

            return true;
        }
    }

    return false;
}

add_action('wp_enqueue_scripts', function () {
    if (!styliiiish_is_checkout_like_request()) {
        return;
    }

    global $wp_scripts, $wp_styles;

    if ($wp_scripts instanceof WP_Scripts && is_array($wp_scripts->queue)) {
        foreach ($wp_scripts->queue as $handle) {
            $src = isset($wp_scripts->registered[$handle]) ? (string) ($wp_scripts->registered[$handle]->src ?? '') : '';
            $is_translatepress_asset = (strpos($handle, 'trp-') === 0)
                || (strpos($handle, 'translatepress') !== false)
                || (strpos($src, 'translatepress') !== false)
                || (strpos($src, 'trp-') !== false);

            if ($is_translatepress_asset) {
                wp_dequeue_script($handle);
            }
        }
    }

    if ($wp_styles instanceof WP_Styles && is_array($wp_styles->queue)) {
        foreach ($wp_styles->queue as $handle) {
            $src = isset($wp_styles->registered[$handle]) ? (string) ($wp_styles->registered[$handle]->src ?? '') : '';
            $is_translatepress_asset = (strpos($handle, 'trp-') === 0)
                || (strpos($handle, 'translatepress') !== false)
                || (strpos($src, 'translatepress') !== false)
                || (strpos($src, 'trp-') !== false);

            if ($is_translatepress_asset) {
                wp_dequeue_style($handle);
            }
        }
    }
}, 9999);

function styliiiish_paymob_guard_is_checkout_context(): bool {
    return styliiiish_is_checkout_like_request();
}

function styliiiish_paymob_guard_is_enabled(): bool {
    $pixel_settings = get_option('woocommerce_paymob-pixel_settings', []);
    return is_array($pixel_settings) && (($pixel_settings['enabled'] ?? 'no') === 'yes');
}

function styliiiish_paymob_guard_is_protected_plugin_style(string $handle, string $src = ''): bool {
    $protected_handles = [
        'sty-owner-css',
        'sty-owner-mobile-css',
        'wf-add-modal',
        'styliiiish-myaccount-css',
        'wf-vendor-orders',
    ];

    if (in_array($handle, $protected_handles, true)) {
        return true;
    }

    $src = strtolower($src);
    if ($src === '') {
        return false;
    }

    return strpos($src, '/wp-content/plugins/flexi-multivendor-plugin/') !== false;
}

add_action('template_redirect', function () {
    if (is_admin() || (function_exists('wp_doing_ajax') && wp_doing_ajax())) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
    if ($request_uri === '') {
        return;
    }

    $request_path = (string) parse_url($request_uri, PHP_URL_PATH);
    $decoded_path = trim(rawurldecode($request_path), '/');
    if ($decoded_path === '' || strpos($decoded_path, 'order-received') !== false) {
        return;
    }

    $prefixes = [
        'ar/الدفع',
        'الدفع',
        'en/payment',
        'payment',
    ];

    $matched_prefix = null;
    foreach ($prefixes as $prefix) {
        if ($decoded_path === $prefix || strpos($decoded_path, $prefix . '/') === 0) {
            $matched_prefix = $prefix;
            break;
        }
    }

    if ($matched_prefix === null) {
        return;
    }

    $tail = ltrim((string) substr($decoded_path, strlen($matched_prefix)), '/');
    if ($tail === '') {
        return;
    }

    $parts = array_values(array_filter(explode('/', $tail), static function ($part) {
        return $part !== '';
    }));

    if (empty($parts)) {
        return;
    }

    $endpoint_aliases = [
        'تم-استلام-الطلب' => 'order-received',
        'تم-استلام-طلبك' => 'order-received',
        'thank-you' => 'order-received',
    ];

    $first_part = rawurldecode((string) $parts[0]);
    if (!isset($endpoint_aliases[$first_part])) {
        return;
    }

    $parts[0] = $endpoint_aliases[$first_part];
    $target = home_url('/checkout/' . implode('/', $parts) . '/');

    $preferredLocale = null;
    if (strpos($matched_prefix, 'en/') === 0 || $matched_prefix === 'en/payment') {
        $preferredLocale = 'en';
    } else {
        $preferredLocale = 'ar';
    }

    if (!headers_sent()) {
        setcookie('trp_language', $preferredLocale, [
            'expires' => time() + (30 * DAY_IN_SECONDS),
            'path' => '/',
            'secure' => is_ssl(),
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    }

    if (!empty($_GET) && is_array($_GET)) {
        $target = add_query_arg(wp_unslash($_GET), $target);
    }

    wp_safe_redirect($target, 302);
    exit;
}, 0);

add_action('send_headers', function () {
    if (!styliiiish_is_checkout_like_request()) {
        return;
    }

    header('Permissions-Policy: payment=(self "https://accept.paymob.com" "https://pay.google.com")', true);
}, 30);

add_action('wp_enqueue_scripts', function () {
    if (!styliiiish_is_checkout_like_request()) {
        return;
    }

    wp_register_script('styliiiish-paymob-permission-guard', false, [], '1.0.0', false);
    wp_enqueue_script('styliiiish-paymob-permission-guard');

    $inline = <<<'JS'
(function (window, document) {
    if (window.__styliiiishPaymobPermissionGuard) {
        return;
    }
    window.__styliiiishPaymobPermissionGuard = true;

    function enforcePaymentIframeAllow(scope) {
        const root = scope && scope.querySelectorAll ? scope : document;
        const frames = root.querySelectorAll
            ? root.querySelectorAll('iframe[src*="accept.paymob.com"], iframe[src*="paymob"], iframe[name*="paymob"]')
            : [];

        frames.forEach(function (frame) {
            const currentAllow = String(frame.getAttribute('allow') || '').trim();
            if (currentAllow.indexOf('payment') !== -1) {
                return;
            }

            frame.setAttribute('allow', (currentAllow ? currentAllow + '; ' : '') + 'payment *');
        });
    }

    function disableGooglePayPath() {
        try {
            if (typeof window.pxl_object === 'object' && window.pxl_object !== null) {
                window.pxl_object.googleenabled = 0;
            }
            window.googleenabled = 0;
        } catch (error) {
        }
    }

    const originalAppendChild = Element.prototype.appendChild;
    Element.prototype.appendChild = function (node) {
        try {
            if (node && node.tagName === 'SCRIPT') {
                const src = String(node.src || '');
                if (src.indexOf('pay.google.com/gp/p/js/pay.js') !== -1) {
                    return node;
                }
            }
        } catch (error) {
        }

        return originalAppendChild.call(this, node);
    };

    disableGooglePayPath();
    enforcePaymentIframeAllow(document);

    if (window.MutationObserver) {
        const observer = new MutationObserver(function (mutations) {
            disableGooglePayPath();

            mutations.forEach(function (mutation) {
                if (!mutation.addedNodes || !mutation.addedNodes.length) {
                    return;
                }

                mutation.addedNodes.forEach(function (node) {
                    if (!node || node.nodeType !== 1) {
                        return;
                    }

                    if (node.tagName === 'IFRAME') {
                        enforcePaymentIframeAllow(node.parentNode || document);
                        return;
                    }

                    enforcePaymentIframeAllow(node);
                });
            });
        });

        observer.observe(document.documentElement || document.body, {
            childList: true,
            subtree: true
        });

        window.setTimeout(function () {
            try {
                observer.disconnect();
            } catch (error) {
            }
        }, 25000);
    }
})(window, document);
JS;

    wp_add_inline_script('styliiiish-paymob-permission-guard', $inline, 'after');
}, 5);

add_action('wp_print_styles', function () {
    global $wp_styles;

    if (!($wp_styles instanceof WP_Styles) || !is_array($wp_styles->queue)) {
        return;
    }

    $seen_sources = [];
    foreach ($wp_styles->queue as $handle) {
        if (!isset($wp_styles->registered[$handle])) {
            continue;
        }

        $src = (string) ($wp_styles->registered[$handle]->src ?? '');

        if (styliiiish_paymob_guard_is_protected_plugin_style($handle, $src)) {
            continue;
        }

        if ($src === '') {
            continue;
        }

        $normalized = preg_replace('/([?&])ver=[^&]+&?/', '$1', $src);
        $normalized = rtrim((string) $normalized, '?&');

        if (isset($seen_sources[$normalized])) {
            wp_dequeue_style($handle);
            continue;
        }

        $seen_sources[$normalized] = true;
    }
}, 1000);

add_action('wp_enqueue_scripts', function () {
    if (!styliiiish_paymob_guard_is_checkout_context() || !styliiiish_paymob_guard_is_enabled()) {
        return;
    }

    $before_inline = <<<'JS'
(function (window) {
    if (window.__styliiiishPaymobBeforeGuardApplied) {
        return;
    }
    window.__styliiiishPaymobBeforeGuardApplied = true;

    try {
        const path = decodeURIComponent(String(window.location && window.location.pathname ? window.location.pathname : ''));
        if (path.indexOf('/ar/') !== -1 || path.indexOf('الدفع') !== -1) {
            if (window.document && window.document.documentElement) {
                window.document.documentElement.setAttribute('lang', 'ar');
                window.document.documentElement.setAttribute('dir', 'rtl');
            }
            window.__styliiiishPreferredCheckoutLang = 'ar';
        }
    } catch (error) {
    }

    function disableGooglePayForPaymob() {
        try {
            if (typeof window.pxl_object === 'object' && window.pxl_object !== null) {
                window.pxl_object.googleenabled = 0;
            }
            window.googleenabled = 0;
        } catch (error) {
        }
    }

    disableGooglePayForPaymob();

    const disableGooglePayInterval = window.setInterval(function () {
        disableGooglePayForPaymob();
    }, 300);

    window.setTimeout(function () {
        window.clearInterval(disableGooglePayInterval);
    }, 15000);

    const nativeAppendChild = Element.prototype.appendChild;
    Element.prototype.appendChild = function (node) {
        try {
            if (node && node.tagName === 'SCRIPT') {
                const src = String(node.src || '');
                if (src.indexOf('pay.google.com/gp/p/js/pay.js') !== -1) {
                    return node;
                }
            }
        } catch (error) {
        }
        return nativeAppendChild.call(this, node);
    };

    // Fix missing global used by paymob-pixel_block.js logs/comparisons.
    if (typeof window.previousTotalBlock === 'undefined') {
        window.previousTotalBlock = null;
    }
    try {
        window.eval('if (typeof previousTotalBlock === "undefined") { var previousTotalBlock = null; }');
    } catch (error) {
    }

    // Block/slow Paymob polling intervals to prevent UI freeze.
    const nativeSetInterval = window.setInterval;
    window.setInterval = function (callback, delay) {
        try {
            const ms = Number(delay);
            const cbText = typeof callback === 'function'
                ? Function.prototype.toString.call(callback)
                : String(callback || '');

            const stack = String((new Error()).stack || '');
            const fromPaymob = stack.indexOf('paymob-pixel_block.js') !== -1;
            const isAggressivePaymobPoll = fromPaymob
                && ms > 0
                && ms <= 1000
                && (
                    cbText.indexOf('wc/store/cart') !== -1
                    || cbText.indexOf('cartTotals') !== -1
                    || cbText.indexOf('previousTotalBlock') !== -1
                    || cbText.indexOf('updateCheckoutData') !== -1
                    || cbText.indexOf('billingAddress') !== -1
                    || cbText.indexOf('ajaxCall(') !== -1
                );

            if (isAggressivePaymobPoll) {
                return nativeSetInterval.call(this, callback, 5000);
            }

            if (fromPaymob && !Number.isNaN(ms) && ms > 0 && ms <= 1000) {
                return nativeSetInterval.call(this, callback, 2500);
            }

            if (!Number.isNaN(ms) && ms > 0 && ms <= 250) {
                return nativeSetInterval.call(this, callback, 1200);
            }
        } catch (error) {
        }
        return nativeSetInterval.apply(this, arguments);
    };
})(window);
JS;

    if (wp_script_is('paymob-pixel-checkout', 'enqueued')) {
        wp_add_inline_script('paymob-pixel-checkout', $before_inline, 'before');
    } else {
        wp_register_script('styliiiish-paymob-checkout-guard-before', false, [], '2.1.0', false);
        wp_enqueue_script('styliiiish-paymob-checkout-guard-before');
        wp_add_inline_script('styliiiish-paymob-checkout-guard-before', $before_inline, 'after');
    }

    wp_register_script('styliiiish-paymob-checkout-guard', false, ['jquery'], '2.0.0', true);
    wp_enqueue_script('styliiiish-paymob-checkout-guard');

    $inline_js = <<<'JS'
(function (window, document, $) {
    if (!$ || typeof $.ajax !== 'function') {
        return;
    }

    if (window.__styliiiishCheckoutIsolatedGuard) {
        return;
    }
    window.__styliiiishCheckoutIsolatedGuard = true;

    const LOADER_ID = 'paymob-loading-indicator';
    const NOTICE_ID = 'styliiiish-paymob-guard-notice';
    const AJAX_TIMEOUT_MS = 20000;
    const MIN_UPDATE_INTERVAL_MS = 2500;
    const DUPLICATE_WINDOW_MS = 1800;
    let lastUpdateCallAt = 0;
    let lastUpdateSignature = null;
    let lastUpdateSignatureAt = 0;
    let loaderTimer = null;
    let activeUpdateXhr = null;

    function enforceIframePaymentPermission(scope) {
        const root = scope && scope.querySelectorAll ? scope : document;
        const nodes = root.querySelectorAll
            ? root.querySelectorAll('iframe[src*="accept.paymob.com"], iframe[src*="unifiedcheckout"], iframe[name*="paymob"]')
            : [];

        nodes.forEach(function (frame) {
            const currentAllow = String(frame.getAttribute('allow') || '').trim();
            if (currentAllow.indexOf('payment') === -1) {
                frame.setAttribute('allow', (currentAllow ? currentAllow + '; ' : '') + 'payment *');
            }
        });
    }

    enforceIframePaymentPermission(document);

    if (window.MutationObserver) {
        const iframeObserver = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (!mutation.addedNodes || !mutation.addedNodes.length) {
                    return;
                }

                mutation.addedNodes.forEach(function (node) {
                    if (!node || node.nodeType !== 1) {
                        return;
                    }

                    if (node.tagName === 'IFRAME') {
                        enforceIframePaymentPermission(node.parentNode || document);
                        return;
                    }

                    enforceIframePaymentPermission(node);
                });
            });
        });

        iframeObserver.observe(document.documentElement || document.body, {
            childList: true,
            subtree: true
        });

        window.setTimeout(function () {
            try {
                iframeObserver.disconnect();
            } catch (error) {
            }
        }, 20000);
    }

    function isPaymobSelected() {
        const block = document.querySelector('input[name="radio-control-wc-payment-method-options"][value="paymob-pixel"]');
        const classic = document.getElementById('payment_method_paymob-pixel');
        return !!((block && block.checked) || (classic && classic.checked));
    }

    function getAjaxAction(rawData) {
        if (!rawData) {
            return '';
        }

        if (typeof rawData === 'object' && rawData !== null) {
            if (typeof rawData.action === 'string') {
                return rawData.action;
            }
            if (rawData instanceof FormData) {
                const action = rawData.get('action');
                return typeof action === 'string' ? action : '';
            }
            return '';
        }

        const text = String(rawData);
        const match = text.match(/(?:^|&)action=([^&]+)/);
        return match ? decodeURIComponent(match[1].replace(/\+/g, ' ')) : '';
    }

    function getPayloadSignature(rawData) {
        if (!rawData) {
            return '';
        }
        if (typeof rawData === 'string') {
            return rawData;
        }
        if (rawData instanceof FormData) {
            try {
                return JSON.stringify(Array.from(rawData.entries()));
            } catch (error) {
                return '';
            }
        }
        if (typeof rawData === 'object') {
            try {
                return JSON.stringify(rawData);
            } catch (error) {
                return '';
            }
        }
        return '';
    }

    function hasPaymobWidget() {
        const root = document.getElementById('paymob-elements');
        return !!(root && root.children && root.children.length > 0);
    }

    function clearLoader() {
        document.querySelectorAll('#' + LOADER_ID).forEach((node) => node.remove());
    }

    function showNotice(message) {
        const text = String(message || 'Checkout error. Please refresh and try again.');
        const existing = document.getElementById(NOTICE_ID);
        if (existing) {
            existing.textContent = text;
            return;
        }

        const host = document.querySelector('.woocommerce-notices-wrapper')
            || document.querySelector('.wc-block-components-notices')
            || document.querySelector('form.checkout')
            || document.querySelector('.wc-block-checkout__actions');

        if (!host) {
            return;
        }

        const node = document.createElement('div');
        node.id = NOTICE_ID;
        node.className = 'woocommerce-error';
        node.setAttribute('role', 'alert');
        node.style.margin = '12px 0';
        node.textContent = text;
        host.prepend(node);
    }

    function armLoaderTimeout() {
        if (loaderTimer) {
            clearTimeout(loaderTimer);
        }
        loaderTimer = setTimeout(function () {
            if (!hasPaymobWidget()) {
                clearLoader();
                showNotice('Paymob checkout is taking too long. Please refresh and try again.');
            }
        }, AJAX_TIMEOUT_MS);
    }

    $.ajaxPrefilter(function (options, originalOptions) {
        const url = String(options?.url || originalOptions?.url || '');
        const rawData = options?.data ?? originalOptions?.data ?? '';
        const action = getAjaxAction(rawData);
        const isPixelUpdate = url.indexOf('admin-ajax.php') !== -1 && action === 'update_pixel_data';
        const isOrderSession = url.indexOf('admin-ajax.php') !== -1 && action === 'get_order_id_from_session';
        const isPaymobCallback = url.indexOf('wc-api=paymob_callback') !== -1;

        if (!(isPixelUpdate || isOrderSession || isPaymobCallback)) {
            return;
        }

        if (options.async === false) {
            options.async = true;
        }

        if (!options.timeout || options.timeout < AJAX_TIMEOUT_MS) {
            options.timeout = AJAX_TIMEOUT_MS;
        }

        if (isPixelUpdate) {
            const now = Date.now();
            const signature = getPayloadSignature(rawData);

            if (signature && signature === lastUpdateSignature && (now - lastUpdateSignatureAt) < DUPLICATE_WINDOW_MS) {
                options.beforeSend = function () {
                    return false;
                };
                return;
            }

            if ((now - lastUpdateCallAt) < MIN_UPDATE_INTERVAL_MS) {
                options.beforeSend = function () {
                    return false;
                };
                return;
            }

            if (activeUpdateXhr && activeUpdateXhr.readyState !== 4) {
                options.beforeSend = function () {
                    return false;
                };
                return;
            }

            lastUpdateCallAt = now;
            lastUpdateSignature = signature;
            lastUpdateSignatureAt = now;
            armLoaderTimeout();
        }
    });

    $(document).ajaxSend(function (_event, jqXHR, settings) {
        const url = String(settings?.url || '');
        const action = getAjaxAction(settings?.data || '');
        const isPixelUpdate = url.indexOf('admin-ajax.php') !== -1 && action === 'update_pixel_data';
        if (!isPixelUpdate) {
            return;
        }

        activeUpdateXhr = jqXHR;
    });

    $(document).ajaxError(function (_event, jqXHR, settings, thrownError) {
        const url = String(settings?.url || '');
        const action = getAjaxAction(settings?.data || '');
        if (url.indexOf('admin-ajax.php') === -1 || action !== 'update_pixel_data') {
            return;
        }
        clearLoader();
        showNotice(jqXHR?.responseJSON?.data || thrownError || 'Payment service failed. Please retry.');
    });

    $(document).ajaxComplete(function (_event, _jqXHR, settings) {
        const url = String(settings?.url || '');
        const action = getAjaxAction(settings?.data || '');
        if (url.indexOf('admin-ajax.php') === -1 || action !== 'update_pixel_data') {
            return;
        }

        if (loaderTimer) {
            clearTimeout(loaderTimer);
            loaderTimer = null;
        }

        if (hasPaymobWidget()) {
            clearLoader();
        }

        if (activeUpdateXhr === _jqXHR) {
            activeUpdateXhr = null;
        }
    });

    const patchInterval = window.setInterval(function () {
        const hasUpdateCheckoutData = typeof window.updateCheckoutData === 'function';
        const hasAjaxCall = typeof window.ajaxCall === 'function';
        const hasInitializePaymobElement = typeof window.initializePaymobElement === 'function';

        if (hasUpdateCheckoutData && !window.__styliiiishWrappedUpdateCheckoutData) {
            const originalUpdateCheckoutData = window.updateCheckoutData;
            window.updateCheckoutData = function () {
                const now = Date.now();
                if ((now - lastUpdateCallAt) < MIN_UPDATE_INTERVAL_MS) {
                    return;
                }
                lastUpdateCallAt = now;
                return originalUpdateCheckoutData.apply(this, arguments);
            };
            window.__styliiiishWrappedUpdateCheckoutData = true;
        }

        if (hasAjaxCall && !window.__styliiiishWrappedAjaxCall) {
            const originalAjaxCall = window.ajaxCall;
            window.ajaxCall = function () {
                const now = Date.now();
                if (!isPaymobSelected()) {
                    return;
                }
                if ((now - lastUpdateCallAt) < MIN_UPDATE_INTERVAL_MS) {
                    return;
                }
                lastUpdateCallAt = now;
                return originalAjaxCall.apply(this, arguments);
            };
            window.__styliiiishWrappedAjaxCall = true;
        }

        if (hasInitializePaymobElement && !window.__styliiiishWrappedInitializePaymobElement) {
            const originalInitializePaymobElement = window.initializePaymobElement;
            window.initializePaymobElement = function () {
                const context = this;
                const args = arguments;
                const MAX_PIXEL_WAIT_ATTEMPTS = 40;
                const PIXEL_WAIT_MS = 250;

                function runWhenPixelReady(attempt) {
                    if (typeof window.Pixel === 'function') {
                        try {
                            return originalInitializePaymobElement.apply(context, args);
                        } catch (error) {
                            clearLoader();
                            showNotice('Unable to initialize card payments. Please refresh and try again.');
                            return;
                        }
                    }

                    if (attempt >= MAX_PIXEL_WAIT_ATTEMPTS) {
                        clearLoader();
                        showNotice('Card payments are taking too long to load. Please refresh and try again.');
                        return;
                    }

                    window.setTimeout(function () {
                        runWhenPixelReady(attempt + 1);
                    }, PIXEL_WAIT_MS);
                }

                runWhenPixelReady(0);
            };
            window.__styliiiishWrappedInitializePaymobElement = true;
        }

        if ((window.__styliiiishWrappedUpdateCheckoutData || !hasUpdateCheckoutData)
            && (window.__styliiiishWrappedAjaxCall || !hasAjaxCall)
            && (window.__styliiiishWrappedInitializePaymobElement || !hasInitializePaymobElement)) {
            window.clearInterval(patchInterval);
        }
    }, 300);

    window.setTimeout(function () {
        window.clearInterval(patchInterval);
    }, 15000);
})(window, document, window.jQuery);
JS;

    wp_add_inline_script('styliiiish-paymob-checkout-guard', $inline_js, 'after');
}, 99999);
