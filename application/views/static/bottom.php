
		<?php
			foreach ($bottom['css'] as $value) {
				echo '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/'.$value.'').'.css">';
				echo "\r\n";
			}
		?>
		
		<?php $this->view('requires/main_scripts'); ?>

		<?php
			foreach ($bottom['js'] as $value) {
				echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'').'.js"></script>';
				echo "\r\n";
			}
		?>

		<!-- <?php $this->view('requires/realtime_scripts'); ?> -->
		
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
			if ($data['is_login'] == 0) {
				$this->view('modals/login_modal');
			}
		?>

	</body>

</html>