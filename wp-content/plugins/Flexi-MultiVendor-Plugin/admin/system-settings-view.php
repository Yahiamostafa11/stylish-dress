<?php
if ( ! defined('ABSPATH') ) {
    exit;
}








?>







<div class="wrap">
    <h1><?php esc_html_e('WebsiteFlexi Owner Dashboard & Marketplace – Settings', 'website-flexi'); ?></h1>

    <?php if ( $message_key ): ?>
        <div class="notice notice-success is-dismissible" style="margin-top:15px;">
            <p>
                <?php
                switch ($message_key) {
                    case 'marketplace_saved':
                        echo esc_html__('Marketplace settings saved successfully.', 'website-flexi');
                        break;
                    case 'add_mode_saved':
                        echo esc_html__('Add Product Mode saved successfully.', 'website-flexi');
                        break;
                    case 'manager_added':
                        echo esc_html__('Manager added successfully.', 'website-flexi');
                        break;
                    case 'manager_removed':
                        echo esc_html__('Manager removed successfully.', 'website-flexi');
                        break;
                    case 'dashboard_user_added':
                        echo esc_html__('Dashboard user added successfully.', 'website-flexi');
                        break;
                    case 'dashboard_user_removed':
                        echo esc_html__('Dashboard user removed successfully.', 'website-flexi');
                        break;
                    case 'kyc_saved':
                        echo esc_html__('KYC settings saved successfully.', 'website-flexi');
                        break;
                    case 'admins_saved':
                        echo esc_html__('Plugin admin emails saved successfully.', 'website-flexi');
                        break;
                    default:
                        echo esc_html__('Settings saved.', 'website-flexi');
                        break;
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <h2 class="nav-tab-wrapper" style="margin-top:20px;">
        <a href="<?php echo esc_url( add_query_arg( array('page' => 'websiteflexi-system-settings', 'tab' => 'marketplace'), admin_url('plugins.php') ) ); ?>"
           class="nav-tab <?php echo ($active_tab === 'marketplace') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Marketplace', 'website-flexi'); ?>
        </a>

        <a href="<?php echo esc_url( add_query_arg( array('page' => 'websiteflexi-system-settings', 'tab' => 'add_product'), admin_url('plugins.php') ) ); ?>"
           class="nav-tab <?php echo ($active_tab === 'add_product') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Add Product', 'website-flexi'); ?>
        </a>
        
        
        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'vendor-orders'), admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'vendor-orders') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Vendor Orders', 'website-flexi'); ?>
        </a>
        
        
        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab' => 'vendors'),admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'vendors') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Vendors', 'website-flexi'); ?>
        </a>

        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'reviews'),admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'reviews') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Reviews', 'website-flexi'); ?>
        </a>
        
        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'reports'), admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'reports') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Reports', 'website-flexi'); ?>
        </a>

        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'license'), admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'license') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('License', 'website-flexi'); ?>
        </a>

        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'pending_products'), admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'pending_products') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Pending Products', 'website-flexi'); ?>
        </a>

        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'kyc'), admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'kyc') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('KYC', 'website-flexi'); ?>
        </a>

        <a href="<?php echo esc_url( add_query_arg(array('page' => 'websiteflexi-system-settings', 'tab'  => 'admins'), admin_url('plugins.php'))); ?>"
        class="nav-tab <?php echo ($active_tab === 'admins') ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e('Admins', 'website-flexi'); ?>
        </a>

        <!-- Future tabs here -->
         
        
        
    </h2>




















    <?php if ($active_tab === 'kyc'): ?>

        <?php $kyc_enabled = get_option('wf_enable_kyc', 'yes') === 'yes'; ?>

        <form method="post" style="margin-top:20px;max-width:920px;">
            <?php wp_nonce_field('wf_save_kyc_settings'); ?>

            <div class="wf-card">
                <h3>🪪 KYC Verification</h3>

                <label class="wf-toggle">
                    <input type="checkbox"
                           name="wf_enable_kyc"
                           value="yes"
                           <?php checked($kyc_enabled); ?>>
                    <span></span>
                    <?php esc_html_e('Enable vendor KYC verification before approval', 'website-flexi'); ?>
                </label>

                <p class="wf-desc">
                    <?php esc_html_e('When disabled, users can become vendors directly without KYC review.', 'website-flexi'); ?>
                </p>
            </div>

            <p class="submit">
                <button type="submit" name="wf_save_kyc_settings" class="button button-primary">
                    <?php esc_html_e('Save KYC Settings', 'website-flexi'); ?>
                </button>
            </p>
        </form>

    <?php endif; ?>

    <?php if ($active_tab === 'admins'): ?>

        <?php
        $saved_admin_emails = isset($admin_emails) && is_array($admin_emails) ? $admin_emails : array();
        $admin_email_options_list = isset($admin_email_options) && is_array($admin_email_options) ? $admin_email_options : array();
        ?>

        <form method="post" style="margin-top:20px;max-width:920px;">
            <?php wp_nonce_field('wf_save_admin_emails'); ?>

            <div class="wf-card">
                <h3>Plugin Admins</h3>

                <p class="wf-desc">
                    <?php esc_html_e('Users with these emails are treated as admins inside this plugin.', 'website-flexi'); ?>
                </p>

                <label for="wf-admin-email-search" style="display:block;font-weight:600;margin-bottom:8px;">
                    <?php esc_html_e('Search & Select Admin Emails', 'website-flexi'); ?>
                </label>

                <input
                    type="search"
                    id="wf-admin-email-search"
                    class="regular-text"
                    style="width:100%;max-width:800px;"
                    placeholder="<?php echo esc_attr__('Search by name or email...', 'website-flexi'); ?>">

                <div id="wf-admin-email-picker" class="wf-admin-email-picker" style="margin-top:10px;max-width:800px;">
                    <?php foreach ( $admin_email_options_list as $opt ): ?>
                        <?php
                        $email = strtolower(trim((string) ($opt['email'] ?? '')));
                        $label = (string) ($opt['label'] ?? $email);
                        if ( $email === '' ) {
                            continue;
                        }
                        $search_text = strtolower($label . ' ' . $email);
                        ?>
                        <label class="wf-admin-email-item" data-search="<?php echo esc_attr($search_text); ?>">
                            <input
                                type="checkbox"
                                name="wf_admin_emails[]"
                                value="<?php echo esc_attr($email); ?>"
                                <?php checked(in_array($email, $saved_admin_emails, true)); ?>>
                            <span><?php echo esc_html($label); ?></span>
                        </label>
                    <?php endforeach; ?>
                    <p id="wf-admin-email-empty-filter" class="description" style="display:none;margin:8px 0 0 0;">
                        <?php esc_html_e('No emails match your search.', 'website-flexi'); ?>
                    </p>
                    <?php if ( empty($admin_email_options_list) ): ?>
                        <p class="description" style="margin:8px 0 0 0;">
                            <?php esc_html_e('No site user emails available yet.', 'website-flexi'); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <p class="description">
                    <?php esc_html_e('Select one or more emails from existing site users.', 'website-flexi'); ?>
                </p>

                <div class="wf-selected-admins-wrap" style="margin-top:14px;max-width:800px;">
                    <h4 style="margin:0 0 8px 0;">
                        <?php esc_html_e('Selected Admin Emails', 'website-flexi'); ?>
                    </h4>
                    <ul id="wf-selected-admin-emails" style="margin:0;padding:0;list-style:none;display:flex;flex-wrap:wrap;gap:8px;"></ul>
                </div>
            </div>

            <p class="submit">
                <button type="submit" name="wf_save_admin_emails_btn" class="button button-primary">
                    <?php esc_html_e('Save Admin Emails', 'website-flexi'); ?>
                </button>
            </p>
        </form>

        <style>
            .wf-admin-email-picker {
                border: 1px solid #dcdcde;
                border-radius: 8px;
                background: #fff;
                max-height: 260px;
                overflow: auto;
                padding: 8px;
            }
            .wf-admin-email-item {
                display: flex;
                align-items: center;
                gap: 8px;
                border-radius: 6px;
                padding: 8px;
                margin: 2px 0;
            }
            .wf-admin-email-item:hover {
                background: #f6f7f7;
            }
            .wf-selected-admins-wrap li {
                background: #f0f4ff;
                border: 1px solid #d8e2ff;
                border-radius: 16px;
                color: #1f3b7a;
                font-size: 12px;
                line-height: 1;
                padding: 8px 10px;
            }
            .wf-selected-admins-wrap li.wf-empty {
                background: #f8f9fb;
                border-color: #e5e7ef;
                color: #6b7280;
            }
        </style>

        <script>
            (function(){
                function renderSelectedEmails() {
                    var list = document.getElementById('wf-selected-admin-emails');
                    if (!list) {
                        return;
                    }

                    var checked = document.querySelectorAll('#wf-admin-email-picker input[name="wf_admin_emails[]"]:checked');
                    var html = '';

                    if (!checked.length) {
                        html = '<li class="wf-empty"><?php echo esc_js(__('No admin emails selected yet.', 'website-flexi')); ?></li>';
                        list.innerHTML = html;
                        return;
                    }

                    checked.forEach(function(input){
                        var email = input.value || '';
                        var safeEmail = email
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                        html += '<li>' + safeEmail + '</li>';
                    });

                    list.innerHTML = html;
                }

                function applySearchFilter() {
                    var search = document.getElementById('wf-admin-email-search');
                    var picker = document.getElementById('wf-admin-email-picker');
                    var empty = document.getElementById('wf-admin-email-empty-filter');

                    if (!search || !picker) {
                        return;
                    }

                    var query = (search.value || '').toLowerCase().trim();
                    var items = picker.querySelectorAll('.wf-admin-email-item');
                    var visibleCount = 0;

                    items.forEach(function(item){
                        var haystack = (item.getAttribute('data-search') || '').toLowerCase();
                        var visible = !query || haystack.indexOf(query) !== -1;
                        item.style.display = visible ? 'flex' : 'none';
                        if (visible) {
                            visibleCount += 1;
                        }
                    });

                    if (empty) {
                        empty.style.display = visibleCount ? 'none' : 'block';
                    }
                }

                document.addEventListener('DOMContentLoaded', function(){
                    var search = document.getElementById('wf-admin-email-search');
                    var picker = document.getElementById('wf-admin-email-picker');

                    if (!picker) {
                        return;
                    }

                    if (search) {
                        search.addEventListener('input', applySearchFilter);
                    }

                    picker.addEventListener('change', function(e){
                        if (e.target && e.target.name === 'wf_admin_emails[]') {
                            renderSelectedEmails();
                        }
                    });

                    applySearchFilter();
                    renderSelectedEmails();
                });
            })();
        </script>

    <?php endif; ?>


    <?php if ($active_tab === 'license'): ?>

        <?php
        // Handle license save from this settings page
        if ( isset($_POST['tv_save_license_from_settings']) ) {
            check_admin_referer('tv_license_nonce');

            update_option('tv_license_key', sanitize_text_field($_POST['tv_license']));

            // Force recheck
            delete_transient('tv_license_status');
            delete_transient('tv_license_checked');

            // Run verify (uses remote check and sets transient)
            if ( function_exists('tv_verify_license') ) {
                tv_verify_license();
            }

            echo '<div class="notice notice-success is-dismissible" style="margin-top:15px;"><p>' . esc_html__('License saved and verification requested.', 'website-flexi') . '</p></div>';
        }

        $wf_license_key = get_option('tv_license_key', '');
        $wf_license_status = get_transient('tv_license_status');
        $wf_license_checked = get_transient('tv_license_checked') ? true : false;
        ?>

        <div style="margin-top:20px;">
            <div class="wf-card wf-license-card">
                <h3>🔐 TaajVendor License</h3>

                <p class="wf-desc"><?php esc_html_e('Enter your license key to activate plugin updates and support.', 'website-flexi'); ?></p>

                <form method="post" id="wf-license-form">
                    <?php wp_nonce_field('tv_license_nonce'); ?>

                    <div class="wf-field" style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                        <input type="text" name="tv_license" value="<?php echo esc_attr($wf_license_key); ?>" style="width:420px;padding:8px;border:1px solid #ddd;border-radius:4px;">

                        <button class="button button-primary" name="tv_save_license_from_settings" style="height:38px;padding:0 14px;"><?php esc_html_e('Save & Activate', 'website-flexi'); ?></button>

                        <button type="button" id="wf-check-license-btn" class="button" style="height:38px;padding:0 14px;"><?php esc_html_e('Check Now', 'website-flexi'); ?></button>
                    </div>
                </form>

                <div id="wf-license-status" style="margin-top:14px;">
                    <strong><?php esc_html_e('Status:', 'website-flexi'); ?></strong>
                    <span class="wf-license-badge"><?php echo tv_format_license_status($wf_license_status); ?></span>
                    <?php if ( $wf_license_checked ): ?>
                        <span style="margin-left:10px;color:#666;"><?php esc_html_e('Checked recently', 'website-flexi'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <style>
            .wf-license-card { max-width:920px; }
            .wf-license-badge span, .wf-license-badge { font-weight:600; }
            .wf-license-card .wf-desc { margin-bottom:10px; }
        </style>

        <script>
            (function($){
                function formatStatusLabel(s){
                    switch(s){
                        case 'valid': return '<span style="color:green">Active</span>';
                        case 'expired': return '<span style="color:orange">Expired</span>';
                        case 'inactive': return '<span style="color:red">Inactive</span>';
                        case 'invalid_domain': return '<span style="color:red">Used on another site</span>';
                        default: return '<span style="color:red">Invalid</span>';
                    }
                }

                $(document).ready(function(){
                    $('#wf-check-license-btn').on('click', function(){
                        var btn = $(this);
                        var key = $('input[name="tv_license"]').val();

                        btn.prop('disabled', true).text('<?php echo esc_js( 'Checking...' ); ?>');

                        $.post(ajaxurl, { action: 'tv_check_license', license: key, domain: '<?php echo esc_js( home_url() ); ?>', nonce: '<?php echo wp_create_nonce('tv_check_license_nonce'); ?>' }, function(res){
                            if (res && res.status){
                                $('#wf-license-status .wf-license-badge').html(formatStatusLabel(res.status));
                            } else {
                                $('#wf-license-status .wf-license-badge').html('<span style="color:red">Error</span>');
                            }
                            btn.prop('disabled', false).text('<?php echo esc_js( 'Check Now' ); ?>');
                        }, 'json').fail(function(){
                            $('#wf-license-status .wf-license-badge').html('<span style="color:red">Error</span>');
                            btn.prop('disabled', false).text('<?php echo esc_js( 'Check Now' ); ?>');
                        });
                    });
                });
            })(jQuery);
        </script>

    <?php endif; ?>

    <?php if ($active_tab === 'pending_products'): ?>

        <?php // Admin review panel for pending vendor products ?>
        <div style="margin-top:20px;">
            <div class="wf-card">
                <h3>📋 Pending Products Review</h3>
                <p class="wf-desc">Review products submitted by customers. Approve to publish or reject and provide a reason which will be saved and emailed to the vendor.</p>

                <div id="wf-pending-products" style="margin-top:12px;">
                    <div class="wf-loading">Loading pending products…</div>
                </div>

            </div>
        </div>

        <script>
            (function($){
                var adminNonce = '<?php echo esc_js( wp_create_nonce('ajax_nonce') ); ?>';

                function loadPendingProducts(){
                    $('#wf-pending-products').html('<div class="wf-loading">Loading pending products…</div>');
                    $.post(ajaxurl, {
                        action: 'sty_filter_vendor_products',
                        status: 'pending',
                        nonce: adminNonce
                    }, function(res){
                        if (res && res.success) {
                            $('#wf-pending-products').html(res.html);
                        } else {
                            $('#wf-pending-products').html('<div class="wf-error">Failed to load pending products.</div>');
                        }
                    }, 'json').fail(function(){
                        $('#wf-pending-products').html('<div class="wf-error">Server error while loading.</div>');
                    });
                }

                // Approve
                $(document).on('click', '#wf-pending-products .sty-approve', function(e){
                    e.preventDefault();
                    var id = $(this).data('id');
                    if (!id) return;
                    if (!confirm('Approve this product and publish it?')) return;
                    $.post(ajaxurl, {
                        action: 'styliiiish_vendor_moderate',
                        nonce: adminNonce,
                        product_id: id,
                        moderation: 'approve'
                    }, function(resp){
                        if (resp && resp.success) {
                            alert(resp.data.message || 'Approved');
                            loadPendingProducts();
                        } else {
                            alert((resp && resp.data && resp.data.message) ? resp.data.message : 'Error');
                        }
                    }, 'json');
                });

                // Reject with typed reason
                $(document).on('click', '#wf-pending-products .sty-reject', function(e){
                    e.preventDefault();
                    var id = $(this).data('id');
                    if (!id) return;
                    var note = prompt('Type rejection reason (will be emailed to vendor):');
                    if (note === null) return; // cancelled
                    // send as array keys
                    var data = {
                        action: 'styliiiish_vendor_moderate',
                        nonce: adminNonce,
                        product_id: id,
                        moderation: 'reject'
                    };
                    data['reason[reason]'] = 'other';
                    data['reason[note]'] = note;

                    $.post(ajaxurl, data, function(resp){
                        if (resp && resp.success) {
                            alert(resp.data.message || 'Rejected');
                            loadPendingProducts();
                        } else {
                            alert((resp && resp.data && resp.data.message) ? resp.data.message : 'Error');
                        }
                    }, 'json');
                });

                // Initial load
                $(document).ready(function(){ loadPendingProducts(); });

            })(jQuery);
        </script>

    <?php endif; ?>

    <?php if ($active_tab === 'marketplace'): ?>

        <form method="post" style="margin-top:20px;">
            <input type="hidden" id="wf-lang-hidden" name="wf_lang" value="<?php echo esc_attr($lang); ?>">



            <?php wp_nonce_field('wf_save_marketplace_settings'); ?>

            <div class="wf-settings-grid">

    <!-- Marketplace -->
    <div class="wf-card">

        <h3>🛍️ Marketplace</h3>

        <label class="wf-toggle">
            <input type="checkbox"
                   name="sty_mp_enable_marketplace"
                   value="1"
                   <?php checked( $marketplace_enabled, true ); ?>>
            <span></span>
            <?php esc_html_e('Enable Customer Marketplace', 'website-flexi'); ?>
        </label>

        <p class="wf-desc">
            <?php esc_html_e('Allow customers to sell and manage products.', 'website-flexi'); ?>
        </p>

    </div>
















    <!-- My Account -->
    <div class="wf-card">

        <h3>👤 My Account</h3>

 
        <label class="wf-toggle">
            <input type="checkbox"
                   name="websiteflexi_disable_downloads"
                   value="yes"
                   <?php checked( get_option('websiteflexi_disable_downloads','no'), 'yes' ); ?>>
            <span></span>
            <?php esc_html_e('Hide Downloads Tab', 'website-flexi'); ?>
        </label>
        
        
        
        <label class="wf-toggle">
            <input type="checkbox"
               id="wf-toggle-banner"
               name="websiteflexi_myaccount_banner"
               value="yes"
               <?php checked( get_option('websiteflexi_myaccount_banner','yes'), 'yes' ); ?>>

            <span></span>
            <?php esc_html_e('Enable Welcome Banner', 'website-flexi'); ?>
        </label>
        
        
        
        
        <!-- Welcome Banner Texts -->
        <?php
            $banner_enabled = get_option('websiteflexi_myaccount_banner','yes') === 'yes';
            ?>
            
            <?php if ( ! $banner_enabled ) : ?>

                    <div class="wf-disabled-note">
                        <?php esc_html_e('Enable welcome banner to edit these settings.', 'website-flexi'); ?>
                    </div>
                    
                    <?php endif; ?>
            
            <div class="wf-card wf-welcome-settings <?php echo $banner_enabled ? 'active' : 'disabled'; ?>">
                
                


        
            <h3>💬 Welcome Banner Texts</h3>
        
           <?php

$languages = [
    'ar'    => 'العربية',
    'en_US' => 'English',
];

$user_id = get_current_user_id();

$allowed_langs = array_keys($languages);

$lang = '';

/*
 Priority:
 1) POST
 2) GET
 3) user meta
 4) locale
 5) default
*/

// 1) POST
if ( isset($_POST['wf_lang']) && in_array($_POST['wf_lang'], $allowed_langs, true) ) {

    $lang = sanitize_text_field($_POST['wf_lang']);

// 2) GET
} elseif ( isset($_GET['wf_lang']) && in_array($_GET['wf_lang'], $allowed_langs, true) ) {

    $lang = sanitize_text_field($_GET['wf_lang']);

// 3) user meta
} else {

    $saved = get_user_meta($user_id, '_wf_settings_lang', true);

    if ( in_array($saved, $allowed_langs, true) ) {

        $lang = $saved;

    }
}

// 4) locale fallback
if ( empty($lang) ) {

    $locale = websiteflexi_get_current_lang();

    if ( in_array($locale, $allowed_langs, true) ) {

        $lang = $locale;
    }
}

// 5) hard default
if ( empty($lang) ) {

    $lang = substr( get_locale(), 0, 2 );
 // default language
}


// Save only if changed
$old = get_user_meta($user_id, '_wf_settings_lang', true);

if ( $old !== $lang ) {

    update_user_meta( $user_id, '_wf_settings_lang', $lang );
}
?>
        
            
           <div style="margin-bottom:15px;">
        
            <label style="font-weight:600;">
                <?php esc_html_e('Edit Language:', 'website-flexi'); ?>
            </label>
        
            <select id="wf-lang-switch">
        
                <?php foreach ( $languages as $code => $label ) : ?>
        
                    <option value="<?php echo esc_attr($code); ?>"
                        <?php selected($lang, $code); ?>>
        
                        <?php echo esc_html($label); ?>
        
                    </option>
        
                <?php endforeach; ?>
        
            </select>
        
        </div>
        
        
       
        
        
        
        
        
        
            <p class="wf-desc">
                <?php
                printf(
                    esc_html__(
                        'Editing texts for language: %s',
                        'website-flexi'
                    ),
                    '<strong>' . esc_html($lang) . '</strong>'
                );
                ?>
            </p>
        
            <?php
            $ids = [
                'manager_title' => 'wf_manager_title_' . $lang,
                'manager_text'  => 'wf_manager_text_' . $lang,
                'manager_btn'   => 'wf_manager_btn_' . $lang,
                'user_title'    => 'wf_user_title_' . $lang,
                'user_text'     => 'wf_user_text_' . $lang,
                'user_btn'      => 'wf_user_btn_' . $lang,
            ];
            ?>
            
            <!-- Manager Title -->
            <div class="wf-field">
            
                <label for="<?php echo esc_attr($ids['manager_title']); ?>">
                    <?php esc_html_e('Manager Title','website-flexi'); ?>
                </label>
            
                <input type="text"
                       id="<?php echo esc_attr($ids['manager_title']); ?>"
                       name="wf_welcome_manager_title_<?php echo esc_attr($lang); ?>"
                       value="<?php echo esc_attr( get_option("wf_welcome_manager_title_{$lang}") ); ?>">
            
            </div>
            
            
            <!-- Manager Text -->
            <div class="wf-field">
            
                <label for="<?php echo esc_attr($ids['manager_text']); ?>">
                    <?php esc_html_e('Manager Description','website-flexi'); ?>
                </label>
            
                <textarea
                    id="<?php echo esc_attr($ids['manager_text']); ?>"
                    name="wf_welcome_manager_text_<?php echo esc_attr($lang); ?>"
                ><?php echo esc_textarea( get_option("wf_welcome_manager_text_{$lang}") ); ?></textarea>
            
            </div>
            
            
            <!-- Manager Button -->
            <div class="wf-field">
            
                <label for="<?php echo esc_attr($ids['manager_btn']); ?>">
                    <?php esc_html_e('Manager Button Text','website-flexi'); ?>
                </label>
            
                <input type="text"
                       id="<?php echo esc_attr($ids['manager_btn']); ?>"
                       name="wf_welcome_manager_btn_<?php echo esc_attr($lang); ?>"
                       value="<?php echo esc_attr( get_option("wf_welcome_manager_btn_{$lang}") ); ?>">
            
            </div>
            
            <hr>
            
            
            <!-- User Title -->
            <div class="wf-field">
            
                <label for="<?php echo esc_attr($ids['user_title']); ?>">
                    <?php esc_html_e('User Title','website-flexi'); ?>
                </label>
            
                <input type="text"
                       id="<?php echo esc_attr($ids['user_title']); ?>"
                       name="wf_welcome_user_title_<?php echo esc_attr($lang); ?>"
                       value="<?php echo esc_attr( get_option("wf_welcome_user_title_{$lang}") ); ?>">
            
            </div>
            
            
            <!-- User Text -->
            <div class="wf-field">
            
                <label for="<?php echo esc_attr($ids['user_text']); ?>">
                    <?php esc_html_e('User Description','website-flexi'); ?>
                </label>
            
                <textarea
                    id="<?php echo esc_attr($ids['user_text']); ?>"
                    name="wf_welcome_user_text_<?php echo esc_attr($lang); ?>"
                ><?php echo esc_textarea( get_option("wf_welcome_user_text_{$lang}") ); ?></textarea>
            
            </div>
            
            
            <!-- User Button -->
            <div class="wf-field">
            
                <label for="<?php echo esc_attr($ids['user_btn']); ?>">
                    <?php esc_html_e('User Button Text','website-flexi'); ?>
                </label>
            
                <input type="text"
                       id="<?php echo esc_attr($ids['user_btn']); ?>"
                       name="wf_welcome_user_btn_<?php echo esc_attr($lang); ?>"
                       value="<?php echo esc_attr( get_option("wf_welcome_user_btn_{$lang}") ); ?>">
            
            </div>

        
        </div>

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

    </div>

































   <!-- Commission -->
    <div class="wf-card wf-commission-card">
        
        
        
        
        
         <!-- Receiver -->

        <h3>🏦 Commission Receiver</h3>

        <?php
        $selected_user = get_option('wf_commission_receiver', get_current_user_id());

        wp_dropdown_users([
            'name'     => 'wf_commission_receiver',
            'selected' => $selected_user,
            'role__in' => ['administrator','shop_manager'],
            'class'    => 'wf-select'
        ]);
        ?>

        <p class="wf-desc">
            <?php esc_html_e('This account will receive commission in wallet.', 'website-flexi'); ?>
        </p>


        
        
        
        
        
        
        
        
        
        
    
        <h3>💰 Marketplace Commission</h3>
    
        <p class="wf-desc">
            <?php esc_html_e(
                'Set how much the platform earns from each vendor sale. This amount will be added to the product price and paid by the customer.',
                'website-flexi'
            ); ?>
        </p>
    
    
       <!-- Type -->
<div class="wf-field">

    <label for="wf_commission_type">
        <?php esc_html_e('Commission Method','website-flexi'); ?>
    </label>

    <select
        id="wf_commission_type"
        name="wf_commission_type"
        class="wf-commission-type"
    >

        <option value="percent" <?php selected(get_option('wf_commission_type'),'percent'); ?>>
            <?php esc_html_e('Percentage (%) of product price','website-flexi'); ?>
        </option>

        <option value="fixed" <?php selected(get_option('wf_commission_type'),'fixed'); ?>>
            <?php esc_html_e('Fixed amount per product','website-flexi'); ?>
        </option>

    </select>

</div>


<!-- Value -->
<div class="wf-field">

    <label for="wf_commission_value">
        <?php esc_html_e('Commission Amount','website-flexi'); ?>
    </label>
    
    
    <div class="wf-input-group">
        <input
            type="number"
            step="0.01"
            min="0"
            id="wf_commission_value"
            name="wf_commission_value"
            class="wf-commission-value"
            value="<?php echo esc_attr(get_option('wf_commission_value',0)); ?>">

                <span class="wf-unit">
                    <?php echo get_option('wf_commission_type') === 'fixed' ? 'EGP' : '%'; ?>
                </span>
    
            </div>
    
            <small class="wf-help">
                <?php esc_html_e(
                    'Example: If vendor price is 500, customer pays 550 when commission is 10%.',
                    'website-flexi'
                ); ?>
            </small>
    
        </div>
    
    
        <!-- Live Preview -->
        <div class="wf-preview">
    
            <strong><?php esc_html_e('Preview','website-flexi'); ?>:</strong>
            <span class="wf-preview-text">
                <?php esc_html_e('Vendor: 500 → Customer: 550 → Platform: 50','website-flexi'); ?>
            </span>
        </div>
        
        
   
    
    </div>


















</div>


            <p class="submit">
                <button type="submit" name="wf_save_marketplace_settings" class="button button-primary">
                    <?php esc_html_e('Save Marketplace Settings', 'website-flexi'); ?>
                </button>
            </p>
        </form>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

    <?php elseif ($active_tab === 'add_product'): ?>
    
    
    
    <?php

// Categories
$cats = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => false
]);

