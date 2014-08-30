jQuery( document ).ready( function( $ ) {

	/**
	 * Make Paypal Payment
	 */

	// User indicates that they want to checkout via paypal
	$(document).on( 'click', '.paypal-checkout', function() {

		// # Show AJAX Spinner
		$(document).find('.overlay.loading, .overlay.loading i').css('display','block');

		// # Prevent default link action
		// event.preventDefault();

		// # Retrieve current cart information
		// 	 This information should be available at this time.
		//   If it is not, ensure the cart is loaded within the modal.
		var cartDescription = $('#checkoutModal').find('.modal-body .checkoutBasicAndPay input[name="cartdescription"]').val();

		// # Retrieve the method in which we will create this payment
		var paymentMethod = $(this).data('payment-method');

		// # Send data to create new post
		$.post( paypal_data.ajaxurl, {
			dataType: 'jsonp',
			action: 'create_payment',
			nonce: paypal_data.nonce,
			cartdescription: cartDescription,
			paymentmethod: paymentMethod
		}, function( response ) {
			if ( response.success === true ) {
				window.location = response.redirecturl;
			}
		}); // $.post
	}); // on.click

}); // jQuery
