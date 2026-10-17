# Styliiiish API (v1)

Base URL: `https://styliiiish.com/api` · JSON in/out · UTF-8.

## Conventions

- **Language:** send `?lang=ar|en` (or an `Accept-Language` header). Arabic product text comes from the TranslatePress dictionary; anything untranslated falls back to English. Default is English when nothing is sent.
- **Auth:** `Authorization: Bearer <token>` (token from login/register, valid 30 days). Changing the password signs out every older token. On `401` the app must clear its token and go to login.
- **Errors:** `{ "message": "…" }` with the HTTP status. Validation errors are `422` with `{ "message", "errors": { field: [..] } }`. `429` = rate limited.
- **Lists:** `{ "data": [...], "meta": {page, per_page, total, last_page, has_more} }`. Paginate with `?page=1&per_page=20` (`limit` still works as `per_page`).
- **Images:** absolute URLs. `image_thumb` (when present) is a small rendition for list screens; use `image` for detail.
- **Money:** EGP, plain numbers.

## App bootstrap

### `GET /mobile/config?platform=android|ios&version=1.0.0`
Call on every launch. Returns `maintenance`, `app.{min_version, latest_version, force_update, update_available, store_url}`, `features`, `contact`, `currency`, `languages`, and `pages` (legal/about/FAQ URLs to open in a WebView; append `?lang=ar|en`).
If `app.force_update` is true, block the app with an update screen. If `maintenance` is true, show a maintenance screen.

## Catalog

| Endpoint | Notes |
|---|---|
| `GET /products` | Query: `search`, `category` (slug), `size` (slug), `min_price`, `max_price`, `page`, `per_page`, `lang`. Each item: `id, name, slug, short_description, price, regular_price, sale_price, on_sale, image, image_thumb, offers[{kind: rent\|new\|used, price, in_stock}]` (cheapest per kind, rent first). |
| `GET /products/{id}` | Adds `description`, `gallery[]`, and **`offers`**: `[{slug, label, price, in_stock, variations[{variation_id, label, price, in_stock}]}]` (rent first). Products without offers have flat `sizes`. |
| `GET /categories` | `[{slug, name, count}]` |
| `GET /sizes` | Size filter values, in order XS→3XL |
| `GET /price-range` | `{data:{min, max, breakpoints[]}}` for the price slider |

## Auth & account

| Endpoint | Body | Result |
|---|---|---|
| `POST /auth/register` | `name, email, password(min 6), phone?` | `201 {token, user{id,name,email}}` |
| `POST /auth/login` | `email, password` | `{token, user}` |
| `GET /auth/me` 🔒 | – | `{user}` |
| `POST /auth/forgot-password` | `email` | `{message:"ok"}` always (never reveals whether the email exists); sends a 6-digit code, valid 10 min |
| `POST /auth/reset-password` | `email, code, password` | `{token, user}` (signed in) |
| `POST /account/password` 🔒 | `current_password, new_password` | `{token, user}` – **replace the stored token** |
| `POST /account/email` 🔒 | `new_email, current_password` | Sends a confirmation link to the new address |
| `POST /account/email/confirm` | `token` | Email changed |
| `DELETE /account` 🔒 (or `POST /account/delete`) | `password` | Erases personal data, removes the user's امنحي listings, signs out. `403` for staff accounts. Orders are kept (bookkeeping) but detached from the person. |

## امنحي marketplace

| Endpoint | Notes |
|---|---|
| `GET /amnahi/listings` | `page, per_page` · item: `id, name, short_description, price, image, image_thumb, seller_name` |
| `GET /amnahi/listings/{id}` | `+ description, dress_type, gallery[], seller_id, status`. Pending listings are visible to their owner only. |
| `POST /amnahi/listings` 🔒 | `multipart/form-data`: `title, description?, price, dress_type?, images[] (1–2, jpg/png/webp ≤ 5 MB)`. Result `status: pending` (moderated) – seller gets an email, and a push when it goes live. |
| `GET /amnahi/mine` 🔒 | The user's own listings with `status` (`pending`/`publish`) |
| `POST /amnahi/listings/{id}/interest` 🔒 | Starts (or returns) the buyer↔seller conversation → `{conversation_id}` |
| `GET /conversations` 🔒 | `page, per_page` · `id, listing_id, listing_title, other_user_name, last_message, updated_at` |
| `GET /conversations/{id}/messages` 🔒 | `{data:[{id, sender_user_id, body, created_at}], my_id}` |
| `POST /conversations/{id}/messages` 🔒 | `{body ≤ 2000}` → `201 {data:{id}}` |

