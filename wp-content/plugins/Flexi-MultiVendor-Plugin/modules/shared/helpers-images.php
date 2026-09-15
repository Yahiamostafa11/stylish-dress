<?php 
/* ===============================
   Images Helper: Render product images HTML for modal
================================== */
function styliiiish_render_product_images_html($product_id) {
    $thumb_id    = get_post_thumbnail_id($product_id);
    $gallery     = get_post_meta($product_id, '_product_image_gallery', true);
    $gallery_ids = !empty($gallery) ? array_filter(array_map('intval', explode(',', $gallery))) : [];

    ob_start();
    ?>
    <div class="styliiiish-images-grid">
        <?php if ($thumb_id): ?>
            <div class="styliiiish-img-item is-main" data-attachment="<?php echo esc_attr($thumb_id); ?>">
                <?php echo wp_get_attachment_image($thumb_id, 'thumbnail'); ?>
                <span class="tag-main">Main</span>
                <div class="img-actions">
                    <button class="button button-small styliiiish-set-main" data-attachment="<?php echo esc_attr($thumb_id); ?>">Set as Main</button>
                    <button class="button button-small styliiiish-remove-image" data-attachment="<?php echo esc_attr($thumb_id); ?>">Remove</button>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($gallery_ids as $gid): ?>
            <?php if ($gid == $thumb_id) continue; ?>
            <div class="styliiiish-img-item" data-attachment="<?php echo esc_attr($gid); ?>">
                <?php echo wp_get_attachment_image($gid, 'thumbnail'); ?>
                <div class="img-actions">
                    <button class="button button-small styliiiish-set-main" data-attachment="<?php echo esc_attr($gid); ?>">Set as Main</button>
                    <button class="button button-small styliiiish-remove-image" data-attachment="<?php echo esc_attr($gid); ?>">Remove</button>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$thumb_id && empty($gallery_ids)): ?>
            <p>No images found for this product.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}



?>