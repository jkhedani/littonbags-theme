<?php

/**
 *	Look Book Fetcher
 */

function fetch_look_book() {

	do_action('init');
	// Need to grab connection types as well.
	// Safe hook for calling p2p_register_connection_type()
	// https://github.com/scribu/wp-posts-to-posts/blob/master/posts-to-posts.php
	_p2p_init();

	global $wpdb, $wp_query, $post;

	// Nonce check
	$nonce = $_REQUEST['nonce'];
	if ( !wp_verify_nonce( $nonce, 'look_book_fetcher_nonce') ) die( __('Busted.') );

	$html = "";
	$success = false;

	// Grab filter values via ajax...
	if ( isset($_REQUEST['lookBookID']) ) {
		$lookBookID = $_REQUEST['lookBookID'];
	}

	// A. Here we want to check if a lookbook is available. If so, let's render the gallery.
	$lookbook = new WP_Query( array(
		'post_type' => 'look_books',
		'page_id' => $lookBookID,
		'nopaging' => true,
	));

	while ( $lookbook->have_posts() ) : $lookbook->the_post();

		if ( get_field('look_book', $post->ID) ) {
			$lookbookPages = get_field('look_book', $post->ID);

			$html .=  '<div id="lookBookModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
			// $html .=    '<div class="modal-header">';
			// $html .=    	'<h2>'.get_the_title().'</h2>';
			// $html .=    	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">Back</button>';
			// $html .=			'<div class="content-fluff stamp-watermark"></div>';
			// $html .=   '</div>';
			$html .=   '<div class="modal-body">';
			$html .= 	 	'<div id="lookBookViewer" class="view-master">';
			$html .=			'<div class="view-master-controls">';
		  $html .=  			'<a class="view-master-control left" href="#productViewer" data-slide="prev">&lsaquo;</a>';
		  $html .=  			'<a class="view-master-control right" href="#productViewer" data-slide="next">&rsaquo;</a>';
		  // Construct counter
			$i = 0;
		  $lookbookPages = get_field('look_book', $post->ID);
			$html .= 				'<div class="view-master-counters">';
			foreach ( $lookbookPages as $lookbookPage ) {
				$html .= 				'<span class="view-master-counter"></span>';
			}
			$html .= 				'</div>';
		  $html .=  		'</div><!-- .view-master-controls -->';
		  $html .=  		'<div class="view-master-reel">';
		  
		  // Construct slides
	    foreach ( $lookbookPages as $lookbookPage ) {
			  if ( !$i++ ) {
			  $html .=  			'<div class="slide active">';
			  } else {
			  $html .=  			'<div class="slide">';
			  }	
			  // $html .=  				'<div class="slide-content">';  
			  $html .=						'<img src="'.$lookbookPage['look_book_page'].'" />';
			  //$html .=				'<div class="background-image item" style="background-image:url('.$lookbookPage['look_book_page'].');"></div>';  
			  // $html .=  		'</div>';
			  $html .=  			'</div>';
		 	}
		  $html .=			'</div><!-- .view-master-reel -->';

			$html .=		'</div><!-- .view-master -->';
			$html .=   '</div>'; // .modal-body
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

//Run Ajax calls even if user is logged in
if(isset($_REQUEST['action']) && ($_REQUEST['action']=='fetch_look_book')):
	do_action( 'wp_ajax_' . $_REQUEST['action'] );
  do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
endif;

?>