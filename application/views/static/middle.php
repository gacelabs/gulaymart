
<body class="<?php echo implode(' ', $middle['body_class']);?>">
	<?php $this->view('requires/fb_scripts'); ?>
	
	<div id="content_body_wrapper">
		<section id="content__top">
			<?php
				foreach ($middle['head'] as $value) {
					$this->view('templates/'.$value);
				}
			?>
		</section>

		<section <?php not_in_array_echo("logged-in", $middle['body_class'], "class='container'");?> id="content__middle">
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