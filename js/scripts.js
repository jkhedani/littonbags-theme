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

	/**
	 *	View Master
	 *	By Justin Hedani
	 */
	( function( $ ) {

	   $.fn.viewmaster = function() {
			// Variables
			var viewmaster = this;
			// Helper Functions
			function showActive( animate ) {
				var activeIndex = $('.active').index();
				var activePostion = masterWidth * activeIndex;
				if ( animate ) {
					viewmaster.find('.view-master-reel').animate({
						marginLeft: '-' + activePostion + 'px',
					}, 500);
				} else {
					// No animation
					viewmaster.find('.view-master-reel').css( 'margin-left', '-' + activePostion + 'px' );
				}
			}

			/*
			 *	Setup
			 */
			// I. Set the width of each slide to the width of the view master wrapper.
			var masterWidth = viewmaster.width();
			viewmaster.find('.slide').each( function() {
				$(this).width(masterWidth);
			});
			// II. Find the total amount of slides and set the reel to the sum of all slide widths.
			var slideCount = viewmaster.find('.slide').length;
			viewmaster.find('.view-master-reel').width( masterWidth * slideCount );

			/*
			 *	Movement
			 */
			// I. Ensure that active slide is shown first by moving the slide reel left the
			// length of single slide multiplied by the active position
			showActive();

			// II. Allow user to move between slides on the reel by moving active class...
			viewmaster.find('.view-master-control').on( 'click', function() {
				if ( $(this).data('slide') == 'prev' ) {
					if ( viewmaster.find('.active.slide').prev().length )
						viewmaster.find('.active.slide').removeClass('active').prev().addClass('active');
				} else if ( $(this).data('slide') == 'next' ) {
					if ( viewmaster.find('.active.slide').next().length )
						viewmaster.find('.active.slide').removeClass('active').next().addClass('active');
				}
				showActive(true);
				return false;
			});
		} // . viewmaster();

	} ( jQuery ));

	/**
	 *	Home Page Scripts
	 */
	if ( $('body').hasClass('home') ) {
		// "Carousel" Sliders
		$('.carousel').carousel();
	} // .hasClass home


	// "All Product Pages"
	if ( $('body').hasClass('post-type-archive-products') ) {

		if ( $('.view-master').length ) {
			$('#productViewer').viewmaster();
			/*
			 * Custom View Master Functions
			 */
			// Since we will always be starting in the midde here, we can just look before and after
			// on page load.
			$('.view-master .active.slide').next().addClass('first-accessories');
			$('.view-master .active.slide').prev().addClass('first-bags');
			// Remove margins on featured images, when ...
			$('.view-master-control').on( 'click', function() {
				// next slide has class first-accessories
				if ( $('.view-master .active.slide').hasClass('first-accessories') ) {
					$('.view-master .active.slide img.featured-image').animate({
						marginLeft: '0px',
					}, 500);
				}
				// next slide has class first-bags
				if ( $('.view-master .active.slide').hasClass('first-bags') ) {
					$('.view-master .active.slide img.featured-image').animate({
						marginRight: '0px',
					}, 500);
				}
				// next slide is center slide
				if ( $('.view-master .active.slide').hasClass('center-slide') ) {
					// Animate both images
					$('.view-master .first-accessories.slide  img.featured-image').animate({
						marginLeft: '-400px',
					}, 500);

					$('.view-master .first-bags.slide img.featured-image').animate({
						marginRight: '-400px',
					}, 500);
				}
			});
		} // .view-master

	}

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