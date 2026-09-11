window.isValid =  true;
window.isCardValid = false;
window.scriptInitialized = false;
window.googleenabled =0;

// Trigger form submission
if (typeof pxl_object !== 'undefined')
{
    window.googleenabled = pxl_object.googleenabled;
   
}
if (typeof window.wc !== 'undefined' && typeof window.wp !== 'undefined' && typeof window.wc.wcSettings !== 'undefined' && typeof window.wc.wcBlocksRegistry !== 'undefined') {
   
    const settings = window.wc.wcSettings.getSetting('paymob-pixel_data', {});
    const label = window.wp.htmlEntities.decodeEntities(settings.title) || window.wp.i18n.__('Paymob Pixel Payment', 'paymob-woocommerce');
    const Icon = () => {
        return settings.icon
            ? window.wp.element.createElement('img', {
                src: settings.icon,
                id: 'paymob-pixel-logo',
                style: {
                    maxWidth: '70px',
                    float: 'right',
                    paddingTop: '6px'
                }
            })
            : null;
    };
   
  let contentInitialized = false;
    const Content = () => {
        if (!contentInitialized) {
            contentInitialized = true;
           
            // Load scripts and initialize Paymob element
            loadScriptsAndInitializePaymob();
        }      
        const selectedGateway = document.querySelector(
            'input[name="radio-control-wc-payment-method-options"]:checked'
        );
        if ( selectedGateway && selectedGateway.value === 'paymob-pixel') {
           
            const buttonSelectors = [
                '.wc-block-components-button',
                '.custom-checkout-button',
                '#theme-specific-button-id'
            ];
        
            buttonSelectors.forEach(selector => {
                if (jQuery(selector).length) {
                    updatePlaceOrderVisibility();
                }
            });
        }
        return window.wp.element.createElement('div', { id: 'paymob-elements' });
    };

    const LabelWithIcon = () => {
        return window.wp.element.createElement('span', { style: { width: '100%' } }, label, window.wp.element.createElement(Icon));
    };

    const Block_Gateway = {
        name: 'paymob-pixel',
        label: window.wp.element.createElement(LabelWithIcon),
        content: window.wp.element.createElement(Content),
        edit: window.wp.element.createElement(Content),
        canMakePayment: () => true,
        ariaLabel: label,
        supports: {
             features:  [
                'products',
                'refunds',
                'subscriptions',
                'subscription_cancellation',
                'subscription_suspension',
                'subscription_reactivation',
                'subscription_amount_changes',
                'subscription_date_changes',
                'subscription_payment_method_change',
                'subscription_payment_method_change_customer',
                'subscription_payment_method_change_admin',
                'multiple_subscriptions',
            ],
        },
    };

    window.wc.wcBlocksRegistry.registerPaymentMethod(Block_Gateway);

    // Append the dynamic CSS
    const css = `
         html[lang="en"] #paymob-pixel-logo {
             float: right !important;
         }
         html[lang="ar"] #paymob-pixel-logo {
             float: left !important;
         }
        `;
       
    const style = document.createElement('style');
    style.appendChild(document.createTextNode(css));
    document.head.appendChild(style);

    // Function to load Paymob scripts and initialize element
    function loadScriptsAndInitializePaymob() {
        isCheckoutFormValid();
        // const googleenabled =pxl_object.googleenabled;
        // loadScripts();
        const { select } = wp.data;
        const cartStore = select('wc/store/cart');
        const cartTotals = cartStore.getCartTotals();
        //const totalAmount = cartTotals ? cartTotals.total_price : null;
        const totalAmount = (parseInt(cartTotals.total_price, 10) /
            10 ** cartTotals.currency_minor_unit);

        const billingData = cartStore.getCustomerData().billingAddress;

        document.addEventListener("click", function(event) {
            // Check if the clicked element is a payment method radio button
            if (event.target.id.startsWith("radio-control-wc-payment-method-options-")) {
                // Hide the button if "pixel" payment method is selected
                if (event.target.id.includes("paymob-pixel")) {
                    showLoadingMessage();
                    ajaxCall(billingData, totalAmount);
                   
                } else {
                    // Show the button for other payment methods
                    jQuery('.wc-block-checkout__form .wc-block-components-button').show(); 
                    jQuery('.wc-block-components-checkout-place-order-button').show();

                    const placeOrderSelectors = [
                        '.wc-block-checkout__form .wc-block-components-button',
                        '.wc-block-components-checkout-place-order-button',
                        '#place_order'
                    ];
                
                    
                    placeOrderSelectors.forEach(selector => {
                        jQuery(selector).show().prop('disabled', false);
                    });
                    

                    jQuery('#place_order').show();
                }
            }
        });
       
        onload = () => {
            setTimeout(function () {
                ajaxCall(billingData, totalAmount);
                console.log('billing data info' + billingData);
            }, 500); // Adjust the delay as needed
        };
        
    }
    document.addEventListener('DOMContentLoaded', function () {
        const checkoutContainer = document.querySelector('.wc-block-checkout');

        if (checkoutContainer) {
            const observer = new MutationObserver(async() => {
                const placeOrderButton = document.querySelector('.wc-block-components-checkout-place-order-button');

                if (placeOrderButton) {
                    placeOrderButton.addEventListener('click', function (e) {
                        e.preventDefault();
                        const selectedGateway = document.querySelector(
                            'input[name="radio-control-wc-payment-method-options"]:checked'
                        );
                        if (isCheckoutFormValid() === true && selectedGateway && selectedGateway.value === 'paymob-pixel') {
                            console.log('Paymob Pixel selected.');
                            const payFromOutside = new Event('payFromOutside');
                            window.dispatchEvent(payFromOutside);
                            window.dispatchEvent(new Event('updateIntentionData'));
                        }
                    });
                    observer.disconnect();
                }
            });
            observer.observe(checkoutContainer, { childList: true, subtree: true });
        }
        // hide place order in loading page  
        setTimeout(function checkButton() {
            const placeOrderBtn = document.querySelector('.wc-block-checkout__form .wp-block-button__link,.wc-block-checkout__form .wc-block-components-button, .wc-block-checkout__form button[type="submit"]');
            
            if (placeOrderBtn) {
                placeOrderBtn.style.display = 'none';
            } else {
                setTimeout(checkButton, 500); // Retry after 500ms
            }
        }, 1000); // Initial delay of 1 second


    });
}

