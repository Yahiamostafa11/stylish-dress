
(function ($) {
    


    document.addEventListener('DOMContentLoaded', function () {

        /* =========================
           Language Switch
        ========================= */

        const langSwitch = document.getElementById('wf-lang-switch');
        const langHidden = document.getElementById('wf-lang-hidden');

        if (langSwitch) {

            langSwitch.addEventListener('change', function () {

                const val = this.value;

                if (langHidden) {
                    langHidden.value = val;
                }

                const url = new URL(window.location.href);
                url.searchParams.set('wf_lang', val);

                window.location.href = url.toString();
            });
        }


        /* =========================
           Welcome Banner Toggle
        ========================= */

        const toggle = document.getElementById('wf-toggle-banner');
        const box    = document.querySelector('.wf-welcome-settings');

        if (toggle && box) {

            const updateBox = () => {

                box.classList.toggle('active', toggle.checked);
                box.classList.toggle('disabled', !toggle.checked);
            };

            updateBox();

            toggle.addEventListener('change', updateBox);
        }


        /* =========================
           Commission Preview
        ========================= */

        const typeSelect  = document.querySelector('.wf-commission-type');
        const valueInput  = document.querySelector('.wf-commission-value');
        const unitSpan    = document.querySelector('.wf-unit');
        const previewText = document.querySelector('.wf-preview-text');

        if (typeSelect && valueInput && unitSpan && previewText) {

            const updateCommissionUI = () => {

                const type  = typeSelect.value;
                const value = parseFloat(valueInput.value) || 0;

                unitSpan.textContent = type === 'fixed' ? 'EGP' : '%';

                const basePrice = 500;

                let commission = 0;
                let finalPrice = basePrice;

                if (type === 'percent') {

                    commission = (basePrice * value) / 100;
                    finalPrice = basePrice + commission;

                } else {

                    commission = value;
                    finalPrice = basePrice + value;
                }

                previewText.textContent =
                    `Vendor: ${basePrice} → Customer: ${finalPrice.toFixed(2)} → Platform: ${commission.toFixed(2)}`;
            };

            typeSelect.addEventListener('change', updateCommissionUI);
            valueInput.addEventListener('input', updateCommissionUI);

            updateCommissionUI();
        }


        /* =========================
           Save Button UX
        ========================= */

        const form = document.querySelector('.wf-settings-cards');
        const btn  = document.querySelector('.wf-save-btn');

        if (form && btn) {

            form.addEventListener('submit', function () {

                btn.innerHTML = '⏳ Saving...';
                btn.disabled = true;
                btn.style.opacity = '.7';

            });
        }

    });


    /* =========================
       Action Forms Toggle
    ========================= */

    document.addEventListener('click', function (e) {

        const trigger = e.target.closest('.wf-action-trigger');
        const form    = e.target.closest('.wf-action-form');

        if (trigger && form) {

            document.querySelectorAll('.wf-action-form.active').forEach(f => {
                if (f !== form) f.classList.remove('active');
            });

            form.classList.toggle('active');
            e.stopPropagation();
            return;
        }

        if (form) {
            e.stopPropagation();
            return;
        }

        document.querySelectorAll('.wf-action-form.active').forEach(f => {
            f.classList.remove('active');
        });

    });


    document.addEventListener('keydown', function (e) {

        if (e.key === 'Escape') {

            document.querySelectorAll('.wf-action-form.active').forEach(f => {
                f.classList.remove('active');
            });
        }

    });


    /* =========================
       Category Attributes (AJAX)
    ========================= */

    $(function () {

        function loadCatAttrs(cat) {

            $.post(ajax_object.ajax_url, {
                action: 'wf_get_saved_cat_attrs',
                cat_id: cat,
                nonce: ajax_object.nonce

            }, function (res) {

                if (!res.success) return;

                $('.wf-attr-check').prop('checked', false);

                res.data.forEach(function (val) {

                    $('.wf-attr-check[value="' + val + '"]')
                        .prop('checked', true);

                });

            });
        }


        $('#wf-cat-select').on('change', function () {

            const cat = $(this).val();

            loadCatAttrs(cat);
        });


        const first = $('#wf-cat-select').val();

        if (first) {
            loadCatAttrs(first);
        }

    });


})(jQuery);
