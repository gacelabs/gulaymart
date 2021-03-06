var hasLatlong = false, autocomplete, map, marker, infowindow, savedAddress1, savedAddress2, savedLatLng;
$(document).ready(function() {
	modalCallbacks();
	if ($('select.chosen').length) $('select.chosen').chosen();

	if ($('#map-box').length) {
		if (window.location.protocol == 'https:') {
			navigator.permissions.query({name:'geolocation'}).then(function(oLocation) {
				oLocation.onchange = function() { findMe(oLocation); }
				findMe(oLocation);
			});
		} else {
			initMapLocations();
		}
	} else {
		navigator.geolocation.getCurrentPosition(function(response) {
			if (response != undefined) {
				oLatLong = {'lat': response.coords.latitude, 'lng': response.coords.longitude};
			}
		}, function () {
			if (mobileAndTabletCheck()) {
				runAlertBox({type:'info', message: 'Please enable your location to access google map data.'/*, unclose: true*/});
			}
			if (oUser != false && mobileAndTabletCheck() == false && Object.values(oLatLong).length == 0) oLatLong = {lat: oUser.lat, lng: oUser.lng};
		}, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 });
	}

	if ($('form').length) {
		$('form [type=submit]').bind('click', function() {
			$('[type=submit]', $(this).parents('form')).removeAttr('clicked');
			$(this).attr('clicked', 1);
		});
		$('form a').bind('click', function() {
			$('a', $(this).parents('form')).removeAttr('clicked');
			$(this).attr('clicked', 1);
		});
	}

	if (!oUser) {
		if ($('.simple-marquee-container').SimpleMarquee != undefined) {
			$('.simple-marquee-container').SimpleMarquee({
				duration: 40000,
			});
		}
	}
});

var findMe = function(oLocation) {
	if (oLocation.state == 'granted' || oLocation.state == 'prompt') {
		$('.close-jq-toast-single:visible').trigger('click');
		navigator.geolocation.getCurrentPosition(function(response) {
			if (response != undefined) {
				oLatLong = {'lat': response.coords.latitude, 'lng': response.coords.longitude};
				initMapLocations();
			}
		}, function () {
			runAlertBox({type:'info', message: 'Please enable your location to access google map data.'/*, unclose: true*/});
		}, { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 });
	} else if (oLocation.state == 'denied') {
		runAlertBox({type:'info', message: 'Please allow GulayMart.com your location to access google map data.'/*, unclose: true*/});
	}
}

