<?php
/**
 * Functions: Functions
 * Location: includes/functions.php
 */
 
 
 // Hide admin bar for shop managers ONLY
 
 
 

 
 
add_filter('show_admin_bar', function($show) {

    if (current_user_can('shop_manager')) {
        return false; // hide admin bar
    }

    return $show; // keep it normally for admins
});
 
 
/**
 * USER Manage Products (User Mode)
 *
 * Shortcode: [styliiiish_user_manage_products]
 * 
 * 🧠 الخلاصة

الكود ده:

✔ بيعمل صفحة إدارة منتجات للمستخدم العادي
✔ باستخدام نفس منطق ولوحة تحكم الأونر
✔ عن طريق Shortcode
✔ مع تحميل CSS / JS مشترك
✔ والتحكم في السلوك عن طريق mode = user
 * 
 * 
 */
function styliiiish_user_manage_products_shortcode() {

    if (!is_user_logged_in()) {
        return '<p>You must be logged in to see your products.</p>';
    }

    ob_start();

    echo '<div class="styliiiish-user-manage-products">';
    echo '<h2 style="margin-bottom:20px;">' . esc_html__( 'My Products', 'website-flexi' ) . '</h2>';


    // نفس نسخة المانجر لكن بمود "user"
    styliiiish_render_manage_products('user');

    echo '</div>';

    return ob_get_clean();
}





















add_shortcode('styliiiish_user_manage_products', 'styliiiish_user_manage_products_shortcode');

add_action('wp_enqueue_scripts', function () {

    // تأكد إنها صفحة الحساب
    if ( ! function_exists('is_account_page') || ! is_account_page() ) {
        return;
    }


    /* =========================
       CSS
    ========================= */

    wp_enqueue_style(
        'sty-owner-css',
        WF_OWNER_DASHBOARD_URL . 'assets/css/owner-style.css',
        [],
        time()
    );

    wp_enqueue_style(
        'sty-owner-mobile-css',
        WF_OWNER_DASHBOARD_URL . 'assets/css/mobile.css',
        ['sty-owner-css'],
        time()
    );


    wp_enqueue_style(
        'wf-add-modal',
        WF_OWNER_DASHBOARD_URL . 'assets/css/add-product-modal.css',
        ['sty-owner-css'],
        time()
    );


    wp_enqueue_style(
        'select2-css',
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
    );



    /* =========================
       JS
    ========================= */

    wp_enqueue_script(
        'select2-js',
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
        ['jquery'],
        null,
        true
    );


    // ✅ SweetAlert الصحيح
    wp_enqueue_script(
        'sweetalert2',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11',
        ['jquery'],
        null,
        true
    );


    wp_enqueue_script(
        'sty-owner-js',
        WF_OWNER_DASHBOARD_URL . 'assets/js/owner-dashboard-theme.js',
        ['jquery','select2-js','sweetalert2'],
        time(),
        true
    );


    wp_enqueue_script(
        'wf-add-modal',
        WF_OWNER_DASHBOARD_URL . 'assets/js/add-product-modal.js',
        ['jquery','sty-owner-js'],
        time(),
        true
    );



    /* =========================
       AJAX DATA
    ========================= */


    // 🔹 القديم (علشان باقي النظام ما يقعش)
    wp_localize_script(
        'sty-owner-js',
        'ajax_object',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ajax_nonce'),
        ]
    );


    // 🔹 الجديد (للمودال)
    wp_localize_script(
         'wf-add-modal',
         'wfModal',
         [
           'ajax'  => admin_url('admin-ajax.php'),
           'nonce' => wp_create_nonce('ajax_nonce'),
         ]
        );


});






add_filter( 'user_has_cap', function ( $caps, $cap, $args, $user ) {

    if ( in_array( 'customer', $user->roles, true ) ) {

        // Allow uploads
        $caps['upload_files'] = true;

        // Force allow async-upload from frontend
        $caps['edit_posts'] = true;

        // Prevent access to wp-admin even with edit_posts
        if ( is_admin() && ! wp_doing_ajax() ) {
            $caps['edit_posts'] = false;
        }
    }

    return $caps;

}, 10, 4 );


add_filter( 'upload_mimes', function( $mimes ) {
    $mimes['jpg']  = 'image/jpeg';
    $mimes['jpeg'] = 'image/jpeg';
    $mimes['png']  = 'image/png';
    $mimes['webp'] = 'image/webp';
    return $mimes;
});


add_filter( 'wp_handle_upload_prefilter', function( $file ) {

    if ( ! is_user_logged_in() ) {
        $file['error'] = 'Not logged in.';
        return $file;
    }

    return $file;
});


add_action('init', function () {
    $role = get_role('customer');
    if ($role && !$role->has_cap('upload_files')) {
        $role->add_cap('upload_files');
    }
});











add_filter('body_class', 'styliiiish_add_user_dashboard_body_class');
function styliiiish_add_user_dashboard_body_class($classes) {

    // لو مش في صفحة الـ owner dashboard → خروج
    if (!is_page('owner-dashboard')) {
        return $classes;
    }

    $user_id = get_current_user_id();
    $is_manager = current_user_can('manage_woocommerce') || wf_od_is_user_plugin_admin();
    
    // لو مش مدير → يبقى User Mode
    if (!$is_manager) {
        $classes[] = 'styliiiish-user-mode';
    }

    return $classes;
}








add_action('wp_enqueue_scripts', 'styliiiish_myaccount_styles');
function styliiiish_myaccount_styles() {

    // نسحب ملف CSS من داخل البلجن
    wp_enqueue_style(
        'styliiiish-myaccount-css',
        plugin_dir_url(__FILE__) . 'assets/css/myaccount-style.css',
        array(),
        time()
    );
}




/*************************           ✅ الخلاصة المختصرة

الكود ده:

✔️ يضيف Welcome Banner في My Account
✔️ قابل للتفعيل / التعطيل من الإعدادات
✔️ بيغيّر المحتوى حسب:

Admin / Manager

User عادي
✔️ بيوجه:

المدير → Owner Dashboard

المستخدم → صفحة إضافة المنتجات                  

***********************************/





