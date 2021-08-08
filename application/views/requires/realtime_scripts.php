
<script type="text/javascript">
	var realtime = false, serviceWorker, isSubscribed;
	window.initSendData = function() {
		realtime = new SendData({
			// debug: true,
			autoConnect: IS_LOCAL ? false : true,
			autoRunStash: true,
			afterInit: function() {
				if (IS_LOCAL && '<?php echo SENDDATA_APPKEY;?>' == 'A3193CF4AEC1ADD05F4B78C4E0C61C39') {
					realtime.connect();
				}
			},
			afterConnect: function() {
				if (realtime.app.connected) {
					$('#is-connected').removeAttr('class').addClass('text-success fa fa-link');
				} else {
					$('#is-connected').removeAttr('class').addClass('text-danger fa fa-chain-broken');
				}
				if (oUser) {
					/*communicate from orders tab to fulfillment tab*/
					if (typeof runOrdersToFulfillments == 'function') runOrdersToFulfillments(realtime);
					/*communicate from fulfillment tab to orders tab*/
					// if (typeof runFulfillmentsToOrders == 'function') runFulfillmentsToOrders(realtime);
					/*communicate from operators booking page*/
					// if (typeof runOperatorBookings == 'function') runOperatorBookings(realtime);
					
					/*listen for incomming on delivery fulfillments*/
					if (typeof fulfillmentProcess == 'function' && oSegments[1] == 'fulfillment') {
						fulfillmentProcess(runFulfillments);
					}
					/*listen for incomming on delivery orders*/
					if (typeof runOrders == 'function' && oSegments[1] == 'orders') {
						orderProcess(runOrders);
					}
					/*listen for incomming on baskets*/
					if (typeof basketProcess == 'function' && oSegments[1] == 'basket') {
						basketProcess(runBaskets);
					}

					/*listen for incomming on menu counts*/
					if (typeof initMenuNavsCount == 'function' && $.inArray(oSegments[1], ['','marketplace']) < 0) {
						initMenuNavsCount();
					}

					/*listen for incomming on tab counts*/
					if (typeof initStatusTabsCount == 'function' && $.inArray(oSegments[1], ['','marketplace']) < 0) {
						initStatusTabsCount();
					}
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

	if ('serviceWorker' in navigator) {
		var onServiceWorkerReady = function(type, oData) {
			navigator.serviceWorker.ready.then(function(registration) {
				registration.update();
				registration.getNotifications({tag:oData.tag}).then(function(notifications) {
					let currentNotification = false;
					console.log(notifications);
					for(let i = 0; i < notifications.length; i++) {
						if (notifications[i].data && oUser.id == notifications[i].data.seller_id) {
							currentNotification = notifications[i];
						}
					}
					return currentNotification;
				}).then(function(currentNotification) {
					let notificationTitle = '';
					const options = oData;
					console.log(currentNotification);
					if (currentNotification) {
						if (currentNotification.data.newMessageCount == undefined || currentNotification.data.newMessageCount == 0) {
							notificationTitle = 'New Message';
							options.body = 'You have a new '+type;
							options.data.newMessageCount = 1;
						} else {
							const messageCount = currentNotification.data.newMessageCount + 1;
							notificationTitle = 'New Message';
							options.body = 'You have '+messageCount+' new '+type+'s';
							options.data.newMessageCount = messageCount;
						}
						return registration.showNotification(notificationTitle, options);
					} else {
						console.log('!notify');
					}
				});
			});
		};
	}

	var runSampleNotif = function() {
		$('#install-app').bind('click', function() {
			if (oUser) {
				var oData = {
					seller_id: oUser.id,
					tag: 'demo-notification',
					url: window.location.protocol + '//' + window.location.hostname + '/orders/messages/'
				};
				if ('serviceWorker' in navigator) {
					onServiceWorkerReady('order', {
						badge: 'https://gulaymart.com/assets/images/favicon.png',
						body: '',
						icon: 'https://gulaymart.com/assets/images/favicon.png',
						tag: 'demo-notification',
						renotify: true,
						vibrate: [200, 100, 200, 100, 200, 100, 200],
						data: oData
					});
				} else {
					realtime.trigger('ordered-notification', 'send-notification', {
						badge: 'https://gulaymart.com/assets/images/favicon.png',
						body: '',
						icon: 'https://gulaymart.com/assets/images/favicon.png',
						tag: 'demo-notification',
						renotify: true,
						vibrate: [200, 100, 200, 100, 200, 100, 200],
						data: oData
					});
				}
			}
		});
	};
	var runNotificationListeners = function() {
		var i = setInterval(function() {
			if (realtime != false) {
				clearInterval(i);
				setTimeout(function() {
					if (realtime.app.connected) {
						$('#is-connected').removeAttr('class').addClass('text-success fa fa-link');
					} else {
						$('#is-connected').removeAttr('class').addClass('text-danger fa fa-chain-broken');
					}
					realtime.bind('fulfilled-notification', 'send-notification', function(object) {
						var oData = object.data;
						// console.log(oData);
						if ('serviceWorker' in navigator) {
							onServiceWorkerReady('fulfillment', oData);
						}
					});
					realtime.bind('ordered-notification', 'send-notification', function(object) {
						var oData = object.data;
						// console.log(oData);
						if ('serviceWorker' in navigator) {
							onServiceWorkerReady('order', oData);
						}
					});
				}, 300);
			}
		}, 1000);
	};
	if ('serviceWorker' in navigator) {
		navigator.serviceWorker.register('sw.js').then(function(reg){
			serviceWorker = reg;
			if (oUser) {
				if (!('Notification' in window)) {
					runAlertBox({type:'info', message: 'This browser does not support Notification Service.'});
				} else if (Notification.permission === 'granted') {
					runNotificationListeners();
					runSampleNotif();
				} else {
					Notification.requestPermission().then(function (permission) {
						if (permission === "granted") {
							runNotificationListeners();
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
	} else {
		runNotificationListeners();
		runSampleNotif();
	}
</script>