function isCheckoutFormValid() {
    let isValid = true; // Assume valid, but check each field

    jQuery('.wc-block-components-form input[required], .wc-block-components-form select[required]').each(function () {
        const field = jQuery(this);
        if (field.val().trim() === '') {
            isValid = false;
            field.addClass('woocommerce-invalid'); // Highlight invalid field
            field.closest('.form-row').addClass('woocommerce-invalid-row'); // Highlight row
        } else {
            field.removeClass('woocommerce-invalid'); // Remove invalid highlight
            field.closest('.form-row').removeClass('woocommerce-invalid-row'); // Remove row highlight
        }
    });

    window.isValid = isValid;
    updatePlaceOrderVisibility();
    return isValid;
}


function updateCheckoutData(forcereload = false) {
    if(!forcereload)
        showLoadingMessage();
    var billingData = {
        first_name: jQuery('#billing_first_name').val(),
        last_name: jQuery('#billing_last_name').val(),
        email: jQuery('#billing_email').val(),
        phone: jQuery('#billing_phone').val(),
        address: jQuery('#billing_address_1').val(),
        city: jQuery('#billing_city').val(),
        postcode: jQuery('#billing_postcode').val(),
        country: jQuery('#billing_country').val(),
        state: jQuery('#billing_state').val(),
    };
    
    var totalAmount = jQuery('.order-total .amount').text().replace(/[^0-9.]/g, '');
    if(totalAmount == null){
        var totalAmount = jQuery('#order_review').find('.order-total .woocommerce-Price-amount').text().replace(/[^0-9.]/g, ''); // Extract total amount
    }
    ajaxCall(billingData, totalAmount, forcereload);
}
// loadScripts();
function loadScripts() {
    // alert(window.googleenabled);
    const paymobScript = document.createElement('script');
    paymobScript.src = "https://cdn.jsdelivr.net/npm/paymob-pixel@latest/main.js";
    paymobScript.type = "module";
    paymobScript.async = true;
    document.head.appendChild(paymobScript);
   
    // const paymobMainCss = document.createElement("link");
    // paymobMainCss.href = "https://cdn.jsdelivr.net/npm/paymob-pixel@latest/main.css";
    // paymobMainCss.rel = "stylesheet";
    // document.head.appendChild(paymobMainCss);

    if (window.googleenabled == 1 ) {
        const googlePayScript = document.createElement('script');
        googlePayScript.src = "https://pay.google.com/gp/p/js/pay.js";
        document.head.appendChild(googlePayScript);
    }
    // const paymobStyleCss = document.createElement("link");
    // paymobStyleCss.href = "https://cdn.jsdelivr.net/npm/paymob-pixel@latest/styles.css";
    // paymobStyleCss.rel = "stylesheet";
    // document.head.appendChild(paymobStyleCss);

    
}
function initializePaymobElement(key, cs) {
    // alert(key+" --- "+ cs)
    jQuery('.wc-block-store-notice').hide() ;
    var forcesavecard = false;
    var customArr = {};

    if (pxl_object.forcesavecard == 1) {
        forcesavecard = true;
    }

    var showsavecard = false;
    if (pxl_object.showsavecard == 1) {
        showsavecard = true;
    }

    var paymentMethods = [];
    if (pxl_object.cardsenabled == 1) {
        paymentMethods.push("card");
    }
    if (pxl_object.googleenabled == 1) {
        paymentMethods.push("google-pay");

    }
    if (pxl_object.appleenabled == 1) {
        paymentMethods.push("apple-pay");

    }

    var customStyles = pxl_object.customize;
    if (customStyles.font_family !== "" || customStyles.font_family !== null) {
        customArr = {
            Font_Family: customStyles.font_family,
            Font_Size_Label: customStyles.font_family,
            Font_Size_Input_Fields: customStyles.font_size_input_fields,
            Font_Size_Payment_Button: customStyles.font_size_payment_button,
            Font_Weight_Label: customStyles.font_weight_label,
            Font_Weight_Input_Fields: customStyles.font_weight_input_fields,
            Font_Weight_Payment_Button: customStyles.font_weight_payment_button,
            Color_Container: customStyles.color_container,
            Color_Border_Input_Fields: customStyles.color_border_input_fields,
            Color_Border_Payment_Button: customStyles.color_border_payment_button,
            Radius_Border: customStyles.radius_border,
            Color_Disabled: customStyles.color_disabled,
            Color_Error: customStyles.color_error,
            Color_Primary: customStyles.color_primary,
            Color_Input_Fields: customStyles.color_input_fields,
            Text_Color_For_Label: customStyles.text_color_for_label,
            Text_Color_For_Payment_Button: customStyles.text_color_for_payment_button,
            Text_Color_For_Input_Fields: customStyles.text_color_for_input_fields,
            Color_For_Text_Placeholder: customStyles.color_for_text_placeholder,
            Width_of_Container: customStyles.width_of_container + '%',
            Vertical_Padding: customStyles.vertical_padding,
            Vertical_Spacing_between_components: customStyles.vertical_spacing_between_components,
            Container_Padding: customStyles.container_padding
        };
    }
    
    hideLoadingIndicator();
    new Pixel({
        publicKey: key,
        clientSecret: cs,
        paymentMethods: paymentMethods,
        elementId: 'paymob-elements', // The ID of the HTML element for rendering Paymob's element
        disablePay: true,
        showSaveCard: showsavecard,
        forceSaveCard: forcesavecard,
       
        beforePaymentComplete: async (paymentmethod) => {
            console.log('Before Payment Complete');
            console.log('Payment Method '+ paymentmethod);

            if(paymentmethod== 'google-pay' || paymentmethod== 'apple-pay')
            {
                if (jQuery('.wc-block-checkout').length) {
                    if(isCheckoutFormValid() === true){
                        console.log('Block checkout - Create order manually for '+ paymentmethod);
                        handleOrderCreation();
                        console.log('Order Created  successfully.');
                        // jQuery('.wc-block-checkout').submit();
                        return true ;
                    }else{
                        displayWooCommerceError("Please fill the Required Information to complete your payment.");
                        return false;
                    }
                } else {
                    // Trigger form submission
                    console.log('Classic Checkout - Create order manually for '+ paymentmethod);
                    // const wooform = jQuery('form.checkout');
                    // jQuery(wooform).append('<input type="hidden" id="pxl_submit" name="pxl_submit" value="pxl_submit">');
                  // First, check if Terms & Conditions checkbox is checked
                    if (jQuery('#terms').length) {
                        // Check if it's an input of type checkbox
                        if (jQuery('#terms').is('input[type="checkbox"]')) {
       
                            if (!jQuery('#terms').is(':checked')) {
                                displayWooCommerceError("Please read and accept the terms and conditions to proceed with your order.");
                                return false;
                            }
                        }
                    }

                    // Then, validate the form
                    if (validateClassicFrom() === false) {
                        return await new Promise((resolve) => {
                            console.log('Classic Checkout - Triggering order submission');
                            
                            // Trigger the WooCommerce place order button
                            jQuery('form.checkout button[name="woocommerce_checkout_place_order"]').trigger('click');

                            // Wait 5 seconds for WooCommerce to create the order
                            setTimeout(() => {
                                console.log('Classic Checkout - Resolve after delay');
                                resolve(true);
                            }, 5000);
                        });
                    } else {
                        displayWooCommerceError("Please fill the Required Information to complete your payment");
                        return false;
                    }


                }
            }
            else
            {
                try {
                    if (jQuery('.wc-block-checkout').length) {
                        // For block-based checkout
                        // return await new Promise((resolve) => {
                        console.log('Block-based checkout detected for '+ paymentmethod);
                       // showLoadingIndicator("Please wait while we direct you to Bank's OTP Page .");
                        window.dispatchEvent(new Event('updateIntentionData'));
                        await new Promise(res => setTimeout(() => res(''),5000));
                        return true;
                    } else {
                        // Trigger form submission
                        console.log('Classic checkout detected for '+ paymentmethod);

                        const form = jQuery('form.checkout');
                        // Add a custom event listener to capture errors
                        return await new Promise((resolve) => {
                            form.on('checkout_error', function (e, errorMessages) {
                                console.log('Checkout Error:', errorMessages);
                                // Stop form submission
                                form.unblock({ message: null});

                                resolve(false);
                                hideLoadingIndicator();
                                return false;
                            });
                            // If the checkout completes successfully
                            form.on('checkout_place_order_success', function () {
                                console.log('Checkout successful.');
                                //showLoadingIndicator("Please wait while we direct you to Bank's OTP Page .");
                                form.unblock({ message: null});

                                resolve(true);
                                // window.dispatchEvent(new Event('updateIntentionData'));
                                // console.log('updateIntentionData');
 
                                // Trigger the form submission
                                form.submit();
                            });
                        }, 5000);
                    }
                   
                } catch (error) {
                    hideLoadingIndicator();
                    console.log('An unexpected error occurred:', error);
                    return false;
                }

            }

         
            hideLoadingIndicator();
        },
        afterPaymentComplete: async (response) => {
             hideLoadingIndicator();
            console.info(response);
                
            // Fetch the order ID from the server
            const order = await jQuery.ajax({
                url: pxl_object.ajax_url,
                type: 'POST',
                async:false,
                data: {
                    action: 'get_order_id_from_session',
                    security: pxl_object.update_checkout_nonce,
                },
            });
            // Check if the response contains the order ID
            if (order && order.success === true && order.data && order.data.order_id) {
               console.log('Order ID is available', order);
               var merchant_order_id = order.data.order_id;
            } else {
                console.log('Order ID is not available or invalid:', order);
            }
            console.info(response);
            if(typeof response.data === 'undefined' && typeof response?.data?.data?.redirect_url !== 'undefined'){
                // in case of Oman Net after OTP valid/invalid
                    window.location.href =  response.data.data.redirect_url;// Update the browser's URL
          		return true;
            }else{

 	showLoadingIndicator("Please wait while we process your transaction.");
                // in case of cards / Apple / google
                
                try {
                    if(typeof response.res !== 'undefined'){
                        // Indicate that after-payment processing is complete
                        response.res.data.errmsg =response.res.data?.['data.message']
                        response.res.data.afterpayment = true;
                        response.res.data.merchant_order_id = merchant_order_id;
                        callbackAjaxCall(response.res.data, null);
                    }else{
                        // Indicate that after-payment processing is complete
                        response.data.afterpayment = true;
                        response.data.merchant_order_id = merchant_order_id;
                    }
                    console.log('After Payment Complete');
                    console.log('Response Data:', response);
                    console.log('Response Data:', response.data.error);
                    console.log('Merchant Order ID:', response.data.merchant_order_id);
             
                    console.log('Merchant Order ID:------', response.data.merchant_order_id);

                    // Simulate a delay (if necessary)
                    await new Promise((res) => setTimeout(res, 5000));
                    // If merchant_order_id is set, proceed with the callback
                    if (response.data.merchant_order_id  && (response.data['success'] == 'true' && response.data['pending'] == 'false') 
                        || (response.data['success'] == 'false' && response.data['pending'] == 'false')) {
                        console.log('before callbackAjaxCall function');
                        callbackAjaxCall(response.data, null);
                        return true;
                    } 
		if(typeof response.data.redirect_url !== 'undefined'){
	                        console.log('inside URL ', response.data.redirect_url);
		     // in case of Oman Net after OTP valid/invalid
	         		window.location.href =  response.data.redirect_url;// Update the browser's URL
			return true;
          		}
                    // Handle error messages if present
                    const errorMessage = response.data.error || response.data?.['data.message'];

                    if (errorMessage !== null && errorMessage !== 'undefined') {
                        console.log('inside errorMessage' + errorMessage);
                        response.data.errmsg = errorMessage;

                        const redirectUrl = new URL(window.location);
            
                        // Remove existing gateway error parameter if present
                        if (redirectUrl.searchParams.has('gatewayerror')) {
                            redirectUrl.searchParams.delete('gatewayerror');
                            window.history.pushState({}, '', redirectUrl); // Update the browser's URL
                        }
                        console.log('before callbackAjaxCall function with gatewayerror');

                        // Add the new gateway error parameter and handle the error
                        redirectUrl.searchParams.set('gatewayerror', errorMessage);
                        displayWooCommerceError(errorMessage);
                        callbackAjaxCall(response.data, redirectUrl.toString());
            
                        return false; // Stop further processing
                    }else {
                        console.log('Merchant Order ID is null or undefined.');
                    }
                    hideLoadingIndicator();
                } catch (error) {
                    // Handle any unexpected errors
                    console.log('Error during afterPaymentComplete:', error);
                    hideLoadingIndicator();

                }
            }
        },
        
        onPaymentCancel: (response) => {
            console.log('Payment has been canceled');
            response.data.afterpayment = true;
            callbackAjaxCall(response.data, null);
        }, 
        cardValidationChanged: (isValid) => {
            console.log(isValid);
            window.isCardValid = isValid === 'true' || isValid === true;
            updatePlaceOrderVisibility();
        },   
        customStyle: customArr,
    });
}

