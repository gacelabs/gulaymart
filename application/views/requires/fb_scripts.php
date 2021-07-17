
<script type="text/javascript">
	window.fbAsyncInit = function() {
		FB.init({
			appId	: '<?php echo FB_APPID;?>',
			cookie	: true,
			status	: true,
			xfbml	: true,
			version	: '<?php echo FB_VERSION;?>'
		});
		FB.AppEvents.logPageView();

		FB.getLoginStatus(function(response) {
			fb_acc_response = response;
			var status = response.status;
			if (oUser == false) {
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

						FB.api('/me?fields=id,email,name', function(data) {
							simpleAjax('authenticate/fb_login', data);
						});
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
			} else {
				switch (status) {
					case "not_authorized": case "unknown":
						FB.login(function(response) {
							fb_acc_response = response;
						}, {scope: 'public_profile, email'});
					break;
				}
			}
		});
	};
	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<div id="fb-root"></div>