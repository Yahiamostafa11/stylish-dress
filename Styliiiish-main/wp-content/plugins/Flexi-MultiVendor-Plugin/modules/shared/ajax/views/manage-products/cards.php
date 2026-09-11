<?php

$products     = $data['products'] ?? [];
$pagination   = $data['pagination'] ?? [];

$total_pages  = $pagination['pages'] ?? 1;
$paged        = $pagination['page'] ?? 1;

$is_user = $data['is_user'] ?? false;

?>

<?php
// Load this view's CSS when the view is rendered (AJAX or include).
echo '<link rel="stylesheet" href="' . esc_url( plugins_url( 'assets/cards.css', __FILE__ ) ) . '" />';
?>

<div class="sty-cards-grid">

<?php if(!empty($products)): ?>

<?php foreach($products as $p): ?>

<?php
$id     = $p->get_id();

$regular_price = (string) $p->get_regular_price();
$sale_price    = (string) $p->get_sale_price();
$current_price = (string) $p->get_price();

$price = $regular_price !== '' ? $regular_price : $current_price;
$sale  = '';

if ($sale_price !== '') {
    $sale = $sale_price;
} elseif ($regular_price !== '' && $current_price !== '' && floatval($current_price) < floatval($regular_price)) {
    $sale = $current_price;
}

// compute discount percent when applicable
$discount_percent = '';
if($sale && $price && floatval($sale) < floatval($price)){
    $discount_percent = round(100 - (floatval($sale) / floatval($price) * 100));
}
$img    = $p->get_image('medium');
$status = $p->get_status();

// Map internal WP statuses to user-friendly labels (publish -> Active)
$status_labels = [
    'publish' => __('Active', 'website-flexi'),
    'draft'   => __('Draft', 'website-flexi'),
    'pending' => __('Pending', 'website-flexi'),
    'private' => __('Private', 'website-flexi'),
    'future'  => __('Scheduled', 'website-flexi'),
    'trash'   => __('Trashed', 'website-flexi')
];

$status_label = isset($status_labels[$status]) ? $status_labels[$status] : ucfirst($status);

$terms = wp_get_post_terms($id,'product_cat',['fields'=>'names']);

$is_deactivated =
    get_post_meta($id,'_styliiiish_manual_deactivate',true)==='yes';

// created timestamp for client-side sorting
$created_ts = get_post_time('U', true, $id);

$data_price = $sale !== '' ? $sale : $price;
?>

<div class="sty-card <?= $is_deactivated?'is-deactivated':'' ?>" data-id="<?= esc_attr($id) ?>" data-created="<?= esc_attr($created_ts) ?>" data-price="<?= esc_attr($data_price) ?>">

    <!-- Image -->
   <div class="card-thumb">

    <?= $img ?>

    <!-- Status -->
    <span class="card-badge badge-<?= esc_attr($status) ?>">
        <?= esc_html( $status_label ) ?>
    </span>

    <?php if($is_deactivated): ?>
        <span class="card-badge badge-off">
            <?= esc_html__('Deactivated','website-flexi') ?>
        </span>
    <?php endif; ?>

    <!-- Delete Button -->
    <button
        class="card-delete-btn btn-delete"
        data-id="<?= esc_attr($id) ?>"
        title="<?= esc_attr__('Delete product','website-flexi') ?>">
        🗑
    </button>

</div>



    <!-- Content -->
    <div class="card-content">

        <h4 class="card-title">
            <?= esc_html($p->get_name()) ?>
        </h4>

        <div class="card-meta">

            <span class="card-price-wrap">
            <?php if($sale && $price && floatval($sale) < floatval($price)): ?>

                <span class="card-price card-price-sale">
                    <?= wp_kses_post( wc_price( (float) $sale ) ) ?>
                </span>

                <span class="card-price card-price-regular">
                    <?= wp_kses_post( wc_price( (float) $price ) ) ?>
                </span>

                <span class="card-discount-badge">
                    -<?= esc_html( $discount_percent ) ?>%
                </span>

            <?php else: ?>

                <span class="card-price">
                    <?= $price !== '' ? wp_kses_post( wc_price( (float) $price ) ) : esc_html__( '—', 'website-flexi' ) ?>
                </span>

            <?php endif; ?>
            </span>

            <?php if(!empty($terms)): ?>
            <span class="card-cat">
                <?= esc_html($terms[0]) ?>
            </span>
            <?php endif; ?>

        </div>

    </div>


    <!-- Actions -->
    <div class="card-footer">

          <a href="#"
              class="card-btn btn-edit-product"
              data-id="<?= esc_attr($id) ?>">
              ✏️ <?= esc_html__( 'Edit', 'website-flexi' ) ?>
          </a>

          <a href="<?= esc_url(get_permalink($id)) ?>"
              target="_blank"
              class="card-btn">
              👁 <?= esc_html__( 'View', 'website-flexi' ) ?>
          </a>

        <?php if($is_user): ?>

            <?php if($is_deactivated): ?>

                     <a href="#"
                         class="card-btn btn-activate-user"
                         data-id="<?= esc_attr($id) ?>">
                         ⚡ <?= esc_html__( 'Activate', 'website-flexi' ) ?>
                     </a>

            <?php else: ?>

                     <a href="#"
                         class="card-btn btn-deactivate-user"
                         data-id="<?= esc_attr($id) ?>">
                         ❌ <?= esc_html__( 'Deactivate', 'website-flexi' ) ?>
                     </a>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<?php else: ?>

<p class="sty-empty"><?= esc_html__( 'No products found.', 'website-flexi' ) ?></p>

<?php endif; ?>

</div>



<!-- Pagination -->
<?php if($total_pages>1): ?>

<div class="pagination-wrapper">

<?php for($i=1;$i<=$total_pages;$i++): ?>

<a href="#"
   class="button styliiiish-page-link <?= $i==$paged?'button-primary':'' ?>"
   data-page="<?= esc_attr($i) ?>">
   <?= $i ?>
</a>

<?php endfor; ?>

</div>

<?php endif; ?>


    
   <?php
// nothing

