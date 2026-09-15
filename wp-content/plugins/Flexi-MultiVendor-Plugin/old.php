<?php

/* ===============================
   Helper: Build Table + Stats + Pagination
================================== */
function styliiiish_build_manage_products_content($paged = 1, $search = '', $cat = 0, $status_filter = '', $mode = 'owner') {

    $is_mobile = wp_is_mobile();
    $per_page  = $is_mobile ? 5 : 10;

    // detect mode
    if (isset($_POST['mode']) && in_array($_POST['mode'], ['owner','user'], true)) {
        $mode = sanitize_text_field($_POST['mode']);
    }

    $is_user = ($mode === 'user');

    $is_vendor_mode = ($mode === 'vendor');

// Logic for Vendor Mode (Customer Dresses)
if ($is_vendor_mode) {

    // 1) Vendor = all users EXCEPT managers/owners
    $manager_ids = get_option('styliiiish_allowed_manager_ids', []);
    if (empty($manager_ids)) {
        $manager_ids = [ get_current_user_id() ];
    }

    $base_args['author__not_in'] = $manager_ids;

    // 2) Product statuses allowed
    // customer dresses appear only when pending or draft
    if (!empty($status_filter)) {

        switch ($status_filter) {
            case 'pending':
                $base_args['post_status'] = ['pending'];
                break;

            case 'draft':
                $base_args['post_status'] = ['draft'];
                break;

            case 'uncomplete':
                $base_args['post_status'] = ['draft'];
                $base_args['meta_query'][] = [
                    'key'     => '_styliiiish_manual_deactivate',
                    'compare' => 'NOT EXISTS'
                ];
                break;

            case 'deactivated':
                $base_args['post_status'] = ['draft'];
                $base_args['meta_query'][] = [
                    'key'     => '_styliiiish_manual_deactivate',
                    'value'   => 'yes',
                    'compare' => '='
                ];
                break;
        }

    } else {
        // default vendor view (pending + draft)
        $base_args['post_status'] = ['pending', 'draft'];
    }
}


    /* ============================================
       Build base query args (OLD CLEAN LOGIC)
    ============================================ */
    // Determine allowed authors for OWNER MODE
        $allowed_ids = get_option('styliiiish_allowed_manager_ids', []);
        if (empty($allowed_ids)) {
             $allowed_ids = [ get_current_user_id() ];
}

    
        $base_args = [
            'post_type'      => 'product',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post__not_in'   => [1905, 1954],
        ];
        
        $base_args['meta_query'] = [];

        
        // Mode: user → يشوف منتجاته فقط
        if ($is_user) {
            $base_args['author'] = get_current_user_id();
        }
        
        // Mode: owner → يشوف كل allowed managers/users
        else {
            $allowed_ids = get_option('styliiiish_allowed_manager_ids', []);
            if (empty($allowed_ids)) {
                $allowed_ids = [ get_current_user_id() ];
            }
            $base_args['author__in'] = $allowed_ids;
        }

    // Search
    if (!empty($search)) {
        $base_args['s'] = $search;
    }

    // Category
    if ($cat > 0) {
        $base_args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cat,
            ]
        ];
    }


/* ============================================
   Handle Status Filter (USER LOGIC)
============================================ */
if ($is_user) {

    if (!empty($status_filter)) {

        switch ($status_filter) {

            case 'active':
                $base_args['post_status'] = ['publish'];
                break;

            case 'pending':
                $base_args['post_status'] = ['pending'];
                break;

            case 'deactivated':
                $base_args['post_status'] = ['draft'];
               $base_args['meta_query'][] = [
                'key'     => '_styliiiish_manual_deactivate',
                'value'   => 'yes',
                'compare' => '=',
            ];


                break;

            case 'uncomplete':
                $base_args['post_status'] = ['draft'];
               $base_args['meta_query'][] = [
                'key'     => '_styliiiish_manual_deactivate',
                'compare' => 'NOT EXISTS'
            ];

                break;
        }

    } else {

        // الافتراضى: كل الحالات ماعدا deactivated
        $base_args['post_status'] = ['publish', 'pending', 'draft'];
    }

} else {

    // OWNER MODE كما هو
    if (!empty($status_filter)) {
        $base_args['post_status'] = [$status_filter];
    } else {
        $base_args['post_status'] = ['publish', 'draft'];
    }
}


    /* ============================================
       Stats (OLD STATS CALL)
    ============================================ */
    $stats = styliiiish_get_products_stats($base_args);


