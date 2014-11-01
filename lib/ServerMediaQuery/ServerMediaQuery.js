jQuery(document).ready( function($) {

  if ( $('body').hasClass('home') && $('.server-media-query').length ) {

    var window_width  = $(window).width();
    var load_function = $('.server-media-query').data('load');
    var post_id       = $('.server-media-query').data('post-id');
    $.post(server_media_query_scripts.ajaxurl, {
      dataType: "jsonp",
      action: 'server_media_query',
      nonce: server_media_query_scripts.nonce,
      windowwidth: window_width,
      postID: post_id,
    }, function(response) {
      if ( response.success === true ) {
        // Place the html content into carousel inner
        $('#homeCarousel .carousel-inner').append( response.html );
      } else {
        console.log('Bad AJAX response \(ServerMediaQuery\)');
      }
    }); // $.post

  }

});
