<?php
if (!defined('ABSPATH')) exit;

get_header();
do_action('woocommerce_before_main_content');

$vendor_username = get_query_var('vendor_name');
$user = get_user_by('login', $vendor_username);

if (!$user) {
    echo '<h2>' . esc_html__('Vendor not found', 'website-flexi') . '</h2>';
    get_footer();
    exit;
}

$vendor_id = $user->ID;

/* ======================
   Vendor Meta
====================== */
    $store = wf_get_vendor_store_meta($vendor_id);
    
    $store_name = $store['name'] ?: $store['display'];
    $store_desc = $store['description'];
    $whatsapp   = $store['whatsapp'];
    $address    = $store['address'];
    $verified   = $store['verified'];
    
    $logo  = $store['logo']  ?: get_avatar_url($vendor_id, ['size'=>180]);
    $cover = $store['cover'] ?: wc_placeholder_img_src();
    
    
    /* ======================
       Vendor Stats
    ====================== */
    $product_count = count_user_posts($vendor_id, 'product');
    
    /* Rating from products */
    $review_stats = wf_get_vendor_reviews_stats( $vendor_id );
    $avg_rating   = $review_stats['avg'];
    $rating_count = $review_stats['count'];

?>

<div class="vendor-page">






    <!-- COVER -->
    <div class="vendor-cover" style="background-image:url('<?php echo esc_url($cover); ?>')"></div>

    <!-- PROFILE CARD -->
    <div class="vendor-profile-card">

        <div class="vendor-logo">
            <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($store_name); ?>">
        </div>

        <div class="vendor-info">
            
            <h1 class="vendor-title">
                    <?php echo esc_html($store_name); ?>
                
                    <?php if ($verified): ?>
                       <span class="vendor-verified-badge"
                          data-tooltip="<?php esc_attr_e('Verified', 'website-flexi'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="12" fill="#1877F2"/>
                            <path d="M9.5 12.8l-1.8-1.8-1.4 1.4 3.2 3.2 7-7-1.4-1.4z"
                                  fill="#fff"/>
                        </svg>
                    </span>
                    <?php endif; ?>
                </h1>



            <?php if ($avg_rating): ?>
                <div class="vendor-rating">
                    <?php echo wc_get_rating_html($avg_rating, $rating_count); ?>
                    <span>(<?php echo esc_html($rating_count); ?>)</span>
                </div>
            <?php else: ?>
                <small><?php esc_html_e('No ratings yet','website-flexi'); ?></small>
            <?php endif; ?>

            <?php if ($store_desc): ?>
                <p class="vendor-bio"><?php echo esc_html($store_desc); ?></p>
            <?php endif; ?>

            <div class="vendor-meta">
                <span>📦 <?php echo esc_html($product_count); ?> <?php esc_html_e('Products','website-flexi'); ?></span>

                <?php if ($address): ?>
                    <span>📍 <?php echo esc_html($address); ?></span>
                <?php endif; ?>

                <?php if ($whatsapp): ?>
                    <span>💬 <a target="_blank" href="https://wa.me/<?php echo esc_attr($whatsapp); ?>">
                        <?php esc_html_e('WhatsApp','website-flexi'); ?>
                    </a></span>
                <?php endif; ?>
                
            </div>
        </div>
    </div>

    <!-- TABS -->
    <div class="vendor-tabs">
        <button class="active" data-tab="products"><?php esc_html_e('Products','website-flexi'); ?></button>
        <button data-tab="reviews"><?php esc_html_e('Reviews','website-flexi'); ?></button>
        <button data-tab="stats"><?php esc_html_e('Statistics','website-flexi'); ?></button>
        <button data-tab="report"><?php esc_html_e('Report','website-flexi'); ?></button>
    </div>







    <!-- TAB: PRODUCTS -->
    <div class="vendor-tab-content active" id="vendor-tab-products">

        <?php
        $paged = max(1, get_query_var('paged'));
        $q = new WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'author'         => $vendor_id,
            'posts_per_page' => 12,
            'paged'          => $paged,
        ]);

        if ($q->have_posts()) :
            woocommerce_product_loop_start();
            while ($q->have_posts()) : $q->the_post();
                wc_get_template_part('content', 'product');
            endwhile;
            woocommerce_product_loop_end();

            the_posts_pagination([
                'prev_text' => esc_html__('Previous','website-flexi'),
                'next_text' => esc_html__('Next','website-flexi'),
            ]);
        else :
            echo '<p>' . esc_html__('No products yet.','website-flexi') . '</p>';
        endif;

        wp_reset_postdata();
        ?>
    </div>












    <!-- TAB PLACEHOLDERS -->
            <div class="vendor-tab-content" id="vendor-tab-reviews">

<?php
global $wpdb;

$current_user_id = get_current_user_id();
$is_logged_in    = is_user_logged_in();

$already_reviewed = 0;
$can_review       = false;
$reason_msg       = '';

