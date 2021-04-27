$(document).ready(function() {
	$('[js-event="orderWhenSelect"]').change(function() {
		if ($(this).val() == 2) {
			$(this).next('.date-input').removeClass('hide');
		} else {
			$(this).next('.date-input').addClass('hide');
		}
	});

	$('[js-element="delivery-date"]').change(function() {
		$(this).parents('.order-item').find('[js-event="orderWhenSelect"]:not(:visible)').prop('value', 2).val(2).trigger('change');
		$(this).parents('.order-item').find('[js-element="delivery-date"]:not(:visible)').prop('value', $(this).val()).val($(this).val());
	});

	$('[js-event="showOrderFooter"]').click(function() {
		$(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
		$(this).parents('.order-grid-footer').find('.order-footer-farm, .order-footer-payment').toggleClass('hidden-xs');
	});

	$('[js-element="checkout-data"]').bind('click', function(e) {
		e.preventDefault();
		var oCheckoutData = $.parseJSON($(e.target).attr('js-json'));
		if (Object.keys(oCheckoutData).length) {
			// console.log(oCheckoutData);
			var oData = {data: oCheckoutData};
			// console.log(oData);
			$(e.target).parents('.order-table-item').find('[js-event="qty"]').attr('disabled', 'disabled');
			$(e.target).parents('.order-table-item').find('a,select,button,input:button,input:submit, input:button, input:text, select').addClass('disabled').prop('disabled', true).attr('disabled', 'disabled');
			simpleAjax('basket/verify/1', oData, $(e.target), true);
		}
	});

	runQtyDefaults($('[js-event="qty"]'));

	$('[js-event="removeBasketItemBtn"]').bind('click', function(e) {
		var arData = [];
		arData.push({id : $(this).data('id'), location_id : $(this).data('location')});
		// console.log(arData);
		var uiButtonSubmit = $(e.target);
		var lastButtonUI = uiButtonSubmit.html();
		var oSettings = {
			url: 'basket/delete/',
			type: 'get',
			data: {data: arData},
			dataType: 'jsonp',
			jsonpCallback: 'gmCall',
			beforeSend: function(xhr, settings) {
				uiButtonSubmit.attr('data-orig-ui', lastButtonUI);
				uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span>');
			},
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			},
			complete: function(xhr, status) {
				uiButtonSubmit.html(uiButtonSubmit.data('orig-ui'));
				uiButtonSubmit.removeAttr('disabled');
			}
		};
		$.ajax(oSettings);
	});

	$('[js-element="remove-all"]').bind('click', function(e) {
		var oToDeleteData = [];
		$(e.target).parents('.order-table-item:first').find('[js-event="removeBasketItemBtn"]').each(function(i, elem) {
			oToDeleteData.push({id : $(elem).data('id'), location_id : $(elem).data('location')});
		});
		console.log(oToDeleteData);

		var uiButtonSubmit = $(e.target);
		var lastButtonUI = uiButtonSubmit.html();
		var oSettings = {
			url: 'basket/delete/',
			type: 'get',
			data: {data: oToDeleteData},
			dataType: 'jsonp',
			jsonpCallback: 'gmCall',
			beforeSend: function(xhr, settings) {
				uiButtonSubmit.attr('data-orig-ui', lastButtonUI);
				uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span>');
			},
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			},
			complete: function(xhr, status) {
				uiButtonSubmit.html(uiButtonSubmit.data('orig-ui'));
				uiButtonSubmit.removeAttr('disabled');
			}
		};
		if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
		oRemoveAjax = $.ajax(oSettings);
	});
});

var oRemoveAjax = false;
var removeBasketItem = function(post) {
	// console.log(post);
	if (Object.keys(post).length) {
		var oSettings = {
			url: 'basket/delete/',
			type: 'post',
			data: {data: post},
			dataType: 'jsonp',
			jsonpCallback: 'gmCall',
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			},
			success: function(data) {

			}
		};
		if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
		oRemoveAjax = $.ajax(oSettings);
	}
}

var removeOnBasket = function(obj) {
	// console.log(obj);
	if (Object.keys(obj).length) {
		$.each(obj, function(i, data) {
			var uiParent = $('[js-element="item-id-'+data.id+'"]').parent('.order-item-list');
			$('[js-element="item-id-'+data.id+'"]').fadeOut().remove();
			if (uiParent.find('.order-item').length == 0) {
				uiParent.next().remove();
				uiParent.remove();
			}
		});

		$('.order-table-item').each(function(i, elem) {
			if ($(elem).find('[js-element*="item-id-"]').length == 0) {
				$(elem).remove();
				var iCnt = $('[js-element*="item-id-"]').length;
				if (iCnt == 0) {
					$('#nav-basket-count').remove();
				} else {
					$('#nav-basket-count').text(iCnt);
				}
			}
		});
		
		if ($('[js-element*="item-id-"]').length == 0) {
			$('[js-element="baskets-panel"]').find('.no-records-ui').removeClass('hide');
		}
	}
}

var oSavedData = {};
var runQtyDefaults = function(ui) {
	ui.first().each(function(i, elem) {
		oSavedData[i] = {
			ui: elem,
			id: $(elem).attr('js-id'),
			min: parseInt($(elem).attr('min')),
			max: parseInt($(elem).attr('max')),
			price: parseFloat($(elem).attr('js-price')),
		}
	});

	ui.bind('input, change', function() {
		var oThis = $(this), iVal = parseInt(oThis.val()),
		uiItems = oThis.parents('.order-item:first');
		oThis.val(iVal); /*no decimals allowed*/
		uiItems.find('[js-event="qty"]').prop('value', iVal).val(iVal);
		/*preventing changes done in console*/
		$.each(oSavedData, function(i, data) {
			// console.log(oThis.is(data.ui));
			if (oThis.is(data.ui)) {
				oThis.attr('min', data.min);
				oThis.attr('max', data.max);
				if (oThis.val() < data.min) {
					oThis.val(data.min);
					uiItems.find('[js-event="qty"]').prop('value', data.min).val(data.min);
				}
				if (oThis.val() > data.max) {
					oThis.val(data.max);
					uiItems.find('[js-event="qty"]').prop('value', data.max).val(data.max);
				}
			}
		});
	});
}