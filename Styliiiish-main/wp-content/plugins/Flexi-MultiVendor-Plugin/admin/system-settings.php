<?php
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * إضافة صفحة WebsiteFlexi System Settings تحت Plugins
 */
 
 
 
 
 
 
 add_action('admin_enqueue_scripts', function(){

    wp_localize_script('jquery','ajax_object',[
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax_nonce')
    ]);

});






add_action('admin_menu', function () {

    add_submenu_page(
    'plugins.php',
    __('WebsiteFlexi Owner Dashboard & Marketplace', 'website-flexi'),
    __('WebsiteFlexi System Settings', 'website-flexi'),
    'manage_options',
    'websiteflexi-system-settings',
    'websiteflexi_render_system_settings_page'
);


});

add_action('admin_init', function(){

    // هل الفورم اتبعت؟
    if( ! isset($_POST['wf_save_cat_attrs_btn']) ){
        return;
    }

    // تحقق أمان
    if(
        ! isset($_POST['_wpnonce']) ||
        ! wp_verify_nonce($_POST['_wpnonce'], 'wf_save_cat_attrs')
    ){
        wp_die('Security check failed');
    }

    // تحقق صلاحيات
    if( ! current_user_can('manage_options') ){
        wp_die('No permission');
    }

    $cat_id = intval($_POST['wf_cat'] ?? 0);

    if(!$cat_id){
        return;
    }

    // Attributes المختارة
    $attrs = $_POST['wf_attrs'] ?? [];

    // تنظيف
    $attrs = array_map('sanitize_text_field', (array)$attrs);

    // جلب القديم
    $map = get_option('wf_category_attributes_map', []);

    // تحديث
    $map[$cat_id] = $attrs;

    update_option('wf_category_attributes_map', $map);

});







/**
 * معالجة الـ POST + عرض صفحة الإعدادات
 */
