jQuery(document).ready(function($){
  
  /*
   * "Shopping Cart": Actions
   */
  
  // Ajaxily? refreshes shopping cart on each page load :(
  function refresh_shopping_cart() {
    //Create a shopping object for each product in the shopping cart
    var jPostIDs = $.jStorage.index();
    var length = $.jStorage.index().length;
    var jProducts = [];
    for(var i = 0; i < length; i++) {
      var postIDArray = jPostIDs[i];
      var jPostID = new String(postIDArray);
      var singleIDValues = $.jStorage.get(postIDArray);
      var singleIDValuesArray = singleIDValues.split(','); // Need to construct an array from string
      var jColor = singleIDValuesArray[0];
      var jQty = singleIDValuesArray[1];

      var jProduct = {};
      jProduct['postID'] = jPostID;
      jProduct['color'] = jColor;
      jProduct['qty'] = jQty;
      jProducts.push(jProduct);
    }
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
          $('.shoppingcart a.shoppingcartshow').popover({
            'html': true,
            'placement': 'bottom',
            'content': response.html+"<a href='#' class='clearcart'>Empty your shopping cart</a>",
          });

          // Show 'Shopping Cart' button if hidden
          if($('ul li.shoppingcart').is(':hidden')) {
            $('ul li.shoppingcart').toggleClass('hide', 'show');
          }
          return false;
        } else {
          alert('fail!');
        }
    });
  }

  // Toggle Shopping Cart
  if($.jStorage.index().length) {
    refresh_shopping_cart();
  } else {
    $('.shoppingcart a.shoppingcartshow').popover({
      'html': true,
      'placement': 'bottom',
      'content': "Your shopping cart is currently empty.",
    });
  }

  // Empty Entire Shopping Cart
  // http://stackoverflow.com/questions/13205103/attach-event-handler-to-button-in-twitter-bootstrap-popover
  $(document).on('click', '.popover a.clearcart', function(){
    $.jStorage.flush();
    // Destroy existing popover and recreate
    $('.shoppingcart a.shoppingcartshow').popover('destroy');
    $('.shoppingcart a.shoppingcartshow').popover({
      'html': true,
      'placement': 'bottom',
      'content': "Your shopping cart is currently empty.",
    });
    $('.shoppingcart a.shoppingcartshow').popover('toggle');
    $('.popover').delay(2000).fadeOut(800);
  });

  /*
   * "Shopping Cart": View
   */

  // After "Add To Cart" is clicked, 'refresh' shopping cart view 
  $(document).on('click', '#addToCart', function() {
    
    // Get post ID of newly added product
    var jPostID = $(this).data('post-id');    
    // Get post IDs of existing products
    var jPostIDs = $.jStorage.index();
    // Merge new post ID into array of existing products
    jPostIDs.push(jPostID);
    
    // Grab option values
    if ($('.product-color-selection')) {
      var jColor = $('.product-color-selection').val();
    } else {
      var jColor = 'none';
    }
    var jQty = $('.product-qty-selection').val();
    // Store the new 'post' in the shopping cart
    $.jStorage.set(jPostID, jColor+','+jQty);

    //Create a shopping object for each product in the shopping cart
    var jPostIDs = $.jStorage.index();
    var length = $.jStorage.index().length;
    var jProducts = [];
    for(var i = 0; i < length; i++) {
      var postIDArray = jPostIDs[i];
      var jPostID = new String(postIDArray);
      var singleIDValues = $.jStorage.get(postIDArray);
      var singleIDValuesArray = singleIDValues.split(','); // Need to construct an array from string
      var jColor = singleIDValuesArray[0];
      var jQty = singleIDValuesArray[1];

      var jProduct = {};
      jProduct['postID'] = jPostID;
      jProduct['color'] = jColor;
      jProduct['qty'] = jQty;
      jProducts.push(jProduct);
    }

    $.post(shopping_cart_scripts.ajaxurl, {
        dataType: "jsonp",
        action: 'refresh_shopping_cart',
        nonce: shopping_cart_scripts.nonce,
        products: jProducts,
        postIDs: jPostIDs,
        color: jColor,
        qty: jQty,
      }, function(response) {
        if (response.success===true) {

          // Destroy existing popover and recreate
          $('.shoppingcart a.shoppingcartshow').popover('destroy');
          $('.shoppingcart a.shoppingcartshow').popover({
            'html': true,
            'placement': 'bottom',
            'content': response.html+"<a href='#' class='clearcart'>Empty your shopping cart</a>",
          });
          $('.shoppingcart a.shoppingcartshow').popover('toggle');
          $('.popover').delay(5000).fadeOut(800);

          // Show 'Shopping Cart' button if hidden
          if($('ul li.shoppingcart').is(':hidden')) {
            $('ul li.shoppingcart').toggleClass('hide', 'show');
          }
          return false;
        } else {
          alert('fail!');
        }
    });
  }); // end click

});