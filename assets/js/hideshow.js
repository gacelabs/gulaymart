$(document).ready(function() {

	$('.hideshow-btn').click(function() {

		$('.hideshow-btn').removeClass('active');
		$(this).addClass('active');

		var target = $(this).attr('hideshow-target');

		$('.hideshow-container>div').addClass('hide');

		$('.hideshow-container').find('#'+target+'').removeClass('hide');

	});

});