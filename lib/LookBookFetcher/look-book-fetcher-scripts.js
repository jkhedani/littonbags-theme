jQuery(document).ready( function($) {

	/**
	 *	Look Book Fetcher
	 */
	if ( $('#generate-lookbook').length ) {
		$(document).on( 'click', '#generate-lookbook .lookbook-link', function() {
			// A. Hide page content & show spinner

			// B. Retrieve associated look book post ID
			var lookbookid = $(this).data('look-book-id');
			// C. Grab & Display Look Book
			$.post(look_book_fetcher_data.ajaxurl, {
				dataType: "jsonp",
				action: 'fetch_look_book',
				nonce: look_book_fetcher_data.nonce,
				lookBookID: lookbookid,
			}, function(response) {
				if ( response.success === true ) {
					// D. Hide primary content on pages
					//$('body #primary').fadeOut('slow');
					// E. Hide spinner and display look book directly after #main
					$('body').append( response.html );
					$('#lookBookModal').modal();
				}
			}); // $.post

			return false;
		}); // end click
	}

	/**
	 *	Prevent page scrolling when modal is present
	 */
	$(document).on( 'show', '#lookBookModal', function() {
		$('html').css( 'overflow', 'hidden' );
		$('html').addClass('fixed');
	});
	$(document).on( 'hide', '#lookBookModal', function() {
		$('html').css( 'overflow-y', 'scroll' );
		$('html').removeClass('fixed');
	});

	/**
	 *	Ensure View Master is applied after the modal has been generated
	 *	Issues with width not being calculated
	 */
	$(document).on( 'shown', '#lookBookModal', function() {
		$('#lookBookViewer').viewmaster();
	});

}); // jquery