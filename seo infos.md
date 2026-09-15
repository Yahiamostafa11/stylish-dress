# SEO Infos — Laravel Pages (Styliiiish)

هذا الملف يجمع روابط صفحات Laravel مع إعدادات SEO المرتبطة بها (Title / Description / Canonical / OG / Twitter).

## Global SEO Stack (Applied)

- Shared partial: `laravel_home/resources/views/partials/shared-seo-meta.blade.php`
- Injected in all Laravel top-level views (21 صفحة).
- Provides:
  - `keywords`
  - `author/publisher/application-name`
  - `googlebot/bingbot`
  - `og:locale` + `og:image` + `og:url` + `og:site_name`
  - `twitter:card/title/description/image`
  - `preconnect` + `dns-prefetch`
  - `JSON-LD` (`WebPage` + `WebSite`)

## Analytics / Ads Tracking (Laravel + WordPress)

- WordPress pages: continue to be tracked by Site Kit as-is.
- Laravel pages: now include global `gtag` injection from shared partial:
  - `laravel_home/resources/views/partials/shared-seo-meta.blade.php`
- Environment configuration:
  - `GA4_MEASUREMENT_ID=G-XXXXXXXXXX`
  - `GOOGLE_ADS_TAG_ID=AW-XXXXXXXXX`
- Config mapping:
  - `laravel_home/config/services.php` → `services.analytics.ga4_measurement_id`
  - `laravel_home/config/services.php` → `services.analytics.google_ads_tag_id`

### E-commerce Events (Implemented)

- `add_to_cart`:
  - Fired on successful AJAX add-to-cart from product page.
  - File: `laravel_home/resources/views/product-single.blade.php`
- `begin_checkout`:
  - Fired on checkout click from cart page CTA.
  - File: `laravel_home/resources/views/cart.blade.php`
  - Fired on checkout click from mini-cart (shared header).
  - File: `laravel_home/resources/views/partials/shared-home-header-interactions.blade.php`
  - Fired on checkout click from home-page custom mini-cart.
  - File: `laravel_home/resources/views/home.blade.php`
- `purchase`:
  - Global helper is available as `window.styliiiishTrackPurchase(payload)`.
  - Auto-deduplicates by `transaction_id` in `sessionStorage`.
  - WordPress thank-you flow can keep using existing tracking, or call helper if purchase is rendered in Laravel.

### Google Ads Conversion Import

1. In GA4, mark key events as conversions (`purchase`, optionally `begin_checkout`/`add_to_cart` if needed).
2. In Google Ads: `Tools & Settings` → `Conversions` → `New conversion action` → `Import` → `Google Analytics 4 properties`.
3. Select the GA4 conversion event(s) and complete import.
4. Wait for data sync (usually up to 24h) and verify status becomes recording.

## URL Map + SEO Source

