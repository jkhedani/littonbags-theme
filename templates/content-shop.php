<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package _s
 * @since _s 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php bedrock_postcontentstart(); ?>

	<header class="entry-header">
		<?php bedrock_abovetitle(); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php bedrock_belowtitle(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', '_s' ), 'after' => '</div>' ) ); ?>
		<?php edit_post_link( __( 'Edit', '_s' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->
	<?php
		// Retrieve all product types (product categories)
		$product_types = get_categories('taxonomy=product_type');
		// Product Tabs
		echo '<ul class="nav nav-tabs product-types" id="product-type-tabs">';
		foreach ($product_types as $product_type) {
			echo '<li><a href="#'.$product_type->slug.'" data-toggle="tab">'.$product_type->name.'</a></li>';
		}
		echo '</ul>';
		// Product Content
		echo '<div class="tab-content product-types">';
		foreach ($product_types as $product_type) {
			echo '<div class="tab-pane" id="'.$product_type->slug.'">';
				$products = new WP_Query(
				array(
					'post_type' => 'products',
					'product_type' => $product_type->slug
				));
				while($products->have_posts()) : $products->the_post();
					echo get_the_title();
				endwhile;
				wp_reset_postdata();
			echo '</div>';
		}
		echo '</div>';

  ?>
	<?php bedrock_postcontentend(); ?>

</article><!-- #post-<?php the_ID(); ?> -->
