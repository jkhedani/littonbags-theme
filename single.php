<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area container">
			<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); 

				// Use appropriate templates for specific content types
				if ( get_post_type() === 'products' ):
					get_template_part( 'templates/single', 'product' );
				else:
					get_template_part( 'single', 'default' );
				endif;

				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template( '', true );
			
			?>

			<?php endwhile; // end of the loop. ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->
		
		<div class="content-fluff stamp-watermark"></div>

<?php get_footer(); ?>