function websiteflexi_render_system_settings_page() {

    if ( ! current_user_can('manage_options') ) {
        wp_die( esc_html__('You do not have permission to access this page.', 'website-flexi') );

    }

    // =========================
    // معالجة الفورم: Marketplace
    // =========================
   if ( isset($_POST['wf_save_marketplace_settings']) && check_admin_referer('wf_save_marketplace_settings') ) {

    // Marketplace
    $enabled = isset($_POST['sty_mp_enable_marketplace']) ? 1 : 0;
    update_option( wf_od_option_key_marketplace_enabled(), $enabled );

    // Welcome banner
    update_option(
        'websiteflexi_myaccount_banner',
        isset($_POST['websiteflexi_myaccount_banner']) ? 'yes' : 'no'
    );

    // Disable downloads
    update_option(
        'websiteflexi_disable_downloads',
        isset($_POST['websiteflexi_disable_downloads']) ? 'yes' : 'no'
    );

    // Commission
    update_option(
        'wf_commission_type',
        sanitize_text_field($_POST['wf_commission_type'] ?? '')
    );

    update_option(
        'wf_commission_value',
        floatval($_POST['wf_commission_value'] ?? 0)
    );

    // Receiver
    if ( isset($_POST['wf_commission_receiver']) ) {

        update_option(
            'wf_commission_receiver',
            intval($_POST['wf_commission_receiver'])
        );
    }


    /* =========================
       Language handling
    ========================= */

    $user_id = get_current_user_id();

    $allowed_langs = apply_filters(
        'websiteflexi_allowed_langs',
        ['ar', 'en_US']
    );

    if ( isset($_POST['wf_lang']) && in_array($_POST['wf_lang'], $allowed_langs, true) ) {

        $lang = sanitize_text_field($_POST['wf_lang']);

        update_user_meta( $user_id, '_wf_settings_lang', $lang );

    } else {

        $saved = get_user_meta( $user_id, '_wf_settings_lang', true );

        if ( in_array($saved, $allowed_langs, true) ) {

            $lang = $saved;

        } else {

            $lang = websiteflexi_get_current_lang();
        }
    }

    if ( empty($lang) ) {
        $lang = websiteflexi_get_current_lang();
    }


    /* =========================
       Save texts
    ========================= */

    $fields = [
        'wf_welcome_manager_title',
        'wf_welcome_manager_text',
        'wf_welcome_manager_btn',
        'wf_welcome_user_title',
        'wf_welcome_user_text',
        'wf_welcome_user_btn',
    ];

    foreach ( $fields as $f ) {

        $key = "{$f}_{$lang}";

        if ( isset($_POST[$key]) ) {

            $value = $_POST[$key];

            if ( strpos($f, '_text') !== false ) {

                $value = sanitize_textarea_field($value);

            } else {

                $value = sanitize_text_field($value);
            }

            update_option( $key, $value );
        }
    }


    /* =========================
       Redirect
    ========================= */

    wp_redirect( add_query_arg([
        'page'    => 'websiteflexi-system-settings',
        'wf_msg'  => 'marketplace_saved',
        'tab'     => 'marketplace',
        'wf_lang' => $lang,
    ], admin_url('plugins.php')) );

    exit;
}

    // =========================
    // معالجة الفورم: Add Product Mode
    // =========================
    if ( isset($_POST['styliiiish_save_add_product_mode_btn'])
        && check_admin_referer('styliiiish_save_add_product_mode') ) {

        if ( isset($_POST['styliiiish_add_product_mode']) ) {
            update_option(
                wf_od_option_key_add_product_mode(),
                sanitize_text_field($_POST['styliiiish_add_product_mode'])
            );
        }

        wp_redirect( add_query_arg(array(
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'add_mode_saved',
            'tab'    => 'add_product',
        ), admin_url('plugins.php')) );
        exit;
    }

    // =========================
    // معالجة الفورم: KYC Settings
    // =========================
    if ( isset($_POST['wf_save_kyc_settings']) && check_admin_referer('wf_save_kyc_settings') ) {

        update_option(
            'wf_enable_kyc',
            isset($_POST['wf_enable_kyc']) ? 'yes' : 'no'
        );

        wp_redirect( add_query_arg([
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'kyc_saved',
            'tab'    => 'kyc',
        ], admin_url('plugins.php')) );
        exit;
    }
    
    // =========================
    // Products Layout
    // =========================
    if(
      isset($_POST['wf_save_products_layout_btn']) &&
      check_admin_referer('wf_save_products_layout')
    ){
    
      update_option(
        'wf_products_layout',
        sanitize_text_field($_POST['wf_products_layout'] ?? 'table')
      );
    
      wp_redirect( add_query_arg([
        'page'   => 'websiteflexi-system-settings',
        'wf_msg' => 'layout_saved',
        'tab'    => 'add_product',
      ], admin_url('plugins.php')) );
    
      exit;
    }


    // =========================
    // معالجة الفورم: إضافة Manager
    // =========================
    if ( isset($_POST['styliiiish_add_manager']) && check_admin_referer('styliiiish_add_manager_action') ) {

        $email    = strtolower(trim(sanitize_email($_POST['manager_email'])));
        $password = sanitize_text_field($_POST['manager_password']);

        if ( empty($email) ) {
            wp_die( esc_html__('Email is required.', 'website-flexi') );
        }

        $allowed_ids = wf_od_get_manager_ids();
        $user = get_user_by('email', $email);

        if ( ! $user ) {
            if ( empty($password) ) {
                $password = wp_generate_password(12);
            }

            $user_id = wp_create_user($email, $password, $email);

            if ( is_wp_error($user_id) ) {
               wp_die(
    esc_html__('Error creating user:', 'website-flexi') . ' ' . esc_html($user_id->get_error_message())
);

            }

            wp_update_user(array(
                'ID'   => $user_id,
                'role' => 'shop_manager'
            ));

            $user = get_user_by('ID', $user_id);

            wp_new_user_notification($user_id, null, 'user');
        }

        if ( ! in_array($user->ID, $allowed_ids, true) ) {
            $allowed_ids[] = $user->ID;
            update_option(wf_od_option_key_manager_ids(), array_unique($allowed_ids));
        }

        wp_redirect( add_query_arg(array(
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'manager_added',
            'tab'    => 'managers',
        ), admin_url('plugins.php')) );
        exit;
    }

    // =========================
    // إزالة Manager
    // =========================
    if ( isset($_GET['wf_remove_manager']) ) {

        $remove_id   = intval($_GET['wf_remove_manager']);
        $allowed_ids = wf_od_get_manager_ids();

        $allowed_ids = array_diff($allowed_ids, array($remove_id));
        update_option(wf_od_option_key_manager_ids(), $allowed_ids);

        wp_redirect( add_query_arg(array(
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'manager_removed',
            'tab'    => 'managers',
        ), admin_url('plugins.php')) );
        exit;
    }

    // =========================
    // إضافة Dashboard User
    // =========================
    if ( isset($_POST['styliiiish_add_dashboard_user']) && check_admin_referer('styliiiish_add_dashboard_action') ) {

        $email    = strtolower(trim(sanitize_email($_POST['dashboard_email'])));
        $password = sanitize_text_field($_POST['dashboard_password']);

        if ( empty($email) ) {
           wp_die( esc_html__('Email is required.', 'website-flexi') );
        }

        $access_ids = wf_od_get_dashboard_ids();
        $user = get_user_by('email', $email);

        if ( ! $user ) {
            if ( empty($password) ) {
                $password = wp_generate_password(12);
            }

            $user_id = wp_create_user($email, $password, $email);

            if ( is_wp_error($user_id) ) {
                wp_die(
    esc_html__('Error creating user:', 'website-flexi') . ' ' . esc_html($user_id->get_error_message())
);

            }

            wp_update_user(array(
                'ID'   => $user_id,
                'role' => 'shop_manager'
            ));

            $user = get_user_by('ID', $user_id);

            wp_new_user_notification($user_id, null, 'user');
        }

        if ( ! in_array($user->ID, $access_ids, true) ) {
            $access_ids[] = $user->ID;
            update_option(wf_od_option_key_dashboard_ids(), array_unique($access_ids));
        }

        wp_redirect( add_query_arg(array(
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'dashboard_user_added',
            'tab'    => 'dashboard_access',
        ), admin_url('plugins.php')) );
        exit;
    }

    // =========================
    // إزالة Dashboard User
    // =========================
    if ( isset($_GET['wf_remove_dashboard_user']) ) {

        $remove_id  = intval($_GET['wf_remove_dashboard_user']);
        $access_ids = wf_od_get_dashboard_ids();

        $access_ids = array_diff($access_ids, array($remove_id));
        update_option(wf_od_option_key_dashboard_ids(), $access_ids);

        wp_redirect( add_query_arg(array(
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'dashboard_user_removed',
            'tab'    => 'dashboard_access',
        ), admin_url('plugins.php')) );
        exit;
    }

    // =========================
    // Save Plugin Admin Emails
    // =========================
    if ( isset($_POST['wf_save_admin_emails_btn']) && check_admin_referer('wf_save_admin_emails') ) {

        $selected = isset($_POST['wf_admin_emails']) ? (array) wp_unslash($_POST['wf_admin_emails']) : array();

        $selected_emails = array();
        foreach ( $selected as $email ) {
            $email = strtolower(trim(sanitize_email($email)));
            if ( $email !== '' ) {
                $selected_emails[] = $email;
            }
        }
        $selected_emails = array_values(array_unique($selected_emails));

        // Restrict saved values to existing site user emails only.
        $site_users = get_users(array(
            'fields' => array('user_email'),
            'number' => -1,
        ));

        $site_email_map = array();
        foreach ( $site_users as $u ) {
            $email = strtolower(trim(sanitize_email($u->user_email ?? '')));
            if ( $email !== '' ) {
                $site_email_map[$email] = true;
            }
        }

        $emails = array();
        foreach ( $selected_emails as $email ) {
            if ( isset($site_email_map[$email]) ) {
                $emails[] = $email;
            }
        }

        update_option(wf_od_option_key_admin_emails(), array_values(array_unique($emails)));

        wp_redirect( add_query_arg(array(
            'page'   => 'websiteflexi-system-settings',
            'wf_msg' => 'admins_saved',
            'tab'    => 'admins',
        ), admin_url('plugins.php')) );
        exit;
    }
    
   


    // =========================
    // إعداد البيانات للعرض
    // =========================

    $active_tab           = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'marketplace';
    $marketplace_enabled  = websiteflexi_is_marketplace_enabled();
    $add_product_mode     = wf_od_get_add_product_mode();

    $manager_ids    = wf_od_get_manager_ids();
    $dashboard_ids  = wf_od_get_dashboard_ids();
    $admin_emails   = wf_od_get_admin_emails();

    $admin_email_options = array();
    $users_for_admins = get_users(array(
        'fields'  => array('ID', 'display_name', 'user_email', 'user_login'),
        'orderby' => 'display_name',
        'order'   => 'ASC',
        'number'  => -1,
    ));

    $seen_admin_emails = array();
    foreach ( $users_for_admins as $u ) {
        $email = strtolower(trim(sanitize_email($u->user_email ?? '')));
        if ( $email === '' || isset($seen_admin_emails[$email]) ) {
            continue;
        }

        $display_name = trim((string) ($u->display_name ?? ''));
        $login_name = trim((string) ($u->user_login ?? ''));

        if ( $display_name !== '' ) {
            $label = $display_name . ' - ' . $email;
        } elseif ( $login_name !== '' ) {
            $label = $login_name . ' - ' . $email;
        } else {
            $label = $email;
        }

        $admin_email_options[] = array(
            'email' => $email,
            'label' => $label,
        );

        $seen_admin_emails[$email] = true;
    }

    $manager_users   = wf_od_get_users_from_ids($manager_ids);
    $dashboard_users = wf_od_get_users_from_ids($dashboard_ids);

    $message_key = isset($_GET['wf_msg']) ? sanitize_key($_GET['wf_msg']) : '';

    // تمرير المتغيرات لملف الـ View
    include WF_OWNER_DASHBOARD_PATH . 'admin/system-settings-view.php';
}



















