jQuery(document).ready( function($) {

  /**
   * Admin Dashboard Scripts
   * @since 1.2.0
   * @link http://www.chartjs.org/docs
   */
  if ( $('body').hasClass('index-php') ) {
    // ### Product Widget
    var product_labels = function() {
      var labels = [];
      $('#dashboard-widgets ul.product-options li').each( function() {
        labels.push( $(this).find('h4 span.sku').text() );
      });
      return labels;
    }
    var product_data = function() {
      var data = [];
      $('#dashboard-widgets ul.product-options li').each( function() {
        data.push( $(this).find('h4 span.sku-quantity').text() );
      });
      return data;
    }
    var ctx = $('canvas#stock-overview').get(0).getContext("2d");
    var data = {
      labels : product_labels(),
      datasets: [
        {
          label: "Products",
          fillColor: "rgba(151,187,205,0.5)",
          strokeColor: "rgba(151,187,205,0.8)",
          highlightFill: "rgba(151,187,205,0.75)",
          highlightStroke: "rgba(151,187,205,1)",
          data: product_data()
        }
      ]
    }
    var stock_overview = new Chart( ctx ).Bar( data );
  } // index-php
});
