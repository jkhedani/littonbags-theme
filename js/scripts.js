jQuery(document).ready(function($){
	
	// "Shop"
	if($('body').hasClass('shop')) {
		$('#product-type-tabs').tab('show');
	}
	// "Product Pages"
	if ( $('body').hasClass('single-products') ) {
		/*
		 * jQuery Click Color Selection modification
		 * 08182013
		 */
 		// Hide select element and title
		$(".product-color-title, .product-color-selection").hide();
		// Create color container after quantity selection
		$('.product-content .product-qty-selection').after('<div class="jquery-color-selection"><ul></ul></div>');
		// Grab available color options and create buttons in color container
		$(".product-color-selection option").each(function() {
			$('.jquery-color-selection ul').append('<li><a href="#" data-color-value='+$(this).val()+' class="'+$(this).val()+'">'+$(this).val()+'</a></li>').addClass('capitalize');
		});
		// Select the appropriate color value
		$('.jquery-color-selection a').on('click',function() {
			$('.jquery-color-selection a').removeClass('selected');
			$(this).addClass('selected');
			var colorValue = $(this).attr('data-color-value');
			$('.product-color-selection').val(colorValue);
			return false;
		});

	}


	/*
	 * Form Validation
	 * Utilizes: https://github.com/jzaefferer/jquery-validation
     * Bootstrap integration with a little help from goldsky: https://gist.github.com/goldsky/4022619
     */

});