function updatePlaceOrderVisibility() {
    console.log("Validation Status:", window.isValid, window.isCardValid);

    const placeOrderSelectors = [
        '.wc-block-checkout__form .wc-block-components-button',
        '.wc-block-components-checkout-place-order-button',
        '#place_order'
    ];
    if (window.isValid && window.isCardValid) {

        placeOrderSelectors.forEach(selector => {
            jQuery(selector).show().prop('disabled', false);
        });
    } else {
        placeOrderSelectors.forEach(selector => {
            jQuery(selector).hide().prop('disabled', true);
        });
    }
}



function validateClassicFrom(){
window.hasEmptyFields = false;
jQuery('.woocommerce-billing-fields input[aria-required], .woocommerce-billing-fields select[aria-required]').each(function () {
  if (jQuery(this).val().trim() === '') {
      window.hasEmptyFields = true;
      jQuery(this).css('border', '1px solid red'); // Highlight the empty field
      return window.hasEmptyFields;
  }
});
// Check if #terms exists on the page
if (jQuery('#terms').length) {
    // Check if it's an input of type checkbox
    if (jQuery('#terms').is('input[type="checkbox"]')) {
        if (!jQuery('#terms').is(':checked')) {
            hasEmptyFields = true;
            jQuery('#terms').closest('label').css('color', 'red'); // Highlight terms label
        } else {
            jQuery('#terms').closest('label').css('color', ''); // Clear highlight
        }
    }
}

return window.hasEmptyFields;
}
function ajaxCall(billingData, totalAmount, forcereload = false) {
    console.log("billingData", billingData);
    console.log("totalAmount", totalAmount);
    if(jQuery("#paymob-elements").children().length == 0 || forcereload ==true){
        setTimeout(() => {
            const paymentMethod = 'paymob-pixel'; // Replace with the desired payment method ID
            const paymentBlockInput = jQuery(`input[name="radio-control-wc-payment-method-options"][value="${paymentMethod}"]`);
            const paymentClassicInput = jQuery('#payment_method_'+paymentMethod);
            if (paymentBlockInput.length > 0 || paymentClassicInput.length > 0) {
             showLoadingMessage(true);
                    showLoadingIndicator("Loading Checkout. Please wait.");
                    console.log('After 5 milli seconds');
                }
        }, 500);
        jQuery.ajax({
            url: pxl_object.ajax_url,
            type: 'POST',
            // async: false,
            data: {
                action: 'update_pixel_data',
                security: pxl_object.update_checkout_nonce,
                billing_data: billingData,
                total_amount: totalAmount
            },
            success: function (response) {
                console.log('Checkout data updated:', response);
                if (response.success) {
                    setTimeout(() => {
						initializePaymobElement(pxl_object.key, response.data.cs);
					}, 500);
                } else {
                    displayWooCommerceError(response.data);
                    hideLoadingIndicator();
                }

            }
        });
    }
}
function callbackAjaxCall(data, url = null) {
    console.info(' callbackAjaxCall ', data);
    console.info(' callbackAjaxCall url ', url);
    jQuery.ajax({
        url: pxl_object.callback,
        type: 'GET',
        async: false,
        data: data,
        success: function (response) {
            if (response.success) {
                console.log('callbackAjaxCall success')
                window.location.href = response.data.url;
            }
        }
    });
    if(url !== null){
        console.log('callbackAjaxCall url !=null')
        window.location.href = url;
    }
}
function showLoadingMessage(reload = false){
     if(jQuery("#paymob-elements").children().length == 0 || reload == true){
        jQuery("#paymob-elements").html(
            `Loading payments, Please wait..`
        );
    }
}
window.previousTotal = null;
jQuery(document).ready(function ($) {
   
    showLoadingMessage();
    $(document).on('updated_checkout', function () {
        // Get the current total amount        
        var totalElement = jQuery('.order-total .amount').text().replace(/[^0-9.]/g, '');
        if(totalElement == null){
            var totalElement = jQuery('#order_review').find('.order-total .woocommerce-Price-amount').text(); // Extract total amount
        }

        // const totalElement = $('.order-total .woocommerce-Price-amount');
        if (totalElement.length) {

            const currentTotal = (totalElement.replace(/[^0-9.]/g, ''));
             
            if (window.previousTotal !== null && window.previousTotal !== currentTotal) {

              //showLoadingIndicator("Loading Checkout. Please wait.");
                console.log('Checkout total has changed.');
                console.log('Previous Total:', window.previousTotal);
                console.log('Current Total:', currentTotal);

                updateCheckoutData(true);
            }

            // Update the previous total
            window.previousTotal = currentTotal;
        }

    });
    
    const totalElementSelector = '.wc-block-components-totals-item__value'; // Adjust the selector based on your theme
    window.previousTotalBlock = null;
   
    setInterval(async() => {
        const totalBlockElement = $(totalElementSelector);
        
        if (totalBlockElement.length) {
            const { select } = wp.data;
            const cartStore = select('wc/store/cart');
            const cartTotals = cartStore.getCartTotals();
            const totalAmount = (parseInt(cartTotals.total_price, 10) /
            10 ** cartTotals.currency_minor_unit);
            const currentBlockTotal = totalAmount;
           
            if (window.previousTotalBlock !== null && window.previousTotalBlock !== currentBlockTotal) {
                console.log('Checkout total has changed.');
                console.log('Previous Total:', previousTotalBlock);
                console.log('Current Total:', currentBlockTotal);

                // Update the previous total
                window.previousTotalBlock = currentBlockTotal;
               
                //const totalAmount = cartTotals ? cartTotals.total_price : null;
               

                const billingData = cartStore.getCustomerData().billingAddress;
               
                    showLoadingMessage();
                    ajaxCall(billingData, totalAmount, true);
                   
            }

            if (window.previousTotalBlock === null) {
                window.previousTotalBlock = currentBlockTotal; // Initialize on the first check
            }
        }
    }, 200); // Check every 1000 milliseconds



    const paymentMethod = 'paymob-pixel'; // Replace with the desired payment method ID
    const paymentBlockInput = jQuery(`input[name="radio-control-wc-payment-method-options"][value="${paymentMethod}"]`);
    const paymentClassicInput = jQuery('#payment_method_'+paymentMethod);
    if (paymentBlockInput.length > 0 || paymentClassicInput.length > 0) {        
        paymentBlockInput.prop('checked', true).trigger('change'); // Select and trigger the change event
        paymentClassicInput.prop('checked', true).trigger('change'); // Select and trigger the change event
        console.log('Payment method set to:', paymentMethod);
       
    } 
});


