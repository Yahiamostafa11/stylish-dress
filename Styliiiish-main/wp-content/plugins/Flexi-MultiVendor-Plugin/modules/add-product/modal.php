







<div id="addProductModal" class="wf-ui-modal">

<div class="wf-ui-modal-box">


<div class="product-builder">


<!-- ===================
   LIVE PREVIEW
=================== -->

<div class="product-preview">

    <div class="preview-image" >
        <img id="previewImg" src="" style="display:none;">
        <span id="noImg">No Image</span>
    </div>

    <div class="preview-gallery" id="previewGallery"></div>

    <div class="preview-title" id="prevTitle">
        Product Name
    </div>

    <div class="preview-price">

        <span id="prevRegular">0 EGP</span>
        
        <span id="prevSale" style="display:none"></span>
        
        </div>


    <div class="preview-desc" id="prevDesc">
        Product description...
    </div>

    <div class="preview-meta">

    <div class="preview-badge">NEW</div>

    <div class="preview-rating">
        ⭐⭐⭐⭐⭐ <span>(0)</span>
    </div>

</div>

<div class="preview-cats" id="prevCats"></div>

<div class="preview-attrs" id="prevAttrs"></div>


<button class="preview-buy-btn">
    Add to Cart
</button>


</div>


<!-- ===================
   FORM
=================== -->

<div class="builder-form">

<form id="addProductForm" enctype="multipart/form-data">



<div class="wf-field">
<label>Name</label>
<input type="text" name="title" id="fTitle">
</div>


<div class="wf-field price-group">

<label>Price</label>

<div class="price-row">

<input type="number"
       name="regular_price"
       id="fRegularPrice"
       placeholder="Original Price">

<input type="number"
       name="sale_price"
       id="fSalePrice"
       placeholder="Sale Price">

</div>

<small class="price-hint">
Leave sale empty if no discount
</small>

</div>



<div class="wf-field">
<label>Description</label>
<textarea name="desc" id="fDesc"></textarea>
</div>

<?php
$wf_user_type_modal = function_exists('wf_od_get_user_type') ? wf_od_get_user_type(get_current_user_id()) : '';
$wf_is_admin_modal = current_user_can('manage_woocommerce') || wf_od_is_user_plugin_admin() || in_array($wf_user_type_modal, ['manager', 'dashboard'], true);
?>

<?php if ($wf_is_admin_modal): ?>
<div class="wf-field">
<label>Status</label>
<select name="admin_status" id="fAdminStatus">
  <option value="draft">Draft</option>
  <option value="publish">Active</option>
</select>
</div>
<?php endif; ?>











<div class="wf-field">
<label>Categories</label>
<select name="cats[]" id="fCats" multiple>

<?php

$is_vendor = ! ( current_user_can('manage_woocommerce') || wf_od_is_user_plugin_admin() );

if($is_vendor){

  $allowed = get_option('wf_allowed_vendor_categories', []);

  if(!empty($allowed)){

    $cats = get_terms([
      'taxonomy'=>'product_cat',
      'hide_empty'=>false,
      'include'=>$allowed
    ]);

  }else{
    $cats = [];
  }

}else{

  $cats = get_terms([
    'taxonomy'=>'product_cat',
    'hide_empty'=>false
  ]);
}

foreach($cats as $c){

  echo "<option value='{$c->term_id}'>{$c->name}</option>";
}
?>

</select>
</div>



<!-- ATTRIBUTES -->

<div class="wf-field">

<label>Attributes</label>

<div class="attr-box">
  <p>Please select category first</p>
</div>

</div>













<div class="wf-field" >
<label>Image</label>

   <input 
  type="file"
  id="styliiiish-upload-input"
  class="styliiiish-upload-input"
  accept="image/*"
  multiple
  style="display:none"
>

<?php global $post; ?>

<button 
  class="styliiiish-upload-btn wf-ui-btn"
  data-id="<?php echo esc_attr($post->ID); ?>"
>
Upload Image
</button>


</div>










<div id="wfImageModal"></div>



<div class="builder-actions">

<button type="submit"
        id="saveProductBtn"
        class="wf-ui-btn">
Save
</button>

<button type="button"
        id="closeAddProductModal"
        class="wf-ui-btn outline">
Cancel
</button>

</div>

</form>

</div>


</div>

</div>
</div>
