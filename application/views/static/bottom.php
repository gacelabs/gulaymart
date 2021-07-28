		
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
			var serviceWorker, isSubscribed;
			if ('serviceWorker' in navigator) {
				var runSampleNotif = function() {
					$('#install-app').bind('click', function() {
						// try {
						// 	var notification = new Notification('test', {
						// 		body: 'message',
						// 		tag: 'simple-push-demo-notification',
						// 		icon: 'https://gulaymart.com/assets/images/favicon.png',
						// 		renotify: true,
						// 	});
						// 	console.log(notification);
						// 	notification.addEventListener('click', function(e) {
						// 		console.log(e.type, e);
						// 	});
						// 	notification.addEventListener('close', function(e) {
						// 		console.log(e.type, e);
						// 	});
						// 	notification.addEventListener('error', function(e) {
						// 		console.log(e.type, e);
						// 	});
						// 	notification.addEventListener('show', function(e) {
						// 		console.log(e.type, e);
						// 	});

						// } catch (err) {
							navigator.serviceWorker.ready.then(function(registration) {
								var notification = registration.showNotification('test', {
									body: 'message',
									tag: 'simple-push-demo-notification',
									icon: 'https://gulaymart.com/assets/images/favicon.png',
									renotify: true,
									vibrate: [200, 100, 200, 100, 200, 100, 200],
								});
								console.log(notification);
							});
						// }
					});
				};

				navigator.serviceWorker.register('sw.js')
				.then(function(reg){
					serviceWorker = reg;
					console.log("Service Worker registered");
					/*serviceWorker.pushManager.getSubscription()
					.then(function(subscription) {
						isSubscribed = !(subscription === null);
						if (isSubscribed) {
							console.log('User IS subscribed.');
						} else {
							console.log('User is NOT subscribed.');
							serviceWorker.pushManager.subscribe({
								userVisibleOnly: true,
								applicationServerKey: 'BA6gsZ2MpAFeB7t0U10uga1bPG9hWDGWOLrHDYKmOua5Cs9oBDEbycdmTFoZ_rVM6v08expaJvKkyJFNMHXd9fo'
							});
						}
					});*/
					if (!('Notification' in window)) {
						runAlertBox({type:'info', message: 'This browser does not support desktop notification.'});
					} else if (Notification.permission === 'granted') {
						runSampleNotif();
					} else if (Notification.permission === 'default' || Notification.permission === 'denied') {
						Notification.requestPermission().then(function (permission) {
							if (permission === "granted") {
								runSampleNotif();
							} else {
								runAlertBox({type:'info', message: 'Please enable Notification permission to use realtime messaging Service.', unclose: true});
							}
						});
					}
				}).catch(function(err) {
					console.log("Issue happened", err);
				});
			}
		</script>
	</body>
</html>