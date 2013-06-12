/*
 * Stripe Javascript Submission
 * Based on: http://pippinsplugins.com/series/integrating-stripe-com-with-wordpress/
 */

Stripe.setPublishableKey(stripe_vars.publishable_key);

function stripeResponseHandler(status, response) {
  if (response.error) {
		
		// show errors returned by Stripe
  	jQuery(".payment-errors").html(response.error.message).toggleClass('hide', 'show');
		// re-enable the submit & pseudo-submit button
		jQuery('#stripe-submit').attr("disabled", false);
		jQuery('.submitPayment').attr("disabled", false); // Trigger function in: lib/ShoppingCart/shopping-cart.js
	
  } else {

    var form$ = jQuery("#stripe-payment-form");
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