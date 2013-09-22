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
				// wp_nav_menu(array(
				// 	'menu' => 'highlight-menu',
				// 	'menu_class' => 'pull-right',
				// 	'link_before' => '<i class="tab brown"></i>'
				// ));
			?>
			<div class="minster-type-treatment">
				<h1><?php _e('The Minster','litton_bags'); ?></h1>
				<span class="minster-flair">~</span>
				<span class="subtitle"><?php _e('A Luxury Camera Bag','litton_bags'); ?></span>
				<br />
				<a class="btn btn-primary" href="<?php echo get_site_url(); ?>/products/the-minster">discover the bag</a>
			</div>

			<?php render_shopping_cart(); ?>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->
	<?php //get_sidebar(); ?>

<?php get_footer(); ?>