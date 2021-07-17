
<script type="text/javascript">
	// if (window.location.protocol == 'https:') {
		window.fbAsyncInit = function() {
			FB.init({
				appId	: '<?php echo FB_APPID;?>',
				cookie	: true,
				xfbml	: true,
				version	: '<?php echo FB_VERSION;?>'
			});
			FB.AppEvents.logPageView();

			var runFbLogin = function() {
				FB.api('/me?fields=id,email,name', function(data) {
					console.log(data);
					simpleAjax('authenticate/fb_login', data);
				});
			};

			var logOutFacebook = function() { FB.logout(); };

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
			}

			$('.fb-login-btn').off('click').on('click', function(e) {
				FB.login(function(response) {
					console.log(response);
					fb_acc_response = response;
					if (response.status === 'connected') {
						runFbLogin();
					}
				}, {scope: 'public_profile, email'});
			});
		};
		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	// } else {
	// 	console.error('fb scripts cannot be use on http protocol!');
	// }
</script>