<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>

	<div class="customer-service"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/customer-service-v1.jpg" alt="Customer Service" /></div>

	<?php echo get_the_post_thumbnail(); ?>

	<header class="entry-header span7">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content span6">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
