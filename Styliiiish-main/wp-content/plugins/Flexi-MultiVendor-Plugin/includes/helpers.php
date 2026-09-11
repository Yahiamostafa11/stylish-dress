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

















