var isSucceed = false, hasLatlong = false, map, marker, infowindow, oLatLong, savedAddress1, savedAddress2, savedLatLng, timeout = null;
$(document).ready(function() {
	oLatLong = {'lat':14.68244804236, 'lng': 120.98537670912712};
	navigator.geolocation.getCurrentPosition(function(response) {
		if (response != undefined) {
			oLatLong = {'lat': response.coords.latitude, 'lng': response.coords.longitude}; 
		}
	});
	console.log(oLatLong);
	savedAddress1 = $('#address_1').val();
	savedAddress2 = $('#address_2').val();
	if ($.trim($('#lat').val()).length && $.trim($('#lng').val()).length) {
		hasLatlong = true;
		oLatLong = {'lat': parseFloat($('#lat').val()), 'lng': parseFloat($('#lng').val())};
	}
	savedLatLng = oLatLong;
	loadMap(oLatLong);

	$('#reset-to-prev-btn, #undo-btn').off('click').on('click', function() {
		resetMap();
		$('#address_2').val('');
	});

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
			console.log(oLatLong, oJson);

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
			dataType: 'json',
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			}
		};
		if (oPauseInputAjax != false && oPauseInputAjax.readyState !== 4) oPauseInputAjax.abort();
		oPauseInputAjax = $.ajax(oSettings);
	});

	setTimeout(function() { $('#search-place').attr('autocomplete', 'input'); }, 300);

});

function setDragEvent(marker, infowindow) {
	google.maps.event.addListener(marker, 'dragend', function() {
		fnDragEnd(marker);
	});
}

function fnDragEnd(marker) {
	var uiInputAddress = $('#address_2');
	map.setCenter(marker.getPosition());
	infowindow.open(map, marker);

	var position = marker.getPosition();
	$('#lat').attr('value', position.lat());
	$('#lng').attr('value', position.lng());

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		latLng: position
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if ($.trim($('#search-place').val()).length) {
				var arr = results[0].address_components.slice(-4, results[0].address_components.length);
				var arVal = [];
				$.each(arr, function(i, row) {
					arVal.push(row.long_name);
				});
				// console.log(arVal);
				uiInputAddress.attr('value', arVal.join(', '));
				uiInputAddress.val(arVal.join(', '));
			}
		} else {
			console.log(status);
			uiInputAddress.attr('value', '');
			uiInputAddress.val('');
			$('#address_1').val(savedAddress1);
		}
	});
}

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

function resetMap(oThisLatLong) {
	if (oThisLatLong == undefined) {
		$('#address_1').val('');
		$('#address_2').val('');
		$('#lat').val('');
		$('#lng').val('');
	}
	$('#search-place').val('');
	
	google.maps.event.clearListeners(marker, 'dragend');
	marker.setMap(null);
	var newMark = new google.maps.Marker({
		position: oThisLatLong == undefined ? oLatLong : oThisLatLong,
		map: map,
		draggable: true,
		animation: google.maps.Animation.DROP,
	});
	fnDragEnd(newMark);

	newMark.setMap(map);
	marker = newMark;
	setDragEvent(newMark, infowindow);

	var bounds = new google.maps.LatLngBounds();
	bounds.extend(newMark.getPosition());
	map.fitBounds(bounds);
	map.setZoom(12);

	infowindow.open(map, marker);
}

function loadMap(oLatLong) {
	map = new google.maps.Map($('#map-box').get(0), {
		zoom: hasLatlong ? 12 : 10,
		center: oLatLong,
		gestureHandling: "cooperative",
	});

	infowindow = new google.maps.InfoWindow({
		content: '<b>Drag Me!</b>',
	});
	// maps.push(map);
	marker = new google.maps.Marker({
		position: oLatLong,
		map: map,
		draggable: true,
		animation: google.maps.Animation.DROP,
	});

	marker.addListener("click", () => {
		map.setZoom(12);
		map.setCenter(marker.getPosition());
		infowindow.open(map, marker);
	});
	infowindow.open(map, marker);
	map.setCenter(marker.getPosition());

	google.maps.event.addListener(map, "click", function(e) {
		map.setCenter(marker.getPosition());
	});
	// markers.push(marker);
	setDragEvent(marker, infowindow);

	var input = $('#search-place').get(0);
	var autocomplete = new google.maps.places.Autocomplete(input);

	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		$('#address_1').val('');
		var place = autocomplete.getPlace();
		var lat = place.geometry.location.lat();
		var long = place.geometry.location.lng();
		
		google.maps.event.clearListeners(marker, 'dragend');
		marker.setMap(null);
		var newMark = new google.maps.Marker({
			position: {'lat': lat, 'lng': long},
			map: map,
			draggable: true,
			animation: google.maps.Animation.DROP,
		});
		fnDragEnd(newMark);

		newMark.setMap(map);
		marker = newMark;
		setDragEvent(newMark, infowindow);

		var bounds = new google.maps.LatLngBounds();
		bounds.extend(newMark.getPosition());
		map.fitBounds(bounds);
		map.setZoom(12);
	});
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