function showLoadingIndicator(message = "Processing, please wait...") {
    const loadingContainer = document.createElement('div');
    loadingContainer.id = 'paymob-loading-indicator';
    loadingContainer.style.position = 'fixed';
    loadingContainer.style.top = '0';
    loadingContainer.style.left = '0';
    loadingContainer.style.width = '100%';
    loadingContainer.style.height = '100%';
    loadingContainer.style.backgroundColor = 'rgba(0, 0, 0, 0.7)'; // Semi-transparent dark background
    loadingContainer.style.display = 'flex';
    loadingContainer.style.flexDirection = 'column';
    loadingContainer.style.justifyContent = 'center';
    loadingContainer.style.alignItems = 'center';
    loadingContainer.style.zIndex = '10000';

    loadingContainer.innerHTML = `
        <div style="
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            padding: 20px; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        ">
            <div class="loading-spinner" style="
                width: 50px; 
                height: 50px; 
                border: 5px solid #f3f3f3; 
                border-top: 5px solid #007bff; 
                border-radius: 50%; 
                animation: spin 1s linear infinite;">
            </div>
            <p style="
                margin-top: 15px; 
                font-family: Arial, sans-serif; 
                font-size: 16px; 
                color: #333;
                text-align: center;">
                ${message}
            </p>
        </div>
    `;
    document.body.appendChild(loadingContainer);

    // Add spinner animation and dark overlay fade-in
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #paymob-loading-indicator {
            animation: fadeIn 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
}

