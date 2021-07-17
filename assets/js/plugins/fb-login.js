$(document).ready(function() {
	if (oUser == false) {
		$('.fb-login-btn').off('click').on('click', function(e) {
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
				}
			}, {scope: 'public_profile, email'});
		});
	} else {
		$('[href="sign-out"]').bind('click', function(e) {
			e.preventDefault();
			var oThis = $(e.target);
			if (oThis.prop('tagName') != 'A') oThis = $(e.target).parents('a');
			// console.log(FB.getUserID());
			if (FB.getUserID() != '') {
				logOutFacebook(oThis);
			} else {
				window.location = oThis.attr('href');
			}
		});
	}
});

var runFbLogin = function() {
	FB.api('/me?fields=id,email,name', function(data) {
		simpleAjax('authenticate/fb_login', data);
	});
};

var logOutFacebook = function(oThis) {
	FB.logout(function(response) {
		window.location = oThis.attr('href');
	});
};