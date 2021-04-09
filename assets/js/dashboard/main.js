$(document).ready(function() {

	$('[js-event="farmMenuTrigger"]').click(function() {
		$(this).toggleClass('active');
		$('[js-event="navbarFarmMenuContainer"]').toggleClass('active');
	});

});