function hideLoadingIndicator() {
    const loadingContainer = document.getElementById('paymob-loading-indicator');
    if (loadingContainer) {
        loadingContainer.remove();
    }
}

/////////////////////////////////////////////////Ajax To Place Order/////
function handleOrderCreation() {
  
        const { select } = wp.data;
        const cartStore = select('wc/store/cart');
        const billingData = cartStore.getCustomerData().billingAddress;
        jQuery.ajax({
            url: pxl_object.ajax_url,
            type: 'POST',
            data: {
                action: 'create_order',
                    security: pxl_object.update_checkout_nonce,
                },
            success: function (response) {
               
                if (response.success) {
                   console.log('done');
                    
                } else {
                    console.log('failed');
                   
                }

            }
            
        });
        
    
}

jQuery(document).ready(function ($) {
    if (!window.scriptInitialized) {
        window.scriptInitialized = true;
        // Load scripts and initialize Paymob element
        loadScripts();
            // Handle changes in billing address inputs
        jQuery(document).on('input change', '.wc-block-components-form input[required], .wc-block-components-form select[required]', function () {
            isCheckoutFormValid();
        });

        // Initial validation on page load
        isCheckoutFormValid();
        // handleOrderCreation();
    }
        
});

// handel place order in paymob-subscription

function getSelectedPaymentMethod() {
    const selected = document.querySelector('input[name="radio-control-wc-payment-method-options"]:checked');
    if (selected) {
        const paymentMethodId = selected.id.replace('radio-control-wc-payment-method-options-', '');
        const placeOrderSelectors = [
            '.wc-block-checkout__form .wc-block-components-button',
            '.wc-block-components-checkout-place-order-button',
            '#place_order'
        ];
        if (paymentMethodId=='paymob-subscription') {

            placeOrderSelectors.forEach(selector => {
                jQuery(selector).show().prop('disabled', false);
            });
        }
    }
}

