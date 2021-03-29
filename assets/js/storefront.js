$(document).ready(function() {

	$('.custom-item-btn').click(function() {
		$('#storefront_nav').find('div.custom-item-child').removeClass('active');
		$('.custom-item-btn').removeClass('active');
		$('.custom-item-btn').find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');

		$(this).addClass('active');

		$(this).next('div.custom-item-child').addClass('active');
		$(this).find('.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');
	});

});