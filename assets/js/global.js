$(document).ready(function() {
	$('button.stop, a.stop, input.stop, [type="submit"].stop, select.stop').click(function(e) {
		e.preventDefault();
		$("select.stop").prop("disabled", true);
		$(this).blur();
		return false;
	});
});