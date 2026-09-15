<?php
/*
Plugin Name: TaajVendor — Multi-Vendor Marketplace for WordPress
Plugin URI:  https://taajvendor.com
Description: Professional multi-vendor marketplace and vendor dashboard system for WordPress & WooCommerce. Includes vendor onboarding, product management, wallet, analytics, and owner controls.
Version: 1.2.0
Author: Mahmoud (TaajVendor Team)
Author URI: https://taajvendor.com
Text Domain: taajvendor
Domain Path: /languages
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
License: Commercial
*/
if (!defined('ABSPATH')) {
    exit;
}



define('TAAJVENDOR_VERSION', '1.2.0');


add_filter('site_transient_update_plugins', 'taajvendor_check_update');
add_filter('plugins_api', 'taajvendor_plugin_info', 20, 3);


/* =========================
   Check for Updates
========================= */

function taajvendor_check_update($transient){

   if (empty($transient->checked)) {
      return $transient;
   }

   /* Use cached license */
   $license_status = get_transient('tv_license_status');

   if ($license_status !== 'valid') {
      return $transient;
   }

   $remote = wp_remote_get(
      'https://taajvendor.com/api/plugin-update.php',
      ['timeout'=>15]
   );

   if (
      is_wp_error($remote) ||
      wp_remote_retrieve_response_code($remote) != 200
   ){
      return $transient;
   }

   $data = json_decode(wp_remote_retrieve_body($remote));

   if (!$data || empty($data->version)) {
      return $transient;
   }

   if (version_compare(TAAJVENDOR_VERSION, $data->version, '<')) {

      $plugin = plugin_basename(__FILE__);

      $transient->response[$plugin] = (object)[
         'slug'        => 'taajvendor',
         'plugin'      => $plugin,
         'new_version' => $data->version,
         'url'         => 'https://taajvendor.com',
         'package'     => $data->download_url,
      ];
   }

   return $transient;
}


/* =========================
   License Notice
========================= */

add_action('admin_notices', 'taajvendor_show_license_notice');

function taajvendor_show_license_notice(){

   if (get_transient('tv_license_status') !== 'valid') {

      echo '<div class="notice notice-error">
         <p><strong>TaajVendor:</strong> License inactive. Please activate your license.</p>
      </div>';

   }
}


/* =========================
   Plugin Info Popup
========================= */

function taajvendor_plugin_info($res, $action, $args){

   if ($action !== 'plugin_information') return $res;

   if (empty($args->slug) || $args->slug !== 'taajvendor') {
      return $res;
   }

   $remote = wp_remote_get(
      'https://taajvendor.com/api/plugin-update.php',
      ['timeout' => 15]
   );

   if (is_wp_error($remote)) return $res;

   $data = json_decode(wp_remote_retrieve_body($remote), true);

   if (!$data || empty($data['version'])) {
      return $res;
   }

   $info = new stdClass();

   $info->name          = $data['name'];
   $info->slug          = $data['slug'];
   $info->version       = $data['version'];
   $info->author        = '<a href="https://taajvendor.com">TaajVendor</a>';
   $info->homepage      = 'https://taajvendor.com';
   $info->download_link = $data['download_url'];
   $info->requires      = $data['requires'];
   $info->tested        = $data['tested'];
   $info->requires_php  = $data['requires_php'];
   $info->sections      = $data['sections'];

   return $info;
}
















/**
 * Constants
 */
define('WF_OWNER_DASHBOARD_PATH', plugin_dir_path(__FILE__));
define('WF_OWNER_DASHBOARD_URL', plugin_dir_url(__FILE__));







/**
 * Load includes
 */
require_once WF_OWNER_DASHBOARD_PATH . 'includes/helpers.php';
require_once WF_OWNER_DASHBOARD_PATH . 'includes/settings-handler.php';
// Private KYC document storage. Must load before vendor-workflow.php, which uses it.
require_once WF_OWNER_DASHBOARD_PATH . 'includes/kyc-storage.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/vendor-workflow.php';
require_once WF_OWNER_DASHBOARD_PATH . 'includes/vendor-profile-editor.php';



/**
 * Admin Settings
 */
if (is_admin()) {
    require_once WF_OWNER_DASHBOARD_PATH . 'admin/system-settings.php';
}

/**
 * Frontend functional files
 */
