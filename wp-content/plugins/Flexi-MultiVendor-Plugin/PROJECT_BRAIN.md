# WebsiteFlexi — PROJECT_BRAIN

Short summary
- Purpose: Multi-vendor marketplace + owner/manager dashboard for vendors to create/edit products, manage orders, and handle vendor workflows.
- Main stack: WordPress + WooCommerce (products as WP posts) + jQuery AJAX frontend.

---

## Full project purpose
Provide an owner/manager/vendor dashboard and marketplace with:
- Vendor application & KYC workflow ([includes/vendor-workflow.php](includes/vendor-workflow.php))
- Vendor product management UI (owner + vendor) with zero-page-reload CRUD, image upload, attribute handling, and preview ([modules/manage-products/manage-products.php](modules/manage-products/manage-products.php), [assets/js/add-product-modal.js](assets/js/add-product-modal.js))
- Admin system settings UI ([admin/system-settings.php](admin/system-settings.php), [admin/system-settings-view.php](admin/system-settings-view.php))
- Vendor orders UI and timeline ([vendor-orders/vendor-orders.php](vendor-orders/vendor-orders.php), [vendor-orders/vendor-orders.js](vendor-orders/vendor-orders.js))

---

## Entry points
- Plugin bootstrap: [owner-dashboard.php](owner-dashboard.php)
- Owner dashboard shortcode / frontend: see [includes/vendor-workflow.php](includes/vendor-workflow.php) and shortcode registration in [owner-dashboard.php](owner-dashboard.php)
- Manage products AJAX handlers: [`styliiiish_add_new_product`](modules/shared/ajax/manage-products-ajax.php), and many other `wp_ajax_` callbacks in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php)
- Frontend JS loaders: [assets/js/owner-core.js](assets/js/owner-core.js) → initializes ManageProductsModule; main UI JS is [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js) and the add/edit modal JS [assets/js/add-product-modal.js](assets/js/add-product-modal.js)
- Admin pages: [admin/system-settings.php](admin/system-settings.php) + view [admin/system-settings-view.php](admin/system-settings-view.php)

---

## JS → AJAX → PHP → DB flows (product creation & editing)

1) UI action
- User clicks "Add product" / "Edit" in UI rendered by [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php) and view [modules/shared/ajax/views/manage-products/table.php](modules/shared/ajax/views/manage-products/table.php).

2) Frontend JS
- Modal open / form fills: [assets/js/add-product-modal.js](assets/js/add-product-modal.js)
- Global owner dashboard interactions: [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js)
- Controls / init: [assets/js/owner-core.js](assets/js/owner-core.js)
- Variables used client-side: `wfModal` (localized to wf-add-modal) — see [functions.php](functions.php) localization; `ajax_object` (legacy) — localized in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) and [functions.php](functions.php)
- Key functions: [`loadManageProductsPage`](assets/js/owner-dashboard-theme.js), [`resetBuilder`](assets/js/add-product-modal.js), `sendRequest` / AJAX wrappers (in theme JS).

3) AJAX request
- Add new product: POST to `admin-ajax.php` with `action=styliiiish_add_new_product` handled by [`styliiiish_add_new_product`](modules/shared/ajax/manage-products-ajax.php).
  - Nonce field: `nonce` (checked with `wp_verify_nonce($_POST['nonce'],'ajax_nonce')`) — see start of [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php).
  - Modal save uses `wfModal.nonce` and `wfModal.ajax` for URL — used in [assets/js/add-product-modal.js](assets/js/add-product-modal.js).

4) PHP handler
- Handlers live in: [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) and some inline handlers in [functions.php](functions.php) and [owner-dashboard.php](owner-dashboard.php).
- Typical flow:
  - verify nonce & user: `wp_verify_nonce`, `get_current_user_id()`
  - sanitize inputs
  - create/update WP posts (`wp_insert_post`, `wp_update_post`)
  - set taxonomy terms (`wp_set_object_terms`) for attributes (taxonomies `pa_*`) — see attribute loop in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php)
  - update post meta (`update_post_meta`) like `_product_attributes`, manual-deactivate meta `_styliiiish_manual_deactivate`, etc.
  - attachment handling via media APIs and `set_post_thumbnail`
  - compute completeness/auto-status logic (checks in [modules/shared/ajax/manage-products-ajax.php:1616](modules/shared/ajax/manage-products-ajax.php))

