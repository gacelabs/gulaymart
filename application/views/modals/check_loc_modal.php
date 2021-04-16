<div class="modal fade" id="check_loc_modal" tabindex="-1" role="dialog" aria-labelledby="check_loc_modalLabel" data-keyboard="false"<?php if (empty($this->session->userdata('prev_latlng'))): ?> data-backdrop="static"<?php endif ?>>
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Check Location</h4>
			</div>
			<div class="modal-body">
				<p class="zero-gaps">As part of Gulaymart's core values to <b>support local farmers</b>, we urge everyone to filter the products based on their residing address. This way, we can be sure that the products shown are produced by the farmers within your local community.</p>
				<img src="assets/images/banner/check-location.png" class="img-responsive" style="margin: 20px 0;">

				<div class="text-step-basic">
					<p class="zero-gaps text-center"><i class="fa fa-exclamation-circle"></i></p>
					<p class="zero-gaps">Use the box below to search the <b class="text-contrast">city</b> where you live in.</p>
				</div>

				<div class="input-group">
					<span class="input-group-addon" id="basic-addon1">My city is</span>
					<input type="text" class="form-control" id="check-place" placeholder="" aria-describedby="basic-addon1">
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	$modaljs = [
		'plugins/jquery.inputmask.min',
		'plugins/inputmask.binding',
		'https://maps.googleapis.com/maps/api/js?key='.GOOGLEMAP_KEY.'&libraries=places',
		'plugins/markerclustererplus.min',
	];
	foreach ($modaljs as $name) {
		if (filter_var($name, FILTER_VALIDATE_URL)) {
			echo '<script type="text/javascript" src="'.$name.'"></script>';
		} else {
			echo '<script type="text/javascript" src="'.base_url('assets/js/'.$name.'.js').'"></script>';
		}
		echo "\r\n";
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		<?php if (empty($this->session->userdata('prev_latlng'))): ?>
			setTimeout(function() {
				$('#check_loc_modal').modal('show');
			}, 1000);
		<?php endif ?>

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
</script>