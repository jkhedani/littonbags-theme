/*
 * Stripe Javascript Submission
 * Based on: http://pippinsplugins.com/series/integrating-stripe-com-with-wordpress/
 */

Stripe.setPublishableKey(stripe_vars.publishable_key);

// Validate email regex function
// http://stackoverflow.com/questions/2855865/jquery-regex-validation-of-e-mail-address
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};

// Scroll Aid
// http://stackoverflow.com/questions/8579643/simple-jquery-scroll-to-anchor-up-or-down-the-page
// Modified by: Justin Hedani
function scrollToAnchor(aid,elem){
  var tag = jQuery("[id='"+ aid +"']");
  if ( !elem ) {
    jQuery('html,body').animate({scrollTop: tag.offset().top},'slow');  
  } else {
    jQuery(elem).animate({scrollTop: tag.offset().top},'slow');
  }
}

/**
 *  ERROR CHECK TYPE A: Pre-Stripe Send
 */
function stripePreparationHandler(checkValue) {

  // Variables assigned to fields we wish to check
  var fieldsLookGood = false; // Ultimately, we want this to be true. (means all error checks passed.)
  var errorDisplay = jQuery('.payment-errors');
  var valid = false;
  var noblankfields = true;

  /**
   * A. Quick Basic Check to ensure fields are filled out
   */
  jQuery('#checkoutModal #stripe-payment-form input').each( function() {
    if ( ! jQuery(this).val() ) {
      jQuery(this).addClass( 'error' );
      errorDisplay.html( 'The fields highlighted below appear to be blank.' );
      var noblankfields = false; // If any field is blank, this var is always false. 
    } else {
      jQuery(this).removeClass( 'error' );
    }
  });

  alert(noblankfields);

  // If there are blank fields, error handle.
  if ( noblankfields == false ) {
    errorDisplay.show();
    jQuery('#checkoutModal').animate({ scrollTop: 0 } , 'slow' );   // If modal becomes scrollable, scroll them to the top of modal to view error.
    return false;
  }

  /**
   *  B. Error check BASIC INFORMATION (name, email and address)
   */
  if ( checkValue == 'basic' ) {

    // Variables assigned to fields we wish to check
    var emailvalid = false;

    // Once we check that all fields have been filled out...
    if ( noblankfields == true ) {

      // Check to see if email field is valid
      if ( !isValidEmailAddress( jQuery('input.email').val() ) ) {
        jQuery('input.email').addClass( 'error' );
        errorDisplay.html( 'Your email appears to be invalid.' );
      } else {
        var emailvalid = true;
      }

      // If their email appears invalid, error handle.
      if ( emailvalid == false ) {
        errorDisplay.show();
        scrollToAnchor( 'basic-info', '#checkoutModal' );
        var fieldsLookGood = false;
      }

    }

    if ( emailvalid ) {
      var fieldsLookGood = true;
    }

  /**
   *  Error check CC INFORMATION (card details)
   */
  } else if ( checkValue == 'cc' ) {
    // var form$ = $("#stripe-payment-form");
    // // https://github.com/stripe/jquery.payment
    // $('[data-numeric]').payment('restrictNumeric'); // Only allow numbers for data-numeric inputs
    // // FIGURE OUT HOW WE'LL HANDLE DIFFERENT CARD FORMATS!
    // form$.find('input.cc-num').payment('formatCardNumber'); // Ensure proper card number format
    // form$.find('input.cc-cvc').payment('formatCardCVC'); // Ensure proper CVC format


    // // Variables assigned to fields we wish to check
    // var ccnum = false;
    // var cvc = false;
    // var ccexpiry = false;

    // // Once we check that all fields have been filled out...
    // if ( noblankfields == true ) {
    //     // Once customer's credit card info appears valid...
    //   if ( ccnum ) {
    //     // Check if cvc number is valid
    //     var cvc = $.payment.validateCardCVC( $('input.cc-cvc').val() );
    //   }

    //   // Once customer's cvc info appears valid...
    //   if ( cvc ) {
    //     // Check if expiry numbers are valid
    //     var ccexpiry = $.payment.validateCardExpiry( $('input.cc-exp-month').val(), $('input.cc-exp-year').val() );
    //   }

    //   // If there are blank fields, error handle.
    //   if ( noblankfields == false ) {
    //     errorDisplay.show();
    //     $('#checkoutModal .modal-body').animate({ scrollTop: 0 } , 'slow' );   // If modal becomes scrollable, scroll them to the top of modal to view error.
    //     return false;

    //   // If their email appears invalid, error handle.
    //   } else if ( emailvalid == false ) {
    //     errorDisplay.show();
    //     scrollToAnchor( 'basic-info', '#checkoutModal .modal-body' );
    //     return false;

    //   // If their credit card num appears invalid, error handle.
    //   } else if ( !ccnum ) {
    //     $('input.cc-num').addClass( 'error' );
    //     errorDisplay.html( 'Your card number to be invalid.' );
    //     errorDisplay.show();
    //     return false;

    //   // If their credit card cvc appears invalid, error handle.
    //   } else if ( !cvc  ) {
    //     $('input.cc-cvc').addClass( 'error' );
    //     errorDisplay.html( 'Your cvc num appears to be invalid.' );
    //     errorDisplay.show();
    //     return false;

    //   // If their credit card expiry date appears invalid, error handle.
    //   } else if ( !ccexpiry  ) {
    //     $('input.cc-exp-month,input.cc-exp-year').addClass( 'error' );
    //     errorDisplay.html( 'Your expiry num appears to be invalid.' );
    //     errorDisplay.show();
    //     return false;
    //   }
    
  } // error check cc


  // If our form appears to be properly filled out, let's go ahead and attempt to submit this form
  if ( fieldsLookGood ) {
    errorDisplay.hide();
    return true;
  } else {
    return false;
  }

} // end type a

