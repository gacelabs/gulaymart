var isSucceed = false, map, marker, infowindow, savedAddress1, savedAddress2, savedLatLng;
$(document).ready(function() {
	var oLatLong = {'lat':14.68244804236, 'lng': 120.98537670912712}, hasLatlong = false;
	navigator.geolocation.getCurrentPosition(function(response) {
		if (response != undefined) {
			oLatLong = {'lat': response.coords.latitude, 'lng': response.coords.longitude}; 
		}
	});
	console.log(oLatLong);

	savedAddress1 = $('#address_1').val();
	savedAddress2 = $('#address_2').val();

	if ($.trim($('#latlng').val()).length) {
		hasLatlong = true;
		oLatLong = JSON.parse($.trim($('#latlng').val()));
	}
	savedLatLng = oLatLong;

	map = new google.maps.Map($('#map-box').get(0), {
		zoom: hasLatlong ? 17 : 7,
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
		// map.setZoom(8);
		map.setCenter(marker.getPosition());
		infowindow.open(map, marker);
	});
	map.setCenter(marker.getPosition());
	infowindow.open(map, marker);

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
		map.setZoom(17);
	});

	var timeout = null;
	$(document).on('mousemove', function() {
		if (timeout !== null) {
			clearTimeout(timeout);
		}
		timeout = setTimeout(function() {
			resetMap();
		}, 12000);
	}).on('touchmove', function() {
		if (timeout !== null) {
			clearTimeout(timeout);
		}
		timeout = setTimeout(function() {
			resetMap();
		}, 12000);
	});

	$('#reset-to-prev-btn').off('click').on('click', function() {
		resetMap();
	});

	$('#undo-btn').off('click').on('click', function() {
		resetMap();
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
	$('#latlng').attr('value', JSON.stringify({'lat': position.lat(), 'lng': position.lng()}));

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		latLng: position
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var arr = results[0].address_components.slice(-4, results[0].address_components.length);
			var arVal = [];
			$.each(arr, function(i, row) {
				arVal.push(row.long_name);
			});
			// console.log(arVal);
			uiInputAddress.attr('value', arVal.join(', '));
			uiInputAddress.val(arVal.join(', '));
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
		savedLatLng = {'lat': data.lat, 'lng': data.lng};
	}
}

function resetMap() {
	$('#address_1').val(savedAddress1);
	$('#address_2').val(savedAddress2);
	$('#search-place').val('');
	
	google.maps.event.clearListeners(marker, 'dragend');
	marker.setMap(null);
	var newMark = new google.maps.Marker({
		position: savedLatLng,
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
	map.setZoom(17);

	infowindow.open(map, marker);
}