if ( $is_logged_in ) {

    // هل قيّم قبل كده؟
    $already_reviewed = (int) $wpdb->get_var( $wpdb->prepare("
        SELECT COUNT(*)
        FROM {$wpdb->comments} c
        INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
        WHERE c.user_id = %d
          AND c.comment_type = 'vendor_review'
          AND cm.meta_key = 'vendor_id'
          AND cm.meta_value = %d
    ", $current_user_id, $vendor_id ) );

    // هل عنده Order نهائي مع منتجات التاجر؟
    if ( $already_reviewed === 0 ) {

        $allowed_statuses = ['completed', 'refunded', 'failed'];
        $orders = wc_get_orders([
            'customer_id' => $current_user_id,
            'status'      => $allowed_statuses,
            'limit'       => -1,
        ]);

        foreach ( $orders as $order ) {
            foreach ( $order->get_items() as $item ) {
                $product_id = $item->get_product_id();
                if ( (int) get_post_field('post_author', $product_id) === (int) $vendor_id ) {
                    $can_review = true;
                    break 2;
                }
            }
        }

        if ( ! $can_review ) {
            $reason_msg = __('You can only review vendors after a completed (final) order.', 'website-flexi');
        }
    }
}
?>

<?php if ( ! $is_logged_in ) : ?>

    <p><?php esc_html_e('Please log in to leave a review.', 'website-flexi'); ?></p>

<?php else : ?>

    <?php if ( $already_reviewed > 0 ) : ?>
        <p><?php esc_html_e('You already reviewed this vendor.', 'website-flexi'); ?></p>
    <?php elseif ( ! $can_review ) : ?>
        <p><?php echo esc_html($reason_msg); ?></p>
    <?php else : ?>

        <form id="vendor-review-form" class="vendor-review-form">
            <h4><?php esc_html_e('Leave a Review', 'website-flexi'); ?></h4>

            <div class="vendor-stars">
                <?php for ( $i = 5; $i >= 1; $i-- ) : ?>
                    <input type="radio" name="rating" value="<?php echo (int)$i; ?>" id="star-<?php echo (int)$i; ?>">
                    <label for="star-<?php echo (int)$i; ?>">★</label>
                <?php endfor; ?>
            </div>

            <textarea name="comment" required
                placeholder="<?php esc_attr_e('Write your review…', 'website-flexi'); ?>"></textarea>

            <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor_id); ?>">
            <input type="hidden" name="action" value="wf_submit_vendor_review">
            <?php wp_nonce_field('wf_vendor_review', 'wf_vendor_review_nonce'); ?>

            <button type="submit" class="button">
                <?php esc_html_e('Submit Review', 'website-flexi'); ?>
            </button>
        </form>
<script>
document.addEventListener('submit', function(e) {

    if (!e.target.classList.contains('vendor-review-form')) return;

    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);

    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(res => res.json())
    .then(res => {
        if (!res.success) {
            alert(res.data?.message || 'Error');
            return;
        }

        // تحديث الصفحة أو إضافة الريفيو مباشرة
       setTimeout(() => location.reload(), 400);

    })
    .catch(() => alert('AJAX error'));
});
</script>

    <?php endif; ?>

<?php endif; ?>

<hr>










<div id="vendor-reviews-list">

<?php

$reviews_per_page = 5;
$page   = max(1, get_query_var('review_page') ?: 1);
$offset = ($page - 1) * $reviews_per_page;


/* =========================
   Get Reviews
========================= */

$reviews = $wpdb->get_results( $wpdb->prepare("
    SELECT c.*
    FROM {$wpdb->comments} c
    INNER JOIN {$wpdb->commentmeta} cm 
        ON c.comment_ID = cm.comment_id
    WHERE c.comment_type = 'vendor_review'
      AND c.comment_approved = 1
      AND cm.meta_key = 'vendor_id'
      AND cm.meta_value = %d
    ORDER BY c.comment_date_gmt DESC
    LIMIT %d OFFSET %d
", 
$vendor_id, 
$reviews_per_page, 
$offset ) );



if ( $reviews ) :

    foreach ( $reviews as $review ) :


        /* =========================
           Rating
        ========================= */

        $rating = (int) get_comment_meta(
            $review->comment_ID,
            'rating',
            true
        );


        /* =========================
           Is Vendor Owner
        ========================= */

        $is_vendor_owner = (
            get_current_user_id() === (int) $vendor_id
        );


        /* =========================
           Has User Reported?
        ========================= */

        $has_reported = false;

        if ( is_user_logged_in() ) {

            $has_reported = (bool) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id
                     FROM {$wpdb->prefix}wf_reports
                     WHERE report_type = 'review'
                       AND object_id = %d
                       AND reported_by = %d",
                    $review->comment_ID,
                    get_current_user_id()
                )
            );
        }


        /* =========================
           Vendor Reply
        ========================= */

        $replies = get_comments([
            'comment_type' => 'vendor_reply',
            'parent'       => (int) $review->comment_ID,
            'post_id'      => (int) $review->comment_post_ID,
            'user_id'      => (int) $vendor_id,
            'status'       => 'approve',
            'number'       => 1,
        ]);

        $has_reply = ! empty($replies);
        $reply     = $has_reply ? $replies[0] : null;

?>


<div class="vendor-review-item" data-review-id="<?php echo esc_attr($review->comment_ID); ?>">


<div class="vendor-review-header">
    <strong class="review-author"><?php echo esc_html( $review->comment_author ); ?></strong>

    <div class="stars">
        <?php echo esc_html( str_repeat('★', max(0, min(5, $rating))) ); ?>
    </div>
</div>

<div class="review-body">
    <p><?php echo esc_html( $review->comment_content ); ?></p>




 <!-- 🚩 Report Review -->