add_action( 'woocommerce_account_dashboard', 'websiteflexi_myaccount_welcome_box' );
function websiteflexi_myaccount_welcome_box() {

    // 🔐 Check if banner enabled from plugin settings
    $enabled = get_option( 'websiteflexi_myaccount_banner', 'yes' );
    if ( $enabled !== 'yes' ) {
        return;
    }

    $site_url = site_url();

    // هل مدير؟
    $is_manager = current_user_can( 'manage_woocommerce' ) || wf_od_is_user_plugin_admin();

    echo '<div class="styliiiish-welcome-box">';

    if ( $is_manager ) {

        $manager_title = websiteflexi_get_i18n_option(
            'wf_welcome_manager_title',
            __( 'Welcome back, Boss! 🔧✨', 'website-flexi' )
        );

        $manager_text = websiteflexi_get_i18n_option(
            'wf_welcome_manager_text',
            __( 'Here’s your quick access...', 'website-flexi' )
        );

        $manager_btn = websiteflexi_get_i18n_option(
            'wf_welcome_manager_btn',
            __( '🔧 Manage Your Website', 'website-flexi' )
        );

        printf(
            '
            <h2 class="sty-title">%s</h2>

            <p class="sty-text">%s</p>

            <a href="%s" class="sty-btn-admin-full">%s</a>
            ',
            esc_html( $manager_title ),
            esc_html( $manager_text ),
            esc_url( $site_url . '/my-account/moderate-site/' ),
            esc_html( $manager_btn )
        );

    } else {

        $user_title = websiteflexi_get_i18n_option(
            'wf_welcome_user_title',
            __( 'Hey lovely! 💖', 'website-flexi' )
        );

        $user_text = websiteflexi_get_i18n_option(
            'wf_welcome_user_text',
            __( 'Ready to earn money now? You can list your products in just a few smooth steps. We’re here to make it easy for you! ✨', 'website-flexi' )
        );

        $user_btn = websiteflexi_get_i18n_option(
            'wf_welcome_user_btn',
            __( 'Add Your Products Now', 'website-flexi' )
        );

        printf(
            '
            <h2 class="sty-title">%s</h2>

            <p class="sty-text">%s</p>

            <a href="%s" class="sty-btn-user-full">%s</a>
            ',
            esc_html( $user_title ),
            esc_html( $user_text ),
            esc_url( $site_url . '/my-account/vendor-dashboard/' ),
            esc_html( $user_btn )
        );
    }

    echo '</div>';
}






















add_action('wp_ajax_styliiiish_update_status', function () {
    check_ajax_referer('ajax_nonce','nonce');

    if ( ! ( current_user_can('manage_woocommerce') || wf_od_is_user_plugin_admin() ) ) {
        wp_send_json_error(['message' => 'No permission']);
    }

    $pid    = intval($_POST['product_id']);
    $status = sanitize_text_field($_POST['status']);

    if (!$pid || !$status) {
        wp_send_json_error(['message' => 'Invalid parameters']);
    }

    wp_update_post([
        'ID'          => $pid,
        'post_status' => $status
    ]);

    wp_send_json_success(['message' => 'Status updated']);
});









/********************                  🔹 الهدف من الكود؟

غالبًا الهدف واحد من دول:

✅ منع تعارض JavaScript في صفحات الداشبورد
✅ تقليل تحميل سكربتات مش محتاجها
✅ تحسين الأداء
✅ منع ثيم Ekart أو Shopire من كسر UI الداشبورد
✅ حماية صفحات Custom Dashboard من JS بتاع الثيم                 ******************************/



add_action('wp_print_scripts', function() {

    if ( is_page('owner-dashboard') || is_page('manage-products') || is_page('manage-customer-products') ) {

        $handles = [
            'theme', 'custom', 'ekart-custom', 'ekart-theme',
            'shopire-theme', 'shopire-custom', // بعض نسخ ekart بتستخدم دول
        ];

        foreach ($handles as $h) {
            wp_dequeue_script($h);
            wp_deregister_script($h);
        }
    }
}, 200);













/* ============================
   ADD / UPDATE RATING
============================ */

function taj_add_vendor_rating($vendor_id, $rating) {

    if (!$vendor_id || !$rating) {
        return;
    }

    $count = (int) get_user_meta($vendor_id, 'taj_vendor_rating_count', true);
    $avg   = (float) get_user_meta($vendor_id, 'taj_vendor_rating_avg', true);

    $new_count = $count + 1;
    $new_avg   = (($avg * $count) + $rating) / $new_count;

    update_user_meta($vendor_id, 'taj_vendor_rating_count', $new_count);
    update_user_meta($vendor_id, 'taj_vendor_rating_avg', round($new_avg, 2));
}


/* ============================
   DISPLAY RATING
============================ */

function taj_show_vendor_rating($vendor_id) {

    if (!$vendor_id) {
        return;
    }

    $rating = get_user_meta($vendor_id, 'taj_vendor_rating_avg', true);
    $count  = get_user_meta($vendor_id, 'taj_vendor_rating_count', true);

    if ($rating && $count) {

        echo '<div class="vendor-rating">';
        echo '⭐ ' . esc_html($rating) . ' (' . esc_html($count) . ' reviews)';
        echo '</div>';

    }
}









