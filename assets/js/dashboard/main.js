$(document).ready(function() {

	$('[js-target]').click(function() {
		var showMe = $(this).attr('js-target');
		$(this).parents('.dashboard-panel').remove();
		$('#'+showMe).toggleClass('hide');
	});

});