$(document).ready(function() {
	$('[js-event="add-set"]').on('click', function() {
		var uiParent = $(this).parents('[id*="farmlocation-"]').first();
		if ($(this).is(':checked')) {
			uiParent.next('[js-element="products-location-set"]').removeClass('hide');
			uiParent.next('[js-element="products-location-set"]').find('input, select').prop('required', true);
		} else {
			uiParent.next('[js-element="products-location-set"]').addClass('hide').find('input').prop('value', '').val('');
			uiParent.next('[js-element="products-location-set"]').find('input, select').prop('required', false);
		}
	});

	$('[js-event="add-set"]').each(function() {
		if ($(this).is(':checked')) {
			var uiParent = $(this).parents('[id*="farmlocation-"]').first();
			uiParent.next('[js-element="products-location-set"]').find('input, select').prop('required', true);
		}
	});

	$('[js-event="new-set"]').on('click', function() {
		var uiParent = $(this).parents('[id*="farmlocation-"]').first();
		if ($(this).is(':checked')) {
			uiParent.next('[js-element="pricing-panel"]').removeClass('hide');
			uiParent.next('[js-element="pricing-panel"]').find('input, select').prop('required', true);
		} else {
			uiParent.next('[js-element="pricing-panel"]').addClass('hide').find('input').prop('value', '').val('');
			uiParent.next('[js-element="pricing-panel"]').find('input, select').prop('required', false);
		}
	});

	$('[js-event="new-set"]').each(function() {
		if ($(this).is(':checked')) {
			var uiParent = $(this).parents('[id*="farmlocation-"]').first();
			uiParent.next('[js-element="pricing-panel"]').find('input, select').prop('required', true);
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
		$('tr[product_id='+data.id+']').fadeOut('fast', function() {
			$('#inventory_table').DataTable().row($(this)).remove().draw();
		});
	}
}