<?php
/**
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" class="row">

	<header class="product-header span3">		
		<h1 class="product-title"><?php the_title(); ?></h1>
		<h2 class="product-subtitle"><?php echo get_field('product_subtitle'); ?></h2>
		<?php if ( $post->post_content=="" && is_user_logged_in() ) : ?>
			<p class="muted helper-text">You currently have no description. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>
		<?php else : ?>
		<p class="product-details"><?php echo get_the_content(); ?></p>
		<?php endif; ?>

	</header><!-- .entry-header -->

	<div class="product-content span2">
	
		<?php

			/*
			 * User Selected Options
			 */

			// Price
			if ( get_field('product_price') ) {
				$priceInPennies = get_field('product_price');
				$preTaxCost = money_format('%n', $priceInPennies/100);
				echo $preTaxCost;
			}

			// Quantity Options
			echo '<h3>Quantity</h3>';
			echo '<select class="product-qty-selection">';
			for($i = 1; $i < 11; $i++) {
				echo '<option value="'.$i.'">'.$i.'</option>';	
			}
			echo '</select>';

			// Color Options
			if (get_field('product_color_options')) {
				$colorOptions = get_field('product_color_options');
				echo '<h3 class="product-color-title">Color</h3>';
				echo '<select class="product-color-selection">';
				foreach ($colorOptions as $colorOption) {
					echo '<option value="'.$colorOption.'">'.$colorOption.'</option>';
				}
				echo '</select>';
			}

			echo '<hr />';
		
			/*
			 * "Add To Cart" Button
			 */
			echo '<a id="addToCart" role="button" href="#" class="btn btn-info" data-post-id="'.$post->ID.'">Add To Cart</a>'; ?>

		</div><!-- .product-content -->

		<div class="product-scroll">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/the-minster-collage-web.jpg" />
		</div>

		<?php
			/*
			 * "Checkout" Modal
			 */
		  echo '<div id="checkoutModal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">';
			echo  '<div class="modal-header">';

			echo 		'<div class="checkoutReview checkoutControls show">';
			echo    	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
			echo    	'<h3 class="checkoutTitle">'. __('Review Your Cart','litton_bags') .'</h3>';
			echo    '</div>';
			echo 		'<div class="checkoutPay checkoutPay hide">';
			echo    	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
			echo    	'<h3 class="checkoutTitle">'. __('Submit Your Payment','litton_bags') .'</h3>';
			echo 		'</div>';
			echo 		'<div class="checkoutThanks checkoutThanks hide">';
			echo    	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
			echo    	'<h3 class="checkoutTitle">'. __('Thanks For Purchasing','litton_bags') .'</h3>';
			echo 		'</div>';
			
			echo  '</div>';
			echo  '<div class="modal-body">';

			// Checkout Step One: Review
			echo 	'<div class="checkoutReview show"></div>';

			// Checkout Step Two: Pay
			echo 	'<div class="checkoutPay hide">';
				// "STRIPE Variables
				$productPrice = get_field('product_price'); // in 'cents'
				$productPriceInDollars = $productPrice/100; // in 'dollars'
				$english_notation = number_format($productPriceInDollars,2,'.',''); // in eng notation 'dollars'

				// "STRIPE" Checkout
				if(isset($_GET['payment']) && $_GET['payment'] == 'paid') {
					echo '<p class="success">' . __('Thank you for your payment.', 'litton_bags') . '</p>';
				} else {

					//echo '<h2>Your Total Cost: &#36;<span class="total-english-notation"></span> USD</h2>';

					echo 'We use a secure payment processing method powered by Stripe. Read more »';
					echo '5% of your purchase will go to the charity WakaWaka Lights.';
					echo 'We accept Visa, Mastercard, etc., etc.';

					// "Stripe": Payment Form
					echo '<form action="" method="POST" id="stripe-payment-form">';
					
					// 		FORM ERRORS
					echo '<div class="payment-errors alert hide"></div>';

					// 		PERSONAL INFO
					echo 	'<div class="form-row" id="basic-info">';
					echo 	'<legend>Basic Information</legend>';
					echo 		'<label>'. __('Full Name', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="name" />';
					echo 		'<label>'. __('Email Address', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" class="email" name="email" />'; // ARE WE DOING THIS CORRECTLY?!
					echo 	'</div>';

					//		ADDRESS
					echo 	'<div class="form-row" id="addr-info">';
					echo 		'<legend>Billing Address</legend>';
					echo 		'<label>'. __('Address Line 1', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-line1" />';
					echo 		'<label>'. __('Address Line 2', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-line2" />';
					echo 		'<label>'. __('City', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-city" />';
					echo 		'<label>'. __('Zip Code', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-zip" />';
					echo 		'<label>'. __('State', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-state" />';
					echo 		'<label>'. __('Country', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="address-country" />';
					echo 		'<br />';
					echo 		'<input id="mailingIsDifferent" type="checkbox" name="mailingDifferent" value="mailingIsDifferent" />';
					echo   	'<span class="formHelperText">My mailing address is different from my billing address.</span>';
					echo 	'</div>';

					// 		CARD NUMBER
					echo 	'<div class="form-row" id="cc-info">';
					echo 		'<legend>Card Information</legend>';
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
					echo 	'<button type="submit hidden" class="btn btn-primary hide" id="stripe-submit">'. __('Submit Payment', 'litton_bags') .'</button>';
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
			echo 	'<div class="checkoutThanks hide">';
			echo  '</div>';

			echo  '</div>'; // .modal-body
			echo  '<div class="modal-footer">';
			echo 		'<div class="checkoutReview checkoutControls show">';
			echo    	'<button class="btn btn-primary choosePaymentMethod">Continue to Payment Method</button>';
			echo 		'</div>';
			echo 		'<div class="checkoutPay checkoutControls hide">';
			echo  		'<img class="processing-spinner hide" src="'.get_stylesheet_directory_uri().'/images/ajax-loader-32.gif" alt="Your payment is processing."/>';
			echo    	'<button class="btn btn-primary submitPayment">Submit Your Payment</button>';
			echo  	'</div>';
			echo 		'<div class="checkoutThanks checkoutControls hide">';
			echo    	'<button class="btn btn-primary closeCheckout" data-dismiss="modal" aria-hidden="true">Close</button>';
			echo  	'</div>';
			echo  '</div>'; // .modal-footer
			echo '</div>'; // .modal (#checkout)

		?>

</article><!-- #post-<?php the_ID(); ?> -->