<?php

/**
 * Helper Functions
 */

// HTML set content type for sending html through WP_Mail
function set_html_content_type() {
    return 'text/html';
}

// Generate Random Order Number
function generateRandomOrderNumber( $length ) {
    $chars = array_merge(range('A', 'Z'), range(0, 9));
    shuffle($chars);
    return implode(array_slice($chars, 0, $length));
}

// Money formatter
// Accepts only cents
function format_money( $amount, $currencyType ) {
    // Format: US
    if ( $currencyType == 'US' ) {
        $nonos = array( '.', '$' ); // Set possible objects to strip
        $priceInPennies = str_replace( $nonos, '', $amount); // ensure cents
        $prettyMoney = money_format( '%n', $priceInPennies/100 ); // Format yo cash
        return $prettyMoney;
    }
}

/**
 * Globals & Constants
 */
$stripe_options = get_option('stripe_settings');
$easypost_options = get_option('easypost_settings');

/**
 * Properly add new script files using this function.
 * http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
function diamond_scripts() {

    // Assign the appropriate protocol
    $protocol = 'http:';
    if ( !empty($_SERVER['HTTPS']) ) $protocol = 'https:';

    // Enqueue Fonts
    wp_enqueue_style( 'google-fonts-oswald', '//fonts.googleapis.com/css?family=Oswald:300,400', array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600', array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-josefin-sans', '//fonts.googleapis.com/css?family=Josefin+Sans:300,400', array(), false, 'all' );
    wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css', array(), false, 'all' );

    // Enqueue Styles
    wp_enqueue_style( 'bootstrap-styles', get_stylesheet_directory_uri().'/css/bootstrap/bootstrap.css' );
	  wp_enqueue_style( 'diamond-style', get_stylesheet_directory_uri().'/css/diamond-style.css' );

    // Enqueue Scripts
    # Modal
    # Tooltip (required for popovers)
    wp_enqueue_script( 'bootstrap-transition-script', get_stylesheet_directory_uri().'/js/bootstrap/transition.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-modal-script', get_stylesheet_directory_uri().'/js/bootstrap/modal.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-tooltip-script', get_stylesheet_directory_uri().'/js/bootstrap/tooltip.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-popover-script', get_stylesheet_directory_uri().'/js/bootstrap/popover.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-tab-script', get_stylesheet_directory_uri().'/js/bootstrap/tab.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-carousel-script', get_stylesheet_directory_uri().'/js/bootstrap/carousel.js', array(), false, true );
    wp_enqueue_script( 'jquery-validate', get_stylesheet_directory_uri().'/js/jquery.validate.js', array('jquery') );
    wp_enqueue_script( 'jquery-payment', get_stylesheet_directory_uri().'/js/jquery.payment.js', array('jquery') );

    // Stripe
    // https://stripe.com/
    global $stripe_options;
    if ( isset($stripe_options['test_mode']) && $stripe_options['test_mode'] ) {
        $publishable = $stripe_options['test_publishable_key']; // Use Test API Key for Stripe Processing
    } else {
        $publishable = $stripe_options['live_publishable_key']; // Use Test API Key for Stripe Processing
    }
    wp_enqueue_script('stripe-processing', get_stylesheet_directory_uri().'/lib/StripeScripts/stripe-processing.js', array('jquery') );
    wp_localize_script('stripe-processing', 'stripe_vars', array(
        'publishable_key' => $publishable,
    ));

    // PayPal
    wp_enqueue_script('paypal-scripts', get_stylesheet_directory_uri().'/lib/paypal/payments/paypal-payment-scripts.js', array('jquery','json2'), true);
    wp_localize_script('paypal-scripts', 'paypal_data', array(
        'ajaxurl' => admin_url('admin-ajax.php',$protocol),
        'nonce' => wp_create_nonce('paypal_nonce')
    ));

    // jStorage
    // http://www.jstorage.info/
    wp_enqueue_script('jstorage-script', get_stylesheet_directory_uri().'/js/jstorage.js', array('jquery','json2'));
    wp_enqueue_script('diamond-custom-script', get_stylesheet_directory_uri().'/js/scripts.js', array('jquery'), false, true);

    // Shopping Cart
    wp_enqueue_script('shopping-cart-scripts', get_stylesheet_directory_uri().'/lib/ShoppingCart/shopping-cart.js', array('jquery','json2'), true);
    wp_localize_script('shopping-cart-scripts', 'shopping_cart_scripts', array(
        'ajaxurl' => admin_url('admin-ajax.php',$protocol),
        'nonce' => wp_create_nonce('shopping_cart_scripts_nonce')
    ));

    // Look Book Fetcher
    wp_enqueue_script('look-book-fetcher-scripts', get_stylesheet_directory_uri().'/lib/LookBookFetcher/look-book-fetcher-scripts.js', array('jquery','json2'), true);
    wp_localize_script('look-book-fetcher-scripts', 'look_book_fetcher_data', array(
      'ajaxurl' => admin_url('admin-ajax.php',$protocol),
      'nonce' => wp_create_nonce('look_book_fetcher_nonce')
    ));

}
add_action( 'wp_enqueue_scripts', 'diamond_scripts' );

/**
 * Shopping Cart
 * By Justin Hedani
 * Uses: Ajax, jStorage & Bootstrap
 */
