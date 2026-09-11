let styScrollPosition = 0;

jQuery(document).ready(function ($) {

    // ✅ افتح الـ Size Guide
    $(document).on('click', '#sty-size-guide-btn', function (e) {
        e.preventDefault();

        styScrollPosition = window.pageYOffset || document.documentElement.scrollTop;

        $('body').css({
            position: 'fixed',
            top: -styScrollPosition + 'px',
            width: '100%'
        });

        $('html, body').addClass('sty-modal-open');
        $('#sty-size-guide-modal').addClass('active');
    });

    // ✅ اغلاق بزر X
    $(document).on('click', '.sty-close', function () {
        closeSizeGuide();
    });

    // ✅ اغلاق عند الضغط على الخلفية
    $(document).on('click', '#sty-size-guide-modal', function (e) {
        if ($(e.target).is('#sty-size-guide-modal')) {
            closeSizeGuide();
        }
    });

    // ✅ Highlight حسب المقاس
    $(document).on('change', 'form.variations_form select[name*="size"]', function () {
        let size = $(this).val();
        if (!size) return;

        size = size.toLowerCase();
        $('#sty-size-table tr').removeClass('active-size');
        $('#sty-size-table tr[data-size="' + size + '"]').addClass('active-size');
    });

});

function closeSizeGuide() {
    jQuery('#sty-size-guide-modal').removeClass('active');
    jQuery('html, body').removeClass('sty-modal-open');

    jQuery('body').css({
        position: '',
        top: '',
        width: ''
    });

    window.scrollTo(0, styScrollPosition);
}

// ✅ Close on ESC
jQuery(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
        closeSizeGuide();
    }
});





