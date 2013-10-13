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
		<?php
			// Get a list of h2 elements from the content to create navigation
			// $str = get_the_content();
			// $DOM = new DOMDocument;
			// $DOM->loadHTML($str);

			// $items = $DOM->getElementsByTagName('h2');

			// echo '<ul class="faqs-navigation">';
			// for ( $i = 0; $i < $items->length; $i++ )
   //    	echo '<li>' . $items->item($i)->nodeValue . '</li>';
   //    echo '</ul>';
		?>
	</header><!-- .entry-header -->

	<div class="entry-content span6">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
