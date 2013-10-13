<?php
function render_shopping_cart() {

	/*
	 * "Checkout" Modal
	 */
  echo '<div id="checkoutModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">';
  echo 	'<div class="container">';
	echo  	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close   X</button>';
	
	/**
	 *	Checkout Headers
	 */
	echo  '<div class="modal-header">';

	echo 		'<div class="checkoutReview checkoutControls show">';
	echo  		'<div class="step-count">1 of 3</div>';
	echo    	'<h3 class="checkoutTitle">'. __('Review Your Cart','litton_bags') .'</h3>';
	echo 			'<div class="half-stache pink"></div>';
	echo    '</div>';

	echo 		'<div class="checkoutBasic hide">';
	echo  		'<div class="step-count">2 of 3</div>';
	echo    	'<h3 class="checkoutTitle">'. __('Basic Information','litton_bags') .'</h3>';
	echo 			'<div class="half-stache pink"></div>';
	echo 		'</div>';

	echo 		'<div class="checkoutPay hide">';
	echo  		'<div class="step-count">3 of 3</div>';
	echo    	'<h3 class="checkoutTitle">'. __('Submit Your Payment','litton_bags') .'</h3>';
	echo 			'<div class="half-stache pink"></div>';
	echo 		'</div>';

	echo 		'<div class="checkoutResult hide">';
	echo    	'<h3 class="checkoutTitle"></h3>';
	echo 		'</div>';
	
	echo  '</div>';


	/**
	 *	Stripe Checkout Content
	 */
	echo  '<div class="modal-body">';

	/**
	 *	A. Checkout Step One: Review
	 */
	echo 	'<div class="checkoutReview show"></div>';

	/**
	 *	B. Checkout Step Two: Basic Info / Pay
	 */
	echo 	'<div class="checkoutBasicAndPay hide">';
		// "STRIPE Variables
		$productPrice = get_field('product_price'); // in 'cents'
		$productPriceInDollars = $productPrice/100; // in 'dollars'
		$english_notation = number_format($productPriceInDollars,2,'.',''); // in eng notation 'dollars'

		if( isset($_GET['payment']) && $_GET['payment'] == 'paid') {
			echo '<p class="success">' . __('Thank you for your payment.', 'litton_bags') . '</p>';
		} else {

			// "Stripe": Basic/Payment Form
			echo '<form action="" method="POST" id="stripe-payment-form">';
			
			// 		FORM ERRORS
			echo '<div class="payment-errors alert hide"></div>';

			/**
			 *	B.1. Basic Info Collection
			 */
			// 		PERSONAL INFO
			echo 	'<div class="form-row checkoutBasic basic-info" id="basic-info" >';
			echo 	'<legend>Basic Information</legend>';
			echo 		'<label>'. __('Full Name', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" name="customer-name" />';
			echo 		'<label>'. __('Email Address', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" class="email" name="email" />'; // ARE WE DOING THIS CORRECTLY?!
			echo 	'</div>';

			//		CC ADDRESS COLLECTION
			echo 	'<div class="form-row checkoutBasic basic-info" id="addr-info">';
			echo 		'<legend>Billing Address</legend>';
			echo 		'<label>'. __('Address Line 1', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-line1" class="address" />';
			echo 		'<label>'. __('Address Line 2', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-line2" class="optional address" />';
			echo  	'<div class="form-row-single">';
			echo 			'<div>';
			echo 				'<label>'. __('City', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" data-stripe="address-city" />';
			echo 			'</div>';
			echo 			'<div>';
			echo 				'<label>'. __('Zip Code', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" class="zip-code" data-stripe="address-zip" />';
			echo 			'</div>';
			echo 			'<div>';
			echo 				'<label>'. __('State', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" class="state" data-stripe="address-state" />';
			echo 			'</div>';
			echo 			'<div>';
			echo 				'<label>'. __('Country', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" class="country" data-stripe="address-country" />';
			echo 			'</div>';
			echo 		'</div>'; // .form-row-single

			echo   	'<span class="formHelperText">Currently, we are only shipping to the United States on our website. Please email us for international purchases.</span>';
			echo 		'<br />';
			echo 		'<input id="shippingIsDifferent" type="checkbox" />';
			echo   	'<span class="formHelperText">My shipping address is different from my billing address.</span>';
			echo 	'</div>';

			//		SHIPPING ADDRESS COLLECTION
			echo 	'<div class="form-row basic-info hide" id="addr-info-shipping">';
			echo 		'<legend>Shipping Address</legend>';
			echo 		'<label>'. __('Address Line 1', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" data-easypost="shipping-address-line1" name="shipping-address-line1" class="address" />';
			echo 		'<label>'. __('Address Line 2', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" data-easypost="shipping-address-line2" name="shipping-address-line2" class="address optional" />';
			echo  	'<div class="form-row-single">';
			echo 			'<div>';
			echo 				'<label>'. __('City', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" data-easypost="shipping-address-city" name="shipping-address-city" />';
			echo 			'</div>';
			echo 			'<div>';
			echo 				'<label>'. __('Zip Code', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" class="zip-code" data-easypost="shipping-address-zip" name="shipping-address-zip" />';
			echo 			'</div>';
			echo 			'<div>';
			echo 				'<label>'. __('State', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" class="state" data-easypost="shipping-address-state" name="shipping-address-state" />';
			echo 			'</div>';
			echo 			'<div>';
			echo 				'<label>'. __('Country', 'litton_bags') .'</label>';
			echo 				'<input type="text" size="20" autocomplete="off" class="country" data-easypost="shipping-address-country" name="shipping-address-country" />';
			echo 			'</div>';
			echo 		'</div>'; // .form-row-single
			echo 	'</div>';

			// 		CARD NUMBER
			echo 	'<div class="form-row checkoutPay payment-info hide" id="cc-info">';
			echo 		'We use a secure payment processing method powered by Stripe. Read more »';
			echo 		'5% of your purchase will go to the charity WakaWaka Lights.';
			echo 		'We accept Visa, Mastercard, etc., etc.';
			echo 		'<legend>Card Information</legend>';
			echo 		'<label>'. __('Name on Card', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" data-stripe="name" />';
			echo 		'<label>'. __('Card Number', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="20" autocomplete="off" class="cc-num" data-stripe="number" />';
			echo 		'<label>'. __('CVC', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="4" autocomplete="off" class="cc-cvc" data-stripe="cvc" />';
			echo 		'<label>'. __('Expiration (MM/YYYY)', 'litton_bags') .'</label>';
			echo 		'<input type="text" size="2" data-stripe="exp-month" class="cc-exp-month" data-numeric />';
			echo 		'<span> / </span>';
			echo 		'<input type="text" size="4" data-stripe="exp-year" class="cc-exp-year" data-numeric />';
			echo 	'</div>';

			//		WORDPRESS DATA VALUES (NO SENSITIVE FORMS BELOW THIS LINE!)	
			echo 	'<input type="hidden" name="action" value="stripe"/>';
			echo 	'<input type="hidden" name="redirect" value="'. get_permalink() .'"/>';
			echo 	'<input type="hidden" name="stripe_nonce" value="'. wp_create_nonce('stripe-nonce').'"/>';
			echo 	'<input type="hidden" name="description" value=""/>';
			echo 	'<button type="submit hidden" class="hide" id="stripe-submit">'. __('Submit Payment', 'litton_bags') .'</button>';
			echo '</form>';
		}
	echo  '</div>'; // Pay

	// Checkout Step Three: "Processing..."
	echo 	'<div class="checkoutProcessing hide">';
	// Ajax gif: http://www.mytreedb.com/view_blog/a-33-high_quality_ajax_loader_images.html
	echo  '<img src="'.get_stylesheet_directory_uri().'/images/ajax-loader-256.gif" alt="Your payment is processing."/>';
	echo  '<p>Please wait for your payment to process. Refrain from closing this page to avoid multiple charges.</p>';
	echo  '</div>';

	// Checkout Step Four: Thank You
	echo 	'<div class="checkoutResult hide">';
	echo  '</div>';

	echo  '</div>'; // .modal-body
	echo  '<div class="modal-footer">';
	echo 		'<div class="checkoutReview checkoutControls show">';
	echo    	'<a class="btn btn-primary choosePaymentMethod">Select Payment Method</a>';
	echo 		'</div>';
	echo 		'<div class="checkoutBasic checkoutControls hide">';
	echo    	'<a id="submitBasicInfo" class="btn btn-primary">Submit Basic Info</a>'; // [completes step B.1]
	echo 		'</div>';
	echo 		'<div class="checkoutPay checkoutControls hide">';
	echo  		'<img class="processing-spinner hide" src="'.get_stylesheet_directory_uri().'/images/ajax-loader-32.gif" alt="Your payment is processing."/>';
	echo    	'<a class="btn btn-primary submitPayment">Submit Your Payment</a>';
	echo  	'</div>';
	echo 		'<div class="checkoutResult checkoutControls hide">';
	echo    	'<a class="btn btn-primary hide showBasicInfo">Review Basic Info Screen</a>'; // Review Basic Info
	echo    	'<a class="btn btn-primary hide showSubmitPayment">Review Payment Screen</a>'; // Review Payment Screen
	echo    	'<a class="btn btn-primary closeCheckout" data-dismiss="modal" aria-hidden="true">Close</a>';
	echo  	'</div>';
	echo  '</div>'; // .modal-footer

	// Modal Fluff
	echo '<div class="tilted-frame"></div>';
	echo '<div class="tilted-frame-deuce"></div>';
	echo '<div class="stamp-watermark"></div>';

  echo '</div>'; // .container

  // More Modal Fluff
  echo '<div class="watercolor-blob pink"></div>';
	echo '<div class="watercolor-blob gold"></div>';
	echo '<div class="watercolor-blob gray"></div>';
	
	echo '</div>'; // .modal (#checkout)

}
?>