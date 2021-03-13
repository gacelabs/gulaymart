
var oFormAjax = false, formAjax = function(form, uploadFile) {
	if (typeof $.ajax == 'function') {
		if (form != undefined && form instanceof HTMLElement) {
			if (uploadFile == undefined) uploadFile = false;
			$('form').removeClass('active-ajaxed-form');

			var uiButtonSubmit = $(form).find('[type=submit]'),
			callbackFn = $(form).data('callback'),
			lastButtonUI = uiButtonSubmit.html(),
			oSettings = {
				url: form.action,
				type: form.method,
				dataType: 'jsonp',
				// timeout: 1000,
				// jsonpCallback: 'echo',
				beforeSend: function(xhr, settings) {
					$(form).addClass('active-ajaxed-form');
					uiButtonSubmit.attr('data-orig-ui', lastButtonUI);
					uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> Processing ...');
				},
				success: function(data) {
					if (data && data.success) {
						console.log(data);
					}
				},
				error: function(xhr, status, thrown) {
					console.log(status, thrown);
				},
				complete: function(xhr, status) {
					uiButtonSubmit.html(uiButtonSubmit.data('orig-ui'));
					uiButtonSubmit.removeAttr('disabled');
					var fn = eval(callbackFn);
					if (typeof fn == 'function') {
						fn(form, xhr, uploadFile);
					}
					if (window.location.pathname.indexOf('new') >= 0) {
						form.reset();
					}
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
			console.log('form elements only!');
		}
	} else {
		console.log('ajax function not loaded!');
	}
}

var oSimpleAjax = false, simpleAjax = function(url, data) {
	if (data == undefined) data = {};
	if (url == undefined) url = false;
	if (url) {
		var oSettings = {
			url: url,
			type: 'post',
			data: data,
			dataType: 'json',
			beforeSend: function(xhr, settings) {},
			success: function(data) {
				if (data && data.success) {
					if (data.redirect) window.location = data.redirect;
				}
			},
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			},
			complete: function(xhr, status) {}
		};
		if (oSimpleAjax != false && oSimpleAjax.readyState !== 4) oSimpleAjax.abort();
		oSimpleAjax = $.ajax(oSettings);
	}
}

var ajaxSuccessResponse = function(response) {
	console.log(response);
	var iConfirmed = true;
	if (response && response.type) {
		iConfirmed = runAlertBox(response, undefined, iConfirmed);
	}
	if (iConfirmed) {
		if (response && (typeof response.callback == 'string')) {
			var fn = eval(response.callback);
			if (typeof fn == 'function') {
				fn(response.data);
			}
		}
		setTimeout(function() {
			if (response && (typeof response.redirect == 'string')) {
				if (response.redirect) window.location = response.redirect;
			}
		}, 1000);
	}
}

window.mobileAndTabletCheck = function() {
	let check = false;
	(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
};

String.prototype.isEmpty = function() {
	return (this.length === 0 || !this.trim());
};

var runAlertBox = function(response, heading, iConfirmed) {
	if (typeof response == 'object') {
		switch (response.type.toLowerCase()) {
			case 'confirm': case 'confirmation': 
				if (heading == undefined) heading = 'Confirmation';
				iConfirmed = false;
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
							setTimeout(function() {
								$('.close-jq-toast-single:visible').trigger('click');
								if (response && (typeof response.redirect == 'string')) {
									if (response.redirect) window.location = response.redirect;
								}
							}, 300);
						});
						$('#toast-cancel').off('click').on('click', function(e) {
							$('.close-jq-toast-single:visible').trigger('click');
						});
					},
				});
			break;
			case 'success':
				if (heading == undefined) heading = 'Success';
				$.toast({
					heading: heading,
					text: response.message,
					icon: 'success',
					loader: true,
					stack: false,
					position: 'top-center',
					allowToastClose: true,
					bgColor: 'darkgreen',
					textColor: 'white'
				});
			break;
			case 'error': case 'danger':
				if (heading == undefined) heading = 'Error';
				$.toast({
					heading: heading,
					text: response.message,
					icon: 'error',
					loader: false,
					stack: false,
					position: 'top-center',
					allowToastClose: true,
					bgColor: 'red',
					textColor: 'white',
					hideAfter: false
				});
			break;
			case 'info': case 'information':
				if (heading == undefined) heading = 'Information';
				$.toast({
					heading: heading,
					text: response.message,
					icon: 'info',
					loader: false,
					stack: false,
					position: 'top-center',
					allowToastClose: true,
					bgColor: 'blue',
					textColor: 'white',
					hideAfter: false
				});
			break;
			case 'warning': case 'warn':
				if (heading == undefined) heading = 'Warning';
				iConfirmed = false;
				$.toast({
					heading: heading,
					text: response.message,
					icon: 'warning',
					loader: false,
					stack: false,
					position: 'top-center',
					allowToastClose: true,
					bgColor: '#f1ac2e',
					textColor: 'white',
					hideAfter: false
				});
			break;
		}
	}
	return iConfirmed;
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