require_once WF_OWNER_DASHBOARD_PATH . 'vendor-products.php';
require_once WF_OWNER_DASHBOARD_PATH . 'orders.php';
require_once WF_OWNER_DASHBOARD_PATH . 'stats.php';
require_once WF_OWNER_DASHBOARD_PATH . 'email.php';
require_once WF_OWNER_DASHBOARD_PATH . 'functions.php';
require_once WF_OWNER_DASHBOARD_PATH . 'tracking-order.php';
require_once WF_OWNER_DASHBOARD_PATH . 'vendor-orders/vendor-orders.php';




include WF_OWNER_DASHBOARD_PATH . 'modules/shared/ajax/manage-products-ajax.php';
include WF_OWNER_DASHBOARD_PATH . 'modules/shared/helpers-images.php';
include WF_OWNER_DASHBOARD_PATH . 'modules/shared/helpers-attributes.php';
include WF_OWNER_DASHBOARD_PATH . 'modules/manage-products/manage-products.php';






add_action('init', function () {

    if ( ! is_user_logged_in() ) return;

    require_once plugin_dir_path(__FILE__) . 'vendor-orders/vendor-orders.php';

});
add_action('wp_enqueue_scripts', function () {

    if ( ! is_user_logged_in() ) return;

    if ( ! is_page() ) return; // أو شرط الشورت كود

    wp_enqueue_style(
        'wf-vendor-orders',
        plugin_dir_url(__FILE__) . 'vendor-orders/vendor-orders.css',
        [],
        '1.1'
    );

    wp_enqueue_script(
        'wf-vendor-orders',
        plugin_dir_url(__FILE__) . 'vendor-orders/vendor-orders.js',
        ['jquery'],
        '1.1',
        true
    );

    wp_localize_script('wf-vendor-orders', 'wfVendorOrders', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('wf_vendor_orders'),
        'is_rtl'   => is_rtl(),
    ]);

});









add_action( 'admin_enqueue_scripts', 'websiteflexi_load_admin_assets' );

function websiteflexi_load_admin_assets( $hook ) {

    // شغّل فقط لو الصفحة فيها اسم الإعدادات
    if ( strpos( $hook, 'websiteflexi-system-settings' ) === false ) {
        return;
    }

    $base = WF_OWNER_DASHBOARD_URL;

    // CSS
    wp_enqueue_style(
        'wf-welcome-ui',
        $base . 'admin/assets/admin.css',
        [],
        time()
    );

    // JS
    wp_enqueue_script(
        'wf-welcome-ui',
        $base . 'admin/assets/admin.js',
        ['jquery'],
        time(),
        true
    );
}

















// replace init with __FILE__ finall relase
//register_activation_hook(__FILE__,'wf_install_support_system');
add_action('init','wf_install_support_system');

function wf_install_support_system(){

  // شغّله مرة واحدة فقط
  if(get_option('wf_support_installed')){
    return;
  }

  global $wpdb;

  require_once ABSPATH.'wp-admin/includes/upgrade.php';

  $charset = $wpdb->get_charset_collate();


  /* ===========================
     CHAT TABLE
  =========================== */

  $chat = $wpdb->prefix.'wf_support_chat';

  $chat_sql = "CREATE TABLE $chat (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    order_id BIGINT UNSIGNED NOT NULL,

    sender_id BIGINT UNSIGNED NOT NULL,

    sender_role VARCHAR(30),

    message LONGTEXT NOT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    seen TINYINT(1) DEFAULT 0,

    KEY order_id (order_id),
    KEY sender_id (sender_id),
    KEY seen (seen)

  ) $charset;";

  dbDelta($chat_sql);



  /* ===========================
     ASSIGNMENTS TABLE
  =========================== */

  $assign = $wpdb->prefix.'wf_support_assignments';

  $assign_sql = "CREATE TABLE $assign (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    order_id BIGINT UNSIGNED NOT NULL,

    agent_id BIGINT UNSIGNED NOT NULL,

    assigned_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY order_unique (order_id),
    KEY agent_id (agent_id)

  ) $charset;";

  dbDelta($assign_sql);


  // علّم إنه اتثبت
  update_option('wf_support_installed',1);
}





