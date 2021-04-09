$(document).ready(function() {
	$('[js-event="deliveryDate"]').click(function() {
		if ($(this).val() == "deliver_now") {
			$('input[name="delivery_date"]').attr('disabled', true);
		} else {
			$('input[name="delivery_date"]').removeAttr('disabled').prop('required', true);
		}
	});
});