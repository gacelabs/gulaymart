$(document).ready(function() {
	runDomShowHide();
});

var runDomShowHide = function() {
	$('.hideshow-btn').off('click').on('click', function(e) {
		var oThis = $(e.target);
		if ($(e.target).prop('tagName') == 'KBD') oThis = oThis.parent();
		var target = oThis.attr('hideshow-target');
		// console.log(target);
		$('.hideshow-btn').removeClass('active');
		oThis.addClass('active');
		$('[element-name="notifications"]').addClass('hide');
		if ($('.hideshow-container').find('.no-records-ui:last').is('visible') == false) {
			$('.hideshow-container').find(target).removeClass('hide');
		}
	});
}