/* ===========================================
   Load Assets ONLY on owner-dashboard page
=========================================== */
add_action('wp_enqueue_scripts', function () {
    $should_load_assets = is_page('owner-dashboard');

    if (
        ! $should_load_assets
        && function_exists('is_account_page')
        && function_exists('is_wc_endpoint_url')
        && is_account_page()
    ) {
        $should_load_assets = (
            is_wc_endpoint_url('vendor-dashboard')
            || is_wc_endpoint_url('vendor_dashboard')
            || is_wc_endpoint_url('vendor_orders')
            || is_wc_endpoint_url('vendor-orders')
            || is_wc_endpoint_url('store-profile')
            || is_wc_endpoint_url('store_profile')
        );
    }

    if (! $should_load_assets) return;
    
    


    // CSS
    wp_enqueue_style('sty-owner-css', WF_OWNER_DASHBOARD_URL . 'assets/css/owner-style.css', [], time());
    wp_enqueue_style('sty-owner-mobile-css', WF_OWNER_DASHBOARD_URL . 'assets/css/mobile.css', ['sty-owner-css'], time());

    // Select2
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery']);

    // SweetAlert
    wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', ['jquery']);

    // Owner Dashboard JS
    wp_enqueue_script('sty-owner-js', WF_OWNER_DASHBOARD_URL . 'assets/js/owner-dashboard-theme.js',
        ['jquery', 'sweetalert2', 'select2-js'], time(), true);
        
        

    wp_enqueue_media();

    wp_localize_script('sty-owner-js', 'ajax_object', [
        'ajax_url'    => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('styliiiish_nonce'),
        'mode'        => 'owner',
        'old_add_url' => admin_url('admin-post.php?action=styliiiish_new_product'),
        'is_manager'  => ( current_user_can('manage_woocommerce') || wf_od_is_user_plugin_admin() ),
    ]);
});

/**
 * Theme CSS (Shopire + Ekart)
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
});

/**
 * Add Settings link near Deactivate
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {

    $settings_link = '<a href="' . admin_url('plugins.php?page=websiteflexi-system-settings') . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
});



add_action('template_redirect', function () {

    if ( get_query_var('vendor-orders', false) === false ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        wp_die('Unauthorized');
    }

    // تحميل CSS & JS
    wf_vendor_orders_assets();

    // تحميل HTML
    add_action('wp_footer', 'wf_vendor_orders_render_modals');
});


add_action('init', function () {
    add_rewrite_endpoint('vendor-orders', EP_ROOT | EP_PAGES);
});










add_action( 'init', function () {
    load_plugin_textdomain(
        'website-flexi',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
});
add_action( 'init', function () {

    if ( function_exists( 'pll_register_string' ) ) {

        pll_register_string(
            'wf_register_vendor',
            'Register as Vendor',
            'WebsiteFlexi'
        );

        pll_register_string(
            'wf_application_under_review',
            'Application Under Review',
            'WebsiteFlexi'
        );

        pll_register_string(
            'wf_store_suspended',
            'Store Suspended',
            'WebsiteFlexi'
        );

        pll_register_string(
            'wf_my_products',
            'My Products',
            'WebsiteFlexi'
        );

        pll_register_string(
            'wf_wallet',
            'Wallet',
            'WebsiteFlexi'
        );

        pll_register_string(
            'wf_withdraw',
            'Withdraw Earnings',
            'WebsiteFlexi'
        );
    }

});


            /* Vendor rewrite */
            add_action('init', function () {
                add_rewrite_rule('^vendor/([^/]+)/?$', 'index.php?vendor_name=$matches[1]', 'top');
                add_rewrite_tag('%vendor_name%', '([^&]+)');
            });
            

            
            /* WooCommerce context */
            add_filter('woocommerce_is_shop', function ($is_shop) {
                return get_query_var('vendor_name') ? true : $is_shop;
            });
            
            /* Force NOT blog (WoodMart fix) */
            add_action('wp', function () {
                if (get_query_var('vendor_name')) {
                    add_filter('woodmart_is_blog', '__return_false');
                }
            });
            
            /* Title */
            add_filter('woodmart_page_title', function ($title) {
                if ($v = get_query_var('vendor_name')) {
                    $user = get_user_by('login', $v);
                    return $user ? esc_html($user->display_name) : __('Vendor','website-flexi');
                }
                return $title;
            });
            
            /* Breadcrumb */
            add_filter('woodmart_breadcrumbs', function ($items) {
                if (!get_query_var('vendor_name')) return $items;
            
                $vendor = get_user_by('login', get_query_var('vendor_name'));
            
                return [
                    ['title'=>__('Home','website-flexi'),'url'=>home_url('/')],
                    ['title'=>__('Vendors','website-flexi'),'url'=>home_url('/vendors')],
                    ['title'=>$vendor ? $vendor->display_name : __('Vendor','website-flexi'),'url'=>''],
                ];
            });
            
            /* Body class */
            add_filter('body_class', function ($classes) {
                if (get_query_var('vendor_name')) {
                    $classes[] = 'woocommerce';
                    $classes[] = 'woocommerce-page';
                    $classes[] = 'vendor-page';
                }
                return $classes;
            });
            
            
            add_action('pre_get_posts', function ($q) {

                if (
                    ! is_admin() &&
                    $q->is_main_query() &&
                    get_query_var('vendor_name')
                ) {
            
                    // نجيب منتجات فقط
                    $q->set('post_type', 'product');
                    $q->set('post_status', 'publish');
            
                    // ❌ مهم جدًا: مانقلبهاش Shop رسمي
                    $q->is_home    = false;
                    $q->is_page    = false;
                    $q->is_archive = false;
            
                    // نخليها Custom Query
                    $q->is_singular = false;
                }
            });
            
            
            
            /****************************************** ⚠️ ملاحظات مهمة جدًا (مستوى محترف)
🔴 1) SEO

أنت محتاج كمان:

meta description

canonical

schema Vendor / Store          *///////////////////

