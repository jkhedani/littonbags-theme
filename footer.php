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
	
	<?php bedrock_mainend(); ?>

	</div><!-- #main .site-main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info container">
			<?php
				wp_nav_menu(array(
					'menu' => 'footer-menu',
					'menu_class' => 'nav-pills pull-right'
				));
			?>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->
	</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>
<?php bedrock_after(); ?>

</body>
</html>