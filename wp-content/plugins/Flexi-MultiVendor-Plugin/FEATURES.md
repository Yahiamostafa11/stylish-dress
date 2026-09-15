# WebsiteFlexi — Features

> Source references: [PROJECT_BRAIN.md](PROJECT_BRAIN.md), [SYSTEM_RULES.md](SYSTEM_RULES.md)

1) Platform / Marketplace features
- Multivendor marketplace with owner/manager and vendor modes — implemented in [owner-dashboard.php](owner-dashboard.php).
- Customer-facing vendor pages and storefront routing — template: [templates/vendor-page.php](templates/vendor-page.php).
- Vendor onboarding & KYC workflow — handlers & endpoints in [includes/vendor-workflow.php](includes/vendor-workflow.php).
- Modular shortcodes & endpoints for marketplace pages — registration in [owner-dashboard.php](owner-dashboard.php) and [includes/vendor-workflow.php](includes/vendor-workflow.php).

2) Vendor features
- Vendor registration, application, review and timeline (KYC) — UI & logic in [includes/vendor-workflow.php](includes/vendor-workflow.php) and admin review in [admin/system-settings-view.php](admin/system-settings-view.php).
- Store profile editor (logo, cover, meta) — [includes/vendor-profile-editor.php](includes/vendor-profile-editor.php).
- Vendor dashboard (manage products, orders, stats) via shortcode — [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php) and [owner-dashboard.php](owner-dashboard.php).
- Report & review flows for vendor trust signals — templates and AJAX in [templates/vendor-page.php](templates/vendor-page.php) and [functions.php](functions.php).

3) Admin / Owner features
- Owner/manager dashboard UI and owner-only views — [owner-dashboard.php](owner-dashboard.php).
- Admin system settings & vendor management UI — [admin/system-settings.php](admin/system-settings.php) and view [admin/system-settings-view.php](admin/system-settings-view.php).
- Vendor applications management page (WP users page) integration — admin menu hook in [includes/vendor-workflow.php](includes/vendor-workflow.php).
- Timeline entries, admin notes, and actions (suspend/approve/set customer) — implemented in [admin/system-settings.php](admin/system-settings.php).

4) Product management features
- SPA-style Manage Products UI with AJAX CRUD, inline editing, image modal and modal builder — frontend modules: [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php), [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js), [assets/js/add-product-modal.js](assets/js/add-product-modal.js).
- Server-side product handlers (create, update, list, duplicate, delete) — [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) (key symbol: [`styliiiish_add_new_product`](modules/shared/ajax/manage-products-ajax.php)).
- Completeness & auto-status checks (name, thumbnail, description, price) — canonical logic in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php:around 1616).
- Attribute & taxonomy management helpers — [modules/shared/helpers-attributes.php](modules/shared/helpers-attributes.php).
- Image upload & attachment helpers — [modules/shared/helpers-images.php](modules/shared/helpers-images.php).
- Manage-products UI views (cards/table) and responsive styles — [modules/shared/ajax/views/manage-products/table.php](modules/shared/ajax/views/manage-products/table.php) and [modules/shared/ajax/views/manage-products/assets/cards.css](modules/shared/ajax/views/manage-products/assets/cards.css).

5) Wallet & revenue features
- Commission computation and wallet crediting integrations (Woo Wallet) — `woo_wallet()` usage and commission flow in [functions.php](functions.php) (see wallet crediting around line ~1733).
- Commission receiver option and fallback admin receiver logic — configurable via options in [functions.php](functions.php) and [admin/system-settings.php](admin/system-settings.php).
- Vendor wallet balance meta usage and transient caching in admin views — references in [admin/system-settings-view.php](admin/system-settings-view.php).

6) Analytics & statistics features
- Per-vendor stats: total orders, completed, returned, success rate, average rating — computed in [admin/system-settings-view.php](admin/system-settings-view.php) and [templates/vendor-page.php](templates/vendor-page.php).
- Product & dashboard stats caching using transients — caching in [admin/system-settings-view.php](admin/system-settings-view.php).
- Manage-products quick stats and skeleton loaders in UI — [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js) (functions: `showLoadingSkeleton`, `loadManageProductsPage`).

7) Security features
- Nonce verification and AJAX permission pattern documented in [PROJECT_BRAIN.md](PROJECT_BRAIN.md) and enforced in handlers (examples: `wp_verify_nonce($_POST['nonce'],'ajax_nonce')` in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php)).
- Role-based ACL: vendor roles `taj_vendor`, `taj_vendor_pending`, `taj_vendor_suspended` — role registration and checks in [includes/vendor-workflow.php](includes/vendor-workflow.php).
- Admin capability checks (manage_options) and form nonces in admin flows — [admin/system-settings.php](admin/system-settings.php) and [admin/system-settings-view.php](admin/system-settings-view.php).
- Endpoint protection for account pages — enforced in [includes/vendor-workflow.php](includes/vendor-workflow.php) template_redirect hooks.
- System rules & security checklist required by project: see [SYSTEM_RULES.md](SYSTEM_RULES.md).