add_filter('pre_handle_404', function ($preempt, $wp_query) {

    if (get_query_var('vendor_name')) {
        $wp_query->is_404 = false;
        return true; // نوقف WordPress من تفعيل 404
    }

    return $preempt;
}, 10, 2);
add_filter('document_title_parts', function ($title) {

    if (get_query_var('vendor_name')) {

        $vendor = get_user_by('login', get_query_var('vendor_name'));
        if (!$vendor) return $title;

        $site_name = get_bloginfo('name');

        if (function_exists('pll_current_language') && pll_current_language() === 'en') {

            // English: Mando Store - Site Name
            $title['title'] = $vendor->display_name . ' Store';

        } else {

            // Arabic: متجر Mando - Site Name
            $title['title'] = 'متجر ' . $vendor->display_name;

        }

        $title['site'] = $site_name;
    }

    return $title;
});





            
            
            /* Template */

            add_filter('template_include', function ($template) {
                if (get_query_var('vendor_name')) {
                    return WF_OWNER_DASHBOARD_PATH . 'templates/vendor-page.php';
                }
                return $template;
            });

            add_filter('woodmart_is_blog', function ($is_blog) {
                if (get_query_var('vendor_name')) {
                    return false;
                }
                return $is_blog;
            });
            
            add_filter('woodmart_is_shop', function ($is_shop) {
                if (get_query_var('vendor_name')) {
                    return true;
                }
                return $is_shop;
            });

/* ===========================================
   Shortcode: Owner Dashboard
=========================================== */



