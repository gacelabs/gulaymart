		
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/autosize.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery-dateformat.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/toast.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/js/global.js'); ?>"></script>

		<?php
			foreach ($bottom['js'] as $value) {
				if (filter_var($value, FILTER_VALIDATE_URL)) {
					echo '<script type="text/javascript" src="'.$value.'"></script>';
				} elseif ((bool)strstr($value, '.min') == false) {
					$this->minify->add_js($value.'.js');
				} else {
					echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'').'.js"></script>';
				}
				echo "\r\n";
			}
			$this->minify->add_js('validate-form.js');
			$this->minify->add_js('common.js');
			$this->minify->add_js('main.js');
			echo $this->minify->deploy_js(false);
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

		<script type="text/javascript">
			if ('serviceWorker' in navigator) {
				navigator.serviceWorker.register('sw.js')
				.then(function(reg){
					console.log("Service Worker registered", reg);
				}).catch(function(err) {
					console.log("Issue happened", err);
				});
			}
		</script>
	</body>
</html>