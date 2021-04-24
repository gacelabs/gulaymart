$(document).ready(function() {
	runFormValidation();
});

jQuery.validator.addMethod("emailExt", function(value, element, param) {
	return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
},'Your E-mail is wrong');

function runFormValidation(forms) {
	if (forms == undefined) {
		forms = $(document.body).find('.form-validate');
	} else {
		forms = $(forms);
	}
	// console.log(forms);
	forms.each(function(i, elem) {
		var form = $(elem);
		if (form.data('disable') != undefined) {
			switch (form.data('disable')) {
				case 'enter':
					form.on('keyup keypress', function(e) {
						var keyCode = e.keyCode || e.which;
						if (keyCode === 13) { 
							e.preventDefault();
							return false;
						}
					});
				break;
			}
		}
		form.validate({
			ignore: '.ignore',
			errorPlacement: function(error, element) {
				if (element.hasClass('chosen')) {
					element.parent('div').find('.chosen-container-single').addClass('error');
					element.parent('div').find('.chosen-container-multi .chosen-choices').addClass('error');
				} else if (element.attr('type') == 'hidden') {
					element.parents('.form-group').addClass('error');
				} else if (element.attr('type') == 'checkbox') {
					element.addClass('error').closest('label').addClass('error');
				} else if (element.attr('type') == 'radio') {
					element.addClass('error').parent().addClass('error').siblings().addClass('error');
				} else {
					element.addClass('error');
				}
			},
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
				}
			},
			rules: oValidationRules, /*find in mainpage.php head tag*/
			submitHandler: function(form, e) {
				if ($(form).data('ajax')) {
					e.preventDefault();
					var isFileExists = $(form).find('input:file').length > 0 ? $(form).find('input:file') : false;
					formAjax(form, isFileExists);
				} else {
					form.submit();
				}
			}
		});
	});


}