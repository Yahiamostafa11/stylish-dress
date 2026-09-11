<?php

 
 
 
 // =============================================
// OWNER DASHBOARD – STATISTICS
// =============================================
function styliiiish_render_stats() {

    // ----- Count Products -----
    $total_products = wp_count_posts('product')->publish;

    // ----- Pending Vendor Products (Dokan) -----
    $pending_vendor_products = wc_get_products(array(
        'limit'   => -1,
        'status'  => 'pending',
        'return'  => 'ids'
    ));
    $pending_count = count($pending_vendor_products);

    // ----- Count Orders -----
    $total_orders = wp_count_posts('shop_order')->publish;

    // ----- Total Sales This Month -----
    $current_year  = date('Y');
    $current_month = date('m');

    $orders = wc_get_orders(array(
        'limit'    => -1,
        'orderby'  => 'date',
        'order'    => 'DESC',
        'date_created' => $current_year . '-' . $current_month . '-01...' . $current_year . '-' . $current_month . '-31'
    ));

    $total_month_sales = 0;

    foreach ($orders as $order) {
        $total_month_sales += $order->get_total();
    }

    // ----- Count Users -----
    $user_count = count_users();
    $total_users = $user_count['total_users'];

    ?>

    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Products</h3>
            <div class="stat-number"><?php echo $total_products; ?></div>
        </div>

        <div class="stat-card">
            <h3> Pending Products</h3>
            <div class="stat-number"><?php echo $pending_count; ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Orders</h3>
            <div class="stat-number"><?php echo $total_orders; ?></div>
        </div>

        <div class="stat-card">
            <h3>This Month's Sales</h3>
            <div class="stat-number"><?php echo wc_price($total_month_sales); ?></div>
        </div>

        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="stat-number"><?php echo $total_users; ?></div>
        </div>

    </div>
    <?php
}