8) Performance & scalability features
- AJAX pagination and filtered listing to avoid heavy page loads — frontend: [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js) / server: [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php).
- Transients caching for vendor order stats — [admin/system-settings-view.php](admin/system-settings-view.php).
- Lazy-loading of large UI modules and decoupled JS entry points — [assets/js/owner-core.js](assets/js/owner-core.js) initializes modules only when present.
- Notes on performance risks & recommended mitigations in [PROJECT_BRAIN.md](PROJECT_BRAIN.md) (avoid time() for asset versions, cache heavy queries).

9) Customization / extensibility features
- Modular architecture with included modules and shared helpers — entry points in [owner-dashboard.php](owner-dashboard.php) and modules in [modules/](modules/) (example: [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php)).
- Localized JS objects for integration: legacy `ajax_object` and modal `wfModal` — localized in [functions.php](functions.php) (symbols: `ajax_object`, `wfModal`).
- Hooks & filters available for customization (e.g. `woocommerce_is_shop`, `pre_get_posts`) — used in [owner-dashboard.php](owner-dashboard.php).
- Templates & CSS separated for themeing: [templates/vendor-page.php](templates/vendor-page.php), [assets/css/owner-style.css](assets/css/owner-style.css), [modules/shared/ajax/views/manage-products/assets/cards.css](modules/shared/ajax/views/manage-products/assets/cards.css).

10) Technical architecture highlights
- Plugin bootstrap & constants: [owner-dashboard.php](owner-dashboard.php) defines `WF_OWNER_DASHBOARD_PATH` and `WF_OWNER_DASHBOARD_URL`.
- JS → AJAX → PHP → DB flow documented in [PROJECT_BRAIN.md](PROJECT_BRAIN.md) (key files: [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js), [assets/js/add-product-modal.js](assets/js/add-product-modal.js), [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php)).
- Canonical product persistence: WP posts + WC product meta + taxonomies — implemented in [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php).
- Shared helpers: [includes/helpers.php](includes/helpers.php) (vendor helper functions), image/attribute helpers under [modules/shared/](modules/shared/).
- Security model: nonces + capability checks + server-side ownership validation — references in [PROJECT_BRAIN.md](PROJECT_BRAIN.md) and enforced in key handlers ([modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php), [admin/system-settings.php](admin/system-settings.php)).
- Known fragile points & remediation priorities are tracked in [PROJECT_BRAIN.md](PROJECT_BRAIN.md) (recommendations: standardize nonces, centralize completeness checks, tighten upload validation).

---

For quick inspection, open these key files:
- Plugin bootstrap: [owner-dashboard.php](owner-dashboard.php)
- Product AJAX: [modules/shared/ajax/manage-products-ajax.php](modules/shared/ajax/manage-products-ajax.php) (contains `styliiiish_add_new_product`)
- Manage products UI: [modules/manage-products/manage-products.php](modules/manage-products/manage-products.php)
- Frontend JS: [assets/js/owner-dashboard-theme.js](assets/js/owner-dashboard-theme.js), [assets/js/add-product-modal.js](assets/js/add-product-modal.js)
- Admin settings: [admin/system-settings.php](admin/system-settings.php), [admin/system-settings-view.php](admin/system-settings-view.php)
- Vendor workflow: [includes/vendor-workflow.php](includes/vendor-workflow.php)
- Helpers: [includes/helpers.php](includes/helpers.php)
- Styling: [assets/css/owner-style.css](assets/css/owner-style.css), [modules/shared/ajax/views/manage-products/assets/cards.css](modules/shared/ajax/views/manage-products/assets/cards.css)

If you want, I can open a concise PR-style remediation plan aligning code with [SYSTEM_RULES.md](SYSTEM_RULES.md) (nonce standardization, centralized capability checks, and completeness consolidation).

## Why Choose WebsiteFlexi?

- Built as a full SaaS-style marketplace platform.
- Designed for high-scale vendor operations.
- Secure-by-design with enforced ACL and nonce validation.
- Modular architecture for future growth.
- Proven AJAX-first UX for fast vendor workflows.
- Ready for monetization (wallet + commissions).

## Use Cases

- Multi-vendor eCommerce marketplace
- Local services marketplace
- Dropshipping platform
- Digital products marketplace
- Franchise / reseller systems


## Planned Enhancements

- Advanced revenue forecasting
- Vendor ranking system
- Automated compliance checks
- AI-assisted product optimization
- Mobile-first dashboard UI




