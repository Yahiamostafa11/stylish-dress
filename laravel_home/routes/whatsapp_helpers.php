<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| WhatsApp notifications
|--------------------------------------------------------------------------
| Sends the customer-facing WhatsApp messages (welcome, order placed, listing received / live,
| someone is interested). Driver is picked in config('styliiiish.whatsapp'):
|   - 'cloud' : Meta WhatsApp Business Cloud API (needs approved templates, see waTemplates()).
|   - 'log'   : nothing is sent, the message is only written to whatsapp_messages (testing).
|   - ''      : disabled – silent no-op (default until credentials exist).
| A failure here must never break the request that triggered it.
*/

// Template bodies. {{n}} placeholders are exactly what has to be submitted to Meta as the template
// body (template name = array key, languages ar + en). In 'text' mode the same bodies are sent as plain text.
if (!function_exists('waTemplates')) {
    function waTemplates(): array
    {
        return [
            'styliiiish_welcome' => [
                'ar' => 'أهلاً {{1}} 💖 نورتي Styliiiish! حسابك اتفعّل، وتقدري تتصفحي فساتين الإيجار والبيع وتعرضي فستانك على امنحي. لو محتاجة أي مساعدة إحنا هنا.',
                'en' => 'Hi {{1}} 💖 Welcome to Styliiiish! Your account is ready — browse dresses to rent or buy, or list yours on Amnahi. We are here if you need anything.',
            ],
            'styliiiish_order_placed' => [
                'ar' => 'شكراً {{1}} 🎀 استلمنا طلبك رقم #{{2}} بإجمالي {{3}} جنيه. كمّلي الدفع من هنا: {{4}}',
                'en' => 'Thank you {{1}} 🎀 we received your order #{{2}} totalling {{3}} EGP. Complete the payment here: {{4}}',
            ],
            'styliiiish_listing_received' => [
                'ar' => 'أهلاً {{1}} ✨ استلمنا إعلان «{{2}}» وهو دلوقتي قيد المراجعة من فريقنا. هنبلغك أول ما يتنشر.',
                'en' => 'Hi {{1}} ✨ we received your listing “{{2}}” and our team is reviewing it. We will let you know as soon as it is live.',
            ],
            'styliiiish_listing_live' => [
                'ar' => 'فستانك «{{2}}» اتنشر على امنحي 🎉 أهلاً {{1}}، شوفي إعلانك من هنا: {{3}}',
                'en' => 'Your dress “{{2}}” is live on Amnahi 🎉 Hi {{1}}, see your listing here: {{3}}',
            ],
            'styliiiish_listing_interest' => [
                'ar' => 'أهلاً {{1}} 💬 {{2}} بعتلك رسالة بخصوص فستانك «{{3}}» على امنحي. ردّي من هنا: {{4}}',
                'en' => 'Hi {{1}} 💬 {{2}} sent you a message about your dress “{{3}}” on Amnahi. Reply here: {{4}}',
            ],
        ];
    }
}

// Digits-only international number (no +). Egyptian local formats (01xxxxxxxxx) become 20 1xxxxxxxxx.
if (!function_exists('waNormalizePhone')) {
    function waNormalizePhone(?string $raw): ?string
    {
        // Arabic-Indic digits -> ASCII
        $raw = strtr((string) $raw, ['٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9']);
        $digits = preg_replace('/\D+/', '', $raw);
        if ($digits === '' || $digits === null) {
            return null;
        }
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0') && strlen($digits) === 11) {
            $digits = '2' . $digits;                       // 01012345678 -> 201012345678
        }
        if (str_starts_with($digits, '20') && strlen($digits) === 12) {
            return $digits;
        }

        return strlen($digits) >= 10 && strlen($digits) <= 15 && !str_starts_with($digits, '0') ? $digits : null;
    }
}

