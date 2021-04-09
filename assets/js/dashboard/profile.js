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
			// $('.shipping-address-panel:not(.jq-toast-wrap)').off('*').one('mousemove', onCursorIdle).one('touchmove', onCursorIdle);
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
});

function updateSavedObjects(data) {
	console.log(data);
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

var onCursorIdle = function(e) {
	if (timeout !== null) clearTimeout(timeout);
	timeout = setTimeout(function() {
		runAlertBox({
			type:'confirm',
			message: 'You have interact with the map 10 seconds ago, do you want to reset previous inputs?',
			callback: function() { 
				timeout = null;
				$('.shipping-address-panel:not(.jq-toast-wrap)').off('*');
				resetMap();
			},
			cancel: function() {
				timeout = null;
				var cnt = 0;
				var i = setInterval(function() {
					++cnt;
					if (cnt == 3) {
						console.log('here', cnt);
						$('.shipping-address-panel:not(.jq-toast-wrap)').off('*').on('mousemove', onCursorIdle).on('touchmove', onCursorIdle);
					}
				}, 3000);
			}
		});
	}, 3000);
};