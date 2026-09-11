jQuery(function($){

    // Detect and load Manage Products module
    if ($('.styliiiish-manage-products-content').length) {

        if (typeof window.ManageProductsModule !== 'undefined') {
            window.ManageProductsModule.init();
        }
    }

});