<?php if ( is_user_logged_in() && (int) $review->user_id !== get_current_user_id() ) : ?>

    <?php if ( $has_reported ) : ?>

        <span class="review-reported" style="color:#2e7d32;font-weight:600;">
            ✔ <?php esc_html_e('Reported', 'website-flexi'); ?>
        </span>

    <?php else : ?>

        <button
            type="button"
            class="review-action report wf-open-report-modal"
            data-review-id="<?php echo esc_attr($review->comment_ID); ?>">
            🚩 <?php esc_html_e('Report', 'website-flexi'); ?>
        </button>

    <?php endif; ?>

<?php endif; ?>











    <!-- ✅ Vendor Reply (عرض الرد) -->
   <?php if ( $has_reply && $reply ) : ?>
    <div class="vendor-reply" data-reply-id="<?php echo esc_attr($reply->comment_ID); ?>">
        <strong><?php esc_html_e('Store Reply', 'website-flexi'); ?></strong>

        <div class="vendor-reply-text">
            <?php echo esc_html( $reply->comment_content ); ?>
        </div>

        <?php if ( $is_vendor_owner ) : ?>
            <div class="vendor-reply-actions">
                <button type="button" class="reply-action edit-reply">
                        ✏️ <?php esc_html_e('Edit', 'website-flexi'); ?>
                    </button>
    
                    <button type="button" class="reply-action delete-reply">
                        ❌ <?php esc_html_e('Delete', 'website-flexi'); ?>
                    </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>


    <!-- ✅ Vendor Reply Form -->
    <?php if ( $is_vendor_owner && ! $has_reply ) : ?>
       <form class="vendor-reply-form" method="post">
            <textarea name="reply" required></textarea>
        
            <input type="hidden" name="review_id" value="<?php echo esc_attr($review->comment_ID); ?>">
            <input type="hidden" name="action" value="wf_submit_vendor_reply">
            <?php wp_nonce_field('wf_vendor_reply', 'wf_vendor_reply_nonce'); ?>
        
            <button type="submit" class="button small">
                <?php esc_html_e('Reply', 'website-flexi'); ?>
            </button>
        </form>

    <?php endif; ?>

  
</div>
</div>

<?php
    endforeach;
else :
    echo '<p>' . esc_html__('No reviews yet.', 'website-flexi') . '</p>';
endif;
?>
</div>
















<script>
document.addEventListener('submit', function(e) {

    if (!e.target.classList.contains('vendor-reply-form')) return;

    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);

    fetch('<?php echo esc_url( admin_url('admin-ajax.php') ); ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(res => res.json())
    .then(res => {

        if (!res.success) {
            alert(res.data?.message || 'Error');
            return;
        }

        const reviewItem = form.closest('.vendor-review-item');
        if (!reviewItem) {
            console.error('vendor-review-item not found');
            return;
        }

        const replyDiv = document.createElement('div');
        replyDiv.className = 'vendor-reply';
        replyDiv.dataset.replyId = res.data.reply_id;

        replyDiv.innerHTML = `
            <strong>Store Reply</strong>
            <div class="vendor-reply-text">${res.data.reply}</div>
            <div class="vendor-reply-actions">
                <button type="button" class="reply-action edit-reply">✏️ Edit</button>
                <button type="button" class="reply-action delete-reply">❌ Delete</button>
            </div>
        `;

        reviewItem.appendChild(replyDiv);
        form.remove();
    })
    .catch(err => {
        console.error(err);
        alert('AJAX error');
    });
});





document.addEventListener('click', function(e){

    /* =====================
       EDIT
    ===================== */
    const editBtn = e.target.closest('.edit-reply');
    if (editBtn) {
        e.preventDefault();

        const wrapper = editBtn.closest('.vendor-reply');
        const textEl  = wrapper.querySelector('.vendor-reply-text');

        const textarea = document.createElement('textarea');
        textarea.value = textEl.innerText.trim();
        textarea.style.width = '100%';

        textEl.replaceWith(textarea);

        editBtn.textContent = '💾 Save';
        editBtn.classList.remove('edit-reply');
        editBtn.classList.add('save-reply');

        textarea.focus();
        return;
    }

    /* =====================
       SAVE
    ===================== */
    const saveBtn = e.target.closest('.save-reply');
    if (saveBtn) {
        e.preventDefault();

        const wrapper  = saveBtn.closest('.vendor-reply');
        const replyId  = wrapper.dataset.replyId;
        const textarea = wrapper.querySelector('textarea');

        const data = new FormData();
        data.append('action', 'wf_edit_vendor_reply');
        data.append('reply_id', replyId);
        data.append('reply', textarea.value);
        data.append('wf_vendor_reply_nonce', '<?php echo wp_create_nonce('wf_vendor_reply'); ?>');

        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(r => r.json())
        .then(r => {
            if (r.success) {
                const newText = textarea.value;
                    const div = document.createElement('div');
                    div.className = 'vendor-reply-text';
                    div.innerText = newText;
                    
                    textarea.replaceWith(div);
                    
                    saveBtn.textContent = 'Edit';
                    saveBtn.classList.remove('save-reply');
                    saveBtn.classList.add('edit-reply');

            } else {
                alert(r.data?.message || 'Save failed');
            }
        });
        return;
    }

    /* =====================
       DELETE
    ===================== */
    const deleteBtn = e.target.closest('.delete-reply');
    if (deleteBtn) {
        e.preventDefault();

        const wrapper = deleteBtn.closest('.vendor-reply');
        const replyId = wrapper.dataset.replyId;

        if (!confirm('Delete this reply?')) return;

        const data = new FormData();
        data.append('action', 'wf_delete_vendor_reply');
        data.append('reply_id', replyId);
        data.append('wf_vendor_reply_nonce', '<?php echo wp_create_nonce('wf_vendor_reply'); ?>');

        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(r => r.json())
        .then(r => {
            if (r.success) {
                wrapper.remove();
            } else {
                alert(r.data?.message || 'Delete failed');
            }
        });
        
        return;
    }
});

