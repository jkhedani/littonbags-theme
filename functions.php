<?php

/**
 * Place any hand-crafted Wordpress
 * Please read the documentation on how to use this file within child theme (README.md)
 */

/**
 *  Globals & Constants
 */
$stripe_options = get_option('stripe_settings');

/**
 * Properly add new script files using this function.
 * http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
function diamond_scripts() {
    $protocol='http:'; // discover the correct protocol to use
    if(!empty($_SERVER['HTTPS'])) $protocol='https:';
	// Enqueue Styles
    wp_enqueue_style( 'diamond-style', get_stylesheet_directory_uri().'/css/diamond-style.css' );
	// Activate line below for responsive layout
	// Requires: Child theme style, resets, parent theme base style and bootstrap base style
	// to load prior to responsive. Responsive styles should typically be loaded last.
	//wp_enqueue_style( 'diamond-style-responsive', get_stylesheet_directory_uri().'/css/diamond-style-responsive.css', array('diamond-style','resets','bootstrap-base-styles','bootstrap-parent-style'));

    /*
     * Set proper API keys based on Stripe Settings in wordpress
     */
    global $stripe_options;
    if(isset($stripe_options['test_mode']) && $stripe_options['test_mode']) {
        $publishable = $stripe_options['test_publishable_key']; // Use Test API Key for Stripe Processing
    } else {
        $publishable = $stripe_options['live_publishable_key']; // Use Test API Key for Stripe Processing
    }
    // Enqueue Scripts
    wp_enqueue_script('bootstrap-transition-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-transition.js', array(), false, true);
    wp_enqueue_script('bootstrap-modal-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-modal.js', array(), false, true);
    wp_enqueue_script('bootstrap-tooltip-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-tooltip.js', array(), false, true);
    wp_enqueue_script('bootstrap-popover-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-popover.js', array(), false, true);
    wp_enqueue_script('bootstrap-tab-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-tab.js', array(), false, true);

    wp_enqueue_script('json2');
    wp_enqueue_script('jquery');
    
    wp_enqueue_script('stripe-processing', get_stylesheet_directory_uri().'/lib/StripeScripts/stripe-processing.js');
    wp_localize_script('stripe-processing', 'stripe_vars', array(
            'publishable_key' => $publishable,
    ));
    // jStorage
    // http://www.jstorage.info/
    wp_enqueue_script('jstorage-script', get_stylesheet_directory_uri().'/js/jstorage.js');
    wp_enqueue_script('diamond-custom-script', get_stylesheet_directory_uri().'/js/scripts.js', array(), false, true);

    // Shopping Cart  
    wp_enqueue_script('shopping-cart-scripts', get_stylesheet_directory_uri().'/lib/ShoppingCart/shopping-cart.js', array('jquery','json2'), true);
    wp_localize_script('shopping-cart-scripts', 'shopping_cart_scripts', array(
        'ajaxurl' => admin_url('admin-ajax.php',$protocol),
        'nonce' => wp_create_nonce('shopping_cart_scripts_nonce')
    ));
    
}
add_action( 'wp_enqueue_scripts', 'diamond_scripts' );

/**
 * Shopping Cart
 * By Justin Hedani
 * Uses: Ajax, jStorage & Bootstrap
 */
require_once( get_stylesheet_directory() . '/lib/ShoppingCart/shopping-cart.php');

/**
 * "Stripe" Integration
 * With lots of love from: http://pippinsplugins.com/series/integrating-stripe-com-with-wordpress/
 */

// Load "Stripe" settings & Payment processors
if (is_admin()) {
    require_once( get_stylesheet_directory() . '/lib/StripeScripts/settings.php' );
} else {
    require_once( get_stylesheet_directory() . '/lib/StripeScripts/stripe-process-payment.php' );
    require_once( get_stylesheet_directory() . '/lib/StripeScripts/stripe-listener.php' );
}

/**
 * Custom Post Types (e.g. Products, etc.)
 */
function LTTNBAGS_post_types() {
  global $product_type;
  // "Products"
  $labels = array(
    'name' => __( 'Products' ),
    'singular_name' => __( 'Product' ),
    'add_new' => __( 'Add New Product' ),
    'add_new_item' => __( 'Add New Product' ),
    'edit_name' => __( 'Edit This Product' ),
    'view_item' => __( 'View This Product' ),
    'search_items' => __('Search Products'),
    'not_found' => __('No Products found.'),
  );
  register_post_type( 'products',
    array(
    	'menu_position' => 5,
    	'public' => true,
    	'supports' => array('title', 'editor', 'thumbnail'),
    	'labels' => $labels,
    )
  );
}
add_action( 'init', 'LTTNBAGS_post_types' );

/**
 * Custom Taxonomies (e.g. Product Type, etc.)
 */
function LTTNBAGS_taxonomies() {
	// "Product Type" category
  $labels = array(
    'name' => _x( 'Product Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Product Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Product Types' ),
    'all_items' => __( 'All Product Types' ),
    'parent_item' => __( 'Parent Product Type' ),
    'parent_item_colon' => __( 'Parent Product Type:' ),
    'edit_item' => __( 'Edit Product Type' ), 
    'update_item' => __( 'Update Product Type' ),
    'add_new_item' => __( 'Add New Product Type' ),
    'new_item_name' => __( 'New Product Type' ),
    'menu_name' => __( ' Edit Product Types' ),
  ); 	
  register_taxonomy('product_type',array('products'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'publicly_queryable' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => '%product_type%', 'with_front' => false ),
  ));
}
add_action( 'init', 'LTTNBAGS_taxonomies');

/**
 * Add custom body classes
 */
function custom_body_class($classes) {
    global $post;
    // "Shop"
    if ( $post->ID === 19 ) {
        $classes[] = 'shop';
        return $classes;
    } else {
        return $classes;
    }
}
add_filter('body_class','custom_body_class');

/**
 * Create Litton Bags Menu Structure
 */
function LTTNBAGS_primary_menu() {
    global $post;
    $productcount = wp_count_posts('products'); // Total products
    $publishedproductcount = $productcount->publish; // Total products published
    ;
    // If there are more than one published products, show nav
    if ( $publishedproductcount > 1 ) {
        // create nav here, justin.
    }
}
add_action('bedrock_abovepostcontent','LTTNBAGS_primary_menu');

/**
 * Custom Hook Functions
 *
 * Use these hooks to add/insert functions/content at specific load points within the Wordpress loading process.
 * Inspired by Thematic
 * A list of all hook functions and what templates they are used in:
 *
 *	bedrock_before()
 *		bedrock_aboveheader()
 *		(header)
 *		bedrock_belowheader()
 *		bedrock_mainstart()
 *			bedrock_contentstart()
 *			(breadcrumbs)
 *			bedrock_abovepostcontent()
 *				bedrock_postcontentstart()
 *				(postcontent)
 *					bedrock_abovetitle()
 *					bedrock_belowtitle()
 *				bedrock_postcontentend()
 *			bedrock_belowpostcontent()
 *			bedrock_contentend()
 *			bedrock_sidebarstart()
 *			(sidebar)
 *			bedrock_sidebarend()
 *			(pager)
 *		bedrock_mainend()
 *	bedrock_after()
 *
 * Here is an example of how to properly hook into a function:
 *
 *		function nameOfMyNewFunction() {
 *			// content to output
 *		}
 *		add_action('theNameOfTheHookTheContentAboveWillGetLoaded','nameOfMyNewFunction');
 *
 */




?>