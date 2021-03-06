$(document).ready(function() {
	runFormValidation();
});

jQuery.validator.addMethod("emailExt", function(value, element, param) {
	return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
},'Your E-mail is wrong');

jQuery.validator.addMethod("birthMonth", function(value, element, param) {
	return /^\d*[1-9]\d*$/.test(value);
},'Your Birth month is wrong');

function runFormValidation(forms) {
	var isRegistrationPage = false;
	if ($.inArray('register', Object.values(oSegments)) >= 0) {
		isRegistrationPage = true;
		jQuery.validator.addMethod("passwordChallenge", function(value, element, param) {
			var specialChars = /\W|_/g.test(value);
			if (specialChars) {
				value = value.replace(value.match(/\W|_/g), '');
			}
			var eightChars = /^[a-zA-Z0-9]{8,}$/.test(value);
			var numberChars = /^.*(?=.*\d).*$/.test(value);
			return eightChars && numberChars && specialChars;
		}, 'Password require atleast 8 letters, 1 numeric, 1 special characters');

		if ($.inArray('re_password', Object.keys(oValidationRules)) >= 0) {
			oValidationRules['password'].passwordChallenge = true;
			oValidationRules['re_password'].passwordChallenge = true;
		}
	}
	if (forms == undefined) {
		forms = $(document.body).find('.form-validate');
	} else {
		forms = $(forms);
	}
	// console.log(forms);
	forms.each(function(i, elem) {
		var form = $(elem);
		if (form.data('disable') != undefined) {
			var arr = form.data('disable').split(',');
			for (var x in arr) {
				var item = arr[x];
				if (typeof item == 'string') {
					switch ($.trim(item.toLowerCase())) {
						case 'enter':
							form.bind('keyup keypress', function(e) {
								var keyCode = e.keyCode || e.which;
								if (keyCode === 13) { 
									e.preventDefault(); e.returnValue = false;
									return false;
								}
							});
						break;
					}
				}
			}
		}
		var fields = form.find('[name][required]');
		if (fields.length) {
			fields.each(function(i, el) {
				if (oValidationRules[el.name] == undefined) {
					oValidationRules[el.name] = {required:true};
					if (el.name == 'birth_month') {
						oValidationRules[el.name].birthMonth = true;
					}
				}
			});
		}
		var recaptcha = form.find('.g-recaptcha');
		form.validate({
			ignore: '.ignore',
			errorPlacement: function(error, element) {},
			highlight: function (element, errorClass, validClass) {
				if ($(element).hasClass('chosen')) {
					$(element).parent('div').find('.chosen-container-single').addClass('error');
					$(element).parent('div').find('.chosen-container-multi .chosen-choices').addClass('error');
				} else if ($(element).attr('type') == 'hidden') {
					$(element).parents('.form-group').addClass('error');
				} else if ($(element).attr('type') == 'checkbox') {
					$(element).addClass('error').closest('label').addClass('error');
				} else if ($(element).attr('type') == 'radio') {
					$(element).addClass('error').parent().addClass('error').siblings().addClass('error');
				} else if ($(element).data('error-include-parent') != undefined) {
					$(element).parents($(element).data('error-include-parent')+':first').addClass('error');
				} else {
					$(element).addClass('error');
					$(element).parent().find('.toggle-password').addClass('invalid');
					form.find('#character_challenge').removeClass('hide');
				}
			},
			unhighlight: function (element, errorClass, validClass) {
				if ($(element).hasClass('chosen')) {
					$(element).parent('div').find('.chosen-container-single').removeClass('error');
					$(element).parent('div').find('.chosen-container-multi .chosen-choices').removeClass('error');
				} else if ($(element).attr('type') == 'hidden') {
					$(element).parents('.form-group').removeClass('error');
				} else if ($(element).attr('type') == 'checkbox') {
					$(element).removeClass('error').closest('label').removeClass('error');
				} else if ($(element).attr('type') == 'radio') {
					$(element).removeClass('error').parent().removeClass('error').siblings().removeClass('error');
				} else if ($(element).data('error-include-parent') != undefined) {
					$(element).parents($(element).data('error-include-parent')+':first').removeClass('error');
				} else {
					$(element).removeClass('error');
					form.find('.toggle-password').removeClass('invalid');
					form.find('input.password').removeClass('error');
					form.find('#character_challenge').addClass('hide');
				}
			},
			rules: oValidationRules, /*find in mainpage.php head tag*/
			submitHandler: function(form, e) {
				if ($(form).data('ajax')) {
					e.preventDefault(); e.returnValue = false;
					var isFileExists = $(form).find('input:file').length > 0 ? $(form).find('input:file') : false;
					formAjax(form, isFileExists);
				} else if (recaptcha.length == 0) {
					form.submit();
				}
			}
		});
	});
}