document.addEventListener('click', function(e){

    /* Open modal */
    const btn = e.target.closest('.wf-open-report-modal');
    if (btn) {
        document.getElementById('wf-report-review-id').value = btn.dataset.reviewId;
        document.getElementById('wf-report-modal').style.display = 'block';
    }

    /* Close modal */
    if (
        e.target.classList.contains('wf-report-cancel') ||
        e.target.classList.contains('wf-report-backdrop')
    ) {
        document.getElementById('wf-report-modal').style.display = 'none';
    }
});

/* Submit report */
document.addEventListener('submit', function(e){

    if (!e.target || e.target.id !== 'wf-report-form') return;

    e.preventDefault();

    const form = e.target;
    const data = new FormData(form);

    console.log('Submitting report', Object.fromEntries(data.entries()));

    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(r => r.json())
    .then(r => {
        console.log('AJAX response', r);

        if (r.success) {
            alert('Report submitted successfully');
            document.getElementById('wf-report-modal').style.display = 'none';
        } else {
            alert(r.data?.message || 'Report failed');
        }
    })
    .catch(err => {
        console.error('AJAX error', err);
        alert('AJAX error');
    });

});

</script>



<div id="wf-report-modal" style="display:none;">
    <div class="wf-report-backdrop"></div>

    <div class="wf-report-modal-box">
        <h3><?php esc_html_e('Report Review', 'website-flexi'); ?></h3>

        <form id="wf-report-form">

            <input type="hidden" name="review_id" id="wf-report-review-id">
            <input type="hidden" name="action" value="wf_report_review">
            <?php wp_nonce_field('wf_report_review', 'wf_report_review_nonce'); ?>

            <label>
                <?php esc_html_e('Reason', 'website-flexi'); ?>
                <select name="reason" required>
                    <option value="spam"><?php esc_html_e('Spam', 'website-flexi'); ?></option>
                    <option value="abuse"><?php esc_html_e('Abuse', 'website-flexi'); ?></option>
                    <option value="fake"><?php esc_html_e('Fake review', 'website-flexi'); ?></option>
                </select>
            </label>

            <label>
                <?php esc_html_e('Comment (optional)', 'website-flexi'); ?>
                <textarea name="comment" rows="3"></textarea>
            </label>

            <div class="wf-report-actions">
                <button type="submit" class="button button-primary">
                    <?php esc_html_e('Submit Report', 'website-flexi'); ?>
                </button>

                <button type="button" class="button wf-report-cancel">
                    <?php esc_html_e('Cancel', 'website-flexi'); ?>
                </button>
            </div>
        </form>
    </div>
</div>


</div>







        <div class="vendor-tab-content" id="vendor-tab-stats">
            <?php
$vendor_id = (int) $vendor_id;

/* =========================
   PRODUCTS
========================= */
$products = get_posts([
    'post_type'   => 'product',
    'post_status' => ['publish', 'pending', 'draft'],
    'author'      => $vendor_id,
    'numberposts' => -1,
]);

$total_products = count($products);

$published_products = count(
    array_filter($products, fn($p) => $p->post_status === 'publish')
);

/* =========================
   ORDERS
========================= */
$orders = wc_get_orders([
    'limit' => -1,
    'status'=> ['completed', 'refunded', 'failed', 'cancelled'],
]);

$total_orders     = 0;
$completed_orders = 0;
$refunded_orders  = 0;

foreach ($orders as $order) {
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        if ((int) get_post_field('post_author', $product_id) === $vendor_id) {
            $total_orders++;

            if ($order->has_status('completed')) {
                $completed_orders++;
            }

            if ($order->has_status('refunded')) {
                $refunded_orders++;
            }
            break;
        }
    }
}

/* =========================
   RATIOS
========================= */
$success_rate  = $total_orders > 0 ? round(($completed_orders / $total_orders) * 100) : 0;
$refund_rate   = $total_orders > 0 ? round(($refunded_orders / $total_orders) * 100) : 0;