function wf_get_vendors_by_status($status) {

    switch ($status) {

        case 'pending':
            return get_users([
                'role'    => 'taj_vendor_pending',
                'orderby' => 'registered',
                'order'   => 'DESC',
            ]);

        case 'approved':
            return get_users([
                'role'    => 'taj_vendor',
                'orderby' => 'registered',
                'order'   => 'DESC',
            ]);

        case 'suspended':
            return get_users([
                'role'    => 'taj_vendor_suspended',
                'orderby' => 'registered',
                'order'   => 'DESC',
            ]);
    }

    return [];
}












function wf_vendor_action_link($user_id, $action) {

    return wp_nonce_url(
        add_query_arg([
            'page' => 'websiteflexi-system-settings',
            'tab'  => 'vendors',
            'wf_vendor_action' => $action,
            'vendor_id' => (int) $user_id,
        ], admin_url('plugins.php')),
        'wf_vendor_action_' . (int) $user_id
    );
}





add_action('admin_init', function () {

    if (
        ! isset($_REQUEST['wf_vendor_action'], $_REQUEST['vendor_id'], $_REQUEST['_wpnonce']) ||
        ! current_user_can('manage_options')
    ) {
        return;
    }

    $vendor_id = (int) $_REQUEST['vendor_id'];

    if ( ! wp_verify_nonce($_REQUEST['_wpnonce'], 'wf_vendor_action_' . $vendor_id) ) {
        return;
    }

    $action = sanitize_key($_REQUEST['wf_vendor_action']);


    $note   = isset($_REQUEST['admin_note'])
        ? sanitize_textarea_field($_REQUEST['admin_note'])
        : '';

    if ( empty($note) ) {
        wp_die(__('Admin note is required.', 'website-flexi'));
    }

    // === Timeline entry ===
    $timeline = get_user_meta($vendor_id, 'taj_vendor_timeline', true);
    if ( ! is_array($timeline) ) {
        $timeline = [];
    }

    $timeline[] = [
        'status' => $action,
        'note'   => $note,
        'time'   => current_time('mysql'),
        'admin'  => get_current_user_id(),
    ];

    update_user_meta($vendor_id, 'taj_vendor_timeline', $timeline);

    // === Actions ===
    switch ($action) {

        case 'approve':
            (new WP_User($vendor_id))->set_role('taj_vendor');
            update_user_meta($vendor_id, 'taj_kyc_status', 'approved');
            $redirect_tab = 'approved';
            break;

        case 'reject':
            (new WP_User($vendor_id))->set_role('taj_vendor_suspended');
            update_user_meta($vendor_id, 'taj_kyc_status', 'rejected');
            $redirect_tab = 'suspended';
            break;

        case 'suspend':
            (new WP_User($vendor_id))->set_role('taj_vendor_suspended');
            update_user_meta($vendor_id, 'taj_kyc_status', 'suspended');
            $redirect_tab = 'suspended';
            break;

        case 'activate':
            (new WP_User($vendor_id))->set_role('taj_vendor');
            update_user_meta($vendor_id, 'taj_kyc_status', 'approved');
            $redirect_tab = 'approved';
            break;
            
         case 'set_pending':
            (new WP_User($vendor_id))->set_role('taj_vendor_pending');
            update_user_meta($vendor_id, 'taj_kyc_status', 'pending');
            $redirect_tab = 'pending';
            break;    
            
            
        case 'set_customer':
            // إزالة كل أدوار الفيندور
            $user = new WP_User($vendor_id);
            $user->set_role('customer'); // WooCommerce customer
        
            // تحديث الحالة
            update_user_meta($vendor_id, 'taj_kyc_status', 'customer');
        
            // (اختياري) إلغاء التوثيق
            update_user_meta($vendor_id, 'taj_vendor_verified', 'no');
        
            $redirect_tab = 'all';
            break;
    

        default:
            return;
    }

    wp_safe_redirect(
        add_query_arg([
            'page'       => 'websiteflexi-system-settings',
            'tab'        => 'vendors',
            'vendor_tab' => $redirect_tab,
        ], admin_url('plugins.php'))
    );
    exit;
});














