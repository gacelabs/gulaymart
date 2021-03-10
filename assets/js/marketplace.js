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

});

$(document).on('ready resize change', function() {
	$('.product-item-inner').masonry({
		itemSelector: '.product-item',
		masonry: {
			horizontalOrder: true
		},
	});
});