/*--------------------------------------------------------------
# Display Vendor Info Box on Single Product with Status Colors
--------------------------------------------------------------*/
add_action('woocommerce_single_product_summary', function () {

    global $product, $wpdb;

    $vendor_id = get_post_field('post_author', $product->get_id());
    $user = get_user_by('ID', $vendor_id);
    if (!$user) return;

    $store_name = get_user_meta($vendor_id, 'taj_store_name', true) ?: $user->display_name;
    $logo_url   = get_user_meta($vendor_id, 'taj_store_logo', true) ?: wc_placeholder_img_src();
    $verified   = get_user_meta($vendor_id, 'taj_vendor_verified', true);

    // Vendor status
    $roles = (array) $user->roles;
    if ( in_array('taj_vendor_pending', $roles, true) ) {
        $status_text = esc_html__('Pending Approval', 'website-flexi');
        $status_color = '#D4A017';
    } elseif ( in_array('taj_vendor_suspended', $roles, true) ) {
        $status_text = esc_html__('Suspended', 'website-flexi');
        $status_color = '#B22222';
    } else {
        $status_text = esc_html__('Active', 'website-flexi');
        $status_color = '#3498db';
    }

    // Reviews
    $reviews_count = (int) $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM {$wpdb->comments} c
        INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
        WHERE c.comment_type='vendor_review' AND c.comment_approved=1
          AND cm.meta_key='vendor_id' AND cm.meta_value=%d
    ", $vendor_id));

    $avg_rating = (float) $wpdb->get_var($wpdb->prepare("
        SELECT AVG(meta_value) FROM {$wpdb->commentmeta}
        WHERE meta_key='rating' AND comment_id IN (
            SELECT comment_ID FROM {$wpdb->comments}
            WHERE comment_type='vendor_review' AND comment_approved=1
              AND comment_ID IN (
                SELECT comment_ID FROM {$wpdb->commentmeta}
                WHERE meta_key='vendor_id' AND meta_value=%d
              )
        )
    ", $vendor_id));
    $avg_rating = $avg_rating ? number_format($avg_rating,1) : '';

    // Order success
    $products = get_posts([
        'post_type'=>'product', 'post_status'=>['publish','pending','draft'],
        'author'=>$vendor_id, 'numberposts'=>-1, 'fields'=>'ids'
    ]);

    $total_orders = $completed_orders = 0;
    if ($products) {
        $orders = wc_get_orders(['limit'=>-1,'status'=>['completed','refunded','failed','cancelled']]);
        foreach($orders as $order){
            foreach($order->get_items() as $item){
                if(in_array($item->get_product_id(), $products)){
                    $total_orders++;
                    if($order->has_status('completed')) $completed_orders++;
                    break;
                }
            }
        }
    }
    $success_rate = $total_orders>0 ? round(($completed_orders/$total_orders)*100) : 0;

    $url = home_url('/vendor/'.$user->user_login);

    echo '<div class="product-vendor-box">';
        // Logo
        echo '<div class="vendor-logo"><a href="'.esc_url($url).'"><img src="'.esc_url($logo_url).'" alt="'.esc_attr($store_name).'"></a></div>';

        // Info
        echo '<div class="vendor-info">';
            // Header
            echo '<div class="vendor-header">';
               echo '<h3 class="vendor-store-name">';
    echo '<a href="'.esc_url($url).'" style="color:inherit; text-decoration:none;">' . esc_html($store_name) . '</a>';
                    if ($verified) {
                        echo ' <span class="vendor-verified-badge" data-tooltip="'.esc_attr__('Verified','website-flexi').'">
                            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                                <circle cx="12" cy="12" r="12" fill="#1877F2"/>
                                <path d="M9.5 12.8l-1.8-1.8-1.4 1.4 3.2 3.2 7-7-1.4-1.4z" fill="#fff"/>
                            </svg>
                        </span>';
                    }
                echo '</h3>';
                echo '<span class="vendor-status-badge" style="background:'.esc_attr($status_color).'">'.esc_html($status_text).'</span>';
            echo '</div>';

            // Reviews
            if($reviews_count>0 && $avg_rating){
                echo '<div class="vendor-rating">'.sprintf(__('Rating: %s / 5 (%d reviews)','website-flexi'), esc_html($avg_rating), esc_html($reviews_count)).'</div>';
            }

            // Order Success
            if($total_orders>0){
    echo '<div class="vendor-success-container">';
        echo '<div class="vendor-success-label">'.esc_html__('Order Success Rate','website-flexi').'</div>';
        echo '<div class="vendor-success-bar">';
            echo '<div class="bar" style="width:'.esc_attr($success_rate).'%"></div>';
            echo '<span class="vendor-success-text">'.esc_html($success_rate).'%</span>';
        echo '</div>';
    echo '</div>';
}


            // Learn more
           
 // echo '<a href="' . esc_url($url) . '" class="vendor-more-info" style="text-align:center; display:block;">'
    // . sprintf( esc_html__('Learn More About','website-flexi') . '<br><span dir="auto">%s</span>', esc_html($store_name) )
   //  . '</a>';


        echo '</div>';
    echo '</div>';

},25);


// CSS
add_action('wp_head', function(){
    echo '<style>

    
    .product-vendor-box {
        display:flex;
        align-items:flex-start;
        background:#fff;
        padding:15px;
        border:1px solid #e1e1e1;
        border-radius:10px;
        box-shadow:0 2px 6px rgba(0,0,0,0.05);
        gap:15px;
        margin:20px 0;
    }
    .product-vendor-box .vendor-logo img {
        width:70px;
        height:70px;
        object-fit:cover;
        border-radius:50%;
        border:1px solid #ddd;
    }
    .product-vendor-box .vendor-info {
        flex:1;
        display:flex;
        flex-direction:column;
        gap:6px;
    }
    .vendor-header {
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }
    .vendor-store-name {
        margin:0;
        font-size:18px;
        font-weight:bold;
        display:flex;
        align-items:center;
        gap:6px;
    }
    .vendor-verified-badge svg {
        vertical-align:middle;
    }
    .vendor-status-badge {
        color:#fff;
        padding:3px 8px;
        font-size:12px;
        font-weight:600;
        border-radius:5px;
    }
    .vendor-rating {
    font-size:14px;
    color:#333; /* نص داكن */
   /* background: #f5c518; /* خلفية ذهبية */*/
    padding:2px 6px;
    border-radius:4px;
    display:inline-block;
    font-weight:600;
    box-shadow: 0 1px 2px rgba(0,0,0,0.2);
   
}
    .vendor-success-bar {
        position:relative;
        height:14px;
        background:#e0e0e0;
        border-radius:7px;
        overflow:hidden;
        margin-top:4px;
    }
    .vendor-success-bar .bar {
        height:100%;
        background:#4CAF50;
    }
    .vendor-success-bar span {
        position:absolute;
        top:-20px;
        right:0;
        font-size:12px;
        font-weight:bold;
        color:#333;
    }
    .vendor-more-info {
        display:inline-block;
        margin-top:10px;
        padding:6px 12px;
        background:#3498db;
        color:#fff;
        border-radius:5px;
        text-decoration:none;
        font-size:13px;
        width:max-content;
    }
    .vendor-more-info:hover {
        background:#2980b9;
    }
    
    .vendor-success-container {
    margin-top:6px;
    display:flex;
    flex-direction:column;
    gap:2px;
}

.vendor-success-label {
    font-size:12px;
    font-weight:600;
    color:#555;
}

.vendor-success-bar {
    position:relative;
    height:16px;
    background:#e0e0e0;
    border-radius:8px;
    overflow:hidden;
}

.vendor-success-bar .bar {
    height:100%;
    background: linear-gradient(90deg, #4CAF50, #66BB6A);
}

.vendor-success-bar .vendor-success-text {
    position:absolute;
    right:6px;
    top:0;
    height:100%;
    display:flex;
    align-items:center;
    font-size:12px;
    font-weight:bold;
    color:#fff;
    text-shadow: 0 0 2px rgba(0,0,0,0.5);
}

    
    .vendor-more-info {
    display: inline-block;
    margin-top: 6px;
    padding: 5px 10px; /* حجم مناسب */
    background: linear-gradient(135deg, #3498db, #2980b9); /* تدرج أزرق جذاب */
    color: #fff;
    border-radius: 6px; /* حواف مستديرة أكثر */
    text-decoration: none;
    font-size: 12px;
    text-align: center;
    width: auto;
    max-width: 100%;
    line-height: 1.3;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15); /* ظل خفيف يعطي depth */
    transition: all 0.2s ease-in-out; /* تأثير سلس عند hover */
}

.vendor-more-info span {
    display: block; /* اسم المتجر في سطر جديد */
    font-weight: 600;
}

.vendor-more-info:hover {
    background: linear-gradient(135deg, #2980b9, #1c5d99); /* تدرج أغمق عند hover */
    transform: translateY(-2px); /* رفع خفيف عند hover */
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}


    </style>';
});










add_action('wp_ajax_wf_submit_vendor_review', 'wf_submit_vendor_review');
add_action('wp_ajax_nopriv_wf_submit_vendor_review', 'wf_submit_vendor_review');

function wf_submit_vendor_review() {

    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => __('Login required', 'website-flexi')]);
    }

    if (
        empty($_POST['wf_vendor_review_nonce']) ||
        ! wp_verify_nonce($_POST['wf_vendor_review_nonce'], 'wf_vendor_review')
    ) {
        wp_send_json_error(['message' => __('Security check failed', 'website-flexi')]);
    }

    $user_id   = get_current_user_id();
    $vendor_id = intval($_POST['vendor_id'] ?? 0);
    $rating    = intval($_POST['rating'] ?? 0);
    $comment   = sanitize_textarea_field($_POST['comment'] ?? '');

    if ( ! $vendor_id || $rating < 1 || $rating > 5 || empty($comment) ) {
        wp_send_json_error(['message' => __('Invalid data', 'website-flexi')]);
    }

    // ✅ 1) لازم يكون عنده Order نهائي مع منتجات التاجر
    $allowed_statuses = ['completed', 'refunded', 'failed'];
    $has_valid_order  = false;

    $orders = wc_get_orders([
        'customer_id' => $user_id,
        'status'      => $allowed_statuses,
        'limit'       => -1,
    ]);

    foreach ( $orders as $order ) {
        foreach ( $order->get_items() as $item ) {
            $product_id = $item->get_product_id();
            if ( (int) get_post_field('post_author', $product_id) === (int) $vendor_id ) {
                $has_valid_order = true;
                break 2;
            }
        }
    }

    if ( ! $has_valid_order ) {
        wp_send_json_error(['message' => __('You can only review vendors after a completed (final) order.', 'website-flexi')]);
    }

    // ✅ 2) منع التقييم المتكرر (SQL قوي)
    global $wpdb;

    $already_reviewed = (int) $wpdb->get_var( $wpdb->prepare("
        SELECT COUNT(*)
        FROM {$wpdb->comments} c
        INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
        WHERE c.user_id = %d
          AND c.comment_type = 'vendor_review'
          AND cm.meta_key = 'vendor_id'
          AND cm.meta_value = %d
    ", $user_id, $vendor_id ) );

    if ( $already_reviewed > 0 ) {
        wp_send_json_error(['message' => __('You already reviewed this vendor.', 'website-flexi')]);
    }

    // ✅ 3) حفظ الريفيو
    $comment_id = wp_insert_comment([
        'comment_post_ID' => wf_vendor_reviews_anchor_post_id(),
        'comment_type'    => 'vendor_review',
        'comment_content' => $comment,
        'user_id'         => $user_id,
        'comment_author'  => wp_get_current_user()->display_name,
        'comment_approved'=> 1,
    ]);

    if ( ! $comment_id || is_wp_error($comment_id) ) {
        wp_send_json_error(['message' => __('Failed to save review', 'website-flexi')]);
    }

    add_comment_meta($comment_id, 'vendor_id', $vendor_id, true);
    add_comment_meta($comment_id, 'rating', $rating, true);

    wp_send_json_success(['message' => __('Review submitted', 'website-flexi')]);
}



/* ================================
   Vendor Reply AJAX
================================ */
add_action('wp_ajax_wf_submit_vendor_reply', 'wf_submit_vendor_reply');

function wf_submit_vendor_reply() {

    if (
        empty($_POST['wf_vendor_reply_nonce']) ||
        ! wp_verify_nonce($_POST['wf_vendor_reply_nonce'], 'wf_vendor_reply')
    ) {
        wp_send_json_error(['message' => __('Security check failed', 'website-flexi')]);
        wp_die();
    }

    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => __('Login required', 'website-flexi')]);
        wp_die();
    }

    $review_id = intval($_POST['review_id'] ?? 0);
    $reply     = sanitize_textarea_field($_POST['reply'] ?? '');
    $vendor_id = get_current_user_id();

    if ( ! $review_id || empty($reply) ) {
        wp_send_json_error(['message' => __('Invalid data', 'website-flexi')]);
        wp_die();
    }

    $review_vendor_id = get_comment_meta($review_id, 'vendor_id', true);

    if ( (int) $review_vendor_id !== (int) $vendor_id ) {
        wp_send_json_error(['message' => __('Unauthorized', 'website-flexi')]);
        wp_die();
    }

    /* ✅ منع تكرار الرد على نفس الريفيو فقط */
   global $wpdb;

$existing = $wpdb->get_var( $wpdb->prepare("
    SELECT COUNT(*)
    FROM {$wpdb->commentmeta}
    WHERE meta_key = 'vendor_reply_for_review'
      AND meta_value = %d
", $review_id ) );

if ( $existing > 0 ) {
    wp_send_json_error(['message' => __('Reply already exists', 'website-flexi')]);
}


    $parent_comment = get_comment($review_id);
    if ( ! $parent_comment ) {
        wp_send_json_error(['message' => __('Review not found', 'website-flexi')]);
        wp_die();
    }

   $reply_id = wp_insert_comment([
    'comment_post_ID'  => (int) ( $parent_comment->comment_post_ID ?: wf_vendor_reviews_anchor_post_id() ),
    'comment_parent'   => $review_id,
    'comment_type'     => 'vendor_reply',
    'comment_content'  => $reply,
    'user_id'          => $vendor_id,
    'comment_author'   => wp_get_current_user()->display_name,
    'comment_approved' => 1,
]);

add_comment_meta($reply_id, 'vendor_reply_for_review', $review_id, true);


    if ( ! $reply_id ) {
        wp_send_json_error(['message' => __('Failed to save reply', 'website-flexi')]);
        wp_die();
    }

    /* 🟢 IMPORTANT: Return data JS needs */
    wp_send_json_success([
        'reply_id' => $reply_id,
        'reply'    => wp_kses_post($reply),
        'message'  => __('Reply added successfully', 'website-flexi'),
    ]);

    wp_die(); // ⬅️ دي كانت ناقصة وبتكسر الدنيا
}










function wf_vendor_reviews_anchor_post_id() {
    // الأفضل: صفحة "Shop" أو "My Account" أو "Home"
    $pid = (int) wc_get_page_id('shop');
    if ( $pid > 0 ) return $pid;

    $pid = (int) wc_get_page_id('myaccount');
    if ( $pid > 0 ) return $pid;

    $pid = (int) get_option('page_on_front');
    if ( $pid > 0 ) return $pid;

    return 1; // fallback آمن جدًا
}




add_action('wp_ajax_wf_delete_vendor_reply', 'wf_delete_vendor_reply');

function wf_delete_vendor_reply() {

    if (
        empty($_POST['wf_vendor_reply_nonce']) ||
        ! wp_verify_nonce($_POST['wf_vendor_reply_nonce'], 'wf_vendor_reply')
    ) {
        wp_send_json_error(['message' => 'Security error']);
    }

    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => 'Login required']);
    }

    $reply_id = intval($_POST['reply_id'] ?? 0);
    $reply    = get_comment($reply_id);

    if ( ! $reply || $reply->comment_type !== 'vendor_reply' ) {
        wp_send_json_error(['message' => 'Reply not found']);
    }

    if ( (int) $reply->user_id !== get_current_user_id() ) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    wp_delete_comment($reply_id, true);

    wp_send_json_success();
}





