$(document).ready(function() {

	var elem = $('#banner_container').offset().top;

	$(window).scroll(function() {  
		if ($(window).scrollTop() > elem) {
			$('#bottom_nav_sm').removeClass('hide');
		}
		else {
			$('#bottom_nav_sm').addClass('hide');
		}
	});

	$('.product-item-inner').masonry({
		itemSelector: '.product-item',
		masonry: {
			horizontalOrder: true
		},
	});

	$(document.body).on('click', '.resetpass-btn', function(e) {
		$('form.sign-in-form').addClass('hide');
		$('form.resetpass-form').removeClass('hide');
		$('.ask-sign-in').removeClass('hide');
		$('.fb-login-btn').addClass('hide');
		$('.login-detail').addClass('hide');
		$('.reset-detail').removeClass('hide');
	});

	$(document.body).on('click', '.ask-sign-in', function(e) {
		$('form.sign-in-form').removeClass('hide');
		$('form.resetpass-form').addClass('hide');
		$('.ask-sign-in').addClass('hide');
		$('.fb-login-btn').removeClass('hide');
		$('.login-detail').removeClass('hide');
		$('.reset-detail').addClass('hide');
	});

});

$(document).on('ready resize change', function() {
	$('.product-item-inner').masonry({
		itemSelector: '.product-item',
		masonry: {
			horizontalOrder: true
		},
	});
});