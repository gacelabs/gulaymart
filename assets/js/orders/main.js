$(document).ready(function() {

	$('[js-event="showOrderFooter"]').click(function() {
		$(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
		$('.order-footer-farm, .order-footer-payment').toggleClass('hidden-xs');
	});

});
