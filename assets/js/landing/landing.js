$(document).ready(function() {
	var img = ['1.jpg', '2.jpg', '3.jpg', '0.jpg'],
	phrase = ['feed their family.', 'dream big.', 'live for a day.', 'to produce more.'],
	sec = 4000,
	i = 0;

	setInterval(function() {
		$('#subphrase').hide();
		if (i < img.length) {
			$('#first').css({'background-image': 'url(assets/landing/images/'+img[i]+')'});
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
		console.log("Registered:", registration);
		let deferredPrompt;
		document.querySelector('.add-pwa').addEventListener('click', async () => {
			deferredPrompt.prompt();
			const { outcome } = await deferredPrompt.userChoice;
			deferredPrompt = null;
		});

		window.addEventListener('beforeinstallprompt', (e) => {
			e.preventDefault();
			deferredPrompt = e;
		});

		window.addEventListener('appinstalled', (e) => {
			deferredPrompt = null;
		});
	}).catch(function(err) {
		console.log("Issue happened:", err);
	});
}