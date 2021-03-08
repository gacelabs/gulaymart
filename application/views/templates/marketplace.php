<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('requires/head'); ?>
	</head>

	<body class="<?php foreach($body_class as $value) { echo trim($value)." ";} ?>">
		
		<div id="content_body_wrapper">
			<section id="content__top">
				<?php
					foreach ($content_top as $value) {
						$this->load->view($value);
					}
				?>
			</section>

			<section class="container" id="content__middle">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="marketplace">
					<?php
						foreach ($content_middle as $value) {
							$this->load->view($value);
						}
					?>
				</div>
			</section>

			<section id="content__footer">
				<?php
					foreach ($content_footer as $value) {
						$this->load->view($value);
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
		<?php $this->load->view('requires/realtime_scripts'); ?>
		<?php
			foreach ($modals as $view => $value) {
				if (is_array($value)) {
					$this->load->view('modals/'.$view.'');
				} else {
					$this->load->view('modals/'.$value.'');
				}
			}
		?>		

	</body>
</html>