// Attributes (WooCommerce)
if ( function_exists('wc_get_attribute_taxonomies') ) {

    $attrs = wc_get_attribute_taxonomies();

    if ( ! is_array($attrs) ) {
        $attrs = [];
    }

} else {

    $attrs = [];

}

// Saved Map
$map = get_option('wf_category_attributes_map', []);

?>











    
<hr>

<div class="wf-box">

<h2>🎨 Products Dashboard Layout</h2>

<p class="description">
Choose how products are displayed in vendor/owner dashboard.
</p>

<form method="post">

<?php wp_nonce_field('wf_save_products_layout'); ?>

<?php
$layout = get_option('wf_products_layout','table');
?>


<div class="wf-layout-grid">

<label class="wf-layout-item">

  <input type="radio"
         name="wf_products_layout"
         value="table"
         <?php checked($layout,'table'); ?>>

  <span>📋 Table View</span>

</label>


<label class="wf-layout-item">

  <input type="radio"
         name="wf_products_layout"
         value="cards"
         <?php checked($layout,'cards'); ?>>

  <span>🗂 Cards View</span>

</label>


<label class="wf-layout-item">

  <input type="radio"
         name="wf_products_layout"
         value="compact"
         <?php checked($layout,'compact'); ?>>

  <span>⚡ Compact View</span>

