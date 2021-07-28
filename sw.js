self.addEventListener('install', function(event) {
	console.log("installed");
});

self.addEventListener('activate', function(event) {
	console.log("activated");
});

self.addEventListener('fetch', function(event) {
	console.log("fetched", event.notification);
	/*event.respondWith(
		fetch(event.request).catch(function() {
			return caches.match(event.request);
		})
		);*/
	});

self.addEventListener('notificationclick', function(event) {
	console.log('On notification click: ', event.notification.tag, event);
	event.notification.close();
	/*This looks to see if the current is already open and*/
	/*focuses if it is*/
	/*event.waitUntil(clients.matchAll({
		type: "window"
	}).then(function(clientList) {
		for (var i = 0; i < clientList.length; i++) {
			var client = clientList[i];
			if (client.url == '/' && 'focus' in client)
				return client.focus();
		}
		if (clients.openWindow)
			return clients.openWindow('/');
	}));*/
});