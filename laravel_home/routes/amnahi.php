<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| امنحي — peer-to-peer marketplace: auth, listings, chat
|--------------------------------------------------------------------------
|
| Sellers/buyers here are real WordPress users (wp_users/wp_usermeta), and
| listings are real WooCommerce products (wp_posts/wp_postmeta) tagged with
| their own dedicated product_cat term (slug 'amnahi', name 'حكايات امنحي')
| — deliberately separate from the brand's own 'used-dress' catalog category,
| so peer listings never leak into the main store (see the exclusion in the
| /api/products routes in web.php). The owner-dashboard's vendor moderation
| screen will still pick these up once WordPress itself is reachable (see
| config('styliiiish.amnahi')), same as any other product category.
|
| Chat lives in new Laravel-native tables (chat_conversations/chat_messages)
| since it isn't a WP/Woo concept. Auth is a signed, stateless bearer token
| (Crypt::encryptString) rather than WP's cookie/nonce session, because this
| API is consumed by a decoupled React app with no shared session store.
*/

$amnahiCors = function ($response) {
    return $response
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
};

$amnahiResolveImage = function (?string $guid, ?string $attachedFile, string $wpBaseUrl): ?string {
    $guid = trim((string) $guid);
    if ($guid !== '') {
        return preg_replace('#^https?://[^/]+#i', $wpBaseUrl, $guid);
    }

    $file = ltrim(trim((string) $attachedFile), '/');
    if ($file !== '') {
        return $wpBaseUrl . '/wp-content/uploads/' . $file;
    }

    return null;
};

$amnahiWpBaseUrl = fn (Request $request) => rtrim(
    (string) (config('styliiiish.wp_public_url') ?: $request->getSchemeAndHttpHost()),
    '/'
);

$amnahiIssueToken = function (int $userId): string {
    return Crypt::encryptString(json_encode(['uid' => $userId, 'exp' => now()->addDays(30)->timestamp]));
};

$amnahiAuthUser = function (Request $request) {
    $header = (string) $request->header('Authorization', '');
    if (!str_starts_with($header, 'Bearer ')) {
        return null;
    }

    try {
        $payload = json_decode(Crypt::decryptString(substr($header, 7)), true);
    } catch (\Throwable $e) {
        return null;
    }

    if (!is_array($payload) || (int) ($payload['exp'] ?? 0) < time()) {
        return null;
    }

    $userId = (int) ($payload['uid'] ?? 0);
    if ($userId <= 0) {
        return null;
    }

    return DB::table('wp_users')->where('ID', $userId)->first();
};

$amnahiThrottle = (string) config('styliiiish.throttle.amnahi', '30,1');

