<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->view('requires/head'); ?>
		<script type="text/javascript">
			var fb_acc_response = false, oUser = <?php echo $current_profile ? json_encode($current_profile) : 'false';?>;
		</script>
	</head>

	<body class="<?php foreach($body_class as $value) { echo trim($value)." ";} ?>">
		<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId      : '<?php echo FB_APPID;?>',
					cookie     : true,
					xfbml      : true,
					version    : '<?php echo FB_VERSION;?>'
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
					foreach ($content_top as $value) {
						$this->view($value);
					}
				?>
			</section>

			<section class="container" id="content__middle">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="marketplace">
					<?php
						foreach ($content_middle as $value) {
							$this->view($value);
						}
					?>
				</div>
			</section>

			<section id="content__footer">
				<?php
					foreach ($content_footer as $value) {
						$this->view($value);
					}
				?>
			</section>
		</div>

		<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/rwd.css'); ?>">
		<?php
			foreach ($css as $key => $values) {
				if ($key == "footer") {
					foreach ($values as $value) {
						echo '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/'.$value.'').'.css">';
						echo "\r\n";
					}
				}
			}
		?>
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
		<?php
			foreach ($js as $key => $values) {
				if ($key == "footer") {
					foreach ($values as $value) {
						echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'').'.js"></script>';
						echo "\r\n";
					}
				}
			}
		?>
		<script type="text/javascript">
			(function(d, s, id) {
				if (d.getElementById('sd-sdk') == null) {
					var js, p = d.getElementsByTagName(s), me = p[p.length - 1];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.type = 'text/javascript';
					js.src = "https://app.send-data.co/get/jsfile/A3193CF4AEC1ADD05F4B78C4E0C61C39";
					me.parentNode.insertBefore(js, me);
				} else {
					console.log('realtime sdk existing.')
				}
			}(document, "script", "sd-sdk"));
		</script>
		<?php $this->view('requires/realtime_scripts'); ?>
		<?php
			foreach ($modals as $view => $value) {
				if (is_array($value)) {
					$this->view('modals/'.$view.'');
				} else {
					$this->view('modals/'.$value.'');
				}
			}
		?>

		<?php
			if ($current_profile) {
				$this->view('modals/login_modal');
			}
		?>

	</body>
</html>