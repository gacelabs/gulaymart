$(document).ready(function() {
	$('[js-event="priceTallyPullUp"]').click(function() {
		$(this).find('i').toggleClass('fa-chevron-right fa-chevron-left');
		$('.price-summary-middle, body.checkout, .order-summary-middle').toggleClass('active');
	});
});