</label>

</div>


<p class="submit">

<button
  class="button button-primary"
  name="wf_save_products_layout_btn">

💾 Save Layout

</button>

</p>

</form>

</div>
    

       
        
        
        
        
       
<hr>

        <div class="wf-box">

            <h2>📂 Vendor Allowed Categories</h2>

                <p class="description">
                Select which categories vendors are allowed to use when adding products.
                </p>

    <form method="post">

        <?php wp_nonce_field('wf_save_vendor_cats'); ?>
        
        <?php
            $allowed_cats = get_option('wf_allowed_vendor_categories', []);
        
                if(!is_array($allowed_cats)){
                    $allowed_cats = [];
                }
        ?>












    <div class="wf-attrs-box">




    <?php 
        // ترتيب: المفعّل أولاً
        $checked   = [];
        $unchecked = [];
            foreach($cats as $cat){
            
                if(in_array($cat->term_id, $allowed_cats)){
                    $checked[] = $cat;
                }else{
                    $unchecked[] = $cat;
                }
            }
        $cats_sorted = array_merge($checked, $unchecked);
    ?>




        <?php foreach($cats_sorted as $cat): ?>

    <?php $is_checked = in_array($cat->term_id,$allowed_cats); ?>

<label class="wf-attr-item <?php echo $is_checked?'is-active':''; ?>">


        <input type="checkbox"
               name="wf_vendor_cats[]"
               value="<?php echo esc_attr($cat->term_id); ?>"
               <?php checked(in_array($cat->term_id,$allowed_cats)); ?>>

        <span><?php echo esc_html($cat->name); ?></span>

    </label>

