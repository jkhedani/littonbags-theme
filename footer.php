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
	<div id="push"></div>
	</div><!-- #page .hfeed .site -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<div class="site-info">
				<?php
					echo '<div class="footer-credits">&copy; '.date('Y');
					echo ' | <a href="#">Feedback(NEED MAILTO ADDRESS)</a>';
					echo '</div>';
				?>
			</div><!-- .site-info -->
		</div><!-- .container -->
	</footer><!-- #colophon .site-footer -->

<?php wp_footer(); ?>
<?php bedrock_after(); ?>

</body>
</html>