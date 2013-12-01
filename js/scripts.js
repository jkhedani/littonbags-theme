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

	// "Shop"
	if($('body').hasClass('shop')) {
		$('#product-type-tabs').tab('show');
	}
	// "Product Pages"
	if ( $('body').hasClass('single-products') ) {

		/**
		 * 	jQuery Click Color Selection modification
		 * 	08182013
		 */

 		// Hide select element and title
		$(".product-color-selection").hide();
		// Create color container after quantity selection
		$('.product-content .product-color-title').after('<div class="jquery-color-selection"><ul></ul></div>');
		// Grab available color options and create buttons in color container
		$(".product-color-selection option").each(function() {
			$('.jquery-color-selection ul').append('<li><a href="#" data-color-value='+$(this).val()+' class="'+$(this).val()+'">'+$(this).val()+'</a></li>').addClass('capitalize');
		});
		// Select the appropriate color value
		$('.jquery-color-selection a').on('click',function() {
			$('.jquery-color-selection a').removeClass('selected');
			$(this).addClass('selected');
			var colorValue = $(this).attr('data-color-value');
			$('.product-color-selection').val(colorValue);
			return false;
		});

		/**
		 *	jQuery ScrollTo
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


	/*
	 * Form Validation
	 * Utilizes: https://github.com/jzaefferer/jquery-validation
     * Bootstrap integration with a little help from goldsky: https://gist.github.com/goldsky/4022619
     */

});