5) DB persistence
- Standard WP/WooCommerce:
  - wp_posts row (post_type = `product`)
  - wp_postmeta keys: `_product_attributes`, `_regular_price`, `_sale_price`, `_sku`, custom keys like `_styliiiish_manual_deactivate`
  - taxonomy terms & term relationships for categories and `pa_*` attributes
  - attachments: wp_posts entries (post_type = `attachment`) + postmeta

6) Response → UI update
- PHP returns JSON via `wp_send_json_success` / `wp_send_json_error` to JS.
- JS updates DOM (table row, preview, toast, modal close/open) using code in [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js) and [assets/js/add-product-modal.js](assets/js/add-product-modal.js).

Key server handlers (critical)
- [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) — main product AJAX handlers (create, edit, search, list)
  - Symbol: [`styliiiish_add_new_product`](modules/shared/ajax/manage-products-ajax.php)
  - Completeness checks: lines around [1616](modules/shared/ajax/manage-products-ajax.php)
- Inline update hooks in [functions.php](functions.php): e.g. `add_action('wp_ajax_styliiiish_update_status', ...)`
- Image handlers / helpers: included helpers files via [owner-dashboard.php](owner-dashboard.php) (`modules/shared/helpers-images.php`, `modules/shared/helpers-attributes.php`)

---

## Critical files (quick list)
- Plugin bootstrap: [owner-dashboard.php](owner-dashboard.php)
- Product AJAX and views: [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php), [modules/shared/ajax/views/manage-products/table.php](modules/shared/ajax/views/manage-products/table.php)
- Manage products UI: [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php)
- Product modal JS: [assets/js/add-product-modal.js](assets/js/add-product-modal.js)
- Owner dashboard JS & UX: [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js)
- Core init JS: [assets/js/owner-core.js](assets/js/owner-core.js)
- Server-side shared logic: [functions.php](functions.php), [includes/helpers.php](includes/helpers.php)
- Vendor workflow & KYC: [includes/vendor-workflow.php](includes/vendor-workflow.php)
- Admin settings: [admin/system-settings.php](admin/system-settings.php) and [admin/system-settings-view.php](admin/system-settings-view.php)
- Vendor orders: [vendor-orders/vendor-orders.php](vendor-orders/vendor-orders.php) and [vendor-orders/vendor-orders.js](vendor-orders/vendor-orders.js)
- Styles: [assets/css/owner-style.css](assets/css/owner-style.css), [assets/css/add-product-modal.css](assets/css/add-product-modal.css)

---

## Important variables & meta keys
Client-side variables (JS)
- `wfModal` (localized by [functions.php](functions.php)) — contains `wfModal.nonce`, `wfModal.ajax` used by add modal ([assets/js/add-product-modal.js](assets/js/add-product-modal.js))
- `ajax_object` (legacy localization) — in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) and [functions.php](functions.php)
- `window.currentProductId` — currently editing/preview product ID ([assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js))
- `pendingAttrs` — temporary client-side attribute store ([assets/js/add-product-modal.js](assets/js/add-product-modal.js))
- `currentFilters` — filters for the products list ([assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js))

Server-side meta keys & options
- Product / postmeta:
  - `_product_attributes` — WooCommerce structured attributes (used/updated in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php))
  - `_styliiiish_manual_deactivate` — manual deactivation flag
  - post thumbnail ID via standard WP postmeta `_thumbnail_id`
  - standard WC keys `_regular_price`, `_sale_price`, `_sku`
- Vendor user meta:
  - `taj_utility_bill`, `taj_id_front`, `taj_id_back`, other KYC keys in [admin/system-settings-view.php](admin/system-settings-view.php) and [includes/vendor-workflow.php](includes/vendor-workflow.php)
  - `taj_vendor_timeline` — vendor status timeline (array)
  - `vendor_wallet_balance` — vendor wallet balance (float)