<?php endforeach; ?>

</div>


            <p class="submit">
                <button
                    class="button button-primary"
                    name="wf_save_vendor_cats_btn">
                
                💾 Save Allowed Categories
                
                </button>
        </p>

    </form>

</div>

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <hr>
        
        <div class="wf-box">
        
        <h2>🎯 Category Attributes Mapping</h2>
        
        <p class="description">
        Select a category and choose which attributes should be available
        when vendors add products.
        </p>
        
        <form method="post">
        
        <?php wp_nonce_field('wf_save_cat_attrs'); ?>
        
        <table class="form-table wf-table">
        
        <tr>
        <th>
        📂 Category
        </th>
        
        <td>
        
        <select id="wf-cat-select"
                name="wf_cat"
                class="wf-select">
        
        <option value="">
        -- Select Category --
        </option>
        
        <?php foreach($cats as $cat): ?>
        
        <option value="<?= esc_attr($cat->term_id) ?>">
        <?= esc_html($cat->name) ?>
        </option>
        
        <?php endforeach; ?>
        
        </select>
        
        <p class="help">
        Choose the product category you want to configure.
        </p>
        
        </td>
        </tr>
        
        
        <tr>
        <th>
        🧩 Assigned Attributes
        </th>
        
        <td>
        
        <div class="wf-attrs-box">
        
        <?php foreach($attrs as $a):
        
        $tax = 'pa_'.$a->attribute_name;
        ?>
        
        <label class="wf-attr-item">
        
        <input type="checkbox"
               name="wf_attrs[]"
               class="wf-attr-check"
               value="<?= esc_attr($tax) ?>">
        
        <span><?= esc_html($a->attribute_label) ?></span>
        
        </label>
        
        <?php endforeach; ?>
        
        </div>
        
        <p class="help">
        Select which attributes will appear for this category.
        </p>
        
        </td>
        </tr>
        
        </table>
        
        
        <p class="submit">
        
        <button class="button button-primary wf-save-btn"
                name="wf_save_cat_attrs_btn">
        
        💾 Save Category Settings
        
        </button>
        
        </p>
        
        </form>
        
        </div>
        
        
        
        
        
        
        
        



        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

<hr>

<div class="wf-box">
    

<h2>📌 Vendor Add Product Note</h2>

<p class="description">
Message shown above "Add Product" for vendors.
Supports HTML & emojis.
</p>

<form method="post">

<?php wp_nonce_field('wf_save_vendor_note', 'wf_vendor_note_nonce'); ?>

<?php
$note = get_option('wf_vendor_add_note','');
?>

<textarea
    name="wf_vendor_add_note"
    rows="4"
    style="width:100%;max-width:800px;"
><?php echo esc_textarea($note); ?></textarea>

<p class="submit">

<button
    type="submit"
    name="wf_save_vendor_note_btn"
    class="button button-primary">

💾 Save Note

</button>

</p>

</form>


<h2>💡 User Tips Message</h2>

<p class="description">
Message shown to vendors inside dashboard.
Supports HTML formatting.
</p>

<form method="post">

<?php wp_nonce_field('wf_save_user_tips'); ?>

<?php
$user_tips = get_option('wf_user_tips_message', '');
?>

<textarea
    name="wf_user_tips_message"
    rows="12"
    style="width:100%;max-width:800px;"
><?php echo esc_textarea($user_tips); ?></textarea>


<p class="submit">

<button
    type="submit"
    name="wf_save_user_tips_btn"
    class="button button-primary">

💾 Save Tips Message

</button>

</p>

</form>

</div>





















        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->
        <!--////////////////////////////// vendors Tap //////////////////////-->


<?php elseif ($active_tab === 'vendors'): ?>

<?php
$vendor_subtab = isset($_GET['vendor_tab']) ? sanitize_key($_GET['vendor_tab']) : 'approved';

if ( $vendor_subtab === 'all' ) {
    $vendors = get_users([
        'role__in' => ['taj_vendor','taj_vendor_pending','taj_vendor_suspended'],
        'orderby'  => 'registered',
        'order'    => 'DESC',
    ]);
} else {
    $vendors = wf_get_vendors_by_status($vendor_subtab);
}


?>

<h2 class="nav-tab-wrapper" style="margin-top:20px;">
    
    <a class="nav-tab <?php echo ($vendor_subtab === 'all') ? 'nav-tab-active' : ''; ?>"
        href="<?php echo esc_url(add_query_arg(['tab'=>'vendors','vendor_tab'=>'all'])); ?>">
        <?php esc_html_e('All Vendors', 'website-flexi'); ?>
    </a>

    <a class="nav-tab <?php echo ($vendor_subtab === 'pending') ? 'nav-tab-active' : ''; ?>"
       href="<?php echo esc_url(add_query_arg(['tab'=>'vendors','vendor_tab'=>'pending'])); ?>">
        <?php esc_html_e('Pending Vendors', 'website-flexi'); ?>
    </a>

    <a class="nav-tab <?php echo ($vendor_subtab === 'approved') ? 'nav-tab-active' : ''; ?>"
       href="<?php echo esc_url(add_query_arg(['tab'=>'vendors','vendor_tab'=>'approved'])); ?>">
        <?php esc_html_e('Approved Vendors', 'website-flexi'); ?>
    </a>



    <a class="nav-tab <?php echo ($vendor_subtab === 'suspended') ? 'nav-tab-active' : ''; ?>"
       href="<?php echo esc_url(add_query_arg(['tab'=>'vendors','vendor_tab'=>'suspended'])); ?>">
        <?php esc_html_e('Suspended Vendors', 'website-flexi'); ?>
    </a>

  
</h2>

<table class="widefat striped" style="margin-top:20px;max-width:1000px;">
<thead>
<tr>
    <th><?php esc_html_e('Vendor Details', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Documents', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Status', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Admin Notes', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Actions', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Verified', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Wallet', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Orders', 'website-flexi'); ?></th>
    <th><?php esc_html_e('Reports&amp;Reviews', 'website-flexi'); ?></th>
    
</tr>
</thead>
<tbody>



<?php if (empty($vendors)): ?>
<tr>
    <td colspan="6"><?php esc_html_e('No vendors found.', 'website-flexi'); ?></td>
</tr>
<?php else: foreach ($vendors as $vendor): ?>

<?php
$store_name     = get_user_meta($vendor->ID, 'taj_store_name', true);
$phone_call     = get_user_meta($vendor->ID, 'taj_phone_call', true);
$phone_whatsapp = get_user_meta($vendor->ID, 'taj_phone_whatsapp', true);
$address        = get_user_meta($vendor->ID, 'taj_current_address', true);

// Nonced, capability-checked viewer urls (includes/kyc-storage.php). These fall back to
// the legacy public url until tools/migrate-kyc-to-private.php has been run, so the
// markup below is unchanged.
$id_front     = function_exists('wf_kyc_doc_url') ? wf_kyc_doc_url($vendor->ID, 'taj_id_front')     : get_user_meta($vendor->ID, 'taj_id_front', true);
$id_back      = function_exists('wf_kyc_doc_url') ? wf_kyc_doc_url($vendor->ID, 'taj_id_back')      : get_user_meta($vendor->ID, 'taj_id_back', true);
$utility_bill = function_exists('wf_kyc_doc_url') ? wf_kyc_doc_url($vendor->ID, 'taj_utility_bill') : get_user_meta($vendor->ID, 'taj_utility_bill', true);

$timeline = get_user_meta($vendor->ID, 'taj_vendor_timeline', true);
$last_reason = $last_time = $last_status = '';

if (is_array($timeline) && !empty($timeline)) {
    $last = end($timeline);
    $last_reason = $last['note'] ?? '';
    $last_time   = $last['time'] ?? '';
    $last_status = $last['status'] ?? '';
}

$vendor_id = $vendor->ID;

    // مؤقتًا – placeholder
    $wallet_balance = get_user_meta( $vendor_id, 'vendor_wallet_balance', true );
    $wallet_balance = $wallet_balance !== '' ? floatval( $wallet_balance ) : 0;
    
    
$vendor_id = $vendor->ID;

// 🔹 جرّب تجيب الإحصائيات من الكاش
$cached_stats = get_transient( 'vendor_orders_stats_' . $vendor_id );

if ( $cached_stats !== false ) {

    // ✅ موجود كاش
    $completed_orders = intval( $cached_stats['completed'] );
    $returned_orders  = intval( $cached_stats['returned'] );

} else {

    // ❌ مفيش كاش – نحسب من جديد
    $completed_orders = 0;
    $returned_orders  = 0;

    // Get vendor product IDs
    $vendor_products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'author'         => $vendor_id,
    ]);

    if ( ! empty( $vendor_products ) ) {

        // Get completed & refunded orders
        $orders = wc_get_orders([
            'status' => ['completed', 'refunded', 'cancelled'],
            'limit'  => -1,
        ]);

        $counted_completed = [];
        $counted_returned  = [];

        foreach ( $orders as $order ) {

            foreach ( $order->get_items() as $item ) {

                $product_id   = $item->get_product_id();
                $variation_id = $item->get_variation_id();

                if (
                    in_array( $product_id, $vendor_products, true ) ||
                    in_array( $variation_id, $vendor_products, true )
                ) {

                    if ( $order->has_status( 'completed' ) ) {
                        $counted_completed[ $order->get_id() ] = true;
                    }

                    if ( $order->has_status( ['refunded', 'cancelled'] ) ) {
                        $counted_returned[ $order->get_id() ] = true;
                    }
                }
            }
        }

        $completed_orders = count( $counted_completed );
        $returned_orders  = count( $counted_returned );
    }

    // 💾 خزّن النتيجة في الكاش لمدة ساعة
    set_transient(
        'vendor_orders_stats_' . $vendor_id,
        [
            'completed' => $completed_orders,
            'returned'  => $returned_orders,
        ],
        HOUR_IN_SECONDS
    );
}