function taj_add_vendor_timeline($vendor_id, $status, $note = '') {

    $timeline = get_user_meta($vendor_id, 'taj_vendor_timeline', true);

    if ( ! is_array($timeline) ) {
        $timeline = [];
    }

    $timeline[] = [
        'status' => $status,
        'note'   => $note,
        'time'   => current_time('mysql'),
        'admin'  => get_current_user_id(),
    ];

    update_user_meta($vendor_id, 'taj_vendor_timeline', $timeline);
}






function taj_send_vendor_email( $user_id, $subject, $message ) {

    $user = get_user_by( 'id', $user_id );
    if ( ! $user ) return;

    wp_mail(
        $user->user_email,
        $subject,
        wpautop( $message )
    );
}






add_action('wp_ajax_wf_report_review', 'wf_report_review');

function wf_report_review() {

    global $wpdb;

    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => 'Login required']);
    }

    if (
        empty($_POST['wf_report_review_nonce']) ||
        ! wp_verify_nonce($_POST['wf_report_review_nonce'], 'wf_report_review')
    ) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    $review_id = intval($_POST['review_id'] ?? 0);
    $reason    = sanitize_text_field($_POST['reason'] ?? 'spam');
    $comment   = sanitize_textarea_field($_POST['comment'] ?? '');
    $user_id   = get_current_user_id();

    if ( ! $review_id ) {
        wp_send_json_error(['message' => 'Invalid review']);
    }

    $review = get_comment($review_id);
    if ( ! $review || $review->comment_type !== 'vendor_review' ) {
        wp_send_json_error(['message' => 'Review not found']);
    }

    // 🚫 منع التكرار
    $table = $wpdb->prefix . 'wf_reports';

    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM {$table} 
             WHERE report_type = 'review' 
             AND object_id = %d 
             AND reported_by = %d",
            $review_id,
            $user_id
        )
    );

    if ( $exists ) {
        wp_send_json_error(['message' => 'You already reported this review']);
    }

    // ✅ حفظ البلاغ
    $wpdb->insert(
        $table,
        [
            'report_type' => 'review',
            'object_id'   => $review_id,
            'reported_by'=> $user_id,
            'reason'      => $reason,      // spam / abuse / fake
            'comment'     => $comment,
            'created_at'  => current_time('mysql'),
        ],
        ['%s','%d','%d','%s','%s','%s']
    );

    wp_send_json_success(['message' => 'Review reported successfully']);
}








