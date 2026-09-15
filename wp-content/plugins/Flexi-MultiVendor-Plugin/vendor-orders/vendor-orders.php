<?php
/*
Plugin Name: WebsiteFlexi Vendor Orders
Description: Vendor orders management with custom statuses and AJAX.
Version: 1.2
Author: Mahmoud Ashraf
*/

if ( ! defined('ABSPATH') ) exit;
define('WF_VENDOR_ORDERS_PATH', plugin_dir_path(__FILE__));
define('WF_VENDOR_ORDERS_URL', plugin_dir_url(__FILE__));


function wf_get_tracking_meta_key() {
    return get_option('wf_tracking_meta_key', '_tracking_number');
}
/**
 * Auto detect tracking number from known meta keys
 */
function wf_get_order_tracking_number( WC_Order $order ) {

    // 1️⃣ المفتاح المخصص من الإعدادات
    $primary_key = wf_get_tracking_meta_key();
    $value = $order->get_meta($primary_key);
    if ( ! empty($value) ) {
        return (string) $value;
    }

    // 2️⃣ مفاتيح مشهورة لشركات الشحن
    $fallback_keys = [
        '_tracking_number',
        '_tracking_id',
        '_bosta_tracking',
        '_aramex_awb',
        '_dhl_waybill',
        '_shipment_tracking',
    ];

    foreach ($fallback_keys as $key) {
        $value = $order->get_meta($key);
        if ( ! empty($value) ) {
            return (string) $value;
        }
    }

    return '';
}


add_action('wp_enqueue_scripts','wf_vendor_orders_assets');

function wf_vendor_orders_assets() {

    if ( ! is_account_page() ) {
        return;
    }

   wp_enqueue_style(
        'wf-vendor-orders',
        WF_VENDOR_ORDERS_URL . 'vendor-orders.css',
        [],
        filemtime(WF_VENDOR_ORDERS_PATH . 'vendor-orders.css')
    );


    wp_enqueue_script(
        'wf-vendor-orders',
        WF_VENDOR_ORDERS_URL . 'vendor-orders.js',
        ['jquery'],
        filemtime(WF_VENDOR_ORDERS_PATH . 'vendor-orders.js'),
        true
    );

    wp_localize_script('wf-vendor-orders', 'wfVendorOrders', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wf_vendor_orders')
    ]);
}




function wf_vendor_orders_render_modals() {
    include WF_VENDOR_ORDERS_PATH . 'vendor-orders.html';
}


























add_action('init','wf_add_support_role');

function wf_add_support_role(){

  add_role(
    'support_agent',
    'Support Agent',
    [
      'read'=>true,
      'edit_posts'=>false
    ]
  );

}



function wf_user_can_access_order_chat($order_id){

  if(!is_user_logged_in()){
    return false;
  }

  $user = wp_get_current_user();
  $uid  = get_current_user_id();


  /* =====================
     Admin = Full Access
  ===================== */

    if ( wf_od_is_user_plugin_admin($uid) ) {
    return true;
  }


  /* =====================
     Assigned Agent Only
  ===================== */

  $assigned = wf_get_assigned_agent_id($order_id);

  if($assigned && $assigned == $uid){
    return true;
  }


  /* =====================
     Vendor (Owner)
  ===================== */

  $order = wc_get_order($order_id);

  if(!$order) return false;

  foreach($order->get_items() as $item){

    $product = $item->get_product();

    if(
      $product &&
      get_post_field('post_author',$product->get_id()) == $uid
    ){
      return true;
    }
  }

  return false;
}


add_action('wp_ajax_wf_send_support_message','wf_send_support_message');

function wf_send_support_message(){

  /* =====================
     Security
  ===================== */

  check_ajax_referer('wf_vendor_orders','nonce');

  if(!is_user_logged_in()){
    wp_send_json_error('not_logged_in');
  }


  /* =====================
     Init
  ===================== */

  global $wpdb;

  $user = wp_get_current_user();

  $order_id = intval($_POST['order_id'] ?? 0);

  $msg = sanitize_textarea_field($_POST['message'] ?? '');


  if(!$order_id || empty($msg)){
    wp_send_json_error('invalid_data');
  }


  /* =====================
     Access Control
  ===================== */

  if(!wf_user_can_access_order_chat($order_id)){
    wp_send_json_error('no_access');
  }


  // Extra protection: Agent only his orders
  if(in_array('support_agent',$user->roles)){

    $assigned = wf_get_assigned_agent_id($order_id);

    if($assigned != $user->ID){
      wp_send_json_error('not_assigned');
    }
  }


  /* =====================
     Insert
  ===================== */

  $role = $user->roles[0] ?? 'user';


  $insert = $wpdb->insert(
    $wpdb->prefix.'wf_support_chat',
    [
      'order_id'    => $order_id,
      'sender_id'   => $user->ID,
      'sender_role' => $role,
      'message'     => $msg
    ],
    [
      '%d','%d','%s','%s'
    ]
  );


  if(!$insert){
    wp_send_json_error($wpdb->last_error);
  }


  wp_send_json_success([
    'id' => $wpdb->insert_id
  ]);

}





add_action('wp_ajax_wf_get_support_messages','wf_get_support_messages');

function wf_get_support_messages(){
    
    
  check_ajax_referer('wf_vendor_orders','nonce');

  if(!is_user_logged_in())
    wp_send_json_error();

  global $wpdb;

  $order_id = intval($_POST['order_id']);

  if(!wf_user_can_access_order_chat($order_id))
    wp_send_json_error();

  $rows = $wpdb->get_results(

    $wpdb->prepare(
      "SELECT * FROM {$wpdb->prefix}wf_support_chat
       WHERE order_id=%d
       ORDER BY id ASC",
       $order_id
    )

  );

  wp_send_json_success($rows);
}



add_action('admin_menu','wf_support_admin_page');


function wf_render_support_admin(){

  echo '<h2>Order Support</h2>';
  echo '<div id="wf-support-admin"></div>';

}

add_action('wp_ajax_wf_mark_support_seen','wf_mark_support_seen');

function wf_mark_support_seen(){

    check_ajax_referer('wf_vendor_orders','nonce');

    if(!is_user_logged_in()) wp_send_json_error('Unauthorized');

    global $wpdb;

    $order = intval($_POST['order_id']);

    $wpdb->update(
        $wpdb->prefix.'wf_support_chat',
        ['seen'=>1],
        ['order_id'=>$order]
    );

    wp_send_json_success();

}


function wf_get_unread_support($order_id){

  global $wpdb;

  return (int)$wpdb->get_var(

    $wpdb->prepare(
      "SELECT COUNT(*) FROM {$wpdb->prefix}wf_support_chat
       WHERE order_id=%d AND seen=0",
       $order_id
    )

  );

}



