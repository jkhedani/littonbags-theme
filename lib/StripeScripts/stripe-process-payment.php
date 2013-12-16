<?php

/**
 *	Easy Post: Generate Purchase Label after confirming customer has purchased physical good.
 */
function purchase_shipping_label( $orderNumber ) {

	// A. Establish EasyPost API keys & load library
	global $easypost_options;
	require_once( get_stylesheet_directory() . '/lib/easypost.php' );
	if ( isset($easypost_options['test_mode']) && $easypost_options['test_mode'] ) {
		\EasyPost\EasyPost::setApiKey( $easypost_options['test_secret_key'] );
	} else {
		\EasyPost\EasyPost::setApiKey( $easypost_options['live_secret_key'] );
	}

	// B. Retrieve Cart Information 
	$desc = $_POST['description']; // retrieve product description
	$desiredProducts = explode('|', $desc); // Separates multiple products

	try {
		// C1. Retrieve this customer's mailing address...
		$to_address = \EasyPost\Address::create( array(
		  "name"    => strip_tags( trim( $_POST['customer-name'] ) ),
		  "street1" => strip_tags( trim( $_POST['shipping-address-line1'] ) ),
		  "street2" => strip_tags( trim( $_POST['shipping-address-line2'] ) ),
		  "city"    => strip_tags( trim( $_POST['shipping-address-city'] ) ),
		  "state"   => strip_tags( trim( $_POST['shipping-address-state'] ) ),
		  "zip"     => strip_tags( trim( $_POST['shipping-address-zip'] ) ),
		));

		// C2. Retrieve poster's address ( Stored in settings )
		$from_address = \EasyPost\Address::create( array(
	    "company" => $easypost_options['company_name'],
	    "street1" => $easypost_options['street_one'],
	    "city"    => $easypost_options['city'],
	    "state"   => $easypost_options['state'],
	    "zip"     => $easypost_options['zip_code'],
		));

		// C3. Create a separate parcel for each product
		$parcels = array();
		foreach ( $desiredProducts as $desiredProduct ) {
			$desiredProductValues = explode(',',$desiredProduct);
			foreach ($desiredProductValues as $key => $value) {
				// Returns PostID
				if ( $key == 0 ) :
					$parcelLength = get_post_meta( $value, 'shipping_length', true );
					$parcelWidth = get_post_meta( $value, 'shipping_width', true );
					$parcelHeight = get_post_meta( $value, 'shipping_height', true );
					$parcelWeight = get_post_meta( $value, 'shipping_weight', true );

					$parcels[] = \EasyPost\Parcel::create( array(
				    "length" => $parcelLength,
					  "width"	 => $parcelWidth,
					  "height" => $parcelHeight,
					  "weight" => $parcelWeight
					));
					
				endif;
			} // end foreach
		} // end foreach

		// C4. Create special shipment for each parcel
		$shipmentLabels = array();
		foreach ($parcels as $parcel) {
			$shipment = \EasyPost\Shipment::create(
		    array(
	        'to_address'   => $to_address,
	        'from_address' => $from_address,
	        'parcel'       => $parcel
		    )
			);
			error_log(print_r($shipment->rates,true));
			$shipmentLabels[] = $shipment->buy($shipment->lowest_rate());
		}

		/**
		 *	Send HTML Emails
		 *	Send ADMIN email to notify that a shipment should be processed.
		 *	Send USER email to send them tracking information.
		 *	Function formatting: http://codex.wordpress.org/Function_Reference/wp_mail
		 */
		foreach ($shipmentLabels as $shipmentLabel) {
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );

			// ADMIN Email
			$htmlMessage = '
				<p><img src="'.get_stylesheet_directory_uri().'/images/logo.png" alt="Litton Fine Camera Bags" /></p>
				<p>Order Number: #'.$orderNumber.'</p>
				<p>New product(s) await to be shipped: '.$shipmentLabel->postage_label->label_url.'</p>
				<p>Product details: '.$desc.'</p><p>Note: It may be beneficial if you verify this purchase at your <a href="https://manage.stripe.com">Stripe Dashboard</a> :)</p>
			';
			wp_mail(  $easypost_options['shipping_confirmation_email'], 'A New Product(s) Requiree Shipping!', $htmlMessage );

			// CUSTOMER Email
			$customerEmail = strip_tags( trim( $_POST['email'] ) );
			$htmlMessage = '
				<p><img src="'.get_stylesheet_directory_uri().'/images/logo.png" alt="Litton Fine Camera Bags" /></p>
				<p>Order Number: #'.$orderNumber.'</p>
				<p>'.$shipmentLabel->rates->carrier.' Tracking Number: #'.$shipmentLabel->tracking_code.'</p>
				<p>Product details: '.$desc.'</p>
			';
			wp_mail( $customerEmail, 'Thanks for shopping at Litton Bags!', $htmlMessage );
			
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
		}

	} catch (Exception $e) {
		// Troubleshooting
	  // echo "Status: " . $e->getHttpStatus() . ":\n";
	  // echo $e->getMessage();
	  // if (!empty($e->param)) {
	  //     echo "\nInvalid param: {$e->param}";
	  // }
	  $easyPostFailStatus  = $e->getHttpStatus();
	  $easyPostFailMessage = $e->getMessage();
	  $redirect = add_query_arg( array( 'shipping' => 'failed', 'reason' => $easyPostFailMessage ), $_POST['redirect'] );
	}
} // .purchase_shipping_label