function waitForSelectedPaymentMethod(attempts = 20) {
    const interval = setInterval(() => {
        const selected = getSelectedPaymentMethod();
        if (selected || attempts <= 0) {
            clearInterval(interval);
        }
        attempts--;
    }, 300);
}

document.addEventListener('DOMContentLoaded', () => {
    waitForSelectedPaymentMethod();
});

window.addEventListener("message", function (event) {
    if (event.data?.type === "discountResponse" || event.data?.type ==="cardData") {
        const discountData = event.data.response?.res?.data;
        // Prevent duplicate discount injection
        if (window.paymobDiscountApplied) {
            console.log("Paymob discount already applied, skipping duplicate.");
            return;
        }
 
        if (discountData && discountData.discounted_amount_cents > 0) {
            window.paymobDiscountApplied = true;
 
            // Convert from cents to EGP
            const original = discountData.original_amount_cents / 100;
            const finalTotal = discountData.discount_amount_cents / 100;
            const discountValue = discountData.discounted_amount_cents / 100;
 
            // Send discount to backend
            jQuery.ajax({
                url: pxl_object.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'paymob_apply_discount',
                    security: pxl_object.update_checkout_nonce,
                    discount: discountValue,
                    original: original,
                    final_total: finalTotal,
                },
                success: function (response) {
                    if (response.success) {
                        console.log("Discount applied:", response.data);
 
                        // Detect checkout type
                        const isBlocksCheckout = document.querySelector(".wc-block-checkout") !== null;
 
                        // Trigger WooCommerce cart refresh
                        // jQuery(document.body).trigger('update_checkout');
 
                        setTimeout(() => {
                            if (isBlocksCheckout) {
                                // --- BLOCKS CHECKOUT ---
                                console.log("WooCommerce Blocks checkout detected");
 
                                // Find official discount block container
                                const discountContainer = document.querySelector(".wp-block-woocommerce-checkout-order-summary-discount-block.wc-block-components-totals-wrapper");
 
                                if (discountContainer) {
                                    // Create or update discount line
                                    let line = document.querySelector(".paymob-discount-line");
                                    if (!line) {
                                        line = document.createElement("div");
                                        line.className = "wc-block-components-totals-item paymob-discount-line";
                                        discountContainer.appendChild(line);
                                    }
 
                                    line.innerHTML = `
                                        <span class="wc-block-components-totals-item__label">Discount</span>
                                        <span class="wc-block-components-totals-item__value">-EGP ${discountValue.toFixed(2)}</span>
                                    `;
                                }
 
                                // Optionally update the total manually
                                const totalEl = document.querySelector(".wc-block-components-totals-footer-item-tax-value, .wc-block-components-totals-footer-item-value");
                                if (totalEl) {
                                    totalEl.textContent = "EGP " + parseFloat(finalTotal).toFixed(2);
                                }
 
                            } else {
                                // --- CLASSIC CHECKOUT ---
                                console.log("Classic checkout detected");
 
                                // Update total in classic layout
                                const totalEl = document.querySelector("tr.order-total .woocommerce-Price-amount.amount bdi");
                                if (totalEl) {
                                    totalEl.innerHTML = `<span class="woocommerce-Price-currencySymbol">EGP</span>${finalTotal.toFixed(2)}`;
                                }
 
                                // Add discount row after subtotal
                                const subtotalRow = document.querySelector("tr.cart-subtotal");
                                if (subtotalRow && !document.querySelector(".paymob-discount-row")) {
                                    const row = document.createElement("tr");
                                    row.className = "paymob-discount-row";
                                    row.innerHTML = `
                                        <th>Discount</th>
                                        <td data-title="Discount">
                                            <span class="woocommerce-Price-amount amount">
                                                <bdi><span class="woocommerce-Price-currencySymbol">EGP</span>-${discountValue.toFixed(2)}</bdi>
                                            </span>
                                        </td>`;
                                    subtotalRow.parentNode.insertBefore(row, subtotalRow.nextSibling);
                                }
                            }
                        }, 500);
                    } else {
                        console.error("Failed to apply discount:", response);
                    }
                },
                error: function (err) {
                    console.error("AJAX error applying discount:", err);
                }
            });
        }
    }
});