var oFormAjax = false, formAjax = function(form, uploadFile) {
	if (typeof $.ajax == 'function') {
		if (form != undefined && form instanceof HTMLElement && form.action.indexOf('no-action') < 0) {
			if (uploadFile == undefined) uploadFile = false;
			$('form').removeClass('active-ajaxed-form');
			var isClicked = parseInt($(form).find('[clicked]:visible').attr('clicked'))
			// console.log(isClicked)
			var uiButtonSubmit = isClicked ? $(form).find('[clicked]:visible') : false,
			callbackFn = $(form).data('callback'),
			lastButtonUI = isClicked ? uiButtonSubmit.html() : false, loadingText = 'Processing ...',
			keep_loading = false;
			if (uiButtonSubmit) {
				if (typeof uiButtonSubmit.attr('loading-text') == 'undefined') {
					loadingText = uiButtonSubmit.html();
				} else if (typeof uiButtonSubmit.attr('loading-text') != 'undefined') {
					loadingText = uiButtonSubmit.attr('loading-text');
				} else {
					loadingText = 'Processing ...';
				}
				keep_loading = (uiButtonSubmit.data('keep-loading') != undefined) ? uiButtonSubmit.data('keep-loading') : false;
				// console.log(keep_loading);
			}

			var oSettings = {
				url: form.action,
				type: form.method,
				dataType: 'jsonp',
				// timeout: 1000,
				jsonpCallback: 'gmCall',
				beforeSend: function(xhr, settings) {
					$(form).addClass('active-ajaxed-form');
					if (uiButtonSubmit) {
						if (uiButtonSubmit.data('orig-ui') == undefined && keep_loading == false) {
							uiButtonSubmit.attr('data-orig-ui', lastButtonUI);
						}
						uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> '+loadingText);
					}
					if (typeof keep_loading != 'boolean' && typeof keep_loading == 'number') {
						$('a,select,button,input:button,input:submit').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
					}
				},
				success: function(data) {
					if (data && data.success) {
						console.log(data);
					}
				},
				error: function(xhr, status, thrown) {
					if (thrown == 'Service Unavailable') {
						console.log('Debug called');
					} else {
						console.log(status, thrown);
					}
				},
				complete: function(xhr, status) {
					if (uiButtonSubmit && keep_loading == false) {
						uiButtonSubmit.html(uiButtonSubmit.data('orig-ui'));
						uiButtonSubmit.removeAttr('disabled');
					} else if (typeof keep_loading != 'boolean' && typeof keep_loading == 'number') {
						setTimeout(function() {
							if (uiButtonSubmit) uiButtonSubmit.html(uiButtonSubmit.data('orig-ui'));
							$('a,select,button,input:button,input:submit').removeClass('stop').prop('disabled', false).removeAttr('disabled');
						}, keep_loading);
					}
					var fn = eval(callbackFn);
					if (typeof fn == 'function') {
						fn(form, xhr, uploadFile);
					}
					/*if (window.location.pathname.indexOf('new') >= 0) {
						form.reset();
					}*/
				}
			};

			var formData = $(form).serialize();
			if (uploadFile != false) {
				formData = new FormData(form);
				oSettings.contentType = false;
				oSettings.processData = false;
			}

			oSettings.data = formData;
			// console.log(oSettings);
			if (oFormAjax != false && oFormAjax.readyState !== 4) oFormAjax.abort();
			oFormAjax = $.ajax(oSettings);
		} else {
			console.log('form elements only! or this has no-action attr');
		}
	} else {
		console.log('ajax function not loaded!');
	}
}

var oSimpleAjax = false, simpleAjax = function(url, data, ui, keep_loading, no_abort) {
	if (data == undefined) data = {};
	if (url == undefined) url = false;
	if (ui == undefined) ui = false;
	if (keep_loading == undefined) keep_loading = false;
	if (no_abort == undefined) no_abort = false;
	if (url && url.indexOf('no-action') < 0) {
		var sLastButtonText = '', loadingText = 'Processing ...';
		if (ui) {
			sLastButtonText = ui.html();
			if (typeof ui.attr('loading-text') == 'undefined') {
				loadingText = ui.html();
			} else if (typeof ui.attr('loading-text') != 'undefined') {
				loadingText = ui.attr('loading-text');
			} else {
				loadingText = 'Processing ...';
			}
		}
		var ajax_complete_fn = false;
		if (data && typeof data.ajax_complete == 'function') {
			ajax_complete_fn = data.ajax_complete;
			delete data.ajax_complete;
		}
		var oSettings = {
			url: url,
			type: 'post',
			data: data,
			cache: false,
			dataType: 'json',
			beforeSend: function(xhr, settings) {
				if (ui) {
					ui.addClass('active-ajaxed-btn');
					ui.attr('data-orig-ui', sLastButtonText);
					ui.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> '+loadingText);
				}
				if (typeof keep_loading != 'boolean' && typeof keep_loading == 'number') {
					$('a,select,button,input:button,input:submit').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
				}
			},
			success: function(response) {
				if (response) {
					if (response.data != undefined) {
						response.data.elem = ui;
					} else if (response) {
						response.elem = ui;
					}
					response.keep_loading = keep_loading;
					ajaxSuccessResponse(response);
				}
			},
			error: function(xhr, status, thrown) {
				if (thrown == 'Service Unavailable') {
					console.log('Debug called');
				} else {
					console.log(status, thrown);
				}
			},
			complete: function(xhr, status) {
				var wait = new Promise((resolve, reject) => {
					if (ui && keep_loading == false) {
						ui.html(ui.data('orig-ui'));
						ui.removeAttr('disabled');
					} else if (typeof keep_loading != 'boolean' && typeof keep_loading == 'number') {
						setTimeout(function() {
							if (ui) ui.html(ui.data('orig-ui'));
							$('a,select,button,input:button,input:submit').removeClass('stop').prop('disabled', false).removeAttr('disabled');
						}, keep_loading);
					}
					resolve();
				}).then(() => {
					if (typeof ajax_complete_fn == 'function') ajax_complete_fn(xhr, status);
				});
			}
		};
		if (oSimpleAjax != false && oSimpleAjax.readyState !== 4 && no_abort == false) oSimpleAjax.abort();
		oSimpleAjax = $.ajax(oSettings);
	} else {
		console.log('form has no action to do');
	}
}

