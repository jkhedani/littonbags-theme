jQuery(document).ready(function($){
	
	/**
	 *	General Scripts
	 */

 	// Hide address bar for iOS devices
 	// http://davidwalsh.name/hide-address-bar
	window.addEventListener("load",function() {
		setTimeout( function() {
			window.scrollTo(0, 1);
		}, 0);
	});

	// Carousel sliders
	$('.carousel').carousel();

	// "Product Pages"
	if ( $('body').hasClass('single-products') ) {

		/**
		 *	jQuery ScrollTo
		 *	Allow users to scroll to pre-determined positions on a page.
		 */

		// Define scrollable object
		var SCROLL_TO_OBJECT = $('.product-scroll img');
		// Define scrollto navigation
		var SCROLL_TO_NAV = $('.product-specifications');
		// Find scrollable object distance from page top
		var DISTANCE_FROM_TOP = parseInt( SCROLL_TO_OBJECT.offset().top );
		// On scroll to navigation click
		$(SCROLL_TO_NAV).find('a').on( 'click', function() {
			// Get desired scroll location
			var desiredScrollLocation = parseInt( $(this).attr('data-scroll-position') );
			// Calculate exact page scroll location by taking into consideration the scroll objects position from the top
			var exactPageScrollToPosition = DISTANCE_FROM_TOP + desiredScrollLocation;
			// scroll page to scroll location
			$('html, body').animate({
        scrollTop: exactPageScrollToPosition
    	}, 500);
		});
	}

});