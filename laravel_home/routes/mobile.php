<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

require_once __DIR__ . '/mobile_helpers.php';
require_once __DIR__ . '/whatsapp_helpers.php';

/*
|--------------------------------------------------------------------------
| Mobile app endpoints
|--------------------------------------------------------------------------
|
| Everything the Flutter app needs on top of the storefront API: remote config / version
| gate, push-device registry, account deletion (required by the App Store and Google Play),
| and the review screenshots. Auth is the same Bearer token as the website
| ($amnahiAuthUser comes from routes/amnahi.php, which web.php loads first).
*/

// Public: version gate, feature flags, contact + legal links. The app calls this on launch
// with ?platform=android|ios&version=1.2.3 (or X-Platform / X-App-Version headers).
Route::get('/api/mobile/config', function (Request $request) {
    $cfg = config('styliiiish.mobile');
    $platform = strtolower((string) ($request->query('platform') ?? $request->header('X-Platform', '')));
    $version = trim((string) ($request->query('version') ?? $request->header('X-App-Version', '')));
    $site = rtrim((string) (config('styliiiish.wp_public_url') ?: $request->getSchemeAndHttpHost()), '/');

    $storeUrl = $platform === 'ios' ? $cfg['ios_url'] : $cfg['android_url'];
    $page = fn (string $path) => $site . $path;

    return response()->json(['data' => [
        'api_version' => 1,
        'maintenance' => $cfg['maintenance'],
        'app' => [
            'min_version' => $cfg['min_version'],
            'latest_version' => $cfg['latest_version'],
            'force_update' => $version !== '' && version_compare($version, $cfg['min_version'], '<'),
            'update_available' => $version !== '' && version_compare($version, $cfg['latest_version'], '<'),
            'store_url' => $storeUrl !== '' ? $storeUrl : null,
        ],
        'languages' => ['ar', 'en'],
        'default_language' => 'ar',
        'currency' => ['code' => 'EGP', 'symbol_ar' => 'ج.م', 'symbol_en' => 'EGP'],
        'features' => [
            'amnahi' => true,
            'chat' => true,
            'rent' => true,
            'reviews' => true,
            'push' => (bool) mobilePushCredentials(),
            'google_login' => false,
            'whatsapp_notifications' => (bool) config('styliiiish.whatsapp.driver'),
        ],
        'contact' => [
            'email' => (string) config('styliiiish.reviews.notify_email'),
            'whatsapp' => $cfg['whatsapp'] ?: null,
            'instagram' => $cfg['instagram'] ?: null,
            'facebook' => $cfg['facebook'] ?: null,
            'tiktok' => $cfg['tiktok'] ?: null,
        ],
        // Static content pages — open these in a WebView (append ?lang=ar|en).
        'pages' => [
            'about' => $page('/about'),
            'contact' => $page('/contact'),
            'faq' => $page('/faq'),
            'terms' => $page('/terms-conditions'),
            'privacy' => $page('/privacy-policy'),
            'refund' => $page('/refund-policy'),
            'shipping' => $page('/shipping-policy'),
            'cookies' => $page('/cookie-policy'),
            'marketplace_policy' => $page('/marketplace-policy'),
        ],
    ]]);
});

// Public: customer review screenshots shown on the Reviews page, with absolute image URLs.
Route::get('/api/reviews/cards', function (Request $request) {
    $site = rtrim((string) (config('styliiiish.wp_public_url') ?: $request->getSchemeAndHttpHost()), '/');
    $manifest = null;
    foreach ([dirname(base_path()) . '/react-dist/reviews/cards/manifest.json', dirname(base_path()) . '/react-frontend/public/reviews/cards/manifest.json'] as $path) {
        if (is_file($path)) {
            $manifest = json_decode((string) file_get_contents($path), true);
            break;
        }
    }

    $cards = collect(is_array($manifest) ? $manifest : [])
        ->filter(fn ($c) => is_array($c) && !empty($c['file']))
        ->map(fn ($c) => [
            'id' => (string) ($c['id'] ?? $c['file']),
            'source' => (string) ($c['source'] ?? ''),
            'image' => $site . '/reviews/cards/' . rawurlencode((string) $c['file']),
            'width' => (int) ($c['w'] ?? 0),
            'height' => (int) ($c['h'] ?? 0),
        ])
        ->values();

    return response()->json(['data' => $cards]);
});

