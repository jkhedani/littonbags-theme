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
	 *	Mobile Menu
	 *	Toggle must be right above menu you wish to affect.
	 */
	$('.mobile-menu-toggle').on( 'click', function() {
		$(this).next('.mobile-menu').toggle();
		return false;
	});

	/**
	 *	View Master
	 *	By Justin Hedani
	 */
	( function( $ ) {

	   $.fn.viewmaster = function() {
			// Variables
			var viewmaster = this;

			/*
			 * Helper Functions
			 */
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
				// If there are no next/prev slides after click, hide next/prev controls
				if ( activeIndex == 0 ) { // when at the FARTHEST LEFT
					viewmaster.find('.view-master-controls .view-master-control.left').hide();
					viewmaster.find('.view-master-controls .view-master-control.right').show();
				} else if ( activeIndex == ( slideCount - 1 ) ) { // when at the FARTHEST RIGHT
					viewmaster.find('.view-master-controls .view-master-control.left').show();
					viewmaster.find('.view-master-controls .view-master-control.right').hide();
				} else {
					if ( $('.view-master .active.slide').hasClass('center-slide') && window.innerWidth <= 768 ) {
						// If there are more items to be viewed, show controls. Also reveals hidden controls on next click.
						viewmaster.find('.view-master-controls .view-master-control').hide();
						console.log('asdf');
					} else {
						// If there are more items to be viewed, show controls. Also reveals hidden controls on next click.
						viewmaster.find('.view-master-control').show();
					}
				}

			}

			/*
			 *	Movement
			 */
			// I. Set the widths of the slides based on view master reel width
			var masterWidth = viewmaster.width();
			viewmaster.find('.slide').each( function() {
				$(this).width(masterWidth);
			});
			// I.i Find the total amount of slides and set the reel to the sum of all slide widths.
			var slideCount = viewmaster.find('.slide').length;
			viewmaster.find('.view-master-reel').width( masterWidth * slideCount );

			// I.ii Also, recalculate the widths on window resize with a bit of a delay to prevent
			// multiple resize calls.
			// $(window).on('resize',function(){
			// 	setTimeout( function() {
			// 		// I. Set the widths of the slides based on view master reel width
			// 		var masterWidth = viewmaster.width();
			// 		viewmaster.find('.slide').each( function() {
			// 			$(this).width(masterWidth);
			// 		});
			// 		// I.i Find the total amount of slides and set the reel to the sum of all slide widths.
			// 		var slideCount = viewmaster.find('.slide').length;
			// 		console.log(masterWidth);
			// 		viewmaster.find('.view-master-reel').width( masterWidth * slideCount );

			// 	},1500);
			// });

			// II. Ensure that active slide is shown first by moving the slide reel left the
			// length of single slide multiplied by the active position
			showActive();

			// III. Allow user to move between slides on the reel by moving active class...
			viewmaster.find('.view-master-control').on( 'click', function() {
				// # when clicking prev...
				if ( $(this).data('slide') == 'prev' ) {
					if ( viewmaster.find('.active.slide').prev().length )
						viewmaster.find('.active.slide').removeClass('active').prev().addClass('active');
				// # when clicking next...
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
		$('.carousel').carousel({
			'interval' : 4000,
		});
	} // .hasClass home


	// "All Product Pages"
	if ( $('body').hasClass('post-type-archive-products') ) {

		if ( $('.view-master').length ) {

			// # Run view master
			$('#productViewer').viewmaster();

			// # Custom View Master Functions
			// Primarily using these functions to help set and postion elements on the page

			var setFirstViewMasterElements = function() {
				// # Since we will always be starting in the midde here, we can just look before and after
				// on page load.
				$('.view-master .active.slide').next().addClass('first-accessories');
				$('.view-master .active.slide').prev().addClass('first-bags');
			}

			var positionViewMasterElements = function() {

				// # when moving to an ACCESSORIES slide
				if ( $('.view-master .active.slide').hasClass('first-accessories') ) {

					if ( window.innerWidth <= 400 ) {
						$('.view-master h1.bags').hide();
						$('.view-master .active.slide img.featured-image').animate({
							marginLeft: '-400px',
						}, 500);
					} else if ( window.innerWidth <= 768 ) {
						$('.view-master .active.slide img.featured-image').animate({
							marginLeft: '-275px',
						}, 500);
					} else {
						$('.view-master .active.slide img.featured-image').animate({
							marginLeft: '-110px',
						}, 500);
					}
				}

				// # when moving to an BAG slide
				if ( $('.view-master .active.slide').hasClass('first-bags') ) {
					if ( window.innerWidth <= 400 ) {
						$('.view-master h1.accessories').hide();
						$('.view-master .active.slide img.featured-image').animate({
							marginRight: '-400px',
						}, 500);
					} else if ( window.innerWidth <= 768 ) {
						$('.view-master .active.slide img.featured-image').animate({
							marginRight: '-275px',
						}, 500);
					} else {
						$('.view-master .active.slide img.featured-image').animate({
							marginRight: '0px',
						}, 500);
					}
				}

				// # when moving to the CENTER slide
				if ( $('.view-master .active.slide').hasClass('center-slide') ) {
					// Show buttons
					$('.view-master h1.accessories').show();
					$('.view-master h1.bags').show();
					// Animate both images
					$('.view-master .first-accessories.slide  img.featured-image').animate({
						marginLeft: '-400px',
					}, 500);
					$('.view-master .first-bags.slide img.featured-image').animate({
						marginRight: '-400px',
					}, 500);
				}

			} // positionViewMasterElements()

			// # Page LOAD
			setFirstViewMasterElements();

			// # On CLICK
			$('.view-master-control').on( 'click', function() {
				positionViewMasterElements();
			});

			// # On RESIZE
			// $(window).on( 'resize', function() {
			// 	setTimeout( function() {
			// 		positionViewMasterElements();
			// 	}, 1500);
			// });


		} // .view-master

	}

 /**
	* Simple delay function.
	* @function
	* @param {function} callback - Function to be called back.
	* @param {int} ms - The amount of time before callback occurs.
	*/
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();

	/**
	 * Back To Top
	 * @function
	 * @author Justin Hedani
	 */

	// On page scroll, show "Back To Top" button unless at top of page.
	$(document).on('scroll', function() {
		if ( document.body.scrollTop === 0 ) {
			delay( function(){
				$('.back-to-top').animate({ opacity: 0.0 }, 500);
			}, 50);
		} else if ( document.body.scrollTop >= 5 ) {
			delay( function(){
				$('.back-to-top').animate({ opacity: 0.8 }, 500);
			}, 50);
		}
	});

	// Animate page scroll back to top
	$('.back-to-top').on('click', function() {
		$('html, body').animate({
			scrollTop: 0
		}, 500 );
		return false;
	});

	// "Product Pages"
	if ( $('body').hasClass('single-products') ) {


		/**
		 *	jQuery ScrollTo
		 *	Allow users to scroll to pre-determined positions on a page.
		 */
		// Define scrollable object
		var SCROLL_TO_OBJECT = $('.product-scroll img');
		// Define scrollto navigation
		var SCROLL_TO_TRIGGER = $('.jumppage');
		// Find scrollable object distance from page top
		var DISTANCE_FROM_TOP = parseInt( SCROLL_TO_OBJECT.offset().top );
		// On scroll to navigation click
		$(SCROLL_TO_TRIGGER).on( 'click', function() {
			// Get desired scroll location
			var desiredScrollLocation = parseInt( $(this).attr('data-jump-coordinates') );
			// Calculate exact page scroll location by taking into consideration the scroll objects position from the top
			var exactPageScrollToPosition = DISTANCE_FROM_TOP + desiredScrollLocation;
			// scroll page to scroll location
			$('html, body').animate({
        scrollTop: exactPageScrollToPosition
    	}, 500);
    	// Disable <a> tag actions
    	return false;
		});

	}

});
