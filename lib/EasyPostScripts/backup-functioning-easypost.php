/**
			 *	Easy Post: Generate Purchase Label after confirming customer has purchased physical good.
			 * 	NOTE: This should be ok to start here. May need more fringe case testing.
			 */

			// A. Confirm that a user has purchased a physical good.
			
			// B. Establish EasyPost API keys & load library
			global $easypost_options;
			require_once( get_stylesheet_directory() . '/lib/easypost.php' );
			if ( isset($easypost_options['test_mode']) && $easypost_options['test_mode'] ) {
				\EasyPost\EasyPost::setApiKey( $easypost_options['test_secret_key'] );
			} else {
				\EasyPost\EasyPost::setApiKey( $easypost_options['live_secret_key'] );
			}

			try {
				// C1. Retrieve this customer's mailing address...
				$to_address = array(
				  "name"    => "Jon Calhoun",
				  "street1" => "388 Townsend St",
				  "street2" => "Apt 20",
				  "city"    => "San Francisco",
				  "state"   => "CA",
				  "zip"     => "94107-8273",
				  "phone"   => "415-456-7890"
				);
				
				// C2. Retrieve poster's address ( Stored in settings )
				$from_address = \EasyPost\Address::create( array(
			    "company" => $easypost_options['company_name'],
			    "street1" => $easypost_options['street_one'],
			    "city"    => $easypost_options['city'],
			    "state"   => $easypost_options['state'],
			    "zip"     => $easypost_options['zip_code'],
				));

				// C3. Create a separate parcel for each product
				// $parcels = array();
				// foreach ($desiredProducts as $desiredProduct) {
				// 	$desiredProductValues = explode(',',$desiredProduct);
				// 	foreach ($desiredProductValues as $key => $value) {
				// 		// Returns PostID
				// 		if ( $key == 0 ) :
				// 			$parcelLength = get_post_meta( $value, 'shipping_length', true );
				// 			$parcelWidth = get_post_meta( $value, 'shipping_width', true );
				// 			$parcelHeight = get_post_meta( $value, 'shipping_height', true );
				// 			$parcelWeight = get_post_meta( $value, 'shipping_weight', true );

				// 			$parcels[] = \EasyPost\Parcel::create( array(
				// 		    "length" => $parcelLength,
				// 			  "width" => $parcelWidth,
				// 			  "height" => $parcelHeight,
				// 			  "weight" => $parcelWeight
				// 			));
							
				// 		endif;
				// 	} // end foreach
				// } // end foreach

				$parcel = \EasyPost\Parcel::create(
				    array(
			        "length" => 18,
						  "width" => 14,
						  "height" => 8,
			        "weight" => 58
				    )
				);
				$shipment = \EasyPost\Shipment::create(
			    array(
			    	'carrier'			 => 'UPS',
		        'to_address'   => $to_address,
		        'from_address' => $from_address,
		        'parcel'       => $parcel
			    )
				);

				$shipment->buy($shipment->lowest_rate());
				error_log( $shipment->postage_label->label_url );

			} catch (Exception $e) {
			  echo "Status: " . $e->getHttpStatus() . ":\n";
			  echo $e->getMessage();
			  if (!empty($e->param)) {
			      echo "\nInvalid param: {$e->param}";
			  }
			  exit();
			}