// Register (or refresh) this phone's FCM token for the signed-in user.
RateLimiter::for('mobile-devices', fn (Request $r) => Limit::perMinute(30)->by('devices|' . $r->ip()));

Route::post('/api/devices', function (Request $request) use ($amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $data = $request->validate([
        'token' => 'required|string|min:20|max:4096',
        'platform' => 'required|in:android,ios',
        'locale' => 'nullable|in:ar,en',
        'app_version' => 'nullable|string|max:20',
    ]);

    DB::table('mobile_devices')->updateOrInsert(
        ['token_hash' => hash('sha256', $data['token'])],
        [
            'user_id' => $user->ID,
            'token' => $data['token'],
            'platform' => $data['platform'],
            'locale' => $data['locale'] ?? 'ar',
            'app_version' => $data['app_version'] ?? null,
            'last_seen_at' => now(),
            'updated_at' => now(),
            'created_at' => now(),
        ]
    );

    return response()->json(['message' => 'ok']);
})->middleware('throttle:mobile-devices');

// Call on logout (token in the body). Needs no login: the token itself is the secret.
Route::delete('/api/devices', function (Request $request) {
    $token = (string) $request->input('token', '');
    if ($token !== '') {
        DB::table('mobile_devices')->where('token_hash', hash('sha256', $token))->delete();
    }

    return response()->json(['message' => 'ok']);
})->middleware('throttle:mobile-devices');

// Permanently closes the signed-in customer's account (store requirement for apps with sign-up).
// Personal data is erased; WooCommerce orders stay (tax/bookkeeping) but are detached from the person.
$mobileDeleteAccount = function (Request $request) use ($amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $data = $request->validate(['password' => 'required|string']);
    if (!amnahiCheckPassword($data['password'], (string) $user->user_pass)) {
        return response()->json(['message' => 'كلمة المرور غلط'], 422);
    }

    // Staff / owner accounts are never self-deleted from the app.
    $caps = @unserialize((string) DB::table('wp_usermeta')->where('user_id', $user->ID)->where('meta_key', 'wp_capabilities')->value('meta_value'), ['allowed_classes' => false]);
    $roles = is_array($caps) ? array_keys(array_filter($caps)) : [];
    if (array_diff($roles, ['subscriber', 'customer'])) {
        return response()->json(['message' => 'الحساب ده حساب إدارة ومينفعش يتحذف من التطبيق'], 403);
    }

    $email = (string) $user->user_email;
    $uid = (int) $user->ID;

    DB::transaction(function () use ($uid) {
        // The user's امنحي listings come off the site.
        DB::table('wp_posts')->where('post_author', $uid)->where('post_type', 'product')->update(['post_status' => 'trash']);
        DB::table('mobile_devices')->where('user_id', $uid)->delete();

        DB::table('wp_usermeta')->where('user_id', $uid)->where('meta_key', 'not like', 'wp\_%')->delete();
        DB::table('wp_usermeta')->updateOrInsert(['user_id' => $uid, 'meta_key' => 'nickname'], ['meta_value' => 'deleted']);

        DB::table('wp_users')->where('ID', $uid)->update([
            'user_login' => 'deleted-' . $uid,
            'user_nicename' => 'deleted-' . $uid,
            'user_email' => 'deleted-' . $uid . '@deleted.invalid',
            'user_url' => '',
            'display_name' => 'مستخدم محذوف',
            'user_pass' => amnahiHashPassword(Str::random(40)),
            'user_activation_key' => '',
        ]);
    });

    try {
        amnahiMailSecurityNotice($email, 'حسابك على Styliiiish اتقفل واتمسحت بياناتك الشخصية بناءً على طلبك.');
    } catch (\Throwable $e) {
        report($e);
    }

    return response()->json(['message' => 'deleted']);
};
Route::delete('/api/account', $mobileDeleteAccount)->middleware('throttle:amnahi-account');
Route::post('/api/account/delete', $mobileDeleteAccount)->middleware('throttle:amnahi-account');

