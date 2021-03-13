var map, marker, infowindow, savedAddress1, savedAddress2, savedLatLng;
$(document).ready(function() {
	var oLatLong = {'lat':14.68244804236, 'lng': 120.98537670912712}, hasLatlong = false;

	savedAddress1 = $('#address_1').val();
	savedAddress2 = $('#address_2').val();

	if ($.trim($('#latlng').val()).length) {
		hasLatlong = true;
		var oLatLong = JSON.parse($.trim($('#latlng').val()));
	}
	savedLatLng = oLatLong;

	map = new google.maps.Map($('#map-box').get(0), {
		zoom: hasLatlong ? 17 : 7,
		center: oLatLong,
		zoomControl: true,
		// streetViewControl: false,
		// fullscreenControl: false,
		// mapTypeControl: false,
		gestureHandling: 'greedy'
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
	infowindow.open(map, marker);
	google.maps.event.addListener(map, "click", function(e) {
		// infowindow.close();
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
			$('#reset-to-prev-btn').trigger('click');
		}, 3000);
	}).on('touchmove', function() {
		if (timeout !== null) {
			clearTimeout(timeout);
		}
		timeout = setTimeout(function() {
			$('#reset-to-prev-btn').trigger('click');
		}, 3000);
	});

	$('#reset-to-prev-btn').off('click').on('click', function() {
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
	});

	$('#search-place-btn').off('click').on('click', function() {
		$('#search-place').trigger('changed');
	});
});

function setDragEvent(marker, infowindow) {
	google.maps.event.addListener(marker, 'dragend', function() {
		fnDragEnd(marker);
	});
}

function fnDragEnd(marker) {
	var uiInputAddress = $('#address_2');
	// infowindow.close();

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