
// متغيرات عامة نستخدمها فى كل الكود
//window.currentFilters = { search:'', cat:'', status:'', page:1 };
//window.currentProduct = 0;
//window.currentImageProd = 0;
//window.searchTimer = null;
//window.imageFrame = null;

// Override functions placeholder
//function scrollToManageProducts() {}
//window.loadManageProductsPage = function(page){}

// Run first load
//setTimeout(() => loadManageProductsPage(1), 300);




// متغيرات عامة نستخدمها فى كل الكود
window.currentFilters   = { search:'', cat:'', status:'', sort:'date_desc', per_row:3, page:1 };
window.currentProduct   = 0;
window.currentImageProd = 0;
window.searchTimer      = null;
window.imageFrame       = null;

// مجرد placeholders – التعريف الحقيقي جوه jQuery(function($){...})
function scrollToManageProducts() {}
window.loadManageProductsPage = function(page){};


 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/





 jQuery(function($){

// Ensure `showSection` is available globally. Some themes/plugins inject
// owner-dashboard HTML via innerHTML or AJAX which won't execute inline
// <script> tags — exposing this helper on `window` prevents
// "showSection is not defined" errors when cards use `onclick="showSection(...)"`.
window.showSection = window.showSection || function(section) {
    try {
        var secs = document.querySelectorAll('.owner-section');
        if (secs && secs.length) {
            secs.forEach(function(s){ s.style.display = 'none'; });
        }
        var el = document.getElementById('section-' + section);
        if (el) {
            el.style.display = 'block';
        }
        if (window.scrollTo) window.scrollTo({ top: 300, behavior: 'smooth' });
    } catch(e) {
        console.warn('showSection failed', e);
    }
};

// Robust card navigation binding (works even if inline onclick/script is stripped).
$(document).on('click', '.owner-card[data-owner-section]', function(e){
    e.preventDefault();
    var section = this.getAttribute('data-owner-section');
    if (section) {
        window.showSection(section);
    }
});

$(document).on('keydown', '.owner-card[data-owner-section]', function(e){
    if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        var section = this.getAttribute('data-owner-section');
        if (section) {
            window.showSection(section);
        }
    }
});

// Mobile default: show 1 card per row initially.
if (window.matchMedia && window.matchMedia('(max-width: 768px)').matches) {
    currentFilters.per_row = 1;
    $('#styliiiish-per-row').val('1');
}

setTimeout(function(){
    try {
        var sections = document.querySelectorAll('.owner-section');
        if (!sections || !sections.length) return;

        var hasVisible = Array.prototype.some.call(sections, function(sec){
            return sec.style.display && sec.style.display !== 'none';
        });

        if (!hasVisible) {
            window.showSection('products');
        }
    } catch(e) {}
}, 50);







window.openModalCount = 0;
window.lastScrollY = 0;

window.lockScroll = function(){


   if(window.openModalCount === 0){

      window.lastScrollY =
        window.scrollY || window.pageYOffset || 0;

      $('html, body').addClass('attr-modal-open');
   }

   window.openModalCount++;
};

window.unlockScroll = function(){


   if(window.openModalCount <= 0) return; // حماية

   window.openModalCount--;

   if(window.openModalCount === 0){

      $('html, body').removeClass('attr-modal-open');

      window.scrollTo(0, window.lastScrollY);
   }
};













/*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of   // Skeleton Loader          ##################                              */
 /**************************** // Function Of   // Skeleton Loader  *******************************************/
   /**************************** // Function Of // Skeleton Loader  *******************************************/  
   function showLoadingSkeleton() {

    let mode = jQuery('#styliiiish-manage-products-content').data('mode');

    let rowsCount = 10;
    let rows = "";
    for (let i = 0; i < rowsCount; i++) {
        rows += `
            <tr class="skeleton-row">
                <td><div class="sk-box sk-check"></div></td>
                <td><div class="sk-box sk-img"></div></td>
                <td><div class="sk-line sk-w-80"></div></td>
                <td><div class="sk-line sk-w-90"></div></td>
                <td><div class="sk-line sk-w-80"></div></td>
                <td><div class="sk-line sk-w-50"></div></td>
                <td>
                    <div class="sk-line sk-w-90"></div>
                    <div class="sk-line sk-w-60 mt-4"></div>
                </td>
                <td><div class="sk-dropdown"></div></td>
                <td>
                    <div class="sk-btn"></div>
                    <div class="sk-btn"></div>
                    <div class="sk-btn"></div>
                </td>
            </tr>
        `;
    }

    let headHtml = $('#styliiiish-manage-products-content table thead').html() || `
        <tr>
            <th></th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Attributes</th>
            <th>Price</th>
            <th>Categories</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>`;

    /* ============================
       USER MODE – NEW SKELETON
    ============================ */
    let userStatsSkeleton = `
        <div class="pretty-stats skeleton-stats">
            <div class="pretty-stat-box skeleton"></div>
            <div class="pretty-stat-box skeleton"></div>
            <div class="pretty-stat-box skeleton"></div>
            <div class="pretty-stat-box skeleton"></div>
        </div>
    `;

    /* ============================
       OWNER MODE – OLD SKELETON
    ============================ */
    let ownerStatsSkeleton = `
        <div class="styliiiish-stats-bar">
            <div class="styliiiish-stat-box"><span class="label">Total</span><span class="value">...</span></div>
            <div class="styliiiish-stat-box"><span class="label">Published</span><span class="value">...</span></div>
            <div class="styliiiish-stat-box"><span class="label">Draft</span><span class="value">...</span></div>
        </div>
    `;

    $('#styliiiish-manage-products-content').html(`
        ${ mode === 'user' ? userStatsSkeleton : ownerStatsSkeleton }
        <table class="owner-products-table skeleton-mode">
            <thead>${headHtml}</thead>
            <tbody>${rows}</tbody>
        </table>
    `);
}

        
        
        
        
        
        
        
        
        

window.scrollToManageProducts = function() {
    let offset = $('#styliiiish-manage-products-content').offset();
    if (!offset) return;
    $('html, body').animate({
        scrollTop: offset.top - 80
    }, 250);
}

window.loadManageProductsPage = function(page) {
    currentFilters.page = page || 1;
    showLoadingSkeleton();



    


    $.post(ajax_object.ajax_url, {
    action: 'styliiiish_manage_products_list',
    nonce: ajax_object.nonce,

    page: currentFilters.page,
    search: currentFilters.search,
    cat: currentFilters.cat,
    status: currentFilters.status,
    sort: currentFilters.sort || 'date_desc',
    per_row: currentFilters.per_row || 3,
    mode: jQuery('#styliiiish-manage-products-content').data('mode')

}, function (html) {

    $('#styliiiish-manage-products-content')
        .hide()
        .html(html)
        .fadeIn(180);

    // Apply per_row CSS variable if provided
    try{
        var per = currentFilters.per_row || 3;
        $('#styliiiish-manage-products-content').find('.sty-cards-grid').css('--cards-cols', per);
    }catch(e){}
    
    // Client-side sorting if server doesn't provide sorted HTML
    try{
        var sort = currentFilters.sort || 'date_desc';
        var grid = $('#styliiiish-manage-products-content').find('.sty-cards-grid');
        if(grid.length){
            var cards = grid.find('.sty-card').get();
            cards.sort(function(a,b){
                var ac = parseInt($(a).data('created')||0,10);
                var bc = parseInt($(b).data('created')||0,10);
                var ap = parseFloat($(a).data('price')||0);
                var bp = parseFloat($(b).data('price')||0);

                if(sort === 'date_desc') return bc - ac;
                if(sort === 'date_asc') return ac - bc;
                if(sort === 'price_asc') return ap - bp;
                if(sort === 'price_desc') return bp - ap;
                return 0;
            });
            // append in new order
            $(cards).each(function(){ grid.append(this); });
        }
    }catch(e){}

});

};





/** ⬅️⬅️⬅️⬅️ هنا بالضبط تحط السطر */
// أول لود بعد ما الـ DOM يجهز
if ($('#styliiiish-manage-products-content').length) {
    setTimeout(function(){
        loadManageProductsPage(1);
    }, 200);
}

// Listen to toolbar controls (sort / per-row) and refresh list
$(document).on('change', '#styliiiish-sort', function(){
    currentFilters.sort = $(this).val();
    loadManageProductsPage(1);
});

$(document).on('change', '#styliiiish-per-row', function(){
    currentFilters.per_row = parseInt($(this).val()) || 3;
    loadManageProductsPage(1);
});









/*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of   Pagination           ##################                              */
 /**************************** // Function Of   Pagination  *******************************************/
   /**************************** // Function Of   Pagination  *******************************************/
        $(document).on('click', '.styliiiish-page-link', function (e) {
    e.preventDefault();
    var page = $(this).data('page');
    if (!page) return;
    scrollToManageProducts();
    loadManageProductsPage(page);
});












/*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of  // Filters Products          ##################                              */
 /**************************** // Function Of  // Filters Products *******************************************/
   /**************************** // Function Of  // Filters Products *******************************************/
        $('#styliiiish-search').on('keyup', function () {
            clearTimeout(searchTimer);
            let val = $(this).val();
            searchTimer = setTimeout(function () {
                currentFilters.search = val;
                loadManageProductsPage(1);
            }, 300);
        });

        $('#styliiiish-filter-cat').on('change', function () {
            currentFilters.cat = $(this).val();
            loadManageProductsPage(1);
        });

        $('#styliiiish-filter-status').on('change', function () {
            currentFilters.status = $(this).val();
            loadManageProductsPage(1);
        });







/*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of  Select all Products          ##################                              */
 /**************************** // Function Of  Select all Products *******************************************/
   /**************************** // Function Of  Select all Products *******************************************/
        
        $(document).on('change', '#styliiiish-select-all', function(){
            let checked = $(this).is(':checked');
            $('#styliiiish-manage-products-content .styliiiish-row-check').prop('checked', checked);
        });







 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of Bulk apply            ##################                              */
 /**************************** // Function Of Bulk apply *******************************************/
   /**************************** // Function Of Bulk apply *******************************************/
        // 
        $('#styliiiish-bulk-apply').on('click', function(){
            let action = $('#styliiiish-bulk-action').val();
            if (!action) {
                Swal.fire('Notice', 'Please select a bulk action.', 'info');
                return;
            }

            let ids = [];
            $('#styliiiish-manage-products-content .styliiiish-row-check:checked').each(function () {
                ids.push($(this).val());
            });

            if (!ids.length) {
                Swal.fire('Notice', 'No products selected.', 'info');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Apply bulk action to selected products?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, apply',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.post(ajax_object.ajax_url, {
                    action: 'styliiiish_bulk_action',
                    nonce: ajax_object.nonce,
                    bulk_action: action,
                    ids: ids
                }, function(response){
                    if (!response || typeof response.success === 'undefined') {
                        Swal.fire('Error', 'Unexpected response.', 'error');
                        return;
                    }
                    if (!response.success) {
                        Swal.fire('Error', response.data && response.data.message ? response.data.message : 'Bulk action failed.', 'error');
                        return;
                    }

                    Swal.fire('Done', response.data.message, 'success');
                    loadManageProductsPage(currentFilters.page);
                }, 'json').fail(function(){
                    Swal.fire('Error', 'Error communicating with server.', 'error');
                });
            });
        });
    







 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of attributes Edit And Save           ##################                              */
 /**************************** // Function Of attributes Edit And Save *******************************************/
   /**************************** // Function Of attributes Edit And Save *******************************************/
            



    // ====== دالة الغلق الموحدة ======
    function closeAttrModal() {
        $('#attrModal').fadeOut(150, function () {
            unlockScroll();
            
            $('html').removeClass('attr-modal-open');
            document.documentElement.style.setProperty('--scrollbar-compensation', '0px');


        });
    }

    // ====== فتح المودال ======
    $(document).on('click', '.btn-edit-attrs', function (e) {
        e.preventDefault();

        currentProductID = $(this).data('id');
        if (!currentProductID) return;



        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.documentElement.style.setProperty('--scrollbar-compensation', scrollbarWidth + 'px');
        
        $('html').addClass('attr-modal-open');




        // قفل الاسكرول
        lockScroll();

        // عرض المودال
        $('#attrModal')
            .css('display', 'flex')
            .hide()
            .fadeIn(150);

        // تحميل البيانات
        $('#attrSelectorWrap')
            .empty()
            .append('<p style="margin:10px 0;">Loading attributes...</p>');

        $.post(ajax_object.ajax_url, {
            action: 'styliiiish_get_attributes',
            nonce: ajax_object.nonce,
            product_id: currentProductID
        }, function (res) {

            $('#attrSelectorWrap').empty();

            if (!res || !res.success) {
                $('#attrSelectorWrap').append('<p>Error loading attributes.</p>');
                return;
            }

            let attributes = res.data;

            if (!attributes.length) {
                $('#attrSelectorWrap').append('<p>No attributes registered for this product.</p>');
                return;
            }

            attributes.forEach(function (attr) {

                let isSizeAttr = attr.taxonomy === 'pa_size';
                let selectedValues = Array.isArray(attr.selected_values)
                    ? attr.selected_values
                    : (attr.selected ? [attr.selected] : []);
                let allSizesSelected = !!attr.all_sizes || selectedValues.includes('__all__');

                let html = `
                    <label style="font-weight:bold;margin-top:10px;display:block;">
                        ${attr.label}
                    </label>
                    <select class="single-attr"
                            data-tax="${attr.taxonomy}"
                            ${isSizeAttr ? 'multiple' : ''}
                            style="width:100%;">
                        ${isSizeAttr ? '' : '<option value="">— Select —</option>'}
                `;

                if (isSizeAttr) {
                    let allSel = allSizesSelected ? 'selected' : '';
                    html += `<option value="__all__" ${allSel}>All sizes</option>`;
                }

                attr.options.forEach(function (opt) {

                    // 🔥 إخفاء بعض الخيارات عن الـ User فقط
                    if (!ajax_object.is_manager) {
                
                        let forbidden = [
                            'new',
                            'used-very-good-styliiiish-certified',
                            'used'
                        ];
                
                        if (forbidden.includes(opt.value)) {
                            return; // Skip
                        }
                    }
                
                    let sel = '';

                    if (isSizeAttr) {
                        sel = (!allSizesSelected && selectedValues.includes(opt.value)) ? 'selected' : '';
                    } else {
                        sel = (opt.value === attr.selected) ? 'selected' : '';
                    }

                    html += `<option value="${opt.value}" ${sel}>${opt.label}</option>`;
                });



                html += `</select>`;

                $('#attrSelectorWrap').append(html);
            });

            if ($.fn.select2) {
                $('.single-attr').each(function () {
                    let $el = $(this);
                    $el.select2({
                        width: '100%',
                        closeOnSelect: !$el.prop('multiple'),
                        dropdownParent: $('#attrModal .attr-modal-content')
                    });
                });
            }

        }, 'json');

    });

    // ====== زر Save ======
    $(document).on('click', '#saveAttrChanges', function (e) {
        e.preventDefault();

        if (!currentProductID) return;

        let items = {};

        $('.single-attr').each(function () {
            let tax = $(this).data('tax');

            if ($(this).prop('multiple')) {
                let vals = $(this).val() || [];

                if (!Array.isArray(vals)) {
                    vals = vals ? [vals] : [];
                }

                items[tax] = vals;
            } else {
                items[tax] = $(this).val();
            }
        });

        $.post(ajax_object.ajax_url, {
            action: 'styliiiish_save_attributes',
            nonce: ajax_object.nonce,
            product_id: currentProductID,
            items: items
        }, function (res) {

            if (!res || !res.success) {
                Swal.fire('Error', 'Error saving attributes.', 'error');
                return;
            }

            Swal.fire('Saved!', 'Attributes updated successfully.', 'success');

            closeAttrModal();
            
            // 🔥 بعد الحفظ — اعمل Pending Check
                $.post(ajax_object.ajax_url, {
                    action: "styliiiish_force_pending_check",
                    nonce: ajax_object.nonce,
                    product_id: currentProductID
                });

            if (typeof loadManageProductsPage === 'function') {
                loadManageProductsPage(1);
            }

        }, 'json');
    });

    // ====== زر Close ======
    $(document).on('click', '#closeAttrModal', function (e) {
        e.preventDefault();
        closeAttrModal();
    });

    // ====== الضغط على الخلفية ======
    $(document).on("click", ".attr-modal", function(e) {
        if ($(e.target).hasClass("attr-modal")) {
            closeAttrModal();
        }
    });







 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of Duplicate Products            ##################                              */
 /**************************** // Function Of Duplicate Products *******************************************/
   /**************************** // Function Of Duplicate Products *******************************************/
            
        // Duplicate
