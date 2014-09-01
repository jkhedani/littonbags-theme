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
			<div class="product-options">

				<!-- Display Option: Product Price -->
				<span class="product-price" data-standard-product-price="<?php echo format_money( get_field('product_price'), 'US' ); ?>"><?php echo format_money( get_field('product_price'), 'US' ); ?></span>

				<!-- Selectable Option: Product Color -->
				<?php if ( get_field('product_options') ) { ?>
					<div class="product-color-container">
						<h3 class="product-color-title">Select a Color</h3>
						<select class="product-option product-color-selection" data-target="data-product_color_name">
							<?php $productOptions = get_field('product_options'); ?>
							<?php foreach ($productOptions as $productOption) : ?>
								<option
								 	value="<?php echo $productOption['product_color_name']; ?>"
						 			data-background-color="<?php echo $productOption['product_color']; ?>"
									data-option-price="<?php echo format_money( $productOption['product_option_price'], 'US' ); ?>"
									data-option-sold-out="<?php echo $productOption['product_option_sold_out']; ?>"
									<?php if ( $productOption === reset($productOptions) ) echo 'selected="selected"'; ?>
									>
									<?php echo $productOption['product_color_name']; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div><!-- .product-color-container -->
				<?php } ?>

				<!-- Product Quantity -->
				<div class="product-quantity-container">
					<h3 class="product-qty-title">Quantity</h3>
					<select class="product-qty-selection">
						<?php for( $i = 1; $i < 11; $i++ ) { ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
				<hr />

			</div><!-- .product-options -->
		</div><!-- .product-left-col -->

		<div class="product-right-col">
			<?php
				// # Add to Basket / Sold Out
				// 	 Construct data attributes based on options. Default value
				//	 is the first option. May want to give ability to set
				//   default option in the future.
				//	 NOTE: Removing "sold out option" as a product should never
				//	 be added to the cart if it is sold out.
				$productOptionKeys = array_keys( $productOptions[0] );
				if ( ($sold_out_key = array_search('product_option_sold_out', $productOptionKeys)) !== false ) {
					unset($productOptionKeys[$sold_out_key]);
				}
				for ( $i = 0; $i < count($productOptionKeys); $i++ ) {
					$productOptionKeys[$i] = $productOptionKeys[$i] . '="'.$productOptions[0][$productOptionKeys[$i]].'"';
				}
				$productDataAttr = 'data-' . implode(' data-', $productOptionKeys);
			?>
			<?php if ( get_field( 'product_sold_out' ) ) : ?>
				<a id="sold-out" href="javascript:void(0);" class="btn btn-primary btn-primary-sold-out show">Sold Out</a>
			<?php else : ?>
				<a id="add-handbasket-item" role="button" href="javascript:void(0);" class="btn btn-primary btn-primary-add-to-cart add-handbasket-item" data-post_id="<?php echo $post->ID; ?>" data-product_name="<?php echo get_the_title(); ?>" data-product_qty="1" <?php echo $productDataAttr; ?>>Add to Basket</a>
			<?php endif; ?>
		</div><!-- .product-right-col -->

	</div><!-- .product-content -->
	<div class="product-scroll">
		<?php echo get_the_post_thumbnail(); ?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