/******************************* 
 
           🧨 الخلاصة النهائية
الكود ده:

✔ لوحة تحكم Owner / Manager
✔ مبنية على Shortcode
✔ UI واحد وصلاحيات مختلفة
✔ بدون Reload
✔ تعتمد على فانكشنز مشتركة

❗ مشاكل حرجة
المشكلة	الخطورة
AJAX بدون nonce	🔴 عالية
AJAX بدون صلاحيات	🔴 عالية
Inline JS	🟡 متوسطة
Logic في الشورت كود	🟡 قابل للتحسين
🔧 لو حابب

أقدر في الخطوة الجاية:

🔐 أقفل ثغرة الـ AJAX

🧼 أعيد هيكلة الـ roles

🧩 أفصل Owner / User clean

🚀 أحسن الأداء                 
**************************************/
function styliiiish_owner_dashboard_shortcode(){
    if (!is_user_logged_in()) {
        return '<p>' . esc_html__('Please log in to access this page.', 'website-flexi') . '</p>';
    }

    $user = wp_get_current_user();
    $user_id = $user->ID;

    $allowed_dashboard = wf_od_get_dashboard_ids();
    $is_admin = wf_od_is_user_plugin_admin($user_id);
    $is_manager = (in_array($user_id, wf_od_get_manager_ids()) || $is_admin);

    if (!$is_manager && !in_array($user_id, $allowed_dashboard)) {
        return '<p>' . esc_html__('You do not have permission to access this page.', 'website-flexi') . '</p>';
    }

    ob_start();
    ?>

    <div class="owner-dashboard-container" id="sty-page-wrapper">

        <h2 style="margin-bottom:20px;">
            🛍 <?php echo esc_html__('Styliiiish Owner Dashboard', 'website-flexi'); ?>
        </h2>

        <!-- CARDS -->
        <div class="owner-card" data-owner-section="products" role="button" tabindex="0">
            <h3>🛍 <?php echo esc_html__('Manage Products', 'website-flexi'); ?> <span>→</span></h3>
        </div>

        <?php if ($is_manager): ?>
            <div class="owner-card" data-owner-section="vendor_products" role="button" tabindex="0">
                <h3>👗 <?php echo esc_html__('Customer Dresses Added', 'website-flexi'); ?> <span>→</span></h3>
            </div>

            <div class="owner-card" data-owner-section="orders" role="button" tabindex="0">
                <h3>📦 <?php echo esc_html__('Orders', 'website-flexi'); ?> <span>→</span></h3>
            </div>

            <div class="owner-card" data-owner-section="stats" role="button" tabindex="0">
                <h3>📊 <?php echo esc_html__('Statistics', 'website-flexi'); ?> <span>→</span></h3>
            </div>

            <div class="owner-card" data-owner-section="email" role="button" tabindex="0">
                <h3>✉️ <?php echo esc_html__('Send Email', 'website-flexi'); ?> <span>→</span></h3>
            </div>
        <?php endif; ?>

        <!-- SECTIONS -->
        <div id="section-products" class="owner-section" style="display:none;">
            <h3>🛍 <?php echo esc_html__('Manage Products', 'website-flexi'); ?></h3>
            <?php styliiiish_render_manage_products($is_manager); ?>
        </div>

        <?php if ($is_manager): ?>
            <div id="section-vendor_products" class="owner-section" style="display:none;">
                <h3>👗 <?php echo esc_html__('Customer Dresses Added', 'website-flexi'); ?></h3>
                <?php styliiiish_render_vendor_products(); ?>
            </div>

            <div id="section-orders" class="owner-section" style="display:none;">
                <h3>📦 <?php echo esc_html__('Orders', 'website-flexi'); ?></h3>
                <?php styliiiish_render_orders(); ?>
            </div>

            <div id="section-stats" class="owner-section" style="display:none;">
                <h3>📊 <?php echo esc_html__('Statistics', 'website-flexi'); ?></h3>
                <?php styliiiish_render_stats(); ?>
            </div>

            <div id="section-email" class="owner-section" style="display:none;">
                <h3>✉️ <?php echo esc_html__('Send Email', 'website-flexi'); ?></h3>
                <?php styliiiish_render_email_sender(); ?>
            </div>
        <?php endif; ?>

    </div>

    <script>
        (function(){
            window.showSection = function(section) {
                document.querySelectorAll('.owner-section').forEach(function(sec){ sec.style.display = 'none'; });
                var target = document.getElementById('section-' + section);
                if (!target) return;
                target.style.display = 'block';

                if (window.scrollTo) {
                    window.scrollTo({top: 300, behavior: 'smooth'});
                }
            };

            var bindCard = function(card){
                if (!card || card.dataset.wfBound === '1') return;
                card.dataset.wfBound = '1';

                card.addEventListener('click', function(){
                    var section = card.getAttribute('data-owner-section');
                    if (section) window.showSection(section);
                });

                card.addEventListener('keydown', function(e){
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        var section = card.getAttribute('data-owner-section');
                        if (section) window.showSection(section);
                    }
                });
            };

            document.querySelectorAll('.owner-card[data-owner-section]').forEach(bindCard);

            var anyVisible = Array.prototype.some.call(
                document.querySelectorAll('.owner-section'),
                function(sec){ return sec.style.display && sec.style.display !== 'none'; }
            );
            if (!anyVisible) {
                window.showSection('products');
            }
        })();
    </script>

    <?php
    return ob_get_clean();
        }
        add_shortcode('owner_dashboard', 'styliiiish_owner_dashboard_shortcode');

function wf_plugin_notranslate_shortcode_tags() {
    return [
        'owner_dashboard',
        'styliiiish_user_manage_products',
        'user_products_dashboard',
        'websiteflexi_vendor_orders',
    ];
}

