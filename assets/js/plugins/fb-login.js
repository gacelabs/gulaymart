
$(document).ready(function() {
	if (window.location.protocol == 'https:') {
		if (oUser == false) {
			var time = setInterval(function() {
				if (FB != undefined) {
					FB.getLoginStatus(function(response) {
						fb_acc_response = response;
						var status = response.status;
						switch (status) {
							case "connected":
								$('.onlogged-out-btn').addClass('hide');
								$('.onlogged-in-btns').removeClass('hide');
							break;
							case "not_authorized": case "unknown":
								$('.onlogged-out-btn').removeClass('hide');
								$('.onlogged-in-btns').addClass('hide');
							break;
						}
					});
					clearInterval(time);
				}
			}, 1000);
		}

		var runFbLogin = function(response) {
			simpleAjax('profile/fb_login', {
				// fb_id: response.authResponse.userID,
				'data': response.authResponse,
				'g-recaptcha-response': grecaptcha.getResponse()
			});
		};

		$('.fb-login-btn').off('click').on('click', function(e) {
			grecaptcha.reset();
			if (fb_acc_response && $.inArray(fb_acc_response.status, ['not_authorized','unknown']) >= 0) {
				grecaptcha.execute(fbWidgetId);
				FB.login(function(response) {
					console.log(response);
					if (response.status === 'connected') {
						runFbLogin(response);
					}
				}, {scope: 'public_profile, email'});
			} else {
				console.log('User already logged in thru FB.');
				setTimeout(function() {
					window.location.reload(true);
				}, 300);
			}
		});
	}
});