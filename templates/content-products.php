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
		// Button to trigger modal
		echo '<a id="buynow" href="#userloginoptions" role="button" class="btn" data-toggle="modal">Buy now!</a>';

		// "BUY NOW!"
		if(!is_user_logged_in()) {
			 
			// Modal
			echo '<div id="userloginoptions" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">';
			echo  	'<div class="modal-header">';
			echo    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
			echo    '<h3 id="myModalLabel">'. __('Welcome!','litton_bags') .'</h3>';
			echo  '</div>';
			echo  '<div class="modal-body">';
	    
			// Forms: http://wordpress.stackexchange.com/questions/95139/custom-registration-template-page
	    ?>
	    <!-- Login -->
			<div class="login hide">

			</div>
	    <!-- Registration -->
	    <div class="registration hide">
	        <div id="register-form">
	            <form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
	                <input type="text" name="user_login" value="Username" id="user_login" class="input" placeholder="Username" />
	                <input type="text" name="user_email" value="E-Mail" id="user_email" class="input"  placeholder="E-Mail" />
	                    <?php do_action('register_form'); ?>
	                    <input class="btn btn-primary" type="submit" value="<?php _e('Register'); ?>" id="register" />
	                <hr />
	                <p class="statement"><?php _e('A password will be e-mailed to you.'); ?></p>
	            </form>
	        </div>
	    </div><!-- /Registration -->
	    <?php
	    
	    // Provide selection options
			echo '<div class="loginoptions">';
			echo 		'<a class="login btn btn-primary" href="/wp-login.php">Log in</a>';
			echo 		'<a class="register btn btn-success" href="/wp-login.php?action=register">Register</a>';
			echo '</div>';

			echo  '</div>';
			echo  '<div class="modal-footer">';
			//echo    '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
			//echo    '<button class="btn btn-primary">Save changes</button>';
			echo  '</div>';
			echo '</div>';

		} else {

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

		} // is_user_logged_in()

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