Chat is poll-based: refresh the open thread every few seconds, and rely on push when the app is in the background.

## Checkout

`POST /checkout`
```json
{
  "customer": {"name","email","phone","address_1","city","country":"EG"},
  "items": [{
    "product_id": 123,
    "variation_id": 456,          // the chosen offer/size variation
    "quantity": 1,
    "custom_size": false,         // true = custom measurements on the chosen offer
    "measurements": {"Bust":"90"},// only for custom_size
    "notes": ""
  }]
}
```
→ `{order_id, order_key, total, currency, pay_url}`. Open `pay_url` in a WebView (Paymob hosted payment page); payment completes there. The cart lives on the device.

## Reviews

- `GET /reviews/cards` → `[{id, source: google|facebook|instagram|messenger|whatsapp, image, width, height}]` (customer review screenshots).
- `POST /reviews` `{name, message(≥8)}` → emailed to the owner.

## Push notifications (FCM)

1. After login (and whenever FCM rotates the token): `POST /devices` 🔒 `{token, platform: android|ios, locale: ar|en, app_version}`.
2. On logout: `DELETE /devices` `{token}` (no auth needed).
3. Notification `data` payload: `{type: "chat", conversation_id}` → open that thread; `{type: "listing_live", listing_id}` → open that listing.

Server side it is active as soon as `FIREBASE_SERVICE_ACCOUNT_JSON` (path to the Firebase service-account JSON) is set in `laravel_home/.env`; until then sending is a silent no-op and `features.push` is `false`.

## Other

- `POST /track` – page-view counter used by the website; the app does not need it.
- Env knobs for the app: `MOBILE_MIN_VERSION`, `MOBILE_LATEST_VERSION`, `MOBILE_ANDROID_URL`, `MOBILE_IOS_URL`, `MOBILE_MAINTENANCE`, `MOBILE_WHATSAPP`, `MOBILE_INSTAGRAM`, `MOBILE_FACEBOOK`, `MOBILE_TIKTOK`.

## WhatsApp messages

The server sends WhatsApp messages automatically (the app only needs to keep the user's phone number and preference up to date):

| Event | Trigger | Template |
|---|---|---|
| Welcome | `POST /auth/register` with a `phone` | `styliiiish_welcome` |
| Order placed | `POST /checkout` (to `customer.phone`, includes the `pay_url`) | `styliiiish_order_placed` |
| Listing received / live | `POST /amnahi/listings` (live right away only if auto-publish) · moderator publishes it | `styliiiish_listing_received`, `styliiiish_listing_live` |
| Someone is interested | `POST /amnahi/listings/{id}/interest` (new conversation) → to the seller | `styliiiish_listing_interest` |

Messages go out in the language of the request (`?lang=` / `Accept-Language`) – for the seller-side ones, in the language saved in the profile.

- `GET /account/notifications` 🔒 → `{data:{phone, whatsapp_enabled, language}}`
- `PUT|POST /account/notifications` 🔒 `{phone?, whatsapp_enabled?, language?}` – `422` if the phone is not a valid number. Egyptian local numbers (`01…`) and international (`+20…`, `0020…`) are accepted.
- `GET /mobile/config` → `features.whatsapp_notifications` tells whether the server has WhatsApp switched on.

Server setup (`laravel_home/.env`): `WHATSAPP_DRIVER=cloud` (Meta WhatsApp Business Cloud API; `log` = record only), `WHATSAPP_TOKEN`, `WHATSAPP_PHONE_NUMBER_ID`, optional `WHATSAPP_MODE=template|text`, `WHATSAPP_API_VERSION`, `WHATSAPP_EN_CODE`. Business-initiated messages need Meta-approved templates: create the five template names above in the WhatsApp Manager (languages `ar` and `en`, body = the text in `routes/whatsapp_helpers.php`, `{{1}}…` placeholders). Every attempt is logged in the `whatsapp_messages` table.
