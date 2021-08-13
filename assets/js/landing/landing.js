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
		let deferredPrompt;
		window.addEventListener('beforeinstallprompt', (e) => {
			e.preventDefault();
			deferredPrompt = e;
		});

		window.addEventListener('appinstalled', (e) => {
			console.log(e, deferredPrompt, registration);
		});

		document.querySelectorAll('.add-pwa').forEach(function(elem, i) {
			elem.addEventListener('click', async (e) => {
				if (deferredPrompt != undefined) {
					deferredPrompt.prompt();
					const { outcome } = await deferredPrompt.userChoice;
					registration.waiting = true;
					if (outcome == 'accepted') {
						registration.installing = true;
						registration.waiting = false;
					} else {
						registration.installing = false;
						registration.waiting = null;
					}
					console.log(outcome, deferredPrompt, registration);
				} else {
					elem.innerText = 'GO TO HOME PAGE';
					elem.addEventListener('click', async (e) => {
						window.location = MAIN_URL;
					});
					document.querySelectorAll('p').forEach(function(elem, i) {
						elem.innerText = 'App already installed!';
					});
					console.log(deferredPrompt, registration);
				}
			});
		});
	}).catch(function(err) {
		console.log("Issue happened:", err);
	});
}