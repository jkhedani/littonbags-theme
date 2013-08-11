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

  <?php bedrock_before(); ?>
  
  <div id="page" class="hfeed site">
   
    <?php bedrock_aboveheader();?>
    
    <header id="page-header" class="container">
    	<div id="navbar" class="navbar navbar-inverse">
      	<div class="navbar-inner">
            <a class="brand site-title" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>    
            <!-- Shopping Cart Nav -->
            <ul class="nav pull-right">
              <li class="shoppingcart"><a href="#" class="shoppingcartshow">Cart</a></li>
            </ul>
      	</div><!-- .navbar-inner -->
      </div><!-- #navbar -->
    </header><!-- .container -->

    <?php bedrock_belowheader();?>

  	<div id="main" role="main" class="site-main row">

    <!-- Background image -->
    <?php if ( is_front_page() ) : ?>
      <img class="background-image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/home-bg.jpg" alt="Home background image" />
    <?php endif; ?>

    <?php bedrock_mainstart(); ?>