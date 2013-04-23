jQuery(document).ready(function($){
	// "User Options" Modal
	// Users can either log in or sign up if they aren't currently logged in...from anywhere!
	if($('#userloginoptions')) {
		// If user selects "Register"
		$('#userloginoptions .loginoptions a.register').on('click',function(){
			$(this).toggleClass('hide');
			if(!$('#userloginoptions .loginoptions a.login').is(':visible')) {
				$('#userloginoptions .loginoptions a.login').toggleClass('hide');
			}
			$('#userloginoptions .registration').toggleClass('hide','show');
			return false;	
		});
		// If user select "Login"
		$('#userloginoptions .loginoptions a.login').on('click',function(){
			$(this).toggleClass('hide');
			if(!$('#userloginoptions .loginoptions a.register').is(':visible')) {
				$('#userloginoptions .loginoptions a.register').toggleClass('hide');
			}
			$('#userloginoptions .registration').toggleClass('hide','show');
			return false;	
		});
	}
});