- Options:
  - `wf_products_layout` — layout option used in [modules/shared/ajax/manage-products-ajax.php:1510](modules/shared/ajax/manage-products-ajax.php)
  - `styliiiish_allowed_manager_ids` — manager IDs used in product queries
  - `wf_vendor_orders` nonces localized in [owner-dashboard.php](owner-dashboard.php)

User roles (ACL)
- `taj_vendor`, `taj_vendor_pending`, `taj_vendor_suspended` — vendor role states
- Owner/manager roles determined by `wf_od_get_manager_ids()` and `wf_od_get_dashboard_ids()` (helpers in [includes/helpers.php](includes/helpers.php))

Custom DB tables
- Support/chat tables created in [owner-dashboard.php](owner-dashboard.php): `$wpdb->prefix.'wf_support_chat'`, `$wpdb->prefix.'wf_support_assignments'` etc.

---

## Security model
- Nonces:
  - AJAX: `wp_verify_nonce($_POST['nonce'],'ajax_nonce')` in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php)
  - Admin forms: `check_admin_referer(...)` in [admin/system-settings.php](admin/system-settings.php), and `wp_nonce_field` use in views ([admin/system-settings-view.php](admin/system-settings-view.php))
  - Additional nonces localized for specific scripts (`wfModal.nonce`) in [functions.php](functions.php)
- Capability checks:
  - Admin-only actions use `current_user_can('manage_options')` in admin handlers ([admin/system-settings.php](admin/system-settings.php))
  - Many AJAX endpoints check `get_current_user_id()` / `is_user_logged_in()` before proceeding ([modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php))
- Server-side validation:
  - Input sanitization (`sanitize_text_field`, `sanitize_email`, `sanitize_textarea_field`) and checks in many places (e.g. [admin/system-settings.php](admin/system-settings.php), [email.php](email.php))
- File uploads: `wp_handle_upload` used in [includes/vendor-workflow.php](includes/vendor-workflow.php)
- Template / ID hardcodes: template IDs and excluded post IDs are hard-coded — requires caution.

---

## Known fragile points (observed)
- Inconsistent nonce usage:
  - Multiple localized objects: `ajax_object` (legacy) vs `wfModal`. Some endpoints expect `nonce` while others use different keys. See [modules/shared/ajax/manage-products-ajax.php:54](modules/shared/ajax/manage-products-ajax.php) and [assets/js/add-product-modal.js:142](assets/js/add-product-modal.js).
  - Owner note: comments in code mention "AJAX without nonce — high severity" ([owner-dashboard.php](owner-dashboard.php)).
- Mixed capability checks:
  - Some AJAX handlers rely only on `is_user_logged_in()` without role checks. Risk: manager/owner vs vendor permission separation must be enforced server-side in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php).
- Hard-coded IDs:
  - Template IDs (e.g. `29323`, `29321`) and excluded post IDs (`post__not_in` => [1905,1954]) in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) and queries — fragile across environments.
- Client-side logic controlling status & completeness:
  - Complexity split between JS "checkBeforeSave" ([assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js)) and PHP completeness checks ([modules/shared/ajax/manage-products-ajax.php:1616](modules/shared/ajax/manage-products-ajax.php)). Risk of divergence.
- Inline/legacy global JS:
  - Inline script usage and placeholders; some functions rely on globals like `window.currentProductId` and `pendingAttrs`; race conditions possible.
- File upload validation:
  - `wp_handle_upload` used but upload file type / size validation needs review in [includes/vendor-workflow.php](includes/vendor-workflow.php).
- Asset versioning & caching:
  - Use of `time()` for asset versioning (development) can prevent proper caching ([functions.php](functions.php)).
- SQL/custom-table operations:
  - Custom tables created with dbDelta in [owner-dashboard.php](owner-dashboard.php) — migrations risk if schema changes.
- Localization strings scattered:
  - Many translatable strings across admin and frontend; translation file (.po/.mo) present but may be incomplete (many empty msgstr).

---

## Short remediation priorities
1. Standardize AJAX nonce & capability checks for all endpoints (use centralized helper in [includes/helpers.php](includes/helpers.php)).
2. Replace hard-coded template/post IDs with options / constants.
3. Consolidate completeness checks to server-side canonical source (single function in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php)).
4. Add stricter file upload checks in [includes/vendor-workflow.php](includes/vendor-workflow.php).
5. Audit endpoints for missing `wp_verify_nonce` / capability checks.

