jQuery(document).ready(function ($) {
    // Function to get query parameter by name
    function getQueryParam(error) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(error);
    }
    // Check if "error" parameter exists in the URL
    const errorMessage = getQueryParam('gatewayerror');
    if (errorMessage) {
        displayWooCommerceError(decodeURIComponent(errorMessage))
    }
});

function displayWooCommerceError(message) {
    // Remove any existing flash messages
    jQuery('.flash-message').remove();

    // Create the flash message container
    const flashMessage = jQuery(`
        <div class="flash-message">
            <p>${message}</p>
            <button class="close-flash">&times;</button>
        </div>
    `);

    // Append the flash message to the body
    jQuery('body').append(flashMessage);

    // Add functionality to close the flash message when the close button is clicked
    flashMessage.find('.close-flash').on('click', function () {
        flashMessage.css({ opacity: 0, transform: 'translateX(100%)' });
        setTimeout(() => flashMessage.remove(), 300); // Wait for the transition to finish
    });
}

document.addEventListener('DOMContentLoaded', function () {
	const selected = document.querySelector('input[name="radio-control-wc-payment-method-options"]:checked');

	if (!selected) {
		// Auto-select your plugin's method if not selected
		const fallback = document.querySelector('input[name="radio-control-wc-payment-method-options"][value="paymob-subscription"]');
		if (fallback) {
			fallback.click();
		}
	}
});