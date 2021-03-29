$(document).ready(function() {

	$('.custom-item-btn').click(function() {
		$('#storefront_nav').find('div.custom-item-child').removeClass('active');
		$('.custom-item-btn').removeClass('active');

		$(this).addClass('active');

		$(this).next('div.custom-item-child').addClass('active');
	});

});