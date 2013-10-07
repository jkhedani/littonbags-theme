<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package _s
 * @since _s 1.0
 */
?>

	</div><!-- #main .site-main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<h3 class="explore-title">Explore</h3>
		<?php
			wp_nav_menu(array(
				'menu' => 'footer-menu',
				'menu_class' => 'nav-pills pull-right'
			));
		?>
	</footer><!-- #colophon .site-footer -->
	</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

<?php render_shopping_cart(); ?>

</body>
</html>