add_filter('do_shortcode_tag', function ($output, $tag) {
    if (!in_array($tag, wf_plugin_notranslate_shortcode_tags(), true)) {
        return $output;
    }

    return '<div class="wf-plugin-notranslate notranslate trp-no-translate" translate="no" data-no-translation="1">' . $output . '</div>';
}, 10, 2);

add_filter('trp_no_translate_selectors', function ($selectors) {
    if (!is_array($selectors)) {
        $selectors = [];
    }

    $selectors = array_merge($selectors, [
        '.wf-plugin-notranslate',
        '.owner-dashboard-container',
        '.owner-section',
        '.sty-vendor-review-wrapper',
        '.sty-vendor-list',
        '.sty-vendor-card',
        '.sty-vendor-card-body',
        '.sty-vendor-actions',
        '.sty-vendor-meta',
        '.sty-vendor-source',
        '.taj-vendor-dashboard',
    ]);

    return array_values(array_unique($selectors));
});

add_action('wp_footer', function () {
    ?>
    <script id="wf-plugin-notranslate-guard">
    (function(){
        var selectors = [
            '.wf-plugin-notranslate',
            '.owner-dashboard-container',
            '.owner-section',
            '.sty-vendor-review-wrapper',
            '.sty-vendor-list',
            '.sty-vendor-card',
            '.sty-vendor-card-body',
            '.taj-vendor-dashboard'
        ];

        var mark = function(node){
            if(!node || node.nodeType !== 1){ return; }
            node.setAttribute('translate','no');
            node.classList.add('notranslate','trp-no-translate');
        };

        selectors.forEach(function(selector){
            document.querySelectorAll(selector).forEach(mark);
        });
    })();
    </script>
    <?php
}, 999);
        

        
        add_action('wp_ajax_styliiiish_inline_update_status', function () {
                    // Nonce + capability checks
                    check_ajax_referer('styliiiish_nonce', 'nonce');

                    $product_id = intval($_POST['product_id']);
                    $status     = sanitize_text_field($_POST['status']);

                    if (! $product_id) {
                        wp_send_json_error('invalid_product');
                    }

                    // Allow managers/admins or the product author to update status
                    $current = wp_get_current_user();
                    $post = get_post($product_id);
                    if ( ! $post ) {
                        wp_send_json_error('invalid_product');
                    }

                    if ( ! wf_od_is_user_plugin_admin() && ! current_user_can('manage_woocommerce') && intval($post->post_author) !== intval($current->ID) && ! current_user_can('manage_options') ) {
                        wp_send_json_error('unauthorized');
                    }

                    wp_update_post([
                        'ID' => $product_id,
                        'post_status' => $status
                    ]);

                    wp_send_json_success('ok');
        });
        
        
        
        
        /**
         * AJAX Inline Editing (Price / Stock)
         */
        add_action('wp_ajax_styliiiish_inline_update', function () {
                    // Nonce + capability checks
                    check_ajax_referer('styliiiish_nonce', 'nonce');

                    $product_id = intval($_POST['product_id']);
                    $field      = sanitize_text_field($_POST['field']);
                    $value      = sanitize_text_field($_POST['value']);

                    if (! $product_id) {
                        wp_send_json_error('invalid_product');
                    }

                    $current = wp_get_current_user();
                    $post = get_post($product_id);
                    if ( ! $post ) {
                        wp_send_json_error('invalid_product');
                    }

                    if ( ! wf_od_is_user_plugin_admin() && ! current_user_can('manage_woocommerce') && intval($post->post_author) !== intval($current->ID) && ! current_user_can('manage_options') ) {
                        wp_send_json_error('unauthorized');
                    }

                    if ($field === 'price') {
                        update_post_meta($product_id, '_regular_price', $value);
                        update_post_meta($product_id, '_price', $value);
                    }

                    if ($field === 'stock') {
                        update_post_meta($product_id, '_stock', $value);
                        update_post_meta($product_id, '_manage_stock', 'yes');
                    }

                    wp_send_json_success('ok');
                });





register_activation_hook(__FILE__, 'wf_create_reports_table');

