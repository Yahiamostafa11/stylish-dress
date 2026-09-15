<?php
/*
    MANAGE PRODUCTS PAGE (Owner Dashboard + User Dashboard)
    SPA Style • AJAX • SweetAlert2 • Inline Editing • Filters • Bulk • Image Modal
*/

if (!defined('ABSPATH')) {
    exit;
}

/* ===============================
   MAIN PAGE RENDER
   $mode = 'owner' | 'user'
================================== */
function styliiiish_render_manage_products( $mode = 'owner' ){
    // ✅ لو جاي من owner-dashboard.php كـ boolean (زي الكود القديم) نخليه owner
    if (!is_string($mode)) {
        $mode = 'owner';
    }

    $is_user_mode = ($mode === 'user');

    // ✅ الصلاحيات:
    // - owner mode: لازم manage_woocommerce (نفس القديم)
    // - user mode: أي يوزر مسجل دخول، هنتحكم في الفلترة من AJAX بعدين
    if ($mode === 'owner') {
        if ( ! ( current_user_can('manage_woocommerce') || wf_od_is_user_plugin_admin() ) ) {
            echo esc_html__('No permission', 'website-flexi');
            return;
        }
    } else {
        if (!is_user_logged_in()) {
            echo 'Please log in to view your products.';
            return;
        }
    }

    $paged = 1;
    ?>

    <?php if ($mode === 'owner' || $mode === 'user'): ?>
        <br>
        <div class="styliiiish-add-product-row">
        <button type="button" class="button button-primary" id="styliiiish-add-product">
            + Add New Product
        </button>




<?php if ($is_user_mode): ?>

<?php
$note = get_option('wf_vendor_add_note','');
?>

<?php if(!empty($note)): ?>

<span class="styliiiish-add-note">
    <?php echo wp_kses_post($note); ?>
</span>

<?php endif; ?>

<?php endif; ?>












        
    </div>

    <br><br>
<?php endif; ?>







        
    <div class="styliiiish-toolbar">
        
        <div class="styliiiish-filters-group">
            <input type="text" id="styliiiish-search" class="regular-text" placeholder="Search products...">
            
            <?php if (!$is_user_mode): ?>
            <?php
            
            wp_dropdown_categories([
                'show_option_all' => 'All categories',
                'taxonomy'        => 'product_cat',
                'name'            => 'styliiiish_filter_cat',
                'id'              => 'styliiiish-filter-cat',
                'hide_empty'      => 0,
                'value_field'     => 'term_id',
            ]);
            ?>
            <?php endif; ?>
            
            
            
            
           <!-- الأصلية (owner فقط) -->
          
        <?php if (!$is_user_mode): ?>
            <select id="styliiiish-filter-status">
                <option value="">All statuses</option>
                <option value="publish">Published</option>
                <option value="draft">Draft</option>
            </select>
            <?php endif; ?>
            
            
            <?php if ($is_user_mode): ?>
            <select id="styliiiish-filter-status-user">
                <option value="">All statuses</option>
                <option value="publish">Active</option>
                <option value="pending">Pending</option>
                <option value="draft">Uncomplete</option>
                <option value="deactivated">Deactivated</option>
            </select>
            <?php endif; ?>

            <!-- Sort and density controls -->
            <label for="styliiiish-sort" style="margin-left:8px;"><?php echo esc_html__('Sort by', 'website-flexi'); ?></label>
            <select id="styliiiish-sort" style="margin-left:6px;">
                <option value="date_desc"><?php echo esc_html__('Newest', 'website-flexi'); ?></option>
                <option value="date_asc"><?php echo esc_html__('Oldest', 'website-flexi'); ?></option>
                <option value="price_asc"><?php echo esc_html__('Price: Low → High', 'website-flexi'); ?></option>
                <option value="price_desc"><?php echo esc_html__('Price: High → Low', 'website-flexi'); ?></option>
            </select>

            <label for="styliiiish-per-row" style="margin-left:12px;"><?php echo esc_html__('Per row', 'website-flexi'); ?></label>
            <select id="styliiiish-per-row" style="margin-left:6px;">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3" selected>3</option>
                <option value="4">4</option>
            </select>






        <?php if (!$is_user_mode): ?>
            <div class="styliiiish-bulk-group">
                <select id="styliiiish-bulk-action">
                    <option value="">Bulk actions</option>
                    <option value="delete">Delete</option>
                    <option value="publish">Set to Published</option>
                    <option value="draft">Set to Draft</option>
                </select>
                <button type="button" class="button" id="styliiiish-bulk-apply">Apply</button>
            </div>
        <?php endif; ?>
    </div>
    </div>
    <!--
    
    <div id="styliiiish-manage-products-content"
         data-mode="<//?php echo esc_attr($mode); ?>">
        <//?php echo styliiiish_get_manage_products_data($paged, '', 0, '', $mode); ?> -->
        <!-- 👆 لسه ما لمسناش الدالة دي، هنعدلها في خطوة جاية عشان تدعم المود -->
  <!--  </div> -->
  
    
   
     <div id="styliiiish-manage-products-content"
             data-mode="<?php echo esc_attr($mode); ?>">
             
            <div class="styliiiish-loader">
                Loading products…
            </div>
        
        </div>




<?php if ($mode === 'owner' || $mode === 'user'): ?>
    <!-- Categories Modal -->
    <?php if ($mode === 'owner'): ?>
        <div id="editCatsModal">
            <div class="cats-modal-box">
                <h3>Edit Categories</h3>
                <div id="cats-checkboxes"></div>
                <div class="cats-btn-row">
                    <button id="saveCatsBtn" class="button button-primary">Save</button>
                    <button id="closeCatsBtn" class="button">Cancel</button>
                </div>
            </div>
        </div>
    <?php endif; ?>






    <!-- Image Modal -->
    <div id="styliiiishImageModal">
        <div class="image-modal-box">
            <h3>Manage Images</h3>
            <div id="styliiiish-images-list" style="flex:1;overflow:auto;max-height:65vh;"></div>
            
            
            
            
            
            
            <div id="styliiiish-lottie-loader" style="display:none; text-align:center; padding:20px;">
                <lottie-player
                    id="sty-loader"
                    src="https://assets9.lottiefiles.com/packages/lf20_j1adxtyb.json"
                    background="transparent"
                    speed="1.2"
                    style="width: 120px; height: 120px; margin:auto;"
                    loop
                    autoplay>
                </lottie-player>
            
                <div id="styliiiish-upload-percent" style="margin-top:10px;font-size:15px;opacity:.8;">
                    Uploading…
                </div>
            </div>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            <div id="styliiiish-upload-progress" style="display:none;">
            <div class="progress-bar">
                <div class="progress-inner"></div>
            </div>
            <div class="progress-text">Uploading 0%</div>
        </div>

            <div class="image-btn-row">
                 <!-- 👇 أضِف هذا هنا 👇 -->
            <input 
                type="file" 
                id="styliiiish-upload-input" 
                accept="image/*" 
                style="display:none;"
            >
            <!-- 👆 أضِف هذا هنا 👆 -->
                <button id="styliiiish-add-image" class="button button-primary">Add / Change Image</button>
                <button id="styliiiish-close-image-modal" class="button">Close</button>
            </div>
        </div>
        
    </div>
<?php endif; ?>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php
}

/**
 * Shortcode: User Products Dashboard
 * Shows the same Manage Products UI but in USER mode
 * Users only see THEIR OWN products
 * Some columns + actions disabled automatically
 */
function styliiiish_user_products_dashboard_shortcode() {

    if (!is_user_logged_in()) {
        return '<p>Please log in to access your products.</p>';
    }

    // IMPORTANT: render the SAME manager UI but in user mode
    ob_start();
    styliiiish_render_manage_products('user');
    return ob_get_clean();
}
add_shortcode('user_products_dashboard', 'styliiiish_user_products_dashboard_shortcode');
























?>