add_action('wp_ajax_wf_edit_vendor_reply', 'wf_edit_vendor_reply');

function wf_edit_vendor_reply() {

    if (
        empty($_POST['wf_vendor_reply_nonce']) ||
        ! wp_verify_nonce($_POST['wf_vendor_reply_nonce'], 'wf_vendor_reply')
    ) {
        wp_send_json_error(['message' => 'Security error']);
    }

    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => 'Login required']);
    }

    $reply_id = intval($_POST['reply_id'] ?? 0);
    $reply    = sanitize_textarea_field($_POST['reply'] ?? '');

    if ( ! $reply_id || empty($reply) ) {
        wp_send_json_error(['message' => 'Invalid data']);
    }

    $comment = get_comment($reply_id);

    if ( ! $comment || $comment->comment_type !== 'vendor_reply' ) {
        wp_send_json_error(['message' => 'Reply not found']);
    }

    if ( (int) $comment->user_id !== get_current_user_id() ) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    wp_update_comment([
        'comment_ID'      => $reply_id,
        'comment_content' => $reply,
    ]);

    wp_send_json_success();
}










add_action('wp_ajax_wf_load_more_reviews', 'wf_load_more_reviews');
add_action('wp_ajax_nopriv_wf_load_more_reviews', 'wf_load_more_reviews');

