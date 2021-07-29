
<script type="text/javascript">
	var realtime = false, serviceWorker, isSubscribed;
	window.initSendData = function() {
		realtime = new SendData({
			afterInit: function() {
				realtime.connect(function() {
					/*console.log('gulaymart.com ready to communicate!');*/
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
										oUser.url = 'https://gulaymart.com';
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
											notificationTitle = 'Demo';
											options.body = 'You have '+messageCount+' new messages';
											options.data.newMessageCount = messageCount;
										} else {
											notificationTitle = 'Demo';
											options.body = 'You have a new message';
											options.data.newMessageCount = 1;
										}
										return registration.showNotification(notificationTitle, options);
									});
								});
							}
						});
					};
					var onServiceWorkerReady = function(type, oData) {
						navigator.serviceWorker.ready.then(function(registration) {
							registration.update();
							registration.getNotifications({tag:oData.tag}).then(function(notifications) {
								let currentNotification;
								for(let i = 0; i < notifications.length; i++) {
									if (notifications[i].data && oUser.id == notifications[i].data.seller_id) {
										currentNotification = notifications[i];
									}
								}
								return currentNotification;
							}).then(function(currentNotification) {
								let notificationTitle = '';
								const options = oData;
								if (currentNotification) {
									const messageCount = currentNotification.data.newMessageCount + 1;
									notificationTitle = 'New Message';
									options.body = 'You have '+messageCount+' new '+type+'s';
									options.data.newMessageCount = messageCount;
								} else {
									notificationTitle = 'New Message';
									options.body = 'You have a new '+type;
									options.data.newMessageCount = 1;
								}
								return registration.showNotification(notificationTitle, options);
							});
						});
					}
					var runNotificationListeners = function() {
						realtime.bind('send-notification', 'fulfilled-items', function(object) {
							var oData = object.data;
							console.log(oData);
							onServiceWorkerReady('fulfillment', oData);
						});
						realtime.bind('send-notification', 'ordered-items', function(object) {
							var oData = object.data;
							console.log(oData);
							onServiceWorkerReady('order', oData);
						});
					};

					navigator.serviceWorker.register('sw.js').then(function(reg){
						serviceWorker = reg;
						/*serviceWorker.pushManager.getSubscription().then(function(subscription) {
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
						if (oUser) {
							/*if ('safari' in window) {
								if ('pushNotification' in window.safari) {
									runAlertBox({type:'info', message: 'This browser does not support Notification Service.'});
								} else {
									var checkRemotePermission = function (permissionData) {
										if (permissionData.permission === 'granted') {
										} else {
											window.safari.pushNotification.requestPermission(
												'https://gulaymart.com',
												'web.com.gulaymart',
												oUser,
												checkRemotePermission
											);
										}
									};
									var permissionData = window.safari.pushNotification.permission('web.com.gulaymart');
									checkRemotePermission(permissionData);
								}
							}*/
							if (!('Notification' in window)) {
								runAlertBox({type:'info', message: 'This browser does not support Notification Service.'});
							} else if (Notification.permission === 'granted') {
								runSampleNotif();
								runNotificationListeners();
							} else {
								Notification.requestPermission().then(function (permission) {
									if (permission === "granted") {
										runSampleNotif();
										runNotificationListeners();
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