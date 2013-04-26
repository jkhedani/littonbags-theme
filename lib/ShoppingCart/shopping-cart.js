jQuery(document).ready(function($){
  
  /*
   * "Shopping Cart": Actions
   * Global Variables: existingProducts, existingProductIDs
   * http://stackoverflow.com/questions/3352020/jquery-the-best-way-to-set-a-global-variable
   */
  
  // Find existing products
  function grab_existing_products_in_cart() {
    window.existingProducts = [];
    window.existingProductIDs = [];
    $.each($.jStorage.index(), function(index,value) { // Grab all existing jStorage objects.
      if(value.indexOf('product') >= 0) { // Select all that are considered products...
        existingProducts.push(value); // Store existing product keys
        var tempValues = $.jStorage.get(value).split(','); // Then convert string into an array
        $.each(tempValues, function(index,value) { // For each product...
          if(index == 0) { // Find index 0 or position 1 in the product value (Post ID)...
            existingProductIDs.push(value); // Save that product ID
          }
        });
      }
    });
  }

  // Refreshes the shopping cart via Ajax on each page load :(
  function refresh_shopping_cart(fadeCallback) {

    grab_existing_products_in_cart();

    // Create a shopping object for each product in the shopping cart
    var jProducts = [];
    $.each(existingProducts, function(index, value){
      var jExsitingProductValues = $.jStorage.get(value).split(','); // Turn values into an array...
      var jPostID  = jExsitingProductValues[0]; // Get the product post ID
      var jColor   = jExsitingProductValues[1]; // Get the product color
      var jQty     = jExsitingProductValues[2]; // Get the product qty
      var jProduct = {}; // Generate a singular product object and store each...
      jProduct['key'] = value; // singluar post id...
      jProduct['postID'] = jPostID; // singluar post id...
      jProduct['color'] = jColor; // singluar post color...
      jProduct['qty'] = jQty; // singluar post qty...
      jProducts.push(jProduct); // In an array of existing products
    });

    $.post(shopping_cart_scripts.ajaxurl, {
      dataType: "jsonp",
      action: 'refresh_shopping_cart',
      nonce: shopping_cart_scripts.nonce,
      products: jProducts,
    }, function(response) {
      if (response.success===true) {
        // Destroy existing popover and recreate
        $('.shoppingcart a.shoppingcartshow').popover('destroy');
        // Destroy create new popover with current shopping cart data
        
        if($.jStorage.index().length) {
          $('.shoppingcart a.shoppingcartshow').popover({
            'html': true,
            'placement': 'bottom',
            'content': response.html+"<a href='#' class='clearcart'>Empty your shopping cart</a><hr /><a href='#checkoutReview' class='btn btn-primary checkout'>Checkout</a>",
          });
        } else {
          $('.shoppingcart a.shoppingcartshow').popover({
            'html': true,
            'placement': 'bottom',
            'content': "Your shopping cart is currently empty.",
          });
        }
        if(fadeCallback == true) {
          // Show popover for for a few seconds, then fade out
          $('.shoppingcart a.shoppingcartshow').popover('toggle');
          $('.popover').delay(4000).fadeOut(800);
        }
        return false;
      } else {
        alert('fail!');
      }
    });
  }
  
  // On page load, show contents of the shopping cart  
  refresh_shopping_cart();

  // Empty Entire Shopping Cart
  // http://stackoverflow.com/questions/13205103/attach-event-handler-to-button-in-twitter-bootstrap-popover
  $(document).on('click', '.popover a.clearcart', function(){
    // Clear entire jStorage
    $.jStorage.flush();
    refresh_shopping_cart(true);
  });

  // Delete Specific Products in Shopping Cart
  $(document).on('click', '.popover .shopping-cart-product a.remove', function(){
    var keyToRemove = $(this).parent().attr('data-jStorage-key');
    $.jStorage.deleteKey(keyToRemove);
    $.jStorage.reInit();
    refresh_shopping_cart(true);
  });

  /*
   * "Shopping Cart": Place valid, new products in cart and refresh view
   * Products are placed when the "Add to Cart" button is clicked.
   */
  $(document).on('click', '#addToCart', function() {

    // Gather all existing products and product IDs
    grab_existing_products_in_cart();

    // Grab new product values
    var jPostID = $(this).data('post-id'); // "Post ID"
    if ($('.product-color-selection')) {
      var jColor = $('.product-color-selection').val(); // "Color"
    } else {
      var jColor = 'none';
    }
    var jQty = $('.product-qty-selection').val(); // "Qty" 
    var length = existingProductIDs.length; // Check how many products exist
    var newProductPosition = length + 1; // Set the new product position for the

    // Check if the product we wish to publish has the same 'postID' as an existing product
    // ATTENTION: MAXIMUM 10 PRODUCTS IN CART!
    var matchingProductPositions = [];
    var i = 0; // Starting index
    var r = 2; // Range from starting index 
    $.each(existingProducts, function(index,value) {
      var existingProductValue = $.jStorage.get(value).split(',');
      $.each(existingProductValue, function(index, value){
        if(value == jPostID) { // If a product contains the same postID, grab its position
          if((i >= 0) && (i <= 2)) {
            matchingProductPositions.push('0');// grab first position
          } else if((i > 2) && (i <= 5)) {
            matchingProductPositions.push('1');// grab second position
          } else if((i > 5) && (i <= 8)) {
            matchingProductPositions.push('2');// grab third position
          } else if((i > 8) && (i <= 11)) {
            matchingProductPositions.push('3');// grab etc. position
          } else if((i > 11) && (i <= 14)) {
            matchingProductPositions.push('4');
          } else if((i > 14) && (i <= 17)) {
            matchingProductPositions.push('5');
          } else if((i > 17) && (i <= 20)) {
            matchingProductPositions.push('6');
          } else if((i > 20) && (i <= 23)) {
            matchingProductPositions.push('7');
          } else if((i > 23) && (i <= 26)) {
            matchingProductPositions.push('8');
          } else if((i > 26) && (i <= 29)) {
            matchingProductPositions.push('9');
          } else if((i > 29) && (i <= 32)) {
            matchingProductPositions.push('10');
          } 
        }
        i++;
      });
    });

    // Define all matching product keys
    var matchingProducts = [];
    $.each(matchingProductPositions, function(index, value){ // Using matching product positions...
      matchingProducts.push(existingProducts[value]); // Grab keys in said positions
    });

    // Iterate through values of all matching products
    var currentColors = [];
    $.each(matchingProducts, function(index, value) { // Grab each matching product...
      var tempValues = $.jStorage.get(value).split(','); // and convert their values into an array.
      $.each(tempValues, function(index, value) { // Grab each value array
        if (index == 1) {  // Find the color value at index 1 (value/position 2)
          currentColors.push(value);
        }
      });
    });

    // Check if color value matches the color value of the product being posted
    if ($.inArray(jColor, currentColors) >= 0) {
     // DO NOT PUBLISH 
    } else {
     // PUBLISH
     $.jStorage.set('product'+newProductPosition, jPostID+','+jColor+','+jQty); // Store the new 'post' in the shopping cart
    }

    // Generate new list of products in cart.
    grab_existing_products_in_cart();

    // Then, refresh the cart.
    refresh_shopping_cart(true);

  }); // end click

  /*
   * Shopping Cart: Checkout
   */

   // After "Checkout" button is clicked
   $(document).on('click', 'a.checkout', function(){
      // Close Shopping cart
      $('.shoppingcart a.shoppingcartshow').popover('toggle');
      // Toggle "checkout" modal one: "Review/Edit Your Cart"
      $('#checkoutReview').modal();

      grab_existing_products_in_cart();

      // Create a shopping object for each product in the shopping cart
      var jProducts = [];
      $.each(existingProducts, function(index, value){
        var jExsitingProductValues = $.jStorage.get(value).split(','); // Turn values into an array...
        var jPostID  = jExsitingProductValues[0]; // Get the product post ID
        var jColor   = jExsitingProductValues[1]; // Get the product color
        var jQty     = jExsitingProductValues[2]; // Get the product qty
        var jProduct = {}; // Generate a singular product object and store each...
        jProduct['key'] = value; // singluar post id...
        jProduct['postID'] = jPostID; // singluar post id...
        jProduct['color'] = jColor; // singluar post color...
        jProduct['qty'] = jQty; // singluar post qty...
        jProducts.push(jProduct); // In an array of existing products
      });
      
      // Show Shopping Cart Items In Window
      $.post(shopping_cart_scripts.ajaxurl, {
        dataType: "jsonp",
        action: 'refresh_shopping_cart',
        nonce: shopping_cart_scripts.nonce,
        products: jProducts,
      }, function(response) {
        if (response.success===true) {
          $('#checkoutReview').find('.modal-body').append().html(response.html);
          if($.jStorage.get('total')) {
            $.jStorage.deleteKey('total');
            $.jStorage.set('total', response.total);
          } else {
            $.jStorage.set('total', response.total);
          }
          $('#checkoutPayment').find('input[name="description"]').attr('value',response.desc);
          return false;
        } else {
          alert('fail!');
        }
      });
    });

   // After "Make Your Payment" is clicked
   $(document).on('click', 'button.checkoutPayment', function(){

      // Close Review Modal & Toggle "checkout" modal two: "Payment information"
      $('#checkoutReview').modal('toggle');
      $('#checkoutPayment').modal();
      var totalCents = $.jStorage.get('total');
      var total_english_notation = totalCents/100;
      $('#checkoutPayment').find('.total-english-notation').append(total_english_notation);
      $('#checkoutPayment').find('input[name="amount"]').attr('value',totalCents);
   });

   // After "Charge Your Card" is clicked
      // Get gif spinner
      // Toggle "checkout" modal three: "Confirmation/Receipt"

   // http://stackoverflow.com/questions/439463/how-to-get-get-and-post-variables-with-jquery
   var $_GET = {};
    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });

    if($_GET["payment"] == 'paid') {
      $('#checkoutPayment').modal('toggle');
    }



});