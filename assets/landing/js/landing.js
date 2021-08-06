$(document).ready(function() {

	var img = ['1.jpg', '2.jpg', '3.jpg', '0.jpg'],
		phrase = ['feed their family.', 'dream big.', 'live for a day.', 'to produce more.'],
		sec = 4000,
		i = 0;

	setInterval(function() {
		$('#subphrase').hide();

		if (i < img.length) {
			$('#first').css({
				'background-image': 'url(./images/'+img[i]+')'
			});
			$('#subphrase').text(phrase[i]).fadeIn('fast');
	 		i++;
		}
	}, sec);

	setInterval(function(e) {
	 if (i == img.length) {
			i = 0;
		}
	}, sec);
});