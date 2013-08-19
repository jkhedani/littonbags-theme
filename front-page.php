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
					
			<?php
				wp_nav_menu(array(
					'menu' => 'highlight-menu',
					'menu_class' => 'pull-right',
					'link_before' => '<i class="tab brown"></i>'
				));
			?>
			<h1><?php _e('Welcome','litton_bags'); ?></h1>
			<a href="/wp/products/the-minster/" class="btn btn-primary">Get The Minster</a>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->
	<?php //get_sidebar(); ?>

<?php get_footer(); ?>