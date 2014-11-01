<?php
/**
 * The Header For Our Theme.
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _s
 * @since _s 1.0
 */

/**
 * Metadata Generation
 * Generate various pieces of meta data for the site's head
 */
// # <meta name="description">
if ( get_field('product_description') ) :
	$page_description = get_field('product_description');
else :
	$page_description = get_bloginfo( 'description', 'display' );
endif;

// # <meta name="keywords">
$page_keywords = 'women\'s camera bags, women\'s camera purse, leather camera bags, premium camera bags, stylish camera bags, lens case, lens holder, lens bag, padded camera bag, designer camera bag, designer camera purse, dslr bag, dslr camera bag, dslr padded bag, lens cloth, camera bag, Litton photography bag, Litton camera bag, photo bag, photography bag';

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php wp_title( '|' ); ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<meta name="description" content="<?php echo $page_description; ?>" />
	<meta name="keywords" content="<?php echo $page_keywords; ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="shortcut icon" href="<?php bloginfo( 'stylesheet_directory' ); ?>/images/favicon-32x32.png" />
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/inc/js/html5.js" type="text/javascript"></script>
	<![endif]-->

	<?php wp_head(); ?>

<!-- Google Analytics Script -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53110929-1', 'auto');
  ga('send', 'pageview');

</script>

</head>

<body <?php body_class(); ?>>
  <div id="page" class="hfeed site">
  	<header id="navbar" class="navbar navbar-fixed-top navbar-inverse">
    	<div class="navbar-inner">
        <div class="container">

          <!-- Site Logo -->
          <a class="site-title mobile" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/litton-ludwig-logo-white@2X.png" alt="Litton Fine Camera Bags" /></a>
					<a class="site-title" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/litton-ludwig-logo@2X.png" alt="Litton Fine Camera Bags" /></a>

          <!-- Hand Basket Toggle -->
          <a href="javascript:void(0);" data-toggle="hand-basket"><i class="fa fa-shopping-cart"></i></a>

          <!-- Site Main Menu -->
          <div class="menu-main-menu-container">
            <a class="mobile-menu-toggle" href="#menu-main-menu" title="Toggle menu view for smaller devices."><i class="fa fa-bars"></i></a>
            <?php
							wp_nav_menu( array (
	              'menu' => 'mobile-menu',
	              'menu_class' => 'mobile-menu',
	              'container' => false,
	            ));
						?>
            <?php
							$locations = get_nav_menu_locations();
							$menu = wp_get_nav_menu_object( $locations['main-menu'] );
							$menu_items = wp_get_nav_menu_items( $menu->term_id );
						?>
						<ul id="menu-<?php echo $menu->slug; ?>" class="main-menu">
							<?php foreach ( $menu_items as $menu_item ) { ?>
								<li>
									<a title="<?php echo $menu_item->attr_title; ?>" href="<?php echo $menu_item->url; ?>">
										<?php echo $menu_item->title; ?>
										<i class="shape flag"></i>
									</a>
								</li>
							<?php }?>
						</ul>
          </div>

        </div><!-- container -->
    	</div><!-- .navbar-inner -->
    </header><!-- #navbar -->

  	<div id="main" role="main" class="site-main">

    <?php if ( is_front_page() ) : ?>

    <?php endif; ?>
