
<body class="<?php echo implode(' ', $middle['body_class']);?>">
	<script>
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
	</script>
	
	<div id="content_body_wrapper">
		<section id="content__top">
			<?php
				foreach ($middle['head'] as $value) {
					$this->view('templates/'.$value);
				}
			?>
		</section>

		<section class="container" id="content__middle">
			<?php
				foreach ($middle['body'] as $value) {
					$this->view('templates/'.$value);
				}
			?>
		</section>

		<section id="content__footer">
			<?php
				foreach ($middle['footer'] as $value) {
					$this->view($value);
				}
			?>
		</section>
	</div>