<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Checkout — cart-to-order handoff
|--------------------------------------------------------------------------
|
| The cart itself lives entirely client-side in React (localStorage), same
| pattern as favorites. This file only handles the one step that has to
| happen server-side: turning a reviewed cart into a real WooCommerce order.
|
| Order creation is delegated to wc-order-bridge.php (WordPress webroot) over
| HTTP with a shared secret — see config('styliiiish.commerce') for why that
| exists instead of the WooCommerce REST API or a hand-rolled table insert.
| This route is a thin validator + relay in front of that bridge; it never
| touches payment gateway credentials itself.
*/

$checkoutCors = function ($response) {
    return $response
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
};

Route::options('/api/checkout', function () use ($checkoutCors) {
    return $checkoutCors(response()->json([], 204));
});

Route::post('/api/checkout', function (Request $request) use ($checkoutCors) {
    $validated = $request->validate([
        'customer.name' => ['required', 'string', 'max:150'],
        'customer.email' => ['required', 'email', 'max:150'],
        'customer.phone' => ['required', 'string', 'max:30'],
        'customer.address_1' => ['required', 'string', 'max:255'],
        'customer.city' => ['required', 'string', 'max:100'],
        'customer.country' => ['nullable', 'string', 'max:2'],
        'items' => ['required', 'array', 'min:1'],
        'items.*.product_id' => ['required', 'integer', 'min:1'],
        'items.*.variation_id' => ['nullable', 'integer', 'min:1'],
        'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
        'items.*.measurements' => ['nullable', 'array'],
        'items.*.notes' => ['nullable', 'string', 'max:500'],
    ]);

    $bridgeUrl = rtrim((string) config('styliiiish.commerce.wc_api_url'), '/') . '/wc-order-bridge.php';
    $bridgeSecret = (string) config('styliiiish.commerce.order_bridge_secret');

    if ($bridgeUrl === '/wc-order-bridge.php' || $bridgeSecret === '') {
        return $checkoutCors(response()->json([
            'message' => 'Checkout is not configured on this server yet.',
        ], 500));
    }

    $response = Http::withHeaders(['X-Bridge-Secret' => $bridgeSecret])
        ->timeout(15)
        ->post($bridgeUrl, $validated);

    if (!$response->successful()) {
        return $checkoutCors(response()->json([
            'message' => $response->json('error') ?? 'Could not create the order.',
        ], $response->status() ?: 502));
    }

    $order = $response->json();

    return $checkoutCors(response()->json([
        'order_id' => $order['order_id'] ?? null,
        'order_key' => $order['order_key'] ?? null,
        'total' => $order['total'] ?? null,
        'currency' => $order['currency'] ?? null,
        'pay_url' => $order['pay_url'] ?? null,
    ]));
});