$(document).on("click", ".btn-duplicate", function (e) {
    e.preventDefault();

    let product_id = $(this).data("id");
    if (!product_id) return;

    Swal.fire({
        title: "Duplicate product?",
        text: "A draft copy will be created.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, duplicate",
        cancelButtonText: "Cancel"
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.post(ajax_object.ajax_url, {
            action: "styliiiish_duplicate_product",
            nonce: ajax_object.nonce,
            product_id: product_id
        }, function(response){

            if (!response || typeof response.success === 'undefined') {
                Swal.fire("Error", "Unexpected response.", "error");
                return;
            }

            if (!response.success) {
                Swal.fire("Error", response.data && response.data.message ? response.data.message : "Failed to duplicate.", "error");
                return;
            }

            Swal.fire("Done", "Product duplicated successfully.", "success");

            setTimeout(function () {
                loadManageProductsPage(1);
                scrollToManageProducts();
            }, 300);

        }, 'json').fail(function(){
            Swal.fire("Error", "Error communicating with server.", "error");
        });

    });
});

 








 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of Delete Products            ##################                              */
 /**************************** // Function Of Delete Products *******************************************/
   /**************************** // Function Of Delete Products *******************************************/
            
        // Delete
        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();

            let product_id = $(this).data("id");
            let $row = $(this).closest("tr");

            if (!product_id) return;

            Swal.fire({
                title: "Are you sure?",
                text: "This product will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {

                    $.post(ajax_object.ajax_url, {
                        action: "styliiiish_delete_product",
                        nonce: ajax_object.nonce,
                        product_id: product_id
                    }, function(response) {

                        if (!response || typeof response.success === 'undefined') {
                            Swal.fire("Error", "Unexpected response from server.", "error");
                            return;
                        }

                        if (!response.success) {
                            var msg = response.data && response.data.message ? response.data.message : "Failed to delete product.";
                            Swal.fire("Error", msg, "error");
                            return;
                        }

                        $row.fadeOut(200, function() {
                            $(this).remove();
                        });

                        Swal.fire("Deleted!", "Product removed successfully", "success");

                        // Reload manage-products list if available
                        try{
                            if (typeof loadManageProductsPage === 'function') {
                                var page = (window.currentFilters && window.currentFilters.page) ? window.currentFilters.page : 1;
                                loadManageProductsPage(page);
                            }
                        }catch(e){ }

                    }, 'json').fail(function () {
                        Swal.fire("Error", "Error communicating with server.", "error");
                    });
                }
            });
        });






 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of Status Of Products            ##################                              */
 /**************************** // Function Of Status Of Products *******************************************/
   /**************************** // Function Of Status Of Products *******************************************/

      // Inline Status
        $(document).on('change', '.inline-status', function () {
            var $select    = $(this);
            var product_id = $select.data('id');
            var status     = $select.val();
            var $row       = $select.closest('tr');

            $select.prop('disabled', true);

            $.post(ajax_object.ajax_url, {
                action: 'styliiiish_update_status',
                nonce: ajax_object.nonce,
                product_id: product_id,
                status: status
            }, function (response) {

                $select.prop('disabled', false);

                if (!response || typeof response.success === 'undefined') {
                    Swal.fire('Error', 'Unexpected response from server.', 'error');
                    return;
                }

                if (!response.success) {
                    var msg = response.data && response.data.message ? response.data.message : 'Error updating status.';
                    Swal.fire('Error', msg, 'error');
                    return;
                }

                $row.addClass('row-highlight');
                setTimeout(function(){ $row.removeClass('row-highlight'); }, 1200);

            }, 'json').fail(function () {
                $select.prop('disabled', false);
                Swal.fire('Error', 'Error communicating with server.', 'error');
            });
        });









 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of Categories Of Products            ##################                              */
 /**************************** // Function Of Categories Of Products *******************************************/
   /**************************** // Function Of Categories Of Products *******************************************/
