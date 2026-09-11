window.ManageProductsModule = {

    init: function() {
        this.cacheDom();
        this.bindEvents();
        this.loadPage(1);
    },

    cacheDom: function() {
        this.container = $('#styliiiish-manage-products-content');
    },

    bindEvents: function() {
        const self = this;

        // Search
        $(document).on('keyup', '#styliiiish-search', function(){
            clearTimeout(self.searchTimer);
            self.searchTimer = setTimeout(function(){
                self.filters.search = $('#styliiiish-search').val();
                self.loadPage(1);
            }, 300);
        });

        // Pagination
        $(document).on('click', '.styliiiish-page-link', function(e){
            e.preventDefault();
            let page = $(this).data('page');
            self.loadPage(page);
        });

        // Sort change
        $(document).on('change', '#styliiiish-sort', function(){
            self.filters.sort = $(this).val();
            self.loadPage(1);
        });

        // Per-row (columns) change
        $(document).on('change', '#styliiiish-per-row', function(){
            self.filters.per_row = parseInt($(this).val()) || 3;
            self.loadPage(1);
        });

    },

    filters: {
        search: '',
        cat: '',
        status: '',
        sort: 'date_desc',
        per_row: 3,
        page: 1
    },

    loadPage: function(page) {
        const self = this;

        self.filters.page = page || 1;
        self.showSkeleton();

        $.post(ajax_object.ajax_url, {
            action: 'styliiiish_manage_products_list',
            nonce: ajax_object.nonce,
            ...self.filters
        }, function(response){
            self.container.html(response);
            // Apply per_row as a CSS variable so cards CSS can adjust
            try{
                var cols = parseInt(self.filters.per_row) || 3;
                self.container.find('.sty-cards-grid').css('--cards-cols', cols);
            }catch(e){}
        });
    },

    showSkeleton: function() {
        this.container.html('<div class="skeleton">Loading...</div>');
    }
};