var ajaxSuccessResponse = function(response, e) {
	// console.log(response);
	var bConfirmed = true;
	if (response && response.type && response.type.length && response.message.length) {
		bConfirmed = runAlertBox(response, undefined, bConfirmed);
	}
	if (bConfirmed) {
		var wait = new Promise((resolve, reject) => {
			if (response && (typeof response.callback == 'string')) {
				var fn = eval(response.callback);
				if (typeof fn == 'function') {
					// console.log(response.callback, 'function');
					fn(response.data, e);
				}
			}
			resolve();
		}).then(() => {
			if (response && (typeof response.redirect == 'string')) {
				if (!response.redirect.isEmpty()) {
					if (response.keep_loading != false) {
						setTimeout(function() {
							window.location = response.redirect;
						}, 2000);
					} else {
						window.location = response.redirect;
					}
				}
			}
		});
	}
}

window.mobileAndTabletCheck = function() {
	let check = false;
	(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
};

String.prototype.isEmpty = function() {return (this.length === 0 || !this.trim());};

var runAlertBox = function(response, heading, bConfirmed) {
	if (bConfirmed == undefined) bConfirmed = false;
	if (typeof response == 'object') {
		switch (response.type.toLowerCase()) {
			case 'confirm': case 'confirmation': 
				if (heading == undefined) heading = 'Confirmation';
				bConfirmed = false;
				$.toast({
					heading: heading,
					text: response.message+'<br><br><button class="btn btn-xs btn-danger pull-right" id="toast-cancel" style="padding: 0 10px;">cancel</button><button class="btn btn-xs btn-success pull-right" style="padding: 0 10px; margin-right: 10px;" id="toast-ok">ok</button><br>',
					icon: 'warning',
					loader: false,
					stack: false,
					position: 'top-center',
					allowToastClose: true,
					bgColor: '#f1ac2e',
					textColor: 'white',
					hideAfter: false,
					beforeShow: function () {
						$('#toast-ok').off('click').on('click', function(e) {
							if (response && (typeof response.callback == 'string')) {
								var fn = eval(response.callback);
								if (typeof fn == 'function') {
									fn(response.data);
								}
							}
							if (response && (typeof response.callback == 'function')) {
								response.callback(response.data);
							}
							$('#toast-cancel').off('click');
							$('.close-jq-toast-single:visible').trigger('click');
							setTimeout(function() {
								if (response && (typeof response.redirect == 'string')) {
									if (response.redirect) window.location = response.redirect;
								}
							}, 300);
						});
						$('#toast-cancel').off('click').on('click', function(e) {
							if (response && (typeof response.cancel == 'function')) {
								response.cancel(response.data);
							}
							$('#toast-ok').off('click');
							$('.close-jq-toast-single:visible').trigger('click');
							setTimeout(function() {
								if (response && (typeof response.redirect == 'string')) {
									if (response.redirect) window.location = response.redirect;
								}
							}, 300);
						});
					},
				});
			break;
			case 'success':
				if (heading == undefined) heading = 'Success';
				var oSettings = {
					heading: heading,
					text: response.message,
					icon: 'success',
					loader: (response.unclose == true) ? false : true,
					stack: false,
					position: 'top-center',
					allowToastClose: (response.unclose == true) ? true : false,
					bgColor: 'darkgreen',
					textColor: 'white',
				};
				if (response.unclose == true) {
					oSettings.hideAfter = false;
				} else {
					oSettings.hideAfter = 7000;
				}
				$.toast(oSettings);
			break;
			case 'error': case 'danger':
				if (heading == undefined) heading = 'Error';
				var oSettings = {
					heading: heading,
					text: response.message,
					icon: 'error',
					loader: (response.unclose == true) ? false : true,
					stack: false,
					position: 'top-center',
					allowToastClose: (response.unclose == true) ? true : false,
					bgColor: 'red',
					textColor: 'white',
				};
				if (response.unclose == true) {
					oSettings.hideAfter = false;
				} else {
					oSettings.hideAfter = 7000;
				}
				$.toast(oSettings);
			break;
			case 'info': case 'information':
				if (heading == undefined) heading = 'Information';
				var oSettings = {
					heading: heading,
					text: response.message+'<div id="information-ok" class="hide"><br><button class="btn btn-xs btn-success pull-right" style="padding: 0 10px; margin-right: 10px;" id="toast-ok">ok</button><br></div>',
					icon: 'info',
					loader: (response.unclose == true) ? false : true,
					stack: false,
					position: 'top-center',
					allowToastClose: (response.unclose == true) ? true : false,
					bgColor: 'blue',
					textColor: 'white',
				};
				if (response && (typeof response.callback == 'function')) {
					oSettings.beforeShow = function () {
						$('#information-ok').removeClass('hide');
						$('#toast-ok').off('click').on('click', function(e) {
							if (response && (typeof response.callback == 'string')) {
								var fn = eval(response.callback);
								if (typeof fn == 'function') {
									fn(response.data);
								}
							}
							if (response && (typeof response.callback == 'function')) {
								response.callback(response.data);
							}
							$('.close-jq-toast-single:visible').trigger('click');
							setTimeout(function() {
								if (response && (typeof response.redirect == 'string')) {
									if (response.redirect) window.location = response.redirect;
								}
							}, 300);
						});
					}
				}
				if (response && (typeof response.afterCallback == 'function')) {
					oSettings.afterShown = function () {
						if (response && (typeof response.afterCallback == 'string')) {
							var fn = eval(response.afterCallback);
							if (typeof fn == 'function') {
								fn(response.data);
							}
						}
						if (response && (typeof response.afterCallback == 'function')) {
							response.afterCallback(response.data);
						}
					}
				}
				if (response.unclose == true) {
					oSettings.hideAfter = false;
				} else {
					oSettings.hideAfter = 7000;
				}
				$.toast(oSettings);
			break;
			case 'warning': case 'warn':
				if (heading == undefined) heading = 'Warning';
				bConfirmed = false;
				$.toast({
					heading: heading,
					text: response.message+'<br><br><button class="btn btn-xs btn-success pull-right" style="padding: 0 10px; margin-right: 10px;" id="toast-ok">ok</button><br>',
					icon: 'warning',
					loader: false,
					stack: false,
					position: 'top-center',
					allowToastClose: false,
					bgColor: '#f1ac2e',
					textColor: 'white',
					hideAfter: false,
					beforeShow: function () {
						$('#toast-ok').off('click').on('click', function(e) {
							if (response && (typeof response.callback == 'string')) {
								var fn = eval(response.callback);
								if (typeof fn == 'function') {
									fn(response.data);
								}
							}
							if (response && (typeof response.callback == 'function')) {
								response.callback(response.data);
							}
							$('.close-jq-toast-single:visible').trigger('click');
							setTimeout(function() {
								if (response && (typeof response.redirect == 'string')) {
									if (response.redirect) window.location = response.redirect;
								}
							}, 300);
						});
					},
				});
			break;
			case 'prompt': 
				if (heading == undefined) heading = 'Proceed to Action';
				bConfirmed = false;
				if (response.data.form_ui != undefined) {
					var formUI = response.data.form_ui;
					$.toast({
						heading: heading,
						text: response.message+'<br><br>'+response.data.form_ui+'<br>',
						icon: 'info',
						loader: false,
						stack: false,
						position: 'top-center',
						allowToastClose: true,
						bgColor: '#b13d3d',
						textColor: 'white',
						hideAfter: false,
						beforeShow: function () {
							runFormValidation();
						},
						afterShown: function () {
							$('#toast-ok').off('click').on('click', function(e) {
								var bValid = true;
								setTimeout(function() {
									$(e.target).parents('form').find('[name]').each(function(i, elem) {
										if ($(elem).hasClass('error')) bValid = false;
									});
									if (bValid) {
										if (response && (typeof response.callback == 'string')) {
											var fn = eval(response.callback);
											if (typeof fn == 'function') {
												fn(response.data, $(e.target).parents('form').serializeArray());
											}
										}
										if (response && (typeof response.callback == 'function')) {
											response.callback(response.data, $(e.target).parents('form').serializeArray());
										}
										$('#toast-cancel').off('click');
										$('.close-jq-toast-single:visible').trigger('click');
										setTimeout(function() {
											if (response && (typeof response.redirect == 'string')) {
												if (response.redirect) window.location = response.redirect;
											}
										}, 300);
									}
								}, 300);
							});
							$('#toast-cancel').off('click').on('click', function(e) {
								if (response && (typeof response.cancel == 'function')) {
									response.cancel(response.data);
								}
								$('#toast-ok').off('click');
								$('.close-jq-toast-single:visible').trigger('click');
								setTimeout(function() {
									if (response && (typeof response.redirect == 'string')) {
										if (response.redirect) window.location = response.redirect;
									}
								}, 300);
							});
						},
					});
				} else {
					console.error('prompt form not supplied!');
				}
			break;
		}
	}
	return bConfirmed;
}

function getParameterByName(name, url) {
	if (url == undefined) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	// console.log(results);
	if (!results) return null;
	if (!results[2]) return '';

	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function copyToClipboard(text) {
	var textArea = document.createElement("textarea");
	textArea.value = text;
	document.body.appendChild(textArea);       
	textArea.select();
	try {
		var successful = document.execCommand('copy');
		var msg = successful ? 'successful' : 'unsuccessful';
		console.log('Copying text command was ' + msg);
	} catch (err) {
		console.log('Oops, unable to copy', err);
	}    
	document.body.removeChild(textArea);
	return successful;
}

var runMediaUploader = function(callback) {
	if ($('.input_upload_images').length) {
		$('.input_upload_images').each(function(i, elem) {
			$(elem).off('change').on('change', function(){
				var checked = "", uiForm = $(elem).parents('form:first');
				if ($(elem)[0].files.length == 1) checked = "checked";
				
				var arUis = [], arImageUi = [];
				for (var i= 0; i < $(elem)[0].files.length; i++) {
					var blob_path = window.URL.createObjectURL(elem.files[i]),
					is_upload = uiForm.data('notmedia') ? 1 : 0;
					var name = uiForm.attr('id') != undefined ? uiForm.attr('id') : ($(elem).data('name') ? $(elem).data('name') : 'galleries');
					var sImageUi = '<li data-toggle="tooltip" data-placement="top" title="Select Image"><div class="preview-image-item" style="background-image: url('+blob_path+')"></div><input type="radio" name="'+name+'[index]" '+checked+' value="'+i+'" required data-upload="'+is_upload+'" data-url-path="BLOB_FILE" /></li>';
					arImageUi.push(sImageUi);
					var filePromise = new Promise(resolve => {
						var reader = new FileReader();
						reader.readAsDataURL(elem.files[i]);
						reader.onload = () => resolve(reader.result);
					});
					filePromise.then(fileContents => { arUis.push(fileContents); });
				}
				// console.log(arUis, arImageUi);
				setTimeout(function() {
					$.each(arImageUi, function(i, ui) {
						var li = $(ui);
						var uiVal = li.find('[data-url-path]').attr('data-url-path', arUis[i]);
						uiForm.find('.preview_images_list').append(uiVal.parents('li'));
					});
				}, 300);

				$('[data-toggle="tooltip"]').tooltip();
				if (uiForm.parent('.dash-panel.theme[class*=score-]').length) {
					var position = uiForm.find('[name="pos"]').val();
					var iTop = (uiForm.parent('.dash-panel.theme.score-'+position).offset().top - ($('nav').height() + $('.dash-panel.score-detail-scroll').height() + 1));
					$("html,body").stop().animate({ scrollTop: iTop, scrollLeft: 0 }, 500);
				}
			}).on('click', function(e) {
				$(elem).parents('form:first').find('.preview_images_selected li').removeClass('error').find('input:radio').removeClass('error');
				setInterval(function() {
					if ($(elem).parents('form:first').find('input:file').val() == '') {
						if ($(elem).parents('form:first').find('.preview_images_selected li input:radio').attr('required') == undefined) {
							$(elem).parents('form:first').find('.preview_images_selected li input:radio').attr({'required':'required'});
							$(elem).parents('form:first').find('input:file').removeAttr('required');
							$(elem).parents('form:first').find('button[value="upload"]').addClass('hide');
							$(elem).parents('form:first').find('button[value="select"]').removeClass('hide');
						}
					} else {
						if ($(elem).parents('form:first').find('.preview_images_selected li input:radio').attr('required') != undefined) {
							// clearInterval(filetime);
							$(elem).parents('form:first').find('.preview_images_selected li input:radio').removeAttr('checked').removeAttr('required');
							$(elem).parents('form:first').find('button[value="upload"]').removeClass('hide');
							$(elem).parents('form:first').find('button[value="select"]').addClass('hide');
						}
					}
				}, 1000);
				$(elem).parents('form:first').find('.preview_images_list').html('');
				$(elem).parents('form:first').find('input:file').attr('required', 'required');
				if ($('body').hasClass('save-veggy') || $('#media_modal:visible').length) {
					$(elem).parents('form:first').find('.preview_images_list li input:radio').attr({'required':'required'});
					$(elem).parents('form:first').attr('data-notmedia', '1');
					if ($(elem).parents('form:first').find('button[value="select"]').length) {
						$(elem).parents('form:first').find('button:submit').addClass('hide');
						$(elem).parents('form:first').find('button[value="upload"]').removeClass('hide');
					}
					$(elem).parents('form:first').find('.preview_images_selected li input:radio').removeAttr('checked').removeAttr('required');
				}
			}).next('.input-group-btn').find('button').on('click', function(e) {
				$(elem).trigger('click');
			});
		});

		$(document.body).on('click', '.preview-image-item', function() {
			var oThis = $(this);
			if (oThis.parents('ul').hasClass('preview_images_list')) {
				oThis.parents('form:first').find('.preview_images_list li input:radio').attr({'required':'required'});
				oThis.parents('form:first').find('.preview_images_selected li input:radio').removeAttr('checked').removeAttr('required');
				oThis.parents('form:first').find('input:file').attr({'required':'required'});
			} else if (oThis.parents('ul').hasClass('preview_images_selected')) {
				oThis.parents('form:first').find('.preview_images_selected li input:radio').attr({'required':'required'});
				oThis.parents('form:first').find('.preview_images_list').html('');
				oThis.parents('form:first').find('input:file').removeAttr('required').prop('value', '').val('');
			}
			/*for the media modal*/
			if ($('#media_modal:visible').length) {
				if ($('body').hasClass('save-veggy') == false) {
					$(this).parents('form:first').find('button:submit').addClass('hide');
				}
				if (oThis.next('input:radio').data('upload') == 1) {
					oThis.parents('form:first').find('button[value="upload"]').removeClass('hide');
					oThis.parents('form:first').attr('data-notmedia', '1');
				} else {
					oThis.parents('form:first').find('button[value="select"]').removeClass('hide');
					oThis.parents('form:first').find('input:file').removeAttr('required');
					oThis.parents('form:first').removeAttr('data-notmedia');
				}
			}

			oThis.next('input[type="radio"]').prop('checked', true);
			if (typeof callback == 'function') callback(this);
		});

	}
}

function setDragEvent(marker, infowindow) {
	google.maps.event.addListener(marker, 'dragend', function() {
		fnDragEnd(marker);
	});
}

function fnDragEnd(marker, isNew) {
	if (isNew == undefined) isNew = false;
	var uiInputAddress = $('#address_2');
	map.setCenter(marker.getPosition());
	// infowindow.open(map, marker);

	var position = marker.getPosition();
	$('#lat').attr('value', position.lat());
	$('#lng').attr('value', position.lng());

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		latLng: position
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (isNew == false) {
				// console.log(results);
				if (results[1]) {
					var arVal = [];
					var city = null, province = null, region = null, country = null, countryCode = null;
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
						if (!province && result.types[0] === 'administrative_area_level_2') {
							for (c = 0, lc = result.address_components.length; c < lc; c += 1) {
								component = result.address_components[c];
								if (component.types[0] === 'administrative_area_level_2') {
									province = component.long_name;
									arVal.push(province);
									break;
								}
							}
						}
						if (!region && result.types[0] === 'administrative_area_level_1') {
							for (c = 0, lc = result.address_components.length; c < lc; c += 1) {
								component = result.address_components[c];
								if (component.types[0] === 'administrative_area_level_1') {
									region = component.long_name;
									if (province != region) {
										arVal.push(region);
									} else {
										region = null;
									}
									break;
								}
							}
						}
						if (!country && result.types[0] === 'country') {
							country = result.address_components[0].long_name;
							arVal.push(country);
							countryCode = result.address_components[0].short_name;
						}
						if (city && country) {
							break;
						}
					}
					// console.log("City: " + city + ", Province: " + province + ", Region: " + region + ", Country: " + country + ", Country Code: " + countryCode);
					var sValue = arVal.join(', ');
					uiInputAddress.attr('value', sValue);
					uiInputAddress.val(sValue);
				}
			}
		} else {
			// console.log(status);
			uiInputAddress.attr('value', '');
			uiInputAddress.val('');
			$('#address_1').val(savedAddress1);
		}
	});
}