let currentProduct = 0;


/* =========================
   Close Categories Modal
========================= */
function closeCatsModal() {

    $('#editCatsModal').fadeOut(150, function () {

        unlockScroll();

        $('html').removeClass('attr-modal-open');

        document.documentElement
            .style
            .setProperty('--scrollbar-compensation', '0px');

    });

    currentProduct = 0;
}


/* =========================
   Open Modal
========================= */
$(document).on("click", ".edit-cats-btn", function (e) {

    e.preventDefault();

    currentProduct = $(this).data("product");

    if (!currentProduct) return;


    /* Scrollbar Compensation */
    const scrollbarWidth =
        window.innerWidth - document.documentElement.clientWidth;

    document.documentElement.style
        .setProperty(
            '--scrollbar-compensation',
            scrollbarWidth + 'px'
        );


    $('html').addClass('attr-modal-open');

    lockScroll();


    /* Show Modal */
    $('#editCatsModal')
        .css('display', 'flex')
        .hide()
        .fadeIn(150);


    /* Loading */
    $('#cats-checkboxes')
        .empty()
        .append('<p style="margin:10px 0;">Loading categories...</p>');


    /* Load Data */
    $.post(ajax_object.ajax_url, {

        action: "styliiiish_get_cats",
        nonce: ajax_object.nonce,
        product_id: currentProduct

    }, function (res) {

        $('#cats-checkboxes').empty();

        if (!res || !res.success) {

            $('#cats-checkboxes')
                .append('<p>Error loading categories.</p>');

            return;
        }


        let html = "";

        res.data.forEach(function (cat) {

            html += `
              <label class="cat-row">
                <input type="radio"
                 name="single_cat"
                       value="${cat.id}"
                       ${cat.checked ? 'checked' : ''}>
                ${cat.name}
              </label>
            `;
        });


        $('#cats-checkboxes').append(html);

    }, 'json');

});


