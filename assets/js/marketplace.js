$(document).ready(function() {

	var elem = $('#navbar-carousel').offset().top;

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

	$('[data-category-group]').off('click').on('click', function(e) {
		var a = $(e.target);
		if (a.prop('tagName') != 'A') {
			a = a.parents('a');
		}
		var group = a.data('category-group');
		if (group != 'all') {
			$('[data-category]').hide();
			$('[data-category*="'+group+'"]').show();
			$('.product-item-inner').isotope({ filter: '[data-category*="'+group+'"]' });
		} else {
			$('[data-category]').show();
			$('.product-item-inner').isotope({ filter: '*' });
		}
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