function wf_support_admin_page(){

      add_menu_page(
      'Support Desk',
      'Support Desk',
      'manage_woocommerce',
      'wf-support',
      'wf_render_support_dashboard',
      'dashicons-headset',
      25
    );


}

function wf_assign_agent($order,$agent){

  global $wpdb;

  $table = $wpdb->prefix.'wf_support_assignments';

  $wpdb->replace($table,[
    'order_id'=>$order,
    'agent_id'=>$agent
  ]);

}

function wf_get_assigned_agent_id($order_id){

  global $wpdb;

  return (int) $wpdb->get_var(
    $wpdb->prepare(
      "SELECT agent_id
       FROM {$wpdb->prefix}wf_support_assignments
       WHERE order_id = %d",
       $order_id
    )
  );
}

function wf_get_assigned_agent($order_id){

  $agent_id = wf_get_assigned_agent_id($order_id);

  if(!$agent_id){
    return false;
  }

  $user = get_user_by('id', $agent_id);

  if(!$user){
    return false;
  }

  return $user->display_name;
}

function wf_get_support_agents(){

  return get_users([
    'role' => 'support_agent'
  ]);}


function wf_render_support_dashboard(){

  global $wpdb;

  



  /* =====================
     DASHBOARD TABLE
  ===================== */


  /* Handle Assignment */

  if(
    isset($_POST['assign'], $_POST['order'], $_POST['agent']) &&
    check_admin_referer('wf_assign_agent_nonce','wf_assign_nonce')
  ){

    if ( wf_od_is_user_plugin_admin() || current_user_can('manage_options') ) {

      wf_assign_agent(
        intval($_POST['order']),
        intval($_POST['agent'])
      );

      echo '<div class="notice notice-success"><p>Agent assigned.</p></div>';
    }
  }


  $orders = $wpdb->get_results("
    SELECT DISTINCT order_id
    FROM {$wpdb->prefix}wf_support_chat
    ORDER BY created_at DESC
  ");


  echo '<h1>📊 Support Dashboard</h1>';

  echo '<table class="widefat striped">';

  echo '
    <thead>
      <tr>
        <th>Order</th>
        <th>Agent</th>
        <th>Unread</th>
        <th>Assign</th>
        <th>Action</th>
      </tr>
    </thead>
  ';


  $agents = wf_get_support_agents();


  foreach($orders as $row){

    $order_id = intval($row->order_id);

    $agent = wf_get_assigned_agent($order_id);

    $unread = wf_get_unread_support($order_id);


    echo '<tr>';

    echo '<td>#'.$order_id.'</td>';

    echo '<td>'.($agent ?: '<em>Unassigned</em>').'</td>';

    echo '<td>'.($unread ? '🔴 '.$unread : '—').'</td>';


    /* Assign */

    echo '<td>';

    if ( wf_od_is_user_plugin_admin() || current_user_can('manage_options') ) {

      echo '<form method="post" style="display:flex;gap:6px">';

      wp_nonce_field('wf_assign_agent_nonce','wf_assign_nonce');

      echo '<select name="agent">';

      foreach($agents as $a){

        echo '<option value="'.$a->ID.'">'
             .esc_html($a->display_name).
             '</option>';
      }

      echo '</select>';

      echo '<input type="hidden" name="order" value="'.$order_id.'">';

      echo '<button type="submit" name="assign" class="button">Assign</button>';

      echo '</form>';
    }

    echo '</td>';


    /* Open */

    echo '<td>
      <a class="button button-primary wf-admin-open-chat"
             data-order="'.$order_id.'"
             href="#">
             Open Chat
          </a>

    </td>';

    echo '</tr>';
  }


  echo '</table>';
  
  echo '<div id="wf-admin-chat-container"></div>';

}













/* ===========================
   SUPPORT TAB CONTENT
=========================== */

add_action(
  'woocommerce_account_support-desk_endpoint',
  'wf_render_agent_dashboard'
);


function wf_render_agent_dashboard(){

  $user = wp_get_current_user();

if(!in_array('support_agent',$user->roles)){

    echo '<p>No access.</p>';
    return;
  }

  global $wpdb;

  $uid = get_current_user_id();


  /* Get Tickets */

  $orders = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT order_id
       FROM {$wpdb->prefix}wf_support_assignments
       WHERE agent_id=%d
       ORDER BY assigned_at DESC",
       $uid
    )
  );


  ?>

  <div class="wf-agent-panel">

    <!-- Sidebar -->
    <div class="wf-ticket-list">

      <h4>📩 My Tickets</h4>

      <?php if(!$orders): ?>

        <div class="wf-empty">
          No assigned chats
        </div>

      <?php else: ?>

        <?php foreach($orders as $row):

          $oid = (int)$row->order_id;
          $unread = wf_get_unread_support($oid);

          $active = (
            isset($_GET['order']) &&
            intval($_GET['order']) === $oid
          );
        ?>

        <a
          href="javascript:void(0)"
          class="wf-ticket-item <?php echo $active ? 'active' : ''; ?>"
          data-order="<?php echo esc_attr($oid); ?>"
          data-link="<?php echo esc_url(
            wc_get_account_endpoint_url('support-desk').'?order='.$oid
          ); ?>"
        >


          <span>#<?php echo $oid; ?></span>

          <?php if($unread): ?>
            <em class="wf-badge"><?php echo $unread; ?></em>
          <?php endif; ?>

        </a>

        <?php endforeach; ?>

      <?php endif; ?>

    </div>


    <!-- Chat Panel -->
    <div class="wf-chat-panel" id="wf-chat-container">

      <?php if(isset($_GET['order'])): ?>

        <?php
          wf_render_front_chat(
            intval($_GET['order'])
          );
        ?>

      <?php else: ?>

        <div class="wf-placeholder" >

          💬 Select a ticket to start chatting

        </div>

      <?php endif; ?>

    </div>

  </div>

  <?php
}





/* ===========================
   FRONT CHAT UI
=========================== */