/* =========================
   REVIEWS
========================= */
$reviews_count = (int) $wpdb->get_var($wpdb->prepare("
    SELECT COUNT(*)
    FROM {$wpdb->comments} c
    INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id
    WHERE c.comment_type = 'vendor_review'
      AND cm.meta_key = 'vendor_id'
      AND cm.meta_value = %d
", $vendor_id));

$avg_rating = (float) $wpdb->get_var("
    SELECT AVG(meta_value)
    FROM {$wpdb->commentmeta}
    WHERE meta_key = 'rating'
      AND comment_id IN (
          SELECT comment_ID
          FROM {$wpdb->comments}
          WHERE comment_type = 'vendor_review'
      )
");


$avg_rating = $avg_rating ? number_format($avg_rating, 1) : '—';

/* =========================
   TRUST LABEL
========================= */
if ($total_orders >= 10 && $success_rate >= 85) {
    $trust_label = '🟢 Trusted Seller';
} elseif ($total_orders < 5) {
    $trust_label = '🟡 New Seller';
} else {
    $trust_label = '🔴 Needs Review';
}
?>







<div class="vendor-stats-grid">

    <div class="stat-box" data-tooltip="<?php esc_attr_e('Total number of products published by this vendor.', 'website-flexi'); ?>">
        <strong><?php echo esc_html($total_products); ?></strong>
        <span><?php esc_html_e('Products', 'website-flexi'); ?></span>
    </div>

    <div class="stat-box" data-tooltip="<?php esc_attr_e('Total orders placed for this vendor\'s products.', 'website-flexi'); ?>">
        <strong><?php echo esc_html($total_orders); ?></strong>
        <span><?php esc_html_e('Total Orders', 'website-flexi'); ?></span>
    </div>

    <div class="stat-box" data-tooltip="<?php esc_attr_e('Orders that were successfully completed without issues.', 'website-flexi'); ?>">
        <strong><?php echo esc_html($completed_orders); ?></strong>
        <span><?php esc_html_e('Completed Orders', 'website-flexi'); ?></span>
    </div>

    <div class="stat-box" data-tooltip="<?php esc_attr_e('Orders that were refunded or returned by customers.', 'website-flexi'); ?>">
        <strong><?php echo esc_html($refunded_orders); ?></strong>
        <span><?php esc_html_e('Refunded Orders', 'website-flexi'); ?></span>
    </div>

    <div class="stat-box" data-tooltip="<?php esc_attr_e('Percentage of successful orders compared to total orders.', 'website-flexi'); ?>">
        <strong><?php echo esc_html($success_rate); ?>%</strong>
        <span><?php esc_html_e('Success Rate', 'website-flexi'); ?></span>
    </div>

    <div class="stat-box" data-tooltip="<?php esc_attr_e('Average rating given by customers who reviewed this vendor.', 'website-flexi'); ?>">
        <strong><?php echo esc_html($avg_rating); ?> ★</strong>
        <span><?php esc_html_e('Rating', 'website-flexi'); ?></span>
    </div>

</div>

<p class="vendor-trust-label">
    <?php echo esc_html($trust_label); ?>
</p>








<div class="vendor-stats-guide">

    <h4 class="vendor-stats-guide-title">
        <?php esc_html_e('How to read these numbers', 'website-flexi'); ?>
    </h4>

    <p class="vendor-stats-guide-intro">
        <?php esc_html_e('These indicators help you understand the vendor’s activity and reliability. Use them together (not one number alone) to form a fair impression.', 'website-flexi'); ?>
    </p>

    <ul class="vendor-stats-guide-list">
        <li>
            <strong><?php esc_html_e('Products:', 'website-flexi'); ?></strong>
            <?php esc_html_e('A higher number usually means the vendor is active. New vendors may have fewer products — this is normal.', 'website-flexi'); ?>
        </li>

        <li>
            <strong><?php esc_html_e('Total Orders:', 'website-flexi'); ?></strong>
            <?php esc_html_e('Shows overall demand and experience. If it is low, the vendor may be new — rely more on rating and success rate.', 'website-flexi'); ?>
        </li>

        <li>
            <strong><?php esc_html_e('Completed Orders:', 'website-flexi'); ?></strong>
            <?php esc_html_e('The more completed orders, the more proven the vendor’s ability to deliver successfully.', 'website-flexi'); ?>
        </li>

        <li>
            <strong><?php esc_html_e('Refunded Orders:', 'website-flexi'); ?></strong>
            <?php esc_html_e('Refunds can happen for many reasons. A high number compared to completed orders may indicate repeated issues.', 'website-flexi'); ?>
        </li>

        <li>
            <strong><?php esc_html_e('Success Rate:', 'website-flexi'); ?></strong>
            <?php esc_html_e('This is one of the most important indicators. Higher success rate generally means higher reliability.', 'website-flexi'); ?>
        </li>

        <li>
            <strong><?php esc_html_e('Rating:', 'website-flexi'); ?></strong>
            <?php esc_html_e('Average rating from customers. Read reviews when available to understand the reasons behind ratings.', 'website-flexi'); ?>
        </li>
    </ul>

    <div class="vendor-stats-guide-tip">
        <strong><?php esc_html_e('Tip:', 'website-flexi'); ?></strong>
        <?php esc_html_e('If the vendor is new (few orders/products), don’t judge too quickly. Check the rating, review details, and communication quality before deciding.', 'website-flexi'); ?>
    </div>

</div>



        </div>





























        <div class="vendor-tab-content" id="vendor-tab-report">
<?php
$current_user_id = get_current_user_id();
$can_report_vendor = false;
$vendor_already_reported = false;
$vendor_report_msg = '';
// 🚫 منع التاجر من الإبلاغ عن نفسه
if ( is_user_logged_in() && (int) $current_user_id === (int) $vendor_id ) {

    $vendor_report_msg = __('You cannot report your own store.', 'website-flexi');
    $can_report_vendor = false;
}


if ( ! is_user_logged_in() ) {

    $vendor_report_msg = __('Please log in to report this vendor.', 'website-flexi');

} else {

    global $wpdb;

    // هل عمل Order من التاجر قبل كده؟
    $orders = wc_get_orders([
        'customer_id' => $current_user_id,
        'limit'       => -1,
    ]);

    foreach ( $orders as $order ) {
        foreach ( $order->get_items() as $item ) {
            $product_id = $item->get_product_id();
            if ( (int) get_post_field('post_author', $product_id) === (int) $vendor_id ) {
                $can_report_vendor = true;
                break 2;
            }
        }
    }

    if ( ! $can_report_vendor ) {
        $vendor_report_msg = __('You must have at least one order from this vendor to submit a report.', 'website-flexi');
    }

    // هل بلّغ قبل كده؟
    $table = $wpdb->prefix . 'wf_reports';
    $vendor_already_reported = (bool) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM {$table}
             WHERE report_type = 'vendor'
             AND object_id = %d
             AND reported_by = %d",
            $vendor_id,
            $current_user_id
        )
    );
}
?>




    <h4><?php esc_html_e('Report this vendor', 'website-flexi'); ?></h4>

    <?php if ( ! is_user_logged_in() || ! $can_report_vendor ) : ?>

        <p class="vendor-report-info">
            <?php echo esc_html($vendor_report_msg); ?>
        </p>

    <?php elseif ( $vendor_already_reported ) : ?>

        <p class="vendor-report-success">
            ✔ <?php esc_html_e('You have already reported this vendor.', 'website-flexi'); ?>
        </p>

    <?php else : ?>

        <form id="wf-vendor-report-form">

            <p class="vendor-report-hint">
                <?php esc_html_e('Select all reasons that apply. Your report helps us keep the marketplace safe.', 'website-flexi'); ?>
            </p>

            <div class="vendor-report-reasons">
                <?php
                $reasons = [
                    'unprofessional' => __('Unprofessional communication', 'website-flexi'),
                    'late_delivery'  => __('Late delivery', 'website-flexi'),
                    'not_as_desc'    => __('Product not as described', 'website-flexi'),
                    'order_issues'   => __('Repeated order issues', 'website-flexi'),
                    'refund_problem' => __('Refund problems', 'website-flexi'),
                    'misleading'     => __('Fake or misleading information', 'website-flexi'),
                    'other'          => __('Other', 'website-flexi'),
                ];

                foreach ( $reasons as $key => $label ) :
                ?>
                    <label>
                        <input type="checkbox" name="reasons[]" value="<?php echo esc_attr($key); ?>">
                        <?php echo esc_html($label); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <textarea
                name="comment"
                placeholder="<?php esc_attr_e('Additional details (optional)', 'website-flexi'); ?>"
                rows="4"></textarea>

            <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor_id); ?>">
            <input type="hidden" name="action" value="wf_report_vendor">
            <?php wp_nonce_field('wf_report_vendor', 'wf_report_vendor_nonce'); ?>

            <button type="submit" class="button button-danger">
                🚩 <?php esc_html_e('Submit Vendor Report', 'website-flexi'); ?>
            </button>

        </form>

    <?php endif; ?>

