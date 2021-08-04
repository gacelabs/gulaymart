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
	/*console.log('on notification click: ', event);*/
	event.notification.close();
	event.waitUntil(event.target.clients.matchAll({
		type: "window"
	}).then(function(clientList) {
		if (oData) {
			for (var i = 0; i < clientList.length; i++) {
				var client = clientList[i];
				if ('focus' in client) {
					client.focus();
					setTimeout(function() {
						client.navigate(oData.url);
					}, 1);
					return;
				}
			}
			if (event.target.clients.openWindow && oData.url) {
				return event.waitUntil(event.target.clients.openWindow(oData.url));
			}
		}
	}));
});