function wf_render_front_chat($order_id){

  if(!wf_user_can_access_order_chat($order_id)){
    return;
  }

  global $wpdb;

  $msgs = $wpdb->get_results(
    $wpdb->prepare(
      "SELECT * FROM {$wpdb->prefix}wf_support_chat
       WHERE order_id=%d
       ORDER BY id ASC",
      $order_id
    )
  );

  ?>

  <div class="wf-chat-card">

        
    
    <!-- Header -->
    <div class="wf-chat-header">
        <button class="wf-chat-back">←</button>
    <div class="wf-chat-title">
          <span>💬 Support Chat</span>
          <span>#<?php echo esc_html($order_id); ?></span>
        </div>
    </div>


    <!-- Messages -->
    <div class="wf-chat-body wf-support-messages">


      <?php foreach($msgs as $m):

        $me = ($m->sender_id == get_current_user_id());
      ?>

        <div class="wf-bubble <?php echo $me?'me':'other'; ?>">
          <?php echo esc_html($m->message); ?>
        </div>

      <?php endforeach; ?>

    </div>

    <!-- Input -->
    <form class="wf-chat-input" id="wf-front-chat-form">

      <textarea
          name="message"
          class="wf-support-text"
          name="message"
          placeholder="Type your reply..."
          required></textarea>


      <button type="submit" class="wf-support-send">➤</button>

      <input type="hidden" name="order_id"
             value="<?php echo esc_attr($order_id); ?>">

      <input type="hidden" name="action"
             value="wf_send_support_message">

      <?php wp_nonce_field('wf_vendor_orders','nonce'); ?>

    </form>

  </div>

  <?php
}



add_action('wp_ajax_wf_load_support_chat','wf_load_support_chat');

function wf_load_support_chat(){

  check_ajax_referer('wf_vendor_orders','nonce');

  if(!is_user_logged_in()){
    wp_send_json_error();
  }

  $order_id = intval($_POST['order_id']);

  if(!wf_user_can_access_order_chat($order_id)){
    wp_send_json_error();
  }

  ob_start();

  wf_render_front_chat($order_id);

  $html = ob_get_clean();

  wp_send_json_success($html);
}



















/* ======================================================
 * 1️⃣ تسجيل حالات الطلب الجديدة رسميًا (أساسي)
 * ====================================================== */
add_action('init', function () {

    $statuses = [
        'wc-received'         => 'Received',
        'wc-ready'            => 'Ready',
        'wc-with_courier'     => 'Shipped',
        'wc-out_for_delivery' => 'Out for Delivery',
    ];

    foreach ($statuses as $status => $label) {
        register_post_status($status, [
            'label'                     => $label,
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop(
                "$label <span class='count'>(%s)</span>",
                "$label <span class='count'>(%s)</span>"
            ),
        ]);
    }
});


/* ======================================================
 * 2️⃣ إدراج الحالات في WooCommerce بعد processing
 * ====================================================== */
add_filter('wc_order_statuses', function ($statuses) {

    $new = [];

    foreach ($statuses as $key => $label) {
        $new[$key] = $label;

        if ($key === 'wc-processing') {
            $new['wc-received']         = 'Received';
            $new['wc-ready']            = 'Ready';
            $new['wc-with_courier']     = 'Shipped';
            $new['wc-out_for_delivery'] = 'Out for Delivery';
        }
    }

    return $new;
});


/* ======================================================
 * 3️⃣ ضمان ظهورها في جدول الطلبات (WC 8+)
 * ====================================================== */
add_filter(
    'woocommerce_order_list_table_prepare_items_args',
    function ($args) {

        if (empty($args['status'])) {
            $args['status'] = [
                'pending',
                'processing',
                'received',
                'ready',
                'with_courier',
                'out_for_delivery',
                'completed',
                'cancelled',
                'refunded',
                'failed',
            ];
        }

        return $args;
    }
);


/* ======================================================
 * 4️⃣ إضافة Views أعلى صفحة Orders
 * ====================================================== */
add_filter('views_edit-shop_order', function ($views) {

    $custom = [
        'received'         => 'Received',
        'ready'            => 'Ready',
        'with_courier'     => 'Shipped',
        'out_for_delivery' => 'Out for Delivery',
    ];

    foreach ($custom as $status => $label) {
        $count = wc_orders_count($status);
        $views['wc-' . $status] =
            "<a href='edit.php?post_type=shop_order&status=wc-{$status}'>
                {$label} <span class='count'>({$count})</span>
            </a>";
    }

    return $views;
});


/* ======================================================
 * 5️⃣ شورت كود لوحة الفيندور
 * ====================================================== */
add_shortcode('websiteflexi_vendor_orders', 'wf_vendor_orders_shortcode');
add_action('wp_ajax_wf_update_item_status', 'wf_update_item_status');

function wf_vendor_orders_shortcode() {

    if ( ! is_user_logged_in() ) {
            return '<p>' . esc_html__( 'You must be logged in.', 'website-flexi' ) . '</p>';
        }


    $user_id = get_current_user_id();
    $delivery_responsibility = get_option('wf_delivery_responsibility','site');

    ob_start();
    ?>
    <div class="wf-vendor-orders">
        <ul class="wf-tabs">
            <li data-tab="current" class="active">
                <?php echo esc_html__( 'Active Orders', 'website-flexi' ); ?>
            </li>
            <li data-tab="history">
                <?php echo esc_html__( 'Order History', 'website-flexi' ); ?>
            </li>
        </ul>


        <div class="wf-tab-content wf-tab-current">
            <?php wf_render_vendor_orders($user_id, $delivery_responsibility, 'current'); ?>
        </div>

        <div class="wf-tab-content wf-tab-history" >
            <?php wf_render_vendor_orders($user_id, $delivery_responsibility, 'history'); ?>
        </div>
    </div>
    <?php

    wf_vendor_orders_scripts();
    return ob_get_clean();
}


/* ======================================================
 * 6️⃣ جلب أوردرات الفيندور وتقسيمها
 * ====================================================== */
function wf_render_vendor_orders($vendor_id, $delivery_responsibility, $section = 'current') {

    $orders = wc_get_orders([
        'limit'  => -1,
        'status' => [
            'pending',
            'processing',
            'received',
            'ready',
            'with_courier',
            'out_for_delivery',
            'on-hold',
            'completed',
            'cancelled',
            'refunded',
            'failed'
        ],
    ]);

    $current = [];
    $history = [];

    foreach ($orders as $order) {

        foreach ($order->get_items() as $item) {

            $product = $item->get_product();
            if ( ! $product ) continue;

            if ( (int) get_post_field('post_author', $product->get_id()) !== $vendor_id ) {
                continue;
            }

            $status = $order->get_status();

            $row = [
                'order'  => $order,
                'item'   => $item,
                'status' => $status,
            ];

            if (in_array($status, ['pending','processing','received','ready','with_courier','out_for_delivery','on-hold'])) {
                $current[] = $row;
            } else {
                $history[] = $row;
            }
        }
    }

    if ($section === 'current') {
        wf_render_orders_table($current, true, $delivery_responsibility);
    } else {
        wf_render_orders_table($history, false, $delivery_responsibility);
    }
}


/* ======================================================
 * 7️⃣ جدول العرض + الصورة المصغرة
 * ====================================================== */