/* =========================
   Save
========================= */
$(document).on("click", "#saveCatsBtn", function (e) {

    e.preventDefault();

    if (!currentProduct) return;

    let btn = $(this);

    btn.prop('disabled', true).text('Saving...');


    let selected = [];

    $('#cats-checkboxes input:checked').each(function () {
        selected.push($(this).val());
    });


    $.post(ajax_object.ajax_url, {

        action: "styliiiish_save_cats",
        nonce: ajax_object.nonce,
        product_id: currentProduct,
        cats: selected

    }, function (res) {

        btn.prop('disabled', false).text('Save');

        if (!res || !res.success) {

            Swal.fire('Error', 'Save failed', 'error');

            return;
        }


        $('#cat-display-' + currentProduct)
            .text(res.data.names.join(', '));


        Swal.fire('Saved!', 'Categories updated.', 'success');


        closeCatsModal();

    }, 'json')

    .fail(function () {

        btn.prop('disabled', false).text('Save');

        Swal.fire('Error', 'Server error', 'error');
    });

});


/* =========================
   Close Button
========================= */
$(document).on("click", "#closeCatsBtn", function (e) {

    e.preventDefault();

    closeCatsModal();
});


/* =========================
   Click Outside
========================= */
$(document).on("click", "#editCatsModal", function (e) {

    if ($(e.target).is('#editCatsModal')) {
        closeCatsModal();
    }

});





