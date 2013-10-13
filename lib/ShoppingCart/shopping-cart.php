<?php

function refresh_shopping_cart() {

	do_action('init');
	global $wpdb, $post, $stripe_options;
	
	// Nonce check
	$nonce = $_REQUEST['nonce'];
	if (!wp_verify_nonce($nonce, 'shopping_cart_scripts_nonce')) die(__('Busted.'));
	
	// http://www.php.net/manual/en/function.money-format.php
	setlocale(LC_MONETARY, 'en_US');

	// Grab all post IDs that should be in cart
	if ( isset($_REQUEST['products']) ) {
		$products = $_REQUEST['products'];
	}

	// Set subtotal of all product costs combined
	$grandSubtotal = 0;

	/*
	 * Let's build the Shopping Cart!
	 */
	$html = "";
	$success = false;
	$productDescription = ""; // Build annotated description to pass to Stripe pipe(|) separated

	if ( isset($products) ) {
		foreach ( $products as $product ) {
			$itemID = ""; // Grab the product ID for use outside this loop
			$itemQty = ""; // Grab the product Qty for use outside this loop
			$html .= '<div class="shopping-cart-product" data-jStorage-key="'.$product['key'].'">';
			foreach ($product as $key => $value) { // For each individual product
				//Get Product Name/Post Data
				if ($key == 'postID') {
					$productsInCart = new WP_Query(array(
						'p' => $value,
						'post_type' => 'products',
					));	
					while($productsInCart->have_posts()) : $productsInCart->the_post();
						$currentPostID = $post->ID;
						$itemID = $currentPostID;
						$html .= '<span class="product-title">'.get_the_title().'</span><span class="pipe">|</span>';
						$productDescription = $productDescription . $currentPostID . ','; // Add ID to product description
						$productDescription = $productDescription . get_the_title() . ','; // Add Title to product description
					endwhile;
					wp_reset_postdata();
				}
				// Get Product Color
				if($key == 'color') {
					$html .= '<span class="product-color" data-product-color="'.$value.'"><span class="product-meta-title">Color: </span>';
					if ($value == 'none') {
						$html .= 'n/a';
					} else {
						$html .= $value;
					}
					$html .= '</span><span class="pipe">|</span>';
					$productDescription = $productDescription . $value . ','; // Add Color to product description
				}
				// Get Product Qty
				if($key == 'qty') {
					$html .= '<span class="product-qty" data-product-qty="'.$value.'"><span class="product-meta-title">Quantity: </span>'.$value.'</span>';
					$itemQty = $value;
					$productDescription = $productDescription . $value; // Add Quantity to product description;
				}
			}

			/*
			 * Generate User-facing totals 
			 */

			// Generate Individual Product Cost
			//$productPrice = get_field('product_price', $itemID); // For some reason, get_field() doesn't work here.
			$productPrice = get_post_meta( $itemID, 'product_price', true );
			$productPriceInDollars = money_format('%n', $productPrice/100); // in 'dollars'
	    $html .= '<span class="pipe">|</span><span class="product-cost" data-product-cost="'.$productPrice.'">'.$productPriceInDollars.'</span>';

	    // Generate Individual Product Subtotal
	    $individualProductSubtotal = $productPrice * $itemQty;
	    $productPriceInDollars = money_format('%n', $individualProductSubtotal/100); // in 'dollars'
	    $html .= '<span class="pipe">|</span><span class="product-subtotal"> Subtotal: '.$productPriceInDollars.'</span>';

	    // Generate Entire Shopping Cart Subtotal
	    $grandSubtotal += $individualProductSubtotal;

			/*
			 * Cleanup
			 */

			// Generate a pipe between products; never at the beginning or the end
			if ($product != end($products)) {
				$productDescription = $productDescription . '|';	
			}

			// Create delete cart item key
			$html .= '<a href="javascript:void(0);" class="btn remove">x</a>';
			$html .= '</div>';
		}

		/*
		 * Let's build the Review Totals!
		 */

		// Generate user readable versions of Totals
		// Subtotals
		$subtotal_productPriceInDollars = money_format('%n', $grandSubtotal/100); // in 'dollars'
		
		// Tax
		$currenttaxrate = $stripe_options['tax_rate'];
		$tax = round($grandSubtotal * $currenttaxrate);
		$tax_productPriceInDollars = money_format('%n', $tax/100); // in 'dollars'

		// Grand
		$grandTotal = intval($grandSubtotal + $tax);
		$grand_productPriceInDollars = money_format('%n', $grandTotal/100); // in 'dollars'

		// Display Subtotal, Add Tax/Fees/Whatever & show Grand Total
		$html .= '<div class="checkout-totals">';
		$html .= '<div class="subtotal"><span class="total-title">Subtotal: </span>'.$subtotal_productPriceInDollars.'</div>';
		$html .= '<div class="auxfees"><span class="total-title">Tax ('.$currenttaxrate.'%): </span>'.$tax_productPriceInDollars.'</div>';
		$html .= '<div class="total"><span class="total-title">Total: </span>'.$grand_productPriceInDollars.'</div>';
		$html .= '</div>';

	} // If products are being set	
	/*
	 * Build the response...
	 */
	$success = true;
	$response = json_encode(array(
		'success' => $success,
		'html' => $html,
		'desc' => $productDescription
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