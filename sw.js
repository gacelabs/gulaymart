self.addEventListener('install', function(event) {
	console.log("installed");
});

self.addEventListener('activate', function(event) {
	console.log("activated");
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
	console.log('on notification click: ', event);
	let oData = event.notification.data;
	event.notification.close();
	/*This looks to see if the current is already open and*/
	/*focuses if it is*/
	event.waitUntil(clients.matchAll({
		type: "window"
	}).then(function(clientList) {
		if (oData) {
			/*console.log('list', clientList);*/
			for (var i = 0; i < clientList.length; i++) {
				var client = clientList[i];
				if (client.url == oData.url && 'focus' in client) {
				    /*console.log('has focus', client);*/
					client.focus();
	                client.navigate(client.url);
					return;
				}
			}		
		}
		if (clients.openWindow) {
			/*console.log('no focus', clients);*/
			return clients.openWindow(oData.url);
		}
	}));
});