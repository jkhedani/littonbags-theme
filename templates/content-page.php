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
		<?php bedrock_abovetitle(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php bedrock_belowtitle(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content span7">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '_s' ), 'after' => '</div>' ) ); ?>
		<?php edit_post_link( __( 'Edit', '_s' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->

	<?php bedrock_postcontentend(); ?>

</article><!-- #post-<?php the_ID(); ?> -->