function wf_render_orders_table($orders, $editable = true, $delivery_responsibility = 'site', $is_history = false) {

    if (empty($orders)) {
        echo '<p>' . esc_html__( 'No orders found.', 'website-flexi' ) . '</p>';
        return;
    }

   

    foreach ($orders as $data) {


        $support_enabled = get_option('wf_enable_support', 0);
        $order  = isset($data['order']) ? $data['order'] : false;
        $item   = isset($data['item']) ? $data['item'] : false;
        $status = isset($data['status']) ? $data['status'] : '';
        

        if ( ! $order || ! $item ) continue;

        // صورة المنتج فقط للأوردرات الجارية
        $thumb = '';
        if (!$is_history) {
            $product = $item->get_product();
            if ( $product && $product->get_image_id() ) {
                $thumb = $product->get_image('thumbnail', ['style'=>'width:50px;height:50px;border-radius:6px;']);
            } else {
                $thumb = wc_placeholder_img('thumbnail', ['style'=>'width:50px;height:50px;border-radius:6px;']);
            }
        }
        

        // اسم الحالة بدل slug
        $statuses = wc_get_order_statuses();
        $status_label = isset($statuses['wc-' . $status]) ? $statuses['wc-' . $status] : ucfirst($status);
        $unread = wf_get_unread_support($order->get_id());

        
        




        echo '
        <tr class="wf-order-card">
        
        <td colspan="100">
        
        <div class="wf-card">
        
             <!-- Header -->
                  <div class="wf-card-header">
                
                    <div 
                      class="wf-order-id"
                      data-order-id="' . esc_attr($order->get_id()) . '"
                    >
                      #' . esc_html($order->get_id()) . '
                    </div>
                
                    <span class="wf-status-badge wf-' . esc_attr($status) . '">
                      ' . esc_html($status_label) . '
                    </span>
                
                  </div>
        
            <!-- Product -->
            <div class="wf-card-product">
                ' . $thumb . '
                <div class="wf-product-info">
                    <div class="wf-product-name">' . esc_html($item->get_name()) . '</div>
                    <div class="wf-product-qty">'
                        . esc_html__('Qty:', 'website-flexi') . ' '
                        . intval($item->get_quantity()) .
                    '</div>
                </div>
            </div>
        
            <!-- Actions -->
            <div class="wf-card-actions">

                  '. ( $unread ? '<span class="wf-support-unread">'.$unread.'</span>' : '' ) .'
                
                  '. ( $support_enabled ? '
                    <button class="wf-track-btn">
                      '. esc_html__('Support','website-flexi') .'
                    </button>
                  ' : '' ) .'
                
                  ' . wf_status_buttons($order, $status, $editable) . '
                
                </div>

        
        </div>
        
        </td>
        </tr>';

    }

    echo '</table>';
}




/* ======================================================
 * 8️⃣ أزرار تغيير الحالة (منطق مضبوط)
 * ====================================================== */
function wf_status_buttons($order, $status, $editable) {

    if ( ! $editable ) {
        return '<em>View only</em>';
    }

    $map = [
        'processing' => ['received'],
        'received'   => ['ready'],
    ];

    // لو الحالة غير موجودة في الماب، نرجع زر Track Order عادي
   if (!isset($map[$status])) {
    return '<button class="wf-status-btn track-order-btn"
        data-order-id="' . esc_attr($order->get_id()) . '"
        data-status="' . esc_attr($status) . '">'
        . esc_html__('Track Order', 'website-flexi') .
    '</button>';
}



    $html = '';
    foreach ($map[$status] as $next) {
     $html .= '<button class="wf-status-btn ' . esc_attr($next) . '"
    data-order-id="' . esc_attr($order->get_id()) . '"
    data-order="' . esc_attr($order->get_id()) . '"
    data-status="' . esc_attr($next) . '">'
    . esc_html(ucwords(str_replace('_',' ',$next))) .
'</button> ';

    }

    return $html;
}







/* ======================================================
 * 9️⃣ AJAX – تغيير الحالة الحقيقية للأوردر
 * ====================================================== */
function wf_update_item_status() {
    check_ajax_referer('wf_vendor_orders','nonce');

    if ( ! is_user_logged_in() ) {
        wp_send_json_error('Unauthorized');
    }

    $user_id  = get_current_user_id();
    $order_id = absint($_POST['order_id']);
    $new      = sanitize_text_field($_POST['status']);

    $order = wc_get_order($order_id);
    if ( ! $order ) {
        wp_send_json_error('Invalid order');
    }

    /* 🔐 تحقق أن هذا الفيندور يملك عنصر داخل الطلب */
    $is_vendor = false;
    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if ($product && (int) get_post_field('post_author', $product->get_id()) === $user_id) {
            $is_vendor = true;
            break;
        }
    }

    if ( ! $is_vendor ) {
        wp_send_json_error('Forbidden');
    }

    $current = $order->get_status();

    /* 🔒 منطق التحويل المسموح فقط */
    $allowed_transitions = [
        'processing' => 'received',
        'received'   => 'ready',
    ];

    if (
        ! isset($allowed_transitions[$current]) ||
        $allowed_transitions[$current] !== $new
    ) {
        wp_send_json_error('You are not allowed to change this order status');
    }

    /* ✅ تنفيذ التحويل */
    $order->update_status(
        $new,
        'Vendor updated order status',
        true
    );
    
    

    wp_send_json_success();
}










add_action('wp_ajax_wf_get_action_buttons', function(){
    check_ajax_referer('wf_vendor_orders','nonce');

    if(!is_user_logged_in()){
        wp_send_json_error('Unauthorized');
    }

    $order_id = absint($_POST['order_id']);
    $order = wc_get_order($order_id);
    if(!$order) wp_send_json_error('Invalid order');

    $status = $order->get_status();
    $buttons_html = wf_status_buttons($order, $status, true);

    wp_send_json_success(['html' => $buttons_html]);
});





/**
 * Get full order data for tracking & UI
 */