/* ============================================
   USER Pretty Stats (NEW UI ONLY)
============================================ */
if ($is_user) {

    $user_id = get_current_user_id();

    // لازم ناخد كل المنتجات بدون post_status أو meta_query أو tax_query
    $all_products = get_posts([
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'author'         => $user_id,
        'post_status'    => ['publish', 'pending', 'draft'], // مهم جداً
    ]);

    $active      = 0;
    $pending     = 0;
    $uncomplete  = 0;
    $deactivated = 0;

    foreach ($all_products as $pid) {

        $status = get_post_status($pid);
        $manual = get_post_meta($pid, '_styliiiish_manual_deactivate', true) === 'yes';

        // موقوف يدويًا
        if ($manual) {
            $deactivated++;
            continue;
        }

        if ($status === 'publish') {
            $active++;
        }
        elseif ($status === 'pending') {
            $pending++;
        }
        elseif ($status === 'draft') {
            $uncomplete++;
        }
    }

    $stat_active_value      = $active;
    $stat_pending_value     = $pending;
    $stat_uncomplete_value  = $uncomplete;
    $stat_deactivated_value = $deactivated;
}


    /* ============================================
       LIST QUERY
    ============================================ */
    $query = new WP_Query($base_args);
    
    $total_products = $query->found_posts;
    $total_pages    = $query->max_num_pages;
    $offset         = ($paged - 1) * $per_page;

        
    $products = [];
    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $product = wc_get_product($post->ID);
            if ($product) $products[] = $product;
        }
    }


    /* ============================================
       OUTPUT START
    ============================================ */
    ob_start();
    
    include plugin_dir_path(__FILE__) . '../../add-product/modal.php';
    
    ?>


