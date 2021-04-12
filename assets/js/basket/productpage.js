$(document).ready(function() {
	var oSavedData = {};
	/*avoid changing the min and max in the ui console*/
	$('.variety-location').find('.input-number').each(function(i, elem) {
		oSavedData[i] = {
			ui: elem,
			min: parseInt($(elem).attr('min')),
			max: parseInt($(elem).attr('max')),
		}
	});

	//http://jsfiddle.net/laelitenetwork/puJ6G/
	$('.btn-number').click(function(e){
		e.preventDefault();
		var oThis = $(this);

		fieldName = oThis.attr('data-field');
		type      = oThis.attr('data-type');

		var input = oThis.parents().eq(1).find("input[name='"+fieldName+"']");
		var currentVal = parseInt(input.val());

		var minValue =  parseInt(input.attr('min'));
		var maxValue =  parseInt(input.attr('max'));
		/*preventing changes done in console*/
		$.each(oSavedData, function(i, data) {
			if (oThis.parents('.variety-location').find('.input-number:visible').is(data.ui)) {
				minValue = data.min;
				maxValue = data.max;
			}
		});
		// console.log(minValue, maxValue, currentVal);

		if (!isNaN(currentVal)) {
			if(type == 'minus') {

				if(currentVal > minValue) {
					input.val(currentVal - 1).change();
				} 
				if(parseInt(input.val()) == minValue) {
					oThis.attr('disabled', true);
				}

			} else if(type == 'plus') {

				if(currentVal < maxValue) {
					input.val(currentVal + 1).change();
				}
				if(parseInt(input.val()) == maxValue) {
					oThis.attr('disabled', true);
					$('.max-qty').removeClass('text-danger');
				}

			}
		} else {
			input.val(0);
		}
		$('.max-qty').removeClass('text-danger');
	});

	$('.input-number').focusin(function(){
		$(this).data('oldValue', $(this).val());
		$('.max-qty').removeClass('text-danger');
	});

	$('.input-number').change(function() {
		var oThis = $(this);

		var minValue =  parseInt($(this).attr('min'));
		var maxValue =  parseInt($(this).attr('max'));

		/*preventing changes done in console*/
		$.each(oSavedData, function(i, data) {
			// console.log(oThis.is(data.ui));
			if (oThis.is(data.ui)) {
				minValue = data.min;
				maxValue = data.max;
			}
		});

		valueCurrent = parseInt($(this).val());
		name = $(this).attr('name');
		eThis = $(this);
		// console.log(minValue, maxValue, valueCurrent);

		if(valueCurrent >= minValue) {
			$(this).parents('.variety-location').find(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled');
		} else {
			$(this).val($(this).data('oldValue'));
			$('.max-qty').removeClass('text-danger');
		}
		if(valueCurrent <= maxValue) {
			$(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled');
		} else {
			$(this).parents('.variety-location').find('.elem-block .max-qty').addClass('text-danger');
			$(this).val($(this).data('oldValue'));
		}
	});

	$(".input-number").keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
			(e.keyCode == 65 && e.ctrlKey === true) || 
			(e.keyCode >= 35 && e.keyCode <= 39)) {
			return;
		}

		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}

		$('.max-qty').removeClass('text-danger');
	});

	// collapse summary
	$('.condition-collapser').click(function() {
		$(this).next('.productpage-summary-inner').toggleClass('active');
	});

	$('#add_product_btn').click(function(e) {
		e.stopPropagation();
		$('#nav_basket').find('.notif-dot').remove();	
		$('#nav_basket').append('<i class="fa fa-circle text-success notif-dot"></i>');	
	});

	// thubmnail preview
	$('#img_thumb_list>li').click(function() {
		var imgSrc = $(this).find('div.img-thumb-item').css('background-image'),
			bg = imgSrc.replace('url(','').replace(')','').replace(/\"/gi, "");

		$('.img-thumb-item').removeClass('active');
		$(this).find('.img-thumb-item').addClass('active');
		$('#main_img_preview').css({'background-image' : 'url('+bg+')'});
	});

});