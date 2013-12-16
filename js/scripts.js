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

	// "Carousel" Sliders
	$('.carousel').carousel();

	// "Shop" Archive Page
	if ( $('body').hasClass('post-type-archive-products') ) {
		
		/**
		 *	View Master
		 *	By Justin Hedani
		 */

		/*
		 *	Helper Functions
		 */
		function showActive( animate ) {
			var activeIndex = $('.active').index();
			var activePostion = masterWidth * activeIndex;
			if ( animate ) {
				$('.view-master .view-master-reel').animate({
					marginLeft: '-' + activePostion + 'px',
				}, 500);
			} else {
				// No animation
				$('.view-master .view-master-reel').css( 'margin-left', '-' + activePostion + 'px' );
			}
		}

		/*
		 *	Setup
		 */
		// I. Set the width of each slide to the width of the view master wrapper.
		var masterWidth = $('.view-master').width();
		$('.view-master .slide').each( function(){
			$(this).width(masterWidth);
		});
		// II. Find the total amount of slides and set the reel to the sum of all slide widths.
		var slideCount = $('.view-master .slide').length;
		$('.view-master .view-master-reel').width( masterWidth * slideCount );

		/*
		 *	Movement
		 */
		// I. Ensure that active slide is shown first by moving the slide reel left the
		// length of single slide multiplied by the active position
		showActive();

		// II. Allow user to move between slides on the reel by moving active class...
		$('.view-master-control').on( 'click', function() {
			if ( $(this).data('slide') == 'prev' ) {
				if ( $('.view-master .active.slide').prev().length )
					$('.view-master .active.slide').removeClass('active').prev().addClass('active');
			} else if ( $(this).data('slide') == 'next' ) {
				if ( $('.view-master .active.slide').next().length )
					$('.view-master .active.slide').removeClass('active').next().addClass('active');
			}
			showActive(true);
			return false;
		});

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