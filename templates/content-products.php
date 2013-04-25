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
		<div class="entry-meta">
			<?php //_s_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
	
	<?php
		// PAGE CONTENT
		if($post->post_content=="") {
			echo '<p class="muted helper-text">You currently have no content. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>';
		} else {
			the_content();
		}
	?>

	<?php

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
		

		/*
		 * "Add To Cart"
		 */
		echo '<hr />';
		// Button to trigger modal
		if(!is_user_logged_in()) {
			echo '<a id="addToCart" href="#" role="button" class="btn btn-info" data-post-id="'.$post->ID.'">Add To Cart</a>';
		} else {
			// Logged in user view...
		}

		/* 
		 * "Shopping Cart Options"
		 */

		// Modal 1: "Register"
		if(!is_user_logged_in()) { 
			echo '<div id="userregister" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">';
			echo  '<div class="modal-header">';
			echo    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
			echo    '<h3 id="myModalLabel">'. __('Welcome!','litton_bags') .'</h3>';
			echo  '</div>';
			echo  '<div class="modal-body">';
			echo 		'<p>Sign up, create a shopping cart and shop Litton Bags whenever you feel like!</p>';
			echo 		'<p>If you do not want to sign up, you can checkout using our guest checkout :)</p>';
			echo 		'<a class="register progress btn btn-success" href="/wp-login.php?action=register">New</a>';
		    ?>
		    <!-- Login -->
		    <!-- http://codex.wordpress.org/Function_Reference/wp_signon -->
				<div class="login-form hide">
					<?php wp_login_form(); ?>
				</div>
		    <?php
			echo 		'<a class="login btn btn-primary" href="/wp-login.php">Returning</a>';
			echo  '</div>';
			echo  '<div class="modal-footer">';
			//echo    '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
			//echo    '<button class="btn btn-primary">Add To Cart</button>';
			echo  '</div>';
			echo '</div>';
		}
		
		// Modal 2: "Add to Cart" option parameters
		echo '<div id="cartoptions" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">';
		echo  '<div class="modal-header">';
		echo    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
		echo    '<h3 id="myModalLabel">'. __('Product Options','litton_bags') .'</h3>';
		echo  '</div>';
		echo  '<div class="modal-body">';
		if (get_field('product_color_options')) {
			$colorOptions = get_field('product_color_options');
			echo '<h3>Select Color</h3>';
			echo '<select>';
			foreach ($colorOptions as $colorOption) {
				echo '<option>'.$colorOption.'</option>';
			}
			echo '</select>';
		}
		echo '<h3>Quantity</h3>';
		echo '<select><option>1</option><option>2</option><option>3</option></select>';
		echo  '</div>';
		echo  '<div class="modal-footer">';
		//echo    '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
		echo    '<button class="btn btn-primary">Add To Cart</button>';
		echo  '</div>';
		echo '</div>';

		/* 
		 * "Checkout Options"
		 */
		if(!is_user_logged_in()) { 

			// Modal 1: User Options Modal
			echo '<div id="usercheckoutoptions" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">';
			echo  	'<div class="modal-header">';
			echo    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
			echo    '<h3 id="myModalLabel">'. __('Welcome!','litton_bags') .'</h3>';
			echo  '</div>';
			echo  '<div class="modal-body">';
		  
		  echo  '<p>Here at Litton we believe simplicity is best so our checkout process is, well, quite simple. If you don&#39;t need a shopping cart or do not want to save your credit card option, select our guest option.</p>';
		  echo 	'<p>Review, Pay and Confrim.</p>';
				// Forms: http://wordpress.stackexchange.com/questions/95139/custom-registration-template-page
		    ?>
		    <!-- Login -->
		    <!-- http://codex.wordpress.org/Function_Reference/wp_signon -->
				<div class="login-form hide">
					<?php wp_login_form(); ?>
				</div>
		    <?php

	    // Provide user checkout options
			echo 		'<div class="loginoptions">';
			echo 			'<a class="login btn btn-primary" href="/wp-login.php">Returning Fan</a>';
			echo 			'<a class="guest progress btn btn-success" href="/wp-login.php?action=register">Guest Fan</a>';
			echo 		'</div>';

			echo  '</div>';
			echo  '<div class="modal-footer">';
			//echo    '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
			//echo    '<button class="btn btn-primary">Save changes</button>';
			echo  '</div>';
			echo '</div>';
		}

		// Modal 2: Checkout Options Modal
		echo '<div id="checkoutform" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">';
		echo  	'<div class="modal-header">';
		echo    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
		echo    '<h3 id="myModalLabel">'. __('Checkout','litton_bags') .'</h3>';
		echo  '</div>';
		echo  '<div class="modal-body">';

			// "STRIPE Variables
			$productPrice = get_field('product_price'); // in 'cents'
			$productPriceInDollars = $productPrice/100; // in 'dollars'
			$english_notation = number_format($productPriceInDollars,2,'.',''); // in eng notation 'dollars'

			// "STRIPE" Checkout
			if(isset($_GET['payment']) && $_GET['payment'] == 'paid') {
				echo '<p class="success">' . __('Thank you for your payment.', 'litton_bags') . '</p>';
			} else {

				// "Stripe": Error Message
				echo '<div class="payment-errors alert hide"></div>';
				echo '<h2>Your Total Cost: &#36;'.$english_notation.' USD</h2>';
				// "Stripe": Payment Form
				echo '<form action="" method="POST" id="stripe-payment-form">';
				//		NAME
				echo 	'<div class="form-row">';
				echo 		'<label>'. __('Full Name', 'litton_bags') .'</label>';
				echo 		'<input type="text" size="20" autocomplete="off" data-stripe="name" />';
				echo 	'</div>';
				//		ADDRESS
				echo 	'<div class="form-row">';
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
				echo 	'</div>';				
				// 		CARD NUMBER
				echo 	'<div class="form-row">';
				echo 		'<label>'. __('Card Number', 'litton_bags') .'</label>';
				echo 		'<input type="text" size="20" autocomplete="off" data-stripe="number" />';
				echo 	'</div>';
				// 		CVC
				echo 	'<div class="form-row">';
				echo 		'<label>'. __('CVC', 'litton_bags') .'</label>';
				echo 		'<input type="text" size="4" autocomplete="off" data-stripe="cvc" />';
				echo 	'</div>';
				// 		EXPIRATION
				echo 	'<div class="form-row">';
				echo 		'<label>'. __('Expiration (MM/YYYY)', 'litton_bags') .'</label>';
				echo 		'<input type="text" size="2" data-stripe="exp-month" />';
				echo 		'<span> / </span>';
				echo 		'<input type="text" size="4" data-stripe="exp-year" />';
				echo 	'</div>';

				//		WORDPRESS DATA VALUES (NO SENSITIVE FORMS BELOW THIS LINE!)	
				echo 	'<input type="hidden" name="action" value="stripe"/>';
				echo 	'<input type="hidden" name="redirect" value="'. get_permalink() .'"/>';
				echo 	'<input type="hidden" name="stripe_nonce" value="'. wp_create_nonce('stripe-nonce').'"/>';
				echo 	'<input type="hidden" name="amount" value="'.base64_encode($productPrice).'"/>';
				echo 	'<button type="submit" class="btn btn-primary" id="stripe-submit">'. __('Submit Payment', 'litton_bags') .'</button>';
				echo '</form>';
			}

		echo  '</div>';
		echo  '<div class="modal-footer">';
		//echo    '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
		//echo    '<button class="btn btn-primary">Save changes</button>';
		echo  '</div>';
		echo '</div>';

	?>
	
	<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '_s' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php
	//echo '<footer class="entry-meta">';
		
			/* translators: used between list items, there is a space after the comma */
			// $category_list = get_the_category_list( __( ', ', '_s' ) );

			// /* translators: used between list items, there is a space after the comma */
			// $tag_list = get_the_tag_list( '', ', ' );

			// if ( ! _s_categorized_blog() ) {
			// 	// This blog only has 1 category so we just need to worry about tags in the meta text
			// 	if ( '' != $tag_list ) {
			// 		$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '_s' );
			// 	} else {
			// 		$meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '_s' );
			// 	}

			// } else {
			// 	// But this blog has loads of categories so we should probably display them here
			// 	if ( '' != $tag_list ) {
			// 		$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '_s' );
			// 	} else {
			// 		$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', '_s' );
			// 	}

			// } // end check for categories on this blog

			// printf(
			// 	$meta_text,
			// 	$category_list,
			// 	$tag_list,
			// 	get_permalink(),
			// 	the_title_attribute( 'echo=0' )
			// );
			
			//edit_post_link( __( 'Edit', '_s' ), '<span class="edit-link">', '</span>' );
	//echo '</footer>';// .entry-meta ?>

	<?php bedrock_postcontentend(); ?>

</article><!-- #post-<?php the_ID(); ?> -->