</div>

















        </div>





















    </div>


<script>
document.querySelectorAll('.vendor-tabs button').forEach(btn=>{
    btn.addEventListener('click',()=>{
        document.querySelectorAll('.vendor-tabs button').forEach(b=>b.classList.remove('active'));
        document.querySelectorAll('.vendor-tab-content').forEach(c=>c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('vendor-tab-'+btn.dataset.tab).classList.add('active');
    });
});

document.addEventListener('click', function(e){

    const btn = e.target.closest('#load-more-reviews');
    if (!btn) return;

    const page   = btn.dataset.page;
    const vendor = btn.dataset.vendor;

    btn.textContent = 'Loading...';

    const data = new FormData();
    data.append('action', 'wf_load_more_reviews');
    data.append('vendor_id', vendor);
    data.append('page', page);

    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(res => res.text())
    .then(html => {

        if (!html.trim()) {
            btn.remove();
            return;
        }

        document.getElementById('vendor-reviews-list')
            .insertAdjacentHTML('beforeend', html);

        btn.dataset.page = parseInt(page) + 1;
        btn.textContent = 'Load more reviews';
    });
});

document.addEventListener('submit', function(e){

    const form = e.target;

    if (form.id !== 'wf-vendor-report-form') return;

    e.preventDefault();

    const data = new FormData(form);
    const checked = form.querySelectorAll('input[name="reasons[]"]:checked');
if (!checked.length) {
    alert('<?php echo esc_js(__('Please select at least one reason.', 'website-flexi')); ?>');
    return;
}


    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(r => r.json())
    .then(r => {

        if (!r.success) {
            alert(r.data?.message || 'Report failed');
            return;
        }

        alert('<?php echo esc_js(__('Vendor reported successfully.', 'website-flexi')); ?>');

        // تحديث الواجهة
        form.innerHTML = `
            <p class="vendor-report-success">
                ✔ <?php echo esc_js(__('You have already reported this vendor.', 'website-flexi')); ?>
            </p>
        `;
    })
    .catch(() => alert('AJAX error'));
});



</script>
















<style>
.vendor-cover{height:280px;background-size:cover;background-position:center}
.vendor-profile-card{display:flex;gap:25px;margin:-70px auto 40px;background:#fff;padding:25px;border-radius:14px;max-width:1200px;box-shadow:0 10px 30px rgba(0,0,0,.06)}
.vendor-logo img{width:150px;height:150px;border-radius:50%;border:6px solid #fff;background:#fff}
.vendor-verified{color:#1877f2;font-size:18px;margin-inline-start:8px}
.vendor-rating{display:flex;align-items:center;gap:6px}
.vendor-bio{margin:12px 0;color:#555;max-width:700px}
.vendor-meta{display:flex;gap:18px;flex-wrap:wrap;font-size:14px;color:#777}
.vendor-tabs{display:flex;gap:10px;border-bottom:1px solid #eee;margin-bottom:30px}
.vendor-tabs button{background:none;border:none;padding:12px 16px;font-weight:600;cursor:pointer}
.vendor-tabs button.active{border-bottom:3px solid #000}
.vendor-tab-content{display:none}
.vendor-tab-content.active{display:block}
.vendor-review-item {
    border-bottom: 1px solid #eee;
    padding: 16px 0;
}

.vendor-review-rating {
    margin-bottom: 6px;
}

.vendor-review-text {
    color: #555;
    margin: 6px 0;
}

.vendor-review-author {
    font-size: 12px;
    color: #888;
}


.vendor-stars {
    display:flex;
    gap:6px;
    direction: rtl;
}
.vendor-stars input { display:none; }
.vendor-stars label {
    font-size:26px;
    cursor:pointer;
    color:#ccc;
}
.vendor-stars input:checked ~ label,
.vendor-stars label:hover,
.vendor-stars label:hover ~ label {
    color:#f5b301;
}
.vendor-review-item {
    margin-bottom:16px;
    padding-bottom:12px;
    border-bottom:1px solid #eee;
}

/* =========================
   Vendor Report UI
========================= */

#vendor-tab-report h4{
  margin: 0 0 12px;
  font-size: 16px;
  font-weight: 700;
  line-height: 1.3;
}

/* Info / Success messages */
#vendor-tab-report .vendor-report-info,
#vendor-tab-report .vendor-report-success{
  margin: 12px 0 16px;
  padding: 12px 14px;
  border-radius: 10px;
  font-size: 13px;
  line-height: 1.5;
  border: 1px solid transparent;
}

#vendor-tab-report .vendor-report-info{
  border-color: rgba(0,0,0,.08);
  background: rgba(0,0,0,.03);
}

#vendor-tab-report .vendor-report-success{
  border-color: rgba(46,125,50,.25);
  background: rgba(46,125,50,.10);
  color: #1b5e20;
  font-weight: 600;
}

/* Hint */
#vendor-tab-report .vendor-report-hint{
  margin: 0 0 12px;
  padding: 10px 12px;
  border-radius: 10px;
  font-size: 13px;
  line-height: 1.5;
  border: 1px dashed rgba(0,0,0,.15);
  background: rgba(0,0,0,.02);
}

/* Reasons wrapper */
#vendor-tab-report .vendor-report-reasons{
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin: 12px 0 14px;
}

/* Each reason label as a “pill card” */
#vendor-tab-report .vendor-report-reasons label{
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,.10);
  background: rgba(255,255,255,.04);
  cursor: pointer;
  user-select: none;
  transition: transform .12s ease, border-color .12s ease, background .12s ease;
}

