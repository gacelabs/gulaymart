$(document).ready(function() {
	$('button.stop, a.stop, input.stop, [type="submit"].stop, select.stop').click(function(e) {
		e.preventDefault();
		$("select.stop").prop("disabled", true);
		$(this).blur();
		return false;
	});
});

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	var expires = "expires="+d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function checkCookie(cname) {
	var cookie = getCookie(cname);
	if (cookie != "") {
		return true;
	} else {
		return false;
	}
}

function modalCallbacks() {
	$('div.modal').on('shown.bs.modal', function(e) { 
		switch (e.target.id) {
			case 'farm_location_modal':
				// console.log($(e.relatedTarget));
				var input = $('<input />', {type: 'hidden', name: 'loc_input', value: '#'+e.relatedTarget.id});
				$(e.target).find('form').prepend(input);
				if (map != undefined) {
					$('#shipping-id').remove();
					var dataLocation = $(e.relatedTarget).next('input:hidden').val();
					if (dataLocation.length) {
						var oData = $.parseJSON(dataLocation),
							oThisLatLong = {lat: parseFloat(oData.lat), lng: parseFloat(oData.lng)};
						// console.log(oData);
						resetMap(oThisLatLong);
						$('#address_1').val(oData.address_1);
					} else {
						resetMap({lat: parseFloat(oUser.lat), lng: parseFloat(oUser.lng)});
						$('#address_1').val('');
						setTimeout(function() {
							$('#address_2').val('');
						}, 500);
					}
					setTimeout(function() {
						google.maps.event.trigger(map, "contextmenu");
					}, 1000);
				}
			break;
			case 'media_modal':
				if ($(e.relatedTarget).data('change-ui').length) {
					var value = $(e.relatedTarget).data('change-ui');
					$(e.target).find('form').prepend($('<input />', {type: 'hidden', name: 'ui', value: value}));
					var field = $(e.relatedTarget).data('field');
					$(e.target).find('form').prepend($('<input />', {type: 'hidden', name: 'col', value: field}));
				}
			break;
			case 'ff_invoice_modal':
				// console.log($(e.relatedTarget).data('basket-merge-id'));
				var merge_id = $(e.relatedTarget).data('basket-merge-id');
				$(e.target).find('p[js-data="loader"]').removeClass('hide');
				simpleAjax('api/set_invoice_html/invoice_middle_body', {table:'baskets_merge', data:{id: merge_id}, row: true, identifier:merge_id}, $(e.relatedTarget));
			break;
		}
	}).on('hide.bs.modal', function(e) { 
		switch (e.target.id) {
			case 'farm_location_modal':
				$(e.target).find('form input[name="loc_input"]').remove();
			break;
			case 'media_modal':
				// console.log(e);
				$(e.target).find('form input[name="ui"]').remove();
				$(e.target).find('form input[name="col"]').remove();
				
				var html = $(e.target).find('form .preview_images_list').html();
				$(e.target).find('form .preview_images_list').html('');
				html = $(html).find('input:radio').attr('name', 'selected').removeAttr('data-upload').removeAttr('checked').parent('li');
				$(html).find('input:radio').each(function(i, elem) {
					var oThis = $(elem);
					elem.value = oThis.data('url-path');
				});
				$(e.target).find('form .preview_images_selected').append(html);
				$(e.target).find('form').find('input:file').prop('value', '').val('');;
			break;
			case 'ff_invoice_modal':
				$(e.target).find('p[js-data="loader"]').addClass('hide');
				$(e.target).find('[js-element="invoice-body"]').html('');
			break;
		}
	});
}

function loadMore(ui, method) {
	if (ui != undefined && ui.length && ui.data('url') != undefined) {
		ui.on('click', function() {
			var ids = [];
			if ($(ui.data('items')).length) {
				$(ui.data('items')).map(function(i, elem) {
					ids.push($(elem).data('id'));
				});
			}
			// console.log(ids);
			$.ajax({
				url: ui.data('url'),
				type: 'post',
				data: {'not_ids': ids},
				dataType: 'json',
				success: function(data) {
					// console.log(data);
					if (data.success) {
						$(ui.data('items')).parent().append(data.html);
					}
					if ($(ui.data('items')).length == data.count) ui.hide();
				}
			});
		});
	}
}