| Page | AR URL | EN URL | Meta Title Source | Meta Description Source | Canonical Source |
|---|---|---|---|---|---|
| Home | https://styliiiish.com/ar | https://styliiiish.com/en | `home.blade.php` → `$t('title')` | `home.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Shop | https://styliiiish.com/ar/shop | https://styliiiish.com/en/shop | `shop.blade.php` → `$t('page_title')` | `shop.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Ads Landing | https://styliiiish.com/ar/ads | https://styliiiish.com/en/ads | `ads-landing.blade.php` → `$t('meta_title')` | `ads-landing.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Product Single (dynamic) | https://styliiiish.com/ar/item/{slug} | https://styliiiish.com/en/item/{slug} | `product-single.blade.php` → `$seoTitle` | `product-single.blade.php` → `$seoDescription` | `$seoUrl` |
| Blog Archive | https://styliiiish.com/ar/blog | https://styliiiish.com/en/blog | `blog.blade.php` → `$t('page_title')` | `blog.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Blog Single (dynamic) | https://styliiiish.com/ar/blog/{slug} | https://styliiiish.com/en/blog/{slug} | `blog-single.blade.php` → `title + suffix` | `blog-single.blade.php` → `$metaDesc` | `$wpBaseUrl . $canonicalPath` |
| Contact | https://styliiiish.com/ar/contact-us | https://styliiiish.com/en/contact-us | `contact.blade.php` → `$t('page_title')` | `contact.blade.php` → `$t('meta_desc')` | `https://styliiiish.com{{ $canonicalPath }}` |
| About | https://styliiiish.com/ar/about-us | https://styliiiish.com/en/about-us | `about.blade.php` → `$t('page_title')` | `about.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Categories | https://styliiiish.com/ar/categories | https://styliiiish.com/en/categories | `categories.blade.php` → `$t('page_title')` | `categories.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Marketplace | https://styliiiish.com/ar/marketplace | https://styliiiish.com/en/marketplace | `marketplace.blade.php` → `$t('page_title')` | `marketplace.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Marketplace Policy | https://styliiiish.com/ar/marketplace-policy | https://styliiiish.com/en/marketplace-policy | `marketplace-policy.blade.php` → `$t('page_title')` | `marketplace-policy.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Privacy Policy | https://styliiiish.com/ar/privacy-policy | https://styliiiish.com/en/privacy-policy | `privacy-policy.blade.php` → `$t('page_title')` | `privacy-policy.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Terms & Conditions | https://styliiiish.com/ar/terms-conditions | https://styliiiish.com/en/terms-conditions | `terms-conditions.blade.php` → `$t('page_title')` | `terms-conditions.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Refund & Return Policy | https://styliiiish.com/ar/refund-return-policy | https://styliiiish.com/en/refund-return-policy | `refund-return-policy.blade.php` → `$t('page_title')` | `refund-return-policy.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Shipping & Delivery Policy | https://styliiiish.com/ar/shipping-delivery-policy | https://styliiiish.com/en/shipping-delivery-policy | `shipping-delivery-policy.blade.php` → `$t('page_title')` | `shipping-delivery-policy.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Cookie Policy | https://styliiiish.com/ar/cookie-policy | https://styliiiish.com/en/cookie-policy | `cookie-policy.blade.php` → `$t('page_title')` | `cookie-policy.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| FAQ | https://styliiiish.com/ar/faq | https://styliiiish.com/en/faq | `faq.blade.php` → `$t('page_title')` | `faq.blade.php` → `$t('meta_desc')` | `$wpBaseUrl . $canonicalPath` |
| Wishlist | https://styliiiish.com/ar/wishlist | https://styliiiish.com/en/wishlist | `wishlist.blade.php` → `$t('page_title')` | `wishlist.blade.php` → `$t('meta_desc')` | `$seoUrl` |
| Cart | https://styliiiish.com/ar/cart | https://styliiiish.com/en/cart | `cart.blade.php` → `$t('page_title')` | `cart.blade.php` → `$t('meta_desc')` | `$seoUrl` |
| Account | https://styliiiish.com/ar/my-account | https://styliiiish.com/en/my-account | `account.blade.php` → `$t('page_title')` | from shared fallback (`meta_desc`/default) | route-driven |
| Welcome/Landing | https://styliiiish.com/ | https://styliiiish.com/en (route-based) | `welcome.blade.php` → `$seoTitle` | `welcome.blade.php` → `$seoDescription` | route-driven |

## Notes

- All pages now include shared SEO augmentation via `@include('partials.shared-seo-meta')`.
- Existing page-specific tags (title/description/canonical/hreflang/og/twitter) remain in place; shared partial adds/standardizes missing professional meta.
- For dynamic pages (`item/{slug}`, `blog/{slug}`), SEO values are generated per content item.

## Structured Data Coverage (Implemented)

- Global: `Organization` + `WebSite` + `WebPage` from shared partial.
- Product page (`/item/{slug}`):
  - `Product` + `Offer`
  - `AggregateRating` (when ratings exist)
  - `Review` (up to 5 approved reviews)
  - `BreadcrumbList`
