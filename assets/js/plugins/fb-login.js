
$(document).ready(function() {
	if (oUser == false) {
		FB.getLoginStatus(function(response) {
			fb_acc_response = response;
			var status = response.status;
			switch (status) {
				case "connected":
					$('.onlogged-out-btn').addClass('hide');
					$('.onlogged-in-btns').removeClass('hide');

					$('#login_modal').find('[data-dismiss="modal"]').addClass('hide');
					$('#login_modal').find('[href="register"]').addClass('hide');
					$('.login-detail').addClass('hide');
					$('.login-form-body').addClass('hide');
					$('.fb-login-btn').addClass('hide');
					$('.fb-login-panel').removeClass('hide');
					$('.fb-signing-in').removeClass('hide');
					$('#login_modal').modal('show');
					runFbLogin();
				break;
				case "not_authorized": case "unknown":
					$('.onlogged-out-btn').removeClass('hide');
					$('.onlogged-in-btns').addClass('hide');
					
					$('#login_modal').removeAttr('data-keyboard').removeAttr('data-backdrop');
					$('#login_modal').find('[data-dismiss="modal"]').removeClass('hide');
					$('#login_modal').find('[href="register"]').removeClass('hide');
					$('.login-detail').removeClass('hide');
					$('.login-form-body').removeClass('hide');
					$('.fb-login-btn').removeClass('hide');
					$('.fb-login-panel').addClass('hide');
					$('.fb-signing-in').addClass('hide');
				break;
			}
		});

		$('.fb-login-btn').off('click').on('click', function(e) {
			FB.login(function(response) {
				console.log(response);
				fb_acc_response = response;
				if (response.status === 'connected') {
					runFbLogin();
				}
			}, {scope: 'public_profile, email'});
		});
	} else {
		runFbLogin(true);
		$('[href="sign-out"]').bind('click', function(e) {
			e.preventDefault();
			var oThis = $(e.target);
			if (oThis.prop('tagName') != 'A') oThis = $(e.target).parents('a');
			console.log(FB.getAuthResponse());
			if (FB.getAuthResponse() != null) {
				// FB.logout(function(response) {
					// console.log(response);
					// window.location = oThis.attr('href');
				// });
			} else {
				// window.location = oThis.attr('href');
			}
		});
	}
});

var runFbLogin = function(already) {
	FB.api('/me?fields=id,email,name', function(data) {
		console.log(data);
		if (already == undefined) {
			simpleAjax('authenticate/fb_login', data);
		}
	});
};

var logOutFacebook = function() { FB.logout(); };