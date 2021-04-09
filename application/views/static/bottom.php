
		<?php
			foreach ($bottom['css'] as $value) {
				if (filter_var($value, FILTER_VALIDATE_URL)) {
					echo '<link rel="stylesheet" type="text/css" href="'.$value.'">';
				} elseif (in_array($value, ['global/global-style']) == false) {
					echo '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/'.$value.'').'.css">';
				}
				echo "\r\n";
			}
		?>
		
		<?php $this->view('requires/main_scripts'); ?>

		<?php
			foreach ($bottom['js'] as $value) {
				if (filter_var($value, FILTER_VALIDATE_URL)) {
					echo '<script type="text/javascript" src="'.$value.'"></script>';
				} elseif (in_array($value, ['global/global-js']) == false) {
					echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'').'.js"></script>';
				}
				echo "\r\n";
			}
		?>
		
		<?php
			foreach ($bottom['modals'] as $view => $value) {
				if (is_array($value)) {
					$this->view('modals/'.$view.'');
				} else {
					$this->view('modals/'.$value.'');
				}
			}
		?>

		<?php
			if (!$current_profile) {
				$this->view('modals/login_modal');
			}
		?>

		<?php if ($this->action != 'store'): ?>
			<?php $this->view('requires/realtime_scripts'); ?>
		<?php endif ?>
		
		<script type="text/javascript" src="<?php echo base_url('assets/js/main.js'); ?>"></script>

	</body>

</html>