#vendor-tab-report .vendor-report-reasons label:hover{
  transform: translateY(-1px);
  border-color: rgba(0,0,0,.18);
  background: rgba(255,255,255,.07);
}

/* Checkbox styling (modern) */
#vendor-tab-report .vendor-report-reasons input[type="checkbox"]{
  width: 18px;
  height: 18px;
  margin-top: 2px;
  accent-color: #d32f2f; /* red accent for report */
}

/* Textarea */
#vendor-tab-report textarea[name="comment"]{
  width: 100%;
  min-height: 110px;
  resize: vertical;
  padding: 12px 12px;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.15);
  background: rgba(255,255,255,.04);
  font-size: 13px;
  line-height: 1.5;
  outline: none;
  transition: border-color .12s ease, box-shadow .12s ease, background .12s ease;
}

#vendor-tab-report textarea[name="comment"]:focus{
  border-color: rgba(211,47,47,.45);
  box-shadow: 0 0 0 3px rgba(211,47,47,.12);
  background: rgba(255,255,255,.06);
}

/* Submit button - keep WP button look but enhance */
#vendor-tab-report .button.button-danger{
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin-top: 12px;
  padding: 8px 14px;
  border-radius: 10px;
  font-weight: 700;
  border: 1px solid rgba(211,47,47,.55);
  background: rgba(211,47,47,.12);
  color: #b71c1c;
  transition: transform .12s ease, background .12s ease, border-color .12s ease;
}

#vendor-tab-report .button.button-danger:hover{
  transform: translateY(-1px);
  background: rgba(211,47,47,.18);
  border-color: rgba(211,47,47,.75);
}

#vendor-tab-report .button.button-danger:active{
  transform: translateY(0);
}

/* Responsive */
@media (max-width: 600px){
  #vendor-tab-report .vendor-report-reasons{
    grid-template-columns: 1fr;
  }

  #vendor-tab-report .button.button-danger{
    width: 100%;
    justify-content: center;
  }
}


.vendor-verified-badge {
    position: relative;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.vendor-verified-badge::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    background: #111;
    color: #fff;
    padding: 6px 10px;
    font-size: 12px;
    border-radius: 6px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: 0.2s ease;
}

.vendor-verified-badge:hover::after {
    opacity: 1;
}





#wf-report-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
}

.wf-report-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.6);
}

.wf-report-modal-box {
    position: relative;
    max-width: 420px;
    background: #fff;
    margin: 10vh auto;
    padding: 20px;
    border-radius: 6px;
    z-index: 2;
}

.wf-report-modal-box select,
.wf-report-modal-box textarea {
    width: 100%;
    margin-top: 4px;
}



.vendor-stats-guide {
    margin-top: 16px;
    padding: 14px 14px;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px;
    background: rgba(255,255,255,.04);
}

.vendor-stats-guide-title {
    margin: 0 0 8px;
    font-size: 15px;
    font-weight: 700;
}

.vendor-stats-guide-intro {
    margin: 0 0 10px;
    opacity: .9;
    font-size: 13px;
    line-height: 1.6;
}

