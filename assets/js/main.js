$(document).ready(function() {

	var elem = $('#product_item_body').offset().top;

	$(window).scroll(function() {  
		if ($(window).scrollTop() > elem-20) {
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

	$('[data-toggle="tooltip"]').tooltip();

});

$(document).on('ready resize change', function() {
	$('.product-item-inner').masonry({
		itemSelector: '.product-item',
		masonry: {
			horizontalOrder: true
		},
	});
});