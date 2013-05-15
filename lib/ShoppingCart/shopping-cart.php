<?php

function refresh_shopping_cart() {
	do_action('init');
	global $wpdb, $post;

	// Nonce check
	$nonce = $_REQUEST['nonce'];
	if (!wp_verify_nonce($nonce, 'shopping_cart_scripts_nonce')) die(__('Busted.'));
	
	// Grab all post IDs that should be in cart
	$products = $_REQUEST['products'];

	/*
	 * Let's build the Shopping Cart!
	 */
	$html = "";
	$success = false;
	$productDescription = ""; // Build annotated description to pass to Stripe pipe(|) separated
	foreach ($products as $product) {
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
				$html .= '<span class="product-color" data-product-color="'.$value.'">';
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
				$html .= '<span class="product-qty" data-product-qty="'.$value.'">'.$value.'</span>';
				$itemQty = $value;
				$productDescription = $productDescription . $value; // Add Quantity to product description;
			}
		}
		// Generate Individual Product Cost
		$productPrice = get_field('product_price', $itemID);
		$productPriceInDollars = $productPrice/100; // in 'dollars'
		$english_notation = number_format($productPriceInDollars,2,'.',''); // in eng notation 'dollars'
    $html .= '<span class="pipe">|</span><span class="product-cost" data-product-cost="'.$productPrice.'">'.$english_notation.'</span>';

    // Generate Individual Product Subtotal
    $individualProductSubtotal = $productPrice * $itemQty;
    $productPriceInDollars = $individualProductSubtotal/100; // in 'dollars'
    $english_notation = number_format($productPriceInDollars,2,'.',''); // in eng notation 'dollars'
    $html .= '<span class="pipe">|</span><span class="product-subtotal"> Subtotal: '.$english_notation.'</span>';

    // Generate Entire Shopping Cart Subtotal
    $grandSubtotal = $grandSubtotal + $individualProductSubtotal;

		// Create delete cart item key
		if ($product != end($products)) {
			$productDescription = $productDescription . '|';	
		}

		$html .= '<a href="javascript:void(0);" class="btn remove">-</a>';
		$html .= '</div>';
	}

	/*
	 * Let's build the Review Totals!
	 */
	// Generate user readable versions of Totals
	// Subtotals
	$subtotal_productPriceInDollars = $grandSubtotal/100; // in 'dollars'
	$subtotal_english_notation = number_format($subtotal_productPriceInDollars,2,'.',''); // in eng notation 'dollars'
	
	// Tax
	$tax = $grandSubtotal * 0.0471;
	$tax_productPriceInDollars = $tax/100; // in 'dollars'
	$tax_english_notation = number_format($tax_productPriceInDollars,2,'.',''); // in eng notation 'dollars'

	// Grand
	$grandTotal = intval($grandSubtotal + $tax);
	$grand_productPriceInDollars = $grandTotal/100; // in 'dollars'
	$grand_english_notation = number_format($grand_productPriceInDollars,2,'.',''); // in eng notation 'dollars'

	// Display Subtotal, Add Tax/Fees/Whatever & show Grand Total
	$html .= '<div class="checkout-totals">';
	$html .= '<div class="subtotal">Subtotal: '.$subtotal_english_notation.'</div>';
	$html .= '<div class="auxfees">Tax (0.0471): '.$tax_english_notation.'</div>';
	$html .= '<div class="total">Total: '.$grand_english_notation.'</div>';
	$html .= '</div>';

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