.vendor-stats-guide-list {
    margin: 0;
    padding: 0 0 0 18px;
    display: grid;
    gap: 8px;
}

.vendor-stats-guide-list li {
    font-size: 13px;
    line-height: 1.6;
    opacity: .95;
}

.vendor-stats-guide-list strong {
    display: inline-block;
    margin-right: 6px;
    font-weight: 700;
}

.vendor-stats-guide-tip {
    margin-top: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,.06);
    font-size: 13px;
    line-height: 1.6;
}

.vendor-stats-guide-tip strong {
    margin-right: 6px;
}

/* Mobile */
@media (max-width: 600px) {
    .vendor-stats-guide {
        padding: 12px;
    }

    .vendor-stats-guide-title {
        font-size: 14px;
    }

    .vendor-stats-guide-intro,
    .vendor-stats-guide-list li,
    .vendor-stats-guide-tip {
        font-size: 12.5px;
    }
}






.vendor-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(140px,1fr));
    gap: 15px;
    margin-top: 15px;
}

.stat-box {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
}

.stat-box strong {
    display: block;
    font-size: 22px;
    margin-bottom: 5px;
}

.vendor-trust-label {
    margin-top: 15px;
    font-weight: 600;
}

.stat-box {
    position: relative;
    cursor: help;
}

.stat-box::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 115%;
    left: 50%;
    transform: translateX(-50%);
    background: #222;
    color: #fff;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 1.4;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity .2s ease;
    z-index: 999;
}

.stat-box::before {
    content: "";
    position: absolute;
    bottom: 105%;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-top-color: #222;
    opacity: 0;
    transition: opacity .2s ease;
}

.stat-box:hover::after,
.stat-box:hover::before {
    opacity: 1;
}








/* ===============================
   REVIEW CARD (Facebook style)
================================ */
.vendor-review-item {
    background: #fff;
    border: 1px solid #e4e6eb;
    border-radius: 10px;
    padding: 14px;
    margin-bottom: 14px;
    font-size: 14px;
}

/* Header */
.vendor-review-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.review-author {
    font-weight: 600;
    color: #050505;
}

.stars {
    color: #f7b928;
    font-size: 13px;
}

/* Body */
.review-body {
    margin-top: 6px;
    color: #1c1e21;
}

/* ===============================
   ACTIONS (Report / Edit / Delete)
================================ */
.review-action,
.reply-action {
    background: none;
    border: none;
    color: #65676b;
    font-size: 13px;
    cursor: pointer;
    padding: 4px 6px;
}

.review-action:hover,
.reply-action:hover {
    background: #f0f2f5;
    border-radius: 6px;
}

/* ===============================
   VENDOR REPLY (Nested comment)
================================ */
.vendor-reply {
    background: #f0f2f5;
    border-radius: 10px;
    padding: 10px 12px;
    margin-top: 10px;
    margin-left: 32px;
}

.vendor-reply strong {
    font-size: 13px;
    color: #050505;
}

.vendor-reply-text {
    margin-top: 4px;
    font-size: 13px;
    color: #1c1e21;
}

/* Actions under reply */
.vendor-reply-actions {
    margin-top: 4px;
}

/* ===============================
   REPLY FORM
================================ */
.vendor-reply-form textarea,
.vendor-review-form textarea {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #ccd0d5;
    padding: 8px;
    font-size: 14px;
}

.vendor-reply-form button,
.vendor-review-form button {
    margin-top: 6px;
    background: #1877f2;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 13px;
    cursor: pointer;
}

.vendor-reply-form button:hover,
.vendor-review-form button:hover {
    background: #166fe5;
}

/* ===============================
   MOBILE
================================ */
@media (max-width: 600px) {
    .vendor-reply {
        margin-left: 16px;
    }
}







/* ===============================
   Vendor Page – Responsive
================================ */

/* Tablets */
@media (max-width: 992px) {

    .vendor-profile-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-top: -60px;
    }

    .vendor-logo img {
        width: 130px;
        height: 130px;
    }

    .vendor-info h1 {
        font-size: 22px;
    }

    .vendor-meta {
        justify-content: center;
    }
    .vendor-page { padding-top:0px;}
}

/* Mobile */
@media (max-width: 600px) {

    .vendor-cover {
        height: 200px;
    }

    .vendor-profile-card {
        padding: 18px;
        margin: -50px 12px 30px;
        border-radius: 12px;
    }

    .vendor-logo img {
        width: 110px;
        height: 110px;
        border-width: 4px;
    }

    .vendor-info h1 {
        font-size: 20px;
        line-height: 1.3;
    }

    .vendor-verified {
        font-size: 16px;
    }

    .vendor-rating {
        justify-content: center;
    }

    .vendor-bio {
        font-size: 14px;
        line-height: 1.6;
    }

    .vendor-meta {
        flex-direction: column;
        gap: 8px;
        align-items: center;
        font-size: 13px;
    }

    /* Tabs */
    .vendor-tabs {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 5px;
    }

    .vendor-tabs button {
        padding: 10px 14px;
        font-size: 14px;
        flex: 0 0 auto;
    }

    /* Products grid fix */
    .woocommerce ul.products {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Very small phones */
@media (max-width: 400px) {

    .vendor-info h1 {
        font-size: 18px;
    }

    .woocommerce ul.products {
        grid-template-columns: 1fr;
    }
}

</style>






<?php
do_action('woocommerce_after_main_content');
get_footer();