global $wpdb;

$vendor_id = $vendor->ID;

/* =========================
   Reviews (Parent only)
========================= */

$reviews_count = $wpdb->get_var(
    $wpdb->prepare(
        "
        SELECT COUNT(c.comment_ID)
        FROM {$wpdb->comments} c
        INNER JOIN {$wpdb->commentmeta} cm 
            ON c.comment_ID = cm.comment_id
        WHERE c.comment_type = %s
          AND c.comment_parent = %d
          AND c.comment_approved = %d
          AND cm.meta_key = %s
          AND cm.meta_value = %d
        ",
        'vendor_review',
        0,
        1,
        'vendor_id',
        $vendor_id
    )
);

$reviews_count = intval( $reviews_count );


/* =========================
   Reports
========================= */

$reports_count = $wpdb->get_var(
    $wpdb->prepare(
        "
        SELECT COUNT(*)
        FROM {$wpdb->prefix}wf_reports
        WHERE report_type = %s
          AND object_id = %d
        ",
        'vendor',
        $vendor_id
    )
);

$reports_count = intval( $reports_count );



?>










<?php if ($vendor_subtab === 'pending'): ?>
<tr>
    
    <td>
    <!-- Store Name -->
    <strong>
        <?php echo esc_html($store_name ?: __('Unnamed Store', 'website-flexi')); ?>
    </strong><br>

    <!-- Username -->
    <small>
        👤 <?php echo esc_html($vendor->user_login); ?>
    </small><br>

    <!-- Email -->
    <small>
        📧 <?php echo esc_html($vendor->user_email); ?>
    </small>
</td>

    <td>
        <?php if ($id_front): ?><a href="<?php echo esc_url($id_front); ?>" target="_blank">🪪 <?php esc_html_e('ID Front','website-flexi'); ?></a><br><?php endif; ?>
        <?php if ($id_back): ?><a href="<?php echo esc_url($id_back); ?>" target="_blank">🪪 <?php esc_html_e('ID Back','website-flexi'); ?></a><br><?php endif; ?>
        <?php if ($utility_bill): ?><a href="<?php echo esc_url($utility_bill); ?>" target="_blank">🧾 <?php esc_html_e('Utility Bill','website-flexi'); ?></a><?php endif; ?>
        <?php if (!$id_front && !$id_back && !$utility_bill): ?><em><?php esc_html_e('No documents uploaded','website-flexi'); ?></em><?php endif; ?>
    </td>

    <td><strong><?php esc_html_e('Pending Review','website-flexi'); ?></strong></td>

        <td>
        <?php if ($last_reason): ?>
            <strong><?php echo esc_html($note_label); ?></strong><br>
            <small style="<?php echo in_array($last_status,['reject','suspend']) ? 'color:#b32d2e;' : 'color:#2271b1;'; ?>">
                <?php echo esc_html($last_reason); ?>
            </small><br>
        <?php endif; ?>

        <?php if ($last_time): ?>
            <small style="color:#666;">
                🕒 <?php echo esc_html(date_i18n('d M Y – H:i', strtotime($last_time))); ?>
            </small>
        <?php endif; ?>

        <?php if (!$last_reason && !$last_time): ?>
            —
        <?php endif; ?>
    </td>

    <td>
        
      <?php $vendor_url = home_url('/vendor/' . $vendor->user_login . '/'); ?>
<a href="<?php echo esc_url($vendor_url); ?>" 
   target="_blank" 
   class="wf-btn wf-btn-view-store">
    🔍 <?php esc_html_e('View Store', 'website-flexi'); ?>
</a>
        
        <form method="post" class="wf-action-form">
            <input type="hidden" name="page" value="websiteflexi-system-settings">
            <input type="hidden" name="tab" value="vendors">
            <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
            <input type="hidden" name="wf_vendor_action" value="approve">
            <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>
        
            <textarea name="admin_note" required
                placeholder="<?php esc_attr_e('Approval note','website-flexi'); ?>"></textarea>
        
            <button type="submit" class="button button-primary button-small wf-confirm-btn">
                <?php esc_html_e('Confirm Approval','website-flexi'); ?>
            </button>
        
            <button type="button"
                class="button button-primary button-small wf-action-trigger"
                data-action="approve">
                <?php esc_html_e('Approve','website-flexi'); ?>
            </button>
        </form>

        <form method="post" class="wf-action-form wf-action-reject">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="reject">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note (hidden by default) -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for rejection','website-flexi'); ?>">
    </textarea>

    <!-- Confirm submit (hidden by default) -->
    <button type="submit"
        class="button button-small wf-confirm-btn"
        style="color:#dc3545;">
        <?php esc_html_e('Confirm Rejection','website-flexi'); ?>
    </button>

    <!-- Visible trigger button -->
    <button type="button"
        class="button button-small wf-action-trigger"
        style="color:#dc3545;">
        <?php esc_html_e('Reject','website-flexi'); ?>
    </button>

</form>




<!-- SET AS CUSTOMER -->
<form method="post" class="wf-action-form wf-action-customer">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="set_customer">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for converting vendor to customer', 'website-flexi'); ?>">
    </textarea>

    <!-- Confirm -->
    <button type="submit"
        class="button button-secondary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Convert to Customer', 'website-flexi'); ?>
    </button>

    <!-- Trigger -->
    <button type="button"
        class="button button-secondary button-small wf-action-trigger">
        👤 <?php esc_html_e('Convert to Customer', 'website-flexi'); ?>
    </button>

</form>


    </td>

    <!-- 6️⃣ Verified -->
        <?php $is_verified = get_user_meta($vendor->ID,'taj_vendor_verified',true)==='yes'; ?>
        <td>
            <form method="post">
                <input type="hidden" name="page" value="websiteflexi-system-settings">
                <input type="hidden" name="tab" value="vendors">
                <input type="hidden" name="vendor_tab" value="all">
                <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
                <input type="hidden" name="wf_vendor_action" value="toggle_verified">
                <?php wp_nonce_field('wf_toggle_verified_' . $vendor->ID, '_wpnonce'); ?>

                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                    <input type="checkbox" name="verified" value="yes"
                           onchange="this.form.submit()" <?php checked($is_verified); ?>>
                    <span><?php esc_html_e('Verified','website-flexi'); ?></span>
                </label>
            </form>
        </td>
    
     <td class="vendor-wallet">
        <strong>
        <?php
        if ( function_exists('woo_wallet') && is_object( woo_wallet()->wallet ) ) {

            $balance = woo_wallet()->wallet->get_wallet_balance( $vendor_id, true );

            if ( $balance !== null ) {
                echo wc_price( $balance );
            } else {
                echo '0.00';
            }

        } else {

            echo '—';

        }
        ?>
    </strong>
    </td>
    
    <td class="vendor-orders">

    <span class="wf-tooltip completed" data-tip="Completed Orders">
        ✅ <?php echo $completed_orders; ?>
    </span><br>

    <span class="wf-tooltip returned" data-tip="Returned / Cancelled Orders">
        🔁 <?php echo $returned_orders; ?>
    </span>

</td>


<td class="vendor-reports-reviews">

    <span class="wf-tooltip reviews" data-tip="Total Reviews">
        ⭐ <?php echo $reviews_count; ?>
    </span><br>

    <span class="wf-tooltip reports" data-tip="Total Reports">
        🚩 <?php echo $reports_count; ?>
    </span>

</td>



</tr>

<?php else: ?>
<!-- APPROVED / SUSPENDED -->
<tr>

    <!-- 1️⃣ Vendor -->
   <td>
    <!-- Store Name -->
    <strong>
        <?php echo esc_html($store_name ?: __('Unnamed Store', 'website-flexi')); ?>
    </strong><br>

    <!-- Username -->
    <small>
        👤 <?php echo esc_html($vendor->user_login); ?>
    </small><br>

    <!-- Email -->
    <small>
        📧 <?php echo esc_html($vendor->user_email); ?>
    </small>
</td>


    <!-- 2️⃣ Documents -->
    <td>
        <?php if ($id_front): ?>
            <a href="<?php echo esc_url($id_front); ?>" target="_blank">🪪 <?php esc_html_e('ID Front','website-flexi'); ?></a><br>
        <?php endif; ?>

        <?php if ($id_back): ?>
            <a href="<?php echo esc_url($id_back); ?>" target="_blank">🪪 <?php esc_html_e('ID Back','website-flexi'); ?></a><br>
        <?php endif; ?>

        <?php if ($utility_bill): ?>
            <a href="<?php echo esc_url($utility_bill); ?>" target="_blank">🧾 <?php esc_html_e('Utility Bill','website-flexi'); ?></a>
        <?php endif; ?>

        <?php if (!$id_front && !$id_back && !$utility_bill): ?>
            <em><?php esc_html_e('No documents uploaded','website-flexi'); ?></em>
        <?php endif; ?>
    </td>

    <!-- 3️⃣ Status -->
    <td>
    <?php if ($vendor_subtab === 'approved'): ?>

        <strong><?php esc_html_e('Approved', 'website-flexi'); ?></strong>

    <?php elseif ($vendor_subtab === 'suspended'): ?>

        <strong style="color:#b32d2e;"><?php esc_html_e('Suspended', 'website-flexi'); ?></strong>

    <?php elseif ($vendor_subtab === 'all'): ?>

        <?php
        if (in_array('taj_vendor', $vendor->roles, true)) {
            echo '<strong>' . esc_html__('Approved', 'website-flexi') . '</strong>';
        } elseif (in_array('taj_vendor_pending', $vendor->roles, true)) {
            echo '<strong>' . esc_html__('Pending Review', 'website-flexi') . '</strong>';
        } elseif (in_array('taj_vendor_suspended', $vendor->roles, true)) {
            echo '<strong style="color:#b32d2e;">' . esc_html__('Suspended', 'website-flexi') . '</strong>';
        } else {
            echo '—';
        }
        ?>

    <?php endif; ?>
</td>

<?php 
if (!isset($note_label)) {
    $note_label = '';
}

