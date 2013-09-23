<?php
	global $easypost_options;
	require_once( get_stylesheet_directory() . '/lib/easypost.php' );

	if ( isset($easypost_options['test_mode']) && $easypost_options['test_mode'] ) {
		\EasyPost\EasyPost::setApiKey( $easypost_options['test_secret_key'] );
	} else {
		\EasyPost\EasyPost::setApiKey( $easypost_options['live_secret_key'] );
	}

	/**
	 *	Easy Post: Purchase Label
	 *	NOTE: Loop should allow us to ship multiple products.
	 */

	// A. Confirm that a user has purchased a physical good.

	try {
	  // B. Create a new users address
	  $to_address = \EasyPost\Address::create( array(
	    "name"    => "Dirk Diggler",
	    "street1" => "388 Townsend St",
	    "street2" => "Apt 20",
	    "city"    => "San Francisco",
	    "state"   => "CA",
	    "zip"     => "94107",
	    "phone"   => "415-456-7890"
	  ));
	  // C. Retrieve the "from address" from settings
		$from_address = \EasyPost\Address::create( array(
	    "company" => $easypost_options['company_name'],
	    "street1" => $easypost_options['street_one'],
	    "city"    => $easypost_options['city'],
	    "state"   => $easypost_options['state'],
	    "zip"     => $easypost_options['zip_code'],
		));
		// D. Retrieve the package size from the product being shipped.
		$parcel = \EasyPost\Parcel::create( array(
	    "predefined_package" => "LargeFlatRateBox",
	    "weight" => 76.9
		));
		$shipment = \EasyPost\Shipment::create( array(
	    "to_address"   => $to_address,
	    "from_address" => $from_address,
	    "parcel"       => $parcel
		));

		$shipment->buy($shipment->lowest_rate());

		echo $shipment->postage_label->label_url;

	} catch (Exception $e) {
	  echo "Status: " . $e->getHttpStatus() . ":\n";
	  echo $e->getMessage();
	  if (!empty($e->param)) {
	      echo "\nInvalid param: {$e->param}";
	  }
	  exit();
	}
?>