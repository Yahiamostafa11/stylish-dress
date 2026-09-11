<!DOCTYPE html>
@php
    $currentLocale = $currentLocale ?? 'ar';
    $localePrefix = $localePrefix ?? '/ar';
    $isEnglish = $currentLocale === 'en';
    $wpBaseUrl = rtrim((string) ($wpBaseUrl ?? env('WP_PUBLIC_URL', request()->getSchemeAndHttpHost())), '/');
    $canonicalPath = $localePrefix . '/wishlist';

    $translations = [
        'ar' => [
            'meta_title' => 'المفضلة | احفظي منتجاتك وارجعي لها في أي وقت | Styliiiish',
            'page_title' => 'المفضلة | ستايلش',
            'meta_desc' => 'تابعي منتجاتك المحفوظة في قائمة المفضلة على Styliiiish وقارنيها بسهولة قبل إتمام الشراء بأفضل عرض مناسب لك.',
            'topbar' => 'المتجر الرسمي • شحن داخل مصر 2–10 أيام',
            'nav_home' => 'الرئيسية',
            'nav_shop' => 'المتجر',
            'nav_marketplace' => 'الماركت بليس',
            'nav_sell' => 'بيعي فستانك',
            'nav_blog' => 'المدونة',
            'slogan' => 'لأن كل امرأة تستحق أن تتألق',
            'title' => 'المفضلة',
            'subtitle' => 'كل المنتجات المحفوظة في مكان واحد',
            'count_loading' => 'جاري تحميل العدد…',
            'continue_shopping' => 'متابعة التسوق',
            'loading' => 'جاري تحميل المفضلة…',
            'empty_title' => 'المفضلة فارغة حالياً',
            'empty_desc' => 'أضيفي المنتجات التي تعجبك من صفحة المنتج لتظهر هنا.',
            'go_shop' => 'تصفح المنتجات',
            'view_product' => 'اذهب إلى المنتج',
            'remove' => 'حذف',
            'removing' => 'جاري الحذف…',
            'remove_failed' => 'تعذر حذف هذا المنتج حالياً.',
            'load_failed' => 'تعذر تحميل عناصر المفضلة حالياً.',
            'items' => 'منتج',
            'footer_brand_title' => 'ستيليش فاشون هاوس',
            'footer_brand_desc' => 'نعمل بشغف على تقديم أحدث تصاميم الفساتين لتناسب كل مناسبة خاصة بك.',
            'footer_brand_time' => 'مواعيد العمل: السبت إلى الجمعة من 11:00 صباحًا حتى 7:00 مساءً.',
            'footer_contact' => 'تواصلي معنا',
            'footer_quick' => 'روابط سريعة',
            'footer_official' => 'معلومات رسمية',
            'footer_policies' => 'سياسات وقوانين',
            'footer_about' => 'من نحن',
            'footer_privacy' => 'سياسة الخصوصية',
            'footer_terms' => 'الشروط والأحكام',
            'footer_refund' => 'سياسة الاسترجاع والاستبدال',
            'footer_faq' => 'الأسئلة الشائعة',
            'footer_shipping' => 'سياسة الشحن والتوصيل',
            'footer_cookie' => 'سياسة ملفات الارتباط',
            'footer_rights' => 'جميع الحقوق محفوظة',
        ],
        'en' => [
            'meta_title' => 'Wishlist | Save Your Favorites and Revisit Anytime | Styliiiish',
            'page_title' => 'Wishlist | Styliiiish',
            'meta_desc' => 'Keep your favorite products in your Styliiiish wishlist and revisit them quickly before completing your purchase.',
            'topbar' => 'Official Store • Egypt shipping in 2–10 days',
            'nav_home' => 'Home',
            'nav_shop' => 'Shop',
            'nav_marketplace' => 'Marketplace',
            'nav_sell' => 'Sell Your Dress',
            'nav_blog' => 'Blog',
            'slogan' => 'Every woman deserves to shine',
            'title' => 'Wishlist',
            'subtitle' => 'Your saved products in one place',
            'count_loading' => 'Loading count…',
            'continue_shopping' => 'Continue shopping',
            'loading' => 'Loading wishlist…',
            'empty_title' => 'Your wishlist is empty',
            'empty_desc' => 'Add products you love from the product page to see them here.',
            'go_shop' => 'Browse products',
            'view_product' => 'Go to product',
            'remove' => 'Remove',
            'removing' => 'Removing…',
            'remove_failed' => 'Unable to remove this item right now.',
            'load_failed' => 'Unable to load wishlist items right now.',
            'items' => 'items',
            'footer_brand_title' => 'Styliiiish Fashion House',
            'footer_brand_desc' => 'We passionately curate the latest dress designs for your special occasions.',
            'footer_brand_time' => 'Working hours: Saturday to Friday, 11:00 AM to 7:00 PM.',
            'footer_contact' => 'Contact us',
            'footer_quick' => 'Quick links',
            'footer_official' => 'Official info',
            'footer_policies' => 'Policies',
            'footer_about' => 'About Us',
            'footer_privacy' => 'Privacy Policy',
            'footer_terms' => 'Terms & Conditions',
            'footer_refund' => 'Refund & Return Policy',
            'footer_faq' => 'FAQ',
            'footer_shipping' => 'Shipping Policy',
            'footer_cookie' => 'Cookie Policy',
            'footer_rights' => 'All rights reserved',
        ],
    ];

    $normalizeBrandText = fn (string $value) => $currentLocale === 'en'
        ? (preg_replace('/ستايلش/iu', 'Styliiiish', $value) ?? $value)
        : (preg_replace('/styliiiish/iu', 'ستايلش', $value) ?? $value);
    $t = fn (string $key) => $normalizeBrandText((string) ($translations[$currentLocale][$key] ?? $translations['ar'][$key] ?? $key));

    $wpLogo = 'https://styliiiish.com/wp-content/uploads/2025/11/ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $wpIcon = 'https://styliiiish.com/wp-content/uploads/2025/11/cropped-ChatGPT-Image-Nov-2-2025-03_11_14-AM-e1762046066547.png';
    $seoUrl = $wpBaseUrl . $canonicalPath;
    $ogLocale = $isEnglish ? 'en_US' : 'ar_AR';
    $ogAltLocale = $isEnglish ? 'ar_AR' : 'en_US';
