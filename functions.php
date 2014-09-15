<?php

/**
 *  Admin-facing Scripts and Functions.
 *
 *  @author Justin Hedani
 *  @since 1.2.0
 */
function admin_scripts() {
    wp_enqueue_style( 'admin-styles', get_stylesheet_directory_uri() . '/css/admin-styles.css' );
    wp_enqueue_script( 'chartjs', get_stylesheet_directory_uri() . '/js/Chart.min.js' );
    wp_enqueue_script( 'admin-scripts', get_stylesheet_directory_uri() . '/js/admin-scripts.js' );
}
add_action('admin_enqueue_scripts', 'admin_scripts');
add_action('login_enqueue_scripts', 'admin_scripts');

/**
 * Move <html> down to compensate for toolbar
 * Note: The styling for this should be tweaked for each site.
 * @since 1.2.0
 */
function clear_admin_toolbar() {
  if ( ! is_admin() && current_user_can('manage_options') ) {
    echo "<style type='text/css'>html{margin-top: 32px;} header#navbar{margin-top: 32px !important;}</style>";
  }
}
add_action( 'wp_head', 'clear_admin_toolbar' );

/**
 *	Return an array of the current user's role.
 *	@since 1.2.0
 *	@return array All current user's roles
 */
if ( ! function_exists('get_current_user_role') ) :
	function get_current_user_role() {
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	};
endif;

/**
 * 	Add special body classes to tell client if user is admin or not.
 *	@since 1.2.0
 */
function admin_class_names($classes) {
	// If user is on the 'admin' side and is not an admin
	if( is_admin() && !current_user_can('manage_options') ) {
		// add 'class-name' to the $classes array
		$classes .= 'not-admin';
		// return the $classes array
		return $classes;
	} else {
		return $classes;
	}
}
// add_filter('admin_body_class','admin_class_names');

/**
 *	Tweak the toolbar.
 *	@link http://codex.wordpress.org/Class_Reference/WP_Admin_Bar
 *  @since 1.2.0
 */
function toolbar_tweaks() {
	global $wp_admin_bar;

	// Remove these menu items (for now)
	$wp_admin_bar->remove_menu( 'search' );
	$wp_admin_bar->remove_menu( 'dashboard' );
	$wp_admin_bar->remove_menu( 'site-name' ); // Re-creating on our own for more control
	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );

	// Hide "My Sites" from users associated with only one course
	$current_user = wp_get_current_user();
	if ( count( get_blogs_of_user( $current_user->ID ) ) == 1 ) {
		$wp_admin_bar->remove_menu( 'my-sites' );
	}
}
add_action( 'wp_before_admin_bar_render', 'toolbar_tweaks' );

/**
 * Create useful toolbar menus.
 * @since 1.2.0
 */
function add_useful_toolbar_menu() {
	global $wp_admin_bar;
	if ( current_user_can('edit_posts') ) {

    // # Set location to either front-facing home or the admin dashboard
		if ( !current_user_can('manage_options') ) {
			$location = get_home_url();
		} else {
			if ( is_admin() ) {
				$location = get_home_url();
			} else {
				$location = get_admin_url();
			}
		}

		// # Litton Bags (Dashboard/Home switch link)
		$wp_admin_bar->add_menu( array(
			'id' => 'back-to-home',
			'title' => get_bloginfo('name'),
			'meta' => array(),
			'href' => $location,
		));

		// # View All
		$wp_admin_bar->add_menu( array(
			'id' => 'view-all',
			'title' => 'View All',
			'meta' => array(),
			'href' => $location,
		));

    // # Create a dropdown listing all post types under View All
		$postTypes = get_post_types( array(), 'object' );
		foreach ($postTypes as $postType) {
			if ( ($postType->name != 'attachment') || ($postType->name != 'revision') || ($postType->name != 'nav_menu_item') ) :
  			$wp_admin_bar->add_menu( array(
  				'parent' => 'view-all',
  				'id' => 'site-name-'.$postType->label,
  				'meta' => array(),
  				'title' => $postType->label,
  				'href' => get_admin_url() . 'edit.php?post_type="' .$postType->name. '"',
  			));
			endif;
		}

    // # Shop Settings Link
    $wp_admin_bar->add_menu( array(
      "id" => "shop-settings",
      "title" => "Shop Settings",
      "meta" => array(),
      "href" => get_admin_url() . "admin.php?page=acf-options",
    ));

		// Modify "Howdy in Menu Bar"
		$user_id      = get_current_user_id();
    $current_user = wp_get_current_user();
    $my_url       = get_home_url();
    if ( ! $user_id ) return;
    $avatar = get_avatar( $user_id, 16 );
    $howdy  = sprintf( __('Aloha e %1$s'), $current_user->display_name );
    $class  = empty( $avatar ) ? '' : 'with-avatar';
    $wp_admin_bar->add_menu( array(
        'id'        => 'my-account',
        'parent'    => 'top-secondary',
        'title'     => $howdy . $avatar,
        'href'      => $my_url,
        'meta'      => array(
            'class'     => $class,
            'title'     => __('My Account'),
        ),
    ) );
	}
}
add_action( 'admin_bar_menu', 'add_useful_toolbar_menu', 25 );


/**
 * Create Stock Overview
 * @since 1.2.0
 */
function dashboard_widget_stock_overview() {
  // Retrieve a list of all post called "products"
  global $post;
  $widget_contents = "<p>The current status of your product inventory.<p>";
  $widget_contents .= '<canvas id="stock-overview" width="400" height="200"></canvas>';
  $products = new WP_Query(array(
    'post_type' => 'products',
    'post_status' => 'publish',
    'post_per_page' => '-1',
    'post_per_archive_page' => '-1'
  ));
  $widget_contents .= '<ul class="products">';
  while ( $products->have_posts() ) : $products->the_post();
    // List the name of each option (maybe with skus, titles and the little image)
    $widget_contents .= '<li class="product">';
    $widget_contents .= "<h3>" . get_the_title() . "</h3>";
    // Product Options
    $widget_contents .= '<ul class="product-options">';
    if ( have_rows('product_skus', $post->ID ) ) :
      while ( have_rows('product_skus', $post->ID ) ) : the_row();
        $widget_contents .= '<li class="product-option">';
        $widget_contents .= "<h4><span class='sku'>" . get_sub_field('sku') . "</span><span class='sku-quantity'>" . get_sub_field('sku_quantity') . "</span></h4>";
        $widget_contents .= "</li>";
      endwhile;
    endif;
    $widget_contents .= "</ul>";
  endwhile;
  wp_reset_postdata();
  $widget_contents .= "</li>";
  $widget_contents .= "</ul>";

  // Show me the contents!
  echo $widget_contents;
  // <input type="submit" name="save" id="save-post" class="button button-primary" value="Save Draft">
}
function dashboard_widget_stripe_overview() {
  // See if we can
  // Link to stripe
}
function add_dashboard_widgets() {
  // Core
  wp_add_dashboard_widget( "stock-overview", "Stock Overview", "dashboard_widget_stock_overview" );
  // Side (http://codex.wordpress.org/Function_Reference/wp_add_dashboard_widget)
  add_meta_box( "stripe-overview", "Stripe Overview", "dashboard_widget_stripe_overview", "dashboard", "side", "high" );
}
function remove_dashboard_widgets() {
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );
  remove_meta_box( 'dashboard_activity', 'dashboard', 'core' );
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'add_dashboard_widgets' );
add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );




/**
 *  Client-facing scripts and functions
 *
 *  @author Justin Hedani
 *  @since 1.2.0
 */
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
  wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css', array(), false, 'all' );

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
