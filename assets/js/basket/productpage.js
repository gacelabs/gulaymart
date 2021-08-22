$(document).ready(function() {
	setSavedData();

	//http://jsfiddle.net/laelitenetwork/puJ6G/
	$('.btn-number').click(function(e){
		e.preventDefault(); e.returnValue = false;
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
				oThis.prop('min', minValue).attr('min', minValue);
				oThis.prop('max', maxValue).attr('max', maxValue);
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
			e.preventDefault(); e.returnValue = false;
		}

		$('.max-qty').removeClass('text-danger');
	});

	// collapse summary
	$('.condition-collapser').click(function() {
		$(this).next('.productpage-summary-inner').toggleClass('active');
	});

	// thubmnail preview
	$('#img_thumb_list>li').click(function() {
		var imgSrc = $(this).find('div.img-thumb-item').css('background-image'),
			bg = imgSrc.replace('url(','').replace(')','').replace(/\"/gi, "");

		$('.img-thumb-item').removeClass('active');
		$(this).find('.img-thumb-item').addClass('active');
		$('#main_img_preview').css({'background-image' : 'url('+bg+')'});
	});

	$('#buy_now_btn').bind('click', function(e) {
		e.preventDefault(); e.returnValue = false;
		var oThis = $(e.target);
		var tagName = oThis.prop('tagName');
		if (tagName != 'A') oThis = $('#buy_now_btn');
		if (Object.keys(oThis.data()).length) {
			var oData = {
				baskets: {
					location_id: oThis.data('location-id'),
					quantity: parseInt($('[name="baskets[quantity]"]').val()),
				},
				order_type: 1
			};
			// console.log(oThis.attr('href'), oData);
			simpleAjax(oThis.attr('href'), oData, oThis, 12000);
		}
	});

});

var oSavedData = {};
function setSavedData() {
	/*avoid changing the min and max in the ui console*/
	$('.variety-location').find('.input-number').each(function(i, elem) {
		oSavedData[i] = {
			ui: elem,
			min: parseInt($(elem).attr('min')),
			max: parseInt($(elem).attr('max')),
		}
	});
}

var stockChanged = function(obj) {
	// console.log(obj);
	if (obj && obj.baskets) {
		var qty = parseInt(obj.baskets.quantity);
		var stocks = parseInt(obj.baskets.rawdata.details.stocks);
		var newStocks = stocks - qty;
		$('[class="max-qty"]').text('Maximum of '+newStocks);
		$('[name="baskets[quantity]"]').prop('max', newStocks).attr('max', newStocks);
		if (newStocks <= 0) {
			$('[js-element="variety"]').html('<p>NO STOCKS AVAILABLE</p>');
			$('[ js-element="basket-btns"]').remove();
		}
		setSavedData();
	}
};

var appendComment = function(obj) {
	// console.log(obj);
	if (obj.under != undefined) {
		if (obj.under > 0) {
			$('[js-element="comment-panel-'+obj.under+'"]').replaceWith(obj.html);
			// runFormValidation($('[js-element="comment-panel-'+obj.under+'"]').find('form'));
		} else {
			$('[js-element="comment-panel-0"]').parents('.comment-box').replaceWith(obj.html);
			// $(obj.html).insertBefore($('[js-element="comment-panel-0"]'))
			// runFormValidation($('[js-element="comment-panel-'+obj.id+'"]').find('form'));
		}
		$('[js-element="comment-panel-'+obj.under+'"]').remove();
	}
};

var removeAddButtons = function(obj) {
	$('[js-element="basket-btns"]').remove();
	$('[js-element="variety"]').html('<p class="zero-gaps" style="margin-bottom:5px;">NO STOCKS AVAILABLE</p>');
	$('.buy-now-mini').remove();
}