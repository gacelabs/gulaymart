
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
						FB.logout(function(response) {
							fb_acc_response = response;
						});
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