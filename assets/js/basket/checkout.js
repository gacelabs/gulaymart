$(document).ready(function() {
	$('[js-event="priceTallyPullUp"]').click(function() {
		$(this).find('i').toggleClass('fa-chevron-up fa-chevron-down')
		$('.price-summary-middle, body.checkout, .order-summary-middle').toggleClass('active');
	});
});