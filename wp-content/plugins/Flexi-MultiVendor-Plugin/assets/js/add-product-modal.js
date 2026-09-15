








jQuery(function($){










if(window.wfAddModalLoaded){return;}
    
window.currentProductId = 0;
let pendingAttrs = null;

function renderPreviewGallery(images, mainImage) {

   var list = Array.isArray(images) ? images.filter(Boolean) : [];
   var main = mainImage || '';

   if (main) {
      list = list.filter(function(url){ return url !== main; });
      list.unshift(main);
   }

   var $gallery = $('#previewGallery');
   $gallery.empty();

   if (!list.length) {
      return;
   }

   list.forEach(function(url, index){
      var $thumb = $('<img>')
         .addClass('preview-thumb')
         .attr('src', url)
         .attr('alt', 'Product image ' + (index + 1));

      if (index === 0) {
         $thumb.addClass('is-active');
      }

      $thumb.on('click', function(){
         $('#previewGallery .preview-thumb').removeClass('is-active');
         $(this).addClass('is-active');

         $('#previewImg').attr('src', url).show();
         $('#noImg').hide();
      });

      $gallery.append($thumb);
   });
}

function syncPreviewGalleryFromImagesHtml(html, mainImage) {
   var urls = [];
   if (html) {
      var $tmp = $('<div>').html(html);
      $tmp.find('.styliiiish-img-item img').each(function(){
         var src = $(this).attr('src');
         if (src) {
            urls.push(src);
         }
      });
   }

   renderPreviewGallery(urls, mainImage || $('#previewImg').attr('src') || '');
}






/* ===========================
   IMAGE UPLOAD SYSTEM
=========================== */
/* ===========================
   OPEN IMAGE MODAL FROM BUTTON
=========================== */
$(document).on("click", ".styliiiish-upload-btn", function (e) {

    e.preventDefault();
    lockScroll();

    // 👇 رجّع الوضع لمنتج
    currentImageContext = "product";
    currentTargetID = null;

    // Priority: JS → HTML
    let pid = window.currentProductId;

    if (!pid) {
        pid = $(this).data("id");
    }

    currentImageProd = pid;



    if (!currentImageProd) {
        Swal.fire(
          "Wait",
          "Product is not ready yet",
          "info"
        );
        return;
    }

    sendRequest(
        "styliiiish_get_images",
        { product_id: currentImageProd },
        function (response) {

            $("#styliiiish-images-list").html(response.data.html);
         syncPreviewGalleryFromImagesHtml(response.data.html, response.data.main_url || $('#previewImg').attr('src') || '');

            $("#styliiiishImageModal").css("display", "flex");
        }
    );
});










$(document).on('click','#addProductModal',function(e){

   if(e.target === this){

      // اقفل فقط لو ضغطت على الخلفية
      closeBuilder();
      

   }

});


$(document).on('click','.wf-ui-modal-box',function(e){
   e.stopPropagation();
});



$(document).on('click','#closeAddProductModal',function(){

   closeBuilder();

});


function closeBuilder(){

   $('#addProductModal').fadeOut();

   // 🔥 نظّف كل حاجة
   resetBuilder();

   // 🔓 رجّع السكرول
   unlockScroll();

   $('#saveProductBtn')
     .prop('disabled', false)
     .text('Save');
}



/* ==========================
   SUBMIT
========================== */

$(document).on('submit','#addProductForm',function(e){

 e.preventDefault();

 let form = new FormData(this);

 form.append('action','styliiiish_add_product');
 form.append('nonce', wfModal.nonce); // مهم
 form.append('product_id', window.currentProductId);



 $.ajax({

   url: wfModal.ajax,
   type:'POST',
   data:form,

   processData:false,
   contentType:false,

   success:function(res){


     if(res.success){

        Swal.fire('Saved','Done','success');

        // If the manage-products list exists, reload it and close the modal
        // only after the list's AJAX completes so the UI reflects the change.
        if (typeof loadManageProductsPage === 'function' && $('#styliiiish-manage-products-content').length) {

           $(document).one('ajaxStop', function(){
              closeBuilder();
           });

           try{
              // preserve current page if available
              var page = (window.currentFilters && window.currentFilters.page) ? window.currentFilters.page : 1;
              loadManageProductsPage(page);
           }catch(e){
              loadManageProductsPage(1);
           }

        } else {
           // fallback: close immediately
           closeBuilder();
        }

     }else{

        Swal.fire('Error',res.data,'error');
     }

   },

   error:function(xhr){


   }

 });

});


/* ==========================
   LIVE PREVIEW
========================== */

$(document).on('input','#fTitle',function(){

   $('#prevTitle').text(this.value || 'Product Name');

});


$(document).on('input','#fDesc',function(){

   $('#prevDesc').text(this.value || 'Product description...');

});


/* ==========================
   PRICE PREVIEW
========================== */

$(document).on('input','#fRegularPrice, #fSalePrice',function(){

   let reg  = parseFloat($('#fRegularPrice').val());
   let sale = parseFloat($('#fSalePrice').val());

   // لو مفيش سعر أساسًا
   if(!reg || reg <= 0){

      $('#prevRegular')
        .removeClass('discount')
        .text('—');

      $('#prevSale').hide();
      $('#prevDiscount').text('');
      return;
   }

   if(sale > 0 && sale < reg){

      let percent = Math.round(100 - (sale/reg*100));

      $('#prevRegular')
        .addClass('discount')
        .text(reg+' EGP');

      $('#prevSale')
        .text(sale+' EGP')
        .fadeIn(120);

      $('#prevDiscount')
        .text('-'+percent+'%');

   }else{

      $('#prevRegular')
        .removeClass('discount')
        .text(reg+' EGP');

      $('#prevSale').fadeOut(120);
      $('#prevDiscount').text('');
   }
});





$(document).on('blur', '#fSalePrice', function(){

   let reg  = parseFloat($('#fRegularPrice').val());
   let sale = parseFloat(this.value);

   if(!reg || !sale) return;

   if(sale >= reg){

      this.value = '';

      Swal.fire(
         'Invalid price',
         'Sale must be lower than regular',
         'warning'
      );

      $(this).focus();
   }
});




/* ==========================
   RESET
========================== */

function resetBuilder(){

   let pid = window.currentProductId;

   $('#addProductForm')[0].reset();

   window.currentProductId = pid;
   $('#currentProductId').val(pid);

   // 🔥 مهم
   pendingAttrs = null;

   $('#previewImg').hide();
   $('#noImg').show();

   $('#prevTitle').text('Product Name');
   $('#prevDesc').text('Product description...');

   $('#prevCats').html('');
   $('#prevAttrs').html('');
   $('#previewGallery').empty();

   $('#prevRegular')
     .removeClass('discount')
     .text('0 EGP');

   $('#prevSale').hide();
}



/* ===========================
   LOAD ATTRIBUTES ON CAT CHANGE
=========================== */

/* ===========================
   CATEGORY CHANGE
=========================== */

$(document).on('change','#fCats',function(){

 let cats = $(this).val() || [];

 if(!Array.isArray(cats)){
    cats = cats ? [cats] : [];
 }

 // Attribute map is category-based, so use first selected category as the driver.
 let cat = cats.length ? cats[0] : '';

 /* ---------- PREVIEW ---------- */

 let html='';

 $('#fCats option:selected').each(function(){

   html += '<span>'+$(this).text()+'</span>';

 });

 $('#prevCats').html(html);


 /* ---------- ATTR AJAX ---------- */

 if(!cat){

   $('.attr-box').html('<p>Select category first</p>');
   return;
 }

 $('.attr-box').html('<p>Loading...</p>');


 $.post(wfModal.ajax,{

   action : 'styliiiish_get_attributes',
   nonce  : wfModal.nonce,
   cat_id : cat

 },function(res){


   if(res.success){

     buildAttrs(res.data);

   }else{

     $('.attr-box').html('<p>No attributes</p>');
   }

 });

});



function buildAttrs(data){

   let html = '';

   if(!Array.isArray(data) || !data.length){

      html = '<p>No attributes for this category</p>';

   }else{

      data.forEach(function(attr){

         const isSizeAttr = attr.taxonomy === 'pa_size';

         html += `
         <div class="attr-group">

            <div class="attr-title">
               ${attr.label}
            </div>

            <div class="attr-list">
         `;

         if (isSizeAttr) {
            html += `
            <label class="attr-item">
               <input type="checkbox"
                      class="size-all-toggle"
                      name="attrs[${attr.taxonomy}][]"
                      value="__all__">
               <span>All sizes</span>
            </label>
            `;
         }

         attr.options.forEach(function(opt){

            if (isSizeAttr) {
               html += `
               <label class="attr-item">
                  <input type="checkbox"
                         class="size-item-toggle"
                         name="attrs[${attr.taxonomy}][]"
                         value="${opt.value}">
                  <span>${opt.label}</span>
               </label>
               `;
            } else {
               html += `
               <label class="attr-item">
                  <input type="radio"
                         name="attrs[${attr.taxonomy}]"
                         value="${opt.value}">
                  <span>${opt.label}</span>
               </label>
               `;
            }

         });

         html += `
            </div>
         </div>
         `;
      });

   }

   $('.attr-box').html(html);

   /* APPLY PENDING ATTRS */
   if(pendingAttrs && typeof pendingAttrs === "object"){

      requestAnimationFrame(function(){

         for(let tax in pendingAttrs){

            const pendingVal = pendingAttrs[tax];

            if (tax === 'pa_size') {
               if (Array.isArray(pendingVal)) {
                  pendingVal.forEach(function (sizeVal) {
                     $(`input[name="attrs[${tax}][]"][value="${sizeVal}"]`)
                       .prop('checked', true)
                       .trigger('change');
                  });
               } else if (pendingVal) {
                  $(`input[name="attrs[${tax}][]"][value="${pendingVal}"]`)
                    .prop('checked', true)
                    .trigger('change');
               }
            } else {
               $(`input[name="attrs[${tax}]"][value="${pendingVal}"]`)
                 .prop('checked', true)
                 .trigger('change');
            }
         }

         pendingAttrs = null;

      });
   }
}

$(document).on('change', '.attr-box .size-all-toggle', function(){
   let $group = $(this).closest('.attr-group');

   if($(this).is(':checked')){
      $group.find('.size-item-toggle').prop('checked', false);
   }
});

$(document).on('change', '.attr-box .size-item-toggle', function(){
   let $group = $(this).closest('.attr-group');

   if($(this).is(':checked')){
      $group.find('.size-all-toggle').prop('checked', false);
   }
});




/* ===========================
   ATTR PREVIEW
=========================== */

$(document).on('change','.attr-box input',function(){

 let html='';

 $('.attr-group').each(function(){

    let checked = $(this).find('input:checked');

    if(checked.length){

       checked.each(function(){
            html += '<span class="preview-attr">'+$(this).parent().text().trim()+'</span>';
       });

    }

 });

 $('#prevAttrs').html(html);

});









/* ==========================
   EDIT PRODUCT
========================== */

$(document).on('click','.btn-edit-product',function(e){

    lockScroll();
   e.preventDefault();

   let pid = $(this).data('id');

   if(!pid) return;


   $.post(wfModal.ajax,{

      action     : 'styliiiish_get_product_for_edit',
      nonce      : wfModal.nonce,
      product_id : pid

   },function(res){

      if(!res.success){
         Swal.fire('Error',res.data,'error');
         return;
      }

      let p = res.data;

        
        pendingAttrs = p.attrs || null;


      
      
      // ✅ PREVIEW IMAGE
        if(p.image){
        
           $("#previewImg")
             .attr("src", p.image)
             .show();
        
           $("#noImg").hide();
           renderPreviewGallery(p.gallery || [], p.image);
        
        }else{
        
           $("#previewImg").hide();
           $("#noImg").show();
           renderPreviewGallery(p.gallery || [], '');
        }


      // خزّن ID
      window.currentProductId = p.id;
      $('#currentProductId').val(p.id);

      // افتح المودال
      $('#addProductModal').fadeIn();

      // عبّي الفورم
      $('#fTitle').val(p.title);
      $('#fDesc').val(p.desc);
      $('#fRegularPrice').val(p.price);
        $('#fSalePrice').val(p.sale || '');

            if ($('#fAdminStatus').length) {
                var editStatus = (p.status === 'publish') ? 'publish' : 'draft';
                $('#fAdminStatus').val(editStatus);
            }
        
        $('#fRegularPrice').trigger('input').blur();



      // الكاتيجوري
      if(p.cats && p.cats.length){
         $('#fCats').val(p.cats).trigger('change');
      }

      // تحديث المعاينة
      $('#prevTitle').text(p.title);
      $('#prevDesc').text(p.desc);

   });

});










});