?>
    <!-- 4️⃣ Admin Notes -->
    <td>
        <?php if ($last_reason): ?>
            <strong><?php echo esc_html($note_label); ?></strong><br>
            <small style="<?php echo in_array($last_status,['reject','suspend']) ? 'color:#b32d2e;' : 'color:#2271b1;'; ?>">
                <?php echo esc_html($last_reason); ?>
            </small><br>
        <?php endif; ?>

        <?php if ($last_time): ?>
            <small style="color:#666;">
                🕒 <?php echo esc_html(date_i18n('d M Y – H:i', strtotime($last_time))); ?>
            </small>
        <?php endif; ?>

        <?php if (!$last_reason && !$last_time): ?>
            —
        <?php endif; ?>
    </td>
    <?php
$user  = get_userdata($vendor->ID);
$roles = (array) ($user->roles ?? []);

if (in_array('taj_vendor_pending', $roles, true)) {
    $vendor_status = 'pending';
} elseif (in_array('taj_vendor', $roles, true)) {
    $vendor_status = 'approved';
} elseif (in_array('taj_vendor_suspended', $roles, true)) {
    $vendor_status = 'suspended';
} else {
    $vendor_status = '';
}
?>
    <!-- 5️⃣ Actions -->
<td>


<?php $vendor_url = home_url('/vendor/' . $vendor->user_login . '/'); ?>
<a href="<?php echo esc_url($vendor_url); ?>" 
   target="_blank" 
   class="wf-btn wf-btn-view-store">
    🔍 <?php esc_html_e('View Store', 'website-flexi'); ?>
</a>


<?php if ($vendor_status === 'pending'): ?>

    <!-- APPROVE -->
<form method="post" class="wf-action-form">
    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="approve">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Approval note','website-flexi'); ?>"></textarea>

    <button type="submit" class="button button-primary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Approval','website-flexi'); ?>
    </button>

    <button type="button"
        class="button button-primary button-small wf-action-trigger"
        data-action="approve">
        <?php esc_html_e('Approve','website-flexi'); ?>
    </button>
</form>


    <!-- REJECT -->
  <form method="post" class="wf-action-form wf-action-reject">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="reject">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note (hidden by default) -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for rejection','website-flexi'); ?>">
    </textarea>

    <!-- Confirm submit (hidden by default) -->
    <button type="submit"
        class="button button-small wf-confirm-btn"
        style="color:#dc3545;">
        <?php esc_html_e('Confirm Rejection','website-flexi'); ?>
    </button>

    <!-- Visible trigger button -->
    <button type="button"
        class="button button-small wf-action-trigger"
        style="color:#dc3545;">
        <?php esc_html_e('Reject','website-flexi'); ?>
    </button>

</form>



<!-- SET AS CUSTOMER -->
<form method="post" class="wf-action-form wf-action-customer">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="set_customer">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for converting vendor to customer', 'website-flexi'); ?>">
    </textarea>

    <!-- Confirm -->
    <button type="submit"
        class="button button-secondary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Convert to Customer', 'website-flexi'); ?>
    </button>

    <!-- Trigger -->
    <button type="button"
        class="button button-secondary button-small wf-action-trigger">
        👤 <?php esc_html_e('Convert to Customer', 'website-flexi'); ?>
    </button>

</form>








<?php elseif ($vendor_status === 'approved'): ?>

    <!-- SUSPEND -->
<form method="post" class="wf-action-form wf-action-suspend">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="suspend">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note (hidden by default) -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for suspension','website-flexi'); ?>">
    </textarea>

    <!-- Confirm submit (hidden by default) -->
    <button type="submit"
        class="button button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Suspension','website-flexi'); ?>
    </button>

    <!-- Visible trigger button -->
    <button type="button"
        class="button button-small wf-action-trigger">
        <?php esc_html_e('Suspend','website-flexi'); ?>
    </button>

</form>

    <!-- SET TO PENDING -->
<form method="post" class="wf-action-form wf-action-pending">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_tab" value="<?php echo esc_attr($vendor_subtab); ?>">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="set_pending">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note (hidden by default) -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for setting vendor to pending', 'website-flexi'); ?>">
    </textarea>

    <!-- Confirm submit (hidden by default) -->
    <button type="submit"
        class="button button-secondary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Set to Pending', 'website-flexi'); ?>
    </button>

    <!-- Visible trigger button -->
    <button type="button"
        class="button button-secondary button-small wf-action-trigger">
        <?php esc_html_e('Set to Pending', 'website-flexi'); ?>
    </button>

</form>


<!-- SET AS CUSTOMER -->
<form method="post" class="wf-action-form wf-action-customer">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="set_customer">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for converting vendor to customer', 'website-flexi'); ?>">
    </textarea>

    <!-- Confirm -->
    <button type="submit"
        class="button button-secondary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Convert to Customer', 'website-flexi'); ?>
    </button>

    <!-- Trigger -->
    <button type="button"
        class="button button-secondary button-small wf-action-trigger">
        👤 <?php esc_html_e('Convert to Customer', 'website-flexi'); ?>
    </button>

</form>



<?php elseif ($vendor_status === 'suspended'): ?>

    <!-- REACTIVATE -->
  <form method="post" class="wf-action-form wf-action-activate">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="activate">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note (hidden by default) -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for re-activation','website-flexi'); ?>">
    </textarea>

    <!-- Confirm button (hidden by default) -->
    <button type="submit"
        class="button button-primary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Re-Activation','website-flexi'); ?>
    </button>

    <!-- Visible trigger -->
    <button type="button"
        class="button button-primary button-small wf-action-trigger">
        <?php esc_html_e('Re-Activate','website-flexi'); ?>
    </button>

</form>


    <!-- SET TO PENDING -->
    <form method="post" class="wf-action-form wf-action-set-pending">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="set_pending">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note (hidden by default) -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for setting pending','website-flexi'); ?>">
    </textarea>

    <!-- Confirm submit (hidden by default) -->
    <button type="submit"
        class="button button-secondary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Set to Pending','website-flexi'); ?>
    </button>

    <!-- Visible trigger button -->
    <button type="button"
        class="button button-secondary button-small wf-action-trigger">
        <?php esc_html_e('Set to Pending','website-flexi'); ?>
    </button>

</form>

<!-- SET AS CUSTOMER -->
<form method="post" class="wf-action-form wf-action-customer">

    <input type="hidden" name="page" value="websiteflexi-system-settings">
    <input type="hidden" name="tab" value="vendors">
    <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
    <input type="hidden" name="wf_vendor_action" value="set_customer">
    <?php wp_nonce_field('wf_vendor_action_' . $vendor->ID, '_wpnonce'); ?>

    <!-- Admin note -->
    <textarea name="admin_note" required
        placeholder="<?php esc_attr_e('Reason for converting vendor to customer', 'website-flexi'); ?>">
    </textarea>

    <!-- Confirm -->
    <button type="submit"
        class="button button-secondary button-small wf-confirm-btn">
        <?php esc_html_e('Confirm Convert to Customer', 'website-flexi'); ?>
    </button>

    <!-- Trigger -->
    <button type="button"
        class="button button-secondary button-small wf-action-trigger">
        👤 <?php esc_html_e('Convert to Customer', 'website-flexi'); ?>
    </button>

</form>


<?php else: ?>

    —

<?php endif; ?>

</td>


    <!-- 6️⃣ Verified -->

    
        <?php $is_verified = get_user_meta($vendor->ID,'taj_vendor_verified',true)==='yes'; ?>
        <td>
            <form method="post">
                <input type="hidden" name="page" value="websiteflexi-system-settings">
                <input type="hidden" name="tab" value="vendors">
                <input type="hidden" name="vendor_tab" value="all">
                <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor->ID); ?>">
                <input type="hidden" name="wf_vendor_action" value="toggle_verified">
                <?php wp_nonce_field('wf_toggle_verified_' . $vendor->ID, '_wpnonce'); ?>

                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                    <input type="checkbox" name="verified" value="yes"
                           onchange="this.form.submit()" <?php checked($is_verified); ?>>
                    <span><?php esc_html_e('Verified','website-flexi'); ?></span>
                </label>
            </form>
        </td>
        
        
 

    
    
    
    
<td class="vendor-wallet">
    <strong>
        <?php
        if ( function_exists('woo_wallet') && is_object( woo_wallet()->wallet ) ) {

            $balance = woo_wallet()->wallet->get_wallet_balance( $vendor_id, true );

            if ( $balance !== null ) {
                echo wc_price( $balance );
            } else {
                echo '0.00';
            }

        } else {

            echo '—';

        }
        ?>
    </strong>
</td>

    
    
    
    
    
    
    <td class="vendor-orders">

    <span class="wf-tooltip completed" data-tip="Completed Orders">
        ✅ <?php echo $completed_orders; ?>
    </span><br>

    <span class="wf-tooltip returned" data-tip="Returned / Cancelled Orders">
        🔁 <?php echo $returned_orders; ?>
    </span>

</td>


<td class="vendor-reports-reviews">

    <span class="wf-tooltip reviews" data-tip="Total Reviews">
        ⭐ <?php echo $reviews_count; ?>
    </span><br>

    <span class="wf-tooltip reports" data-tip="Total Reports">
        🚩 <?php echo $reports_count; ?>
    </span>

</td>


    
    
    
    
</tr>


            <?php endif; ?>

        <?php endforeach; endif; ?>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        <?php elseif ($active_tab === 'reviews'): ?>

            <?php
        global $wpdb;
        
        /* =====================
           Build Filters
        ===================== */
        
        $where = [];
        $params = [];
        
        /* Base */
        $where[] = "c.comment_type = 'vendor_review'";
        $where[] = "c.comment_approved = 1";
        
