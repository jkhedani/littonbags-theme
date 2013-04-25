<?php

function refresh_shopping_cart() {
	global $wpdb, $post;

	// Nonce check
	$nonce = $_REQUEST['nonce'];
	if (!wp_verify_nonce($nonce, 'shopping_cart_scripts_nonce')) die(__('Busted.'));
	
	// Grab all post IDs that should be in cart
	$products = $_REQUEST['products'];

	$html = "";
	$success = false;

	foreach ($products as $product) {
		foreach ($product as $key => $value) {
			//Get Product Name/Post Data
			if ($key == 'postID') {
				$productsInCart = new WP_Query(array(
					'p' => $value,
					'post_type' => 'products',
				));	
				while($productsInCart->have_posts()) : $productsInCart->the_post();
					$currentPostID = $post->ID;
					$html .= get_the_title();
				endwhile;
				wp_reset_postdata();
			}
			// Get Product Color
			if($key == 'color') {
				if ($value == 'none') {
					// Do nothin.
				} else {
					$html .= 'Color:'.$value;
				}
			}
			// Get Product Qty
			if($key == 'qty') {
				$html .= 'Qty:'.$value;
			}
			$html .= '<br />';
		}
	}

	// Build the response...
	$success = true;
	$response = json_encode(array(
		'success' => $success,
		'html' => $html
	));
	
	// Construct and send the response
	header("content-type: application/json");
	echo $response;
	exit;
}
add_action('wp_ajax_nopriv_refresh_shopping_cart', 'refresh_shopping_cart');
add_action('wp_ajax_refresh_shopping_cart', 'refresh_shopping_cart');

//Run Ajax calls even if user is logged in
if(isset($_REQUEST['action']) && ($_REQUEST['action']=='refresh_shopping_cart')):
	do_action( 'wp_ajax_' . $_REQUEST['action'] );
  do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
endif;

?>