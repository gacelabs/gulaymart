$(document).ready(function() {
	$('[js-event="priceTallyPullUp"]').click(function() {
		$(this).find('i').toggleClass('fa-angle-right fa-angle-left');
		$('.price-summary-middle, body.checkout, .order-summary-middle').toggleClass('active');
	});
});