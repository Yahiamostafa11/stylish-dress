<?php
/**
 * OWNER DASHBOARD – CUSTOMER DRESSES ADDED
 * مراجعة فساتين العملاء (Pending + Draft) بشكل Cards
 */



//# Test Auto Deployment at 12:10 AM
//# Test Auto Deployment at 12:10 AM


if ( ! defined('ABSPATH') ) exit;

/**
 * Query: نجيب منتجات العملاء فقط (مش الـ Owners)
 * الحالة: pending + draft (لسه تحت المراجعة)
 */
function styliiiish_get_customer_dresses_for_review() {

    // IDs المدراء / الـ Owners
    $manager_ids = get_option('styliiiish_allowed_manager_ids', [] );
    if ( ! is_array( $manager_ids ) ) {
        $manager_ids = [];
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 50,
        'post_status'    => ['pending', 'draft'],
        'author__not_in' => $manager_ids, // استبعاد أصحاب لوحة الـ owner
        'post__not_in'   => [1905, 1954], // قوالب الإضافة
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    return new WP_Query( $args );
}






function styliiiish_get_reject_reasons() {
    return [
        'images'     => 'Images are not clear',
        'details'    => 'Missing product details',
        'condition'  => 'Condition not described correctly',
        'price'      => 'Price is not reasonable',
        'category'   => 'Wrong category selected',
        'policy'     => 'Violates marketplace policy',
        'other'      => 'Other reason',
    ];
}















/**
 * AJAX: Approve / Reject
 */
add_action( 'wp_ajax_styliiiish_vendor_moderate', 'styliiiish_vendor_moderate_cb' );
function styliiiish_vendor_moderate_cb() {
    // Nonce: accept either frontend nonces used across scripts
    if ( empty($_POST['nonce']) || ( ! wp_verify_nonce($_POST['nonce'], 'styliiiish_nonce') && ! wp_verify_nonce($_POST['nonce'], 'ajax_nonce') ) ) {
        wp_send_json_error(['message' => 'Bad nonce']);
    }

    // Role check: manager/dashboard or Woo admin
    $user_type = wf_od_get_user_type(get_current_user_id());
    if ( ! in_array($user_type, ['manager','dashboard'], true) && ! current_user_can('manage_woocommerce') ) {
        wp_send_json_error(['message' => 'No permission']);
    }

    $product_id = intval($_POST['product_id'] ?? 0);
    $moderation = sanitize_key($_POST['moderation'] ?? '');
    $reasonData  = $_POST['reason'] ?? [];
		if ( ! is_array( $reasonData ) ) {
			$reasonData = [];
		}
	$reason_key = sanitize_key($reasonData['reason'] ?? '');
	$reason_note = wp_kses_post($reasonData['note'] ?? '');

	$reasons = styliiiish_get_reject_reasons();
	$reason_label = $reasons[$reason_key] ?? 'Other';
	// ?? ???? ????? ???? ?????? ?????? ?? ???????
		$reason_text = $reason_label;
		if ( $reason_note ) {
			$reason_text .= ' - ' . $reason_note;
		}

    if (!$product_id || !in_array($moderation, ['approve','reject'], true)) {
        wp_send_json_error(['message' => 'Invalid data']);
    }

    $product = get_post($product_id);
    if (!$product || $product->post_type !== 'product') {
        wp_send_json_error(['message' => 'Product not found']);
    }

    // ========== APPROVE ==========
    if ($moderation === 'approve') {

        wp_update_post([
            'ID'          => $product_id,
            'post_status' => 'publish',
        ]);

        delete_post_meta($product_id, '_styliiiish_reject_reason');
        update_post_meta($product_id, '_styliiiish_approved_by', get_current_user_id());

        clean_post_cache($product_id);
        wc_delete_product_transients($product_id);

        $msg = 'Dress approved and published.';

    } else {

        wp_update_post([
            'ID'          => $product_id,
            'post_status' => 'draft',
        ]);

        update_post_meta( $product_id, '_styliiiish_reject_reason_key',   $reason_key );
		update_post_meta( $product_id, '_styliiiish_reject_reason_label', $reason_label );
		update_post_meta( $product_id, '_styliiiish_reject_reason_note',  $reason_note );

		// ???? ?????? ???? ????????? ?? ?? ???? ???? ??? ?????? ??? _styliiiish_reject_reason
		update_post_meta( $product_id, '_styliiiish_reject_reason', $reason_text );



        clean_post_cache($product_id);
        wc_delete_product_transients($product_id);

        $msg = 'Dress rejected and saved with reason.';
    }

    // ====== ????? ????? ????? ?????? ======
    $author_id = (int) get_post_field( 'post_author', $product_id );
    $user      = get_userdata( $author_id );

    if ( $user && $user->user_email ) {

        $subject = ( $moderation === 'approve' )
            ? 'Your dress has been approved on Styliiiish ??'
            : 'Your dress needs changes on Styliiiish';

        $body  = "Hello " . $user->display_name . ",\n\n";
        $body .= "Dress: " . get_the_title( $product_id ) . "\n\n";

        if ( $moderation === 'approve' ) {
            $body .= "Good news! Your dress has been approved and is now live on Styliiiish Marketplace. ??\n\n";
        } else {
            $body .= "Your dress could not be approved yet. Please check the reason below:\n\n";
            $body .= "Reason: " . $reason_text . "\n\n";

        }

        $body .= "You can edit your dress from your account page.\n\n";
        $body .= "Styliiiish Team ??\n";

        wp_mail( $user->user_email, $subject, $body );
    }

    wp_send_json_success([
        'message' => $msg,
        'status'  => $moderation,
    ]);
}




add_action('wp_ajax_sty_filter_vendor_products', 'sty_filter_vendor_products_cb');
function sty_filter_vendor_products_cb() {
    if ( empty($_POST['nonce']) || ( ! wp_verify_nonce($_POST['nonce'], 'styliiiish_nonce') && ! wp_verify_nonce($_POST['nonce'], 'ajax_nonce') ) ) {
        wp_send_json_error(['message' => 'Bad nonce']);
    }

    $user_type = wf_od_get_user_type(get_current_user_id());
    if (!in_array($user_type, ['manager', 'dashboard'], true) && !current_user_can('manage_woocommerce')) {
        wp_send_json_error(['message' => 'No permission']);
    }

    $status = isset($_POST['status']) ? sanitize_key($_POST['status']) : '';

    $manager_ids = get_option('styliiiish_allowed_manager_ids', [] );
    if (!is_array($manager_ids)) {
        $manager_ids = [];
    }

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 50,
        'author__not_in' => $manager_ids,
        'post__not_in'   => [1905, 1954],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [],
    ];

    switch ($status) {

        case 'pending':
            $args['post_status'] = ['pending'];
            break;

        case 'publish':
            $args['post_status'] = ['publish'];
            break;

        case 'incomplete':
            $args['post_status'] = ['draft'];
            $args['meta_query'][] = [
                'key'     => '_styliiiish_manual_deactivate',
                'compare' => 'NOT EXISTS'
            ];
            break;

        case 'deactivated':
            $args['post_status'] = ['draft'];
            $args['meta_query'][] = [
                'key'     => '_styliiiish_manual_deactivate',
                'value'   => 'yes',
                'compare' => '='
            ];
            break;

        default:
            // ?? ???? ????: ??? ???????? ?????? (pending + draft)
            $args['post_status'] = ['pending', 'draft'];
            break;
    }

    $q = new WP_Query($args);

    ob_start();

    if ($q->have_posts()) {
        foreach ($q->posts as $post) {
            echo sty_render_vendor_single_card($post->ID);
        }
    } else {
        echo '<div class="sty-no-items">No dresses found.</div>';
    }

    wp_send_json_success([
        'html' => ob_get_clean()
    ]);
}
	


















