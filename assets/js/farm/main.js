$(document).ready(function() {
	$('[js-event="add-set"]').each(function(i, elem) {
		$(elem).unbind('click').bind('click', function() {
			var uiParent = $(this).parents('[id*="farmlocation-"]').first();
			if ($(this).is(':checked')) {
				$(this).attr('checked', 'checked');
				uiParent.next('[js-element="products-location-set"]').removeClass('hide');
				uiParent.next('[js-element="products-location-set"]').find('input, select').prop('required', true);
				// $('#price_btn_container').find('input:reset').trigger('click');
			} else {
				$(this).removeAttr('checked');
				uiParent.next('[js-element="products-location-set"]').addClass('hide').find('input')/*.prop('value', '').val('')*/;
				uiParent.next('[js-element="products-location-set"]').find('input, select').prop('required', false);
			}
			runFormValidation();
		});
	});

	$('[js-event="new-set"]').each(function(i, elem) {
		$(elem).unbind('click').bind('click', function() {
			var uiParent = $(this).parents('[id*="farmlocation-"]').first();
			if ($(this).is(':checked')) {
				$(this).attr('checked', 'checked');
				uiParent.next('[js-element="pricing-panel"]').removeClass('hide');
				uiParent.next('[js-element="pricing-panel"]').find('input, select').prop('required', true);
			} else {
				$(this).removeAttr('checked');
				uiParent.next('[js-element="pricing-panel"]').addClass('hide').find('input')/*.prop('value', '').val('')*/;
				uiParent.next('[js-element="pricing-panel"]').find('input, select').prop('required', false);
			}
			runFormValidation();
		});
	});
});

var oRemoveAjax = false;
var removeItem = function(obj, e) {
	// console.log(e)
	var oSettings = {
		url: 'farm/remove_veggy',
		type: 'post',
		data: obj,
		dataType: 'jsonp',
		beforeSend: function() {
			if ($('tr[product_id='+obj.id+']').length) {
				var oThis = $('tr[product_id='+obj.id+']').find('td:first a:last').clone();
				oThis.addClass('stop');
				if (obj.perm_delete == 0) {
					oThis.html('<span class="spinner-border spinner-border-sm"></span> Unpublishing...');
				} else {
					oThis.html('<span class="spinner-border spinner-border-sm"></span> Deactivating...');
				}
				$('tr[product_id='+obj.id+']').find('td:first').html(oThis);
			}
		},
		error: function(xhr, status, thrown) {
			if (thrown == 'Service Unavailable') {
				console.log('Debug called');
			} else {
				console.log(status, thrown);
			}
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

var removeEditBtn = function(data) {
	if (data && data.id) {
		if ($('tr[product_id='+data.id+']').length) {
			var deleteClone = $('tr[product_id='+data.id+']').find('td:first a:last').clone();
			$('tr[product_id='+data.id+'] td').eq(2).text('Unpublished');
			deleteClone.removeClass('stop').attr('href', (deleteClone.attr('href').replace(/\/+$/, '')+'/1')).text('Deactivate');
			$('tr[product_id='+data.id+']').find('td:first').html(deleteClone);
			runATagAjax();
		} else {
			console.log('unable to remove edit button');
		}
	}
}