add_action('admin_post_wf_admin_delete_review', 'wf_admin_delete_review');

function wf_admin_delete_review() {

    if ( ! current_user_can('manage_options') ) {
        wp_die('Unauthorized');
    }

    if (
        empty($_POST['wf_admin_delete_review_nonce']) ||
        ! wp_verify_nonce($_POST['wf_admin_delete_review_nonce'], 'wf_admin_delete_review')
    ) {
        wp_die('Security check failed');
    }

    global $wpdb;

    $review_id = intval($_POST['review_id'] ?? 0);
    $report_id = intval($_POST['report_id'] ?? 0);

    if ( ! $review_id ) {
        wp_die('Invalid review');
    }

    /* ===========================
       1️⃣ Delete ALL replies first
    ============================ */
    $replies = get_comments([
        'parent' => $review_id,
        'status' => 'all',
        'number' => 0,
    ]);

    foreach ( $replies as $reply ) {
        wp_delete_comment($reply->comment_ID, true);
    }

    /* ===========================
       2️⃣ Delete the review itself
    ============================ */
    wp_delete_comment($review_id, true);

    /* ===========================
       3️⃣ Delete report row
    ============================ */
    $table = $wpdb->prefix . 'wf_reports';

    if ( $report_id ) {
        $wpdb->delete(
            $table,
            ['id' => $report_id],
            ['%d']
        );
    }

    /* ===========================
       4️⃣ Redirect back safely
    ============================ */
    wp_redirect( wp_get_referer() );
    exit;
}




