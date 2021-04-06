$(document).ready(function() {
	$('[js-event="add-set"]').on('click', function() {
		var uiParent = $(this).parents('[js-element="address-panel"]').first();
		if ($(this).is(':checked')) {
			uiParent.next('[js-element="products-location-set"]').removeClass('hide');
		} else {
			uiParent.next('[js-element="products-location-set"]').addClass('hide');
		}
	});
});

var oRemoveAjax = false;
var removeItem = function(id) {
	var oSettings = {
		url: 'farm/remove_veggy',
		type: 'post',
		data: {id: id},
		dataType: 'jsonp',
		error: function(xhr, status, thrown) {
			console.log(status, thrown);
		}
	};
	if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
	oRemoveAjax = $.ajax(oSettings);
}

var removeOnTable = function(data) {
	if (data && data.id) {
		$('tr[product_id='+data.id+']').fadeOut();
	}
}