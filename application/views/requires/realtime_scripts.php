
<script type="text/javascript">
	var realtime = false, serviceWorker, isSubscribed;
	window.initSendData = function() {
		realtime = new SendData({
			// debug: true,
			autoConnect: IS_LOCAL ? false : true,
			autoRunStash: true,
			afterInit: function() {
				if (IS_LOCAL) realtime.connect();
			},
			afterConnect: function() {
				if (realtime.app.connected) {
					$('#is-connected').removeAttr('class').addClass('text-success fa fa-link');
				} else {
					$('#is-connected').removeAttr('class').addClass('text-danger fa fa-chain-broken');
				}
				if (oUser) {
					/*communicate from orders cycle*/
					if (typeof fetchOrderCycles == 'function' && $.inArray(oSegments[1], ['','marketplace']) < 0) {
						realtime.bind('order-cycle', 'incoming-gm-process', function(object) {
							var oData = object.data;
							// console.log(oData);
							fetchOrderCycles(oData);
						});
						// fetchOrderCycles({merge_id: [47,48]});
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

	let iLocalMessageCount = '';
	var onServiceWorkerReady = function(oData) {
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.ready.then(function(registration) {
				registration.update();
				registration.getNotifications({tag:oData.tag}).then(function(notifications) {
					let currentNotification = false;
					for (let i = 0; i < notifications.length; i++) {
						if (notifications[i].data && oUser.id == notifications[i].data.id) {
							currentNotification = notifications[i];
						}
					}
					return currentNotification;
				}).then(function(currentNotification) {
					const oOptions = oData;
					let bNotify = false;
					if (oOptions.data && oUser.id == oOptions.data.id) {
						bNotify = true;
						let iMessageCount = '';
						let sType = oOptions.data.type;
						if (currentNotification) {
							iMessageCount = currentNotification.data.newMessageCount + 1;
							oOptions.data.newMessageCount = iMessageCount;
							oOptions.body = 'You have '+iMessageCount+' new '+sType+'s';
						} else {
							oOptions.data.newMessageCount = 1;
							oOptions.body = 'You have a new '+sType;
						}
					}
					// console.log(bNotify, oOptions);
					if (bNotify) {
						return registration.showNotification('New Message', oOptions);
					}
				});
			});
		} else {
			const localOptions = oData;
			let localNotify = false;
			if (localOptions.data && oUser.id == localOptions.data.id) {
				localNotify = true;
				if (typeof iLocalMessageCount == 'number') {
					iLocalMessageCount += 1;
					localOptions.body = 'You have '+iLocalMessageCount+' new messages.';
				} else {
					iLocalMessageCount = 1;
					localOptions.body = 'You have a new message.';
				}
			}
			if (localNotify) {
				runAlertBox({type:'success', message: localOptions.body});
			}
		}
	};

	var runSampleNotif = function() {
		$('#install-app').bind('click', function() {
			if (oUser && oUser.is_admin) {
				var oData = {
					id: oUser.id,
					tag: 'notif:test-id:notification',
					url: window.location.protocol + '//' + window.location.hostname + '/orders/messages/',
					type: 'test message'
				};
				simpleAjax('test/msg', {ajax_complete: function() {
					realtime.trigger('gm-push-notification', 'notifications', {
						badge: 'https://gulaymart.com/assets/images/favicon.png',
						body: '',
						icon: 'https://gulaymart.com/assets/images/favicon.png',
						tag: 'notif:test-id:notification',
						renotify: true,
						vibrate: [200, 100, 200, 100, 200, 100, 200],
						data: oData
					});
				}});
			}
		});
	};

	var runNotificationListeners = function() {
		var i = setInterval(function() {
			if (realtime != false) {
				clearInterval(i);
				setTimeout(function() {
					realtime.bind('gm-push-notification', 'notifications', function(object) {
						var oData = object.data;
						// console.log(oData);
						onServiceWorkerReady(oData);
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
								message: 'Please enable Notification permission to use realtime messaging Service.<br>This can be reset in Page Info which can be accessed by clicking the lock icon next to the URL.'/*, 
								unclose: true*/
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