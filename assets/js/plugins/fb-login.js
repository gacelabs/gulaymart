
$(document).ready(function() {
	// if (window.location.protocol == 'https:') {
		$('.fb-login-btn').off('click').on('click', function(e) {
			FB.login(function(response) {
				console.log(response);
				fb_acc_response = response;
				if (response.status === 'connected') {
					runFbLogin();
				}
			}, {scope: 'public_profile, email'});
		});
	// }
});

var runFbLogin = function() {
	FB.api('/me?fields=id,email,name', function(data) {
		console.log(data);
		simpleAjax('authenticate/fb_login', data);
	});
};

var logOutFacebook = function() { FB.logout(); };