$(document).ready(function() {
	jQuery.validator.addMethod("emailExt", function(value, element, param) {
		return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
	},'Your E-mail is wrong');

	var forms = $(document.body).find('.form-validate');

	forms.each(function(i, elem) {
		var form = $(elem);

		form.validate({
			ignore: '.ignore',
			errorPlacement: function(error, element) {
				if (element.hasClass('chosen-select')) {
					element.parent('div').find('.chosen-container-single').addClass('error');
				}
			},
			highlight: function (element, errorClass, validClass) {
				if ($(element).hasClass('chosen-select')) {
					$(element).parent('div').find('.chosen-container-single').addClass('error');
				} else if ($(element).data('error-include-parent') != undefined) {
					$(element).parents($(element).data('error-include-parent')+':first').addClass('error');
				} else {
					$(element).addClass('error');
				}
			},
			unhighlight: function (element, errorClass, validClass) {
				if ($(element).hasClass('chosen-select')) {
					$(element).parent('div').find('.chosen-container-single').removeClass('error');
				} else if ($(element).data('error-include-parent') != undefined) {
					$(element).parents($(element).data('error-include-parent')+':first').removeClass('error');
				} else {
					$(element).removeClass('error');
				}
			},
			rules: oValidationRules, /*find in mainpage.php head tag*/
			submitHandler: function(form, e) {
				if ($(form).data('ajax')) {
					e.preventDefault();
					var isFileExists = $(form).find('input:file').length > 0 ? $(form).find('input:file') : false;
					doAjax(form, isFileExists);
				} else {
					form.submit();
				}
			}
		});
	});
});

// revised and moved the codes from down here to public\requirements\js\recaptcha-loader.js