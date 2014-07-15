<?php

/*
 * Sample bootstrap file.
 */

// # Load Wordpress
// Hacky but should do the trick for now
require_once( $_SERVER['DOCUMENT_ROOT'] . "/wp-load.php" );

// Include the composer autoloader
if(!file_exists(__DIR__ .'/vendor/autoload.php')) {
	echo "The 'vendor' folder is missing. You must run 'composer update --no-dev' to resolve application dependencies.\nPlease see the README for more information.\n";
	exit(1);
}
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/common.php';
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
$apiContext = getApiContext();

/**
 * Helper method for getting an APIContext for all calls
 *
 * @return PayPal\Rest\ApiContext
 */
function getApiContext() {
	
	// ### Api context
	// Use an ApiContext object to authenticate 
	// API calls. The clientId and clientSecret for the 
	// OAuthTokenCredential class can be retrieved from 
	// developer.paypal.com

	// # LIVE Context
	if ( get_field('enable_live_paypal_credentials','option') == true ) {

		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				'ASRpcBCKDEFmfuax8xaXY8VfKSO5QyWM12SsOOpyyksiOqH3xA32nb_OeT7F',
				'EPFRVBC8fiMpm0RvQ8ZcTkoYX0pk37ycnGSpPYWief4qE2XqVzxFM71iDutf'
			)
		);
		// #### SDK configuration
		// Comment this line out and uncomment the PP_CONFIG_PATH
		// 'define' block if you want to use static file 
		// based configuration
		$apiContext->setConfig(
			array(
				'mode' => 'sandbox',
				'http.ConnectionTimeOut' => 30,
				'log.LogEnabled' => true,
				'log.FileName' => '../PayPal.log',
				'log.LogLevel' => 'FINE'
			)
		);

	// # TEST Context
	} elseif ( ! get_field('enable_live_paypal_credentials','option') == true ) {

		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				get_field('paypal_test_client_id','option'),
				get_field('paypal_test_secret','option')
			)
		);
		// #### SDK configuration
		// Comment this line out and uncomment the PP_CONFIG_PATH
		// 'define' block if you want to use static file 
		// based configuration
		$apiContext->setConfig(
			array(
				'mode' => 'sandbox',
				'http.ConnectionTimeOut' => 30,
				'log.LogEnabled' => true,
				'log.FileName' => '../PayPal.log',
				'log.LogLevel' => 'FINE'
			)
		);

	}
	
	return $apiContext;
} // getApiContext();

error_log(print_r($apiContext,true));

?>