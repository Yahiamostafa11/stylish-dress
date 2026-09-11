<div id="paymob-elements"></div>
<script>
    window.hasEmptyFields = false;
  

    window.addEventListener('load', function () {           
         updateCheckoutData(); 
        });
        jQuery(document).ready(function ($) {
			$(document).on('change', 'input[name="payment_method"]', function () {
			if (this.value === 'paymob-pixel') {
				updateCheckoutData();
				console.log('updateCheckoutData() called on user change to paymob-pixel');
			}
		});
        // Function to toggle the visibility of the Place Order button
        function togglePlaceOrderButton() {
            const selectedPaymentMethod = $('input[name="payment_method"]:checked').val(); // Get selected payment method
            
            if (selectedPaymentMethod === 'paymob-pixel') {
                // Hide the Place Order button for 'paymob-pixel'
                $('#place_order').hide();
            } else {
                // Show the Place Order button for other payment methods
                $('#place_order').show();
            }
        }

        // Run on page load
        togglePlaceOrderButton();

        // Attach event listener to payment method radio buttons
        $(document).on('change', 'input[name="payment_method"]', togglePlaceOrderButton);

        // Reapply toggle logic after checkout updates
        $(document.body).on('updated_checkout', togglePlaceOrderButton);
        $('form.checkout').on('submit', function (event) {
            // Loop through required checkout fields
            $('.woocommerce-billing-fields input, .woocommerce-billing-fields select').each(function () {
                if ($(this).prop('required') && $(this).val().trim() === '') {
                    window.hasEmptyFields = true;
                    $(this).css('border', '1px solid red'); // Highlight the empty field
                } else {
                    $(this).css('border', ''); // Remove the border for filled fields
                }
            });
        });
    });

    if (jQuery('#place_order').length && jQuery('input[name="payment_method"]:checked').val() === 'paymob-pixel' && window.hasEmptyFields === false) {
        jQuery('#place_order').on('click', function (event) { 
            event.preventDefault(); // Prevent default form submission
            const payFromOutside = new Event('payFromOutside');
            window.dispatchEvent(payFromOutside);
            const updateIntentionData = new Event('updateIntentionData');
            window.dispatchEvent(updateIntentionData);
            // await new Promise(res => setTimeout(() => res(''),5000))
            return false; 
        });
    }
    jQuery('#place_order').on('click', function (event) {
        if(jQuery('button[type="submit"][name="woocommerce_checkout_place_order"]').length =1){
            jQuery('button[type="submit"][name="woocommerce_checkout_place_order"]').submit();
        }
        if(jQuery('input[name="woocommerce_checkout_place_order"]').length =1){
            jQuery('input[name="woocommerce_checkout_place_order"]').submit();
        }else{
            console.info('submit not triggered')
        }
    });



</script>
