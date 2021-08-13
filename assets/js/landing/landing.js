$(document).ready(function() {
	var img = ['1.jpg', '2.jpg', '3.jpg', '0.jpg'],
	phrase = ['feed their family.', 'dream big.', 'live for a day.', 'to produce more.'],
	sec = 4000,
	i = 0;
	setInterval(function() {
		$('#subphrase').hide();
		if (i < img.length) {
			$('#first').css({'background-image': 'url(assets/images/landing/'+img[i]+')'});
			$('#subphrase').text(phrase[i]).fadeIn('fast');
			++i;
		}
	}, sec);
	setInterval(function(e) {
		if (i == img.length) i = 0;
	}, sec);
});

if ('serviceWorker' in navigator) {
	navigator.serviceWorker.register('sw.js').then(function(registration){
		registration.update();
		// console.log("Registered:", registration);
		document.querySelectorAll('.add-pwa').forEach(function(elem, i) {
			// console.log(elem, i);
			let deferredPrompt;
			elem.addEventListener('click', async (e) => {
				if (deferredPrompt != undefined) {
					deferredPrompt.prompt();
					const { outcome } = await deferredPrompt.userChoice;
					console.log(outcome);
				} else {
					document.querySelectorAll('.add-pwa').forEach(function(el, j) {
						el.innerText = 'GO TO HOME PAGE';
						el.removeEventListener('click', () => {
							window.location = MAIN_URL;
						});
					});
					elem.removeEventListener('click', () => {
						window.location = MAIN_URL;
					});
					alert('App already added to home page.');
				}
				console.log(deferredPrompt, registration);
				// deferredPrompt = null;
			});

			window.addEventListener('beforeinstallprompt', (e) => {
				e.preventDefault();
				deferredPrompt = e;
				console.log(e, registration);
			});

			window.addEventListener('appinstalled', (e) => {
				console.log(e, deferredPrompt, registration);
				deferredPrompt = null;
				// window.location = MAIN_URL;
				// registration.getNotifications().then(function(notifications) {
				// 	let currentNotification = false;
				// 	if (notifications.length) {
				// 		var total = notifications.length - 1;
				// 		if (notifications[total] != undefined) {
				// 			currentNotification = notifications[total];
				// 		}
				// 		/*clear all except the last*/
				// 		for (let i = 0; i < total; i++) {
				// 			if (notifications[i].data && oUser.id == notifications[i].data.id) {
				// 				notifications[i].close();
				// 			}
				// 		}
				// 	}
				// 	return currentNotification;
				// }).then(function(currentNotification) {
				// 	if (currentNotification) currentNotification.click();
				// });
			});
		});
	}).catch(function(err) {
		console.log("Issue happened:", err);
	});
}