<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area container">
			<div id="content" class="site-content" role="main">
				
			<?php while ( have_posts() ) : the_post();

				if ( $post->ID === 19 ) : // "Shop"
					get_template_part( 'templates/content', 'shop' );
				else:
					get_template_part( 'templates/page', 'default' );
				endif;

				comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>
			
			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->
		<?php //get_sidebar(); ?>

<?php get_footer(); ?>