/* =====================
   Vendor Search (CACHED + FAST)
===================== */
if ( ! empty($_GET['search_vendor']) ) {

    $search = trim($_GET['search_vendor']);

    // ⛔ Prevent heavy queries on very small input
    if ( mb_strlen($search) < 3 && ! is_numeric($search) ) {
        $where[] = '1=0';
    } else {

        $cache_key = 'wf_vendor_search_' . md5($search);
        $vendor_ids = get_transient($cache_key);

        if ( $vendor_ids === false ) {

            $vendor_ids = [];

            // 1️⃣ Numeric ID
            if ( is_numeric($search) ) {

                $vendor_ids[] = (int) $search;

            } else {

                // 2️⃣ Email / Display name
                $users_main = get_users([
                    'search'         => '*' . esc_attr($search) . '*',
                    'search_columns' => ['user_email', 'display_name'],
                    'fields'         => 'ID',
                    'number'         => 30,
                ]);

                // 3️⃣ Store name
                $users_store = get_users([
                    'meta_query' => [
                        [
                            'key'     => 'taj_store_name',
                            'value'   => $search,
                            'compare' => 'LIKE',
                        ],
                    ],
                    'fields' => 'ID',
                    'number' => 30,
                ]);

                $vendor_ids = array_unique(
                    array_merge(
                        array_map('intval', $users_main),
                        array_map('intval', $users_store)
                    )
                );
            }

            // Cache result for 10 minutes
            set_transient($cache_key, $vendor_ids, 10 * MINUTE_IN_SECONDS);
        }

        if ( empty($vendor_ids) ) {
            $where[] = '1=0';
        } else {
            $where[] = 'vendor_meta.meta_value IN (' . implode(',', array_slice($vendor_ids, 0, 50)) . ')';
        }
    }
}



        
        /* Rating filter */
        if ( ! empty($_GET['filter_rating']) ) {
            $where[] = "rating_meta.meta_value = %d";
            $params[] = (int) $_GET['filter_rating'];
        }
        
        /* Date from */
        if ( ! empty($_GET['from']) ) {
            $where[] = "c.comment_date >= %s";
            $params[] = $_GET['from'] . ' 00:00:00';
        }
        
        /* Date to */
        if ( ! empty($_GET['to']) ) {
            $where[] = "c.comment_date <= %s";
            $params[] = $_GET['to'] . ' 23:59:59';
        }
        
        /* Soft delete (hidden) */
        $where[] = "hidden_meta.meta_value IS NULL";
        
        /* =====================
           SQL
        ===================== */
        
        $sql = "
        SELECT 
            c.*,
            vendor_meta.meta_value AS vendor_id,
            rating_meta.meta_value AS rating
        FROM {$wpdb->comments} c
        
        INNER JOIN {$wpdb->commentmeta} vendor_meta
            ON vendor_meta.comment_id = c.comment_ID
            AND vendor_meta.meta_key = 'vendor_id'
        
        LEFT JOIN {$wpdb->commentmeta} rating_meta
            ON rating_meta.comment_id = c.comment_ID
            AND rating_meta.meta_key = 'rating'
        
        LEFT JOIN {$wpdb->commentmeta} hidden_meta
            ON hidden_meta.comment_id = c.comment_ID
            AND hidden_meta.meta_key = 'wf_hidden'
        
        WHERE " . implode(' AND ', $where) . "
        
        ORDER BY c.comment_date_gmt DESC
        LIMIT 200
        ";
        
        if ( ! empty( $params ) ) {

            $reviews = $wpdb->get_results(
                $wpdb->prepare( $sql, $params )
            );
        
        } else {
        
            $reviews = $wpdb->get_results( $sql );
        
        }

        ?>


                    <h2 style="margin-top:20px;"><?php esc_html_e('Vendor Reviews', 'website-flexi'); ?></h2>
                    
                    
                    <form method="get" style="margin:15px 0;">
                        <input type="hidden" name="page" value="websiteflexi-system-settings">
                        <input type="hidden" name="tab" value="reviews">
                    
                        <input type="text"
                               name="search_vendor"
                               placeholder="Vendor ID / Email / Store name"
                               value="<?php echo esc_attr($_GET['search_vendor'] ?? ''); ?>"
                               style="width:260px;">
                    
                        <select name="filter_rating">
                            <option value="">All ratings</option>
                            <?php for ($i=5;$i>=1;$i--): ?>
                                <option value="<?php echo $i; ?>" <?php selected($_GET['filter_rating'] ?? '', $i); ?>>
                                    <?php echo $i; ?> ★
                                </option>
                            <?php endfor; ?>
                        </select>
                    
                        <input type="date" name="from" value="<?php echo esc_attr($_GET['from'] ?? ''); ?>">
                        <input type="date" name="to" value="<?php echo esc_attr($_GET['to'] ?? ''); ?>">
                    
                        <button class="button">Filter</button>
                    </form>


                    
                    <?php if ( empty($reviews) ) : ?>
                        <p><?php esc_html_e('No vendor reviews found.', 'website-flexi'); ?></p>
                    <?php else : ?>
                    
                    
         
                    
                    
                    <div class="wf-reviews-grid">

                        <?php foreach ( $reviews as $review ) :
                        
                            $rating = get_comment_meta($review->comment_ID, 'rating', true);
                            $vendor = get_user_by('id', (int) $review->vendor_id);
                        
                            $delete_url = wp_nonce_url(
                                add_query_arg([
                                    'page' => 'websiteflexi-system-settings',
                                    'tab'  => 'reviews',
                                    'delete_review' => $review->comment_ID,
                                ], admin_url('plugins.php')),
                                'wf_delete_review_' . $review->comment_ID
                            );
                        
                        ?>
                        
                        <div class="wf-review-card">
                        
                            <!-- Header -->
                            <div class="wf-review-header">
                        
                                <div>
                                    <strong><?php echo esc_html($review->comment_author); ?></strong><br>
                                    <small><?php echo esc_html($review->comment_author_email); ?></small>
                                    
                                </div>
                        
                                <div class="wf-review-rating">
                                    <?php
                                    for($i=1;$i<=5;$i++){
                                        echo $i <= $rating ? '⭐' : '☆';
                                    }
                                    ?>
                                </div>
                        
                            </div>
                        
                        
                            <!-- Vendor -->
                           <div class="wf-review-vendor">

                                <?php if ( $vendor ) :
                                
                                    $store_name = get_user_meta( $vendor->ID, 'taj_store_name', true );
                                
                                ?>
                                
                                 <?php if ( $store_name ) : ?>
                                        <div class="wf-review-store">
                                            🏪 <?php echo esc_html( $store_name ); ?>
                                        </div>
                                    <?php endif; ?>
                                
                                <?php else : ?>
                                
                                    —
                                
                                <?php endif; ?>
                                
                                    🛍️ <?php echo esc_html( $vendor->display_name ); ?>
                                    <small>(#<?php echo $vendor->ID; ?>)</small>
                                
                                   
                                
                                </div>

                        
                        
                            <!-- Content -->
                            <div class="wf-review-content">
                        
                                <?php echo esc_html( wp_trim_words($review->comment_content, 30) ); ?>
                        
                            </div>
                        
                        
                            <!-- Footer -->
                            <div class="wf-review-footer">
                        
                                <span class="wf-review-date">
                                    📅 <?php echo esc_html(
                                        date_i18n('d M Y – H:i', strtotime($review->comment_date))
                                    ); ?>
                                </span>
                        
                                <a href="<?php echo esc_url($delete_url); ?>"
                                   onclick="return confirm('<?php echo esc_js(__('Delete this review?', 'website-flexi')); ?>');"
                                   class="wf-review-delete">
                                    🗑 Delete
                                </a>
                        
                            </div>
                        
                        </div>
                        
                        <?php endforeach; ?>
                        
                        </div>




















<?php endif; ?>

        
            <?php elseif ($active_tab === 'reports'): ?>
            
            
            
            
            
            
            
            
            

                                    <?php
                                    
                                    
                                    
                                    global $wpdb;
                                    $table = $wpdb->prefix . 'wf_reports';

                                        $report_tab = isset($_GET['report_tab'])
                                            ? sanitize_key($_GET['report_tab'])
                                            : 'review';
                                        
                                        $report_view = isset($_GET['report_view'])
                                            ? sanitize_key($_GET['report_view'])
                                            : 'pending';
                                        
                                        $status = ($report_view === 'history')
                                            ? 'reviewed'
                                            : 'pending';

                                            // Count Pending
                                            $pending_count = $wpdb->get_var(
                                                $wpdb->prepare(
                                                    "SELECT COUNT(*) FROM {$table}
                                                     WHERE report_type = %s AND status = %s",
                                                    $report_tab,
                                                    'pending'
                                                )
                                            );
                                            
                                            // Count Reviewed
                                            $reviewed_count = $wpdb->get_var(
                                                $wpdb->prepare(
                                                    "SELECT COUNT(*) FROM {$table}
                                                     WHERE report_type = %s AND status = %s",
                                                    $report_tab,
                                                    'reviewed'
                                                )
                                            );
                                            
                                            
                                        $reports = $wpdb->get_results(
                                            $wpdb->prepare(
                                                "SELECT * FROM {$table}
                                                 WHERE report_type = %s
                                                 AND status = %s
                                                 ORDER BY created_at DESC
                                                 LIMIT 200",
                                                $report_tab,
                                                $status
                                            )
                                        );


                                    ?>
                                    
                                    <!-- View Tabs -->
                                           <h2 class="nav-tab-wrapper wf-main-tabs" style="margin-top:20px;">

                                               
                                                <!-- Type -->
                                                <a class="nav-tab <?php echo ($report_tab === 'review') ? 'nav-tab-active' : ''; ?>"
                                                   href="<?php echo esc_url(add_query_arg([
                                                        'tab'=>'reports',
                                                        'report_tab'=>'review',
                                                        'report_view'=>$report_view
                                                   ])); ?>">
                                                    📝 Review Reports
                                                </a>
                                            
                                                <a class="nav-tab <?php echo ($report_tab === 'vendor') ? 'nav-tab-active' : ''; ?>"
                                                   href="<?php echo esc_url(add_query_arg([
                                                        'tab'=>'reports',
                                                        'report_tab'=>'vendor',
                                                        'report_view'=>$report_view
                                                   ])); ?>">
                                                    🛍️ Vendor Reports
                                                </a>
                                            
                                                <a class="nav-tab <?php echo ($report_tab === 'product') ? 'nav-tab-active' : ''; ?>"
                                                   href="<?php echo esc_url(add_query_arg([
                                                        'tab'=>'reports',
                                                        'report_tab'=>'product',
                                                        'report_view'=>$report_view
                                                   ])); ?>">
                                                    📦 Product Reports
                                                </a>
                                            
                                            </h2>
                                            
                                            
                                            
                                            <div class="wf-sub-tabs">

                                                <!-- Pending -->
                                                <a class="<?php echo ($report_view === 'pending') ? 'active' : ''; ?>"
                                                   href="<?php echo esc_url(add_query_arg([
                                                        'tab'=>'reports',
                                                        'report_view'=>'pending',
                                                        'report_tab'=>$report_tab
                                                   ])); ?>">
                                                   ⏳ Pending (<?php echo intval($pending_count); ?>)
                                                </a>
                                            
                                                <!-- History -->
                                                <a class="<?php echo ($report_view === 'history') ? 'active' : ''; ?>"
                                                   href="<?php echo esc_url(add_query_arg([
                                                        'tab'=>'reports',
                                                        'report_view'=>'history',
                                                        'report_tab'=>$report_tab
                                                   ])); ?>">
                                                   📜 History (<?php echo intval($reviewed_count); ?>)
                                                </a>
                                            
                                            </div>

                                            
                                            
                                            
                                            
                                            
                                            
                                            

                                    
                                    <?php if ( empty($reports) ): ?>
                                        <p><?php esc_html_e('No reports found.', 'website-flexi'); ?></p>
                                    <?php else: ?>
                                    
                                    <div class="wf-reports-grid">

