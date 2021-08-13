var bLoginTriggered = false;
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

	$('[js-event="farmMenuTrigger"]').click(function() {
		$(this).toggleClass('active');
		$('[js-event="navbarFarmMenuContainer"]').toggleClass('active');
	});

	$('form.sign-in-form').bind('submit', function(e) {
		bLoginTriggered = true;
	});

	if ($('[has-basket-session="1"]').length) {
		console.log('has basket orders');
		// runAlertBox({type:'info', message: 'has basket orders!', unclose: true});
	}
});

window.onpopstate = function(e) {
	if (mobileAndTabletCheck()) {
		if (e.target.location.hash != '#login' && bLoginTriggered == false) {
			if ($('.modal').length) $('.modal').modal('hide');
		} else {
			e.target.location.reload(true);
		}
	}
};

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
	$('div.modal').on('show.bs.modal', function(e) { 
		switch (e.target.id) {
			case 'farm_location_modal':
				if (mobileAndTabletCheck()) window.location.hash = 'm';
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
				if (mobileAndTabletCheck()) window.location.hash = 'm';
				if ($(e.relatedTarget).data('change-ui').length) {
					var value = $(e.relatedTarget).data('change-ui');
					$(e.target).find('form').prepend($('<input />', {type: 'hidden', name: 'ui', value: value}));
					var field = $(e.relatedTarget).data('field');
					$(e.target).find('form').prepend($('<input />', {type: 'hidden', name: 'col', value: field}));
				}
			break;
			case 'ff_invoice_modal':
				if (mobileAndTabletCheck()) window.location.hash = 'm';
				// console.log($(e.relatedTarget).data('basket-merge-id'));
				var merge_id = $(e.relatedTarget).data('basket-merge-id');
				$(e.target).find('p[js-data="loader"]').removeClass('hide');
				simpleAjax('api/set_invoice_html/invoice_middle_body', {table:'baskets_merge', data:{id: merge_id}, row: true, identifier:merge_id}, $(e.relatedTarget));
			break;
			case 'check_loc_modal':
				if (mobileAndTabletCheck()) window.location.hash = 'm';
				var input = $('#check-place').get(0);
				// input.focus();
				var i = setInterval(function() {
					if (typeof google != 'undefined') {
						clearInterval(i);
						var autocomplete = new google.maps.places.Autocomplete(input, {
							componentRestrictions: {country: "ph"},
						});

						var execLocation = function(latlng) {
							var geocoder = new google.maps.Geocoder();
							geocoder.geocode({
								latLng: latlng
							}, function(results, status) {
								// console.log(results);
								if (status == google.maps.GeocoderStatus.OK) {
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
						}

						google.maps.event.addListener(autocomplete, 'place_changed', function() {
							var place = autocomplete.getPlace();
							// console.log(place.geometry.location.lat(), place.geometry.location.lng());
							execLocation(place.geometry.location);
						});

						if (mobileAndTabletCheck()) {
							$('#my-curr-loc').tooltip().focus();
							$('#my-curr-loc').off('click').on('click', function() {
								execLocation(oLatLong);
							});
						}
					}
				}, 1000);
			break;
			case 'login_modal':
				if (mobileAndTabletCheck()) window.location.hash = 'm';
				$('.ask-sign-in').click();
			break;
			case 'reply_modal':
				if (mobileAndTabletCheck()) window.location.hash = 'm';
				var oReply = JSON.parse($(e.relatedTarget).attr('data-reply'));
				var oFeedback = JSON.parse($(e.relatedTarget).attr('data-feedback'));
				// console.log(oReply, oFeedback);
				if (Object.keys(oFeedback).length) {
					// $('#buyer_photo').attr('src', oFeedback.profile.photo_url);
					$('#buyer_fullname').text(oFeedback.profile.firstname+' '+oFeedback.profile.lastname);
					// $('#buyer_date').text($.format.date(oFeedback.added, "- ddd, MMMM d, yyyy | hh:ss p"));
					$('#buyer_date').text(timeZoneFormatDate(oFeedback.added));
					$('#buyer_comments').text(oFeedback.content);
					$('#to_id').val(oFeedback.from_id);
					
					$('#under').val(oFeedback.id);
					$('#page_id').val(oFeedback.page_id);
					$('#entity_id').val(oFeedback.entity_id);
				}
				if (oReply == false) {
					$('#reply_box').removeClass('hide');
					$('#seller_content').addClass('hide');
					$('#seller_buyer_date').text('');
					$('#seller_comments').text('');
				} else {
					if (oReply == true) {
						$('#reply_modalLabel').text('Last Response:');
						$('#reply_box').addClass('hide');
						$('#seller_content').addClass('hide');
						$('#buyer_fullname').text(oFeedback.farm.name);
						$('#is_seller').text((oFeedback.from_id == oUser.id ? '(You)' : ''));
					} else {
						/*already replied*/
						$('#reply_modalLabel').text('Last Conversations:');
						$('#reply_box').addClass('hide');
						$('#seller_content').removeClass('hide');
						$('#seller_content').find('img.media-object').attr('src', oReply.farm.profile_pic);
						// $('#seller_buyer_date').text($.format.date(oReply.added, "- ddd, MMMM d, yyyy | hh:ss p"));
						$('#seller_farm_name').text(oReply.farm.name);
						$('#seller_buyer_date').text(timeZoneFormatDate(oReply.added));
						$('#seller_comments').text(oReply.content);
						$('#is_seller').text((oReply.from_id == oUser.id ? '(You)' : ''));
					}
				}
			break;
			case 'ff_received_modal':
				if (mobileAndTabletCheck()) window.location.hash = 'm';
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
			case 'login_modal':
				setTimeout(function() {
					$('.login-with-social').removeClass('hide');
					$('.fb-login-panel').addClass('hide');
					$('.fb-signing-in').addClass('hide');
					$('.invalid-fb-email').addClass('hide');

					$('.ask-sign-in').click();
					$('[name="email_address"]').removeClass('error');
					$('[name="email"]').removeClass('error');
					$('[name="password"]').removeClass('error');
				}, 1000);
			break;
			case 'reply_modal':
				$('#reply_modal').find('form [name][required]').removeClass('error');
				$('#seller_reply').val('');
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

var timeZoneFormatDate = function(sDate, options) {
	if (options == undefined) {
		options = {
			timeZone: TIMEZONE,
			weekday: 'long',
			year: 'numeric',
			month: 'long',
			day: 'numeric',
			hour: 'numeric',
			minute: 'numeric'
		};
	}
	var oObject = new Date(sDate), 
	sNewDate = oObject.toLocaleString('en-US', options);
	return sNewDate;
}

function capitalizeFirstLetter(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

var closeModals = function() {
	$('.modal').modal('hide');
}