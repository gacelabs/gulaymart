		
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-min.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'.$this->versioning);?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/autosize.min.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery-dateformat.min.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/favico-0.3.10.min.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/toast.min.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/global.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/validate-form.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'.$this->versioning); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/main.js'.$this->versioning); ?>"></script>

		<?php
			foreach ($bottom['js'] as $value) {
				if (filter_var($value, FILTER_VALIDATE_URL)) {
					echo '<script type="text/javascript" src="'.$value.'"></script>';
				} else {
					echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'.js'.$this->versioning).'"></script>';
				}
				echo "\r\n";
			}
		?>
		
		<?php
			foreach ($bottom['modals'] as $view => $value) {
				if (is_array($value)) {
					$this->view('modals/'.$view, ['data'=>$data]);
				} else {
					$this->view('modals/'.$value, ['data'=>$data]);
				}
			}
		?>

		<?php
			if (!isset($data['for_email'])) {
				if (!$current_profile) {
					$this->view('modals/login_modal', ['data'=>$data]);
				}
			}
		?>

		<?php if (!isset($data['for_email']) AND $this->action != 'store'): ?>
			<?php $this->view('requires/realtime_scripts', ['data'=>$data]); ?>
		<?php endif ?>
	</body>
</html>