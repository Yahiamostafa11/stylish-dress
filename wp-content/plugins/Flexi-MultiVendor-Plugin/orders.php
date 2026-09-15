<?php

 
 
 
 // Last Check Now 01:31 AM
 // Last Check Now 01:31 AM
 // Last Check Now 01:31 AM
// Last Check Now 01:31 AM
 // =============================================
// OWNER DASHBOARD – ORDERS LIST
// =============================================
function styliiiish_render_orders() {

    // =======================
    // ORDERS PAGINATION LOGIC
    // =======================

    // Detect device
    $is_mobile = wp_is_mobile();
    $per_page  = $is_mobile ? 5 : 10;

    // Current page
    $paged = isset($_GET['opage']) ? max(1, intval($_GET['opage'])) : 1;

    // Get all orders (IDs only for performance)
    $all_orders = wc_get_orders(array(
        'limit'   => -1,
        'orderby' => 'date',
        'order'   => 'DESC',
        'return'  => 'objects',
    ));

    // Total orders
    $total_orders = is_array($all_orders) ? count($all_orders) : 0;

    if ($total_orders === 0) {
        echo '<p>No orders found.</p>';
        return;
    }

    // Total pages
    $total_pages = ceil($total_orders / $per_page);

    // Slice for current page
    $offset = ($paged - 1) * $per_page;
    $orders = array_slice($all_orders, $offset, $per_page);

    // =======================
    // TABLE RENDER
    // =======================

    echo '<style>
        .orders-table { width:100%; border-collapse:collapse; margin-top:15px; }
        .orders-table th, .orders-table td {
            padding:12px; border-bottom:1px solid #ddd; text-align:left;
        }
        .orders-badge { padding:4px 10px; border-radius:6px; font-size:12px; color:#fff; }
        .os-processing { background:#0073aa; }
        .os-completed { background:#28a745; }
        .os-cancelled { background:#dc3545; }
    </style>';

    echo '<table class="orders-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($orders as $order) {

        if (!is_a($order, 'WC_Order')) {
            continue;
        }

        $status      = $order->get_status();
        $badge_class = 'os-' . $status;

        echo '<tr>
                <td>#' . esc_html($order->get_id()) . '</td>
                <td>' . esc_html($order->get_formatted_billing_full_name()) . '</td>
                <td>' . wc_price($order->get_total()) . '</td>
                <td><span class="orders-badge ' . esc_attr($badge_class) . '">' . esc_html(ucfirst($status)) . '</span></td>
                <td>' . esc_html($order->get_date_created()->date('Y-m-d')) . '</td>
                <td>
                    <a class="button" href="' . esc_url( admin_url('post.php?post=' . $order->get_id() . '&action=edit') ) . '">
                        View
                    </a>
                </td>
            </tr>';
    }

    echo '</tbody></table>';

    // =======================
    // PAGINATION SECTION
    // =======================

    echo '<div style="margin-top:20px;">';

    echo '<p><strong>Showing ' .
        ($offset + 1) . ' - ' .
        min($offset + $per_page, $total_orders) .
        ' of ' . $total_orders . ' orders</strong></p>';

    echo '<div style="margin-top:10px; display:flex; gap:8px; flex-wrap:wrap;">';

    // Previous
    if ($paged > 1) {
        echo '<a class="button" href="?opage=' . ($paged - 1) . '">« Previous</a>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        $class = ($i == $paged) ? 'button button-primary' : 'button';
        echo '<a class="' . esc_attr($class) . '" href="?opage=' . $i . '">' . $i . '</a>';
    }

    // Next
    if ($paged < $total_pages) {
        echo '<a class="button" href="?opage=' . ($paged + 1) . '">Next »</a>';
    }

    echo '</div></div>';
}
