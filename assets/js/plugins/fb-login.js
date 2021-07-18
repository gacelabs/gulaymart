$(document).ready(function() {
	if (oUser == false) {
		$('.fb-login-btn').off('click').on('click', function(e) {
			if (fb_acc_response.status != 'connected') {
				FB.login(function(response) {
					// console.log(response);
					fb_acc_response = response;
					if (response.status === 'connected') {
						$('#login_modal').find('[data-dismiss="modal"]').addClass('hide');
						$('#login_modal').find('[href="register"]').addClass('hide');
						$('.login-detail').addClass('hide');
						$('.login-form-body').addClass('hide');
						$('.fb-login-btn').addClass('hide');
						$('.fb-login-panel').removeClass('hide');
						$('.fb-signing-in').removeClass('hide');
						runFbLogin();
					} else{
						$('#login_modal').modal('hide');
					}
				}, {scope: 'public_profile, email'});
			} else {
				runFbLogin();
			}
		});
	}
});

var runFbLogin = function() {
	FB.api('/me?fields=id,email,name', function(data) {
		// console.log('login 2', fb_acc_response);
		simpleAjax('authenticate/fb_login', data);
	});
};