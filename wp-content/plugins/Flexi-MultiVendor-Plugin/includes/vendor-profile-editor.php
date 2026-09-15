<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Vendor Store Profile Editor in My Account
 * - Endpoint: store-profile
 * - Saves store meta + logo + cover
 * Textdomain: website-flexi
 */

function wf_is_vendor_user( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    if ( ! $user_id ) return false;

    $user = get_userdata( $user_id );
    if ( ! $user ) return false;

    $roles = (array) $user->roles;

    // Vendors roles used in your system
    $vendor_roles = ['taj_vendor', 'taj_vendor_pending', 'taj_vendor_suspended'];

    foreach ( $vendor_roles as $r ) {
        if ( in_array( $r, $roles, true ) ) return true;
    }

    return false;
} 


/**
 * 3) Endpoint content
 */
add_action('woocommerce_account_store-profile_endpoint', function () {

    if ( ! is_user_logged_in() ) {
        echo '<p>' . esc_html__('Please log in.', 'website-flexi') . '</p>';
        return;
    }

    if ( ! wf_is_vendor_user() ) {
        echo '<p>' . esc_html__('You do not have permission to access this page.', 'website-flexi') . '</p>';
        return;
    }

    $user_id = get_current_user_id();

    // Handle save
    if ( isset($_POST['wf_save_store_profile']) ) {

        if ( ! isset($_POST['_wpnonce']) || ! wp_verify_nonce($_POST['_wpnonce'], 'wf_save_store_profile') ) {
            echo '<div class="woocommerce-error">' . esc_html__('Security check failed.', 'website-flexi') . '</div>';
        } else {

            $store_name  = isset($_POST['taj_store_name']) ? sanitize_text_field($_POST['taj_store_name']) : '';
            $store_desc  = isset($_POST['taj_store_description']) ? wp_kses_post($_POST['taj_store_description']) : '';
            $whatsapp    = isset($_POST['taj_phone_whatsapp']) ? sanitize_text_field($_POST['taj_phone_whatsapp']) : '';
            $address     = isset($_POST['taj_current_address']) ? sanitize_text_field($_POST['taj_current_address']) : '';
            $phone_call  = isset($_POST['taj_phone_call']) ? sanitize_text_field($_POST['taj_phone_call']) : '';

            // media URLs stored in user meta
            $logo_url    = isset($_POST['taj_store_logo']) ? esc_url_raw($_POST['taj_store_logo']) : '';
            $cover_url   = isset($_POST['taj_store_cover']) ? esc_url_raw($_POST['taj_store_cover']) : '';

            update_user_meta($user_id, 'taj_store_name', $store_name);
            update_user_meta($user_id, 'taj_store_description', $store_desc);
            update_user_meta($user_id, 'taj_phone_whatsapp', $whatsapp);
            update_user_meta($user_id, 'taj_current_address', $address);
            update_user_meta($user_id, 'taj_phone_call', $phone_call);

            update_user_meta($user_id, 'taj_store_logo', $logo_url);
            update_user_meta($user_id, 'taj_store_cover', $cover_url);

            $redirect = add_query_arg('wf_updated', '1', wc_get_account_endpoint_url('store-profile'));
                wp_safe_redirect($redirect);
                exit;

        }
    }

    // Load current
    $store_name  = get_user_meta($user_id, 'taj_store_name', true);
    $store_desc  = get_user_meta($user_id, 'taj_store_description', true);
    $whatsapp    = get_user_meta($user_id, 'taj_phone_whatsapp', true);
    $address     = get_user_meta($user_id, 'taj_current_address', true);

    $store = wf_get_vendor_store_meta($user_id);

        $logo_url  = $store['logo'];
        $cover_url = $store['cover'];


    $display_name = wp_get_current_user()->display_name;


if ( isset($_GET['wf_updated']) && $_GET['wf_updated'] === '1' ) {
    echo '<div class="woocommerce-message wf-updated-msg">' . esc_html__('Store profile updated successfully.', 'website-flexi') . '</div>';
}



    // Fallbacks
    if ( ! $store_name ) $store_name = $display_name;




    ?>
    <script>
document.addEventListener('DOMContentLoaded', function () {

    // Hide message after 4 seconds
    const msg = document.querySelector('.wf-updated-msg');
    if (msg) {
        setTimeout(() => {
            msg.style.transition = 'opacity 400ms ease';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 450);
        }, 4000);
    }

    // Remove wf_updated from URL (so it won't reappear on refresh)
    const url = new URL(window.location.href);
    if (url.searchParams.get('wf_updated')) {
        url.searchParams.delete('wf_updated');
        window.history.replaceState({}, document.title, url.toString());
    }

});
</script>

    <div class="wf-store-profile-editor">

        <h3 style="margin-bottom:12px;">
            <?php esc_html_e('Store Profile', 'website-flexi'); ?>
        </h3>

        <p style="margin-bottom:18px;color:#666;">
            <?php esc_html_e('Update your store information, logo, and cover. These details will appear on your public vendor page.', 'website-flexi'); ?>
        </p>

        <form method="post" class="woocommerce-EditAccountForm edit-account">
            <?php wp_nonce_field('wf_save_store_profile'); ?>







            <!-- Cover -->
            <div class="wf-media-row">
                <div class="wf-media-label">
                    <strong><?php esc_html_e('Store Cover', 'website-flexi'); ?></strong>
                    <div class="wf-media-hint"><?php esc_html_e('Recommended: wide image (e.g. 1200×400).', 'website-flexi'); ?></div>
                </div>

                <div class="wf-media-box">
                    <div class="wf-cover-preview" style="<?php echo $cover_url ? 'background-image:url(' . esc_url($cover_url) . ');' : ''; ?>">
                        <?php if ( ! $cover_url ): ?>
                            <span><?php esc_html_e('No cover selected', 'website-flexi'); ?></span>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" name="taj_store_cover" id="wf_store_cover" value="<?php echo esc_attr($cover_url); ?>">

                    <button type="button"
                        class="button wf-pick-store-image"
                        data-type="cover"
                        data-vendor-id="<?php echo get_current_user_id(); ?>">

                        <?php esc_html_e('Choose Cover', 'website-flexi'); ?>
                    </button>

                    <button type="button" class="button wf-clear-media" data-target="#wf_store_cover" data-preview=".wf-cover-preview">
                        <?php esc_html_e('Remove', 'website-flexi'); ?>
                    </button>
                </div>
            </div>

            <!-- Logo -->
            <div class="wf-media-row" style="margin-top:18px;">
                <div class="wf-media-label">
                    <strong><?php esc_html_e('Store Logo', 'website-flexi'); ?></strong>
                    <div class="wf-media-hint"><?php esc_html_e('Recommended: square image (e.g. 400×400).', 'website-flexi'); ?></div>
                </div>

                <div class="wf-media-box wf-logo-wrap">
                    <div class="wf-logo-preview">
                        <?php if ( $logo_url ): ?>
                            <img src="<?php echo esc_url($logo_url); ?>" alt="" />
                        <?php else: ?>
                            <div class="wf-logo-placeholder"><?php esc_html_e('No logo', 'website-flexi'); ?></div>
                        <?php endif; ?>
                    </div>

                    <input type="hidden" name="taj_store_logo" id="wf_store_logo" value="<?php echo esc_attr($logo_url); ?>">

                    <button type="button"
                            class="button wf-pick-store-image"
                            data-type="logo"
                            data-vendor-id="<?php echo get_current_user_id(); ?>">
                        <?php esc_html_e('Choose Logo', 'website-flexi'); ?>
                    </button>

                    <button type="button" class="button wf-clear-media" data-target="#wf_store_logo" data-preview=".wf-logo-preview">
                        <?php esc_html_e('Remove', 'website-flexi'); ?>
                    </button>
                </div>
            </div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="margin-top:22px;">
                <label for="taj_store_name"><?php esc_html_e('Store Name', 'website-flexi'); ?></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                       name="taj_store_name" id="taj_store_name"
                       value="<?php echo esc_attr($store_name); ?>" required>
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="taj_store_description"><?php esc_html_e('Store Description', 'website-flexi'); ?></label>
                <textarea class="woocommerce-Input input-text" name="taj_store_description" id="taj_store_description"
                          rows="5" style="min-height:110px;"><?php echo esc_textarea($store_desc); ?></textarea>
            </p>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="taj_phone_whatsapp"><?php esc_html_e('WhatsApp Number', 'website-flexi'); ?></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                       name="taj_phone_whatsapp" id="taj_phone_whatsapp"
                       value="<?php echo esc_attr($whatsapp); ?>"
                       placeholder="<?php echo esc_attr__('Example: 201155555555', 'website-flexi'); ?>">
                <small style="color:#777;display:block;margin-top:6px;">
                    <?php esc_html_e('Use international format without + sign.', 'website-flexi'); ?>
                </small>
            </p>
            
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="taj_phone_call">
                    <?php esc_html_e('Phone Number', 'website-flexi'); ?>
                </label>
            
                <input type="text"
                       class="woocommerce-Input woocommerce-Input--text input-text"
                       name="taj_phone_call"
                       id="taj_phone_call"
                       value="<?php echo esc_attr( get_user_meta( $user_id, 'taj_phone_call', true ) ); ?>"
                       placeholder="<?php esc_attr_e('Example: 01123456789', 'website-flexi'); ?>">
            </p>


            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="taj_current_address"><?php esc_html_e('Address', 'website-flexi'); ?></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                       name="taj_current_address" id="taj_current_address"
                       value="<?php echo esc_attr($address); ?>">
            </p>

            <p style="margin-top:18px;">
                <button type="submit" name="wf_save_store_profile" class="button woocommerce-Button button-primary">
                    <?php esc_html_e('Save Changes', 'website-flexi'); ?>
                </button>
            </p>
            
            
            
            <input type="file" id="styliiiish-upload-input" accept="image/*" style="display:none;" />

        </form>
    </div>

    <style>
        .wf-store-profile-editor { max-width: 900px; }

        .wf-media-row{
            display:flex; gap:16px; align-items:flex-start;
            padding:14px; border:1px solid #eee; border-radius:12px; background:#fff;
        }
        .wf-media-label{ min-width:220px; }
        .wf-media-hint{ font-size:12px; color:#777; margin-top:6px; }

        .wf-media-box{ flex:1; display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
        .wf-cover-preview{
            width:100%; max-width:520px; height:160px; border-radius:12px;
            border:1px dashed #ddd; display:flex; align-items:center; justify-content:center;
            background-size:cover; background-position:center; background-color:#fafafa;
            overflow:hidden;
        }
        .wf-cover-preview span{ color:#888; font-size:13px; }

        .wf-logo-wrap .wf-logo-preview{
            width:90px; height:90px; border-radius:16px; overflow:hidden;
            border:1px solid #eee; background:#fafafa; display:flex; align-items:center; justify-content:center;
        }
        .wf-logo-wrap .wf-logo-preview img{
            width:100%; height:100%; object-fit:cover;
        }
        .wf-logo-placeholder{ color:#888; font-size:13px; }

        @media (max-width: 768px){
            .wf-media-row{ flex-direction:column; }
            .wf-media-label{ min-width:unset; }
            .wf-cover-preview{ height:140px; }
            .woocommerce-message, .woocommerce-notice {border-radius: 12px;padding: 14px 18px;font-weight: 600;max-width: 1200px;margin: 20px auto;}

        }
    </style>
    <?php
});

/**
 * 4) Enqueue media uploader on My Account store profile page only
 */



add_action('wp_ajax_styliiiish_upload_store_image', 'styliiiish_upload_store_image');
function styliiiish_upload_store_image() {
    check_ajax_referer('ajax_nonce','nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'Unauthorized']);
    }

    $vendor_id  = intval($_POST['vendor_id']);
    $type       = sanitize_text_field($_POST['image_type']); // cover | logo
    
    if (get_current_user_id() !== $vendor_id) {
    wp_send_json_error(['message' => 'Permission denied']);
}


    if (!in_array($type, ['cover', 'logo'], true)) {
        wp_send_json_error(['message' => 'Invalid image type']);
    }

    if (empty($_FILES['file'])) {
        wp_send_json_error(['message' => 'No file uploaded']);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attach_id = media_handle_upload('file', 0);

    if (is_wp_error($attach_id)) {
        wp_send_json_error(['message' => $attach_id->get_error_message()]);
    }

    $meta_key = $type === 'cover'
        ? 'taj_store_cover'
        : 'taj_store_logo';

    $url = wp_get_attachment_url($attach_id);
    update_user_meta($vendor_id, $meta_key, esc_url_raw($url));

    wp_send_json_success([
        'url' => $url
    ]);
}
















/**
 * 5) Flush rewrite rules on plugin activation (recommended)
 * (Add these lines in your main plugin file if you want)
 */