// ERROR CHECK TYPE B: Post-Stripe Send
function stripeResponseHandler(status, response) {
 	
 	// If a submitted form returns with errors, handle errors.
  if ( response.error ) {

    var errorDisplay = jQuery(".payment-errors");
		
    // When an error occurs, show the errorDisplay
		errorDisplay.show();

    // Hide thinker after receiving an error from sent form
    jQuery(".checkoutPay .processing-spinner").hide(); 

		// Handle Card Errors
  	if ( response.error.type == "card_error" ) {
  		
  		// Invalid Credit Card Number
  		if ( response.error.code == "invalid_number" ) {
        //jQuery('input.cc-num').addClass( 'error' );
  			errorDisplay.html('The card number is not a valid credit card.');
  		
  		// Invalid Security Code
  		} else if ( response.error.code == "invalid_cvc" ) {
        //jQuery('input.cc-cvc').addClass( 'error' );
  			errorDisplay.html('The card&#039;s security code is invalid.');
  		
  		// Invalid Expiration Month
  		} else if ( response.error.code == "invalid_expiry_month" ) {
        //jQuery('input.cc-exp-month').addClass( 'error' );
  			errorDisplay.html('The card&#039;s expiration month is invalid.');
			
			// Invalid Expiration Year
  		} else if ( response.error.code == "invalid_expiry_year" ) {
        //jQuery('input.cc-exp-month').addClass( 'error' );
  			errorDisplay.html('The card&#039;s expiration year is invalid.');
  		
  		// Graceful Fallback
  		} else {
  			errorDisplay.html(response.error.message);
  		}  		

  	} else {
  		// All other Stripe errors handled here for now
  		errorDisplay.html(response.error.message);
  	} // end card_errors

		// re-enable the submit & pseudo-submit button
		//jQuery('#stripe-submit').attr("disabled", false);
		//jQuery('.submitPayment').attr("disabled", false); // Trigger function in: lib/ShoppingCart/shopping-cart.js
	  return false;

	// Handle Semi-valid Form
  } else {
    var errorDisplay = jQuery(".payment-errors");
    
    errorDisplay.hide();
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

  /**
   *  Prepare Stripe Form
   */
  
  // If error check says we good...
  if ( stripePreparationHandler('cc') ) {
    $('#stripe-submit').click(); // Click the hidden stripe submit button.
  }

  /**
   *  Submit Stripe Form
   */
	$("#stripe-payment-form").submit(function(event) {

    // Show thinking when sending form for inital processing
		$('.checkoutPay .processing-spinner').show();
    var form$ = $(this);

    // Disable the submit button to prevent repeated clicks
    form$.find('button').prop('disabled', true);

    // Submit to stripe and check for errors on return
    // NOTE: Error checking handled by stripeResponseHandler
    Stripe.createToken(form$, stripeResponseHandler);
 
		// prevent the form from submitting with the default action
		return false;
	});
});