// Emails the seller the moment a buyer starts a NEW chat about her listing
// (not on every message — just this first "أنا مهتمة" click). Wrapped by the
// caller in a try/catch: a mail hiccup should never block the buyer's chat
// from being created.
$amnahiNotifySellerOfInterest = function (int $sellerId, int $buyerId, int $listingId, int $conversationId): void {
    $seller = DB::table('wp_users')->where('ID', $sellerId)->first();
    if (!$seller || empty($seller->user_email)) {
        return;
    }

    $buyerName = (string) DB::table('wp_users')->where('ID', $buyerId)->value('display_name');
    $listingTitle = (string) DB::table('wp_posts')->where('ID', $listingId)->value('post_title');
    $chatUrl = config('styliiiish.frontend_url') . '/messages/' . $conversationId;

    $html = '
        <div style="font-family:Tahoma,Arial,sans-serif;max-width:480px;margin:0 auto;padding:24px">
            <h2 style="color:#8E2F43;margin:0 0 16px">فيه بنت مهتمة بفستانك على امنحي! 👗</h2>
            <p style="color:#433131;font-size:14px;line-height:1.8">
                <strong>' . e($buyerName ?: 'مستخدمة') . '</strong> بعتت رسالة عشان تسأل عن
                <strong>«' . e($listingTitle ?: 'إعلانك') . '»</strong>.
            </p>
            <p style="margin:24px 0">
                <a href="' . e($chatUrl) . '" style="background:#BA5D70;color:#fff;padding:12px 24px;
                    border-radius:8px;text-decoration:none;font-weight:600;display:inline-block">
                    ردي عليها دلوقتي
                </a>
            </p>
            <p style="color:#9C7F6C;font-size:12px">Styliiiish &middot; امنحي</p>
        </div>
    ';

    Mail::html($html, function ($message) use ($seller) {
        $message->to($seller->user_email)->subject('فيه بنت مهتمة بفستانك على امنحي 👗');
    });
};

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::post('/api/auth/register', function (Request $request) use ($amnahiCors, $amnahiIssueToken) {
    $data = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'password' => 'required|string|min:6',
        'phone' => 'nullable|string|max:30',
    ]);

    if (DB::table('wp_users')->where('user_email', $data['email'])->exists()) {
        return $amnahiCors(response()->json(['message' => 'البريد الإلكتروني مسجل بالفعل'], 422));
    }

    $now = now();
    $login = (Str::slug($data['name']) ?: 'user') . '-' . Str::lower(Str::random(5));

    $userId = DB::table('wp_users')->insertGetId([
        'user_login' => $login,
        'user_pass' => password_hash($data['password'], PASSWORD_BCRYPT),
        'user_nicename' => Str::slug($data['name']) ?: $login,
        'user_email' => $data['email'],
        'user_url' => '',
        'user_registered' => $now,
        'user_activation_key' => '',
        'user_status' => 0,
        'display_name' => $data['name'],
    ]);

    DB::table('wp_usermeta')->insert([
        ['user_id' => $userId, 'meta_key' => 'nickname', 'meta_value' => $data['name']],
        ['user_id' => $userId, 'meta_key' => 'first_name', 'meta_value' => $data['name']],
        ['user_id' => $userId, 'meta_key' => 'wp_capabilities', 'meta_value' => serialize(['subscriber' => true])],
        ['user_id' => $userId, 'meta_key' => 'wp_user_level', 'meta_value' => '0'],
        ['user_id' => $userId, 'meta_key' => 'styliiiish_phone', 'meta_value' => (string) ($data['phone'] ?? '')],
    ]);

    return $amnahiCors(response()->json([
        'token' => $amnahiIssueToken($userId),
        'user' => ['id' => $userId, 'name' => $data['name'], 'email' => $data['email']],
    ], 201));
})->middleware('throttle:' . $amnahiThrottle);

Route::post('/api/auth/login', function (Request $request) use ($amnahiCors, $amnahiIssueToken) {
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = DB::table('wp_users')->where('user_email', $data['email'])->first();
    if (!$user || !password_verify($data['password'], $user->user_pass)) {
        return $amnahiCors(response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401));
    }

    return $amnahiCors(response()->json([
        'token' => $amnahiIssueToken((int) $user->ID),
        'user' => ['id' => (int) $user->ID, 'name' => $user->display_name, 'email' => $user->user_email],
    ]));
})->middleware('throttle:' . $amnahiThrottle);

Route::get('/api/auth/me', function (Request $request) use ($amnahiCors, $amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return $amnahiCors(response()->json(['message' => 'Unauthorized'], 401));
    }

    return $amnahiCors(response()->json([
        'user' => ['id' => (int) $user->ID, 'name' => $user->display_name, 'email' => $user->user_email],
    ]));
});

/*
|--------------------------------------------------------------------------
| Listings
|--------------------------------------------------------------------------
*/