/**
 * Render cards – UI مستقل عن manage products
 */
function styliiiish_render_vendor_products() {

    $q = styliiiish_get_customer_dresses_for_review();
    ?>



    <div class="sty-vendor-review-wrapper">

        <div class="sty-vendor-review-head">
            <p class="sty-vendor-review-desc">
                Here you can review dresses added by customers.<br>
                Approve to publish, or reject with a clear reason so it’s emailed to the customer.
            </p>
        </div>

		
						<div class="sty-vendor-filters">
                            <button class="vp-filter-btn active" data-status="pending">Pending</button>
							<button class="vp-filter-btn" data-status="publish">Active</button>
							<button class="vp-filter-btn" data-status="incomplete">Incomplete</button>
							<button class="vp-filter-btn" data-status="deactivated">Deactivated</button>
						</div>     
		
<div class="sty-vendor-list">
	
	

	
	
    <?php if ( $q && $q->have_posts() ) : ?>
        <?php foreach ( $q->posts as $post ) : ?>
            <?php echo sty_render_vendor_single_card( $post->ID ); ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="sty-no-items" translate="no">No dresses found.</div>
    <?php endif; ?>
</div>


    </div>

    <script translate="no" class="notranslate trp-no-translate" data-no-translation="1">
jQuery(function($){

        if (!window.ajax_object || !window.ajax_object.ajax_url || !window.ajax_object.nonce) {
            window.ajax_object = {
                ajax_url: "<?php echo esc_js(admin_url('admin-ajax.php')); ?>",
                nonce: "<?php echo esc_js(wp_create_nonce('ajax_nonce')); ?>"
            };
        }

    function wfRenderVendorListLoading() {
        var $list = $(".sty-vendor-list");
        var $item = $("<div>")
            .addClass("sty-no-items")
            .attr("translate", "no")
            .text("Loading...");
        $list.empty().append($item);
    }

    function wfRenderVendorListError(message) {
        var safeMessage = message || "حدث خطأ أثناء تحميل البيانات.";
        var $list = $(".sty-vendor-list");
        var $item = $("<div>")
            .addClass("sty-no-items")
            .attr("translate", "no")
            .text(safeMessage);
        $list.empty().append($item);
    }


	var pendingBtn = $('.vp-filter-btn').filter('[data-status="pending"]');

	if (pendingBtn.length) {
		pendingBtn.trigger("click");
	}

	
	

    // Helper: send moderation request
function sendModeration(productID, moderation, reason) {
    return $.post(ajax_object.ajax_url, {
        action: 'styliiiish_vendor_moderate',
        nonce: ajax_object.nonce,
        product_id: productID,
        moderation: moderation,
        reason: reason   // ????? ??? ???? || ''
    }, null, 'json');
}



    // Approve single (card button)
    $(document).on('click', '.sty-approve', function(e){
        e.preventDefault();
        let id = $(this).data('id');
        if (!id) return;

        Swal.fire({
            title: 'Approve this dress?',
            text: 'It will be published immediately.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve',
            cancelButtonText: 'Cancel'
        }).then((res) => {
            if (!res.isConfirmed) return;

            sendModeration(id, 'approve', '').done(function(resp){
                if (!resp || !resp.success) {
                    Swal.fire('Error', resp?.data?.message || 'Failed.', 'error');
                    return;
                }

                Swal.fire('Approved', 'Dress has been published.', 'success');

                let card = $(".sty-vendor-card[data-id='"+id+"']");
                card.addClass("removing");

                setTimeout(() => {
                    card.remove();
                }, 250);
            
                // Reload manage-products list if present
                try{
                    if (typeof loadManageProductsPage === 'function') {
                        var page = (window.currentFilters && window.currentFilters.page) ? window.currentFilters.page : 1;
                        loadManageProductsPage(page);
                    }
                }catch(e){}

            }).fail(function(){
                Swal.fire('Error', 'Server error.', 'error');
            });
        });
    });

    // Reject single (card button) + reason
// Reject single (card button) + reason
$(document).on('click', '.sty-reject', function(e){
    e.preventDefault();
    let id = $(this).data('id');
    if (!id) return;

    Swal.fire({
        title: 'Reject this dress?',
        html: `
            <select id="reject-reason" class="swal2-input">
                <option value="">Select rejection reason</option>
                <option value="images">Images are not clear</option>
                <option value="details">Missing product details</option>
                <option value="condition">Condition not described correctly</option>
                <option value="price">Price is not reasonable</option>
                <option value="category">Wrong category selected</option>
                <option value="policy">Violates marketplace policy</option>
                <option value="other">Other reason</option>
            </select>

            <textarea id="reject-note"
                class="swal2-textarea"
                placeholder="Additional notes (optional)"></textarea>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Reject & Send',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const reason = document.getElementById('reject-reason').value;
            const note   = document.getElementById('reject-note').value;

            if (!reason) {
                Swal.showValidationMessage('Please select a rejection reason');
                return false;
            }

            return { reason, note };
        }
    }).then((res) => {
        if (!res.isConfirmed) return;

        // ????? ??? ?? (object) ???? PHP ????? array
        sendModeration(id, 'reject', res.value).done(function(resp){
            if (!resp || !resp.success) {
                Swal.fire('Error', resp?.data?.message || 'Failed.', 'error');
                return;
            }

            Swal.fire('Rejected', 'Reason sent to the customer.', 'success');

            let card = $(".sty-vendor-card[data-id='"+id+"']");
            card.addClass("removing");

            setTimeout(() => {
                card.remove();
            }, 250);

        }).fail(function(){
            Swal.fire('Error', 'Server error.', 'error');
        });
    });
});

    // =========================
    // Filters: Pending / Active / Incomplete / Deactivated
    // =========================
  
    $(document).on("click", ".vp-filter-btn", function (e) {
        e.preventDefault();

        var status = $(this).data("status");

        $(".vp-filter-btn").removeClass("active");
        $(this).addClass("active");

        wfRenderVendorListLoading();

        $.post(ajax_object.ajax_url, {
            action: "sty_filter_vendor_products",
            nonce: ajax_object.nonce,
            status: status
        }, function (resp) {
            if (!resp || !resp.success) {
                wfRenderVendorListError('فشل تحميل الفساتين.');
                return;
            }

            $(".sty-vendor-list").html(resp.data.html);
        }, 'json');
    });

						  
						  
						  
						  
						  
						  

						
				  
						  
						  
						  

});					  
</script>

    <?php
}


















function sty_render_vendor_single_card($product_id){

    $p = wc_get_product($product_id);
    if (!$p || !is_a($p, 'WC_Product')) return '';

    // === Data ===
    $name       = $p->get_name();

    $raw_desc   = (string) $p->get_description();
    $js_markers = [
        '$(document).on("click"',
        '$(document).on(\'click\'',
        '$.post(',
        'ajax_object.',
        'sty_filter_vendor_products',
        'wfRenderVendorListError',
    ];

    foreach ( $js_markers as $marker ) {
        $pos = strpos( $raw_desc, $marker );
        if ( $pos !== false ) {
            $raw_desc = substr( $raw_desc, 0, $pos );
            break;
        }
    }

    $raw_desc = wp_strip_all_tags( $raw_desc );
    $desc     = wp_trim_words( $raw_desc, 20, '...' );
    $price      = $p->get_regular_price();
    $price      = $price ? wc_price($price) : '�';
    $date_obj = $p->get_date_created();

		if ($date_obj) {
			$date = $date_obj->date('Y-m-d H:i');
		} else {
			$post = get_post($product_id);
			$date = $post ? date('d M Y --- h:i A', strtotime($post->post_date)) : '�';
		}
	$source = $date_obj ? 'Vendor' : 'Admin';
    $thumb      = $p->get_image('thumbnail');

    // Condition Attribute
    $condition_terms = wc_get_product_terms($product_id, 'pa_product-condition', ['fields' => 'names']);
    $condition = !empty($condition_terms) ? implode(', ', $condition_terms) : '�';

    ob_start(); ?>

    <div class="sty-vendor-card notranslate trp-no-translate" data-id="<?php echo $product_id; ?>" translate="no" data-no-translation="1">

        <div class="sty-vendor-card-left">
            <div class="sty-vendor-checkbox">
                <input type="checkbox" class="sty-vendor-check" value="<?php echo $product_id; ?>">
            </div>

            <div class="sty-vendor-thumb sty-open-gallery" data-id="<?php echo $product_id; ?>">
                <?php echo $thumb; ?>
            </div>
        </div>

        <div class="sty-vendor-card-body">

            <div class="sty-vendor-title" translate="no">
                <?php echo esc_html($name); ?>
            </div>

            <div class="sty-vendor-desc" translate="no">
                <strong>Description: </strong> <?php echo esc_html($desc); ?>
            </div>

            <div class="sty-vendor-cond" translate="no">
                <strong>Condition: </strong> <?php echo esc_html($condition); ?>
            </div>

            <div class="sty-vendor-meta">
                <div><strong>Price:</strong> <?php echo $price; ?></div>
                <div><strong>Date:</strong> <?php echo $date; ?></div>
            </div>
			
			<div class="sty-vendor-source">
			  <strong>Added by:</strong> <?php echo $source; ?>
			</div>


            <div class="sty-vendor-actions">
                <button class="sty-btn sty-approve" data-id="<?php echo $product_id; ?>"> Approve</button>
                <button class="sty-btn sty-reject"  data-id="<?php echo $product_id; ?>"> Reject</button>
                <a target="_blank" href="<?php echo get_permalink($product_id); ?>" class="sty-btn sty-view">View</a>
            </div>

        </div>
    </div>

    <?php 

    return ob_get_clean();
}














add_action('wp_ajax_sty_get_gallery', 'sty_get_gallery_cb');
function sty_get_gallery_cb() {
    if ( empty($_POST['nonce']) || ( ! wp_verify_nonce($_POST['nonce'], 'styliiiish_nonce') && ! wp_verify_nonce($_POST['nonce'], 'ajax_nonce') ) ) {
        wp_send_json_error(['message' => 'Bad nonce']);
    }

    $pid = intval($_POST['product_id'] ?? 0);
    if (!$pid) wp_send_json_error(['message' => 'Invalid product ID']);

    $p = wc_get_product($pid);
    if (!$p) wp_send_json_error(['message' => 'Product not found']);

    $attachment_ids = $p->get_gallery_image_ids();
    $images = [];

    foreach ($attachment_ids as $id) {
        $images[] = wp_get_attachment_image_url($id, 'large');
    }

    if (empty($images)) {
        $images[] = wp_get_attachment_image_url($p->get_image_id(), 'large');
    }

    wp_send_json_success(['images' => $images]);
}




add_action('wp_footer', 'sty_gallery_script');
function sty_gallery_script() {
    ?>
    <script translate="no" class="notranslate trp-no-translate" data-no-translation="1">
		
		
		 
		
jQuery(function($){

        if (!window.ajax_object || !window.ajax_object.ajax_url || !window.ajax_object.nonce) {
            window.ajax_object = {
                ajax_url: "<?php echo esc_js(admin_url('admin-ajax.php')); ?>",
                nonce: "<?php echo esc_js(wp_create_nonce('ajax_nonce')); ?>"
            };
        }
	
	
	
	
	
	
	
/* ==============
   Helper: Disable Scroll
==============*/
function disableScroll() {
    $('html, body').css('overflow', 'hidden');
    document.body.style.setProperty('overflow', 'hidden', 'important');
}

/* ==============
   Helper: Enable Scroll ONLY if SweetAlert closed
==============*/
function enableScrollSafely() {

    // ?? SweetAlert ??? ???? ? ?? ????? ???????
    if ($('.swal2-container').length > 0) {
        return;
    }

    $('html, body').css('overflow', 'auto');
    document.body.style.setProperty('overflow', 'auto', 'important');
}


/* =======================
   1) OPEN MAIN GALLERY
======================= */
$(document).on("click", ".sty-open-gallery", function () {

    let pid = $(this).data("id");

    $.post(ajax_object.ajax_url, {
        action: "sty_get_gallery",
        nonce: ajax_object.nonce,
        product_id: pid
    }, function(resp) {

        if (!resp.success) {
            Swal.fire("Error", resp.data.message || "Failed to load gallery.");
            return;
        }

        let html = '<div class="sty-gallery-wrap">';
        resp.data.images.forEach(img => {
            html += `
                <div class="sty-img-box">
                    <img src="${img}" class="sty-gallery-img" />
                </div>
            `;
        });
        html += '</div>';

        Swal.fire({
    title: "Product Images",
    html: html,
    width: '750px',
    background: '#fff',
    showCloseButton: true,
    focusConfirm: false,

    didOpen: () => {
        // Disable scroll
        $('html, body').css('overflow', 'hidden');
        document.body.style.setProperty('overflow', 'hidden', 'important');
    },

    willClose: () => {
        // Re-enable scroll AFTER SweetAlert is fully removed
        setTimeout(() => {
            $('html, body').css('overflow', 'auto');
            document.body.style.setProperty('overflow', 'auto', 'important');
        }, 50);
    }
});
    }, 'json');
});


/* =======================
   2) FULLSCREEN VIEWER
======================= */
$(document).on("click", ".sty-gallery-img", function(){

    let src = $(this).attr("src");

    let fullscreen = `
        <div class="sty-fullscreen-view">
            <div class="sty-fullscreen-close">�</div>
            <img src="${src}">
        </div>
    `;

    $("body").append(fullscreen);

    disableScroll();
});


/* =======================
   3) CLOSE FULLSCREEN
======================= */
$(document).on("click", ".sty-fullscreen-close, .sty-fullscreen-view", function(e){

    if ($(e.target).hasClass("sty-fullscreen-view") || $(e.target).hasClass("sty-fullscreen-close")) {

        $(".sty-fullscreen-view").remove();

        enableScrollSafely();
    }

});

	
	
	
	
	
	const wfRenderVendorListErrorLocal = (message) => {
		$(".sty-vendor-list").html(`<div class="sty-no-items" translate="no">${message}</div>`);
	};

    // Auto-load Pending on page open
    function loadPending() {
        $.post(ajax_object.ajax_url, {
            action: "sty_filter_vendor_products",
            nonce: ajax_object.nonce,
            status: "pending"
        }, function(resp) {

            if (!resp || !resp.success) {
                wfRenderVendorListErrorLocal('فشل تحميل الفساتين المعلّقة.');
                return;
            }

            $(".sty-vendor-list").html(resp.data.html);

        }, 'json');
    }

    // Run on page load
    loadPending();
	
	
	
	
});
</script>
    <?php
}