function wf_get_full_order_data( $order_id ) {

    $order = wc_get_order( $order_id );
    if ( ! $order ) return false;

    /* ========= Order Core ========= */
    $data = [
        'order_id' => $order->get_id(),
        'status'   => $order->get_status(),
        'payment'  => $order->get_payment_method_title(),
        'tracking' => wf_get_order_tracking_number( $order ),
        'arrival' => wf_calculate_expected_arrival( $order ),
    ];

    /* ========= Timeline Steps ========= */
    $steps = [
        'processing',
        'received',
        'ready',
        'with_courier',
        'out_for_delivery',
        'completed',
    ];

    /* ========= Dates Per Step ========= */
    $dates = [
        'processing'        => $order->get_date_created(),
        'received'          => $order->get_date_paid(),
        'ready'             => $order->get_meta('_order_ready_date'),
        'with_courier'      => $order->get_meta('_order_shipped_date'),
        'out_for_delivery'  => $order->get_meta('_order_out_for_delivery_date'),
        'completed'         => $order->get_date_completed(),
    ];

    $formatted_dates = [];

    foreach ( $steps as $step ) {
        $date = $dates[$step] ?? null;

        if ( $date instanceof WC_DateTime ) {
            $formatted_dates[$step] = $date->date_i18n('d M Y – H:i');
        } elseif ( $date ) {
            $formatted_dates[$step] = date_i18n('d M Y – H:i', strtotime($date));
        } else {
            $formatted_dates[$step] = '';
        }
    }

    /* ========= Final Payload ========= */
    $data['steps'] = $steps;
    $data['dates'] = $formatted_dates;

    return $data;
}




add_action('wp_ajax_wf_get_order_full_data', 'wf_ajax_get_order_full_data');

function wf_ajax_get_order_full_data() {

    check_ajax_referer('wf_vendor_orders','nonce');

    if ( ! is_user_logged_in() ) {
        wp_send_json_error('Unauthorized');
    }

    $order_id = absint($_POST['order_id']);
    if ( ! $order_id ) {
        wp_send_json_error('Invalid order ID');
    }

    $data = wf_get_full_order_data( $order_id );

    if ( ! $data ) {
        wp_send_json_error('Order not found');
    }

    wp_send_json_success( $data );
}



function wf_calculate_expected_arrival( WC_Order $order ) {

    $created = $order->get_date_created();
    if ( ! $created ) return '';

    $prep_min = (int) get_option('wf_prep_days_min', 1);
    $prep_max = (int) get_option('wf_prep_days_max', 1);
    $ship_min = (int) get_option('wf_ship_days_min', 1);
    $ship_max = (int) get_option('wf_ship_days_max', 1);

    // أقصى مدة متوقعة
    $max_days = $prep_max + $ship_max;

    $expected = clone $created;
    $expected->modify("+{$max_days} days");

    return $expected->date_i18n('Y-m-d');
}





add_action('woocommerce_order_status_changed', function ($order_id, $old, $new) {

    $order = wc_get_order($order_id);
    if (!$order) return;

    $meta_map = [
        'received'         => '_order_received_date',
        'ready'            => '_order_ready_date',
        'with_courier'     => '_order_shipped_date',
        'out_for_delivery' => '_order_out_for_delivery_date',
    ];

    if (isset($meta_map[$new])) {
        $order->update_meta_data(
            $meta_map[$new],
            current_time('mysql')
        );
        $order->save();
    }
}, 10, 3);



add_action('wp_ajax_wf_reload_vendor_orders', 'wf_reload_vendor_orders');

function wf_reload_vendor_orders(){
    check_ajax_referer('wf_vendor_orders','nonce');

    if ( ! is_user_logged_in() ) {
        wp_send_json_error();
    }

    $user_id = get_current_user_id();
    $delivery = get_option('wf_delivery_responsibility','site');

    ob_start();

    wf_render_vendor_orders($user_id, $delivery, 'current');

    $html = ob_get_clean();

    wp_send_json_success([
        'html' => $html
    ]);
}



/**
 * Get real order step dates for timeline
 */
function wf_get_order_step_dates( $order_id ) {
    if ( ! $order_id ) return [];

    $order = wc_get_order( $order_id );
    if ( ! $order ) return [];

    // الخطوات الرئيسية
   $steps = [
    'processing' => $order->get_date_created(), 
    'received' => $order->get_meta('_order_received_date'),
    'ready' => $order->get_meta('_order_ready_date'), 
    'with_courier' => $order->get_meta('_order_shipped_date'), 
    'out_for_delivery' => $order->get_meta('_order_out_for_delivery_date'), 
    'completed' => $order->get_date_completed()
];


    $formatted = [];
    foreach ( $steps as $key => $date ) {
        if ( $date ) {
            // ننسق التاريخ بشكل احترافي
            if ( $date instanceof WC_DateTime ) {
                $formatted[$key] = $date->date_i18n('d M Y – H:i'); // يمكن تغيير التنسيق
            } else {
                $formatted[$key] = date_i18n('d M Y – H:i', strtotime($date));
            }
        } else {
            $formatted[$key] = ''; // لو لا يوجد تاريخ
        }
    }

    return $formatted;
}
add_action('wp_footer', function() {
    $orders = wc_get_orders(['limit' => -1]); // جلب كل الطلبات للعرض
    $order_dates = [];
    foreach ($orders as $order) {
        $order_dates[$order->get_id()] = wf_get_order_step_dates($order->get_id());
    }
    ?>
    <script>
        const wfOrderDates = <?php echo json_encode($order_dates); ?>;
    </script>
    <?php
});


/* ======================================================
 * 🔟 JS + CSS
 * ====================================================== */
