<?php

/**
 * Sample bootstrap file.
 *
 * @param  type Request either the client id or client secret
 * @return id or secret
 */

// Include the composer autoloader
// The location of your project's vendor autoloader.
$composerAutoload = dirname(__FILE__) . '/../../../../vendor/autoload.php';
// if (!file_exists($composerAutoload)) {
//     //If the project is used as its own project, it would use rest-api-sdk-php composer autoloader.
//     $composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';
//
//     if (!file_exists($composerAutoload)) {
//         echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
//         exit(1);
//     }
// }
require $composerAutoload;
//require __DIR__ . '/common.php';

function paypal_api_key( $type ) {
  $paypal_api_value = '';
  $api_mode = get_field( 'paypal_api_mode', 'option' );
  if ( $type === "id" ) {
    if ( $api_mode === true ) {
      $paypal_api_client_id = get_field( 'paypal_live_client_id', 'option' );
    } else {
      $paypal_api_client_id = get_field( 'paypal_test_client_id', 'option' );
    }
    $paypal_api_value = $paypal_api_client_id;
  } elseif ( $type === "secret" ) {
    if ( $api_mode === true ) {
      $paypal_api_secret_key = get_field( 'paypal_live_secret_api_key', 'option' );
    } else {
      $paypal_api_secret_key = get_field( 'paypal_test_secret_api_key', 'option' );
    }
    $paypal_api_value = $paypal_api_secret_key;
  }
  return $paypal_api_value;
}

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

error_reporting(E_ALL);
ini_set('display_errors', '1');

// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/webapps/developer/applications/myapps
$clientId     = paypal_api_key("id");
$clientSecret = paypal_api_key("secret");

/** @var \Paypal\Rest\ApiContext $apiContext */
$apiContext = getApiContext($clientId, $clientSecret);
return $apiContext;

exit;
/**
 * Helper method for getting an APIContext for all calls
 *
 * @return PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret)
{

    // ### Api context
    // Use an ApiContext object to authenticate
    // API calls. The clientId and clientSecret for the
    // OAuthTokenCredential class can be retrieved from
    // developer.paypal.com

    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
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
            'log.LogLevel' => 'FINE',
            'validation.level' => 'log'
        )
    );

    /*
    // Register the sdk_config.ini file in current directory
    // as the configuration source.
    if(!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", __DIR__);
    }
    */

    return $apiContext;
}
