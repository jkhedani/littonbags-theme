jQuery(document).ready( function($) {

	// SHOWN
	$(document).on( 'show.bs.modal', '#lookBookModal', function() {
		// # Prevent page scrolling when modal is present
		$('html').css( 'overflow', 'hidden' );
		$('html').addClass('fixed');
	});
	// HIDDEN
	$(document).on( 'hide.bs.modal', '#lookBookModal', function() {
		// # Restore scrolling functionality
		$('html').css( 'overflow-y', 'scroll' );
		$('html').removeClass('fixed');
	});

	/**
	*	Ensure View Master is applied after the modal has been generated
	*	Issues with width not being calculated
	*/
	$(document).on( 'show.bs.modal', '#lookBookModal', function() {
		$('.carousel').carousel({ 'interval' : 4000 });
		//$('#lookBookViewer').viewmaster();
	});

	/**
	 *	Look Book
	 */
	if ( $('#generate-lookbook').length ) {
		$(document).on( 'click', '#generate-lookbook .lookbook-link', function() {

			// # If we've already generated a modal, just toggle it.
			if ( $(document).find('#lookBookModal').length ) { // This isn't fucking working
				$('#lookBookModal').modal('toggle');
			// # Otherwise, create it.
			} else {
				// A. Hide page content & show spinner
				// B. Retrieve associated look book post ID
				var lookbookid = $(this).data('look-book-id');
				if ( screen.width < 768 ) {
					var lookbooksize = 'medium';
				} else {
					var lookbooksize = 'large';
				}
				// C. Grab & Display Look Book
				$.post(look_book_data.ajaxurl, {
					dataType: "jsonp",
					action: 'fetch_look_book',
					nonce: look_book_data.nonce,
					lookBookID: lookbookid,
					lookBookSize: lookbooksize,
				}, function(response) {
					if ( response.success === true ) {
						// D. Hide primary content on pages
						//$('body #primary').fadeOut('slow');
						// E. Hide spinner and display look book directly after #main

						$('body').append( response.html );
						$('#lookBookModal').modal();
					}
				}); // $.post
			}
			return false;
		}); // end click
	}

}); // jquery