/*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
 /*_______________________________________________________________________________________________________________________________________________________*/
/*         ############             Function Of Add New product           ##################                              */
 /**************************** // Function Of Add New product *******************************************/
   /**************************** // Function Of Add New product *******************************************/
           jQuery(document).on('click', '#styliiiish-add-product', function (e) {
                e.preventDefault();
            
                Swal.fire({
                    title: "Create new product?",
                    text: "A new product will be created from the template.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Create",
                    cancelButtonText: "Cancel"
                }).then((result) => {
            
                    if (!result.isConfirmed) return;
            
                    jQuery.post(ajax_object.ajax_url, {
            
                        action: 'styliiiish_add_new_product',
                        nonce: wfModal.nonce
            
                    }, function (response) {
            
                        if (!response || !response.success) {
            
                            Swal.fire(
                                "Error",
                                response?.data?.message || "Failed!",
                                "error"
                            );
            
                            return;
                        }
            
                        /* ==========================
                               SUCCESS
                            ========================== */
                            
                            let pid = response.data.new_id;   // ✅ خد الـ ID من السيرفر
                            
                            
                            // خزّن الـ ID
                            window.currentProductId = pid;
                            
                            // hidden input
                            jQuery('#currentProductId').val(pid);
                            
                            // افتح المودال
                            $('#addProductModal').fadeIn();
                                lockScroll();
                            
                            // ريست
                            if(typeof resetBuilder === 'function'){
                                resetBuilder();
                            }
                            
                            Swal.fire(
                               "Done!",
                               "Product created successfully.",
                               "success"
                            );

            
            
                    }, 'json')
            
                    .fail(function () {
            
                        Swal.fire(
                            "Error",
                            "AJAX request failed!",
                            "error"
                        );
            
                    });
            
                });
            });


	








/*         ############             IMAGE MODAL + MEDIA LIBRARY           ##################                              */
 /**************************** // IMAGE MODAL + MEDIA LIBRARY *******************************************/
   /**************************** // IMAGE MODAL + MEDIA LIBRARY *******************************************/
   
   
let previewSource = null; 
// "upload" | "main" | "server"

window.hasMainImage = false;
window.lastMainPreview = null;
let currentImageContext = "product"; // product | store
let currentTargetID = null;
let imageModalCache = {};
let previewLocked = false;


function setPreview(src){

   if(!src) return;

   $("#previewImg")
     .attr("src", src + "?v=" + Date.now())
     .fadeIn(150);

   $("#noImg").hide();
}

function resetPreview(){

   $("#previewImg").hide().attr("src","");
   $("#noImg").show();
}


// تحديث صورة المنتج داخل الجدول
function updateImageRow(prodID, mainHTML) {
    let row = $(`tr[data-row-id="${prodID}"] .styliiiish-image-wrapper`);
    if (!row.length) return;

    const overlay = `<div class="styliiiish-image-overlay">Edit image</div>`;

    if (mainHTML) {
        row.html(mainHTML + overlay);
    } else {
        row.html(`<div class="no-image">No image</div>${overlay}`);
    }
}

// API Helper
window.sendRequest = function(action, data, onSuccess) {
    $.post(ajax_object.ajax_url, { action, nonce: ajax_object.nonce, ...data }, function(response) {
        if (!response || response.success !== true) {
            Swal.fire("Error", response?.data?.message || "Error occurred.", "error");
            return;
        }
        onSuccess(response);
    }, "json").fail(function() {
        Swal.fire("Error", "Error communicating with server.", "error");
    });
}

/* ============================================================
   ADD / CHANGE IMAGE BUTTON
============================================================ */

$(document).on("click", "#styliiiish-add-image", function (e) {

    e.preventDefault();

    if (!currentImageProd) return;

    // OWNER → افتح مكتبة الوسائط
    if (ajax_object.mode === "owner") {
        if (typeof wp !== "undefined" && wp.media) {

            if (!window.styImageFrame) {
                window.styImageFrame = wp.media({
                    title: "Select Image",
                    button: { text: "Use Image" },
                    multiple: false
                });

                window.styImageFrame.on("select", function () {

                    let attachment = window.styImageFrame.state().get("selection").first().toJSON();

                    sendRequest(
                        "styliiiish_add_image_to_product",
                        {
                            product_id: currentImageProd,
                            attachment_id: attachment.id
                        },
                        function(response) {

                            // ✅ تحديث الكاش
                            imageModalCache[currentImageProd] = response.data.html;
                        
                            $("#styliiiish-images-list").html(response.data.html);
                            updateImageRow(currentImageProd, response.data.main);
                        }

                    );
                });
            }

            window.styImageFrame.open();
            return;
        }
    }

    // USER → رفع ملف مباشر
    $("#styliiiish-upload-input").click();
});




/* ============================================================
   USER IMAGE UPLOAD HANDLER
============================================================ */

