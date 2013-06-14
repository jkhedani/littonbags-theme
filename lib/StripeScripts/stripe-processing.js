/*
 * Stripe Javascript Submission
 * Based on: http://pippinsplugins.com/series/integrating-stripe-com-with-wordpress/
 */

Stripe.setPublishableKey(stripe_vars.publishable_key);

// Possibly follow this to use front side validation...
// https://gist.github.com/boucher/1750368

function stripeResponseHandler(status, response) {
 	
 	// Handle Errors
  if (response.error) {
		var errorDisplay = jQuery(".payment-errors");
		// When an error occurs, show the errorDisplay
		errorDisplay.show();

		// Handle Card Errors
  	if ( response.error.type == "card_error" ) {
  		
  		// Invalid Credit Card Number
  		if ( response.error.code == "invalid_number" ) {
  			errorDisplay.html('The card number is not a valid credit card.');
  		
  		// Invalid Security Code
  		} else if ( response.error.code == "invalid_cvc" ) {
  			errorDisplay.html('The card&#039;s security code is invalid.');
  		
  		// Invalid Expiration Month
  		} else if ( response.error.code == "invalid_expiry_month" ) {
  			errorDisplay.html('The card&#039;s expiration month is invalid.');
			
			// Invalid Expiration Year
  		} else if ( response.error.code == "invalid_expiry_year" ) {
  			errorDisplay.html('The card&#039;s expiration year is invalid.');
  		
  		// Graceful Fallback
  		} else {
  			errorDisplay.html(response.error.message);
  		}  		

  	} else {
  		// All other Stripe errors handled here for now
  		errorDisplay.html(response.error.message).show();
  	} // end card_errors

		// re-enable the submit & pseudo-submit button
		jQuery('#stripe-submit').attr("disabled", false);
		jQuery('.submitPayment').attr("disabled", false); // Trigger function in: lib/ShoppingCart/shopping-cart.js
	
	// Handle Semi-valid Form
  } else {

  	var form$ = jQuery("#stripe-payment-form");
  	// After form is validated, show processing screen
  	jQuery('#checkoutModal .checkoutPay').hide('fast');
		jQuery('#checkoutModal .checkoutProcessing').show('fast');
    // token contains id, last4, and card type
    var token = response['id'];
    // insert the token into the form so it gets submitted to the server
    form$.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
    // and submit
    form$.get(0).submit();
 
  }
}

jQuery(document).ready(function($) {

	$("#stripe-payment-form").submit(function(event) {
		var form$ = $(this);

    // Disable the submit button to prevent repeated clicks
    form$.find('button').prop('disabled', true);

    Stripe.createToken(form$, stripeResponseHandler);
 
		// send the card details to Stripe
		// Stripe.createToken({
		// 	number: $('.card-number').val(),
		// 	cvc: $('.card-cvc').val(),
		// 	exp_month: $('.card-expiry-month').val(),
		// 	exp_year: $('.card-expiry-year').val()
		// }, stripeResponseHandler);
 
		// Hide checkoutPay

		// prevent the form from submitting with the default action
		return false;
	});
});