/**
 *	Stripe: Process Payment
 */
function stripe_process_payment() {
	if ( isset($_POST['action']) && $_POST['action'] == 'stripe' && wp_verify_nonce($_POST['stripe_nonce'], 'stripe-nonce') ) {
 			
		error_log('Stripe Test Start');

 		/**
 		 *	STEP A.
		 * 	"Stripe" Data Setup
		 *	NOTE: http://pippinsplugins.com/stripe-integration-part-7-creating-and-storing-customers/
		 */
		global $stripe_options;
		require_once(get_stylesheet_directory() . '/lib/Stripe.php'); // load the stripe libraries
		$token = $_POST['stripeToken']; // retrieve the token generated by stripe.js
		$desc = $_POST['description']; // retrieve product description
		$orderNumber = generateRandomOrderNumber( 10 ); // generate random order number
		
		/**
		 * STEP B.
	 	 * Calculate total amount server side (based on description format)
	 	 * Description format described below:
	 	 *	 postID,productName,productColor,productQty
	   */
		$subtotal = 0;
		$quantity = 0;
		$desiredProducts = explode('|', $desc); // Separates multiple products
		foreach ( $desiredProducts as $desiredProduct ) {
			$desiredProductValues = explode(',',$desiredProduct);
			foreach ($desiredProductValues as $key => $value) {
				// Returns PostID
				if ( $key == 0 ) {
					$postID = $value;
				}
				// Returns Color
				if ( $key  == 2 ) {
					// Use color to cross reference actual price (option based)
					$productColor = $value;
					$productOptions = get_field( 'product_options', $postID );
					$standardPrice = get_field( 'product_price', $postID );
					$optionPrice = "";
					// Iterate through options to find the current options selected (looking for option based on color)
					foreach ( $productOptions as $productOption ) {
						if ( $productOption['product_color_name'] == $productColor ) {
							$optionPrice = $productOption['product_option_price'];
						}
					}
					// If cost of the option differs from the product price, set the product cost to the option amount
					if ( ( $optionPrice != $standardPrice ) &&  ( $optionPrice != 0 ) ) {
						$actualPrice = $optionPrice;
					} else {
						$actualPrice = $standardPrice;
					}
					$productPrice = $actualPrice;
					//$productPrice = get_field('product_price', $value); // Individual product price
					//$productPrice = get_post_meta( $value, 'product_price', true );

				}
				// Returns Quantity
				if ( $key == 3 ) {
					$quantity = $value;
				}

				// Ensure we have a product price to add. (loop may run multiple times on null values)
				if ( isset($productPrice) ) {
					$temp = $productPrice * $quantity; // For each product, calculate total cost before tax	
				}
			
			}
			$subtotal += $temp; // Add each product total to the subtotal
		}
		$currenttaxrate = $stripe_options['tax_rate'];
		$tax = round($subtotal * $currenttaxrate); // Round tax to an integer (rounds up after .5)
		$grandtotal = $subtotal + $tax;

		// set the total amount in cents
		$amount = $grandtotal;

 		/**
 		 *  Step C.
 		 * 	Stripe Processing: Process payment for users who submit them
 		 */
		// check if we are using test mode
		if ( isset( $stripe_options['test_mode']) && $stripe_options['test_mode'] ) {
			$secret_key = $stripe_options['test_secret_key'];
		} else {
			$secret_key = $stripe_options['live_secret_key'];
		}
		// set proper api key
 		Stripe::setApiKey($secret_key);

 		// Attempt to charge the user.
		try {

			// STEP ONE: Assume all users are guests and therefore have no customer_ids.
			if ( is_user_logged_in() ):
				// Technically, do nothing as we are not handling logged in users.
				$customer_id = false;
				$customer_email = strip_tags( trim( $_POST['email'] ) );
			else:
				$customer_id = false;
				$customer_email = strip_tags( trim( $_POST['email'] ) );
			endif;

			// STEP TWO: Create a new customer for all transactions (do we need to create a customer for all our transactions?)
			if( !$customer_id ) {
				$customer = Stripe_Customer::create(array(
					'card' => $token,
					'email' => $customer_email,
				));
				$customer_id = $customer->id;
			}

			// STEP THREE: Append the order number
			$desc = $desc . ' #' . $orderNumber; // Sends order number to Stripe Dashboard for easy searching

			// STEP FOUR: Charge the new customers
			if( $customer_id ) {
				$charge = Stripe_Charge::create(array(
					'amount' => $amount, // amount in cents
					'currency' => 'usd',
					'customer' => $customer_id,
					'description' => $desc
				));
			}

			// If charge is successful, purchase label from EasyPost.
			purchase_shipping_label( $orderNumber );

			// redirect on successful payment
			$redirect = add_query_arg('payment', 'paid', $_POST['redirect']);

 		/**
 		 * 	Stripe: Handle Card Errors
 		 * 	NOTE: Addresses seeme to get cashed even on failure :: https://support.stripe.com/questions/cvc-or-avs-failed-but-payment-succeeded
 		 * 	Important: https://support.stripe.com/questions/what-are-street-and-zip-checks-address-verification-or-avs-and-how-should-i-use-them
 		 */
		} catch( Stripe_CardError $e ) {
			// Since it's a decline, Stripe_CardError will be caught
  		$body = $e->getJsonBody();
  		$err  = $body['error'];
			$stripeErrorCode = $err[ 'code' ];
			$redirect = add_query_arg( array( 'payment' => 'card_error', 'reason' => $stripeErrorCode), $_POST['redirect']);
		} catch (Stripe_InvalidRequestError $e) {
			error_log('request');
			$body = $e->getJsonBody();
  		$err  = $body['error'];
			print('Param is:' . $err['param'] . "\n");
		  print('Message is:' . $err['message'] . "\n");
		  // Invalid parameters were supplied to Stripe's API
		} catch (Stripe_AuthenticationError $e) {
			error_log('auth');
		  // Authentication with Stripe's API failed
		  // (maybe you changed API keys recently)
		} catch (Stripe_ApiConnectionError $e) {
			error_log('api');
			// "Cannot validate payment data with Stripe. Are you still connected to the internet?"
		  // Network communication with Stripe failed
		} catch (Stripe_Error $e) {
			error_log('genero');
		  // Display a very generic error to the user, and maybe send
		  // yourself an email
		} catch (Exception $e) {
			// redirect on failed payment
			$body = $e->getJsonBody();
  		$err  = $body['error'];
			$stripeErrorCode = $err[ 'code' ];
			$redirect = add_query_arg( array( 'payment' => 'failed', 'reason' => $stripeErrorCode), $_POST['redirect']);
		}
 		
 		if ( isset($redirect) ) {
 			// redirect back to our previous page with the added query variable
			wp_redirect($redirect); exit;	
 		}
		

	} // Nonce check
} // end function
add_action('init', 'stripe_process_payment');
?>