function resetMap(oThisLatLong) {
	var isNew = oThisLatLong == undefined ? true : false;
	if (isNew) {
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
	fnDragEnd(newMark, isNew);

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
	var i = setInterval(function() {
		if (typeof google != 'undefined') {
			clearInterval(i);
			map = new google.maps.Map($('#map-box').get(0), {
				zoom: hasLatlong ? 12 : 10,
				center: oLatLong,
				gestureHandling: "cooperative",
				draggableCursor: 'pointer'
			});

			infowindow = new google.maps.InfoWindow({
				content: '<b>Drag Me! or Click the Map!</b>',
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

			google.maps.event.addListener(map, "contextmenu", function(event) {
				map.setCenter(marker.getPosition());
				infowindow.open(map, marker);
				map.setZoom(12);
			});

			google.maps.event.addListener(map, "click", function(event) {
				// console.log(event);
				var lat = event.latLng.lat();
				var long = event.latLng.lng();
				
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
				map.setZoom(map.getZoom());
			});
			// markers.push(marker);
			setDragEvent(marker, infowindow);
			
			setAutocompleteEvent();
		}
	}, 1000);
}

function initMapLocations() {
	// console.log(oLatLong);
	savedAddress1 = $('#address_1').val();
	savedAddress2 = $('#address_2').val();
	if ($.trim($('#lat').val()).length && $.trim($('#lng').val()).length) {
		hasLatlong = true;
		oLatLong = {'lat': parseFloat($('#lat').val()), 'lng': parseFloat($('#lng').val())};
	}
	savedLatLng = oLatLong;
	loadMap(oLatLong);

	$('#reset-to-prev-btn, #undo-btn').off('click').on('click', function() {
		$('#shipping-id').remove();
		resetMap();
		$('#address_2').val('');
	});
}

function setAutocompleteEvent() {
	var input = $('#search-place').get(0);
	if (autocomplete != undefined) google.maps.event.clearListeners(autocomplete, 'place_changed');
	// console.log(autocomplete, google.maps.event);
	autocomplete = new google.maps.places.Autocomplete(input, {
		componentRestrictions: {country: "ph"},
	});

	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		$('#address_1').val('');
		var place = autocomplete.getPlace();
		// console.log(place);
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

function formatDate(date) {
	if (date == undefined) {
		var d = new Date();
	} else {
		var d = new Date(date);
	}
	var month = '' + (d.getMonth() + 1),
	day = '' + d.getDate(),
	year = d.getFullYear();

	if (month.length < 2) 
		month = '0' + month;
	if (day.length < 2) 
		day = '0' + day;

	return [year, month, day].join('-');
}