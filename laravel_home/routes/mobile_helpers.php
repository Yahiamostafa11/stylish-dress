<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Helpers shared by the mobile API and the list endpoints
|--------------------------------------------------------------------------
*/

// "ar" | "en": ?lang= wins, then the Accept-Language header, default English (what the web always sent before).
if (!function_exists('apiRequestLang')) {
    function apiRequestLang(Request $request): string
    {
        $lang = strtolower(substr((string) $request->query('lang', ''), 0, 2));
        if ($lang === '') {
            $lang = strtolower(substr((string) $request->header('Accept-Language', ''), 0, 2));
        }

        return $lang === 'ar' ? 'ar' : 'en';
    }
}

// Pagination input: ?page=&per_page= (or the older ?limit=). Returns [page, perPage, offset].
if (!function_exists('apiPageParams')) {
    function apiPageParams(Request $request, int $default = 20, int $max = 100): array
    {
        $perPage = (int) ($request->query('per_page') ?? $request->query('limit') ?? $default);
        $perPage = max(1, min($max, $perPage));
        $page = max(1, (int) $request->query('page', 1));

        return [$page, $perPage, ($page - 1) * $perPage];
    }
}

if (!function_exists('apiPageMeta')) {
    function apiPageMeta(int $page, int $perPage, int $total): array
    {
        return [
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => max(1, (int) ceil($total / $perPage)),
            'has_more' => $page * $perPage < $total,
        ];
    }
}

// Smaller rendition of a media-library image for list screens. $metaSerialized is the
// attachment's `_wp_attachment_metadata` value; falls back to null when there is none.
if (!function_exists('apiThumbUrl')) {
    function apiThumbUrl(?string $metaSerialized, ?string $attachedFile, string $wpBaseUrl, string $preferred = 'woocommerce_thumbnail'): ?string
    {
        $file = ltrim(trim((string) $attachedFile), '/');
        if ($file === '' || !$metaSerialized) {
            return null;
        }

        $meta = @unserialize($metaSerialized, ['allowed_classes' => false]);
        if (!is_array($meta) || empty($meta['sizes']) || !is_array($meta['sizes'])) {
            return null;
        }

        foreach ([$preferred, 'medium', 'woocommerce_thumbnail', 'thumbnail'] as $size) {
            if (!empty($meta['sizes'][$size]['file'])) {
                $dir = dirname($file);

                return $wpBaseUrl . '/wp-content/uploads/' . ($dir === '.' ? '' : $dir . '/') . $meta['sizes'][$size]['file'];
            }
        }

        return null;
    }
}

/*
|--------------------------------------------------------------------------
| Push notifications (Firebase Cloud Messaging, HTTP v1)
|--------------------------------------------------------------------------
| Everything here is a silent no-op until FIREBASE_SERVICE_ACCOUNT_JSON is set, and a push
| failure must never break the request that triggered it.
*/

if (!function_exists('mobilePushCredentials')) {
    function mobilePushCredentials(): ?array
    {
        $raw = trim((string) config('styliiiish.mobile.push_service_account'));
        if ($raw === '') {
            return null;
        }
        if ($raw[0] !== '{' && is_file($raw)) {
            $raw = (string) file_get_contents($raw);
        }
        $json = json_decode($raw, true);

        return is_array($json) && !empty($json['client_email']) && !empty($json['private_key']) && !empty($json['project_id']) ? $json : null;
    }
}

if (!function_exists('mobilePushAccessToken')) {
    function mobilePushAccessToken(array $cred): ?string
    {
        return Cache::remember('fcm_access_token', 3000, function () use ($cred) {
            $b64 = fn (string $s) => rtrim(strtr(base64_encode($s), '+/', '-_'), '=');
            $now = time();
            $unsigned = $b64(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])) . '.' . $b64(json_encode([
                'iss' => $cred['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ]));
            if (!openssl_sign($unsigned, $signature, $cred['private_key'], OPENSSL_ALGO_SHA256)) {
                return null;
            }

            $res = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $unsigned . '.' . $b64($signature),
            ]);

            return $res->ok() ? (string) $res->json('access_token') : null;
        });
    }
}

// Sends one notification to every device of a user. $texts = ['ar' => [title, body], 'en' => [title, body]];
// each device gets the language it registered with. $data values are cast to strings (FCM requirement).
if (!function_exists('mobilePushToUser')) {
    function mobilePushToUser(int $userId, array $texts, array $data = []): void
    {
        try {
            $cred = mobilePushCredentials();
            if (!$cred) {
                return;
            }
            $devices = DB::table('mobile_devices')->where('user_id', $userId)->get(['id', 'token', 'locale']);
            if ($devices->isEmpty()) {
                return;
            }
            $accessToken = mobilePushAccessToken($cred);
            if (!$accessToken) {
                Cache::forget('fcm_access_token');

                return;
            }

            $data = array_map('strval', $data);
            foreach ($devices as $device) {
                [$title, $body] = $texts[$device->locale] ?? $texts['ar'];
                $res = Http::withToken($accessToken)->timeout(10)
                    ->post("https://fcm.googleapis.com/v1/projects/{$cred['project_id']}/messages:send", ['message' => [
                        'token' => $device->token,
                        'notification' => ['title' => $title, 'body' => $body],
                        'data' => $data,
                        'android' => ['priority' => 'HIGH', 'notification' => ['channel_id' => 'default']],
                        'apns' => ['payload' => ['aps' => ['sound' => 'default']]],
                    ]]);

                if ($res->status() === 401) {
                    Cache::forget('fcm_access_token');
                } elseif ($res->status() === 404 || str_contains((string) $res->body(), 'UNREGISTERED')) {
                    DB::table('mobile_devices')->where('id', $device->id)->delete();
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}

// Runs the push after the HTTP response has been sent, so chat/listing requests stay fast.
if (!function_exists('mobilePushLater')) {
    function mobilePushLater(int $userId, array $texts, array $data = []): void
    {
        app()->terminating(fn () => mobilePushToUser($userId, $texts, $data));
    }
}