<?php foreach ($reports as $r):

    $user = get_user_by('id', $r->reported_by);

    $review = null;
    $vendor = null;
    $item_title = '—';
    $item_link  = '';
    $item_type  = ucfirst($r->report_type);

    if ($r->report_type === 'review') {

        $review = get_comment($r->object_id);
        $vendor_id = get_comment_meta($r->object_id, 'vendor_id', true);

        if ($review && $vendor_id) {
            $vendor = get_user_by('id', (int)$vendor_id);

            if ($vendor) {
                $item_title = 'Review #' . $review->comment_ID;
                $item_link  = site_url(
                    '/vendor/' . $vendor->user_login . '/#comment-' . $review->comment_ID
                );
            }
        }

    } elseif ($r->report_type === 'vendor') {

        $vendor = get_user_by('id', $r->object_id);

        if ($vendor) {
            $item_title = 'Vendor: ' . $vendor->display_name;
            $item_link  = site_url('/vendor/' . $vendor->user_login);
        }
    }

?>

<div class="wf-report-card">

    <!-- HEADER -->
    <div class="wf-report-header">

        <div class="wf-report-title">
            🚩 <?php echo esc_html($item_type); ?> Report
            <small>#<?php echo esc_html($r->id); ?></small>
        </div>

        <span class="wf-report-status <?php echo esc_attr($r->status); ?>">
            <?php echo esc_html( ucfirst($r->status) ); ?>
        </span>

    </div>


    <!-- REPORTED ITEM -->
<div class="wf-report-item">

    <strong>🔗 Reported Item:</strong><br>

    <?php if ($item_link): ?>
        <a href="<?php echo esc_url($item_link); ?>" target="_blank">
            <?php echo esc_html($item_title); ?>
        </a>
    <?php else: ?>
        <em>Item not found</em>
    <?php endif; ?>


    <?php if ( $r->report_type === 'review' && ! empty($review) ): ?>

        <?php
        $review_author = $review->comment_author;
        $review_text   = wp_trim_words($review->comment_content, 35);
        $review_rating = get_comment_meta($review->comment_ID, 'rating', true);
        ?>

        <!-- Reported Review Preview -->
        <div class="wf-reported-review">

            <div class="wf-review-head">

                👤 <?php echo esc_html($review_author); ?>

                <?php if ($review_rating): ?>
                    <span class="wf-review-stars">
                        ⭐ <?php echo esc_html($review_rating); ?>/5
                    </span>
                <?php endif; ?>

            </div>

            <div class="wf-review-content">
                “<?php echo esc_html($review_text); ?>”
            </div>

        </div>

    <?php endif; ?>

</div>



    <!-- DETAILS -->
    <div class="wf-report-body">

        <p>
            <strong>👤 Reported By:</strong>
            <?php echo esc_html(
                $user ? $user->display_name . ' (#' . $user->ID . ')' : '—'
            ); ?>
        </p>

        <p>
            <strong>⚠️ Reason:</strong>
            <?php echo esc_html( ucfirst($r->reason) ); ?>
        </p>

        <p>
            <strong>💬 Comment:</strong><br>
            <?php echo esc_html($r->comment ?: '—'); ?>
        </p>

        <p>
            <strong>📅 Date:</strong>
            <?php echo esc_html(
                date_i18n('d M Y – H:i', strtotime($r->created_at))
            ); ?>
        </p>

    </div>


    <!-- ACTIONS -->
    <div class="wf-report-actions">

        <?php if ($review): ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="wf_admin_delete_review">
            <input type="hidden" name="review_id" value="<?php echo esc_attr($review->comment_ID); ?>">
            <input type="hidden" name="report_id" value="<?php echo esc_attr($r->id); ?>">
            <?php wp_nonce_field('wf_admin_delete_review','wf_admin_delete_review_nonce'); ?>

            <button class="wf-btn-danger"
                    onclick="return confirm('Delete this review permanently?');">
                ❌ Delete Review
            </button>
        </form>
        <?php endif; ?>

        <?php if ($r->status !== 'reviewed'): ?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="wf_mark_report_reviewed">
            <input type="hidden" name="report_id" value="<?php echo esc_attr($r->id); ?>">
            <?php wp_nonce_field('wf_mark_report','wf_mark_report_nonce'); ?>

            <button class="wf-btn-success">
                ✅ Mark as Reviewed
            </button>
        </form>
        <?php else: ?>
            <span class="wf-reviewed-badge">✔ Reviewed</span>
        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

</div>

                                                <?php endif; ?>
        

    
    
    
    
    
    
    
    


    
    
    
    

<?php endif; ?>



<?php
if ($active_tab === 'vendor-orders'):

/* Save Settings */
if (
    isset($_POST['wf_delivery_responsibility']) &&
    check_admin_referer('wf_save_vendor_settings', 'wf_vendor_nonce')
) {

    update_option(
        'wf_delivery_responsibility',
        sanitize_text_field($_POST['wf_delivery_responsibility'])
    );

    if (isset($_POST['wf_tracking_meta_key'])) {
        update_option(
            'wf_tracking_meta_key',
            sanitize_text_field($_POST['wf_tracking_meta_key'])
        );
    }

    update_option('wf_prep_days_min', absint($_POST['wf_prep_days_min'] ?? 0));
    update_option('wf_prep_days_max', absint($_POST['wf_prep_days_max'] ?? 0));

    update_option('wf_ship_days_min', absint($_POST['wf_ship_days_min'] ?? 0));
    update_option('wf_ship_days_max', absint($_POST['wf_ship_days_max'] ?? 0));

    update_option('wf_enable_support', isset($_POST['wf_enable_support']) ? 1 : 0);

    echo '<div class="notice notice-success"><p>✅ Settings saved successfully.</p></div>';
}

/* Current Values */

$current           = get_option('wf_delivery_responsibility', 'site');
$tracking_meta_key = get_option('wf_tracking_meta_key', '_tracking_number');

?>

<h2 style="margin:20px 0;">📦 Vendor Orders Management</h2>

<p style="color:#555;max-width:800px;">
    Control how vendor orders are prepared, shipped, tracked, and supported.
    These settings affect <strong>all vendors</strong> on your platform.
</p>


<form method="post" class="wf-settings-cards">

<?php wp_nonce_field('wf_save_vendor_settings','wf_vendor_nonce'); ?>


<!-- Card 1 -->
<div class="wf-card">

    <h3>🚚 Who Handles Delivery?</h3>

    <p class="wf-desc">

        Decide who is responsible for delivering orders to customers.

        <br><br>

        <strong>Vendor:</strong>
        Vendor ships the order himself.

        <br>

        <strong>Site:</strong>
        Your company handles shipping and delivery.

    </p>

    <select name="wf_delivery_responsibility">
        <option value="vendor" <?php selected($current,'vendor'); ?>>
            Vendor delivers orders
        </option>

        <option value="site" <?php selected($current,'site'); ?>>
            Website handles delivery
        </option>
    </select>

</div>


<!-- Card 2 -->
<div class="wf-card">

    <h3>📍 Order Tracking System</h3>

    <p class="wf-desc">

        This is the database key used to store shipment tracking numbers.

        <br><br>

        When an order is shipped, the tracking number will be saved using this key.

        <br><br>

        Make sure it matches your shipping company integration.

    </p>

    <input
        type="text"
        name="wf_tracking_meta_key"
        value="<?php echo esc_attr($tracking_meta_key); ?>"
    >

    <small>
        Examples:
        <code>_tracking_number</code>,
        <code>_bosta_tracking</code>,
        <code>_aramex_id</code>
    </small>

</div>


<!-- Card 3 -->
<div class="wf-card">

    <h3>⏱️ Order Preparation Time</h3>

    <p class="wf-desc">

        How long vendors need to prepare orders before shipping.

        <br><br>

        This time is shown to customers as "Preparing Order".

    </p>

    <div class="wf-range">

        <input type="number" min="0"
               name="wf_prep_days_min"
               value="<?php echo esc_attr(get_option('wf_prep_days_min',1)); ?>">

        <span>to</span>

        <input type="number" min="0"
               name="wf_prep_days_max"
               value="<?php echo esc_attr(get_option('wf_prep_days_max',3)); ?>">

        <span>days</span>

    </div>

    <small>
        Example: 1 → 3 means orders need 1 to 3 days to prepare.
    </small>

</div>


<!-- Card 4 -->
<div class="wf-card">

    <h3>🚛 Shipping & Delivery Time</h3>

    <p class="wf-desc">

        Estimated time for courier companies to deliver orders
        after shipping.

        <br><br>

        This helps customers know when to expect their package.

    </p>

    <div class="wf-range">

        <input type="number" min="0"
               name="wf_ship_days_min"
               value="<?php echo esc_attr(get_option('wf_ship_days_min',1)); ?>">

        <span>to</span>

        <input type="number" min="0"
               name="wf_ship_days_max"
               value="<?php echo esc_attr(get_option('wf_ship_days_max',3)); ?>">

        <span>days</span>

    </div>

    <small>
        Example: 2 → 5 means delivery takes 2–5 days.
    </small>

</div>


<!-- Card 5 -->
<div class="wf-card">

    <h3>💬 Order Support System</h3>

    <p class="wf-desc">

        Enable a private support chat inside each order.

        <br><br>

        Vendors and admins can communicate
        about shipping, delays, and problems.

    </p>

    <label class="wf-switch">

        <input type="checkbox"
               name="wf_enable_support"
               value="1"
               <?php checked(get_option('wf_enable_support'),1); ?>>

        <span></span>

    </label>

    <p style="margin-top:8px;color:#666;font-size:13px;">
        Recommended: Enable for better customer satisfaction.
    </p>

</div>


<!-- Sticky Save Bar -->
<div class="wf-save-bar">

    <div class="wf-save-info">
        ⚙️ Changes will apply to all vendors
    </div>

    <button type="submit" class="wf-save-btn">
        💾 Save All Settings
    </button>

</div>


</form>

<?php endif; ?>












</div>
