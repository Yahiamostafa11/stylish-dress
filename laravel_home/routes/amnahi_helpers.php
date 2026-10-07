<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| امنحي — shared helpers (emails + password hashing)
|--------------------------------------------------------------------------
|
| Required from amnahi.php. Kept as plain functions (not route closures) so
| they can also be exercised from `artisan tinker`.
*/

/*
|--------------------------------------------------------------------------
| Passwords
|--------------------------------------------------------------------------
|
| Accounts live in wp_users, and WordPress (6.8+) stores passwords as
| "$wp$" + bcrypt(base64(hmac-sha384(password))). Accounts created through
| the React site use plain bcrypt. Both have to be accepted at login, and new
| passwords are written in the WordPress format so wp-login accepts them too.
*/

if (!function_exists('amnahiCheckPassword')) {
    function amnahiCheckPassword(string $password, string $hash): bool
    {
        if ($hash === '') {
            return false;
        }

        if (str_starts_with($hash, '$wp$')) {
            $prehash = base64_encode(hash_hmac('sha384', trim($password), 'wp-sha384', true));

            return password_verify($prehash, substr($hash, 3));
        }

        if (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2a$') || str_starts_with($hash, '$2b$')) {
            return password_verify($password, $hash);
        }

        return false;
    }
}

if (!function_exists('amnahiHashPassword')) {
    function amnahiHashPassword(string $password): string
    {
        $prehash = base64_encode(hash_hmac('sha384', trim($password), 'wp-sha384', true));

        return '$wp' . password_hash($prehash, PASSWORD_BCRYPT);
    }
}

/*
|--------------------------------------------------------------------------
| Password-reset OTP
|--------------------------------------------------------------------------
|
| A 6-digit code emailed to the account, valid 10 minutes, 5 wrong guesses
| burn it, single use. Only a keyed hash of the code is stored (in the cache),
| never the code itself.
*/

if (!function_exists('amnahiOtpKey')) {
    function amnahiOtpKey(string $email): string
    {
        return 'amnahi_pw_otp:' . sha1(strtolower(trim($email)));
    }
}

if (!function_exists('amnahiOtpHash')) {
    function amnahiOtpHash(string $code): string
    {
        return hash_hmac('sha256', $code, (string) config('app.key'));
    }
}

if (!function_exists('amnahiIssueOtp')) {
    function amnahiIssueOtp(string $email): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put(amnahiOtpKey($email), ['h' => amnahiOtpHash($code), 'tries' => 0], now()->addMinutes(10));

        return $code;
    }
}

if (!function_exists('amnahiCheckOtp')) {
    /** True only for a correct, unexpired, unused code. Consumes it on success. */
    function amnahiCheckOtp(string $email, string $code): bool
    {
        $key = amnahiOtpKey($email);
        $record = Cache::get($key);
        if (!is_array($record)) {
            return false;
        }

        if ((int) ($record['tries'] ?? 0) >= 5) {
            Cache::forget($key);

            return false;
        }

        if (hash_equals((string) $record['h'], amnahiOtpHash(trim($code)))) {
            Cache::forget($key);

            return true;
        }

        $record['tries'] = (int) ($record['tries'] ?? 0) + 1;
        Cache::put($key, $record, now()->addMinutes(10));

        return false;
    }
}

/*
|--------------------------------------------------------------------------
| Emails
|--------------------------------------------------------------------------
*/

if (!function_exists('amnahiSiteUrl')) {
    function amnahiSiteUrl(string $path = ''): string
    {
        return rtrim((string) config('styliiiish.frontend_url'), '/') . $path;
    }
}

if (!function_exists('amnahiMailHtml')) {
    function amnahiMailHtml(string $title, string $bodyHtml, ?string $ctaUrl = null, ?string $ctaText = null, ?string $footnote = null): string
    {
        $cta = ($ctaUrl && $ctaText)
            ? '<p style="margin:24px 0"><a href="' . e($ctaUrl) . '" style="background:#BA5D70;color:#fff;padding:12px 24px;'
                . 'border-radius:8px;text-decoration:none;font-weight:600;display:inline-block">' . e($ctaText) . '</a></p>'
            : '';
        $foot = $footnote ? '<p style="color:#9C7F6C;font-size:12px;line-height:1.7">' . e($footnote) . '</p>' : '';

        return '<div dir="rtl" style="font-family:Tahoma,Arial,sans-serif;max-width:480px;margin:0 auto;padding:24px;text-align:right">'
            . '<h2 style="color:#8E2F43;margin:0 0 16px">' . e($title) . '</h2>'
            . '<div style="color:#433131;font-size:14px;line-height:1.9">' . $bodyHtml . '</div>'
            . $cta . $foot
            . '<p style="color:#9C7F6C;font-size:12px;margin-top:28px">Styliiiish &middot; امنحي</p>'
            . '</div>';
    }
}