## Why Choose WebsiteFlexi?

- Fast AJAX-first vendor workflows (modal product builder + inline edits) driven by [`loadManageProductsPage`](assets/js/owner-dashboard-theme.js) and the modal system [`wfModal`](functions.php).
- Mature WooCommerce integration: products persisted as WP posts + WC meta — handlers live in [`modules/shared/ajax/manage-products-ajax.php`](modules/shared/ajax/manage-products-ajax.php) (e.g. [`styliiiish_add_new_product`](modules/shared/ajax/manage-products-ajax.php)).
- Complete vendor lifecycle: application, KYC, status timeline and role controls in [`includes/vendor-workflow.php`](includes/vendor-workflow.php).
- Owner-grade controls and settings: admin system UI in [`admin/system-settings.php`](admin/system-settings.php) with management views in [`admin/system-settings-view.php`](admin/system-settings-view.php).
- Modular, extensible codebase: UI modules in [`modules/manage-products/manage-products.php`](modules/manage-products/manage-products.php), shared helpers in [`includes/helpers.php`](includes/helpers.php) and attribute/image helpers under `modules/shared/`.
- Security & compliance aligned with project rules: nonces, capability checks and ownership validation (see [PROJECT_BRAIN.md](PROJECT_BRAIN.md) and [SYSTEM_RULES.md](SYSTEM_RULES.md)).
- Ready-to-monetize wallet and commission flows (wallet hooks referenced in [`functions.php`](functions.php)).

## Use Cases

- Multi-vendor eCommerce marketplace — vendors list & sell fashion items; owner/manager controls platform policies and commission.
- Customer-sourced marketplace (Sell Your Dress) — customers onboard as vendors via the KYC flow in [`includes/vendor-workflow.php`](includes/vendor-workflow.php).
- Local marketplaces & pop-up shops — quick vendor onboarding and lightweight product builder (`assets/js/add-product-modal.js`, `assets/css/add-product-modal.css`).
- Order & support workflows for vendors — vendor order timeline and tracking in [`vendor-orders/vendor-orders.php`](vendor-orders/vendor-orders.php).
- Service marketplaces or resellers — flexible product attributes (`modules/shared/helpers-attributes.php`) and category/taxonomy mapping.
- Admin analytics & reconciliation — per-vendor stats and cached reports surfaced in [`admin/system-settings-view.php`](admin/system-settings-view.php).

## Product Roadmap

Short-term (next sprint)
- Standardize AJAX security: centralize nonce + capability checks into a helper in [`includes/helpers.php`](includes/helpers.php) and update handlers in [`modules/shared/ajax/manage-products-ajax.php`](modules/shared/ajax/manage-products-ajax.php). (See remediation priority in [PROJECT_BRAIN.md](PROJECT_BRAIN.md).)
- Consolidate server-side completeness checks into a single canonical function in [`modules/shared/ajax/manage-products-ajax.php`](modules/shared/ajax/manage-products-ajax.php) to remove JS/PHP divergence (`checkBeforeSave` vs PHP checks).
- Harden file uploads: enforce MIME/size checks and sanitize uploads in [`includes/vendor-workflow.php`](includes/vendor-workflow.php).

Mid-term (1–3 months)
- Wallet & payouts evolution: audit and extend wallet flows in [`functions.php`](functions.php) for scheduled payouts, refund handling and commission reporting.
- Analytics & caching: expand vendor dashboards with time-series charts and longer-lived transients in [`admin/system-settings-view.php`](admin/system-settings-view.php).
- Permission & role cleanup: formalize owner/manager/vendor separation and remove legacy global JS reliance (e.g. `window.currentProduct`).

Long-term (6+ months)
- Mobile-first dashboard rewrite: rework product builder and listing UX to a progressive SPA using the current modules (`assets/js/add-product-modal.js`, `assets/css/add-product-modal.css`) as the foundation.
- Vendor ranking & search: implement ranking signals (ratings, fulfillment rate) surfaced on [`templates/vendor-page.php`](templates/vendor-page.php).
- AI-assisted product optimization: integrate automated title/description suggestions and image QC into the add-product flow (`assets/js/add-product-modal.js` → AJAX → [`modules/shared/ajax/manage-products-ajax.php`](modules/shared/ajax/manage-products-ajax.php)).
- Multi-region & performance scale: introduce read replicas/caching and remove fragile hard-coded template/post IDs (follow [PROJECT_BRAIN.md](PROJECT_BRAIN.md) guidance).

Notes
- All roadmap items should follow the rules in [SYSTEM_RULES.md](SYSTEM_RULES.md): preserve backward compatibility, avoid heavy queries in loops, and validate security for every endpoint.
