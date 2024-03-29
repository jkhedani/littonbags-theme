<?php
/**
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('row'); ?>>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<hr />
		<div class="entry-meta">
			<?php //_s_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		if($post->post_content=="") {
			echo '<p class="muted helper-text">You currently have no content. Add some <a href="'.get_edit_post_link().'" title="Edit this piece of content">here.</a></p>';
		} else {
			the_content();
		}
		?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '_s' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	
</article><!-- #post-<?php the_ID(); ?> -->