if (!function_exists('amnahiMailSend')) {
    /** Never throws — a mail hiccup must not break the request that triggered it. */
    function amnahiMailSend(string $to, string $subject, string $html): bool
    {
        if ($to === '') {
            return false;
        }

        try {
            Mail::html($html, fn ($message) => $message->to($to)->subject($subject));

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}

if (!function_exists('amnahiUserEmail')) {
    function amnahiUserEmail(int $userId): array
    {
        $u = DB::table('wp_users')->where('ID', $userId)->first(['user_email', 'display_name']);

        return [(string) ($u->user_email ?? ''), (string) ($u->display_name ?? '')];
    }
}

if (!function_exists('amnahiMailListingReceived')) {
    /** Right after upload: "under review". */
    function amnahiMailListingReceived(int $sellerId, int $listingId, string $title): bool
    {
        [$email, $name] = amnahiUserEmail($sellerId);

        return amnahiMailSend(
            $email,
            'استلمنا فستانك على امنحي ✨',
            amnahiMailHtml(
                'فستانك وصلنا! ✨',
                '<p>أهلاً ' . e($name ?: 'يا قمر') . '،</p>'
                . '<p>استلمنا إعلان <strong>«' . e($title) . '»</strong> وهو دلوقتي <strong>قيد المراجعة</strong> من فريقنا.</p>'
                . '<p>أول ما نوافق عليه وينزل على الموقع هنبعتلك إيميل فيه رابط الإعلان.</p>',
                amnahiSiteUrl('/amnahi/' . $listingId),
                'شوفي إعلانك'
            )
        );
    }
}

if (!function_exists('amnahiMailListingLive')) {
    /** When the listing is published. */
    function amnahiMailListingLive(int $sellerId, int $listingId, string $title): bool
    {
        [$email, $name] = amnahiUserEmail($sellerId);

        return amnahiMailSend(
            $email,
            'فستانك اتنشر على امنحي 🎉',
            amnahiMailHtml(
                'فستانك بقى على الموقع! 🎉',
                '<p>أهلاً ' . e($name ?: 'يا قمر') . '،</p>'
                . '<p>إعلان <strong>«' . e($title) . '»</strong> اتراجع واتنشر، ودلوقتي أي حد بيتصفح امنحي يقدر يشوفه.</p>'
                . '<p>لو حد اهتم بالفستان هيوصلك إيميل، وهتلاقي رسائله في صفحة الرسايل.</p>',
                amnahiSiteUrl('/amnahi/' . $listingId),
                'شوفي إعلانك'
            )
        );
    }
}

if (!function_exists('amnahiMailNewMessage')) {
    /**
     * Tells $recipientId that someone wrote to them about a listing.
     * Throttled to one email per conversation per recipient every 15 minutes so
     * a chatty buyer doesn't flood the seller's inbox.
     */
    function amnahiMailNewMessage(int $recipientId, int $senderId, int $listingId, int $conversationId, bool $firstContact = false): bool
    {
        if (!Cache::add("amnahi_chat_mail:{$conversationId}:{$recipientId}", 1, now()->addMinutes(15))) {
            return false;
        }

        [$email, $name] = amnahiUserEmail($recipientId);
        [, $senderName] = amnahiUserEmail($senderId);
        $title = (string) DB::table('wp_posts')->where('ID', $listingId)->value('post_title');

        $lead = $firstContact
            ? '<strong>' . e($senderName ?: 'مستخدمة') . '</strong> مهتمة بفستانك وبعتتلك رسالة عن <strong>«' . e($title ?: 'إعلانك') . '»</strong>.'
            : '<strong>' . e($senderName ?: 'مستخدمة') . '</strong> بعتتلك رسالة جديدة بخصوص <strong>«' . e($title ?: 'إعلانك') . '»</strong>.';

        return amnahiMailSend(
            $email,
            $firstContact ? 'فيه بنت مهتمة بفستانك على امنحي 👗' : 'وصلتك رسالة جديدة على امنحي 💬',
            amnahiMailHtml(
                $firstContact ? 'فيه بنت مهتمة بفستانك! 👗' : 'وصلتك رسالة جديدة 💬',
                '<p>أهلاً ' . e($name ?: 'يا قمر') . '،</p><p>' . $lead . '</p>',
                amnahiSiteUrl('/messages/' . $conversationId),
                'ردي عليها دلوقتي',
                'لو بعتتلك أكتر من رسالة هنبعتلك تنبيه واحد بس كل ربع ساعة.'
            )
        );
    }
}

if (!function_exists('amnahiMailPasswordOtp')) {
    function amnahiMailPasswordOtp(object $user, string $code): bool
    {
        return amnahiMailSend(
            (string) $user->user_email,
            'كود إعادة تعيين كلمة المرور: ' . $code,
            amnahiMailHtml(
                'كود إعادة تعيين كلمة المرور 🔑',
                '<p>أهلاً ' . e((string) $user->display_name ?: 'يا قمر') . '،</p>'
                . '<p>اكتبي الكود ده في الموقع عشان تختاري كلمة مرور جديدة. الكود شغال لمدة <strong>10 دقايق</strong> ولمرة واحدة بس:</p>'
                . '<p dir="ltr" style="font-size:34px;letter-spacing:10px;font-weight:700;color:#8E2F43;background:#FBF1F3;'
                . 'border-radius:12px;padding:14px 10px;text-align:center;margin:18px 0">' . e($code) . '</p>',
                null,
                null,
                'لو إنتِ مطلبتيش تغيير كلمة المرور، تجاهلي الرسالة دي وحسابك في أمان. متشاركيش الكود مع حد.'
            )
        );
    }
}

/*
|--------------------------------------------------------------------------
| Account changes
|--------------------------------------------------------------------------
*/

if (!function_exists('amnahiPasswordFingerprint')) {
    function amnahiPasswordFingerprint(?string $hash): string
    {
        return substr(hash('sha256', (string) $hash), 0, 16);
    }
}

if (!function_exists('amnahiEmailChangeToken')) {
    function amnahiEmailChangeToken(object $user, string $newEmail): string
    {
        return Crypt::encryptString(json_encode([
            'k' => 'emailchange',
            'uid' => (int) $user->ID,
            'new' => $newEmail,
            'fp' => amnahiPasswordFingerprint($user->user_pass),
            'exp' => now()->addHour()->timestamp,
        ]));
    }
}

if (!function_exists('amnahiResolveEmailChangeToken')) {
    /** @return array{0: object, 1: string}|null  [user, newEmail] */
    function amnahiResolveEmailChangeToken(string $token): ?array
    {
        try {
            $payload = json_decode(Crypt::decryptString($token), true);
        } catch (\Throwable $e) {
            return null;
        }

        if (!is_array($payload) || ($payload['k'] ?? '') !== 'emailchange' || (int) ($payload['exp'] ?? 0) < time()) {
            return null;
        }

        $user = DB::table('wp_users')->where('ID', (int) ($payload['uid'] ?? 0))->first();
        if (!$user || !hash_equals((string) ($payload['fp'] ?? ''), amnahiPasswordFingerprint($user->user_pass))) {
            return null;
        }

        return [$user, (string) ($payload['new'] ?? '')];
    }
}

if (!function_exists('amnahiMailEmailChangeConfirm')) {
    /** Sent to the NEW address; the change only happens when she clicks the link. */
    function amnahiMailEmailChangeConfirm(object $user, string $newEmail, string $token): bool
    {
        return amnahiMailSend(
            $newEmail,
            'أكدي الإيميل الجديد – Styliiiish',
            amnahiMailHtml(
                'تأكيد الإيميل الجديد ✉️',
                '<p>أهلاً ' . e((string) $user->display_name ?: 'يا قمر') . '،</p>'
                . '<p>طلبتي تغيير إيميل حسابك على Styliiiish لـ <strong>' . e($newEmail) . '</strong>. اضغطي على الزرار عشان نأكد التغيير. الرابط شغال لمدة <strong>ساعة</strong>.</p>',
                amnahiSiteUrl('/confirm-email?token=' . rawurlencode($token)),
                'تأكيد الإيميل',
                'لو إنتِ مطلبتيش التغيير ده، تجاهلي الرسالة ومفيش حاجة هتتغير.'
            )
        );
    }
}

if (!function_exists('amnahiMailSecurityNotice')) {
    /** Heads-up to the OLD address that something about the account changed. */
    function amnahiMailSecurityNotice(string $to, string $what): bool
    {
        return amnahiMailSend(
            $to,
            'تنبيه أمان على حسابك – Styliiiish',
            amnahiMailHtml(
                'تنبيه أمان 🔔',
                '<p>' . e($what) . '</p>'
                . '<p>لو إنتِ اللي عملتي كده، مفيش داعي لأي حاجة. ولو لأ، اطلبي إعادة تعيين كلمة المرور فوراً وكلمينا.</p>',
                amnahiSiteUrl('/forgot-password'),
                'إعادة تعيين كلمة المرور'
            )
        );
    }
}
