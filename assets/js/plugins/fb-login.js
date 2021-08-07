$(document).ready(function() {
	if (oUser == false) {
		$('.fb-login-btn').off('click').on('click', function(e) {
			FB.login(function(response) {
				// console.log(response);
				if (response.status === 'connected') {
					runFbLogin();
				} else{
					$('#login_modal').modal('hide');
				}
			}, {scope: 'public_profile, email'});
		});
	}
});

var runFbLogin = function(data) {
	$('#login_modal').find('[data-dismiss="modal"]').addClass('hide');
	$('#login_modal').find('[href="register"]').addClass('hide');
	$('.login-detail').addClass('hide');
	$('.login-form-body').addClass('hide');
	$('.fb-login-btn').addClass('hide');
	$('.fb-login-panel').removeClass('hide');
	$('.fb-signing-in').removeClass('hide');
	FB.api('/me?fields=id,email,name', function(data) {
		FB.getLoginStatus(function(response) {
			data.fbauth = response;
			simpleAjax('authenticate/fb_login', data);
		});
	});
};

var ifb, fbCnt = 0;
var enterFBEmailAddress = function(oData) {
	delete oData.elem;
	// console.log(oData);
	$('#invalid-fb-email-form').off('submit').on('submit', function(e) {
		e.preventDefault(); e.returnValue;
		var bValid = true;
		$(e.target).find('[name]').each(function(i, elem) {
			$(elem).off('input blur').on('input blur', function(f) {
				if ($(f.target).val() !== '' && /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(f.target).val())) {
					$(f.target).removeClass('error');
				}
			});
			if ($(elem).val() === '' || /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test($(elem).val()) == false) {
				$(elem).addClass('error');
				bValid = false;
			}
		});
		// console.log(bValid);
		if (bValid) {
			var oFormData = $(e.target).serializeArray();
			if (Object.keys(oFormData).length) {
				for (var key in oFormData) {
					var oItem = oFormData[key];
					if (Object.keys(oItem).length) {
						oData[oItem.name] = oItem.value;
					}
				}
			}
			$('#login_modal').find('[data-dismiss="modal"]').addClass('hide');
			$('.ask-sign-in').addClass('hide');
			$('.login-form-body').addClass('hide');
			$('#invalid-fb-email-form').addClass('hide');
			$('#fb-bad').addClass('hide');

			$('.fb-signing-in').removeClass('hide');
			$('.login-form-body').find('form').removeClass('hide');
			$('#fb-good').removeClass('hide');
			// console.log(oData);
			FB.getLoginStatus(function(response) {
				oData.fbauth = response;
				simpleAjax('authenticate/fb_login', oData);
			});
		}
	});

	$('#login_modal').find('[data-dismiss="modal"]').removeClass('hide');
	$('.fb-signing-in').addClass('hide');
	$('.ask-sign-in').removeClass('hide');
	$('.login-form-body').find('form').addClass('hide');
	$('.login-form-body').removeClass('hide');
	
	$('#fb-good').addClass('hide');
	$('#fb-bad').removeClass('hide');
	$('#invalid-fb-email-form').removeClass('hide');
}