add_action('admin_post_wf_mark_report_reviewed', 'wf_mark_report_reviewed');

function wf_mark_report_reviewed() {

    if ( ! current_user_can('manage_options') ) {
        wp_die('Unauthorized');
    }

    if (
        empty($_POST['wf_mark_report_nonce']) ||
        ! wp_verify_nonce($_POST['wf_mark_report_nonce'], 'wf_mark_report')
    ) {
        wp_die('Security check failed');
    }

    global $wpdb;

    $report_id = intval($_POST['report_id'] ?? 0);
    if ( ! $report_id ) {
        wp_die('Invalid report');
    }

    $table = $wpdb->prefix . 'wf_reports';

    $wpdb->update(
        $table,
        ['status' => 'reviewed'],
        ['id' => $report_id],
        ['%s'],
        ['%d']
    );

    wp_redirect( wp_get_referer() );
    exit;
}



add_action('admin_init', 'wf_handle_toggle_vendor_verified');

function wf_handle_toggle_vendor_verified() {

    if (
        empty($_POST['wf_vendor_action']) ||
        $_POST['wf_vendor_action'] !== 'toggle_verified'
    ) {
        return;
    }

    if ( ! current_user_can('manage_options') ) {
        wp_die('Unauthorized');
    }

    $vendor_id = isset($_POST['vendor_id']) ? intval($_POST['vendor_id']) : 0;

    if ( ! $vendor_id ) {
        wp_die('Invalid vendor ID');
    }

    if (
        empty($_POST['_wpnonce']) ||
        ! wp_verify_nonce($_POST['_wpnonce'], 'wf_toggle_verified_' . $vendor_id)
    ) {
        wp_die('Invalid nonce');
    }

    // ✔️ checkbox لو مش checked مش بييجي أصلاً
    $new_status = isset($_POST['verified']) ? 'yes' : 'no';

    update_user_meta($vendor_id, 'taj_vendor_verified', $new_status);

    // رجوع آمن
    wp_safe_redirect( wp_get_referer() ?: admin_url('plugins.php?page=websiteflexi-system-settings&tab=vendors&vendor_tab=all') );
    exit;
}
































