<?php

function LTTNBAGS_dequeue_scripts() {
  wp_dequeue_script( 'bootstrap-tooltip' );
}
add_action( 'wp_print_scripts', 'LTTNBAGS_dequeue_scripts', 100 );

function LTTNBAGS_enqueue_scripts() {

  // Assign the appropriate protocol
  $protocol = 'http:';
  if ( !empty($_SERVER['HTTPS']) ) $protocol = 'https:';

  // Enqueue Fonts
  wp_enqueue_style( 'google-fonts-oswald', '//fonts.googleapis.com/css?family=Oswald:300,400', array(), false, 'all' );
  wp_enqueue_style( 'google-fonts-source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600', array(), false, 'all' );
  wp_enqueue_style( 'google-fonts-josefin-sans', '//fonts.googleapis.com/css?family=Josefin+Sans:300,400', array(), false, 'all' );
  wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css', array(), false, 'all' );

  // Enqueue Styles
  // Must come after bootstrap styles as some styles override others.
  wp_enqueue_style( 'diamond-style', get_stylesheet_directory_uri().'/css/diamond-style.css', array('bootstrap-styles') );

  // Enqueue Scripts
  wp_enqueue_script( 'bootstrap-carousel-script', get_stylesheet_directory_uri().'/js/bootstrap/carousel.js', array(), false, true );
  wp_enqueue_script( 'jquery-validate', get_stylesheet_directory_uri().'/js/jquery.validate.js', array('jquery') );
  wp_enqueue_script( 'jquery-payment', get_stylesheet_directory_uri().'/js/jquery.payment.js', array('jquery') );

  // Look Book
  wp_enqueue_script('look-books-scripts', get_stylesheet_directory_uri().'/lib/LookBooks/look-books-scripts.js', array('jquery','json2'), true);
  wp_localize_script('look-books-scripts', 'look_book_data', array(
    'ajaxurl' => admin_url('admin-ajax.php',$protocol),
    'nonce' => wp_create_nonce('look_books_nonce')
  ));

  // General
  wp_enqueue_script('diamond-custom-script', get_stylesheet_directory_uri().'/js/scripts.js', array('jquery'), false, true);


}
add_action( 'wp_enqueue_scripts', 'LTTNBAGS_enqueue_scripts' );

/**
 * To Market
 * A simple shopping + checkout solution for WP.
 * @url https://github.com/jkhedani/ToMarket
 */
require_once( get_stylesheet_directory() . '/lib/ToMarket/tomarket.php');

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
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'shop',
        )
    )
  );
  // "Look Book"
  $labels = array(
    'name' => __( 'Look Books' ),
    'singular_name' => __( 'Look Book' ),
    'add_new' => __( 'Add New Look Book' ),
    'add_new_item' => __( 'Add New Look Book' ),
    'edit_name' => __( 'Edit This Look Book' ),
    'view_item' => __( 'View This Look Book' ),
    'search_items' => __('Search Look Books'),
    'not_found' => __('No Look Books found.'),
  );
  register_post_type( 'look_books',
    array(
        'menu_position' => 5,
        'public' => true,
        'supports' => array('title'),
        'labels' => $labels,
        'has_archive' => true,
        // 'rewrite' => array(
        //     'slug' => 'shop',
        // )
    )
  );
}
add_action( 'init', 'LTTNBAGS_post_types' );

/**
 *  P2P Connections
 */
function LTTNBAGS_connection_types() {

  // Connect
  p2p_register_connection_type( array(
    // Connnection Attributes
    'name' => 'look_books_to_products',
    'from' => 'look_books',
    'to' => 'products',
    // 'reciprocol' => true,
    // 'admin_box' => 'from',
    'sortable' => 'any',
    // 'title' => array( 'from' => __( 'Connected Modules', 'my-textdomain' ), 'to' => __( 'Connected Unit', 'my-textdomain' ) ),
    // 'from_labels' => array(
    //   'singular_name' => __( 'Unit', 'my-textdomain' ),
    //   'search_items' => __( 'Search Units', 'my-textdomain' ),
    //   'not_found' => __( 'No Units found.', 'my-textdomain' ),
    //   'create' => __( 'Connect to a Unit', 'my-textdomain' ),
    // ),
    // 'to_labels' => array(
    //   'singular_name' => __( 'Module', 'my-textdomain' ),
    //   'search_items' => __( 'Search Modules', 'my-textdomain' ),
    //   'not_found' => __( 'No Modules found.', 'my-textdomain' ),
    //   'create' => __( 'Connect a Module', 'my-textdomain' ),
    // ),
  ));

}
add_action( 'p2p_init', 'LTTNBAGS_connection_types' );

/**
 *  Look Books
 *  Include function after P2P so connections are availabled
 */
require_once( get_stylesheet_directory() . '/lib/LookBooks/look-books-functions.php');

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
    //'rewrite' => array( 'slug' => '%product_type%', 'with_front' => false ), // Seems to be causing permalink issues
  ));
}
add_action( 'init', 'LTTNBAGS_taxonomies');


/**
 * Designate Litton Bags Menu Locations
 */
function LTTNBAGS_designate_menu_locations() {
  register_nav_menu( 'main-menu', __( 'Main Menu' ) );
  register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}
add_action( 'init', 'LTTNBAGS_designate_menu_locations' );

/**
 * SEO: Create a more descriptive <title>
 */
function LTTNBAGS_improved_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	// Append the page / post name instead of prepending it.
	$title = get_bloginfo( 'name', 'display' ) . $title;

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	return $title;
}
add_filter( 'wp_title', 'LTTNBAGS_improved_wp_title', 10, 2 );


/** DEPRECIATED? */
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
 *  Add body class to pages that are not the home page to help
 *  styles distiguish between the two
 */
function page_not_home_class( $classes ) {
    if ( ! is_front_page() ) {
        $classes[] = 'page-not-home';
    }
    return $classes;
}
add_filter('body_class','page_not_home_class');

/**
 *  Page Jump Short Codes
 */

// [jumppage title="" px="" anchor="" ]
function jumppage_function( $atts ) {
    extract( shortcode_atts( array(
        'title' => 'Some Title',
        'px' => '100',
        'anchor' => '',
    ), $atts ) );
    return "<a class='jumppage' href='' data-jump-coordinates='{$px}'>{$title}</a>";
}
function register_shortcodes() {
    add_shortcode( 'jumppage', 'jumppage_function' );
}
add_action('init','register_shortcodes');

?>
