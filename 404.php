<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package _s
 * @since _s 1.0
 */

get_header(); ?>

	<div class="row-fluid"><!-- Bootstrap: REQUIRED! -->
		<div id="primary" class="content-area span8">
			<div id="content" class="site-content" role="main">

				<article id="post-0" class="post error404 not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Snap! That page can&rsquo;t be found.', '_s' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'It looks like nothing was found at this location.', '_s' ); ?></p>
						<p><a href="<?php get_home_url(); ?>" title="Take me back to the home page."><?php _e( 'Take me back home.', '_s' ); ?></a></p>

						<?php get_search_form(); ?>

					</div><!-- .entry-content -->
				</article><!-- #post-0 .post .error404 .not-found -->

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->
	</div><!-- .row-fluid -->

<?php get_footer(); ?>