if (!function_exists('waUserPhone')) {
    /** The user's saved WhatsApp-able number, or null when missing / they turned WhatsApp messages off. */
    function waUserPhone(int $userId): ?string
    {
        $meta = DB::table('wp_usermeta')->where('user_id', $userId)->whereIn('meta_key', ['styliiiish_phone', 'styliiiish_wa_optout', 'styliiiish_lang'])->pluck('meta_value', 'meta_key');
        if (($meta['styliiiish_wa_optout'] ?? '') === '1') {
            return null;
        }

        return waNormalizePhone($meta['styliiiish_phone'] ?? '');
    }
}

if (!function_exists('waUserLang')) {
    function waUserLang(int $userId): string
    {
        return DB::table('wp_usermeta')->where('user_id', $userId)->where('meta_key', 'styliiiish_lang')->value('meta_value') === 'en' ? 'en' : 'ar';
    }
}

// Sends one templated message now. Returns true when the provider accepted it (or the log driver recorded it).
if (!function_exists('waSend')) {
    function waSend(?string $rawPhone, string $template, array $params, string $lang = 'ar', ?int $userId = null): bool
    {
        $cfg = config('styliiiish.whatsapp');
        $driver = (string) ($cfg['driver'] ?? '');
        $phone = waNormalizePhone($rawPhone);
        $templates = waTemplates();
        if ($driver === '' || !$phone || !isset($templates[$template])) {
            return false;
        }
        $lang = $lang === 'en' ? 'en' : 'ar';
        $params = array_values(array_map('strval', $params));

        $status = 'sent';
        $detail = null;
        try {
            if ($driver === 'cloud') {
                if (empty($cfg['token']) || empty($cfg['phone_number_id'])) {
                    return false;
                }
                $text = preg_replace_callback('/\{\{(\d+)\}\}/', fn ($m) => $params[(int) $m[1] - 1] ?? '', $templates[$template][$lang]);
                $body = ($cfg['mode'] ?? 'template') === 'text'
                    ? ['messaging_product' => 'whatsapp', 'to' => $phone, 'type' => 'text', 'text' => ['body' => $text, 'preview_url' => true]]
                    : ['messaging_product' => 'whatsapp', 'to' => $phone, 'type' => 'template', 'template' => [
                        'name' => $template,
                        'language' => ['code' => $lang === 'en' ? ($cfg['en_code'] ?: 'en') : 'ar'],
                        'components' => [['type' => 'body', 'parameters' => array_map(fn ($p) => ['type' => 'text', 'text' => $p], $params)]],
                    ]];
                $res = Http::withToken($cfg['token'])->timeout(15)
                    ->post("https://graph.facebook.com/{$cfg['api_version']}/{$cfg['phone_number_id']}/messages", $body);
                if (!$res->successful()) {
                    $status = 'failed';
                    $detail = substr((string) $res->body(), 0, 1000);
                }
            } else { // 'log'
                $status = 'logged';
            }
        } catch (\Throwable $e) {
            $status = 'failed';
            $detail = substr($e->getMessage(), 0, 1000);
            report($e);
        }

        try {
            DB::table('whatsapp_messages')->insert([
                'user_id' => $userId, 'phone' => $phone, 'template' => $template, 'lang' => $lang,
                'params' => json_encode($params, JSON_UNESCAPED_UNICODE), 'status' => $status, 'detail' => $detail, 'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        return $status !== 'failed';
    }
}

// Queue a message for after the HTTP response (same pattern as push) so requests stay fast.
if (!function_exists('waSendLater')) {
    function waSendLater(?string $rawPhone, string $template, array $params, string $lang = 'ar', ?int $userId = null): void
    {
        if (!config('styliiiish.whatsapp.driver') || !$rawPhone) {
            return;
        }
        app()->terminating(fn () => waSend($rawPhone, $template, $params, $lang, $userId));
    }
}

// Convenience: message a registered user using their saved number + language (respects opt-out).
if (!function_exists('waNotifyUser')) {
    function waNotifyUser(int $userId, string $template, array $params): void
    {
        if (!config('styliiiish.whatsapp.driver')) {
            return;
        }
        $phone = waUserPhone($userId);
        if ($phone) {
            waSendLater($phone, $template, $params, waUserLang($userId), $userId);
        }
    }
}
