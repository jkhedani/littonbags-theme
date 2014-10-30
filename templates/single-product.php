<?php
/**
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" class="row">

	<!-- Points of Interest: Plus Button Version -->
	<?php $product_pois = get_field('product_poi'); ?>
	<?php foreach ($product_pois as $product_poi) : ?>
		<?php $posx = $product_poi['product_poi_position_left'];?>
		<?php $posy = $product_poi['product_poi_position_top'];?>
		<button class="poi <?php echo $product_poi['product_poi_open_direction_x']; ?> <?php echo $product_poi['product_poi_open_direction_y']; ?>" style="left: <?php echo $posx; ?>px;top: <?php echo $posy; ?>px;">
			<i class="fa fa-plus"></i>
			<div class="poi-content">
				<strong><?php echo $product_poi['product_poi_title']; ?></strong>
				<?php echo $product_poi['product_poi_content']; ?>
			</div>
		</button>
	<?php endforeach; ?>

	<!-- Product Description(s) -->
	<header class="product-header span3">
		<h1 class="product-title"><?php the_title(); ?></h1>
		<h2 class="product-subtitle"><?php echo get_field( 'product_subtitle' ); ?></h2>
		<?php if ( $post->post_content=="" && is_user_logged_in() ) : ?>
			<p class="muted helper-text">You currently have no description. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>
		<?php else : ?>
			<div class="product-details"><?php echo get_field( 'product_overview' ); ?></div>
			<!-- Points of Interest: Text Version -->
			<?php if ( $product_pois ) : ?>
				<ul class="poi text">
					<li><h3>Features</h3></li>
					<?php foreach ($product_pois as $product_poi) : ?>
					<li>
						<a>
							<span><?php echo $product_poi['product_poi_title']; ?></span>
							<i class="fa fa-plus"></i>
							<div class="poi-content"><?php echo $product_poi['product_poi_content']; ?></div>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endif; ?>
		<!-- Product Lookbook Link -->
		<?php
			$lookbooks = new WP_Query(array(
				'post_type' 			=> 'look_books',
				'posts_per_page' 	=> -1, // Limit one for home page
				'meta_key'				=> 'look_book_location',
				'meta_query' 			=> array (
					array (
						'key' 		=> 'look_book_location',
						'value' 	=> '"' . $post->ID . '"',
						'compare' => 'LIKE'
					)
				)
			));
		?>
		<?php if ( $lookbooks->have_posts() ) : ?>
			<?php while ( $lookbooks->have_posts() ) : $lookbooks->the_post(); ?>
				<div id="generate-lookbook" class="lookbook-link-container">
					<a class="lookbook-link" href="#generate-lookbook" data-look-book-id="<?php echo $post->ID; ?>">View <?php echo get_the_title(); ?> Lookbook</a>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<!-- Product Options -->
	<div class="product-content product-options span2">
		<?php
			// # Each "Product Option" is its own individual SKU
			if ( get_field('product_skus') ) :
				$product_skus = get_field('product_skus');
			endif;
		?>

		<!-- Product Price -->
		<?php foreach ( $product_skus as $product_sku ) : ?>
			<div class="product-price" data-sku="<?php echo $product_sku['sku']; ?>"><?php echo format_money( $product_sku['sku_price'], 'US' ); ?></div>
		<?php endforeach; ?>

		<!-- Product Color -->
		<div class="product-color-container">
			<h3 class="product-color-title">Select a Color</h3>
			<select class="product-option product-color-selection user-selectable-option" data-sku-option-type="sku_color_name">
				<?php $product_skus = get_field('product_skus'); ?>
				<?php foreach ($product_skus as $product_sku) : ?>
					<option
					 	value="<?php echo $product_sku['sku_color_name']; ?>"
						data-sku="<?php echo $product_sku['sku']; ?>"
			 			data-sku-color="<?php echo $product_sku['sku_color']; ?>"
						data-option-sold-out="<?php if ( $product_sku['sku_quantity'] === '0' ) echo 1; ?>"
						<?php if ( $product_sku === reset($product_skus) ) echo 'selected="selected"'; ?>
						>
						<?php echo $product_sku['sku_color_name']; ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div><!-- .product-color-container -->

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

		<?php
		// # Create invisible descriptors for every sku
		for ( $i = 0; $i < count($product_skus); $i++ ) {
			// # Add some post data into the mix
			$product_skus[$i]['post_id'] = $post->ID;
			$product_skus[$i]['product_name'] = get_the_title();
			// # For each of the skus, gather various pieces of data
			$product_skus_keys = array_keys( $product_skus[$i] );
			// # Iterate through all the sku keys and construct
			for ( $ii = 0; $ii < count($product_skus_keys); $ii++ ) {
				// # Remove "private" data VALUE from loop
				if ( $product_skus_keys[$ii] === 'sku_quantity' ) {
					// do nothing for removed array keys
				} else {
					// Add the product sku values for each
					$product_skus_keys[$ii] = $product_skus_keys[$ii] . '="'.$product_skus[$i][$product_skus_keys[$ii]].'"';
				}
			}
			// # Remove "private" data KEY from loop (after construction)
			$sku_quantity_key = array_search('sku_quantity', $product_skus_keys);
			unset($product_skus_keys[$sku_quantity_key]);
			$productDataAttr = 'data-' . implode(' data-', $product_skus_keys);
			?>
			<div class="descriptor" <?php echo $productDataAttr; ?>></div>
		<?php }

		// # Add to Basket
		// 	 Collate all user inputed data here to interpret what a user wants to
		//   add to their cart.
		// # Create the initial data set based on the first product sku
		// $product_skus_keys = array_keys( $product_skus[0] );
		// // # Remove "private" data from loop
		// $sku_quantity_key = array_search('sku_quantity', $product_skus_keys);
		// unset($product_skus_keys[$sku_quantity_key]);
		// // # Iterate through all the sku keys and construct
		// for ( $i = 0; $i < count($product_skus_keys); $i++ ) {
		// 	$product_skus_keys[$i] = $product_skus_keys[$i] . '="'.$product_skus[0][$product_skus_keys[$i]].'"';
		// }
		// $productDataAttr = 'data-' . implode(' data-', $product_skus_keys);
		?>
		<a id="add-handbasket-item" role="button" href="javascript:void(0);" class="btn btn-primary btn-primary-add-to-cart add-handbasket-item">Add to Basket</a>

	</div><!-- .product-content -->


<?php
/**

	1. Disable add to cart button until all options have been selected.
	1. When an option is selected, determine


*/
?>








	<div class="product-scroll">
		<?php echo get_the_post_thumbnail(); ?>
	</div>

	<!-- Back To Top -->
	<div class="back-to-top" data-toggle="page-top"><i class="fa fa-chevron-up"></i></div>

</article><!-- #post-<?php the_ID(); ?> -->
