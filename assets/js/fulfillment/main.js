$(document).ready(function() {

	$('[js-event="actionSelect"]').change(function() {
		var actionVal = $(this).val();
		if (actionVal == "5") {
			$(this).next('[js-event="reasonSelect"]').removeClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#ff7575');
		} else {
			$(this).next('[js-event="reasonSelect"]').addClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#799938');
		}
	});

	$('[js-event="actionApplyBtn"]').click(function() {
		var actionVal = $('[js-event="actionSelect"]').val(),
			cancelVal = $('[js-event="cancelReasonSelect"]').val();
		if (actionVal == "5" && !$.isNumeric(cancelVal)) {
			$('[js-event="cancelReasonSelect"]').addClass('error');
		}
	});

	$('[js-event="cancelReasonSelect"]').change(function() {
		if ($.isNumeric($(this).val())) {
			$(this).removeClass('error');
		}
	});

	$('[js-event="moreInfoBtn"]').click(function(e) {
		var thisParent = $(this).parents('.ff-item-container');

		$('.ff-item-container').removeClass('active').css('filter','blur(2px)');

		$(thisParent).addClass('active').css('filter','blur(0)');

		$('input[type="checkbox"]').prop('checked', false);

		$(thisParent).find('input[type="checkbox"]').prop('checked', true);
	});

	$('[data-dismiss="modal"]').click(function(e) {
		$('.ff-item-container').removeClass('active').css('filter','blur(0)');
		$('#more_info_container').removeClass('active');
	});

	$('#ff_invoice_modal').on('hidden.bs.modal', function () {
		$('.ff-item-container').removeClass('active').css('filter','blur(0)');
		$('#more_info_container').removeClass('active');
	});

	$('[js-event="checkAll"]').on('change', function() {
		var thisParent = $(this).parents('.ff-item-container');

		if ($(this).is(':checked')) {
			$(thisParent).find('input[type="checkbox"]').prop('checked', true);
		} else {
			$(thisParent).find('input[type="checkbox"]').prop('checked', false);
		}
	})

});