<?php
get_header();

$vendor = get_queried_object();
$vendor_id = $vendor->ID;

$store_name = get_user_meta($vendor_id, 'taj_store_name', true);
?>

<h1><?php echo esc_html($store_name ?: $vendor->display_name); ?></h1>

<?php
$query = new WP_Query([
    'post_type' => 'product',
    'author'    => $vendor_id,
    'post_status' => 'publish'
]);

if ($query->have_posts()):
    woocommerce_product_loop_start();
    while ($query->have_posts()):
        $query->the_post();
        wc_get_template_part('content', 'product');
    endwhile;
    woocommerce_product_loop_end();
endif;

wp_reset_postdata();

get_footer();
