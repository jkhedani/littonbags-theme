<?php
/**
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php bedrock_postcontentstart(); ?>

	<header class="entry-header">
		
		<?php bedrock_abovetitle(); ?>
		
		<h1 class="entry-title"><?php the_title(); ?></h1>
		
		<?php bedrock_belowtitle(); ?>
		
		<hr />

	</header><!-- .entry-header -->

	<div class="entry-content">
	
	<?php
			/*
			 * Bag Description
			 */

			if ( $post->post_content=="" && is_user_logged_in() ) {
				echo '<p class="muted helper-text">You currently have no description. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>';
			} else {
				the_content();
			}

			/*
			 * User Selected Options
			 */

			// Color Options
			if (get_field('product_color_options')) {
				$colorOptions = get_field('product_color_options');
				echo '<h3>Select Color</h3>';
				echo '<select class="product-color-selection">';
				foreach ($colorOptions as $colorOption) {
					echo '<option value="'.$colorOption.'">'.$colorOption.'</option>';
				}
				echo '</select>';
			}

			// Quantity Options
			echo '<h3>How many would you like to add?</h3>';
			echo '<select class="product-qty-selection">';
			for($i = 1; $i < 11; $i++) {
				echo '<option value="'.$i.'">'.$i.'</option>';	
			}
			echo '</select>';
			
			echo '<hr />';
		
			/*
			 * "Add To Cart" Button
			 */
			echo '<a id="addToCart" role="button" href="#" class="btn btn-info" data-post-id="'.$post->ID.'">Add To Cart</a>';

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

					// "Stripe": Payment Form
					echo '<form action="" method="POST" id="stripe-payment-form">';
					
					// 		FORM ERRORS
					echo '<div class="payment-errors alert hide"></div>';

					// 		PERSONAL INFO
					echo 	'<div class="form-row">';
					echo 	'<legend>Basic Information</legend>';
					echo 		'<label>'. __('Full Name', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="name" />';
					echo 		'<label>'. __('Email Address', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" name="email" />'; // ARE WE DOING THIS CORRECTLY?!
					echo 	'</div>';

					//		ADDRESS
					echo 	'<div class="form-row">';
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
					echo 	'<div class="form-row">';
					echo 		'<legend>Card Information</legend>';
					echo 		'<label>'. __('Card Number', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="20" autocomplete="off" data-stripe="number" />';
					echo 		'<label>'. __('CVC', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="4" autocomplete="off" data-stripe="cvc" />';
					echo 		'<label>'. __('Expiration (MM/YYYY)', 'litton_bags') .'</label>';
					echo 		'<input type="text" size="2" data-stripe="exp-month" />';
					echo 		'<span> / </span>';
					echo 		'<input type="text" size="4" data-stripe="exp-year" />';
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
			echo  '<img src="'.get_stylesheet_directory_uri().'/images/ajax-loader.gif" alt="Your payment is processing."/>';
			echo  '<p>Please wait for your payment to process. Refrain from closing this page to avoid multiple charges.</p>';
			echo  '</div>';

			// Checkout Step Four: Thank You
			echo 	'<div class="checkoutThanks hide">';
			echo  '</div>';

			echo  '</div>'; // .modal-body
			echo  '<div class="modal-footer">';
			//echo    '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
			echo 		'<div class="checkoutReview checkoutControls show">';
			echo    	'<button class="btn btn-primary choosePaymentMethod">Select A Payment Method</button>';
			echo 		'</div>';
			echo 		'<div class="checkoutPay checkoutControls hide">';
			echo    	'<button class="btn btn-primary submitPayment">Submit Your Payment</button>';
			echo  	'</div>';
			echo 		'<div class="checkoutThanks checkoutControls hide">';
			echo    	'<button class="btn btn-primary closeCheckout" data-dismiss="modal" aria-hidden="true">Close</button>';
			echo  	'</div>';
			echo  '</div>'; // .modal-footer
			echo '</div>'; // .modal (#checkout)

	?>
	
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '_s' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	
	<?php bedrock_postcontentend(); ?>

</article><!-- #post-<?php the_ID(); ?> -->