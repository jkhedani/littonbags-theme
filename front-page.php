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
				<?php
					/**
					 * Use ACF only to display lookbooks
					 * @since 1.2.0
					 */
					$lookbooks = new WP_Query(array(
						'post_type' 			=> 'look_books',
						'posts_per_page' 	=> 1, // Limit one for home page
						'meta_key'				=> 'look_book_location',
						'meta_query' 			=> array (
							array (
								'key' 		=> 'look_book_location',
								'value' 	=> '"' . $post->ID . '"',
								'compare' => 'LIKE'
							)
						)
					));
					$i = 0;
					while ( $lookbooks->have_posts() ) : $lookbooks->the_post();
						if ( have_rows('look_book', $post->ID ) ) :
							while ( have_rows('look_book', $post->ID ) ) : the_row();
								$lookbook_image = wp_get_attachment_image_src( get_sub_field('look_book_page'), 'full' ); ?>
								<img class="item <?php if (!$i++) echo 'active'; ?>" src="<?php echo $lookbook_image[0]; ?>" />
							<?php endwhile;
						endif;
					endwhile;
					wp_reset_postdata();
				?>
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
