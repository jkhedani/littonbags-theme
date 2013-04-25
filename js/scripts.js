jQuery(document).ready(function($){

	// "Returning"
	$('#userregister a.login').on('click',function(){
		$('#userregister .registration-form').hide();
		$('#userregister .login-form').show();
		return false;	
	});

	/*
	 * "User Checkout Options" Modal Scripts
	 */
	// Users can either log in or sign up if they aren't currently logged in...from anywhere!
	if($('#usercheckoutoptions')) {
		// "Guest"
		$('#usercheckoutoptions .loginoptions a.guest').on('click',function(){
			// Close Welcome modal and go to options
			var currentModal = $(this).parents('.modal').attr('id');
			$('#'+currentModal).modal('toggle');
			$('#cartoptions').modal('toggle');


			
			// var nextModal = $(this).parents('.modal').next().attr('id');
			//$('#'+currentModal).modal('toggle');
			//$('#'+nextModal).modal('toggle');
			return false;	
		});
		// "Login"
		$('#usercheckoutoptions .loginoptions a.login').on('click',function(){
			//$(this).toggleClass('hide');
			// if(!$('#userloginoptions .loginoptions a.register').is(':visible')) {
			// 	$('#userloginoptions .loginoptions a.register').toggleClass('hide');
			// }
			$('#usercheckoutoptions .registration-form').hide();
			$('#usercheckoutoptions .login-form').show();
			return false;	
		});
	}
});