Route::post('/api/amnahi/listings', function (Request $request) use ($amnahiCors, $amnahiAuthUser, $amnahiWpBaseUrl) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return $amnahiCors(response()->json(['message' => 'سجّلي دخولك الأول'], 401));
    }

    $maxImages = (int) config('styliiiish.amnahi.max_images', 2);
    $maxKb = (int) config('styliiiish.amnahi.max_image_kb', 5120);

    $data = $request->validate([
        'title' => 'required|string|max:150',
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0',
        'dress_type' => 'nullable|string|max:100',
        'images' => 'required|array|min:1|max:' . $maxImages,
        'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:' . $maxKb,
    ]);

    $wpBaseUrl = $amnahiWpBaseUrl($request);
    $wpRoot = dirname(base_path());
    $now = now();
    $autoPublish = (bool) config('styliiiish.amnahi.auto_publish', true);

    $productId = DB::table('wp_posts')->insertGetId([
        'post_author' => $user->ID,
        'post_date' => $now,
        'post_date_gmt' => $now->copy()->utc(),
        'post_content' => (string) ($data['description'] ?? ''),
        'post_title' => $data['title'],
        'post_excerpt' => Str::limit((string) ($data['description'] ?? ''), 150, ''),
        'post_status' => $autoPublish ? 'publish' : 'pending',
        'comment_status' => 'open',
        'ping_status' => 'closed',
        'post_password' => '',
        'post_name' => 'amnahi-' . Str::lower(Str::random(10)),
        'to_ping' => '',
        'pinged' => '',
        'post_modified' => $now,
        'post_modified_gmt' => $now->copy()->utc(),
        'post_content_filtered' => '',
        'post_parent' => 0,
        'guid' => '',
        'menu_order' => 0,
        'post_type' => 'product',
        'post_mime_type' => '',
        'comment_count' => 0,
    ]);

    DB::table('wp_posts')->where('ID', $productId)->update([
        'guid' => $wpBaseUrl . '/?post_type=product&p=' . $productId,
    ]);

    $priceValue = number_format((float) $data['price'], 2, '.', '');
    DB::table('wp_postmeta')->insert([
        ['post_id' => $productId, 'meta_key' => '_regular_price', 'meta_value' => $priceValue],
        ['post_id' => $productId, 'meta_key' => '_price', 'meta_value' => $priceValue],
        ['post_id' => $productId, 'meta_key' => '_sku', 'meta_value' => 'AMNAHI-' . $productId],
        ['post_id' => $productId, 'meta_key' => '_manage_stock', 'meta_value' => 'no'],
        ['post_id' => $productId, 'meta_key' => '_stock_status', 'meta_value' => 'instock'],
        ['post_id' => $productId, 'meta_key' => '_virtual', 'meta_value' => 'no'],
        ['post_id' => $productId, 'meta_key' => '_downloadable', 'meta_value' => 'no'],
        ['post_id' => $productId, 'meta_key' => '_styliiiish_amnahi_listing', 'meta_value' => '1'],
        ['post_id' => $productId, 'meta_key' => '_styliiiish_amnahi_seller_id', 'meta_value' => (string) $user->ID],
        ['post_id' => $productId, 'meta_key' => '_styliiiish_amnahi_dress_type', 'meta_value' => (string) ($data['dress_type'] ?? '')],
    ]);

    $catTermTaxonomyId = DB::table('wp_term_taxonomy as tt')
        ->join('wp_terms as t', 't.term_id', '=', 'tt.term_id')
        ->where('tt.taxonomy', 'product_cat')
        ->where('t.slug', 'amnahi')
        ->value('tt.term_taxonomy_id');

    $typeTermTaxonomyId = DB::table('wp_term_taxonomy as tt')
        ->join('wp_terms as t', 't.term_id', '=', 'tt.term_id')
        ->where('tt.taxonomy', 'product_type')
        ->where('t.slug', 'simple')
        ->value('tt.term_taxonomy_id');

    foreach (array_filter([$catTermTaxonomyId, $typeTermTaxonomyId]) as $ttId) {
        DB::table('wp_term_relationships')->insertOrIgnore([
            'object_id' => $productId,
            'term_taxonomy_id' => $ttId,
            'term_order' => 0,
        ]);
        DB::table('wp_term_taxonomy')->where('term_taxonomy_id', $ttId)->increment('count');
    }

    $attachmentIds = [];
    foreach ($request->file('images') ?? [] as $file) {
        $year = $now->format('Y');
        $month = $now->format('m');
        $dir = $wpRoot . "/wp-content/uploads/{$year}/{$month}";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'amnahi';
        $filename = $baseName . '-' . Str::lower(Str::random(6)) . '.' . $ext;
        $mimeType = $file->getMimeType() ?: 'image/jpeg';
        $file->move($dir, $filename);
        $destination = $dir . '/' . $filename;

        [$width, $height] = @getimagesize($destination) ?: [0, 0];
        $relativePath = "{$year}/{$month}/{$filename}";
        $attachmentGuid = $wpBaseUrl . '/wp-content/uploads/' . $relativePath;

        $attachmentId = DB::table('wp_posts')->insertGetId([
            'post_author' => $user->ID,
            'post_date' => $now,
            'post_date_gmt' => $now->copy()->utc(),
            'post_content' => '',
            'post_title' => $baseName,
            'post_excerpt' => '',
            'post_status' => 'inherit',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_password' => '',
            'post_name' => $baseName . '-' . Str::lower(Str::random(6)),
            'to_ping' => '',
            'pinged' => '',
            'post_modified' => $now,
            'post_modified_gmt' => $now->copy()->utc(),
            'post_content_filtered' => '',
            'post_parent' => $productId,
            'guid' => $attachmentGuid,
            'menu_order' => 0,
            'post_type' => 'attachment',
            'post_mime_type' => $mimeType,
            'comment_count' => 0,
        ]);

        DB::table('wp_postmeta')->insert([
            ['post_id' => $attachmentId, 'meta_key' => '_wp_attached_file', 'meta_value' => $relativePath],
            ['post_id' => $attachmentId, 'meta_key' => '_wp_attachment_metadata', 'meta_value' => serialize([
                'width' => $width, 'height' => $height, 'file' => $relativePath, 'sizes' => [],
            ])],
        ]);

        $attachmentIds[] = $attachmentId;
    }

    if (!empty($attachmentIds)) {
        DB::table('wp_postmeta')->insert([
            'post_id' => $productId, 'meta_key' => '_thumbnail_id', 'meta_value' => (string) $attachmentIds[0],
        ]);
        if (count($attachmentIds) > 1) {
            DB::table('wp_postmeta')->insert([
                'post_id' => $productId,
                'meta_key' => '_product_image_gallery',
                'meta_value' => implode(',', array_slice($attachmentIds, 1)),
            ]);
        }
    }

    return $amnahiCors(response()->json([
        'data' => ['id' => $productId, 'status' => $autoPublish ? 'publish' : 'pending'],
    ], 201));
})->middleware('throttle:' . $amnahiThrottle);

