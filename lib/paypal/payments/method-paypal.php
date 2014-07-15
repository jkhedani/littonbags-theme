<?php

/**
 *	Global Declarations
 */

// # via bootstrap.php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
// # via payments/method-XXX.php
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

/**
 * Payments
 * Method: via PayPal
 * Allow users to create 
 */
function create_payment_method_paypal() {

	// ### Initiate Wordpress
	// Re-run Wordpress to obtain functionality plus
	// check nonce.
	do_action('init');
	$nonce = $_REQUEST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'paypal_nonce' ) ) {
	    die( __('Busted.') ); // Nonce check
	}
	$success = false;
	$paypalRedirectURL = "";
	$cartdescription = $_REQUEST['cartdescription'];

	// ### Bootstrap PayPal API 
	// Configure our API context
	// Include the composer autoloader if we aren't already set
	require __DIR__ . '/bootstrap.php';
	// if(!file_exists(__DIR__ .'/vendor/autoload.php')) {
	// 	echo "The 'vendor' folder is missing. You must run 'composer update --no-dev' to resolve application dependencies.\nPlease see the README for more information.\n";
	// 	exit(1);
	// }
	// require __DIR__ . '/vendor/autoload.php';
	// require __DIR__ . '/common.php';
	// $apiContext = getApiContext();

	// ### Create Payment using PayPal as payment method
	// This sample code demonstrates how you can process a 
	// PayPal Account based Payment.
	// API used: /v1/payments/payment
	session_start();

	// ### Payer
	// A resource representing a Payer that funds a payment
	// For paypal account payments, set payment method
	// to 'paypal'.
	$payer = new Payer();
	$payer->setPaymentMethod("paypal");

	// ### Construct Itemized Infromation via Wordpress
	// Using the cart description, generate the appropriate
	// cart items for each of the items in the cart
	$itemlistarray = array();
	$cartsubtotal = '';
	$productdescriptions = explode( '|', $cartdescription );
	foreach ( $productdescriptions as $productdescription ) {
		// # Begin outlining details for our new product
		$newproducts = explode( ',', $productdescription );
		$newproductid = $newproducts[0];
		$newproductcolor = $newproducts[1];
		$newproductqty = $newproducts[2];
		$newproducttitle   = get_the_title( $newproductid );
		$newproductoptions = get_field( 'product_options', $newproductid );
		foreach ( $newproductoptions as $newproductoption ) {
			if ( $newproductoption['product_color_name'] == $newproductcolor ) {
				$newproductprice = number_format($newproductoption['product_option_price'] / 100, 2, '.', '');
			}
		}
		$newproductid = new Item(); // Use product id as item variable namespace
		$newproductid->setName( $newproducttitle )
		->setCurrency('USD')
		->setQuantity( $newproductqty )
		->setPrice( $newproductprice );

		// # Build an array of all products to be set in the item list
		$itemlistarray[] = $newproductid;

		// # Build cart subtotal
		$cartsubtotal = $cartsubtotal + $newproductprice;
	}

	// ### Itemized information
	// (Optional) Lets you specify item wise
	// information
	// $item1 = new Item();
	// $item1->setName('Minster')
	// 	->setCurrency('USD')
	// 	->setQuantity(1)
	// 	->setPrice('7.50');
	// $item2 = new Item();
	// $item2->setName('Granola bars')
	// 	->setCurrency('USD')
	// 	->setQuantity(5)
	// 	->setPrice('2.00');

	$itemList = new ItemList();
	//$itemList->setItems( array( $item1, $item2 ) );
	$itemList->setItems( $itemlistarray );

	// ### Additional payment details
	// Use this optional field to set additional
	// payment information such as tax, shipping
	// charges etc.
	$taxrate = get_field( 'tax_rate', 'option' );
	$carttax = round( $cartsubtotal * $taxrate, 2 );
	$details = new Details();
	$details->setShipping('0.00')
		->setTax( $carttax )
		->setSubtotal( $cartsubtotal );

	// ### Amount
	// Lets you specify a payment amount.
	// You can also specify additional details
	// such as shipping, tax.
	$carttotal = $cartsubtotal + $carttax;
	$amount = new Amount();
	$amount->setCurrency("USD")
		->setTotal( $carttotal )
		->setDetails( $details );

	// ### Transaction
	// A transaction defines the contract of a
	// payment - what is the payment for and who
	// is fulfilling it. 
	$transaction = new Transaction();
	$transaction->setAmount($amount)
		->setItemList($itemList)
		->setDescription("Payment description");

	// ### Redirect urls
	// Set the urls that the buyer must be redirected to after 
	// payment approval/ cancellation.
	$baseUrl = get_stylesheet_directory_uri();
	$redirectUrls = new RedirectUrls();
	$redirectUrls->setReturnUrl("$baseUrl/lib/paypal/payments/execute-payment.php?success=true")
		->setCancelUrl("$baseUrl/lib/paypal/payments/execute-payment.php?success=false");

	// ### Payment
	// A Payment Resource; create one using
	// the above types and intent set to 'sale'
	$payment = new Payment();
	$payment->setIntent("sale")
		->setPayer($payer)
		->setRedirectUrls($redirectUrls)
		->setTransactions(array($transaction));

	// ### Create Payment
	// Create a payment by calling the 'create' method
	// passing it a valid apiContext.
	// (See bootstrap.php for more on `ApiContext`)
	// The return object contains the state and the
	// url to which the buyer must be redirected to
	// for payment approval
	try {
		$payment->create($apiContext);
		error_log('success');
	} catch (PayPal\Exception\PPConnectionException $ex) {
		echo "Exception: " . $ex->getMessage() . PHP_EOL;
		var_dump($ex->getData());
		error_log("Exception: " . $ex->getMessage() . PHP_EOL);
		error_log($ex->getData());
		exit(1);
	}

	// ### Get redirect url
	// The API response provides the url that you must redirect
	// the buyer to. Retrieve the url from the $payment->getLinks()
	// method
	foreach($payment->getLinks() as $link) {
		if($link->getRel() == 'approval_url') {
			$redirectUrl = $link->getHref();
			break;
		}
	}

	// ### Redirect buyer to PayPal website
	// Save the payment id so that you can 'complete' the payment
	// once the buyer approves the payment and is redirected
	// back to your website.
	//
	// It is not a great idea to store the payment id
	// in the session. In a real world app, you may want to 
	// store the payment id in a database.
	$_SESSION['paymentId'] = $payment->getId();

	// # NOTE: Instead of redirecting the user via PHP here, we'll
	// do so once the ajax has been successful
	// if(isset($redirectUrl)) {
	// 	header("Location: $redirectUrl");
	// 	exit;
	// }

	$paypalRedirectURL = $redirectUrl;
  $success = true;
  $response = json_encode( array(
      'success' => $success,
      'redirecturl' => $paypalRedirectURL,
  ));

  header( 'content-type: application/json' );
  echo $response;
  exit;

}
add_action( 'wp_ajax_nopriv_create_payment_method_paypal', 'create_payment_method_paypal' );
add_action( 'wp_ajax_create_payment_method_paypal', 'create_payment_method_paypal' );

if ( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'create_payment_method_paypal' )  ) {
    do_action( 'wp_ajax_' . $_REQUEST['action'] );
    do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
}