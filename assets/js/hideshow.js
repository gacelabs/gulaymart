$(document).ready(function() {
	$('.hideshow-btn').click(function() {
		var target = $(this).attr('hideshow-target');
			$('.hideshow-btn').removeClass('active');
			$(this).addClass('active');
			$('.hideshow-container>div').addClass('hide');
			$('.hideshow-container').find(target).removeClass('hide');
	});
});