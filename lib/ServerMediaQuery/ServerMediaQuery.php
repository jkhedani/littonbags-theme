<?php

/**
 * Server Media Query
 * Spoof serving mobile-only content by making an
 * ajax call on server load requesting screen size parameters.
 */

function server_media_query() {
  do_action("init");
  global $wp_query, $post;

  // Nonce check
  $nonce = $_REQUEST['nonce'];
  if ( !wp_verify_nonce( $nonce, 'server_media_query_nonce') ) die( __('Busted.') );

  $html = "";
  $success = false;

  $windowwidth = $_REQUEST['windowwidth'];
  $postID = $_REQUEST['postID'];


  /**
   * Mobile Galleries
   */
  if ( $windowwidth < 768 ) {

    /**
     * Use ACF only to display lookbooks
     * @since 1.2.0
     */
    $lookbooks = new WP_Query(array(
      'post_type' 			=> 'look_books',
      'posts_per_page' 	=> 1, // Select the first book.
      'meta_key'				=> 'look_book_location',
      'meta_query' 			=> array (
        array (
          'key' 		=> 'look_book_location',
          'value' 	=> '"' . $postID . '"',
          'compare' => 'LIKE'
        )
      )
    ));

  /**
   * Normal Galleries
   */
  } else {

    /**
     * Use ACF only to display lookbooks
     * @since 1.2.0
     */
    $lookbooks = new WP_Query(array(
      'post_type' 			=> 'look_books',
      'posts_per_page' 	=> 1, // Limit one for home page
      'offset'          => 1, // Select the second lookbook!
      'meta_key'				=> 'look_book_location',
      'meta_query' 			=> array (
        array (
          'key' 		=> 'look_book_location',
          'value' 	=> '"' . $postID . '"',
          'compare' => 'LIKE'
        )
      )
    ));


  }

  // Create HTML Markup
  $i = 0;
  while ( $lookbooks->have_posts() ) : $lookbooks->the_post();
    if ( have_rows('look_book', $post->ID ) ) :
      while ( have_rows('look_book', $post->ID ) ) : the_row();
        $lookbook_image = wp_get_attachment_image_src( get_sub_field('look_book_page'), 'full' );
        if (!$i++) {
          $html .= '<img class="item active" src="'. $lookbook_image[0] .'" />';
        } else {
          $html .= '<img class="item" src="'. $lookbook_image[0] .'" />';
        }
      endwhile;
    endif;
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
add_action('wp_ajax_nopriv_server_media_query', 'server_media_query');
add_action('wp_ajax_server_media_query', 'server_media_query');

?>