@endphp
<html lang="{{ $isEnglish ? 'en' : 'ar' }}" dir="{{ $isEnglish ? 'ltr' : 'rtl' }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="noindex, follow">
    <meta name="author" content="Styliiiish">
    <meta name="description" content="{{ $t('meta_desc') }}">
    <link rel="canonical" href="{{ $seoUrl }}">
    <link rel="alternate" hreflang="ar" href="{{ $wpBaseUrl }}/ar/wishlist">
    <link rel="alternate" hreflang="en" href="{{ $wpBaseUrl }}/en/wishlist">
    <link rel="alternate" hreflang="x-default" href="{{ $wpBaseUrl }}/ar/wishlist">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:locale:alternate" content="{{ $ogAltLocale }}">
    <meta property="og:site_name" content="{{ $isEnglish ? 'Styliiiish' : 'ستايلش' }}">
    <meta property="og:title" content="{{ $t('meta_title') }}">
    <meta property="og:description" content="{{ $t('meta_desc') }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $wpIcon }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $t('meta_title') }}">
    <meta name="twitter:description" content="{{ $t('meta_desc') }}">
    <meta name="twitter:image" content="{{ $wpIcon }}">
    <meta name="theme-color" content="#d51522">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $wpIcon }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $wpIcon }}">
    <link rel="apple-touch-icon" href="{{ $wpIcon }}">
    <title>{{ $t('meta_title') }}</title>
    @include('partials.shared-seo-meta')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $t('page_title'),
        'description' => $t('meta_desc'),
        'url' => $seoUrl,
        'inLanguage' => $isEnglish ? 'en' : 'ar',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Styliiiish',
            'url' => $wpBaseUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $wpIcon,
            ],
        ],
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
            --danger: #dc2626;
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, Arial, sans-serif; background: var(--bg); color: var(--text); }
        a { text-decoration: none; color: inherit; }
        .container { width: min(1180px, 92%); margin: 0 auto; }

        .topbar { background: var(--secondary); color: #fff; font-size: 13px; }
        .topbar .container { min-height: 40px; display: flex; align-items: center; justify-content: center; }

        .header { background: #fff; border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 40; }
        .header-inner { min-height: 74px; display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 12px; }
        .brand { display: flex; flex-direction: column; gap: 2px; }
        .brand-logo { height: 38px; width: auto; max-width: min(210px, 40vw); object-fit: contain; }
        .brand-sub { font-size: 11px; color: var(--muted); }

        .nav { display: flex; justify-content: center; gap: 8px; padding: 5px; border: 1px solid var(--line); border-radius: 12px; background: #f9fbff; }
        .nav a { padding: 8px 12px; border-radius: 8px; font-size: 14px; font-weight: 700; white-space: nowrap; }
        .nav a.active, .nav a:hover { color: var(--primary); background: #fff4f5; }

        .head-btn { border: 1px solid var(--line); border-radius: 10px; min-width: 38px; min-height: 38px; display: inline-flex; align-items: center; justify-content: center; background: #fff; }
        .head-btn.active { color: var(--primary); border-color: rgba(213,21,34,.3); background: #fff4f5; }

        .wishlist-head { padding: 24px 0 14px; }
        .wishlist-head h1 { margin: 0 0 6px; font-size: clamp(25px, 4vw, 35px); }
        .wishlist-head p { margin: 0; color: var(--muted); }

        .panel { background: #fff; border: 1px solid var(--line); border-radius: 16px; overflow: hidden; margin-bottom: 24px; }
        .panel-top { padding: 18px; border-bottom: 1px solid var(--line); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
        .badge { border-radius: 999px; background: #fff3f4; color: var(--primary); font-weight: 800; padding: 8px 12px; font-size: 13px; }
        .btn { border: 1px solid var(--line); border-radius: 10px; min-height: 40px; padding: 0 14px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; background: #fff; cursor: pointer; }
        .btn:hover { border-color: var(--primary); color: var(--primary); }
        .panel-body { padding: 18px; }

        .grid { display: grid; gap: 12px; }
        .item {
            display: grid;
            grid-template-columns: 94px 1fr auto;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            padding: 10px;
        }
        .thumb { width: 94px; height: 94px; object-fit: cover; border-radius: 10px; border: 1px solid var(--line); background: #f2f2f5; }
        .name { margin: 0; font-size: 16px; line-height: 1.4; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
        .btn-danger { color: var(--danger); border-color: rgba(220,38,38,.25); }
        .btn-danger:hover { background: #fff5f5; }
        .state { border: 1px dashed var(--line); border-radius: 14px; text-align: center; padding: 28px 14px; color: var(--muted); }
        .state h3 { margin: 0 0 8px; color: var(--text); font-size: 22px; }

        .site-footer { margin-top: 10px; background: #0f1a2a; color: #fff; border-top: 4px solid var(--primary); }
        .footer-grid { padding: 32px 0 20px; display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.1fr; gap: 20px; }
        .footer-brand-logo { width: 156px; max-width: 100%; object-fit: contain; margin-bottom: 10px; display: block; }
        .footer-brand h4 { margin: 0 0 8px; font-size: 18px; }
        .footer-brand p { margin: 0 0 8px; color: #c8d3e2; font-size: 14px; line-height: 1.7; }
        .footer-contact-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .footer-contact-row a { color: #fff; border: 1px solid rgba(255,255,255,.25); border-radius: 999px; padding: 6px 10px; font-size: 12px; }
        .footer-col h5 { margin: 0 0 10px; font-size: 15px; }
        .footer-links { list-style: none; margin: 0; padding: 0; display: grid; gap: 8px; }
        .footer-links a { color: #d7e0ed; font-size: 14px; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.18); padding: 12px 0; display: flex; justify-content: space-between; gap: 10px; color: #b8c5d8; font-size: 13px; flex-wrap: wrap; }
        .footer-bottom a { color: #fff; }

        @media (max-width: 980px) {
            .header-inner { grid-template-columns: 1fr; padding: 10px 0; }
            .brand, .nav { justify-content: center; text-align: center; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 720px) {
            .item { grid-template-columns: 78px 1fr; }
            .thumb { width: 78px; height: 78px; }
            .actions { grid-column: 1 / -1; justify-content: stretch; }
            .actions .btn { flex: 1 1 100%; }
            .nav { overflow-x: auto; justify-content: flex-start; }
        }

        @media (max-width: 390px) {
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
    @include('partials.shared-home-header-styles')
</head>
<body>
    @include('partials.shared-home-header')

    <main class="container">
        <section class="wishlist-head">
            <h1>{{ $t('title') }}</h1>
            <p>{{ $t('subtitle') }}</p>
        </section>

        <section class="panel">
            <header class="panel-top">
                <span class="badge" id="wishlistCount">{{ $t('count_loading') }}</span>
                <a class="btn" href="{{ $localePrefix }}/shop">{{ $t('continue_shopping') }}</a>
            </header>
            <div class="panel-body">
                <div id="wishlistContainer" class="grid">
                    <div class="state">{{ $t('loading') }}</div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.shared-home-footer')

    <script>
        (() => {
            const localePrefix = @json($localePrefix);
            const texts = {
                loading: @json($t('loading')),
                emptyTitle: @json($t('empty_title')),
                emptyDesc: @json($t('empty_desc')),
                goShop: @json($t('go_shop')),
                viewProduct: @json($t('view_product')),
                remove: @json($t('remove')),
                removing: @json($t('removing')),
                removeFailed: @json($t('remove_failed')),
                loadFailed: @json($t('load_failed')),
                items: @json($t('items')),
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const container = document.getElementById('wishlistContainer');
            const countBadge = document.getElementById('wishlistCount');

            const getItemsUrl = () => `${localePrefix}/item/wishlist/items`;
            const getRemoveUrl = (id) => `${localePrefix}/item/wishlist/${encodeURIComponent(id)}`;

            const setCount = (count) => {
                const safeCount = Math.max(0, Number(count) || 0);
                if (!countBadge) return;
                countBadge.textContent = `${safeCount} ${texts.items}`;
            };

            const escapeHtml = (value) => String(value || '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const renderEmpty = () => {
                container.innerHTML = `
                    <div class="state">
                        <h3>${escapeHtml(texts.emptyTitle)}</h3>
                        <p>${escapeHtml(texts.emptyDesc)}</p>
                        <a class="btn" href="${localePrefix}/shop" style="margin-top:12px;">${escapeHtml(texts.goShop)}</a>
                    </div>
                `;
            };

            const renderItems = (items) => {
                const safeItems = Array.isArray(items) ? items : [];
                if (safeItems.length === 0) {
                    renderEmpty();
                    return;
                }

                container.innerHTML = safeItems.map((item) => {
                    const id = Number(item.id) || 0;
                    const name = escapeHtml(item.name || '');
                    const image = escapeHtml(item.image || '');
                    const url = escapeHtml(item.url || '#');

                    return `
                        <article class="item" data-id="${id}">
                            <img class="thumb" src="${image}" alt="${name}" loading="lazy" />
                            <div><h3 class="name">${name}</h3></div>
                            <div class="actions">
                                <a class="btn" href="${url}">${escapeHtml(texts.viewProduct)}</a>
                                <button class="btn btn-danger js-remove" type="button" data-id="${id}">${escapeHtml(texts.remove)}</button>
                            </div>
                        </article>
                    `;
                }).join('');

                bindRemoveActions();
            };

            const loadItems = async () => {
                container.innerHTML = `<div class="state">${escapeHtml(texts.loading)}</div>`;

                try {
                    const response = await fetch(`${getItemsUrl()}?_=${Date.now()}`, {
                        method: 'GET',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const payload = await response.json();
                    if (!response.ok || !payload || payload.success !== true) {
                        throw new Error('wishlist_load_failed');
                    }

                    setCount(payload.count);
                    renderItems(payload.items || []);
                } catch (error) {
                    container.innerHTML = `<div class="state">${escapeHtml(texts.loadFailed)}</div>`;
                }
            };

            const removeItem = async (productId, button) => {
                if (!productId || !button) return;

                const oldText = button.textContent;
                button.disabled = true;
                button.textContent = texts.removing;

                try {
                    const response = await fetch(getRemoveUrl(productId), {
                        method: 'DELETE',
                        credentials: 'include',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });

                    const payload = await response.json();
                    if (!response.ok || !payload || payload.success !== true) {
                        throw new Error('wishlist_remove_failed');
                    }

                    setCount(payload.count);
                    await loadItems();
                } catch (error) {
                    button.disabled = false;
                    button.textContent = oldText || texts.remove;
                    alert(texts.removeFailed);
                }
            };

            const bindRemoveActions = () => {
                container.querySelectorAll('.js-remove').forEach((button) => {
                    button.addEventListener('click', () => {
                        const id = Number(button.getAttribute('data-id') || 0);
                        removeItem(id, button);
                    });
                });
            };

            loadItems();
        })();
    </script>
</body>
</html>

