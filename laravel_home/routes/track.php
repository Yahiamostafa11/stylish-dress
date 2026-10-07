<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Visit tracking — POST /api/track
|--------------------------------------------------------------------------
|
| Called by the React storefront on every route change. Privacy-first: no cookies,
| no raw IP stored, no query strings; the visitor id is a hash that rotates daily.
| Bots/monitors and back-office paths are ignored.
*/

RateLimiter::for('track', fn (Request $r) => Limit::perMinute(120)->by('track|' . $r->ip()));

Route::post('/api/track', function (Request $request) {
    $cors = fn ($response) => $response->header('Access-Control-Allow-Origin', '*');

    $data = $request->validate(['path' => 'required|string|max:300']);

    $ua = (string) $request->userAgent();
    if ($ua === '' || preg_match('/bot|crawl|spider|slurp|preview|headless|lighthouse|pingdom|uptime|monitor|curl|wget|python|go-http|java\/|facebookexternalhit|embedly|whatsapp/i', $ua)) {
        return $cors(response('', 204));
    }

    $path = parse_url($data['path'], PHP_URL_PATH) ?: '/';
    $path = '/' . ltrim($path, '/');
    $path = mb_substr($path, 0, 190);

    // Shoppers only: skip the back office and payment callbacks.
    if (preg_match('#^/(owner-dashboard|secure-admin|wp-|wc-api|checkout/order-)#i', $path)) {
        return $cors(response('', 204));
    }

    $day = now('Africa/Cairo')->toDateString();
    $visitor = substr(hash_hmac('sha256', $request->ip() . '|' . $ua . '|' . $day, (string) config('app.key')), 0, 16);

    DB::table('styliiiish_pageviews')->insert([
        'day' => $day,
        'visitor' => $visitor,
        'path' => $path,
        'created_at' => now(),
    ]);

    // Keep the table small: drop rows older than 90 days, occasionally.
    if (random_int(1, 400) === 1) {
        DB::table('styliiiish_pageviews')->where('created_at', '<', now()->subDays(90))->delete();
    }

    return $cors(response('', 204));
})->middleware('throttle:track');
