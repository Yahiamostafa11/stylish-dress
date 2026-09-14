<?php
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * Helper: جلب قيمة option مع default
 */
function wf_od_get_option($key, $default = '') {
    $val = get_option($key, null);
    if ($val === null || $val === '') {
        return $default;
    }
    return $val;
}

/**
 * Helper: جلب لستة user IDs وتحويلهم لمستخدمين
 */
function wf_od_get_users_from_ids($ids = array()) {
    $users = array();
    if (!is_array($ids)) {
        $ids = array();
    }

    foreach ($ids as $uid) {
        $u = get_user_by('ID', intval($uid));
        if ($u) {
            $users[] = $u;
        }
    }
    return $users;
}

/**
 * Plugin-level admin check.
 * True for WP administrators or users whose email is listed in plugin settings.
 */
function wf_od_is_user_plugin_admin( $user_id = 0 ) {

    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }

    if ( ! $user_id ) {
        return false;
    }

    $user = get_user_by('ID', (int) $user_id);
    if ( ! $user ) {
        return false;
    }

    if ( in_array('administrator', (array) $user->roles, true) || user_can($user, 'manage_options') ) {
        return true;
    }

    if ( ! function_exists('wf_od_get_admin_emails') ) {
        return false;
    }

    $emails = wf_od_get_admin_emails();
    if ( empty($emails) ) {
        return false;
    }

    $user_email = strtolower(trim((string) $user->user_email));
    if ( $user_email === '' ) {
        return false;
    }

    return in_array($user_email, $emails, true);
}


/**
 * Determine user type based on settings
 *
 * Returns:
 * - "manager"
 * - "dashboard"
 * - "marketplace"
 */
function wf_od_get_user_type( $user_id = 0 ) {

    if (!$user_id) {
        $user_id = get_current_user_id();
    }

    if (!$user_id) {
        return 'marketplace';
    }

    // Lists from settings
    // ⚠️ هنا بنعتمد على الدوال اللى فى settings-handler.php
    if ( ! function_exists('wf_od_get_manager_ids') || ! function_exists('wf_od_get_dashboard_ids') ) {
        return 'marketplace';
    }

    $manager_ids   = wf_od_get_manager_ids();
    $dashboard_ids = wf_od_get_dashboard_ids();

    // Plugin admins (including email-based admins) get manager-level access.
    if ( wf_od_is_user_plugin_admin($user_id) ) {
        return 'manager';
    }

    // 1) Manager
    if ( in_array($user_id, $manager_ids, true) ) {
        return 'manager';
    }

    // 2) Dashboard user (not manager)
    if ( in_array($user_id, $dashboard_ids, true) ) {
        return 'dashboard';
    }

    // 3) Default → Marketplace user
    return 'marketplace';
}








if ( ! function_exists('wf_get_vendor_store_meta') ) {

    function wf_get_vendor_store_meta( $vendor_id ) {

    if ( ! $vendor_id ) return [];

    $user = get_user_by( 'id', $vendor_id );
    if ( ! $user ) return [];

    return [
        'id'          => $vendor_id,
        'username'    => $user->user_login,
        'display'     => $user->display_name,

        // Store
        'name'        => get_user_meta( $vendor_id, 'taj_store_name', true ) ?: $user->display_name,
        'description' => get_user_meta( $vendor_id, 'taj_store_description', true ),
        'logo'        => get_user_meta( $vendor_id, 'taj_store_logo', true ),
        'cover'       => get_user_meta( $vendor_id, 'taj_store_cover', true ),

        // Contact
        'whatsapp'    => get_user_meta( $vendor_id, 'taj_phone_whatsapp', true ),
        'phone'       => get_user_meta( $vendor_id, 'taj_phone_call', true ),
        'address'     => get_user_meta( $vendor_id, 'taj_current_address', true ),

        // Status
        'verified'    => get_user_meta( $vendor_id, 'taj_vendor_verified', true ) === 'yes',
        'kyc_status'  => get_user_meta( $vendor_id, 'taj_kyc_status', true ),
    ];
}

}




