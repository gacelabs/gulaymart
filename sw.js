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
	let clients = event.target.clients;
	event.waitUntil(clients.matchAll({
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
						client.navigate(client.url);
					}, 1000);
					return;
				}
			}		
		}
		if (clients.openWindow) {
			/*console.log('no focus', clients);*/
			return setTimeout(function() {
				clients.openWindow(oData.url);
			}, 1000);
		}
	}));
});