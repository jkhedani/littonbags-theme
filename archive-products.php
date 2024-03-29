<?php
/**
 * "Shop Page"
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area container">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<?php
				// A. Retrieve and display products sorted by their content type
				$cameraBags = new WP_Query( array(
					'post_type' => 'products',
					'product_type' => 'camera-bags',
					'order' => 'ASC',
					'orderby' => 'date',
				));
				$accessories = new WP_Query( array(
					'post_type' => 'products',
					'product_type' => 'accessories',
				));
			?>
			<div id="productViewer" class="view-master">

				<div class="view-master-controls">
		    	<a class="view-master-control left" href="#productViewer" data-slide="prev">&lsaquo;</a>
		    	<a class="view-master-control right" href="#productViewer" data-slide="next">&rsaquo;</a>
		    </div>

		    <div class="view-master-reel">
		    <?php
		    	// First, load all bags in a descending order
		    	while ( $cameraBags->have_posts() ) : $cameraBags->the_post();
		    ?>
		    	<div class="slide bag-slide">
		    		<div class="slide-content">
		    			<h1 class="slide-title"><?php echo get_the_title(); ?></h1>
		    			<h2 class="slide-subtitle"><?php echo get_field('product_subtitle'); ?></h2>
		    			<a class="slide-view-more" href="<?php echo get_permalink(); ?>">View Bag >>></a>
		    		</div>
		    		<?php if ( get_field('product_shop_image') ) { ?>
		    			<div class="slide-image-container">
 		    				<img class="featured-image" src="<?php echo get_field('product_shop_image'); ?>" />
 		    			</div>
 		    		<?php } ?>
		    	</div>
		    <?php
		    	endwhile;
		    	wp_reset_postdata();
		    ?>

		    <?php // Insert Landing slide here ?>
		    <div class="slide center-slide active">
		    	<div class="slide-content">
		    		<h1 class="bags">
		    			<a class="view-master-control left" data-slide="prev" href="#productViewer">
		    			<span class="content-fluff pink-arrow left"></span>
		    			<span>Bags</span>
		    			</a>
		    		</h1>
		    		<h1 class="accessories">
		    			<a class="view-master-control right" data-slide="next" href="#productViewer">
		    				<span>Accessories</span>
		    				<span class="content-fluff pink-arrow right"></span>
		    			</a>
		    		</h1>
		    	</div>
		    </div>

		    <?php
		    	// Third load all bags in a descending order
		    	while ( $accessories->have_posts() ) : $accessories->the_post();
		    ?>
		    	<div class="slide accessories-slide">
		    		<div class="slide-content">
		    			<h1 class="slide-title"><?php echo get_the_title(); ?></h1>
		    			<h2 class="slide-subtitle"><?php echo get_field('product_subtitle'); ?></h2>
		    			<a class="slide-view-more" href="<?php echo get_permalink(); ?>">View >>></a>
		    		</div>
		    		<?php if ( get_field('product_shop_image') ) { ?>
		    			<div class="slide-image-container">
 		    				<img class="featured-image" src="<?php echo get_field('product_shop_image'); ?>" />
 		    			</div>
 		    		<?php } ?>
		    	</div>
		    <?php
		    	endwhile;
		    	wp_reset_postdata();
		    ?>

		    </div><!-- .view-master-reel -->
		  </div><!-- .view-master -->


		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

		</div><!-- #content .site-content -->
	</section><!-- #primary .content-area -->

<?php get_footer(); ?>