Route::get('/api/amnahi/listings', function (Request $request) use ($amnahiCors, $amnahiResolveImage, $amnahiWpBaseUrl) {
    $wpBaseUrl = $amnahiWpBaseUrl($request);
    $limit = max(1, min(60, (int) $request->query('limit', 30)));

    $rows = DB::table('wp_posts as p')
        ->join('wp_postmeta as flag', fn ($j) => $j->on('p.ID', '=', 'flag.post_id')->where('flag.meta_key', '_styliiiish_amnahi_listing'))
        ->leftJoin('wp_postmeta as price', fn ($j) => $j->on('p.ID', '=', 'price.post_id')->where('price.meta_key', '_price'))
        ->leftJoin('wp_postmeta as thumb', fn ($j) => $j->on('p.ID', '=', 'thumb.post_id')->where('thumb.meta_key', '_thumbnail_id'))
        ->leftJoin('wp_posts as img', 'thumb.meta_value', '=', 'img.ID')
        ->leftJoin('wp_postmeta as img_file', fn ($j) => $j->on('img.ID', '=', 'img_file.post_id')->where('img_file.meta_key', '_wp_attached_file'))
        ->leftJoin('wp_users as u', 'u.ID', '=', 'p.post_author')
        ->where('p.post_type', 'product')
        ->where('p.post_status', 'publish')
        ->orderBy('p.post_date', 'desc')
        ->limit($limit)
        ->select(
            'p.ID as id',
            'p.post_title as name',
            'p.post_excerpt as short_description',
            'price.meta_value as price',
            'img.guid as image_guid',
            'img_file.meta_value as image_file',
            'u.display_name as seller_name'
        )
        ->get()
        ->map(fn ($row) => [
            'id' => (int) $row->id,
            'name' => (string) $row->name,
            'short_description' => trim((string) $row->short_description),
            'price' => $row->price !== null ? (float) $row->price : null,
            'image' => $amnahiResolveImage($row->image_guid, $row->image_file, $wpBaseUrl),
            'seller_name' => (string) $row->seller_name,
        ])
        ->unique('id')
        ->values();

    return $amnahiCors(response()->json(['data' => $rows]));
});

