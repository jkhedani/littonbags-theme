<?php

/**
 * Place any hand-crafted Wordpress
 * Please read the documentation on how to use this file within child theme (README.md)
 */

/**
 *  Register Field Groups
 *
 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 */

// if( function_exists("register_field_group") ) {
    // Product Details
    // register_field_group(array (
    //     'id' => 'acf_product-details',
    //     'title' => 'Product Details',
    //     'fields' => array (
    //         array (
    //             'key' => 'field_5292ec4ae2a07',
    //             'label' => 'Product Options',
    //             'name' => 'product_options',
    //             'type' => 'repeater',
    //             'sub_fields' => array (
    //                 array (
    //                     'key' => 'field_5292ec62e2a08',
    //                     'label' => 'Product Color Name',
    //                     'name' => 'product_color_name',
    //                     'type' => 'text',
    //                     'column_width' => '',
    //                     'default_value' => '',
    //                     'placeholder' => '',
    //                     'prepend' => '',
    //                     'append' => '',
    //                     'formatting' => 'html',
    //                     'maxlength' => '',
    //                 ),
    //                 array (
    //                     'key' => 'field_529ac9f2ab9c9',
    //                     'label' => 'Product Color',
    //                     'name' => 'product_color',
    //                     'type' => 'color_picker',
    //                     'instructions' => 'Select a close color for this option.',
    //                     'default_value' => '',
    //                 ),
    //                 array (
    //                     'key' => 'field_5292ec76e2a09',
    //                     'label' => 'Product Checkout Image Preview',
    //                     'name' => 'product_checkout_image_preview',
    //                     'type' => 'image',
    //                     'column_width' => '',
    //                     'save_format' => 'url',
    //                     'preview_size' => 'thumbnail',
    //                     'library' => 'all',
    //                 ),
    //                 array (
    //                     'key' => 'field_529ac989c7bcc',
    //                     'label' => 'Product Option Price',
    //                     'name' => 'product_option_price',
    //                     'type' => 'number',
    //                     'instructions' => 'Enter the price of the product in cents(USD) e.g. "34900"',
    //                     'default_value' => '',
    //                     'placeholder' => '',
    //                     'prepend' => '',
    //                     'append' => '',
    //                     'min' => '',
    //                     'max' => '',
    //                     'step' => '',
    //                 ),
    //                 array (
    //                     'default_value' => 0,
    //                     'message' => 'Product option sold out.',
    //                     'key' => 'field_52c60bb9cd0cc',
    //                     'label' => 'Product Option Sold Out',
    //                     'name' => 'product_option_sold_out',
    //                     'type' => 'true_false',
    //                     'instructions' => 'Did you sell out of this option?',
    //                 ),
    //             ),
    //             'row_min' => 0,
    //             'row_limit' => '',
    //             'layout' => 'row',
    //             'button_label' => 'Add Option',
    //         ),
    //         array (
    //             'default_value' => '',
    //             'key' => 'field_51760a56678b4',
    //             'label' => 'Product Price',
    //             'name' => 'product_price',
    //             'type' => 'number',
    //             'instructions' => 'Enter the price of the product in cents(USD) e.g. "34900"',
    //             'required' => 1,
    //         ),
    //         // array (
    //         //     'layout' => 'vertical',
    //         //     'choices' => array (
    //         //         'black' => 'Black',
    //         //         'white' => 'White',
    //         //         'taupe' => 'Taupe',
    //         //     ),
    //         //     'default_value' => '',
    //         //     'key' => 'field_517874920f218',
    //         //     'label' => 'Color Options',
    //         //     'name' => 'product_color_options',
    //         //     'type' => 'checkbox',
    //         //     'instructions' => 'If there are color options available for the product, check all that apply below. Otherwise, leave checkboxes blank.',
    //         // ),
    //         array (
    //             'default_value' => '',
    //             'formatting' => 'html',
    //             'key' => 'field_51da38849cb71',
    //             'label' => 'Product Subtitle',
    //             'name' => 'product_subtitle',
    //             'type' => 'text',
    //         ),
    //         array (
    //             'key' => 'field_52ad1f2b67e5a',
    //             'label' => 'Product "Shop" Image',
    //             'name' => 'product_shop_image',
    //             'type' => 'image',
    //             'save_format' => 'url',
    //             'preview_size' => 'thumbnail',
    //             'library' => 'all',
    //         ),
    //         array (
    //             'default_value' => '',
    //             'key' => 'field_5240e5123eb58',
    //             'label' => 'Shipping Weight',
    //             'name' => 'shipping_weight',
    //             'type' => 'number',
    //             'instructions' => 'Input the weight of the parcel in OZ.',
    //             'required' => 1,
    //         ),
    //         array (
    //             'default_value' => '',
    //             'key' => 'field_5240e59863d1c',
    //             'label' => 'Shipping Length',
    //             'name' => 'shipping_length',
    //             'type' => 'number',
    //             'instructions' => 'Input the length of the parcel in IN.',
    //             'required' => 1,
    //         ),
    //         array (
    //             'default_value' => '',
    //             'key' => 'field_5240e5cc63d1d',
    //             'label' => 'Shipping Width',
    //             'name' => 'shipping_width',
    //             'type' => 'number',
    //             'instructions' => 'Input the width of the parcel in IN.',
    //             'required' => 1,
    //         ),
    //         array (
    //             'default_value' => '',
    //             'key' => 'field_5240e5da63d1e',
    //             'label' => 'Shipping Height',
    //             'name' => 'shipping_height',
    //             'type' => 'number',
    //             'instructions' => 'Input the height of the parcel in IN.',
    //             'required' => 1,
    //         ),
    //         array (
    //             'default_value' => 0,
    //             'message' => 'Product sold out.',
    //             'key' => 'field_52085b419ca0d',
    //             'label' => 'Product Sold Out',
    //             'name' => 'product_sold_out',
    //             'type' => 'true_false',
    //             'instructions' => 'Did you sell out of this product?',
    //         ),
    //         array (
    //             'default_value' => 0,
    //             'message' => 'Enable Throw In Shipping.',
    //             'key' => 'field_52c608a22b940',
    //             'label' => 'Throw In Shipping',
    //             'name' => 'throw_in_shipping',
    //             'type' => 'true_false',
    //             'instructions' => 'Is this product small enough to fit in the shipment parcel of a larger order?',
    //         ),
    //     ),
    //     'location' => array (
    //         array (
    //             array (
    //                 'param' => 'post_type',
    //                 'operator' => '==',
    //                 'value' => 'products',
    //                 'order_no' => 0,
    //                 'group_no' => 0,
    //             ),
    //         ),
    //     ),
    //     'options' => array (
    //         'position' => 'normal',
    //         'layout' => 'default',
    //         'hide_on_screen' => array (
    //         ),
    //     ),
    //     'menu_order' => 0,
    // ));
    
    // Look Books
    // register_field_group(array (
    //     'id' => 'acf_lookbooks',
    //     'title' => 'Lookbooks',
    //     'fields' => array (
    //         array (
    //             'key' => 'field_52c0f694e4e3c',
    //             'label' => 'Look Book',
    //             'name' => 'look_book',
    //             'type' => 'repeater',
    //             'sub_fields' => array (
    //                 array (
    //                     'key' => 'field_52c0f764e4e3d',
    //                     'label' => 'Look Book Page',
    //                     'name' => 'look_book_page',
    //                     'type' => 'image',
    //                     'column_width' => '',
    //                     'save_format' => 'url',
    //                     'preview_size' => 'thumbnail',
    //                     'library' => 'all',
    //                 ),
    //             ),
    //             'row_min' => 1,
    //             'row_limit' => 99,
    //             'layout' => 'row',
    //             'button_label' => 'Add Look Book Page',
    //         ),
    //     ),
    //     'location' => array (
    //         array (
    //             array (
    //                 'param' => 'post_type',
    //                 'operator' => '==',
    //                 'value' => 'look_books',
    //                 'order_no' => 0,
    //                 'group_no' => 0,
    //             ),
    //         ),
    //     ),
    //     'options' => array (
    //         'position' => 'normal',
    //         'layout' => 'no_box',
    //         'hide_on_screen' => array (
    //         ),
    //     ),
    //     'menu_order' => 0,
    // ));
