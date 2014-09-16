<?php

/**
 *	Look Books
 */

function fetch_look_book() {

	global $wp_query, $post;

	// Nonce check
	$nonce = $_REQUEST['nonce'];
	if ( !wp_verify_nonce( $nonce, 'look_books_nonce') ) die( __('Busted.') );

	$html = "";
	$success = false;

	// Grab filter values via ajax...
	if ( isset($_REQUEST['lookBookID']) ) {
		$lookBookID = $_REQUEST['lookBookID'];
	}
	if ( isset($_REQUEST['lookBookSize']) ) {
		$lookbooksize = $_REQUEST['lookBookSize'];
	}

	// A. Here we want to check if a lookbook is available. If so, let's render the gallery.
	$lookbook = new WP_Query( array(
		'post_type' => 'look_books',
		'page_id' => $lookBookID,
		'nopaging' => true,
	));

	//error_log($lookBookID);

	while ( $lookbook->have_posts() ) : $lookbook->the_post();

		if ( have_rows('look_book', $post->ID) ) {
			$i = 0;
			$lookbookPages = get_field('look_book', $post->ID);

			$html .=  '<div id="lookBookModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
			$html .=	 '<div class="modal-dialog">';
			$html .=   '<div class="modal-body">';
			// Litton Bags Logo
			$html .=		'<div class="lookbook-logo">';
			$html .= 			'<img src="'.get_stylesheet_directory_uri() .'/images/litton-ludwig-logo-white.png" />';
			$html  .= 		'</div>';
			// Carousel
			$html .= 	 	'<div id="lookBookViewer" class="carousel slide">';
			// Carousel Indicators
			$html .=		'<ol class="carousel-indicators">';
			foreach ( $lookbookPages as $lookbookPage ) {
				if ( !$i++ ) :
					$html .=			'<li data-target="#carousel-example-generic" data-slide-to="'.$i.'" class="active"></li>';
				else :
					$html .=			'<li data-target="#carousel-example-generic" data-slide-to="'.$i.'"></li>';
				endif;
			}
			$html .=		'</ol>';

		  // Carousel Slides
			$i = 0; // Reset counter
			$html .= '<div class="carousel-inner">';
			while ( have_rows('look_book', $post->ID ) ) : the_row();
			$lookbook_image = wp_get_attachment_image_src( get_sub_field('look_book_page'), $lookbooksize ); // Set lookbook image size based on screen width
			if ( !$i++ ) :
				$html .=	'<img class="item active" src="' . $lookbook_image[0] . '" />';
			else :
				$html .=	'<img class="item" src="' . $lookbook_image[0] . '" />';
			endif;
			endwhile;
			$html .= '</div>';

			// Carousel Controls
			$html .= 				'<a class="carousel-control left" href="#lookBookViewer" data-slide="prev">&lsaquo;</a>';
			$html .= 				'<a class="carousel-control right" href="#lookBookViewer" data-slide="next">&rsaquo;</a>';
			$html .= 		'</div>'; // carousel

			$html .=   '</div>'; // .modal-body
			$html .= 	'</div>'; // .modal-dialog
			$html .= '</div>'; // .modal
	  }

	endwhile;
	wp_reset_postdata();

	$success = true;
	$response = json_encode(array(
		'success' => $success,
		'html' => $html,
	));

	// Construct and send the response
	header("content-type: application/json");
	echo $response;
	exit;
}
add_action('wp_ajax_nopriv_fetch_look_book', 'fetch_look_book');
add_action('wp_ajax_fetch_look_book', 'fetch_look_book');

?>
