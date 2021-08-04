<body class="<?php echo implode(' ', $middle['body_class']); echo(!empty($current_profile) ? ' is-logged' : '');?> <?php if (empty($current_profile)) {echo "check-loc-on";}?>" data-is-mobile="<?php echo $this->agent->is_mobile() ? 1 : 0;?>">
	<?php if (!isset($data['for_email']) AND $this->action != 'store' AND (bool)strstr($_SERVER['HTTP_HOST'], 'local') == false): ?>
		<?php $this->view('requires/fb_scripts'); ?>
	<?php endif ?>
	
	<div id="content_body_wrapper">
		<section id="content__top">
			<?php
				foreach ($middle['head'] as $value) {
					$this->view('templates/'.$value, ['data'=>$data]);
				}
			?>
		</section>

		<section <?php str_has_value_echo("marketplace", $this->class_name, "class='container'");?> id="content__middle">
			<?php
				foreach ($middle['body'] as $value) {
					$this->view('templates/'.$value, ['data'=>$data]);
				}
			?>
		</section>

		<section id="content__footer">
			<?php
				foreach ($middle['footer'] as $value) {
					$this->view($value, ['data'=>$data]);
				}
			?>
		</section>
	</div>