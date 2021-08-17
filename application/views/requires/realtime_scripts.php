
<script type="text/javascript" async crossorigin="anonymous">
	var realtime = false, serviceWorker, isSubscribed, favicon = {badge: function() {}};
	let iLocalMessageCount = '';
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
					$('#is-connected').removeAttr('class').addClass('text-success fa fa-link').attr('title', 'connected');
				} else {
					$('#is-connected').removeAttr('class').addClass('text-danger fa fa-chain-broken').attr('title', 'disconnected');
				}
				if (oUser) {
					favicon = new Favico({animation : 'none'});
					if (badge > 0) {
						favicon.badge(badge == 0 ? '' : badge);
					}
					/*communicate from orders cycle*/
					if (typeof fetchOrderCycles == 'function' && $.inArray(oSegments[1], ['admin']) < 0) {
						realtime.bind('order-cycle', 'incoming-gm-process', function(object) {
							var oData = object.data;
							// console.log(oData);
							fetchOrderCycles(oData);
						});
						// fetchOrderCycles({merge_id: [47,48]});
					}
					if ($.inArray(oSegments[1], ['admin']) >= 0 && $.inArray(oSegments[2], ['bookings']) >= 0) {
						realtime.bind('booking-log', 'incoming-gm-logs', function(object) {
							var oData = object.data;
							// console.log(oData);
							var today = new Date();
							simpleAjax('admin/bookings/UpdatedLogs', {
								date: $.format.date(today, "yyyy-MM-dd"),
								name: oData.type
							}, false, true, true);
						});
					}
				}
				realtime.bind('info-updates', 'incoming-gm-infos', function(object) {
					var oData = object.data;
					console.log(oData);
				});
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

	var onServiceWorkerReady = function(oData) {
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.ready.then(function(registration) {
				registration.update();
				registration.getNotifications({tag:oData.tag}).then(function(notifications) {
					let currentNotification = false;
					if (notifications.length) {
						var total = notifications.length - 1;
						if (notifications[total] != undefined) {
							if (notifications[total].data && oUser.id == notifications[total].data.id) {
								currentNotification = notifications[total];
							}
						}
						/*clear all except the last*/
						for (let i = 0; i < total; i++) {
							if (notifications[i].data && oUser.id == notifications[i].data.id) {
								notifications[i].close();
							}
						}
					}
					return currentNotification;
				}).then(function(currentNotification) {
					const oOptions = oData;
					let bNotify = false;
					let iMessageCount = '';
					if (oOptions.data && oUser.id == oOptions.data.id) {
						bNotify = true;
						let sType = oOptions.data.type;
						iMessageCount = oOptions.data.count;
						iMessageCount = iMessageCount == 0 ? '' : iMessageCount;
						oOptions.data.newMessageCount = iMessageCount;
						if (iMessageCount > 1) {
							oOptions.body = 'You have '+iMessageCount+' new '+sType+'s';
						} else if (typeof iMessageCount == 'number') {
							oOptions.body = 'You have a new '+sType;
						}
						if (oOptions.body.length == 0) bNotify = false;
					}
					// console.log(bNotify, oOptions);
					if (bNotify) {
						badge = iMessageCount;
						favicon.badge(iMessageCount == 0 ? '' : iMessageCount);
						return registration.showNotification('New Message', oOptions);
					}
				});
			});
		} else {
			const localOptions = oData;
			let localNotify = false;
			if (localOptions.data && oUser.id == localOptions.data.id) {
				localNotify = true;
				iLocalMessageCount = localOptions.data.count;
				iLocalMessageCount = iLocalMessageCount == 0 ? '' : iLocalMessageCount;
				if (iLocalMessageCount > 1) {
					localOptions.body = 'You have '+iLocalMessageCount+' new messages.';
				} else if (typeof iLocalMessageCount == 'number') {
					localOptions.body = 'You have a new message.';
				}
				if (localOptions.body.length == 0) localNotify = false;
			}
			if (localNotify) {
				badge = iLocalMessageCount;
				favicon.badge(iLocalMessageCount == 0 ? '' : iLocalMessageCount);
				runAlertBox({type:'success', message: localOptions.body});
			}
		}
	};

	var runSampleNotif = function() {
		$('#install-app').bind('click', function() {
			if (oUser && oUser.is_admin) simpleAjax('test/msg/test-notification'+iLocalMessageCount);
		});
	};

	var runNotificationListeners = function() {
		var i = setInterval(function() {
			if (realtime != false && $.inArray(oSegments[1], ['admin']) < 0) {
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
		if (oUser) {
			runNotificationListeners();
			runSampleNotif();
		}
	}
</script>