jQuery(document).ready(function($){
	
	/*
	 * "Shopping Cart": Actions
	 * Requires: jStorage
	 * Global Variables: existingProducts, existingProductIDs
	 * http://stackoverflow.com/questions/3352020/jquery-the-best-way-to-set-a-global-variable
	 */
	
	// Find existing products
	function grab_existing_products_in_cart() {
		window.existingProducts = [];
		window.existingProductIDs = [];
		$.each($.jStorage.index(), function(index,value) { // Grab all existing jStorage objects.
			if(value.indexOf('product') >= 0) { // Select all that are considered products...
				existingProducts.push(value); // Store existing product keys
				var tempValues = $.jStorage.get(value).split(','); // Then convert string into an array
				$.each(tempValues, function(index,value) { // For each product...
					if(index == 0) { // Find index 0 or position 1 in the product value (Post ID)...
						existingProductIDs.push(value); // Save that product ID
					}
				});
			}
		});
	}

	// Refreshes the shopping cart via Ajax on each page load :(
	function refresh_shopping_cart(fadeCallback,location) {

		grab_existing_products_in_cart();

		// Create a shopping object for each product in the shopping cart
		var jProducts = [];
		$.each(existingProducts, function(index, value){
			var jExsitingProductValues = $.jStorage.get(value).split(','); // Turn values into an array...
			var jPostID  = jExsitingProductValues[0]; // Get the product post ID
			var jColor   = jExsitingProductValues[1]; // Get the product color
			var jQty     = jExsitingProductValues[2]; // Get the product qty
			var jProduct = {}; // Generate a singular product object and store each...
			jProduct['key'] = value; // singluar post id...
			jProduct['postID'] = jPostID; // singluar post id...
			jProduct['color'] = jColor; // singluar post color...
			jProduct['qty'] = jQty; // singluar post qty...
			jProducts.push(jProduct); // In an array of existing products
		});

		$.post(shopping_cart_scripts.ajaxurl, {
			dataType: "jsonp",
			action: 'refresh_shopping_cart',
			nonce: shopping_cart_scripts.nonce,
			products: jProducts,
		}, function(response) {
			if ( response.success === true ) {

				// If refreshing cart for review, remove stale content from dom.
				if ( location === 'review' ) {
					$('.checkoutReview .shopping-cart-product').remove();
					$('.checkoutReview .checkout-totals').remove();
				} else {
					// Destroy existing popover and recreate
					$('.shoppingcart a.shoppingcartshow').popover('destroy');	
				}

				// If shopping cart has something in it ...
				if ( $.jStorage.index().length ) {
					// And if we are updating data in the review section...
					if ( location === 'review' ) {
						// Load new data into checkout review
						$('#checkoutModal').find('.modal-body .checkoutReview').append().html(response.html);
						// Generate Description to send to Stripe Dashboard
						$('#checkoutModal').find('.modal-body .checkoutBasicAndPay input[name="description"]').attr('value',response.desc);
						return false;
					// Create a shopping object for each product in the popover shopping cart
					} else {
						$('.shoppingcart a.shoppingcartshow').popover({
							'html': true,
							'placement': 'bottom',
							'content': "<h1>Your Cart</h1>"+response.html+"<hr /><a class='btn btn-primary checkout'>Checkout</a>",
							//'content': response.html+"<a class='clearcart'>Empty your shopping cart</a><hr /><a class='btn btn-primary checkout'>Checkout</a>",
						});
					}
				} else {
					// If there isn't any items in the cart and we are in the review screen...
					if ( location === 'review' ) {
						// Close the modal
						$('#checkoutModal').modal('hide');
						// Destroy existing popover and recreate
						$('.shoppingcart a.shoppingcartshow').popover('destroy');	
						// And display "Empty" cart
						$('.shoppingcart a.shoppingcartshow').popover({
							'html': true,
							'placement': 'bottom',
							'content': "Your shopping cart is currently empty.",
						});	
					// Otherwise, just display the empty cart
					} else {
						$('.shoppingcart a.shoppingcartshow').popover({
							'html': true,
							'placement': 'bottom',
							'content': "Your shopping cart is currently empty.",
						});	
					}		
				}
				if ( fadeCallback == true ) {
					// Show popover for for a few seconds, then fade out
					$('.shoppingcart a.shoppingcartshow').popover('toggle');
					$('.popover').delay(4000).fadeOut(800);
				}
				return false;
			} else {
				alert('Whoops! Something went wrong. Verify One.');
			}
		});
	}

	/*
	 * "Shopping Cart": Init & Beyond...
	 */

	// If user is browsing in "Private Browsing" on a mobile phone, jStorage may/may not work. Use
	// this alert to help the user troubleshoot.
	if(!$.jStorage.storageAvailable()) {
		alert('Snap! In order to use the shopping cart on this site, you must disable "Private Browsing" on your phone.');
	}

	// On page load, show contents of the shopping cart  
	refresh_shopping_cart();

	// Empty Entire Shopping Cart
	// http://stackoverflow.com/questions/13205103/attach-event-handler-to-button-in-twitter-bootstrap-popover
	$(document).on('click', '.popover a.clearcart', function(){
		// Clear entire jStorage
		$.jStorage.flush();
		refresh_shopping_cart(true);
	});

	// Delete Specific Products in Shopping Cart
	$(document).on('click', '.popover .shopping-cart-product a.remove', function(){
		var keyToRemove = $(this).parent().attr('data-jStorage-key');
		$.jStorage.deleteKey(keyToRemove);
		$.jStorage.reInit();
		refresh_shopping_cart(true);
	});

	// Delete specific product in review modal
	$(document).on('click', '.checkoutReview a.remove', function() {
		var keyToRemove = $(this).parent().attr('data-jStorage-key');
		$.jStorage.deleteKey(keyToRemove);
		$.jStorage.reInit();
		refresh_shopping_cart( true, 'review' );
	});

	/*
	 * "Shopping Cart": Place valid, new products in cart and refresh view
	 * Products are placed when the "Add to Cart" button is clicked.
	 */
	$(document).on('click', '#addToCart', function() {

		// Gather all existing products and product IDs
		grab_existing_products_in_cart();

		// Grab new product values
		var jPostID = $(this).data('post-id'); // "Post ID"
		if ($('.product-color-selection')) {
			var jColor = $('.product-color-selection').val(); // "Color"
		} else {
			var jColor = 'none';
		}
		var jQty = $('.product-qty-selection').val(); // "Qty" 
		var length = existingProductIDs.length; // Check how many products exist
		var newProductPosition = length + 1; // Set the new product position for the

		// Check if the product we wish to publish has the same 'postID' as an existing product
		// ATTENTION: MAXIMUM 10 PRODUCTS IN CART!
		var matchingProductPositions = [];
		var i = 0; // Starting index
		var r = 2; // Range from starting index 
		$.each(existingProducts, function(index,value) {
			var existingProductValue = $.jStorage.get(value).split(',');
			$.each(existingProductValue, function(index, value){
				if(value == jPostID) { // If a product contains the same postID, grab its position
					if((i >= 0) && (i <= 2)) {
						matchingProductPositions.push('0');// grab first position
					} else if((i > 2) && (i <= 5)) {
						matchingProductPositions.push('1');// grab second position
					} else if((i > 5) && (i <= 8)) {
						matchingProductPositions.push('2');// grab third position
					} else if((i > 8) && (i <= 11)) {
						matchingProductPositions.push('3');// grab etc. position
					} else if((i > 11) && (i <= 14)) {
						matchingProductPositions.push('4');
					} else if((i > 14) && (i <= 17)) {
						matchingProductPositions.push('5');
					} else if((i > 17) && (i <= 20)) {
						matchingProductPositions.push('6');
					} else if((i > 20) && (i <= 23)) {
						matchingProductPositions.push('7');
					} else if((i > 23) && (i <= 26)) {
						matchingProductPositions.push('8');
					} else if((i > 26) && (i <= 29)) {
						matchingProductPositions.push('9');
					} else if((i > 29) && (i <= 32)) {
						matchingProductPositions.push('10');
					} 
				}
				i++;
			});
		});

		// Define all matching product keys
		var matchingProducts = [];
		$.each(matchingProductPositions, function(index, value){ // Using matching product positions...
			matchingProducts.push(existingProducts[value]); // Grab keys in said positions
		});

		// Iterate through values of all matching products
		var currentColors = [];
		$.each(matchingProducts, function(index, value) { // Grab each matching product...
			var tempValues = $.jStorage.get(value).split(','); // and convert their values into an array.
			$.each(tempValues, function(index, value) { // Grab each value array
				if (index == 1) {  // Find the color value at index 1 (value/position 2)
					currentColors.push(value);
				}
			});
		});

		// Check if color value matches the color value of the product being posted
		if ($.inArray(jColor, currentColors) >= 0) {
		 // DO NOT PUBLISH 
		} else {
		 // PUBLISH
		 $.jStorage.set('product'+newProductPosition, jPostID+','+jColor+','+jQty); // Store the new 'post' in the shopping cart
		}

		// Generate new list of products in cart.
		grab_existing_products_in_cart();

		// Then, refresh the cart.
		refresh_shopping_cart(true);

	}); // end click

	/**
	 * 	Shopping Cart: Checkout Process
	 * 	Utilizes: Bootstrap Modal, Stripe Payment Processing
	 *	Requires: Bootstrap, Stripe, Easypost
	 */

	 var checkoutModal = $('#checkoutModal');

	 // Prevent page scrolling when modal is present (NEEDS FIXING!)
	 checkoutModal.on( 'show', function() {
	 	$('html').css( 'overflow', 'hidden' );
	 	$('html').addClass('fixed');
	 });
	 checkoutModal.on( 'hide', function() {
	 	$('html').css( 'overflow', 'scroll' );
	 	$('html').removeClass('fixed');
	 });

	 /**
	  *	 A. Show Checkout Modal I after "Checkout" button is clicked
	  */
	 $(document).on('click', 'a.checkout', function() {
			// Close "Shopping cart"
			$('.shoppingcart a.shoppingcartshow').popover('toggle');
			// Toggle "checkout" modal one: "Review/Edit Your Cart"
			$('#checkoutModal').modal({ backdrop: false });

			// Hopefully, passing the location parameter will refresh this screen properly...
			refresh_shopping_cart( true, 'review' );

			//grab_existing_products_in_cart();

			// // Create a shopping object for each product in the shopping cart
			// var jProducts = [];
			// $.each(existingProducts, function(index, value){
			// 	var jExsitingProductValues = $.jStorage.get(value).split(','); // Turn values into an array...
			// 	var jPostID  = jExsitingProductValues[0]; // Get the product post ID
			// 	var jColor   = jExsitingProductValues[1]; // Get the product color
			// 	var jQty     = jExsitingProductValues[2]; // Get the product qty
			// 	var jProduct = {}; // Generate a singular product object and store each...
			// 	jProduct['key'] = value; // singluar post id...
			// 	jProduct['postID'] = jPostID; // singluar post id...
			// 	jProduct['color'] = jColor; // singluar post color...
			// 	jProduct['qty'] = jQty; // singluar post qty...
			// 	jProducts.push(jProduct); // In an array of existing products
			// });
			
			// // Display Shopping Cart Items In Checkout
			// $.post(shopping_cart_scripts.ajaxurl, {
			// 	dataType: "jsonp",
			// 	action: 'refresh_shopping_cart',
			// 	nonce: shopping_cart_scripts.nonce,
			// 	products: jProducts,
			// }, function(response) {
			// 	if (response.success===true) {
			// 		$('#checkoutModal').find('.modal-body .checkoutReview').append().html(response.html);
			// 		// Generate Description to send to Stripe Dashboard
			// 		$('#checkoutModal').find('.modal-body .checkoutBasicAndPay input[name="description"]').attr('value',response.desc);
			// 		return false;
			// 	} else {
			// 		alert('Whoops! Something went wrong.');
			// 	}
			// });
		});

	/**
	 *  B. Show Checkout Modal II after "Select Payment Method" is clicked
	 */
	$(document).on('click', 'button.choosePaymentMethod', function(){
		// Close Review Modal & Toggle "checkout" modal two: "Payment information"
		$('#checkoutModal .checkoutReview').hide('fast');
		$('#checkoutModal .checkoutBasicAndPay').show('fast');
		$('#checkoutModal .checkoutBasic').show('fast');
	});

	/**
	 *	B.1. Intermediate Step ( UI within form )
	 */
	// If user, checks "Shipping address is different."...
	$('#shippingIsDifferent').change( function() {
		// Show Shipping address fields...
		if ( $('#shippingIsDifferent').is(':checked') ) {
			$('#addr-info-shipping').removeClass('hide').addClass('show');
		} else {
			$('#addr-info-shipping').removeClass('show').addClass('hide');
		}
	});

	// User indicates they wish to complete Basic Info
	$('#submitBasicInfo').on( 'click', function() {
		$(this).prop('disabled', true); // Disable this button to prevent multiple submits
		// If a user has indicated that their shipping address
		// is NOT different from their billing...
		if ( ! $('#shippingIsDifferent').is(':checked') ) {
			$('input[data-easypost="shipping-address-line1"]').val( $('input[data-stripe="address-line1"]').val() );
			$('input[data-easypost="shipping-address-line2"]').val( $('input[data-stripe="address-line2"]').val() );
			$('input[data-easypost="shipping-address-city"]').val( $('input[data-stripe="address-city"]').val() );
			$('input[data-easypost="shipping-address-zip"]').val( $('input[data-stripe="address-zip"]').val() );
			$('input[data-easypost="shipping-address-state"]').val( $('input[data-stripe="address-state"]').val() );
			$('input[data-easypost="shipping-address-country"]').val( $('input[data-stripe="address-country"]').val() );
		}

		// Check Basic Information for any errors...
		if ( stripePreparationHandler('basic') ) {
			// If we look good, show CC info.
			$('#checkoutModal .checkoutBasic').hide('fast');
			$('#checkoutModal .checkoutPay').show('fast');
		} else {
			// Otherwise, re-enable this button for "re-submit".
			$(this).prop('disabled', false); // Re-enable this button to submit again.
		}
		// Complete Basic Info
		return false;
	});

	// User indicates they wish to complete Basic Info
	$('.submitPayment').on( 'click', function() {
		$(this).prop('disabled', true); // Disable this button to prevent multiple submits
		// Check Basic Information for any errors...
		if ( stripePreparationHandler('cc') ) {
			$('#stripe-submit').click(); // Click the hidden stripe submit button.
		} else {
			// Otherwise, re-enable this button for "re-submit".
			$(this).prop('disabled', false); // Re-enable this button to submit again.
		}
	});

	/**
	 *	B.2. Show Checkout Modal III after "Submit your payment" is selected
	 *	See StripeScripts/stripe-processing.js for this handler	
	 */

	/**
	 *	C. Show A Response Screen for all scenarios
	 */
	// http://stackoverflow.com/questions/439463/how-to-get-get-and-post-variables-with-jquery
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}
		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});

	// When closing checkout...
	$('#checkoutModal').on( 'hide', function() {
		// remove any instances of url parameters
		var old_url = document.URL;
		var new_url = old_url.substring(0, old_url.indexOf('?'));
		if ( new_url ) {
			window.location = new_url;
		}
	});

	// Show results modal to user once user has finished any sort of processing.
	if ( $_GET[ 'payment' ] || $_GET[ 'shipping' ] ) {
		$('#checkoutModal').modal({ backdrop: false });
		$('#checkoutModal .checkoutReview').hide('fast');
		$('#checkoutModal .checkoutPay').hide('fast');
		$('#checkoutModal .checkoutProcessing').hide('fast');
		$('#checkoutModal .checkoutResult').show('fast');
	}

	// On a truly successful payment...
	if( $_GET[ 'payment' ] == 'paid' ) {
		// Update Title
		$('#checkoutModal .modal-header .checkoutResult .checkoutTitle').text('Thanks for purchasing!');
		// Update Body Content
		$('#checkoutModal .modal-body .checkoutResult').html('yaaaaaaaay!');
		// Create useful footer buttons
	}

	// On an unsuccessful payment...
	if( $_GET[ 'payment' ] == 'card_error' ) {
		alert( $_GET[ 'reason' ] );
		switch ( $_GET[ 'reason' ] ) {
			case 'incorrect_number':
				var errorMessage = 'It appears the card number is incorrect.';
				break;
			case 'invalid_number':
				var errorMessage = 'The appears that this is not a valid credit card number.';
				break;
			case 'invalid_expiry_month':
				var errorMessage = 'It appears that the expiration month is invalid.';
				break;
			case 'invalid_expiry_year':
				var errorMessage = 'It appears that the expiration year is invalid.';
				break;
			case 'invalid_cvc':
				var errorMessage = 'It appears that the card security code is invalid.';
				break;
			case 'expired_card':
				var errorMessage = 'It appears that the card has expired.';
				break;
			case 'incorrect_cvc':
				var errorMessage = 'It appears that the card has an incorrect security code.';
				break;
			case 'incorrect_zip':
				var errorMessage = 'It appears that the card has an incorrect zip code.';
				break;
			case 'card_declined':
				var errorMessage = 'This card has been declined by your bank.';
				break;
			default:
				var errorMessage = 'Whoops! Looks like something wrong on our end. Mind sending us an email with this code: ' + $_GET[ 'reason' ];
				break;
		}
		// Update Title
		$('#checkoutModal .modal-header .checkoutResult .checkoutTitle').text('Whoops!');
		// Update Body Content
		$('#checkoutModal .modal-body .checkoutResult').html( errorMessage );
		// Create useful footer buttons
	}

	// On an unsuccessful shipment processing...
	if ( $_GET['shipping'] == 'failed' ) {
		// Update Title
		$('#checkoutModal .modal-header .checkoutResult .checkoutTitle').text('Whoops!');
		// Update Body Content
		$('#checkoutModal .modal-body .checkoutResult').html('Shipping failed!');
		// Create useful footer buttons
	}

});