- Listing pages:
  - `/shop`: `ItemList` of products + `BreadcrumbList`
  - `/ads`: `ItemList` of products + `BreadcrumbList`

## Merchant Center Feeds (Implemented)

- Arabic feed: `https://styliiiish.com/merchant-feed.xml`
- English feed: `https://styliiiish.com/merchant-feed-en.xml`
- Feed fields include: `id`, `title`, `description`, `link`, `image_link`, `availability`, `price`, `condition`, `brand`.

## معلومات عن المصدر (للشفافية مع Google)

- المصدر الرسمي: `styliiiish.com`
- هذه النتيجة مصدرها موقع إلكتروني فهرسه محرّك بحث Google.
- مزيد من المعلومات حول هذه الصفحة:
  - الصفحة لها Canonical واضح و`hreflang` عربي/إنجليزي.
  - الصفحة تدعم Structured Data مناسب لنوعها (WebPage / Product / ItemList / Breadcrumb).
  - اللغة الأساسية في النتائج العربية: العربية (`ar`).
  - الموقع يستهدف ويخدم أكثر من منطقة، منها: مصر وسويسرا (`areaServed: EG, CH`).

## بحثك وهذه النتيجة

- يتطابق الموقع الإلكتروني `styliiiish.com` مع عبارة بحث واحدة أو أكثر من العبارات التي أدخلتها.
- هذه النتيجة باللغة العربية (عند صفحة عربية) أو الإنجليزية (عند صفحة إنجليزية).
- يبدو أن هذه النتيجة ذات صلة بعمليات البحث في مناطق متعددة، بما فيها سويسرا.

## طريقة عمل Google (توضيح مهم)

- هذه نتيجة بحث وليست إعلانًا.
- يتم الدفع مقابل الإعلانات فقط، وتظهر دائمًا بعلامة مثل: "إعلان" أو "دعائية".

## صفحات السوشيال الرسمية للموقع

- Facebook: `https://www.facebook.com/Styliiish.Egypt/`
- Instagram: `https://www.instagram.com/styliiiish.egypt/`
- TikTok: `https://www.tiktok.com/@styliiish_?_r=1&_t=ZS-94HEUy9a0RE`
- Google Business Profile: `https://g.page/styliish`
- WhatsApp Business: `https://wa.me/201050874255`

> تم ربط هذه الحسابات أيضًا داخل Schema (`sameAs`) لتكون الإشارة واضحة قدر الإمكان لمحركات البحث.

## Execution Checklist (Search Console + Rich Results)

1. Submit/verify sitemaps:
  - `https://styliiiish.com/sitemap_index.xml`
  - `https://styliiiish.com/en/sitemap_index.xml`
2. Validate samples in Rich Results Test:
  - Home, Shop, Ads, and 3–5 product URLs.
3. In Search Console:
  - URL Inspection → Request Indexing for updated templates.
  - Monitor Enhancements: `Products`, `Breadcrumbs`, and `Merchant listings`.
4. In Merchant Center:
  - Add primary feed URL (`merchant-feed.xml`) and schedule daily fetch.
  - Fix disapproved items (price mismatch, availability mismatch, image quality).
5. Recheck Core Web Vitals + mobile usability weekly.

## Lighthouse Performance Fixes (Feb 2026)

- Reduced oversized footer logo payload:
  - Switched footer logo rendering to smaller generated image variant (`300x128`) with `srcset` (`1x/2x`) and safe fallback.
- Improved home product image delivery:
  - Home cards now request a lighter WordPress size variant first (`-768x1024`) with graceful fallback to original image.
  - Added explicit `width/height`, `sizes`, and `decoding="async"` to stabilize rendering and reduce transfer cost.
- Removed forced reflow source in header interactions:
  - Replaced `offsetWidth` animation reset with `requestAnimationFrame` sequence.
- Reduced critical request chaining on initial load:
  - Deferred non-critical `cart summary` and `wishlist count` requests to idle/load phase instead of immediate execution.
