$(document).ready(function() {

	$('[js-event="farmMenuTrigger"]').click(function() {
		$(this).toggleClass('active');
		$('[js-event="navbarFarmMenuContainer"]').toggleClass('active');
	});

	$('[js-target]').click(function() {
		var showMe = $(this).attr('js-target');

		$(this).parents('.dashboard-panel').remove();

		$('#'+showMe).toggleClass('hide');
	});

});