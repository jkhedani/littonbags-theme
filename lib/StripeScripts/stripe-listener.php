<?php

/*
 * Stripe Event Listener
 * http://pippinsplugins.com/stripe-integration-part-6-payment-receipts/
 */
function stripe_event_listener() {
 	// Listen for wps-listener (from Stripe)
	if ( isset( $_GET['lb-listener'] ) && $_GET['lb-listener'] == 'stripe' ) {

		// Basic Stripe Setup
		global $stripe_options;
		require_once( get_stylesheet_directory() . '/lib/Stripe.php' );
		if( isset( $stripe_options['test_mode'] ) && $stripe_options['test_mode'] ) {
			$secret_key = $stripe_options['test_secret_key'];
		} else {
			$secret_key = $stripe_options['live_secret_key'];
		}
		Stripe::setApiKey($secret_key);

		// retrieve the request's body and parse it as JSON
		$body = @file_get_contents('php://input');
		// grab the event information
		$event_json = json_decode($body);
		// this will be used to retrieve the event from Stripe
		$event_id = $event_json->id;
 
 		// We must ensure to only use the event ID from the initial listen.
		if ( isset( $event_json->id ) ) {
			/*
			 * Let's generate some events
			 */
			try {
				// to verify this is a real event, we re-retrieve the event from Stripe 
				$event = Stripe_Event::retrieve($event_id);
				$invoice = $event->data->object;
 
				// Upon Successful Payment
				if ( $event->type == 'charge.succeeded' ) {
					
					/*
					 * Send customer's payment receipt email here.
					 */

					// retrieve the payer's information
					// $customer = Stripe_Customer::retrieve($invoice->customer);
					// $email = $customer->email;
 
					// $amount = $invoice->amount / 100; // amount comes in as amount in cents, so we need to convert to dollars
 
					// $subject = __('Payment Receipt', 'litton_bags');
					// $headers = 'From: "' . html_entity_decode(get_bloginfo('name')) . '" <' . get_bloginfo('admin_email') . '>';
					// //$message = "Hello " . $customer_name . "\n\n";
					// $message = "Hello!\n\n";
					// $message .= "You have successfully made a payment of " . $amount . "\n\n";
					// $message .= "Thank you!";

					// wp_mail($email, $subject, $message, $headers);

				}
 
				// failed payment
				if($event->type == 'charge.failed') {
					
					/*
					 * Send customer's failed payment notice here.
					 */
 
					// retrieve the payer's information
					$customer = Stripe_Customer::retrieve($invoice->customer);
					$email = $customer->email;
 
					$subject = __('Failed Payment', 'pippin_stripe');
					$headers = 'From: "' . html_entity_decode(get_bloginfo('name')) . '" <' . get_bloginfo('admin_email') . '>';
					$message = "Hello " . $customer_name . "\n\n";
					$message .= "We have failed to process your payment of " . $amount . "\n\n";
					$message .= "Please get in touch with support.\n\n";
					$message .= "Thank you.";
 
					wp_mail($email, $subject, $message, $headers);
				}

			} catch (Exception $e) {
				// something failed, perhaps log a notice or email the site admin
			}
		
		} // isset $event_json
	} // wps-listener
}
// add_action('init', 'stripe_event_listener');

?>