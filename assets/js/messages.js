$(document).ready(function() {
	$readMoreJS.init({
		target: '.notif-item-middle p',
		numOfWords: 15,
		toggle: true,
		moreLink: 'Read More',
		lessLink: 'Show Less'
	});

	$('.rm-link').click(function() {
		$(this).parents('.notif-item-middle').next('.notif-item-footer').removeClass('hide');
	});
});