// Called by the WordPress mu-plugin when a moderator publishes a listing: pushes "your dress is live"
// to the seller's phone. Same shared secret as the order bridge; not reachable without it.
Route::post('/api/internal/listing-live', function (Request $request) {
    $secret = (string) config('styliiiish.commerce.order_bridge_secret');
    if ($secret === '' || !hash_equals($secret, (string) $request->header('X-Bridge-Secret', ''))) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $post = DB::table('wp_posts')->where('ID', (int) $request->input('post_id'))->where('post_type', 'product')->first(['ID', 'post_author', 'post_title']);
    if ($post) {
        mobilePushToUser((int) $post->post_author, [
            'ar' => ['فستانك اتنشر 🎉', '«' . $post->post_title . '» بقى متاح على امنحي'],
            'en' => ['Your dress is live 🎉', '“' . $post->post_title . '” is now on Amnahi'],
        ], ['type' => 'listing_live', 'listing_id' => $post->ID]);
        waNotifyUser((int) $post->post_author, 'styliiiish_listing_live', [
            DB::table('wp_users')->where('ID', $post->post_author)->value('display_name'),
            $post->post_title,
            amnahiSiteUrl('/amnahi/' . $post->ID),
        ]);
    }

    return response()->json(['message' => 'ok']);
});

// Contact number + notification preferences (WhatsApp messages, language used in them).
$mobileNotificationPrefs = function (Request $request) use ($amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    if ($request->isMethod('get')) {
        $meta = DB::table('wp_usermeta')->where('user_id', $user->ID)->whereIn('meta_key', ['styliiiish_phone', 'styliiiish_wa_optout', 'styliiiish_lang'])->pluck('meta_value', 'meta_key');

        return response()->json(['data' => [
            'phone' => (string) ($meta['styliiiish_phone'] ?? ''),
            'whatsapp_enabled' => ($meta['styliiiish_wa_optout'] ?? '') !== '1',
            'language' => ($meta['styliiiish_lang'] ?? '') === 'en' ? 'en' : 'ar',
        ]]);
    }

    $data = $request->validate([
        'phone' => 'nullable|string|max:30',
        'whatsapp_enabled' => 'nullable|boolean',
        'language' => 'nullable|in:ar,en',
    ]);
    $set = fn (string $key, string $value) => DB::table('wp_usermeta')->updateOrInsert(['user_id' => $user->ID, 'meta_key' => $key], ['meta_value' => $value]);

    if (array_key_exists('phone', $data)) {
        $phone = trim((string) $data['phone']);
        if ($phone !== '' && !waNormalizePhone($phone)) {
            return response()->json(['message' => 'رقم الموبايل مش صحيح', 'errors' => ['phone' => ['invalid']]], 422);
        }
        $set('styliiiish_phone', $phone);
    }
    if (array_key_exists('whatsapp_enabled', $data) && $data['whatsapp_enabled'] !== null) {
        $set('styliiiish_wa_optout', $data['whatsapp_enabled'] ? '0' : '1');
    }
    if (!empty($data['language'])) {
        $set('styliiiish_lang', $data['language']);
    }

    return response()->json(['message' => 'ok']);
};
Route::get('/api/account/notifications', $mobileNotificationPrefs);
Route::put('/api/account/notifications', $mobileNotificationPrefs)->middleware('throttle:amnahi-account');
Route::post('/api/account/notifications', $mobileNotificationPrefs)->middleware('throttle:amnahi-account');

// Owner/dev only (shared secret): sends one template to a number right now and returns the result,
// to check the WhatsApp credentials once they are in .env.
Route::post('/api/internal/whatsapp-test', function (Request $request) {
    $secret = (string) config('styliiiish.commerce.order_bridge_secret');
    if ($secret === '' || !hash_equals($secret, (string) $request->header('X-Bridge-Secret', ''))) {
        return response()->json(['message' => 'Forbidden'], 403);
    }
    $template = (string) $request->input('template', 'styliiiish_welcome');
    $ok = waSend((string) $request->input('phone'), $template, (array) $request->input('params', ['Test']), (string) $request->input('lang', 'ar'));
    $last = DB::table('whatsapp_messages')->orderByDesc('id')->first();

    return response()->json(['sent' => $ok, 'last' => $last]);
});
