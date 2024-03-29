#	Litton Bags Child Theme
Child Theme of [Bedrock v1.2](https://github.com/jkhedani/Wordpress-Bedrock-Theme)
> Just get a job? Why don’t I strap on my job helmet and squeeze down into a job
>	cannon and fire off into job land, where jobs grow on little
>	jobbies?! -Charlie

## Release Notes

**1.2 (upcoming)**


**1.1**
- Ensure mobile users can scroll through their shopping cart
- Update all Bootstrap .js scripts
- General SEO improvements
- General Bug fixes

**1.0**

- PayPal integration allows users to checkout via PayPal
- Basic shopping cart allows users to manage multiple products
- Basic integration of responsive design


## Developer Notes

### Updating Live
1. Run `composer update` locally and fix any problem areas.
2. Reference release notes in PM software.

### Handling Charges

It appears that handling invalid addresses can be tricky as read here:
https://support.stripe.com/questions/cvc-or-avs-failed-but-payment-succeeded
https://support.stripe.com/questions/what-are-street-and-zip-checks-address-verification-or-avs-and-how-should-i-use-them

Everytime a purchase is made, it would be advisable to check to see if their
address and zip are valid. The stripe dashboard should tell you this. If either
the street address or CVC is not valid, proceed by doing the following:

•		Immediately refund the charge to the customer. This will prevent customers
		from claiming fraudulent deductions to their account.
•		Politely inform the customer that their purchase has been refunded and that
    information related to processing the payment was not valid.
		NEVER, EVER ,EVER SEND CUSTOMER INFORMATION OR DETAILED REASONS WHY A
		PURCHASE HAS BEEN REFUNDED OVER EMAIL OR PHONE!
•		You may also encourage them to try their purchase again and making sure
		billing address, zipcode and CVC are all inputed correctly.

### Current To Do (10092013)

+		Complete Error Checking
+ 	PayPal Checkout
+		Create "Refund" Email Hook
+		Ensure address checks for both Stripe and EasyPost are working properly.
+		Spend one hour tweaking typography, colors, shopping cart and checkout layout.
+		Style results modal for litton bags.
+		Complete User Documentation
+		Icons needed @2X: footer social icons, primary logo, & shopping cart

### PayPal

## Installation
**Note:** Commands using `sudo` are for my reference (i.e. Vagrant setup).

1.	PayPal's installation instructions are shit. Fuck them.
2.	Open a shell window on your server (local should be your Vagrant machine)
3.	Install Composer:
				cd ~/
				curl -sS https://getcomposer.org/installer | php
4. 	Clone the PayPal API repo (I placed this in my theme to access WP
		functions at runtime ):
				git clone https://github.com/paypal/rest-api-sdk-php PayPal
5.  A. If you want to run the samples:
				cd PayPal/sample
				sudo php ~/composer.phar update --no-dev
6.  B. Write your own app:
				cd PayPal
				sudo mv sample/composer.json .
				sudo mv sample/sdk_config.ini .
				sudo php ~/composer.php update --no-dev

## Resources.

https://developer.paypal.com/webapps/developer/docs/integration/web/web-checkout/
https://www.sandbox.paypal.com/
https://developer.paypal.com/

https://devtools-paypal.com/guide/pay_paypal/php?interactive=ON&env=sandbox

Classic API
https://apps.paypal.com

This appears to be the type of REST API call we want to make:
https://devtools-paypal.com/guide/pay_paypal/php?interactive=ON&env=sandbox