if ( ! function_exists('wf_get_vendor_reviews_stats') ) {

    function wf_get_vendor_reviews_stats( $vendor_id ) {

    global $wpdb;

    $ratings = $wpdb->get_col( $wpdb->prepare("
        SELECT cm.meta_value
        FROM {$wpdb->commentmeta} cm
        INNER JOIN {$wpdb->comments} c ON c.comment_ID = cm.comment_id
        WHERE cm.meta_key = 'rating'
        AND c.comment_type = 'vendor_review'
        AND c.comment_approved = 1
        AND EXISTS (
            SELECT 1 FROM {$wpdb->commentmeta}
            WHERE comment_id = c.comment_ID
            AND meta_key = 'vendor_id'
            AND meta_value = %d
        )
    ", $vendor_id) );

    $count = count($ratings);
    $avg   = $count ? round(array_sum($ratings) / $count, 1) : 0;

    return [
        'count' => $count,
        'avg'   => $avg,
    ];
}

}


















/* ===========================================================================
 * Centralised product-access gate  (added by ZIJ Tech — audit item H1)
 * ---------------------------------------------------------------------------
 * Every product AJAX handler in this plugin verifies the nonce `ajax_nonce`.
 * That nonce is issued to EVERY logged-in user, so it proves the request came
 * from our own pages — it proves nothing whatsoever about the caller's role.
 * Authorisation therefore has to be explicit on each handler, and several were
 * relying on the nonce alone.
 *
 * SYSTEM_RULES.md §4 already requires nonce + role + object ownership on every
 * sensitive action. These helpers are the single place that decides, so new
 * handlers cannot quietly forget one of the three.
 * ======================================================================== */

if ( ! function_exists('wf_od_is_manager') ) {
    /**
     * Manager-level access to the products module.
     *
     * @param int $user_id Defaults to the current user.
     * @return bool
     */
    function wf_od_is_manager( $user_id = 0 ) {
        $user_id = $user_id ? (int) $user_id : get_current_user_id();

        if ( $user_id <= 0 ) {
            return false;
        }

        if ( wf_od_is_user_plugin_admin( $user_id ) ) {
            return true;
        }

        if ( user_can( $user_id, 'manage_woocommerce' ) ) {
            return true;
        }

        return in_array( wf_od_get_user_type( $user_id ), array( 'manager', 'dashboard' ), true );
    }
}

if ( ! function_exists('wf_od_can_edit_product') ) {
    /**
     * May this user read/modify this specific product?
     *
     * Managers may touch anything. Everyone else is limited to products they
     * authored — this is what stops one vendor reaching another vendor's data.
     *
     * @param int $product_id
     * @param int $user_id Defaults to the current user.
     * @return bool
     */
    function wf_od_can_edit_product( $product_id, $user_id = 0 ) {
        $product_id = (int) $product_id;
        $user_id    = $user_id ? (int) $user_id : get_current_user_id();

        if ( $user_id <= 0 || $product_id <= 0 ) {
            return false;
        }

        if ( 'product' !== get_post_type( $product_id ) ) {
            return false;
        }

        if ( wf_od_is_manager( $user_id ) ) {
            return true;
        }

        return $user_id === (int) get_post_field( 'post_author', $product_id );
    }
}

if ( ! function_exists('wf_od_guard_product') ) {
    /**
     * Gate for any handler that acts on one product. Ends the request on failure.
     *
     * @param int $product_id
     * @return int The validated product id.
     */
    function wf_od_guard_product( $product_id ) {
        $product_id = (int) $product_id;

        if ( $product_id <= 0 || 'product' !== get_post_type( $product_id ) ) {
            wp_send_json_error( array( 'message' => 'Invalid product' ), 400 );
        }

        if ( ! wf_od_can_edit_product( $product_id ) ) {
            wp_send_json_error( array( 'message' => 'No permission' ), 403 );
        }

        return $product_id;
    }
}

if ( ! function_exists('wf_od_guard_products_module') ) {
    /**
     * Gate for handlers that do not target one product (lists, taxonomy lookups).
     * Requires a logged-in user; managers and marketplace vendors both qualify,
     * but the data layer must still scope results by wf_od_resolve_products_mode().
     */
    function wf_od_guard_products_module() {
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => 'Login required' ), 401 );
        }
    }
}

if ( ! function_exists('wf_od_resolve_products_mode') ) {
    /**
     * Decide which product scope the caller actually gets.
     *
     * The client may ASK for a mode, but may only ever narrow its own access:
     *   - 'user'  -> always honoured (show me only my own products)
     *   - 'owner' -> honoured for managers only; anyone else is forced to 'user'
     *
     * Before this existed the mode came straight from $_POST and defaulted to
     * 'owner', so any logged-in customer could enumerate the whole catalogue.
     *
     * @param string|null $requested Raw requested mode, or null to read $_POST.
     * @return string 'owner'|'user'
     */
    function wf_od_resolve_products_mode( $requested = null ) {
        if ( null === $requested ) {
            $requested = isset( $_POST['mode'] ) ? sanitize_key( wp_unslash( $_POST['mode'] ) ) : '';
        }

        if ( 'user' === $requested ) {
            return 'user';
        }

        return wf_od_is_manager() ? 'owner' : 'user';
    }
}
