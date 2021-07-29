self.addEventListener('install', function(event) {
	console.log("installed");
	// event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', function(event) {
	console.log("activated");
	// event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', function(event) {
	console.log("fetched");
	/*event.respondWith(
		fetch(event.request).catch(function() {
			return caches.match(event.request);
		})
	);*/
});

self.addEventListener('notificationclick', function(event) {
	let oData = event.notification.data;
	console.log('on notification click: ', event);
	event.notification.close();
	/*This looks to see if the current is already open and*/
	/*focuses if it is*/
	event.waitUntil(event.target.clients.matchAll({
		type: "window"
	}).then(function(clientList) {
		if (oData) {
			/*console.log('list', clientList);*/
			for (var i = 0; i < clientList.length; i++) {
				var client = clientList[i];
				if (client.visibilityState == 'visible' && 'focus' in client) {
					/*console.log('has focus', client);*/
					client.focus();
					setTimeout(function() {
						client.navigate(oData.url);
					}, 1000);
					return;
				}
			}		
		}
		/*if (event.target.clients.openWindow) {
			return setTimeout(function() {
				event.target.clients.openWindow(oData.url);
			}, 1000);
		}*/
	}));
});