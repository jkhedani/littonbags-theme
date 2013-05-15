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
			
	<div id="primary" class="content-area row">
		<div id="content" class="site-content span12" role="main">
			
			<div class="row">
				<header class="span12">
					<h1><?php _e('Welcome','litton_bags'); ?></h1>
					<a href="/wp/products/the-minster/" class="btn btn-primary">Get The Minster</a>
					<a href="#" class="btn btn-primary test" data-toggle="popover" data-content="Here's some test content" data-original-title="A Title">Test</a>
				</header>
			</div>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->
	<?php //get_sidebar(); ?>

<?php get_footer(); ?>