$(document).on("change", "#styliiiish-upload-input", function () {

      let files = Array.from(this.files); // ✅ لازم يكون موجود هنا

   if (!files.length) return;

   // ❌ مفيش FileReader خلاص

   this.value = "";

   let index = 0;

   // UI Start
   $("#styliiiish-images-list").css("opacity","0.3");
   $("#styliiiish-lottie-loader").fadeIn(200);
   $("#styliiiish-upload-percent").text("Uploading… 0%");


    function uploadSingle(file){

        let formData = new FormData();


        if (currentImageContext === "store") {

            formData.append("action", "styliiiish_upload_store_image");
            formData.append("vendor_id", currentTargetID);
            formData.append("image_type", currentStoreImageType);

        } else {

            formData.append("action", "styliiiish_upload_image_custom");
            formData.append("product_id", currentImageProd);
        }


        formData.append("file", file);

        // Include nonce for upload requests
        formData.append('nonce', ajax_object.nonce);


        $.ajax({

           url: ajax_object.ajax_url,
           type: "POST",
           data: formData,
           dataType: "json",
           timeout: 120000, // 👈 مهم
           contentType: false,
           processData: false,


            xhr: function(){

                let xhr = new XMLHttpRequest();

                xhr.upload.addEventListener("progress", function(e){

                    if(e.lengthComputable){

                        let p = Math.round((e.loaded / e.total) * 100);

                        let global = Math.round(
                            ((index + p/100) / files.length) * 100
                        );

                       if(global < 100){

                           $("#styliiiish-upload-percent")
                             .text("Uploading… " + global + "%");
                        
                        }else{
                        
                           $("#styliiiish-upload-percent")
                             .text("Processing image…");
                        }

                    }

                });

                return xhr;
            },


            success: function(res){

               if(typeof res !== "object" || !res.success){
            
                  Swal.fire(
                    "Error",
                    res?.data?.msg || "Upload failed",
                    "error"
                  );
            
                  next();
                  return;
               }
            
               if(res.data.main_url){
                   
                   

               window.lastMainPreview = res.data.main_url;
            
               setPreview(res.data.main_url);
            }



            
               // Update UI
                imageModalCache[currentImageProd] = res.data.html;
                
                $("#styliiiish-images-list").html(res.data.html);
                
                updateImageRow(currentImageProd, res.data.main);
                
                // 👇 مرحلة انتقالية واضحة
                $("#styliiiish-upload-percent").text("Finalizing…");
                
                setTimeout(function(){
                   next();
                }, 150); // تأخير بسيط للـ UX

            },



            error: function(xhr, status){

               if(status === "timeout"){
            
                  Swal.fire(
                    "Timeout",
                    "Server is taking too long to process the image",
                    "warning"
                  );
            
               }
            
               $("#styliiiish-upload-percent").text("Retrying…");
                    next();

            }


        });
    }



    function next(){

        index++;

        if(index >= files.length){

            finish();
            return;
        }

        uploadSingle(files[index]);
    }



    function finish(){

       previewLocked = false;
       previewSource = "server";
    
       $("#styliiiish-upload-percent").text("Done ✔️");
    
       setTimeout(function(){
    
          $("#styliiiish-lottie-loader").fadeOut(300);
          $("#styliiiish-images-list").css("opacity","1");
          $("#styliiiish-upload-percent").text("");
    
       },600);
    }





    // Start first
    uploadSingle(files[0]);

});



/* ============================================================
   OPEN IMAGE MODAL
============================================================ */

$(document).on("click", ".styliiiish-image-cell", function () {

   currentImageProd = $(this).data("id");
   if (!currentImageProd) return;

   $("#styliiiishImageModal").css("display","flex");
        lockScroll();


   if (imageModalCache[currentImageProd]) {

      $("#styliiiish-images-list")
        .html(imageModalCache[currentImageProd]);

      return;
   }

   $("#styliiiish-images-list")
     .html("<p class='loading'>Loading...</p>");

   sendRequest(
      "styliiiish_get_images",
      { product_id: currentImageProd },
      function(response){

         imageModalCache[currentImageProd] = response.data.html;

         $("#styliiiish-images-list")
           .html(response.data.html);
      }
   );

});




$("#styliiiish-close-image-modal").on("click", function () {

    $("#styliiiishImageModal").hide();

    // 👇 Sync البريفيو بعد القفل
    // متعملش reset لو فيه main قبل كده
if(window.hasMainImage && window.lastMainPreview){
   setPreview(window.lastMainPreview);
}

unlockScroll(); // مهم
});


/* ============================================================
   SET MAIN IMAGE
============================================================ */

$(document).on("click", ".styliiiish-set-main", function (e) {

   e.preventDefault();

   const btn = $(this);

   const attachID = btn.data("attachment");

   if (!currentImageProd || !attachID){
      Swal.fire("Error","Invalid image","error");
      return;
   }

   btn.addClass("loading");

   sendRequest(
      "styliiiish_set_featured_image",
      {
         product_id: currentImageProd,
         attachment_id: attachID
      },
      function(res){

         btn.removeClass("loading");

         if(!res.success){
            Swal.fire("Error","Operation failed","error");
            return;
         }

         imageModalCache[currentImageProd] = res.data.html;

         $("#styliiiish-images-list").html(res.data.html);

         updateImageRow(currentImageProd, res.data.main);

         if(res.data.main_url){

           window.lastMainPreview = res.data.main_url;
           window.hasMainImage    = true;   // 👈 مهم
        
           setPreview(res.data.main_url);
        }


      }
   );
});


/* ============================================================
   REMOVE IMAGE
============================================================ */

$(document).on("click", ".styliiiish-remove-image", function (e) {

  e.preventDefault();

  const attachID = $(this).data("attachment");

  if (!currentImageProd || !attachID) return;

  Swal.fire({
    title: "Delete image?",
    icon: "warning",
    showCancelButton: true
  }).then((r)=>{

    if(!r.isConfirmed) return;

    sendRequest(
      "styliiiish_remove_image",
      {
        product_id: currentImageProd,
        attachment_id: attachID
      },
      function(response){

        if(!response.success){
           Swal.fire("Error","Delete failed","error");
           return;
        }

        imageModalCache[currentImageProd] = response.data.html;

        if(currentImageContext === "product"){

          $("#styliiiish-images-list").html(response.data.html);

          updateImageRow(currentImageProd, response.data.main);
        }

        if(!response.data.main && ajax_object.mode === "user"){
          changeProductStatus(currentImageProd,"uncomplete");
        }
      }
    );
  });

});


























let currentStoreImageType = null;

$(document).on("click", ".wf-pick-store-image", function (e) {
    e.preventDefault();

    currentImageContext   = "store";
    currentTargetID       = $(this).data("vendor-id");
    currentStoreImageType = $(this).data("type"); // cover | logo

    if (!currentTargetID || !currentStoreImageType) return;

    const $input = $("#styliiiish-upload-input");
    if (!$input.length) {
        console.error("styliiiish-upload-input not found on this page");
        return;
    }

    // عشان لو المستخدم اختار نفس الملف مرتين
    $input.val("");
    $input.trigger("click");
});






   // كود زر remove فى store profile 
