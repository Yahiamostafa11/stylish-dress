<?php

$products     = $data['products'] ?? [];
$stats        = $data['stats'] ?? [];
$pretty_stats = $data['pretty_stats'] ?? [];

$pagination   = $data['pagination'] ?? [];

$total_products = $pagination['total']  ?? 0;
$total_pages    = $pagination['pages']  ?? 1;
$offset         = $pagination['offset'] ?? 0;
$paged          = $pagination['page']   ?? 1;
$per_page       = $pagination['per']    ?? 10;

$is_user = $data['is_user'] ?? false;
$mode    = $data['mode'] ?? 'owner';

/* Pretty stats */
$stat_active_value      = $pretty_stats['active'] ?? 0;
$stat_pending_value     = $pretty_stats['pending'] ?? 0;
$stat_uncomplete_value  = $pretty_stats['uncomplete'] ?? 0;
$stat_deactivated_value = $pretty_stats['deactivated'] ?? 0;

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
// nothing

