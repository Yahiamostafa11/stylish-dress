<?php
/**
 * Trusted server-to-server bridge used only by the Laravel backend
 * (routes/checkout.php) to create a real WooCommerce order.
 *
 * Why this exists instead of the WooCommerce REST API: the REST API's
 * Basic Auth mode requires HTTPS, and this box only runs plain HTTP
 * locally — the REST-correct alternative is OAuth1.0a request signing,
 * which is a lot of surface area for one internal call. Since Laravel and
 * WordPress already share this filesystem/host, it's simpler and safer to
 * boot WordPress directly and call wc_create_order() — the real WooCommerce
 * order API — rather than hand-writing rows into the HPOS order tables.
 *
 * This is NOT a public endpoint: every request must carry the shared
 * secret from wp-config.php (STYLIIISH_ORDER_BRIDGE_SECRET) in the
 * X-Bridge-Secret header, or it's rejected before WordPress even loads
 * further logic than wp-load.php itself.
 */

header('Content-Type: application/json');

// WordPress core emits a stray leading byte (BOM) before any output reaches
// here on this install — harmless for HTML pages, but it silently breaks
// strict JSON parsing on the Laravel side. Buffer everything and discard
// whatever WordPress's own bootstrap printed, so only our own json_encode()
// output ever reaches the response body.
ob_start();

function bridge_clean_all_buffers(): void {
    // WordPress's own bootstrap/plugins may have opened additional nested
    // output buffers by this point — pop every level, not just our own,
    // so nothing they wrote (e.g. a stray leading BOM byte) reaches the client.
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
}

function bridge_fail(int $httpCode, string $message): void {
    http_response_code($httpCode);
    bridge_clean_all_buffers();
    echo json_encode(['error' => $message]);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    bridge_fail(405, 'POST only');
}

define('WP_USE_THEMES', false);
require __DIR__ . '/wp-load.php';

if (!defined('STYLIIISH_ORDER_BRIDGE_SECRET') || STYLIIISH_ORDER_BRIDGE_SECRET === '') {
    bridge_fail(500, 'Bridge secret not configured on the WordPress side');
}

$givenSecret = $_SERVER['HTTP_X_BRIDGE_SECRET'] ?? '';
if (!hash_equals(STYLIIISH_ORDER_BRIDGE_SECRET, $givenSecret)) {
    bridge_fail(401, 'Invalid bridge secret');
}

if (!class_exists('WooCommerce')) {
    bridge_fail(500, 'WooCommerce is not active');
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    bridge_fail(400, 'Invalid JSON body');
}

$customer = $payload['customer'] ?? [];
$items = $payload['items'] ?? [];

if (!is_array($items) || count($items) === 0) {
    bridge_fail(422, 'Order must have at least one item');
}
foreach (['name', 'email', 'phone', 'address_1', 'city'] as $field) {
    if (empty($customer[$field])) {
        bridge_fail(422, "Missing customer.$field");
    }
}

try {
    $order = wc_create_order();

    $nameParts = explode(' ', trim($customer['name']), 2);
    $address = [
        'first_name' => $nameParts[0],
        'last_name'  => $nameParts[1] ?? '',
        'email'      => sanitize_email($customer['email']),
        'phone'      => sanitize_text_field($customer['phone']),
        'address_1'  => sanitize_text_field($customer['address_1']),
        'city'       => sanitize_text_field($customer['city']),
        'country'    => sanitize_text_field($customer['country'] ?? 'EG'),
    ];
    $order->set_address($address, 'billing');
    $order->set_address($address, 'shipping');

    foreach ($items as $line) {
        $productId = isset($line['product_id']) ? (int) $line['product_id'] : 0;
        $variationId = isset($line['variation_id']) ? (int) $line['variation_id'] : 0;
        $quantity = isset($line['quantity']) ? max(1, (int) $line['quantity']) : 1;

        // A "ready size" line targets the specific WC_Product_Variation the
        // customer picked (admin-defined sizes) — add_product() understands
        // variation products directly and records the size attribute on the
        // order line automatically. A "custom measurements" line has no
        // variation_id and adds the parent/simple product instead.
        if ($variationId) {
            $variation = wc_get_product($variationId);
            if (!$variation || $variation->get_parent_id() !== $productId) {
                bridge_fail(422, "variation_id $variationId does not belong to product_id $productId");
            }
            $product = $variation;
        } else {
            $product = $productId ? wc_get_product($productId) : null;
        }

        if (!$product) {
            bridge_fail(422, "Unknown product_id: $productId");
        }

        $itemId = $order->add_product($product, $quantity);
        if (!$itemId) {
            bridge_fail(500, "Could not add product $productId to the order");
        }

        $orderItem = $order->get_item($itemId);
        // Skip for ready-size lines: add_product() with a WC_Product_Variation
        // already records the size as a real product attribute, which the
        // order screen displays automatically ("Size: XL") — adding it again
        // here as custom meta would just show the same line twice.
        if ($orderItem && !$variationId && !empty($line['measurements']) && is_array($line['measurements'])) {
            foreach ($line['measurements'] as $label => $value) {
                if ($value === '' || $value === null) {
                    continue;
                }
                $orderItem->add_meta_data(sanitize_text_field((string) $label), sanitize_text_field((string) $value), true);
            }
            $orderItem->save();
        }
        if ($orderItem && !empty($line['notes'])) {
            $orderItem->add_meta_data(__('Notes', 'styliiiish'), sanitize_textarea_field((string) $line['notes']), true);
            $orderItem->save();
        }
    }

    $order->set_created_via('styliiiish-react-checkout');
    $order->calculate_totals();
    $order->set_status('pending');
    $order->save();

    $result = json_encode([
        'order_id'  => $order->get_id(),
        'order_key' => $order->get_order_key(),
        'total'     => $order->get_total(),
        'currency'  => $order->get_currency(),
        'pay_url'   => $order->get_checkout_payment_url(true),
    ]);
    bridge_clean_all_buffers();
    echo $result;
} catch (Throwable $e) {
    error_log('[wc-order-bridge] ' . $e->getMessage());
    bridge_fail(500, 'Order creation failed: ' . $e->getMessage());
}
