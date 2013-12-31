<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>

	<?php echo get_the_post_thumbnail(); ?>

	<header class="entry-header span7">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content span7">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
