$(document).ready(function() {
	$('[js-event="deliveryDate"]').click(function() {
		if ($(this).val() == "deliver_now") {
			$('input[name="delivery_date"]').attr('disabled', true).attr('value', '').val('');
		} else {
			$('input[name="delivery_date"]').removeAttr('disabled').prop('required', true);
		}
	});

	var oSavedData = {};
	$('[js-event="qty"]').each(function(i, elem) {
		oSavedData[i] = {
			ui: elem,
			min: parseInt($(elem).attr('min')),
			max: parseInt($(elem).attr('max')),
		}
	});

	$('[js-event="qty"]').bind('input', function() {
		var oThis = $(this);
		/*preventing changes done in console*/
		$.each(oSavedData, function(i, data) {
			// console.log(oThis.is(data.ui));
			if (oThis.is(data.ui)) {
				oThis.attr('min', data.min);
				oThis.attr('max', data.max);
				if (oThis.val() < data.min) {
					oThis.val(data.min);
				}
				if (oThis.val() > data.max) {
					oThis.val(data.max);
				}
			}
		});
	});
});