function wf_vendor_orders_scripts() { 

    
    $steps_list = ['processing','received','ready','with_courier','out_for_delivery','completed'];
    $step_labels = [
        'processing' => __('Processing','website-flexi'),
        'received' => __('Received','website-flexi'),
        'ready' => __('Ready','website-flexi'),
        'with_courier' => __('Shipped','website-flexi'),
        'out_for_delivery' => __('Out for delivery','website-flexi'),
        'completed' => __('Completed','website-flexi')
    ];

    // اجلب التواريخ الحقيقية
    
    


    ?>

<!-- Mobile Order Tracker -->
<div id="mobile-order-tracker" class="mobile-tracker-modal" style="display:none" dir="rtl">
    <div class="mobile-tracker-overlay"></div>

    <div class="mobile-tracker-sheet">

        <!-- Header -->
        <div class="mobile-tracker-header">
            <span class="grabber"></span>
            <h3 class="mobile-tracker-title"><?php _e('Order Progress', 'website-flexi'); ?></h3>
        </div>

        <!-- Body -->
        <div class="mobile-tracker-body">

            <!-- TIMELINE SIDE -->
                <div class="mobile-tracker-timeline-wrap">
                    <div class="mobile-tracker-timeline" data-current-step="">
                        <span class="mobile-timeline-progress"></span>
                        <span class="mobile-timeline-loading"></span>
                
                        <?php foreach ( $steps_list as $step ) : ?>
                            <div class="mobile-tracker-step <?php echo esc_attr($step); ?>"
                                 data-step="<?php echo esc_attr($step); ?>">
                
                                <span class="dot"></span>
                
                                <div class="mobile-step-content">    
                                    <span class="mobile-label">
                                        <?php echo esc_html($step_labels[$step]); ?>
                                    </span>
                
                                    <!-- التاريخ يتم ملؤه بالـ JS -->
                                    <span class="mobile-step-date"></span>
                                </div>
                
                            </div>
                        <?php endforeach; ?>
                
                    </div>
                </div>
            <!-- ORDER INFO SIDE -->
                <div class="mobile-order-info-wrap">
                
                    <div class="info-card">
                        <label><?php _e('Order Number', 'website-flexi'); ?></label>
                        <strong class="js-mobile-order-id">—</strong>
                    </div>
                
                    <div class="info-card">
                      <label><?php _e('Tracking Number', 'website-flexi'); ?></label>
                        <strong class="js-mobile-tracking">—</strong>
                    </div>
                
                    <div class="info-card">
                      <label><?php _e('Expected Arrival', 'website-flexi'); ?></label>
                        <strong class="js-mobile-arrival">—</strong>
                    </div>
                
                    <div class="info-card highlight">
                        <label><?php _e('Payment Method', 'website-flexi'); ?></label>
                        <strong class="js-mobile-payment">—</strong>
                    </div>

            </div>
        </div>

        <button class="mobile-close-tracker">
            <?php _e('Close', 'website-flexi'); ?>
        </button>

    </div>
</div>




<div id="order-tracker-modal" class="order-tracker" style="display:none;">
    <div class="tracker-overlay"></div>

    <div class="tracker-content">
        <h3 class="tracker-title"><?php _e('Order Progress', 'website-flexi'); ?></h3>

        <div class="tracker-container">
            <!-- Order Details -->
            <div class="order-header">
                <div class="order-info">
                    <div class="order-details">
                        <span class="order-id">
                                <?php _e('Order ID', 'website-flexi'); ?>:
                                <strong class="js-order-id">—</strong>
                            </span>
                            
                            <span class="tracking-id">
                                <?php _e('Tracking ID', 'website-flexi'); ?>:
                                <strong class="js-tracking">—</strong>
                            </span>
                            
                            <span class="expected-arrival">
                                <?php _e('Expected Arrival', 'website-flexi'); ?>:
                                <strong class="js-arrival">—</strong>
                            </span>
                            
                            <span class="payment-method">
                                <?php _e('Payment', 'website-flexi'); ?>:
                                <strong class="js-payment">—</strong>
                            </span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="tracker-timeline" data-current-step="ready" >
                <span class="timeline-progress"></span>
                <span class="timeline-loading"></span>

                <div class="tracker-step processing" data-step="processing" data-pos="">
                    <div class="icon">⏳</div>
                    <div class="label"><?php _e('Processing', 'website-flexi'); ?></div>
                </div>

                <div class="tracker-step received" data-step="received" data-pos="">
                    <div class="icon">✅</div>
                    <div class="label"><?php _e('Received', 'website-flexi'); ?></div>
                </div>

                <div class="tracker-step ready" data-step="ready" data-pos="">
                    <div class="icon">📦</div>
                    <div class="label"><?php _e('Ready', 'website-flexi'); ?></div>
                </div>

                <div class="tracker-step shipped" data-step="with_courier" data-pos="">
                    <div class="icon">🚚</div>
                    <div class="label"><?php _e('Shipped', 'website-flexi'); ?></div>
                </div>

                <div class="tracker-step out_for_delivery" data-step="out_for_delivery" data-pos="">
                    <div class="icon">🏠</div>
                    <div class="label"><?php _e('Out for delivery', 'website-flexi'); ?></div>
                </div>

                <div class="tracker-step completed" data-step="completed" data-pos="">
                    <div class="icon">🎉</div>
                    <div class="label"><?php _e('Completed', 'website-flexi'); ?></div>
                </div>
            </div>
        </div>

        <button class="close-tracker"><?php _e('Close', 'website-flexi'); ?></button>
    </div>
</div>


<!-- ===========================
   WF ORDER SUPPORT MODAL
=========================== -->

<div id="wf-support-modal" class="wf-support-modal">

  <div class="wf-support-overlay"></div>

  <div class="wf-support-box">

    <!-- ORDER CARD HEADER -->
    <div class="wf-support-order-card">

      <div class="wf-support-product">

        <img class="wf-support-thumb" src="" />

        <div class="wf-support-product-info">

          <div class="wf-support-title"></div>

          <div class="wf-support-sub">

            <span class="wf-support-order-id"></span>

            <span class="wf-support-qty"></span>

          </div>

        </div>

      </div>

      <div class="wf-support-status">

        <span class="wf-support-badge"></span>

        <span class="wf-support-live">
          🟢 Live Support
        </span>

      </div>

      <div class="wf-support-actions">
          <button class="wf-support-close">×</button>
        </div>


    </div>


    <!-- CHAT AREA -->
    <div class="wf-support-messages">
        

        
    </div>


    <!-- INPUT -->
    <div class="wf-support-input">

      <input
        type="text"
        class="wf-support-text"
        placeholder="Type your message about this order..."
      >

      <button class="wf-support-send">
        ➤
      </button>

    </div>

  </div>

</div>













<style>
    
    




.arrival-warning {
    color: #f9a825; /* أصفر */
    font-weight: bold;
}

.arrival-late {
    color: #d32f2f; /* أحمر */
    font-weight: bold;
}


   .timeline-loading {
    position: absolute;
    top: 35%;
    height: 6px;
    transform: translateY(-50%);
    border-radius: 3px;
    background: linear-gradient( to right, rgba(67,160,71,0) 0%, #43a047 30%, #ff9800 60%, rgba(255,152,0,0) 100% );
    background-size: 300% 100%;
    animation: flowToNext 1.6s linear infinite;
    pointer-events: none !important;
    z-index: 1;
    inset-inline-start: 0;
}

@keyframes flowToNext {
    0% {
        background-position: -100% 50%;
        opacity: 0.4;
    }
    30% {
        opacity: 1;
    }
    100% {
        background-position: 200% 50%;
        opacity: 0.4;
    }
}





.mobile-timeline-loading {
    position: absolute;
    right: 8px;
    width: 3px;
    border-radius: 3px;

    /* ===== BACKGROUND LAYERS ===== */
    background:
        /* 1️⃣ الجزء الثابت (أول 50%) */
        linear-gradient( to bottom, #43a047, #ff9800 ) 0% 0% / 100% 50% no-repeat,

        /* 2️⃣ الجزء المتحرك (آخر 50%) */
        linear-gradient( to bottom, #ddd 0%, #ff9800 45%, #ff9800 55%, #ddd 100% ) 0% 100% / 100% 200% ;

    animation: loadingMoveV 1.4s linear infinite;
    display: none;
    pointer-events: none !important;
    z-index: 1;
}


@keyframes loadingMoveV {
    0% { background-position:
            0% 0%,      /* الطبقة الأولى: ثابتة تمامًا */
            0% 200%;    /* الطبقة الثانية: تبدأ من تحت */
        }
    100% {
        background-position:
            0% 0%,      /* الطبقة الأولى: لا تتحرك */
            0% 0%;      /* الطبقة الثانية: تطلع لفوق */
    }
}





.timeline-progress,
.mobile-timeline-progress {
    pointer-events: none;
    z-index: 0;
}






    
/* === Tracker Modal === */
#order-tracker-modal {
    position: fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:9999;
}
.tracker-overlay {
    position:absolute;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
}
.tracker-content {
    position:relative;
    background:#fff;
    padding:24px;
    border-radius:16px;
    max-width:700px;
    width:90%;
    box-shadow:0 10px 40px rgba(0,0,0,0.25);
}
.tracker-content h3 {
    text-align:center;
    margin-bottom:24px;
    font-size:22px;
    font-weight:700;
    color:#333;
}

/* === Timeline Horizontal === */
.tracker-timeline {
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:relative;
    margin:30px 0;
    position: relative;
}


.tracker-timeline::before {
    content:'';
    position:absolute;
    top:35%;
    left:0;
    width:100%;
    height:6px;
    background:#ddd;
    transform:translateY(-50%);
    z-index:0;
}

/* === Individual Step === */
.tracker-step {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width:120px;
    z-index:1;
    word-break: break-word;
}

.timeline-progress {
    position: absolute;
    top: 35%;
    inset-inline-start: 0;   /* ⭐ مهم */
    height: 6px;
    transform: translateY(-50%);
    border-radius: 3px;
    z-index: 1;
    width: 0;
    background: #43a047;
    pointer-events: none;
}


.tracker-step {
    --step-index: 0;
}
.tracker-step.processing { --step-index: 0; }
.tracker-step.received   { --step-index: 1; }
.tracker-step.ready      { --step-index: 2; }
.tracker-step.shipped    { --step-index: 3; }
.tracker-step.out_for_delivery { --step-index: 4; }
.tracker-step.completed  { --step-index: 5; }


/* Step Icon */
.tracker-step .icon {
    width: 50px;
    height: 50px;
    line-height: 50px;
    border-radius: 50%;
    background:#ddd;
    color:#fff;
    font-size:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:8px;
    transition: all 0.3s ease;
}
.tracker-step.completed .icon { background:#43a047; } /* أخضر */
.tracker-step.current .icon { background:#ff9800; }  /* برتقالي */
.tracker-step.upcoming .icon { background:#ccc; }     /* رمادي */

/* Step Label */
.tracker-step .label {
    font-size:14px;
    font-weight:600;
    color:#555;
    white-space: nowrap; /* منع كسرة السطر إلا عند الحاجة */
}


/* === Order Header – Pro UI/UX === */
.order-header {
    display: flex;
    align-items: center;
    justify-content: space-between; /* توزيع المساحة */
    gap: 24px;
    margin-bottom: 24px;
    flex-wrap: wrap;
    background: #f5f7fa;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

/* أيقونة الأوردر كبيرة وجامب */
.order-icon {
    font-size: 40px;
    background: #0071e3;
    color: #fff;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* قسم المعلومات */
.order-info {
    display: flex;
    flex: 1;
    flex-wrap: wrap;
    gap: 12px 20px; /* صفوف وأعمدة */
}

/* Order ID */
.order-id {
    font-weight: 700;
    font-size: 16px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* باقي التفاصيل */
.order-details {
    display: flex;
    flex: 1;
    flex-wrap: wrap;
    gap: 12px 20px; /* صفوف وأعمدة */
}

.order-details span {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #e0e7ff;
    padding: 6px 10px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 16px;
    color: #333;
    flex: 1 1 45%; /* عرض حوالي نصف المساحة لكل badge */
    min-width: 140px; /* لا يقل عن هذا */
}

/* أيقونات صغيرة للتفاصيل */
.order-details .order-id::before { content: "🛒"; }
.order-details .payment-method::before { content: "💳"; }
.order-details .expected-arrival::before { content: "📅"; }
.order-details .tracking-id::before { content: "🔑"; }


/* === Responsive Mobile Vertical Timeline === */
/* === Mobile Vertical Timeline === */
/* === Mobile Vertical Timeline === */

/* === Close Button === */
.close-tracker {
    margin-top:24px;
    padding:12px 24px;
    background:#0071e3;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:0.25s;
}
.close-tracker:hover { filter: brightness(1.1); }






/* =========================================
   Mobile Only Order Tracker Modal
========================================= */






@media (max-width: 768px) {

.mobile-tracker-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: flex-end;
    font-family: -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
    height: 100% !important;
}

.mobile-tracker-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1;
    pointer-events: auto;
}

/* ===== Bottom Sheet ===== */
.mobile-tracker-sheet {
    width: 100%;
    max-height: 80%;
    background: #fff;
    border-radius: 22px 22px 0 0;
    padding: 12px 16px 50px !important;
    display: flex;
    flex-direction: column;
    animation: slideUp .35s ease;
    position: relative;
    z-index: 2;
    pointer-events: auto;
}

@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

/* ===== Header ===== */
.mobile-tracker-header {
    text-align: center;
    position: relative;
    padding-bottom: 8px;
}

.grabber {
    width: 40px;
    height: 4px;
    background: #ddd;
    border-radius: 10px;
    display: block;
    margin: 0 auto 8px;
}

.mobile-tracker-title {
    font-size: 17px;
    font-weight: 700;
}

/* ===== Body Split ===== */
.mobile-tracker-body {
    display: grid;
    grid-template-columns: 45% 55%;
    gap: 12px;
    margin-top: 10px;
}

/* ===== Timeline ===== */
.mobile-tracker-timeline-wrap {
    position: relative;
}

.mobile-tracker-timeline {
    position: relative;
    padding-right: 22px;
}

.mobile-tracker-timeline::before {
    content: '';
    position: absolute;
    right: 8px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #e0e0e0;
}

.mobile-timeline-progress {
    position: absolute;
    right: 8px;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(to bottom, #4caf50, #ff9800);
    transform-origin: top;
    transform: scaleY(.65); /* تتغير بالـ JS */
}

/* Steps */
.mobile-tracker-step {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    position: relative;
}

.mobile-tracker-step .dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ccc;
    flex-shrink: 0;
}

.mobile-tracker-step.completed .dot {
    background: #4caf50;
}

.mobile-tracker-step.current .dot {
    background: #ff9800;
    box-shadow: 0 0 0 6px rgba(255,152,0,.25);
}

.mobile-label {
    font-size: 13px;
    font-weight: 600;
}

/* ===== Order Info ===== */
.mobile-order-info-wrap {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-bottom:20px ;
}

.info-card {
    background: #f7f8fa;
    border-radius: 12px;
    padding: 10px 12px;
}

.info-card label {
    font-size: 12px;
    color: #777;
    display: block;
}

.info-card strong {
    font-size: 14px;
    font-weight: 700;
}

.info-card.highlight {
    background: linear-gradient(135deg,#eef2ff,#fff);
}

/* ===== Close ===== */
.mobile-close-tracker {
    margin-top: 12px;
    padding: 14px;
    border: none;
    border-radius: 14px;
    background: #111;
    color: #fff;
    font-weight: 700;
    font-size: 15px;
}

.mobile-step-content {
    display: flex;
    flex-direction: column;
    gap: 3px; /* مسافة صغيرة بين اللابل والتاريخ */
    align-items: flex-start;
}

.mobile-step-date {
    font-size: 11px;
    color: #555; /* لون داكن قليلًا ليكون واضح */
    opacity: 0.9;
    line-height: 1.2;
    transition: all .3s ease;
}

/* التاريخ لا يظهر للحالات القادمة */
.mobile-tracker-step.upcoming .mobile-step-date {
    display: none;
}

/* الحالة الحالية */
.mobile-tracker-step.current .mobile-step-date {
    color: #ff9800; /* نفس لون النقطة */
    font-weight: 600;
}

/* تحسين المسافة بين dot و المحتوى */
.mobile-tracker-step {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 28px;
    position: relative;
}



}





















/* =====================================================
   TABLE RESET (Desktop)
===================================================== */


/* ===============================
   Skeleton Loader for WF Cards
================================ */

.wf-loading {
    position: relative;
    pointer-events: none;
    overflow: hidden;
}

/* إخفاء المحتوى الحقيقي */
.wf-loading * {
    visibility: hidden;
}

/* طبقة السكيلتون */
.wf-loading::after {
    content: '';
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            100deg,
            #eee 40%,
            #f5f5f5 50%,
            #eee 60%
        );

    background-size: 200% 100%;

    animation: wf-shimmer 1.2s infinite;

    border-radius: 12px;
    z-index: 10;
}

/* الحركة */
@keyframes wf-shimmer {
    from {
        background-position: 200% 0;
    }

    to {
        background-position: -200% 0;
    }
}





.wf-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
}

.wf-table th {
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #666;
    padding: 10px 12px;
}

.wf-table td {
    padding: 0;
    border: none;
}

/* =====================================================
   CARD CONTAINER
===================================================== */
.wf-card {
    display: grid;
    grid-template-columns: 160px 1fr auto;
    align-items: center;
    gap: 24px;
    padding: 18px 22px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    transition: all .25s ease;
}

.wf-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0,0,0,.12);
}

/* =====================================================
   HEADER (ORDER ID + STATUS)
===================================================== */
.wf-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
}

.wf-order-id {
    font-size: 15px;
    font-weight: 700;
    color: #111;
}

/* =====================================================
   STATUS BADGE
===================================================== */
.wf-status-badge {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 999px;
    white-space: nowrap;
}

/* Status Colors */
.wf-status-badge.wf-processing {
    background: #fff3cd;
    color: #856404;
}

.wf-status-badge.wf-received {
    background: #e3f2fd;
    color: #0d47a1;
}

.wf-status-badge.wf-ready {
    background: #ede7f6;
    color: #4527a0;
}

.wf-status-badge.wf-with_courier {
    background: #e8f5e9;
    color: #1b5e20;
}

.wf-status-badge.wf-out_for_delivery {
    background: #e0f7fa;
    color: #006064;
}

.wf-status-badge.wf-completed {
    background: #e0f2f1;
    color: #004d40;
}

/* =====================================================
   PRODUCT INFO
===================================================== */
.wf-card-product {
    display: flex;
    align-items: center;
    gap: 14px;
}

.wf-card-product img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.wf-product-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.wf-product-name {
    font-size: 14px;
    font-weight: 600;
    color: #222;
}

.wf-product-qty {
    font-size: 13px;
    color: #666;
}

/* =====================================================
   ACTIONS
===================================================== */
.wf-card-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Support Button */
.wf-track-btn {
    background: transparent;
    border: 1px solid #ddd;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 13px;
    cursor: pointer;
    transition: all .2s ease;
}

.wf-track-btn:hover {
    background: #f5f5f5;
}

/* Status Action Button */
.wf-status-btn {
    background: #0d6efd;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.wf-status-btn:hover {
    background: #0b5ed7;
}

.wf-status-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
}

/* =====================================================
   RESPONSIVE SAFETY (Desktop Only)
===================================================== */
@media (max-width: 768px) {
    .wf-card {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .wf-card-actions {
        justify-content: flex-end;
    }
}

/* === Responsive Mobile Cards === */
@media (max-width:768px){

.wf-table,
.wf-table tbody,
.wf-table tr,
.wf-table td {
    display:block;
    width:100%;
}

.wf-table thead { display:none; }

.wf-order-card td {
    padding:0;
    border:none;
}

/* ===== Card ===== */
.wf-card {
    background:#fff;
    border-radius:16px;
    margin-bottom:18px;
    padding:16px;
    box-shadow:0 10px 28px rgba(0,0,0,0.08);
}

/* Header */
.wf-card-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
}

.wf-order-id {
    font-weight:800;
    font-size:16px;
}

.wf-status-badge {
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    color:#fff;
}

/* Status Colors */
.wf-processing { background:#ff6d00; }
.wf-received { background:#43a047; }
.wf-ready { background:#1976d2; }
.wf-with_courier { background:#8e24aa; }
.wf-out_for_delivery { background:#fbc02d; color:#000; }
.wf-completed { background:#2e7d32; color:#fff; }
/* Product */
.wf-card-product { 
        display:flex; 
    gap:14px;
    align-items:center;
}

.wf-card-product img {
    width:70px;
    height:70px;
    border-radius:12px;
    object-fit:cover;
}

.wf-product-name {
    font-weight:700;
    font-size:15px;
}

.wf-product-qty {
    font-size:13px;
    color:#666;
}

/* Actions */
.wf-card-actions {
    display:flex;
    gap:10px;
    margin-top:14px;
}

.wf-track-btn {
    flex:1;
    padding:12px;
    border:none;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
}

.wf-track-btn {
    background:#0071e3;
    color:#fff;
}



/* Expand */
.wf-card-expand {
    display:none;
    margin-top:14px;
}

.wf-card.expanded .wf-card-expand {
    display:block;
}

}





    
    
</style>














<?php }
