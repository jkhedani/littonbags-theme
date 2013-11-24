
#		Litton Bags Child Theme
#		Theme Based on: Bedrock (https://jkhedani@bitbucket.org/jkhedani/wordpress-bedrock-theme.git)
#		Written by: Justin Hedani

> " Just get a job? Why don’t I strap on my job helmet and squeeze down into a job cannon and fire off into job land, where jobs grow on little jobbies?!" -Charlie


Handling Charges
================

It appears that handling invalid addresses can be tricky as read here:
https://support.stripe.com/questions/cvc-or-avs-failed-but-payment-succeeded
https://support.stripe.com/questions/what-are-street-and-zip-checks-address-verification-or-avs-and-how-should-i-use-them

Everytime a purchase is made, it would be advisable to check to see if their address and zip are valid. The stripe dashboard
should tell you this. If either the street address or CVC is not valid, proceed by doing the following:

•		Immediately refund the charge to the customer. This will prevent cusomters from claiming fraudulent deductions to their account.
•		Politely inform the customer that their purchase has been refunded and that information related to processing the payment was not valid.
		NEVER, EVER ,EVER SEND CUSTOMER INFORMATION OR DETAILED REASONS WHY A PURCHASE HAS BEEN REFUNDED OVER EMAIL OR PHONE!
•		You may also encourage them to try their purchase again and making sure billing address, zipcode and CVC are all inputed correctly.


Current To Do (10092013)
========================

+		Complete Error Checking
+ 	PayPal Checkout
+		Create "Refund" Email Hook
+		Ensure address checks for both Stripe and EasyPost are working properly.
+		Spend one hour tweaking typography, colors, shopping cart and checkout layout.
+		Style results modal for litton bags.
+		Complete User Documentation