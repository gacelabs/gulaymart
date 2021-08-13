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
		if (window.localStorage.getItem('appinstalled') == null) {
			// console.log("Registered:", registration);
			document.querySelectorAll('.add-pwa').forEach(function(elem, i) {
				// console.log(elem, i);
				let deferredPrompt;
				elem.addEventListener('click', async (e) => {
					if (deferredPrompt != undefined) {
						deferredPrompt.prompt();
						const { outcome } = await deferredPrompt.userChoice;
						console.log(outcome);
					}
					console.log(deferredPrompt, registration);
					deferredPrompt = null;
				});

				window.addEventListener('beforeinstallprompt', (e) => {
					e.preventDefault();
					deferredPrompt = e;
					console.log(e, registration);
				});

				window.addEventListener('appinstalled', (e) => {
					console.log(e, deferredPrompt, registration);
					deferredPrompt = null;
					window.localStorage.setItem('appinstalled', 1);
				});
			});
		} else {
			document.querySelectorAll('p').forEach(function(elem, i) {
				elem.remove();
			});
			document.querySelectorAll('.add-pwa').forEach(function(elem, i) {
				elem.innerText = 'GO TO HOME PAGE';
				elem.addEventListener('click', async (e) => {
					window.location = MAIN_URL;
				});
			});
		}
	}).catch(function(err) {
		console.log("Issue happened:", err);
	});
}