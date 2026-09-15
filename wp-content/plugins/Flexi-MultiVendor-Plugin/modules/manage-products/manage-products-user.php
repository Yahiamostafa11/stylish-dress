<?php

function styliiiish_render_user_products() {

    $user_id = get_current_user_id();

    ?>
    
    <div class="styliiiish-toolbar">
        <input type="text" id="user-search" placeholder="Search...">
        
        <?php
            wp_dropdown_categories([
                'show_option_all' => 'All categories',
                'taxonomy'        => 'product_cat',
                'name'            => 'user_filter_cat',
                'id'              => 'user-filter-cat',
                'hide_empty'      => 0,
                'value_field'     => 'term_id',
            ]);
        ?>
    </div>

    <div id="user-products-content">
        <?php echo styliiiish_user_products_content(1, $user_id); ?>
    </div>

    <?php
}
