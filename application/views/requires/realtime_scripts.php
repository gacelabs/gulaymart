
<script type="text/javascript">
	var realtime = false, serviceWorker;
	window.initSendData = function() {
		realtime = new SendData({
			afterInit: function() {
				realtime.connect(function() {
					// console.log('gulaymart.com ready to communicate!');
					/*realtime.trigger('add-delivery', 'deliveries', {});*/
					if (oUser) {
						/*communicate from orders tab to fulfillment tab*/
						if (typeof runOrdersToFulfillments == 'function') runOrdersToFulfillments(realtime);
						/*communicate from fulfillment tab to orders tab*/
						if (typeof runFulfillmentsToOrders == 'function') runFulfillmentsToOrders(realtime);
						/*communicate from operators booking page*/
						if (typeof runOperatorBookings == 'function') runOperatorBookings(realtime);
						
						/*listen for incomming on delivery fulfillments*/
						if (typeof fulfillmentProcess == 'function' && oSegments[1] == 'fulfillment') {
							fulfillmentProcess(runFulfillments);
						}
						/*listen for incomming on delivery orders*/
						if (typeof runOrders == 'function' && oSegments[1] == 'orders') {
							orderProcess(runOrders);
						}
					}
				});
			},
			afterConnect: function() {
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

	if ('serviceWorker' in navigator) {
		var runSampleNotif = function() {
			$('#install-app').bind('click', function() {
				if (oUser) {
					navigator.serviceWorker.ready.then(function(registration) {
						registration.update();
						registration.getNotifications({tag:'demo-notification'}).then(function(notifications) {
							let currentNotification;
							for(let i = 0; i < notifications.length; i++) {
								if (notifications[i].data && notifications[i].data.id === oUser.id) {
									currentNotification = notifications[i];
								}
							}
							return currentNotification;
						}).then(function(currentNotification) {
							let notificationTitle = '';
							const options = {
								badge: 'https://gulaymart.com/assets/images/favicon.png',
								body: '',
								icon: 'https://gulaymart.com/assets/images/favicon.png',
								tag: 'demo-notification',
								renotify: true,
								vibrate: [200, 100, 200, 100, 200, 100, 200],
								data: oUser
							};
							if (currentNotification) {
								const messageCount = currentNotification.data.newMessageCount + 1;
								notificationTitle = 'New Message';
								options.body = 'You have '+messageCount+' new messages';
								options.data.newMessageCount = messageCount;
							} else {
								notificationTitle = 'New Message';
								options.body = 'You have a new Message';
								options.data.newMessageCount = 1;
							}
							return registration.showNotification(notificationTitle, options);
						});
					});
				}
			});
		};

		navigator.serviceWorker.register('sw.js').then(function(reg){
			serviceWorker = reg;
			if (oUser) {
				if (!('Notification' in window)) {
					runAlertBox({type:'info', message: 'This browser does not support Notification Service.'});
				} else if (Notification.permission === 'granted') {
					runSampleNotif();
				} else {
					Notification.requestPermission().then(function (permission) {
						if (permission === "granted") {
							runSampleNotif();
						} else {
							runAlertBox({
								type:'info',
								message: 'Please enable Notification permission to use realtime messaging Service.<br>This can be reset in Page Info which can be accessed by clicking the lock icon next to the URL.', 
								unclose: true
							});
						}
					});
				}
			}
		}).catch(function(err) {
			console.log("Issue happened", err);
		});
	}
</script>