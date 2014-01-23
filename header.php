<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package _s
 * @since _s 1.0
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title><?php
	//Print the <title> tag based on what is being viewed. 
	global $page, $paged;

  //Add page/content title
	wp_title( '|', true, 'right' );

	// Add the site name.
	bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );

?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php bloginfo( 'stylesheet_directory' ); ?>/images/favicon.png" />
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/inc/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>

<?php
  // ADMIN: Move navbar down from under admin when user is
  // logged in but not in the theme customizer previewer
  global $wp_customize;
  if ( is_user_logged_in() && ! isset( $wp_customize ) ) {
    echo '
    <style type="text/css">
      #navbar { margin-top: 28px; } /* Positions navbar below admin bar */
      #main { padding-top: 88px; } /* Lowers all content below navbar to approximate position */
      @media (max-width: 979px) {
        #main { padding-top: 0px; } /* Navbar turns static, no need for compensation here*/
      }
    </style>';
  }
?></head>

<body <?php body_class(); ?>>
  
  <div id="page" class="hfeed site">

  	<header id="navbar" class="navbar navbar-fixed-top navbar-inverse">
    	<div class="navbar-inner">
        <div class="container">
          <!-- Site Logo -->
          <a class="site-title" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>    

          <!-- Shopping Cart Trigger -->
          <div class="shoppingcart">
            <a href="javascript:void(0);" class="shoppingcartshow">Cart</a>
          </div>
          <!-- Site Main Menu -->
          <div class="menu-main-menu-container">
            <a class="mobile-menu-toggle hide" href="#menu-main-menu" title="Toggle menu view for smaller devices.">Menu</a>
            <?php wp_nav_menu( array(
              'menu' => 'mobile-menu',
              'menu_class' => 'mobile-menu',
              'container' => false,
            )); ?>
            <?php wp_nav_menu( array(
              'menu' => 'main-menu',
              'menu_class' => 'main-menu',
              'container' => false,
            )); ?>
          </div>
        </div>
    	</div><!-- .navbar-inner -->
    </header><!-- #navbar -->

  	<div id="main" role="main" class="site-main">

    <?php if ( is_front_page() ) : ?>

      <!-- Background image -->
      <div id="homeCarousel" class="carousel slide">
        <div class="carousel-inner">
        <?php $i = 0; ?>
        <?php $home_featured_gallery = get_field('home_gallery'); ?>
        <?php foreach ( $home_featured_gallery as $home_featured_gallery_meta ) { ?>
            <div class="background-image item <?php if (!$i++) echo 'active'; ?>" style="background-image:url(<?php echo $home_featured_gallery_meta['home_gallery_image']; ?>);"></div>
        <?php } ?>
        </div><!-- .carousel-inner -->
        <a class="carousel-control left" href="#homeCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#homeCarousel" data-slide="next">&rsaquo;</a>
      </div><!-- .carousel -->

      <!-- The Minster Call To Action -->
      <a class="btn btn-primary btn-primary-home" href="<?php echo get_site_url(); ?>/products/the-minster">Shop The Bag</a>
      
    <?php endif; ?>
