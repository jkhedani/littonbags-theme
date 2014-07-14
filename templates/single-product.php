<?php
/**
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" class="row">

	<header class="product-header span3">		
		<h1 class="product-title"><?php the_title(); ?></h1>
		<h2 class="product-subtitle"><?php echo get_field( 'product_subtitle' ); ?></h2>
		<?php if ( $post->post_content=="" && is_user_logged_in() ) : ?>
			<p class="muted helper-text">You currently have no description. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>
		<?php else : ?>
		<p class="product-details"><?php echo do_shortcode( get_the_content() ); ?></p>
		<?php endif; ?>

		<!-- Product Lookbook Link -->
		<?php
			// Here we want to check if a lookbook is available. If so, let's render the gallery.
			$lookbook = new WP_Query( array(
				'post_type' => 'look_books',
				'connected_type' => 'look_books_to_products',
  			'connected_items' => get_queried_object(),
  			'nopaging' => true,
			));
		?>
		<?php if ( $lookbook->have_posts() ) : ?>
		<?php 	while ( $lookbook->have_posts() ) : $lookbook->the_post(); ?>

			<div id="generate-lookbook" class="lookbook-link-container">
				<a class="lookbook-link" href="#generate-lookbook" data-look-book-id="<?php echo $post->ID; ?>">View The Lookbook</a>
			</div>

		<?php 	endwhile; ?>
		<?php 	wp_reset_postdata(); ?>
		<?php endif; ?>

	</header><!-- .entry-header -->

	<!-- Product Details -->
	<div class="product-content span2">
	
	<?php
		/*
		 * User Selected Options
		 */

		// Get Product Options (price, colors options, etc.)
		$productOptions = get_field('product_options');

		echo '<div class="product-left-col">';
			// Price
			if ( get_field('product_price') ) {
				$productPrice = get_field('product_price');
				echo '<span class="product-price" data-standard-product-price="'.format_money( $productPrice, 'US' ).'">'.format_money( $productPrice, 'US' ).'</span>';
			}

			// Color Options
			if ( get_field('product_options') ) {
				echo '<div class="product-color-container">';
				echo '<h3 class="product-color-title">Select a Color</h3>';
				echo '<select class="product-color-selection" data-product-sold-out="';
				if (get_field( 'product_sold_out' )) echo '1';
				echo '">';
				foreach ($productOptions as $productOption) {
					echo '<option value="'.$productOption['product_color_name'].'"';
					if ($productOption === reset($productOptions)) {
						echo 'data-selected="1"';
					}
					echo ' data-background-color="'.$productOption['product_color'].'" data-option-price="'.format_money( $productOption['product_option_price'], 'US' ).'" data-option-sold-out="'.$productOption['product_option_sold_out'].'">'.$productOption['product_color_name'].'</option>';
				}
				echo '</select>';
				echo '</div>'; // .product-color-container
			}

			// Quantity Options
			echo 	'<div class="product-quantity-container">';
			echo 		'<h3 class="product-qty-title">Quantity</h3>';
			echo 		'<select class="product-qty-selection">';
			for($i = 1; $i < 11; $i++) {
			echo 			'<option value="'.$i.'">'.$i.'</option>';	
			}
			echo 		'</select>';
			echo 	'</div>'; // .product-quantity-container

			echo '<hr />';
		echo '</div>'; // .product-left-col

		echo '<div class="product-right-col">';
			/*
			 * "Add To Cart" Button or "Sold Out"
			 */
			if ( get_field( 'product_sold_out' ) ) {
				// Sold Out
				echo '<a id="soldOut" href="javascript:void(0);" class="btn btn-primary btn-primary-sold-out show">Sold Out</a>';
				echo '<a id="addToCart" role="button" href="javascript:void(0);" class="btn btn-primary btn-primary-add-to-cart hide" data-post-id="'.$post->ID.'">Add To Cart</a>';
			} else {
				// Add To Cart
				echo '<a id="soldOut" href="javascript:void(0);" class="btn btn-primary btn-primary-sold-out hide">Sold Out</a>';
				echo '<a id="addToCart" role="button" href="javascript:void(0);" class="btn btn-primary btn-primary-add-to-cart show" data-post-id="'.$post->ID.'">Add To Cart</a>'; 
			}

		echo '</div>'; // .product-right-col

		?>

	</div><!-- .product-content -->

	<div class="product-scroll">
		<?php echo get_the_post_thumbnail(); ?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->