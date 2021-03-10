<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->view('requires/head'); ?>
	</head>

	<body class="<?php foreach($body_class as $value) { echo trim($value)." ";} ?>">
		
		<div id="content_body_wrapper">
			<section id="content__top">
				<?php
					foreach ($content_top as $value) {
						$this->view($value);
					}
				?>
			</section>

			<section id="content__middle">
				<?php
					foreach ($content_middle as $value) {
						$this->load->view($value);
					}
				?>
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
		<?php
			foreach ($modals as $view => $value) {
				if (is_array($value)) {
					$this->view('modals/'.$view.'');
				} else {
					$this->view('modals/'.$value.'');
				}
			}
		?>		

	</body>
</html>