---

## Quick reference links (open these to inspect)
- [owner-dashboard.php](owner-dashboard.php)
- [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) — main product AJAX
  - [`styliiiish_add_new_product`](modules/shared/ajax/manage-products-ajax.php)
- [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php)
- [modules/shared/ajax/views/manage-products/table.php](modules/shared/ajax/views/manage-products/table.php)
- [assets/js/add-product-modal.js](assets/js/add-product-modal.js)
- [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js)
  - `loadManageProductsPage` function in [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js)
  - `checkBeforeSave` function in [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js)
- [assets/js/owner-core.js](assets/js/owner-core.js)
- [functions.php](functions.php)
- [includes/vendor-workflow.php](includes/vendor-workflow.php)
- [includes/helpers.php](includes/helpers.php)
- [admin/system-settings.php](admin/system-settings.php)
- [admin/system-settings-view.php](admin/system-settings-view.php)
- [vendor-products.php](vendor-products.php)
- [vendor-orders/vendor-orders.php](vendor-orders/vendor-orders.php)
- [assets/css/owner-style.css](assets/css/owner-style.css)

---

If you want, I can convert the remediation priorities into concrete PR-style changes (nonce standardization and a centralized permission helper).

---

## Comprehensive Audit (2026-03-11)

### Scope and method
- Type: static code/config audit (repository-level), no live penetration test and no server shell access.
- Covered layers:
  - WordPress core/runtime routing and hardening (`index.php`, `.htaccess`, `wp-config.php`)
  - Custom plugin security/ACL/AJAX (`Flexi-MultiVendor-Plugin`)
  - Laravel storefront integration and data bridge (`laravel_home/routes/web.php`, `wishlist-bridge.php`)
  - Operational compatibility points (DB, routes, caching, translation stack)

### Operating model (current system)
- Platform topology (hybrid):
  - Root front controller in `index.php` performs manual route switching between Laravel and WordPress.
  - WordPress remains the source of truth for products/orders/users.
  - Laravel reads/writes directly against WordPress tables (`wp_posts`, `wp_postmeta`, `wp_terms`, `wp_comments`, etc).
- Runtime signals detected:
  - WordPress version: `6.9.1` (`wp-includes/version.php`)
  - WooCommerce version: `10.5.2` (`wp-content/plugins/woocommerce/woocommerce.php`)
  - Laravel: `12.x`, requires PHP `^8.2` (`laravel_home/composer.json`)
  - Hosting/caching indicators: LiteSpeed/Hostinger markers in `.htaccess` and `default.php`.
- Complexity indicators:
  - `laravel_home/routes/web.php`: ~4882 lines
  - `modules/shared/ajax/manage-products-ajax.php`: ~2315 lines
  - `vendor-orders/vendor-orders.php`: ~2680 lines

### Laravel <-> WordPress compatibility assessment
- Overall status: **working but tightly coupled and fragile**.
- Why it works now:
  - Both apps use same MySQL database and same `wp_` prefix.
  - Root `index.php` explicitly routes some account/checkout/vendor paths to WordPress.
  - Laravel storefront queries WP data directly, so content parity is immediate.
- Fragility points:
  - Hard dependency on WP schema/table names and plugins (TranslatePress/WPML tables).
  - Any route not explicitly protected in `index.php` defaults to Laravel.
  - Business logic duplicated across JS/PHP/Laravel in several flows.
- Compatibility score (practical): **6.5/10**.

### Risk register (ordered by severity)

#### Critical
1. Secret material present in runtime config files.
  - Evidence: `wp-config.php`, `laravel_home/.env` contain production DB/application secrets.
  - Note: both files are currently ignored by git (`.gitignore` / `laravel_home/.gitignore`), but exposure risk remains if backup/misconfig/file disclosure happens.

2. Public debug endpoint in production routes.
  - Evidence: `laravel_home/routes/web.php` exposes `/debug/wpml-product/{slug}` and returns internal translation/post mapping.
  - Impact: information disclosure, easier reconnaissance.