add_action('wp_ajax_wf_get_saved_cat_attrs', function(){

    check_ajax_referer('ajax_nonce','nonce');

    $cat = intval($_POST['cat_id']);

    if(!$cat){
        wp_send_json_error();
    }

    $map = get_option('wf_category_attributes_map', []);

    $saved = $map[$cat] ?? [];

    wp_send_json_success($saved);
});
































add_action('init', function(){

    /* ===============================
       Save Category Attributes Map
    =============================== */
    if( isset($_POST['wf_save_cat_attrs_btn']) ){

        if(
            empty($_POST['_wpnonce']) ||
            ! wp_verify_nonce($_POST['_wpnonce'], 'wf_save_cat_attrs')
        ){
            return;
        }

        if( ! current_user_can('manage_options') ){
            return;
        }

        $cat_id = intval($_POST['wf_cat'] ?? 0);

        if($cat_id){

            $attrs = array_map(
                'sanitize_text_field',
                (array) ($_POST['wf_attrs'] ?? [])
            );

            $map = get_option('wf_category_attributes_map', []);

            if(!is_array($map)){
                $map = [];
            }

            $map[$cat_id] = $attrs;

            update_option('wf_category_attributes_map', $map);
        }
    }


    /* ===============================
       Save User Tips
    =============================== */
    if( isset($_POST['wf_save_user_tips_btn']) ){

        if(
            empty($_POST['_wpnonce']) ||
            ! wp_verify_nonce($_POST['_wpnonce'], 'wf_save_user_tips')
        ){
            return;
        }

        if( ! current_user_can('manage_options') ){
            return;
        }

        $msg = wp_kses_post($_POST['wf_user_tips_message'] ?? '');

        update_option('wf_user_tips_message', $msg);
    }


    /* ===============================
       Save Vendor Allowed Categories
    =============================== */
    if( isset($_POST['wf_save_vendor_cats_btn']) ){

        if(
            empty($_POST['_wpnonce']) ||
            ! wp_verify_nonce($_POST['_wpnonce'], 'wf_save_vendor_cats')
        ){
            return;
        }

        if( ! current_user_can('manage_options') ){
            return;
        }

        $cats = array_map('intval', $_POST['wf_vendor_cats'] ?? []);

        update_option('wf_allowed_vendor_categories', $cats);
    }


    /* ===============================
       Save Vendor Add Note
    =============================== */
    if( isset($_POST['wf_save_vendor_note_btn']) ){

        if(
            empty($_POST['wf_vendor_note_nonce']) ||
            ! wp_verify_nonce($_POST['wf_vendor_note_nonce'],'wf_save_vendor_note')
        ){
            return;
        }

        if( ! current_user_can('manage_options') ){
            return;
        }

        $note = wp_kses_post($_POST['wf_vendor_add_note'] ?? '');

        update_option('wf_vendor_add_note', $note);
    }

});




