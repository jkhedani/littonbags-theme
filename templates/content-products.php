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

		<!-- Product Specifications -->
		<ul class="product-specifications">
			<li><a href="#features" data-scroll-position="500">Features</a></li>
			<li><a href="#interior" data-scroll-position="1500">Interior & Material</a></li>
			<li><a href="#modular-storage" data-scroll-position="2200">Modular Storage</a></li>
		</ul>

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

		// Price
		if ( get_field('product_price') ) {
			$productPrice = get_field('product_price');
			echo '<span class="product-price" data-standard-product-price="'.format_money( $productPrice, 'US' ).'">'.format_money( $productPrice, 'US' ).'</span>';
		}

		// Quantity Options
		echo '<h3 class="product-qty-title">Quantity</h3>';
		echo '<select class="product-qty-selection">';
		for($i = 1; $i < 11; $i++) {
			echo '<option value="'.$i.'">'.$i.'</option>';	
		}
		echo '</select>';

		// Color Options
		if ( get_field('product_options') ) {
			//$colorOptions = get_field('product_color_options');
			echo '<h3 class="product-color-title">Select a Color</h3>';
			echo '<select class="product-color-selection">';
			foreach ($productOptions as $productOption) {
				echo '<option value="'.$productOption['product_color_name'].'" data-background-color="'.$productOption['product_color'].'" data-option-price="'.format_money( $productOption['product_option_price'], 'US' ).'">'.$productOption['product_color_name'].'</option>';
			}
			echo '</select>';
		}

		echo '<hr />';
	
		/*
		 * "Add To Cart" Button
		 */
		echo '<a id="addToCart" role="button" href="javascript:void(0);" class="btn btn-primary btn-primary-add-to-cart" data-post-id="'.$post->ID.'">Add To Cart</a>'; ?>

	</div><!-- .product-content -->

	<div class="product-scroll">
		<?php echo get_the_post_thumbnail(); ?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->