3. KYC file uploads lack explicit allowlist controls.
  - Evidence: `includes/vendor-workflow.php` uses `wp_handle_upload(..., ['test_form' => false])` without explicit MIME/extension/size policy.
  - Impact: upload abuse risk (malicious or oversized files).

#### High
1. Database error leakage to clients.
  - Evidence: `vendor-orders/vendor-orders.php` returns `wp_send_json_error($wpdb->last_error)` on insert failure.
  - Impact: SQL/internal schema leakage.

2. No visible throttling/rate-limit on public write endpoints.
  - Evidence: POST review/report/wishlist endpoints in `laravel_home/routes/web.php` with no route-level throttle.
  - Impact: spam/flood abuse, moderation overhead.

3. Excessive coupling and monolith route file.
  - Evidence: very large `routes/web.php` with direct DB queries to WP tables across many concerns.
  - Impact: regression risk, slower onboarding, higher change failure rate.

4. Route arbitration risk between frameworks.
  - Evidence: root `index.php` defaults to Laravel for non-explicit WordPress routes.
  - Impact: silent SEO/404 regressions or plugin endpoint breakage when new WP pages/endpoints are added.

#### Medium
1. Hard-coded IDs and environment-bound constants.
  - Evidence: template/product IDs and fixed assumptions in `manage-products-ajax.php` and related flows.
  - Impact: migration/staging drift.

2. Version compatibility drift.
  - Evidence: custom plugin header in `owner-dashboard.php` says "Tested up to: 6.4" while site runs WP 6.9.1.
  - Impact: unverified behavior on newer core.

3. Duplicate env keys may create config ambiguity.
  - Evidence: repeated `CACHE_STORE` and `QUEUE_CONNECTION` entries in `laravel_home/.env`.
  - Impact: unexpected runtime behavior.

4. Debug logging remains in business flows.
  - Evidence: `error_log(...)` traces in `includes/vendor-workflow.php` and report flow in `functions.php`.
  - Impact: noisy logs and potential data exposure in shared hosting logs.

### Weakness summary
- Security posture is uneven: strong nonce/capability checks in many endpoints, but still a few high-impact gaps.
- Integration pattern is fast to ship but hard to maintain at scale (direct DB coupling + route-level arbitration).
- Operational hygiene needs tightening (debug routes/logs/config duplication).

### Recommended improvement plan

#### Phase 0 (within 24h)
1. Remove or protect `/debug/wpml-product/{slug}` behind admin auth and non-production guard.
2. Replace DB error responses with generic messages (`Operation failed`) and log server-side only.
3. Enforce upload policy in KYC flow:
  - Allowed MIME/extensions (PDF/JPG/PNG/WEBP only)
  - Max file size
  - Reject double extensions
4. Normalize `.env` duplicated keys and rotate secrets if ever exposed outside server.

#### Phase 1 (within 7 days)
1. Add request throttling for public POST endpoints (reviews/reports/wishlist bridge actions).
2. Split Laravel `routes/web.php` into controllers/services by domain (catalog, product, wishlist, content).
3. Centralize Laravel access to WP tables via repository/service layer to reduce query duplication.
4. Replace hard-coded IDs with options/config values managed in WP admin.

#### Phase 2 (within 30 days)
1. Create a contract for framework boundaries:
  - Which paths belong to WP vs Laravel
  - Health checks and fallback behavior
2. Add integration tests for critical journeys:
  - Product page
  - Wishlist add/remove
  - Checkout/account redirects
3. Add security checklist in deployment pipeline:
  - No debug routes in production
  - No verbose DB errors to client
  - Upload restrictions validated

### Executive conclusion
- The current architecture is functional and commercially usable, but it is operating with **high coupling** and a few **high/critical security risks** that should be addressed immediately.
- If Phase 0 + Phase 1 are implemented, expected gains are:
  - lower abuse surface,
  - more stable Laravel/WP coexistence,
  - faster and safer releases.


## Brain Version
- v1.0 — Initial architecture mapping (2026-02-05)
- v1.1 — Added wallet flow (…)
- v1.2 — Added comprehensive site/system compatibility + risk audit (2026-03-11)
