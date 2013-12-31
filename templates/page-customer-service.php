<?php
/**
 * Template name: Customer Service
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
				
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>

					<header class="entry-header span7">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content span6">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

				</article><!-- #post-<?php the_ID(); ?> -->
				
			<?php
				// Customer service menu
				wp_nav_menu(array( 'menu' => 'customer-service' ));
			?>

			<?php endwhile; // end of the loop. ?>

			<?php echo get_the_post_thumbnail(); ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->
		<?php //get_sidebar(); ?>

<?php get_footer(); ?>