<div class="styliiiish-stats-bar">


    <?php if ($is_user): ?>

        <div class="pretty-stats">

            <div class="pretty-stat-box stat-active-pretty">
                <div class="pretty-dot"></div>
                <div class="pretty-label">Active:</div>
                <div class="pretty-value"><?php echo esc_html($stat_active_value); ?></div>
            </div>

            <div class="pretty-stat-box stat-pending-pretty">
                <div class="pretty-dot"></div>
                <div class="pretty-label">Pending:</div>
                <div class="pretty-value"><?php echo esc_html($stat_pending_value); ?></div>
            </div>

            <div class="pretty-stat-box stat-uncomplete-pretty">
                <div class="pretty-dot"></div>
                <div class="pretty-label">Uncomplete:</div>
                <div class="pretty-value"><?php echo esc_html($stat_uncomplete_value); ?></div>
            </div>

            <div class="pretty-stat-box stat-deactivated-pretty">
                <div class="pretty-dot"></div>
                <div class="pretty-label">Deactivated:</div>
                <div class="pretty-value"><?php echo esc_html($stat_deactivated_value); ?></div>
            </div>

        </div>

    <?php else: ?>

        <div class="styliiiish-stat-box stat-published">
            <div class="stat-inner">
                Published: <?php echo esc_html($stats['publish']); ?>
            </div>
        </div>

        <div class="styliiiish-stats-row">
            <div class="styliiiish-stat-box stat-total">
                <div class="stat-inner">
                    Total: <?php echo esc_html($stats['total']); ?>
                </div>
            </div>

            <div class="styliiiish-stat-box stat-draft">
                <div class="stat-inner">
                    Draft: <?php echo esc_html($stats['draft']); ?>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>


                <div class="pagination-wrapper" style="margin-top: 10px;">
                <?php if ($total_products > 0) : ?>
                    <strong>
                        Showing <?php echo esc_html($offset + 1); ?> - 
                        <?php echo esc_html(min($offset + $per_page, $total_products)); ?>
                        of <?php echo esc_html($total_products); ?>
                    </strong>
                    <br><br>
            
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <a href="#"
                           class="button styliiiish-page-link <?php echo $i == $paged ? 'button-primary styliiiish-current-page' : ''; ?>"
                           data-page="<?php echo esc_attr($i); ?>">
                            <?php echo esc_html($i); ?>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>



    <table class="owner-products-table">
        <thead>
        <tr>
            <th><input type="checkbox" id="styliiiish-select-all"></th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Attributes</th>
            <th style="width:70px">Price</th>
            <th>Categories</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>


        <tbody>
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $p) : ?>
                <?php
                $product_id   = $p->get_id();
                $terms        = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']);
                $price        = $p->get_regular_price();

                // Description
                $desc_full  = wp_strip_all_tags( get_post_field('post_content', $product_id) );
                $desc_short = wp_trim_words( $desc_full, 30 );

                // Attributes text
                $attr_text = styliiiish_get_attributes_text( $product_id );
                if ( $attr_text === '' ) {
                    $attr_text = get_post_meta( $product_id, '_styliiiish_inline_attributes', true );
                }
                $attr_full  = wp_strip_all_tags($attr_text);
                $attr_short = $attr_full ? wp_trim_words($attr_full, 8) : '—';
                ?>

                
                <tr data-row-id="<?php echo esc_attr($product_id); ?>">
                    <td>
                        <input type="checkbox" class="styliiiish-row-check" value="<?php echo esc_attr($product_id); ?>">
                    </td>


                    <td class="styliiiish-image-cell" data-id="<?php echo esc_attr($product_id); ?>">
                        <div class="styliiiish-image-wrapper">
                            <?php echo $p->get_image('thumbnail'); ?>
                            <div class="styliiiish-image-overlay">Edit image</div>
                        </div>
                    </td>



                    <td data-label="name">
                        <span
                            class="inline-edit"
                            contenteditable="true"
                            data-id="<?php echo esc_attr($product_id); ?>"
                            data-field="title"><?php echo esc_html($p->get_name()); ?></span>
                    </td>


                    <td data-label="Description">
                        <span
                            class="inline-edit inline-description"
                            contenteditable="true"
                            data-id="<?php echo esc_attr($product_id); ?>"
                            data-field="post_content"
                            data-full="<?php echo esc_attr($desc_full); ?>">
                            <?php echo esc_html($desc_short); ?>
                        </span>
                    </td>



                    <td data-label="Attributes">
                        <!-- Edit Attributes -->
                        <button 
                            class="btn-edit-attrs" 
                            data-id="<?php echo esc_attr($product_id); ?>">
                            ✏️ Attributes
                        </button>
                    </td>



                    <td data-label="Price">
                        <span
                            class="inline-edit"
                            contenteditable="true"
                            data-id="<?php echo esc_attr($product_id); ?>"
                            data-field="price"><?php echo $price !== '' ? esc_html($price) . ' EGP' : '—'; ?></span>
                    </td>





                    <td class="cats-cell" data-label="Categories">

                          <div class="cats-wrap">
                        
                            <div 
                              class="cats-text"
                              id="cat-display-<?php echo esc_attr($product_id); ?>"
                              title="<?php echo esc_attr( implode(', ', $terms) ); ?>"
                            >
                        
                              <?php echo !empty($terms) 
                                ? esc_html( implode(', ', $terms) ) 
                                : '<span class="cats-empty">No categories</span>'; ?>
                        
                            </div>
                        
                            <?php if ( current_user_can('edit_products') ): ?>
                        
                              <button
                                  type="button"
                                  class="button button-small edit-cats-btn cats-edit-btn"
                                  data-product="<?= esc_attr($product_id) ?>"
                                  aria-label="Edit categories"
                                  data-loading="0">
                                
                                  ✏️
                                
                                </button>
                        <?php endif; ?>
                    </div>
                </td>
                                                


			<td data-label="Status">
			<?php if ($is_user): ?>

				<?php
				$is_deactivated = get_post_meta($product_id, '_styliiiish_manual_deactivate', true) === 'yes';
				$status         = $p->get_status();

				$reject_label = get_post_meta($product_id, '_styliiiish_reject_reason_label', true);
				$reject_note  = get_post_meta($product_id, '_styliiiish_reject_reason_note', true);
				?>

				<?php if ($is_deactivated): ?>
					<span class="sty-status status-deactivated">Deactivated ??</span>

				<?php elseif ($status === 'publish'): ?>
					<span class="sty-status status-active">Active ??</span>

				<?php elseif ($status === 'pending'): ?>
					<span class="sty-status status-pending">Pending</span>

				<?php else: // draft ?>

					<span class="sty-status status-uncomplete">
						<?php echo $reject_label ? 'Rejected ?' : 'Incomplete ??'; ?>
					</span>

					<?php if ($reject_label): ?>
						<div class="sty-reject-reason-box">
							<strong>Reason:</strong> <?php echo esc_html($reject_label); ?>

							<?php if ($reject_note): ?>
								<div class="sty-reject-note">
									<?php echo esc_html($reject_note); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

				<?php endif; ?>

			<?php else: ?>

				<!-- OWNER VIEW -->
				<select class="inline-status" data-id="<?php echo esc_attr($product_id); ?>">
					<option value="publish" <?php selected($p->get_status(), 'publish'); ?>>Published</option>
					<option value="draft" <?php selected($p->get_status(), 'draft'); ?>>Draft</option>
				</select>

			<?php endif; ?>
			</td>




                   <td data-label="Actions">
                        <div class="owner-action-buttons">
                            
                            
                            <a href="#"
                               class="owner-action-btn btn-edit-product btn-edit-user"
                               data-id="<?php echo esc_attr($product_id); ?>">
                               ✏️ Edit
                            </a>

                    
                            <a class="owner-action-btn btn-view"
                               target="_blank"
                               href="<?php echo esc_url(get_permalink($product_id)); ?>">View</a>
                    
                            <?php if (!$is_user): ?>
                    
                                <a class="owner-action-btn btn-duplicate"
                                   href="#"
                                   data-id="<?php echo esc_attr($product_id); ?>">
                                    Duplicate
                                </a>
                    
                            <?php else: ?>
                                <?php
                                $is_deactivated = get_post_meta($product_id, '_styliiiish_manual_deactivate', true) === 'yes';
                                ?>
                    
                                <?php if ($is_deactivated): ?>
                                    <a href="#"
                                       class="owner-action-btn btn-activate-user"
                                       data-id="<?php echo esc_attr($product_id); ?>">
                                        ⚡ Activate
                                    </a>
                                <?php else: ?>
                                    <a href="#"
                                       class="owner-action-btn btn-deactivate-user"
                                       data-id="<?php echo esc_attr($product_id); ?>">
                                        ❌ Deactivate
                                    </a>
                                <?php endif; ?>
                    
                            <?php endif; ?>
                    
                            <a href="#"
                               class="owner-action-btn btn-delete"
                               data-id="<?php echo esc_attr($product_id); ?>">
                                Delete
                            </a>
                        </div>
                    </td>


                    
                    
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="9">No products found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    
    
    

           <div class="pagination-wrapper" style="margin-top: 10px;">
            <?php if ($total_products > 0) : ?>
                <strong>
                    Showing <?php echo esc_html($offset + 1); ?> - 
                    <?php echo esc_html(min($offset + $per_page, $total_products)); ?>
                    of <?php echo esc_html($total_products); ?>
                </strong>
                <br><br>
        
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <a href="#"
                       class="button styliiiish-page-link <?php echo $i == $paged ? 'button-primary styliiiish-current-page' : ''; ?>"
                       data-page="<?php echo esc_attr($i); ?>">
                        <?php echo esc_html($i); ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>
        </div>





                <?php if ($is_user): ?>

                    <?php
                    $user_tips = get_option('wf_user_tips_message');
                    ?>
                    
                    <?php if (!empty($user_tips)): ?>
                    
                    <div class="styliiiish-user-tips-box">
                    
                        <?php echo wp_kses_post($user_tips); ?>
                    
                    </div>
                    
                    <?php endif; ?>
                    
                    <?php endif; ?>






                <!-- Modals -->
                
                
                
        <div id="attrModal" class="attr-modal" style="display:none;">
            <div class="attr-modal-content">
                <h3>Select Attributes</h3>

                <div id="attrSelectorWrap"></div>

                <button id="saveAttrChanges" class="btn-save">Save</button>
                <button id="closeAttrModal" class="btn-close">Close</button>
            </div>
        </div>

    </div>
    
    
    
    
    
    <div id="editCatsModal" class="attr-modal" style="display:none;">
    <div class="cats-modal-box">

        <h3>📂 Edit Categories</h3>

        <div id="cats-checkboxes"></div>

        <div class="cats-btn-row">

            <button id="saveCatsBtn" class="button button-primary">
                Save
            </button>

            <button id="closeCatsBtn" class="button">
                Cancel
            </button>

        </div>

    </div>
</div>

    
    
    <?php
    return ob_get_clean();
}