Route::get('/api/amnahi/mine', function (Request $request) use ($amnahiCors, $amnahiAuthUser, $amnahiResolveImage, $amnahiWpBaseUrl) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return $amnahiCors(response()->json(['message' => 'Unauthorized'], 401));
    }

    $wpBaseUrl = $amnahiWpBaseUrl($request);

    $rows = DB::table('wp_posts as p')
        ->join('wp_postmeta as flag', fn ($j) => $j->on('p.ID', '=', 'flag.post_id')->where('flag.meta_key', '_styliiiish_amnahi_listing'))
        ->leftJoin('wp_postmeta as price', fn ($j) => $j->on('p.ID', '=', 'price.post_id')->where('price.meta_key', '_price'))
        ->leftJoin('wp_postmeta as thumb', fn ($j) => $j->on('p.ID', '=', 'thumb.post_id')->where('thumb.meta_key', '_thumbnail_id'))
        ->leftJoin('wp_posts as img', 'thumb.meta_value', '=', 'img.ID')
        ->leftJoin('wp_postmeta as img_file', fn ($j) => $j->on('img.ID', '=', 'img_file.post_id')->where('img_file.meta_key', '_wp_attached_file'))
        ->where('p.post_author', $user->ID)
        ->where('p.post_type', 'product')
        ->orderBy('p.post_date', 'desc')
        ->select('p.ID as id', 'p.post_title as name', 'p.post_status as status', 'price.meta_value as price', 'img.guid as image_guid', 'img_file.meta_value as image_file')
        ->get()
        ->map(fn ($row) => [
            'id' => (int) $row->id,
            'name' => (string) $row->name,
            'status' => (string) $row->status,
            'price' => $row->price !== null ? (float) $row->price : null,
            'image' => $amnahiResolveImage($row->image_guid, $row->image_file, $wpBaseUrl),
        ])
        ->unique('id')
        ->values();

    return $amnahiCors(response()->json(['data' => $rows]));
});