function wf_load_more_reviews() {
    global $wpdb;

    $vendor_id = intval($_POST['vendor_id'] ?? 0);
    $page      = intval($_POST['page'] ?? 1);
    $per_page  = 5;
    $offset    = ($page - 1) * $per_page;

    if ( ! $vendor_id ) wp_die();

    $reviews = $wpdb->get_results( $wpdb->prepare("
        SELECT c.*
        FROM {$wpdb->comments} c
        INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
        WHERE c.comment_type = 'vendor_review'
          AND c.comment_approved = 1
          AND cm.meta_key = 'vendor_id'
          AND cm.meta_value = %d
        ORDER BY c.comment_date_gmt DESC
        LIMIT %d OFFSET %d
    ", $vendor_id, $per_page, $offset ) );

    if ( ! $reviews ) wp_die();

    foreach ( $reviews as $review ) {
        $rating = (int) get_comment_meta($review->comment_ID, 'rating', true);
        ?>
        <div class="vendor-review-item">
            <div class="vendor-review-header">
                <strong><?php echo esc_html($review->comment_author); ?></strong>
                <div class="stars"><?php echo str_repeat('★', $rating); ?></div>
            </div>
            <div class="review-body">
                <p><?php echo esc_html($review->comment_content); ?></p>
            </div>
        </div>
        <?php
    }

    wp_die();
}




add_action('wp_ajax_wf_report_vendor', 'wf_report_vendor');

function wf_report_vendor() {

    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => 'Login required']);
    }

    if (
        empty($_POST['wf_report_vendor_nonce']) ||
        ! wp_verify_nonce($_POST['wf_report_vendor_nonce'], 'wf_report_vendor')
    ) {
        wp_send_json_error(['message' => 'Security check failed']);
    }



    global $wpdb;

    $vendor_id = intval($_POST['vendor_id'] ?? 0);
    $reasons   = array_map('sanitize_text_field', $_POST['reasons'] ?? []);
    $comment   = sanitize_textarea_field($_POST['comment'] ?? '');
    $user_id   = get_current_user_id();

   if ( ! $vendor_id ) {
    wp_send_json_error(['message' => 'Invalid vendor']);
    }
    
    if ( empty($reasons) ) {
        wp_send_json_error(['message' => 'At least one reason is required']);
    }


    $table = $wpdb->prefix . 'wf_reports';

    // منع التكرار
    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM {$table}
             WHERE report_type = 'vendor'
             AND object_id = %d
             AND reported_by = %d",
            $vendor_id,
            $user_id
        )
    );

    if ( $exists ) {
        wp_send_json_error(['message' => 'Already reported']);
    }

    error_log('WF_VENDOR_REPORT INSERT: ' . print_r([
    'vendor_id' => $vendor_id,
    'user_id'   => $user_id,
    'reasons'   => $reasons,
], true));



    $wpdb->insert(
        $table,
        [
            'report_type' => 'vendor',
            'object_id'   => $vendor_id,
            'reported_by'=> $user_id,
            'reason'      => implode(',', $reasons),
            'comment'     => $comment,
            'status'      => 'pending',
            'created_at'  => current_time('mysql'),
        ]
    );

    wp_send_json_success(['message' => 'Vendor reported successfully']);
}