$(document).on("click", ".wf-clear-media", function (e) {
    e.preventDefault();

    const target  = $(this).data("target");   // #wf_store_cover | #wf_store_logo
    const preview = $(this).data("preview");  // .wf-cover-preview | .wf-logo-preview

    // امسح القيمة
    $(target).val("");

    // Reset UI
    if (preview === ".wf-cover-preview") {
        $(preview)
            .css("background-image", "none")
            .html('<span>No cover selected</span>');
    }

    if (preview === ".wf-logo-preview") {
        $(preview).html(
            '<div class="wf-logo-placeholder">No logo</div>'
        );
    }
});









// Hide 'Media Library' tab for users
// Force-hide media library tab for users





function checkBeforeSave(field, value, el) {

    // نشتغل فقط على المنتجات Active
    let statusText = el.closest("tr").find(".sty-status").text().trim();
    if (!statusText.includes("Active")) {
        return Promise.resolve(true);
    }

    // =========================
    // 1) DESCRIPTION CHECK
    // =========================
    if (field === "post_content" || field === "description") {
        let wordCount = value.trim().split(/\s+/).length;

        if (wordCount < 20) {
            return Swal.fire({
               title: "Description Too Short",
                title: "Description Too Short",
                html: `
                    To keep your product active and visible to buyers, the description must be at least <b>20 words</b>.<br><br>
                    If you continue, the product will switch to <b>Incomplete</b> status, be hidden from customers, and will require review again once updated.
                `,
                icon: "warning",
                customClass: {
                popup: "sty-alert",
                confirmButton: "sty-btn-primary",
                cancelButton: "sty-btn-secondary",
            },
                showCancelButton: true,
                confirmButtonText: "Continue Anyway",
                cancelButtonText: "Continue Editing"
            }).then(r => r.isConfirmed);
        }
    }

    // =========================
    // 2) NAME CHECK
    // =========================
    if (field === "title" && value.trim().length < 3) {
        return Swal.fire({
            title: "Name Too Short",
                html: `
                    To keep your product active and visible to buyers, the name must be clear and contain at least <b>3 characters</b>.<br><br>
                    If you continue, the product will switch to <b>Incomplete</b> status, be hidden from customers, and will require review again once the name is properly updated.
                `,
            icon: "warning",
            customClass: {
            popup: "sty-alert",
            confirmButton: "sty-btn-primary",
            cancelButton: "sty-btn-secondary",
        },
            showCancelButton: true,
            confirmButtonText: "Continue Anyway",
            cancelButtonText: "Continue Editing"
        }).then(r => r.isConfirmed);
    }

    // =========================
    // 3) PRICE CHECK
    // =========================
    let numericPrice = parseFloat(value.replace(/[^\d.]/g, ''));

    if (field === "price" && (!numericPrice || numericPrice <= 0)) {
        return Swal.fire({
            title: "Invalid Price",
                html: `
                    To remain active and visible to buyers, your product must have a valid price greater than <b>0</b>.<br><br>
                    If you proceed, the product will be marked as <b>Incomplete</b>, removed from customer view, and will need to be reviewed again after you update the price.
                `,
            icon: "warning",
            customClass: {
            popup: "sty-alert",
            confirmButton: "sty-btn-primary",
            cancelButton: "sty-btn-secondary",
        },
            showCancelButton: true,
            confirmButtonText: "Continue Anyway",
            cancelButtonText: "Continue Editing"
        }).then(r => r.isConfirmed);
    }

    // =========================
    // 4) IMAGE CHECK (if removed)
    // =========================
    if (field === "image_removed") {  // لو هتعملها لاحقاً
        return Swal.fire({
            title: "Main Image Removed",
                html: `
                    To keep your product active and visible to buyers, a clear main image is required.<br><br>
                    If you continue, the product will switch to <b>Incomplete</b> status, be hidden from customers, and will require review again once a new main image is added.
                `,
            icon: "warning",
            customClass: {
            popup: "sty-alert",
            confirmButton: "sty-btn-primary",
            cancelButton: "sty-btn-secondary",
        },
            showCancelButton: true,
            confirmButtonText: "Continue Anyway",
            cancelButtonText: "Upload Image Now"
        }).then(r => r.isConfirmed);
    }

    // Passed ✓
    return Promise.resolve(true);
}
















/* ============================
   INLINE EDIT — TITLE / PRICE / DESCRIPTION
============================ */

$(document).on("blur", ".inline-edit", function () {

    let el     = $(this);
    let id     = el.data("id");
    let field  = el.data("field");
    let value  = el.text().trim();

    if (!id || !field) return;

    // ========== PRE-SAVE VALIDATION ==========
    checkBeforeSave(field, value, el).then(allowed => {

        if (!allowed) {
            el.focus();
            return;
        }

        // ========== AJAX SAVE ==========
        $.post(ajax_object.ajax_url, {
            action: "styliiiish_quick_update_product",
            nonce: ajax_object.nonce,
            product_id: id,
            field: field,
            value: value
        }, function (response) {

            if (!response || response.success !== true) {
                Swal.fire("Error", (response?.data?.message || "Update failed"), "error");
                return;
            }

            // DESCRIPTION FIX
            if (field === "post_content" || field === "description") {
                el.text(response.short || response.data?.short);
                el.attr("data-full", response.full || response.data?.full);
            } else {
                el.text(response.value || response.data?.value || value);
            }

            // Highlight save
            el.css({
                background: "#d4ffd4",
                transition: "0.3s"
            });

            setTimeout(() => {
                el.css("background", "transparent");
            }, 500);

            // Auto pending check
            $.post(ajax_object.ajax_url, {
                action: "styliiiish_trigger_pending_check",
                nonce: ajax_object.nonce,
                product_id: id
            });

        }, "json");

    });

});







$(document).on("change", "#styliiiish-filter-status-user", function () {

    let chosen = $(this).val();

    if (chosen === "publish") {
        currentFilters.status = "active";
    }
    else if (chosen === "pending") {
        currentFilters.status = "pending";
    }
    else if (chosen === "draft") {
        currentFilters.status = "uncomplete";
    }
    else if (chosen === "deactivated") {
        currentFilters.status = "deactivated";
    }
    else {
        currentFilters.status = "";
    }

    loadManageProductsPage(1);  // 🔥 نرسل page فقط
});








