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
			
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<!-- Background image -->
      <div id="homeCarousel" class="carousel slide">
        <div class="carousel-inner">
        <?php $i = 0; ?>
        <?php $home_featured_gallery = get_field('home_gallery'); ?>
        <?php foreach ( $home_featured_gallery as $home_featured_gallery_meta ) { ?>
          <img class="item <?php if (!$i++) echo 'active'; ?>" src="<?php echo $home_featured_gallery_meta['home_gallery_image']; ?>" />
        <?php } ?>
        </div><!-- .carousel-inner -->
        <a class="carousel-control left" href="#homeCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#homeCarousel" data-slide="next">&rsaquo;</a>
      </div><!-- .carousel -->

      <!-- The Minster Call To Action -->
      <a class="btn btn-primary btn-primary-home" href="<?php echo get_site_url(); ?>/products/the-minster">Shop The Bag</a>

			<div class="minster-type-treatment">
				<h1><?php _e('The Minster','litton_bags'); ?></h1>
				<span class="minster-flair">~</span>
				<span class="subtitle"><?php _e('A Luxury Camera Bag','litton_bags'); ?></span>
				<br />
			</div>

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>