function wf_create_reports_table() {
    global $wpdb;

    $table = $wpdb->prefix . 'wf_reports';
    $charset = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "CREATE TABLE {$table} (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        report_type VARCHAR(20) NOT NULL,
        object_id BIGINT UNSIGNED NOT NULL,
        reported_by BIGINT UNSIGNED NOT NULL,
        reason VARCHAR(20) NOT NULL,
        comment TEXT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY report_type (report_type),
        KEY object_id (object_id),
        KEY reported_by (reported_by)
    ) {$charset};";

    dbDelta($sql);
}









add_action('admin_menu', 'tv_add_license_menu');

function tv_add_license_menu(){
   add_options_page(
      'TaajVendor License',
      'TaajVendor License',
      'manage_options',
      'taajvendor-license',
      'tv_render_license_page'
   );
}



function tv_render_license_page(){

   if (isset($_POST['tv_save_license'])) {

   check_admin_referer('tv_license_nonce');

   update_option(
      'tv_license_key',
      sanitize_text_field($_POST['tv_license'])
   );

   // Force recheck
   delete_transient('tv_license_status');
   delete_transient('tv_license_checked');

   tv_verify_license(); // ← أهم سطر
}


   $license = get_option('tv_license_key','');
   $status  = get_transient('tv_license_status');
   ?>

   <div class="wrap">
      <h1>TaajVendor License</h1>

      <form method="post">
         <?php wp_nonce_field('tv_license_nonce'); ?>

         <table class="form-table">
            <tr>
               <th>License Key</th>
               <td>
                  <input type="text"
                     name="tv_license"
                     value="<?php echo esc_attr($license); ?>"
                     style="width:420px"
                     placeholder="TV-XXXX-XXXX-XXXX">
               </td>
            </tr>
         </table>

         <p>
            <button class="button button-primary" name="tv_save_license">
               Save & Activate
            </button>
         </p>
      </form>

      <?php if ($status): ?>
         <p><strong>Status:</strong>
            <?php echo tv_format_license_status($status); ?>
         </p>
      <?php endif; ?>
   </div>
<?php
}



function tv_format_license_status($status){

   switch ($status) {
      case 'valid': return '<span style="color:green">Active</span>';
      case 'expired': return '<span style="color:orange">Expired</span>';
      case 'inactive': return '<span style="color:red">Inactive</span>';
      case 'invalid_domain': return '<span style="color:red">Used on another site</span>';
      default: return '<span style="color:red">Invalid</span>';
   }
}



function tv_verify_license(){

   $key = get_option('tv_license_key');
   if (!$key) return false;

   $res = wp_remote_post(
      'https://taajvendor.com/wp-admin/admin-ajax.php',
      [
         'timeout' => 15,
         'body' => [
            'action'  => 'tv_check_license',
            'license' => $key,
            'domain'  => home_url()
         ]
      ]
   );

   if (is_wp_error($res)) return false;

   $data = json_decode(wp_remote_retrieve_body($res), true);

   if (!is_array($data) || empty($data['status'])) {
      return false;
   }

   set_transient(
      'tv_license_status',
      $data['status'],
      DAY_IN_SECONDS
   );

   return ($data['status'] === 'valid');
}

add_action('wp_ajax_tv_check_license', function () {
    // Verify nonce and capability
    check_ajax_referer('tv_check_license_nonce', 'nonce');

    if (! current_user_can('manage_options')) {
         wp_send_json_error(['error' => 'unauthorized']);
    }

    $license = isset($_POST['license']) ? sanitize_text_field($_POST['license']) : '';
    $domain  = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : '';

    $status = tv_remote_license_check($license, $domain);

    wp_send_json(['status'=>$status]);
});



function tv_remote_license_check($license, $domain){

   $res = wp_remote_post(
      'https://taajvendor.com/wp-admin/admin-ajax.php',
      [
         'timeout'=>15,
         'body'=>[
            'action' =>'tv_check_license',
            'license'=>$license,
            'domain' =>$domain
         ]
      ]
   );

   if (is_wp_error($res)) {
      return 'error';
   }

   $data = json_decode(wp_remote_retrieve_body($res), true);

   if (!is_array($data) || empty($data['status'])) {
      return 'invalid';
   }

   return $data['status'];
}


add_action('admin_init','tv_auto_check_license');

function tv_auto_check_license(){

   if (get_transient('tv_license_checked')) return;

   tv_verify_license();

   set_transient('tv_license_checked', 1, DAY_IN_SECONDS);
}



