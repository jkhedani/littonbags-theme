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

			<div class="minster-type-treatment">
				<h1><?php _e('The Minster','litton_bags'); ?></h1>
				<span class="minster-flair">~</span>
				<span class="subtitle"><?php _e('A Luxury Camera Bag','litton_bags'); ?></span>
				<br />
			</div>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->
	<?php //get_sidebar(); ?>

<?php get_footer(); ?>