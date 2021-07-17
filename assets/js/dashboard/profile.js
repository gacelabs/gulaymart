var isSucceed = false, timeout = null;
$(document).ready(function() {
	$('.email-copy').bind('click', function(e) {
		var oThis = $(e.target);
		if (oThis.prop('tagName') != 'DIV') oThis = $(e.target).parent('.email-copy');
		if (isSucceed == false) {
			isSucceed = copyToClipboard(oThis.find('input').attr('placeholder'));
		}
		setTimeout(function() {
			oThis.next('.tooltip').fadeOut('fast', function() {
				$(this).remove();
				oThis.removeAttr('aria-describedby').trigger('click');
				isSucceed = false;
			});
		}, 300);
	});

	$('.edit-shp-btn').bind('click', function(e) {
		var oThis = $(e.target);
		if (Object.keys(oThis.data('json')).length) {
			var oJson = $.parseJSON(oThis.attr('data-json'));
			var oLatLong = {'lat': parseFloat(oJson.lat), 'lng': parseFloat(oJson.lng)};
			// console.log(oLatLong, oJson);

			$('#lat').attr('value', parseFloat(oJson.lat)).val(parseFloat(oJson.lat));
			$('#lng').attr('value', parseFloat(oJson.lng)).val(parseFloat(oJson.lng));
			$('#address_1').attr('value', oJson.address_1).val(oJson.address_1);
			$('#address_2').attr('value', oJson.address_2).val(oJson.address_2);
			savedAddress1 = oJson.address_1;
			savedAddress2 = oJson.address_2;
			savedLatLng = oLatLong;

			$('#shipping-id').remove();
			$('#shipping-form').prepend('<input type="hidden" name="id" id="shipping-id" value="'+oJson.id+'">');
			
			resetMap(oLatLong, false);
		}
	});

	var oPauseInputAjax = false;
	$('input[name="active"][data-ajax]').off('change').on('change', function(e) {
		e.preventDefault();
		var oData = $(e.target).data('json') != undefined ? $(e.target).data('json') : {};
		var oSettings = {
			url: $(e.target).data('url'),
			type: 'get',
			data: oData,
			error: function(xhr, status, thrown) {
				console.log(xhr, status, thrown);
			}
		};
		if (oPauseInputAjax != false && oPauseInputAjax.readyState !== 4) oPauseInputAjax.abort();
		oPauseInputAjax = $.ajax(oSettings);
	});

	setTimeout(function() { $('#search-place').attr('autocomplete', 'input'); }, 300);

	$('#toktok-active').off('change').on('change', function(e) {
		e.preventDefault();
		$('[name=referral_code]').removeClass('error');
		if (this.checked) {
			$('[name=referral_code]').removeAttr('readonly');
			$('[js-element*="riders"]').find('input').removeAttr('readonly');
			$('[js-element*="riders"]').find('[js-element="action"]:not([js-event="trash"])').removeAttr('disabled');
		} else {
			$('[name=referral_code]').attr('readonly','readonly');
			$('[js-element*="riders"]').find('input').attr('readonly','readonly');
			$('[js-element*="riders"]').find('[js-element="action"]:not([js-event="trash"])').attr('disabled','disabled');
		}
	});

	runAddRiders();

	var isChecked = $('#toktok-active').is(':checked');
	$('#operator_form').find('[js-element="reset"]').bind('click', function(e) {
		var nowIsChecked = $('#toktok-active').is(':checked');
		$('#operator_form').find('[js-element="riders"]').each(function(i, elem) {
			var blankCount = 0;
			$(elem).find('input:text').each(function(x, input) {
				if (input.value == '') blankCount++;
				if (blankCount == $(elem).find('input:text').length) {
					$(elem).find('input:text').prop('value', '').val('');
					// console.log($(elem).find('input:text'))
					var parent = $(input).parents('[js-element="riders"]:first');
					parent.prev().find('[js-event="toggle-off"]')
						.attr({'js-event':'toggle-on', 'data-orig-ui':'<i class="fa fa-toggle-on"></i>'})
						.removeAttr('data-toggle')
						.removeAttr('data-placement')
						.removeAttr('data-original-title')
					parent.prev().find('[js-event="toggle-on"]').find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
					parent.remove();
				}
			});
		});
		$('#operator_form').get(0).reset();
		// console.log(isChecked, nowIsChecked)
		if ((isChecked && nowIsChecked == false) || (isChecked == false && nowIsChecked)) {
			$('#toktok-active').trigger('change');
		}
	});

	$('[href="sign-out"]').bind('click', function(e) {
		e.preventDefault();
		var oThis = $(e.target);
		if (oThis.prop('tagName') != 'A') oThis = $(e.target).parents('a');
		FB.logout();
		setTimeout(function() {
			// window.location = oThis.attr('href');
		}, 300);
	});
});

