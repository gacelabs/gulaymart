
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
								runFbLogin();
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

		var runFbLogin = function() {
			FB.api('/me?fields=id,email,name', function(data) {
				console.log(data);
				simpleAjax('authenticate/fb_login', data);
			});
		};

		$('.fb-login-btn').off('click').on('click', function(e) {
			FB.login(function(response) {
				console.log(response);
				fb_acc_response = response;
				if (response.status === 'connected') {
					runFbLogin();
				}
			}, {scope: 'public_profile, email'});
		});
	}
});