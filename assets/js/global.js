$(document).ready(function() {
	$('button.stop, a.stop, input.stop, [type="submit"].stop, select.stop').click(function(e) {
		e.preventDefault();
		$("select.stop").prop("disabled", true);
		$(this).blur();
		return false;
	});

	if (checkCookie('prev_latlng') == false && oUser == false && oSegments[1] != 'register') {
		$(window).on('scroll load', function() {
			if (($('#login_modal').data('bs.modal') || {}).isShown) {
				return false;
			} else {
				$('#check_loc_modal').modal('show');
			}
		});
		$('#login_modal').on('hidden.bs.modal', function () {
			if (checkCookie('prev_latlng') == false) {
				$('#check_loc_modal').modal('show');
			} else {
				reloadState();
			}
		});
	}
});

function getUrlParamByName(name, url) {
	if (url == undefined) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	// console.log(results);
	if (!results) return null;
	if (!results[2]) return '';

	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

if ((getUrlParamByName('install-app') == 'true') && oSegments.length == 0) {
	let deferredPrompt;
	document.getElementById('add-pwa').addEventListener('click', async () => {
		/*Hide the app provided install promotion*/
		document.getElementById('add-pwa').style.display = 'none';
		/*Show the install prompt*/
		deferredPrompt.prompt();
		/*Wait for the user to respond to the prompt*/
		const { outcome } = await deferredPrompt.userChoice;
		/*Optionally, send analytics event with outcome of user choice*/
		console.log(`User response to the install prompt: ${outcome}`);
		/*We've used the prompt, and can't use it again, throw it away*/
		deferredPrompt = null;
	});

	/*Initialize deferredPrompt for use later to show browser install prompt.*/
	window.addEventListener('beforeinstallprompt', (e) => {
		/*Prevent the mini-infobar from appearing on mobile*/
		e.preventDefault();
		/*Stash the event so it can be triggered later.*/
		deferredPrompt = e;
		/*Update UI notify the user they can install the PWA*/
		document.getElementById('add-pwa').style.display = 'block';
		/*Optionally, send analytics event that PWA install promo was shown.*/
		console.log(`'beforeinstallprompt' event was fired.`);
	});

	window.addEventListener('appinstalled', (e) => {
		/*Hide the app-provided install promotion*/
		document.getElementById('add-pwa').style.display = 'none';
		/*Clear the deferredPrompt so it can be garbage collected*/
		deferredPrompt = null;
		/*Optionally, send analytics event to indicate successful install*/
		console.log('PWA was installed');
		setTimeout(function() {
			alert(APPNAME + ' Installed!');
		}, 3000);
	});
}

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
			case 'check_loc_modal':
				var input = $('#check-place').get(0);
				// input.focus();
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
			case 'login_modal':
				setTimeout(function() {
					$('.ask-sign-in').click();
					$('[name="email_address"]').removeClass('error');
					$('[name="password"]').removeClass('error');
				}, 1000);
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

// Check for browser support of event handling capability
if (window.addEventListener) {
	window.addEventListener("load", downloadJSAtOnload, false);
} else if (window.attachEvent) {
	window.attachEvent("onload", downloadJSAtOnload);
} else {
	window.onload = downloadJSAtOnload;
}

// Add a script element as a child of the body
var downloadJSAtOnload = function() {
	var element = document.createElement("script");
	element.src = "https://www.google.com/recaptcha/api.js?onload=runReCaptchaOnLoad&render=explicit";
	document.body.appendChild(element);
}

window.runReCaptchaOnLoad = function() {
	$(document).find('form').each(function() {
		var form = $(this);
		runGRecaptchaChallenge(form.find('.g-recaptcha'), form);
	});
	// runGRecaptchaChallenge($('.login-with-social .g-recaptcha'));
};

var fbWidgetId;
var runGRecaptchaChallenge = function(recaptcha, form) {
	if (recaptcha != undefined) {
		if (recaptcha.length) {
			if (recaptcha.data('size') === 'invisible') {
				if (form != undefined) {
					var widgetId = grecaptcha.render(recaptcha.get(0), {
						callback: function(challenge) {
							// console.log(challenge);
							if ($.trim(challenge).length) {
								form.off('submit').submit();
							}
						}
					});
					form.bind('submit', function(e) {
						grecaptcha.reset();
						if (form.find('[name]').hasClass('error') == false) {
							grecaptcha.execute(widgetId);
						}
					});
				} else {
					fbWidgetId = grecaptcha.render(recaptcha.get(0));
				}
			} else {
				grecaptcha.render(recaptcha.get(0));
			}
		}
	}
}

