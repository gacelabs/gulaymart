
<script type="text/javascript">
	if (window.location.protocol == 'https') {
		window.fbAsyncInit = function() {
			FB.init({
				appId	: '<?php echo FB_APPID;?>',
				cookie	: true,
				xfbml	: true,
				version	: '<?php echo FB_VERSION;?>'
			});
			FB.AppEvents.logPageView();
		};
		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	} else {
		console.error('fb scripts cannot be use on http protocol!');
	}
</script>