function updateSavedObjects(data) {
	// console.log(data);
	if (typeof data == 'object') {
		savedAddress1 = data.address_1;
		savedAddress2 = data.address_2;
		savedLatLng = {'lat': parseFloat(data.lat), 'lng': parseFloat(data.lng)};
		if (data.id && $('#shipping-item-'+data.id).length) {
			$('#shipping-item-'+data.id).find('.address_1').text(savedAddress1);
			$('#shipping-item-'+data.id).find('.address_2').text(savedAddress2);
			$('#shipping-item-'+data.id).find('.edit-shp-btn').attr('data-json', JSON.stringify(data));
			$('#shipping-item-'+data.id).find().prop('checked', (data.active ? true : false));
		}
	}
}

var runAddRiders = function() {
	$('[js-element="action"]').off('click').on('click', function(e) {
		e.preventDefault();
		var oThis = $(this),
			action = oThis.attr('js-event'),
			parent = oThis.parents('[js-element*="riders"]:first');
		
		parent.find('input').removeClass('error');

		if (oThis.prop('tagName') != 'BUTTON') oThis = oThis.parent('[js-element="action"]');

		if (action == 'plus') {
			var uiClone = $('[js-template="riders"]').clone(true);
			uiClone.find('input:not([js-name="active"])').val('');
			uiClone.attr('js-element', 'riders').removeAttr('js-template').removeClass('hide');
			var iCount = $('[js-element="riders"]').length;
			uiClone.find('input').each(function(i, elem) {
				var name = $(elem).attr('js-name');
				$(elem).attr('name', 'riders['+iCount+']['+name+']');
			});

			uiClone.insertBefore($('[js-element="rider-footer"'));
			runAddRiders();
		} else if (action == 'trash') {
			parent.remove();
		} else if (action == 'toggle-off') {
			runAlertBox({
				type:'confirm',
				message: 'Want to activate this rider?',
				callback: function() {
					simpleAjax('profile/activate_rider', {id:oThis.val()}, oThis, 300);
				}
			});
		} else if (action == 'toggle-on') {
			runAlertBox({
				type:'confirm',
				message: 'Do you want to deactivate this rider?',
				callback: function() {
					simpleAjax('profile/deactivate_rider', {id:oThis.val()}, oThis, 300);
				}
			});
		}
	});
}

var appendOperatorID = function(obj) {
	if (obj && obj.id != undefined) {
		$('#operator_form').find('[name="id"][value="'+obj.id+'"]').remove();
		$('#operator_form').prepend('<input type="hidden" name="id" value="'+obj.id+'">');
		if (obj.riders) {
			var ui = false;
			$.each(obj.riders, function(x, data) {
				for (field in data) {
					var value = data[field];
					ui = $('[name="riders['+x+']['+field+']"][js-name="'+field+'"]').val(value);
				}
			});
			if (ui) {
				ui.parent().find('[js-element="action"]').find('i').removeClass('fa-trash').addClass('fa-toggle-on');

				ui.parent().find('[js-element="action"]')
					.attr('data-orig-ui', '<i class="fa fa-toggle-on"></i>')
					.attr('js-event', 'toggle-on')
					.attr('data-toggle', 'tooltip')
					.attr('data-placement', 'top')
					.attr('data-original-title', 'Deactivate Rider');
				$('[data-toggle="tooltip"]').tooltip();
			}
		}
	}
}

var deactivateRiderUI = function(obj) {
	if (obj && obj.id != undefined) {
		if ($('[js-element="action"][value="'+obj.id+'"]').length) {
			var parent = $('[js-element="action"][value="'+obj.id+'"]').parents('[js-element*="riders"]:first');
			parent.find('[js-element="action"]')
				.attr('data-orig-ui', '<i class="fa fa-toggle-off"></i>')
				.attr('js-event', 'toggle-off')
				.attr('data-toggle', 'tooltip')
				.attr('data-placement', 'top')
				.attr('data-original-title', 'Activate Rider');
			$('[data-toggle="tooltip"]').tooltip();

			setTimeout(function() {
				parent.find('[js-element="action"]').find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
			}, 1000);
			
			parent.find('input:text').attr('disabled', 'disabled');
			parent.find('input:text').removeAttr('required');
			parent.find('[js-name="active"]').prop('value', 0).val(0);
		}
	}
}

var activateRiderUI = function(obj) {
	if (obj && obj.id != undefined) {
		if ($('[js-element="action"][value="'+obj.id+'"]').length) {
			var parent = $('[js-element="action"][value="'+obj.id+'"]').parents('[js-element*="riders"]:first');
			parent.find('[js-element="action"]')
				.attr('data-orig-ui', '<i class="fa fa-toggle-on"></i>')
				.attr('js-event', 'toggle-on')
				.attr('data-toggle', 'tooltip')
				.attr('data-placement', 'top')
				.attr('data-original-title', 'Deactivate Rider');
			$('[data-toggle="tooltip"]').tooltip();

			setTimeout(function() {
				parent.find('[js-element="action"]').find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
			}, 1000);
			
			parent.find('input:text').removeAttr('disabled');
			parent.find('input:text').attr('required', 'required');
			parent.find('[js-name="active"]').prop('value', obj.active).val(obj.active);
		}
	}
}