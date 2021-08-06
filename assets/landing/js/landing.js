$(document).ready(function() {

	var img = ['1.jpg', '2.jpg', '3.jpg', '0.jpg'],
		phrase = ['farmer\'s feed their family.', 'farmers dream big.', 'farmers live for a day.', 'farmers to produce more.'],
		sec = 4000,
		i = 0;

	setInterval(function(e) {
		if (i < img.length) {
			$('#first').css({
				'background-image': 'url(./images/'+img[i]+')'
			});
			$('#subphrase').fadeOut('fast').text(phrase[i]).fadeIn('slow');
	 		i++;
		}
	}, sec);

	setInterval(function(e) {
	 if (i == img.length) {
			i = 0;
		}
	}, sec);
});