Route::get('/api/amnahi/listings/{id}', function (Request $request, string $id) use ($amnahiCors, $amnahiAuthUser, $amnahiResolveImage, $amnahiWpBaseUrl) {
    $wpBaseUrl = $amnahiWpBaseUrl($request);

    $row = DB::table('wp_posts as p')
        ->join('wp_postmeta as flag', fn ($j) => $j->on('p.ID', '=', 'flag.post_id')->where('flag.meta_key', '_styliiiish_amnahi_listing'))
        ->leftJoin('wp_postmeta as price', fn ($j) => $j->on('p.ID', '=', 'price.post_id')->where('price.meta_key', '_price'))
        ->leftJoin('wp_postmeta as dtype', fn ($j) => $j->on('p.ID', '=', 'dtype.post_id')->where('dtype.meta_key', '_styliiiish_amnahi_dress_type'))
        ->leftJoin('wp_postmeta as thumb', fn ($j) => $j->on('p.ID', '=', 'thumb.post_id')->where('thumb.meta_key', '_thumbnail_id'))
        ->leftJoin('wp_posts as img', 'thumb.meta_value', '=', 'img.ID')
        ->leftJoin('wp_postmeta as img_file', fn ($j) => $j->on('img.ID', '=', 'img_file.post_id')->where('img_file.meta_key', '_wp_attached_file'))
        ->leftJoin('wp_postmeta as gallery', fn ($j) => $j->on('p.ID', '=', 'gallery.post_id')->where('gallery.meta_key', '_product_image_gallery'))
        ->leftJoin('wp_users as u', 'u.ID', '=', 'p.post_author')
        ->where('p.ID', $id)
        ->where('p.post_type', 'product')
        ->select(
            'p.ID as id',
            'p.post_author as seller_id',
            'p.post_title as name',
            'p.post_content as description',
            'p.post_status as status',
            'price.meta_value as price',
            'dtype.meta_value as dress_type',
            'img.guid as image_guid',
            'img_file.meta_value as image_file',
            'gallery.meta_value as gallery_ids',
            'u.display_name as seller_name'
        )
        ->first();

    // Unpublished (pending) listings are visible to their own seller only.
    $viewer = $row && $row->status !== 'publish' ? $amnahiAuthUser($request) : null;
    $isOwner = $viewer && (int) $viewer->ID === (int) $row->seller_id;
    if (!$row || ($row->status !== 'publish' && !($isOwner && in_array($row->status, ['pending', 'draft'], true)))) {
        return $amnahiCors(response()->json(['message' => 'الإعلان غير متاح'], 404));
    }

    $galleryImages = [];
    if (!empty($row->gallery_ids)) {
        $ids = array_values(array_filter(array_map('intval', explode(',', $row->gallery_ids))));
        if ($ids) {
            $galleryImages = DB::table('wp_posts as img')
                ->leftJoin('wp_postmeta as img_file', fn ($j) => $j->on('img.ID', '=', 'img_file.post_id')->where('img_file.meta_key', '_wp_attached_file'))
                ->whereIn('img.ID', $ids)
                ->select('img.guid as image_guid', 'img_file.meta_value as image_file')
                ->get()
                ->map(fn ($g) => $amnahiResolveImage($g->image_guid, $g->image_file, $wpBaseUrl))
                ->filter()
                ->values()
                ->all();
        }
    }

    return $amnahiCors(response()->json([
        'data' => [
            'id' => (int) $row->id,
            'status' => (string) $row->status,
            'seller_id' => (int) $row->seller_id,
            'seller_name' => (string) $row->seller_name,
            'name' => (string) $row->name,
            'description' => trim((string) strip_tags((string) $row->description)),
            'price' => $row->price !== null ? (float) $row->price : null,
            'dress_type' => (string) $row->dress_type,
            'image' => $amnahiResolveImage($row->image_guid, $row->image_file, $wpBaseUrl),
            'gallery' => $galleryImages,
        ],
    ]));
});

/*
|--------------------------------------------------------------------------
| Chat
|--------------------------------------------------------------------------
*/