// User Deactivate Product
$(document).on('click', '.btn-deactivate-user', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const id   = $btn.data('id');
    if (!id) return;

    Swal.fire({
        title: 'Deactivate this dress?',
        text: 'It will stop appearing to customers until you activate it again.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, deactivate',
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.post(ajax_object.ajax_url, {
            action: 'styliiiish_user_deactivate_product',
            nonce: ajax_object.nonce,
            product_id: id
        }, function (res) {

            if (!res || !res.success) {
                Swal.fire('Error', res?.data?.message || 'Deactivation failed.', 'error');
                return;
            }

            const $row = $('tr[data-row-id="' + id + '"]');
            $row.find('td[data-label="Status"] span').first().replaceWith(res.data.status_html);

            // حوّل الزر لـ Activate
            $btn
                .removeClass('btn-deactivate-user')
                .addClass('btn-activate-user')
                .text('Activate');

            Swal.fire('Done', 'Dress deactivated.', 'success');

            // Reload manage-products list if available
            try{
                if (typeof loadManageProductsPage === 'function') {
                    var page = (window.currentFilters && window.currentFilters.page) ? window.currentFilters.page : 1;
                    loadManageProductsPage(page);
                }
            }catch(e){ }

        }, 'json');
    });
});





// User Activate Product
$(document).on('click', '.btn-activate-user', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const id   = $btn.data('id');
    if (!id) return;

    Swal.fire({
        title: 'Send for review?',
        text: 'Your dress will be checked. If complete → Pending, if not → Uncomplete.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes, send',
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.post(ajax_object.ajax_url, {
            action: 'styliiiish_user_activate_product',
            nonce: ajax_object.nonce,
            product_id: id
        }, function (res) {

            if (!res || !res.success) {
                Swal.fire('Error', res?.data?.message || 'Activation failed.', 'error');
                return;
            }

            const $row = $('tr[data-row-id="' + id + '"]');
            
            // ✔ تحديث شكل الحالة
            $row.find('td[data-label="Status"] span')
                .first()
                .replaceWith(res.data.status_html);

            // ✔ لو المنتج جه Pending
            if (res.data.status === 'pending') {

                Swal.fire('Sent!', 'Your dress is now Pending review.', 'success');

                // بقى Active؟ نخلي الزر Deactivate
                $btn
                    .removeClass('btn-activate-user')
                    .addClass('btn-deactivate-user')
                    .text('Deactivate');

            } 
            // ✔ لو رجع Draft → ناقص حاجة
            else if (res.data.status === 'draft') {
                
                Swal.fire('Incomplete', 'Some required fields are missing. Please complete them first.', 'warning');
                
                // ما نخليش الزر يروح Deactivate، لأنه مش Active أصلاً
                // نخليه زي ما هو (Activate)
            }

            // Reload manage-products list if available
            try{
                if (typeof loadManageProductsPage === 'function') {
                    var page = (window.currentFilters && window.currentFilters.page) ? window.currentFilters.page : 1;
                    loadManageProductsPage(page);
                }
            }catch(e){ }

        }, 'json');

    });
});










function changeProductStatus(productID, newStatus) {


    $.post(ajax_object.ajax_url, {
        action: "styliiiish_update_status",
        nonce: ajax_object.nonce,
        product_id: productID,
        status: newStatus
    }, function(response) {

        if (response && response.success) {

            // تحديث الحالة داخل الجدول
            let row = $(`tr[data-row-id="${productID}"]`);
            let label = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

            row.find(".sty-status").text(label);

            // تحديث CSS class
            row.removeClass("status-active status-pending status-uncomplete")
               .addClass("status-" + newStatus);

        } else {
            Swal.fire("Error", response?.data?.message || "Unable to update status.", "error");
        }

    }, "json");
}






// فتح نافذة اختيار الملف عند الضغط على الزر
 // Toggle business fields
  $(document).on('change', '#business_type', function () {
    const val = $(this).val();
    if (val === 'home') {
      $('#home_notice').show();
      $('#business_fields').hide();
    } else if (val) {
      $('#home_notice').hide();
      $('#business_fields').show();
    } else {
      $('#home_notice').hide();
      $('#business_fields').hide();
    }
  });

  // Open file dialog
  $(document).on('click', '.taj-file-btn', function (e) {
    e.preventDefault();
    const id = $(this).data('target');
    const $input = $('#' + id);
    if ($input.length) $input.trigger('click');
  });

  // Remove file
  $(document).on('click', '.taj-file-remove', function (e) {
    e.preventDefault();
    const id = $(this).data('target');
    const $input = $('#' + id);
    if (!$input.length) return;

    $input.val(''); // clear
    const $field = $input.closest('.taj-file-field');
    const $name  = $field.find('.taj-file-name');
    const empty  = $name.data('empty') || 'No file chosen';
    $name.text(empty);

    $field.find('.taj-file-preview').hide().empty();
  });

  // Show file name + preview
  $(document).on('change', 'input[type="file"]', function () {
    const $field = $(this).closest('.taj-file-field');
    const $name  = $field.find('.taj-file-name');
    const empty  = $name.data('empty') || 'No file chosen';

    if (!this.files || !this.files.length) {
      $name.text(empty);
      $field.find('.taj-file-preview').hide().empty();
      return;
    }

    const file = this.files[0];
    $name.text(file.name);

    // Preview image if possible
    if (file.type && file.type.indexOf('image/') === 0) {
      const reader = new FileReader();
      reader.onload = function (ev) {
        $field.find('.taj-file-preview')
          .html('<img src="'+ ev.target.result +'" alt="" />')
          .show();
      };
      reader.readAsDataURL(file);
    } else {
      $field.find('.taj-file-preview').hide().empty();
    }
  });














  
if ($('#styliiiish-manage-products-content').length) {

    setTimeout(function () {

        loadManageProductsPage(1);

    }, 200);

}










    });
  