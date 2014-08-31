<?php
/**
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" class="row">

	<!-- Product Description(s) -->
	<header class="product-header span3">
		<h1 class="product-title"><?php the_title(); ?></h1>
		<h2 class="product-subtitle"><?php echo get_field( 'product_subtitle' ); ?></h2>
		<?php if ( $post->post_content=="" && is_user_logged_in() ) : ?>
			<p class="muted helper-text">You currently have no description. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>
		<?php else : ?>
			<div class="product-details"><?php echo get_field( 'product_overview' ); ?></div>
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
			<?php while ( $lookbook->have_posts() ) : $lookbook->the_post(); ?>
				<div id="generate-lookbook" class="lookbook-link-container">
					<a class="lookbook-link" href="#generate-lookbook" data-look-book-id="<?php echo $post->ID; ?>">View The Lookbook</a>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<!-- Product Options -->
	<div class="product-content span2">

			<div class="product-left-col">
				<?php
					// General Options
					$productOptions = get_field('product_options');
					$productPrice = get_field('product_price');
				?>
				<div class="product-options">

					<!-- Product Price -->
					<span class="product-option product-price" data-standard-product-price="<?php echo format_money( $productPrice, 'US' ); ?>"><?php echo format_money( $productPrice, 'US' ); ?></span>

					<!-- Product Color -->
					<?php

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
					?>
				</div><!-- .product-options -->
			</div><!-- .product-left-col -->


			<div class="product-right-col">
				<!-- Add To Basket / Sold Out -->
				<?php
					// # Construct data attributes based on options. Default value
					//	 is the first option. May want to give ability to set
					//   default option in the future.
					$productOptionKeys = array_keys( $productOptions[0] );
					for ( $i = 0; $i < count($productOptionKeys); $i++ ) {
						$productOptionKeys[$i] = $productOptionKeys[$i] . '="'.$productOptions[0][$productOptionKeys[$i]].'"';
					}
					$productDataAttr = 'data-' . implode(' data-', $productOptionKeys);
				?>
				<?php if ( get_field( 'product_sold_out' ) ) : ?>
					<a id="sold-out" href="javascript:void(0);" class="btn btn-primary btn-primary-sold-out show">Sold Out</a>
				<?php else : ?>
					<a id="add-to-hand-basket" role="button" href="javascript:void(0);" class="btn btn-primary btn-primary-add-to-cart" data-post-id="<?php echo $post->ID; ?>" <?php echo $productDataAttr; ?>></a>
				<?php endif; ?>
			</div><!-- .product-right-col -->

	</div><!-- .product-content -->

	<div class="product-scroll">
		<?php echo get_the_post_thumbnail(); ?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