// }

/**
 *  Helper Functions
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
 *  Globals & Constants
 */
$stripe_options = get_option('stripe_settings');
$easypost_options = get_option('easypost_settings');

/**
 * Properly add new script files using this function.
 * http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 */
function diamond_scripts() {
    $protocol = 'http:'; // discover the correct protocol to use
    if ( !empty($_SERVER['HTTPS']) ) $protocol = 'https:';
	// Enqueue Styles
	wp_enqueue_style( 'diamond-style', get_stylesheet_directory_uri().'/css/diamond-style.css' );
    wp_enqueue_style( 'google-fonts-oswald', '//fonts.googleapis.com/css?family=Oswald:300,400', array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-raleway', '//fonts.googleapis.com/css?family=Raleway:300', array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600', array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-josefin-sans', '//fonts.googleapis.com/css?family=Josefin+Sans:300,400', array(), false, 'all' );
    wp_enqueue_style( 'google-fonts-droid-sans', '//fonts.googleapis.com/css?family=Droid+Sans', array(), false, 'all' );
    wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css', array(), false, 'all' );
    // Activate line below for responsive layout
	// Requires: Child theme style, resets, parent theme base style and bootstrap base style
	// to load prior to responsive. Responsive styles should typically be loaded last.
	// wp_enqueue_style( 'diamond-style-responsive', get_stylesheet_directory_uri().'/css/diamond-style-responsive.css', array('diamond-style','resets','bootstrap-base-styles','bootstrap-parent-style'));
    
    /*
     * Set proper API keys based on Stripe Settings in wordpress
     */
    global $stripe_options;
    if ( isset($stripe_options['test_mode']) && $stripe_options['test_mode'] ) {
        $publishable = $stripe_options['test_publishable_key']; // Use Test API Key for Stripe Processing
    } else {
        $publishable = $stripe_options['live_publishable_key']; // Use Test API Key for Stripe Processing
    }
    // Enqueue Scripts
    wp_enqueue_script( 'bootstrap-transition-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-transition.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-modal-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-modal.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-tooltip-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-tooltip.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-popover-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-popover.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-tab-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-tab.js', array(), false, true );
    wp_enqueue_script( 'bootstrap-carousel-script', get_template_directory_uri().'/inc/bootstrap/js/bootstrap-carousel.js', array(), false, true );
    wp_enqueue_script( 'jquery-validate', get_stylesheet_directory_uri().'/js/jquery.validate.js', array('jquery') );
    wp_enqueue_script( 'jquery-payment', get_stylesheet_directory_uri().'/js/jquery.payment.js', array('jquery') );

    //wp_enqueue_script( 'json2'); // Is this necessary?
    //wp_enqueue_script( 'jquery'); // Is this necessary?
    
    // Stripe
    // https://stripe.com/
    wp_enqueue_script('stripe-processing', get_stylesheet_directory_uri().'/lib/StripeScripts/stripe-processing.js', array('jquery') );
    wp_localize_script('stripe-processing', 'stripe_vars', array(
            'publishable_key' => $publishable,
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

  // Ajax Post Fetcher
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
 *  PayPal Integration
 */
//require_once( get_stylesheet_directory() . '/lib/PayPal/sample/sdk_config.ini' );

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