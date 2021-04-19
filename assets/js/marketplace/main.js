$(document).ready(function() {
	if (checkCookie('prev_latlng') == false && oUser == false) {
		$(window).on('scroll load', function() {
			if (($('#login_modal').data('bs.modal') || {}).isShown) {
				return false;
			} else {
				$('#check_loc_modal').modal('show');
			}
		});

		$('#login_modal').on('hidden.bs.modal', function () {
			$('#check_loc_modal').modal('show');
		});
	}

	$('#check_loc_modal').on('shown.bs.modal', function(e) {
		var input = $('#check-place').get(0);
		input.focus();
		var i = setInterval(function() {
			if (typeof google != 'undefined') {
				clearInterval(i);
				var autocomplete = new google.maps.places.Autocomplete(input, {
					componentRestrictions: {country: "ph"},
				});

				google.maps.event.addListener(autocomplete, 'place_changed', function() {
					var place = autocomplete.getPlace();
					// console.log(place.geometry.location.lat(), place.geometry.location.lng());
					var geocoder = new google.maps.Geocoder();
					geocoder.geocode({
						latLng: place.geometry.location
					}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							// console.log(results);
							if (results[1]) {
								var arVal = [];
								var city = null;
								var c, lc, component;
								for (var r = 0, rl = results.length; r < rl; r += 1) {
									var result = results[r];
									if (!city && result.types[0] === 'locality') {
										for (c = 0, lc = result.address_components.length; c < lc; c += 1) {
											component = result.address_components[c];
											if (component.types[0] === 'locality') {
												city = component.long_name;
												arVal.push(city);
												break;
											}
										}
									}
									if (city) {
										break;
									}
								}
								if (city) {
									$('#check-place').prop('value', city).val(city);
								}
								// console.log("City: " + city);
								simpleAjax('api/fetch_coordinates', {city: city});
							}
						}
					});
				});
			}
		}, 1000);
	}).on('hide.bs.modal', function(e) {});
});

var reloadState = function(data) {
	// window.location.reload(true);
	setTimeout(function() {
		/*$.ajax({
			url: '/',
			success: function(html) {
				var new_document = document.open('text/html', 'replace');
				new_document.write(html);
				new_document.close();
			}
		});*/
		window.location.reload(true);
	}, 2000);
}

