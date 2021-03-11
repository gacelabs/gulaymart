$(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();

	$(document.body).on('click', '.resetpass-btn', function(e) {
		$('form.sign-in-form').addClass('hide');
		$('form.resetpass-form').removeClass('hide');
		$('.ask-sign-in').removeClass('hide');
		$('.fb-login-btn').addClass('hide');
		$('.login-detail').addClass('hide');
		$('.reset-detail').removeClass('hide');
	});

	$(document.body).on('click', '.ask-sign-in', function(e) {
		$('form.sign-in-form').removeClass('hide');
		$('form.resetpass-form').addClass('hide');
		$('.ask-sign-in').addClass('hide');
		$('.fb-login-btn').removeClass('hide');
		$('.login-detail').removeClass('hide');
		$('.reset-detail').addClass('hide');
	});

	$('form:not(.use-ajax)').on('submit', function(e) {
		var uiButtonSubmit = $(e.target).find('[type=submit]');
		if (uiButtonSubmit.length && $(e.target).find('.error').length == 0) {
			uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> Processing ...');
		}
	});
});

$(window).on('load resize change scroll', function(){
		var navSticky = $('#bottom_nav_sm').width(),
		winWidth = $(window).width();

	$('#bottom_nav_sm').css('left', (winWidth/2)-(navSticky/2)+'px');
})