Route::post('/api/amnahi/listings/{id}/interest', function (Request $request, string $id) use ($amnahiCors, $amnahiAuthUser, $amnahiNotifySellerOfInterest) {
    $buyer = $amnahiAuthUser($request);
    if (!$buyer) {
        return $amnahiCors(response()->json(['message' => 'سجّلي دخولك الأول'], 401));
    }

    $sellerId = DB::table('wp_posts')->where('ID', $id)->where('post_type', 'product')->value('post_author');
    if (!$sellerId) {
        return $amnahiCors(response()->json(['message' => 'الإعلان غير موجود'], 404));
    }
    if ((int) $sellerId === (int) $buyer->ID) {
        return $amnahiCors(response()->json(['message' => 'مينفعش تبعتي رسالة لنفسك'], 422));
    }

    $existing = DB::table('chat_conversations')
        ->where('listing_id', $id)
        ->where('buyer_user_id', $buyer->ID)
        ->first();

    if ($existing) {
        return $amnahiCors(response()->json(['data' => ['conversation_id' => $existing->id]]));
    }

    $conversationId = DB::table('chat_conversations')->insertGetId([
        'listing_id' => $id,
        'buyer_user_id' => $buyer->ID,
        'seller_user_id' => $sellerId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    try {
        $amnahiNotifySellerOfInterest((int) $sellerId, (int) $buyer->ID, (int) $id, (int) $conversationId);
    } catch (\Throwable $e) {
        report($e);
    }

    return $amnahiCors(response()->json(['data' => ['conversation_id' => $conversationId]], 201));
})->middleware('throttle:' . $amnahiThrottle);

Route::get('/api/conversations', function (Request $request) use ($amnahiCors, $amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return $amnahiCors(response()->json(['message' => 'Unauthorized'], 401));
    }

    $rows = DB::table('chat_conversations as c')
        ->where('c.buyer_user_id', $user->ID)
        ->orWhere('c.seller_user_id', $user->ID)
        ->orderBy('c.updated_at', 'desc')
        ->get();

    $data = $rows->map(function ($c) use ($user) {
        $otherId = (int) $c->buyer_user_id === (int) $user->ID ? $c->seller_user_id : $c->buyer_user_id;

        return [
            'id' => (int) $c->id,
            'listing_id' => (int) $c->listing_id,
            'listing_title' => (string) DB::table('wp_posts')->where('ID', $c->listing_id)->value('post_title'),
            'other_user_name' => (string) DB::table('wp_users')->where('ID', $otherId)->value('display_name'),
            'last_message' => DB::table('chat_messages')->where('conversation_id', $c->id)->orderByDesc('id')->value('body'),
            'updated_at' => $c->updated_at,
        ];
    });

    return $amnahiCors(response()->json(['data' => $data]));
});

Route::get('/api/conversations/{id}/messages', function (Request $request, string $id) use ($amnahiCors, $amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return $amnahiCors(response()->json(['message' => 'Unauthorized'], 401));
    }

    $conversation = DB::table('chat_conversations')->where('id', $id)->first();
    if (!$conversation || ((int) $conversation->buyer_user_id !== (int) $user->ID && (int) $conversation->seller_user_id !== (int) $user->ID)) {
        return $amnahiCors(response()->json(['message' => 'غير مسموح'], 403));
    }

    $messages = DB::table('chat_messages')
        ->where('conversation_id', $id)
        ->orderBy('id')
        ->get(['id', 'sender_user_id', 'body', 'created_at']);

    return $amnahiCors(response()->json(['data' => $messages, 'my_id' => (int) $user->ID]));
});

Route::post('/api/conversations/{id}/messages', function (Request $request, string $id) use ($amnahiCors, $amnahiAuthUser) {
    $user = $amnahiAuthUser($request);
    if (!$user) {
        return $amnahiCors(response()->json(['message' => 'Unauthorized'], 401));
    }

    $conversation = DB::table('chat_conversations')->where('id', $id)->first();
    if (!$conversation || ((int) $conversation->buyer_user_id !== (int) $user->ID && (int) $conversation->seller_user_id !== (int) $user->ID)) {
        return $amnahiCors(response()->json(['message' => 'غير مسموح'], 403));
    }

    $data = $request->validate(['body' => 'required|string|max:2000']);

    $messageId = DB::table('chat_messages')->insertGetId([
        'conversation_id' => $id,
        'sender_user_id' => $user->ID,
        'body' => $data['body'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('chat_conversations')->where('id', $id)->update(['updated_at' => now()]);

    return $amnahiCors(response()->json(['data' => ['id' => $messageId]], 201));
})->middleware('throttle:60,1');
