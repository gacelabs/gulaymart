
<script type="text/javascript">
	var realtime = false;
	window.initSendData = function() {
		realtime = new SendData({
			afterInit: function() {
				realtime.connect(function() {
					console.log('gulaymart.com ready to communicate!');
					/*if (oUser == false) {
						realtime.bind('session', 'auth-login', function(object) {
							if (object.data.device_id == DEVICE_ID) {
								runAlertBox({
									type:'confirm',
									message: 'Session Log-in detected from other browser, do you want to cancel all other log-in sessions?',
									callback: function() { 
										window.location.href = 'sign-out';
									}
								});
							}
						});
					} else {
						realtime.bind('session', 'auth-logout', function(object) {
							if (object.data.device_id == DEVICE_ID) {
								runAlertBox({
									type:'warn',
									message: 'Log-out request detected from other browser, click ok to reload page.',
									callback: function() {
										window.location.href = 'sign-out';
									}
								});
							}
						});
					}*/
					/*realtime.trigger('add-delivery', 'deliveries', {});*/
					if (oUser) {
						/*communicate from orders tab to fulfillment tab*/
						if (typeof runFulfilllmentsRealtime == 'function') runFulfilllmentsRealtime(realtime);
						/*communicate from fulfillment tab to orders tab*/
						if (typeof runOrdersRealtime == 'function') runOrdersRealtime(realtime);
					}
				});
			},
			afterConnect: function() {
				/*realtime.bind('return-delivery', 'returns', function(object) {
					console.log('received response from portal.toktok.ph', object.data);
				});*/
				/*if (oUser) {
					realtime.trigger('session', 'auth-login', oUser);
				} else {
					realtime.trigger('session', 'auth-logout', {device_id: DEVICE_ID});
				}*/
			}
		});
	};
	(function(d, s, id) {
		var js, p = d.getElementsByTagName(s), me = p[p.length - 1];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.type = 'text/javascript';
		js.src = "<?php echo REALTIME_URL;?>";
		me.parentNode.insertBefore(js, me);
	}(document, "script", "sd-sdk"));
</script>