require_once( get_stylesheet_directory() . '/lib/ShoppingCart/shopping-cart.php');
require_once( get_stylesheet_directory() . '/lib/ShoppingCart/shopping-cart-markup.php');

/**
 * "Stripe" Integration
 * With lots of love from: http://pippinsplugins.com/series/integrating-stripe-com-with-wordpress/
 */

// Load "Stripe" settings & Payment processors
if ( is_admin() ) {
    require_once( get_stylesheet_directory() . '/lib/StripeScripts/settings.php' );
} else {
    require_once( get_stylesheet_directory() . '/lib/StripeScripts/stripe-process-payment.php' );
    require_once( get_stylesheet_directory() . '/lib/StripeScripts/stripe-listener.php' );
}

/**
 *  PayPal Functions
 */
require_once( get_stylesheet_directory() . '/lib/paypal/payments/method-paypal.php' );

// function submit_welcome_form() {
//     global $wpdb, $current_user;
//     $nonce = $_REQUEST['nonce'];
//     if ( ! wp_verify_nonce( $nonce, 'ajax_interactions_nonce' ) ) {
//         die( __('Busted.') ); // Nonce check
//     }
//     $html = "";
//     $success = false;
//     $params = array();
//     parse_str( $_REQUEST['postdata'], $params ); // Unserialize post data

//     // Update our user meta
//     update_user_meta( $current_user->ID, 'registration-reason', $params['registration-reason'] );
//     update_user_meta( $current_user->ID, 'education-level', $params['education-level'] );
//     update_user_meta( $current_user->ID, 'location', $params['location'] );
//     update_user_meta( $current_user->ID, 'allow-data-access', $params['allow-data-access'] );

//     $success = true;
//     $response = json_encode( array(
//         'success' => $success,
//         'html' => $html,
//     ));

//     header( 'content-type: application/json' );
//     echo $response;
//     exit;
// }
// add_action( 'wp_ajax_nopriv_submit_welcome_form', 'submit_welcome_form' );
// add_action( 'wp_ajax_submit_welcome_form', 'submit_welcome_form' );

// if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'submit_welcome_form' )  ) {
//     do_action( 'wp_ajax_' . $_REQUEST['action'] );
//     do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
// }

/**
 *  "Easy Post" Integration
 *  https://www.easypost.com
 */
if ( is_admin() ) {
    require_once( get_stylesheet_directory() . '/lib/EasyPostScripts/settings.php' );
}
//require_once( get_stylesheet_directory() . '/lib/easypost.php' );

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
 *  Look Book Fetcher
 *  Include function after P2P so connections are availabled
 */
require_once( get_stylesheet_directory() . '/lib/LookBookFetcher/look-book-fetcher-functions.php');

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
 * Create Litton Bags Menu Structure
 */

// Designate menu locations
function LTTNBAGS_designate_menu_locations() {
  register_nav_menu( 'main-menu', __( 'Main Menu' ) );
  register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}
add_action( 'init', 'LTTNBAGS_designate_menu_locations' );

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