/****************************                      شرح الكود:

is_admin() → نتأكد إننا ما نؤثرش على لوحة التحكم.

$query->is_main_query() → نتعامل فقط مع الاستعلام الرئيسي للواجهة.

is_post_type_archive('product') و is_tax() و is_search() → نطبق الفلتر فقط على منتجات WooCommerce على المتجر والأرشيف والبحث.

get_users(['role' => 'taj_vendor_pending']) و ['role' => 'taj_vendor_suspended'] → نحصل على الـ vendors اللي حالتهم تمنع ظهور منتجاتهم.

$query->set('author__not_in', $blocked_vendor_ids); → نستبعد كل منتجاتهم من الاستعلام.

✅ النتيجة: أي منتجات من vendor حالة pending أو suspended لن تظهر في الموقع في أي صفحة shop أو archive أو search.                  ******************/





add_action('pre_get_posts', function($query) {

    // نتأكد اننا في الاستعلام الرئيسي للواجهة (frontend main query)
    if (is_admin() || !$query->is_main_query()) return;

    // فقط منتجات WooCommerce
    if (!is_post_type_archive('product') && !is_tax() && !is_search()) return;

    global $wpdb;

    // جلب كل الـ vendor IDs اللي حالتهم pending أو suspended
    $pending_suspended_roles = ['taj_vendor_pending', 'taj_vendor_suspended'];

    $user_ids = $wpdb->get_col("
        SELECT ID FROM {$wpdb->users} u
        INNER JOIN {$wpdb->usermeta} m ON u.ID = m.user_id
        WHERE m.meta_key = '{$wpdb->prefix}capabilities'
    ");

    $blocked_vendor_ids = [];

    foreach ($pending_suspended_roles as $role) {
        $role_users = get_users([
            'role' => $role,
            'fields' => 'ID',
        ]);
        $blocked_vendor_ids = array_merge($blocked_vendor_ids, $role_users);
    }

    if (!empty($blocked_vendor_ids)) {
        $query->set('author__not_in', $blocked_vendor_ids);
    }

});





/*******************************               شرح سريع:

woocommerce_is_purchasable → يمنع المنتج من الإضافة للسلة إذا vendor حالته pending أو suspended.

woocommerce_loop_add_to_cart_link → يعدل زر “Add to Cart” في صفحات الأرشيف والمتجر، ويستبدله برسالة قابلة للترجمة.

woocommerce_single_product_summary → يمنع زر السلة في صفحة المنتج الفردية ويعرض نفس الرسالة.

✅ النتيجة:

أي منتجات vendor محظورة لن تُضاف للسلة.

زر Add to Cart يستبدل برسالة واضحة “This store is still pending / This store is suspended”.               ****************/


/*--------------------------------------------------------------
# Replace Add to Cart with Custom Message for Pending / Suspended Vendor
--------------------------------------------------------------*/
add_filter( 'woocommerce_is_purchasable', function( $purchasable, $product ) {

    if( ! $product instanceof WC_Product ){
        return $purchasable;
    }

    $post = get_post( $product->get_id() );
    $author_id = $post ? $post->post_author : 0;

    if( ! $author_id ){
        return $purchasable;
    }

    $user = get_userdata( $author_id );

    if ( $user ) {

        $roles = (array) $user->roles;

        if (
            in_array('taj_vendor_pending', $roles, true) ||
            in_array('taj_vendor_suspended', $roles, true)
        ) {
            return false;
        }
    }

    return $purchasable;

}, 10, 2 );

/*--------------------------------------------------------------
# Add Custom Colored Message Instead of Add to Cart
--------------------------------------------------------------*/
add_action( 'woocommerce_single_product_summary', function() {

    global $product;

    if( ! $product instanceof WC_Product ){
        return;
    }

    $post = get_post( $product->get_id() );
    $author_id = $post ? $post->post_author : 0;

    if( ! $author_id ){
        return;
    }

    $user = get_userdata( $author_id );

    if ( ! $user ) return;

    $roles = (array) $user->roles;

    if ( in_array('taj_vendor_pending', $roles, true) ) {

        echo '<p class="aswaq-taj-message pending">' .
        esc_html__( 'This product has been temporarily disabled by Aswaq Taj because the store is still under review.', 'website-flexi' ) .
        '</p>';
    }

    if ( in_array('taj_vendor_suspended', $roles, true) ) {

        echo '<p class="aswaq-taj-message suspended">' .
        esc_html__( 'This product is unavailable because the store has been suspended by Aswaq Taj.', 'website-flexi' ) .
        '</p>';
    }

}, 31 );

/*--------------------------------------------------------------
# Add Custom CSS for Messages
--------------------------------------------------------------*/
add_action( 'wp_head', function() {
    echo '<style>
        .aswaq-taj-message {
            font-weight: bold;
            font-size: 16px;
            padding: 12px;
            margin: 15px 0;
            border-radius: 6px;
            text-align: center;
        }
        .aswaq-taj-message.pending {
            background-color: #fff3cd; /* أصفر فاتح */
            color: #856404; /* نص بني داكن */
            border: 1px solid #ffeeba;
        }
        .aswaq-taj-message.suspended {
            background-color: #f8d7da; /* أحمر فاتح */
            color: #721c24; /* نص أحمر داكن */
            border: 1px solid #f5c6cb;
        }
    </style>';
});








/************************ ✅ هذا الكود:

لا يحتاج لتعديل قالب content-product.php

يجعل منتجات البائع Pending/Suspended رمادية و غير قابلة للنقر

يظهر رسالة Hover قابلة للترجمة

يطبق على كل المنتجات في أي لووب WooCommerce      **************/


/*--------------------------------------------------------------
# Disabled Products for Pending/Suspended Vendors
--------------------------------------------------------------*/
add_filter('woocommerce_post_class', function($classes, $product) {

    if (! $product instanceof WC_Product){
        return $classes;
    }

    $post = get_post( $product->get_id() );
    $author_id = $post ? $post->post_author : 0;

    if( ! $author_id ){
        return $classes;
    }

    $user = get_user_by('ID', $author_id);

    if ($user) {

        $roles = (array) $user->roles;

        if ( in_array('taj_vendor_pending', $roles, true) ) {

            $classes[] = 'vendor-product-disabled';
            $classes[] = 'vendor-pending';

        } elseif ( in_array('taj_vendor_suspended', $roles, true) ) {

            $classes[] = 'vendor-product-disabled';
            $classes[] = 'vendor-suspended';
        }
    }

    return $classes;

}, 10, 2);

/*--------------------------------------------------------------
# Add Tooltip and Overlay Badge
--------------------------------------------------------------*/
add_action('woocommerce_shop_loop_item_title', function() {

    global $product;

    if( ! $product instanceof WC_Product ){
        return;
    }

    $post = get_post( $product->get_id() );
    $author_id = $post ? $post->post_author : 0;

    if( ! $author_id ){
        return;
    }

    $user = get_user_by('ID', $author_id);

    if (!$user) return;

    $roles = (array) $user->roles;

    $tooltip = '';
    $badge_text = '';

    if ( in_array('taj_vendor_pending', $roles, true) ) {

        $tooltip = esc_attr__('This store is still pending','website-flexi');
        $badge_text = esc_html__('Pending','website-flexi');

    } elseif ( in_array('taj_vendor_suspended', $roles, true) ) {

        $tooltip = esc_attr__('This store is suspended','website-flexi');
        $badge_text = esc_html__('Suspended','website-flexi');
    }

    if ($tooltip) {

        echo '<span class="vendor-tooltip" data-tooltip="' .
             esc_attr($tooltip) .
             '">' .
             esc_html($badge_text) .
             '</span>';
    }

}, 9);

/*--------------------------------------------------------------
# CSS for Disabled Products
--------------------------------------------------------------*/
add_action('wp_head', function() {
    echo '<style>
    .vendor-product-disabled {
        opacity: 0.5;
        pointer-events: none;
        cursor: default;
        position: relative;
    }
    .vendor-product-disabled a {
        pointer-events: none;
    }

    /* Tooltip on title hover */
    .vendor-product-disabled .woocommerce-loop-product__title::after {
        content: attr(data-tooltip);
        position: absolute;
        top: -1.5em;
        left: 0;
        font-size: 12px;
        color: #fff;
        background: #333;
        padding: 3px 6px;
        border-radius: 4px;
        display: none;
        white-space: nowrap;
        z-index: 99;
    }
    .vendor-product-disabled:hover .woocommerce-loop-product__title::after {
        display: block;
    }

    /* Overlay badge on image */
    .vendor-tooltip {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #D4A017; /* yellow for pending default */
        color: #000;
        padding: 3px 6px;
        font-size: 12px;
        font-weight: bold;
        border-radius: 4px;
        z-index: 10;
    }
    .vendor-product-disabled.vendor-pending .vendor-tooltip {
        background-color: #D4A017; /* dark yellow */
        color: #000;
    }
    .vendor-product-disabled.vendor-suspended .vendor-tooltip {
        background-color: #B22222; /* dark red */
        color: #fff;
    }
    </style>';
});





/* ===========================
   SUPPORT TAB
=========================== */

add_action('init','wf_add_support_endpoint');

function wf_add_support_endpoint(){
  add_rewrite_endpoint('support-desk', EP_ROOT | EP_PAGES);
}


add_filter('woocommerce_account_menu_items','wf_add_support_tab');

function wf_add_support_tab($items){

  if(current_user_can('support_agent')){

    $items['support-desk'] = 'Support Desk';
  }

  return $items;
}










add_filter('woocommerce_product_get_price', 'wf_apply_vendor_markup', 20, 2);
add_filter('woocommerce_product_get_regular_price', 'wf_apply_vendor_markup', 20, 2);
add_filter('woocommerce_product_get_sale_price', 'wf_apply_vendor_markup', 20, 2);
add_filter('woocommerce_product_variation_get_price', 'wf_apply_vendor_markup', 20, 2);
add_filter('woocommerce_product_variation_get_regular_price', 'wf_apply_vendor_markup', 20, 2);
add_filter('woocommerce_product_variation_get_sale_price', 'wf_apply_vendor_markup', 20, 2);

function wf_is_commission_vendor_user( $user_id ) {
    $user_id = intval( $user_id );
    if ( $user_id <= 0 ) {
        return false;
    }

    $user = get_userdata( $user_id );
    if ( ! $user ) {
        return false;
    }

    $roles = (array) $user->roles;

    return (
        in_array( 'taj_vendor', $roles, true )
        || in_array( 'taj_vendor_pending', $roles, true )
        || in_array( 'taj_vendor_suspended', $roles, true )
    );
}

function wf_calculate_markup_price( $price, $type, $value ) {
    if ( $price === '' || $price === null ) {
        return $price;
    }

    $numeric_price = floatval( $price );

    if ( $type === 'percent' ) {
        return $numeric_price + ( ( $numeric_price * $value ) / 100 );
    }

    if ( $type === 'fixed' ) {
        return $numeric_price + $value;
    }

    return $price;
}

function wf_flush_commission_price_cache() {
    if ( function_exists( 'wc_delete_product_transients' ) ) {
        wc_delete_product_transients();
    }

    if ( class_exists( 'WC_Cache_Helper' ) ) {
        WC_Cache_Helper::get_transient_version( 'product', true );
    }
}

add_action( 'update_option_wf_commission_type', function () {
    wf_flush_commission_price_cache();
}, 10, 0 );

add_action( 'update_option_wf_commission_value', function () {
    wf_flush_commission_price_cache();
}, 10, 0 );

function wf_apply_vendor_markup( $price, $product ) {

    if ( is_admin() && ! defined('DOING_AJAX') ) {
        return $price;
    }

    $vendor_id = get_post_field( 'post_author', $product->get_id() );

    // Only for vendor accounts (active / pending / suspended)
    if ( ! wf_is_commission_vendor_user( $vendor_id ) ) {
        return $price;
    }

    $type  = get_option('wf_commission_type','percent');
    $value = floatval(get_option('wf_commission_value',0));

    if ( ! $value ) {
        return $price;
    }

    return wf_calculate_markup_price( $price, $type, $value );
}










add_action('woocommerce_order_status_completed','wf_split_wallet_commission');

function wf_split_wallet_commission( $order_id ) {

    // Prevent duplicate commission
    if ( get_post_meta( $order_id, '_wf_commission_paid', true ) === 'yes' ) {
        return;
    }

    $order = wc_get_order( $order_id );

    if ( ! $order ) return;


    $type  = get_option('wf_commission_type','percent');
    $value = floatval(get_option('wf_commission_value',0));

    foreach ( $order->get_items() as $item ) {

        $product = $item->get_product();
        if ( ! $product ) continue;

        $vendor_id = get_post_field('post_author', $product->get_id());

        if ( ! user_can( $vendor_id, 'taj_vendor' ) ) continue;

        $price = $item->get_subtotal();

        // Calculate commission
        if ( $type === 'percent' ) {

            $commission = ( $price * $value ) / 100;

        } else {

            $commission = $value;
        }

        $vendor_amount = $price;

        // Credit vendor wallet
        if ( function_exists('woo_wallet') ) {

            woo_wallet()->wallet->credit(
                $vendor_id,
                $vendor_amount,
                'Product sale #' . $order_id
            );
        }

       // Get commission receiver (admin wallet)
        $admin_id = intval(
            get_option( 'wf_commission_receiver', get_current_user_id() )
        );
        
        // Fallback if user not exists
        if ( ! get_user_by( 'id', $admin_id ) ) {
            $admin_id = 1;
        }
        
        // Credit admin wallet (commission)
        if ( function_exists( 'woo_wallet' ) ) {
        
            woo_wallet()->wallet->credit(
                $admin_id,
                $commission,
                'Marketplace commission - Order #' . $order_id
            );
        }
    }
    // Mark as paid
    update_post_meta( $order_id, '_wf_commission_paid', 'yes' );
}


















/* =========================================
   Language Helper
========================================= */

function websiteflexi_get_current_lang() {

    // WPML
    if ( defined('ICL_LANGUAGE_CODE') ) {

        $lang = ICL_LANGUAGE_CODE;

    // Polylang
    } elseif ( function_exists('pll_current_language') ) {

        $lang = pll_current_language();

    // WordPress
    } else {

        $locale = get_locale(); // en_US / ar / en_GB

        // خُد أول جزءين
        $lang = $locale;
    }

    // Normalize
    if ( $lang === 'en' || $lang === 'en_GB' ) {
        return 'en_US';
    }

    if ( $lang === 'ar_EG' || $lang === 'ar_SA' ) {
        return 'ar';
    }

    return $lang;
}



/* =========================================
   Multilang Option Helper
========================================= */

if ( ! function_exists('websiteflexi_get_i18n_option') ) {

    function websiteflexi_get_i18n_option( $key, $default = '' ) {

        $lang = websiteflexi_get_current_lang();

        // Try in order
        $try = [
            "{$key}_{$lang}", // current lang
            "{$key}_all",     // fallback
            $key,             // global
        ];

        foreach ( $try as $opt ) {

            $val = get_option( $opt );

            if ( $val !== false && $val !== '' ) {
                return $val;
            }
        }

        return $default;
    }
}

