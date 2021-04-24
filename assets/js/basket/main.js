$(document).ready(function() {
	var oOrderType = {};
	$('[js-event="orderWhenSelect"]').change(function() {
		if ($(this).val() == 2) {
			$(this).next('.date-input').removeClass('hide');
			oOrderType['type'] = 'deliver_scheduled';
			oOrderType['date'] = $('[js-element="delivery-date"]').val();
		} else {
			oOrderType['type'] = 'deliver_now';
			$(this).next('.date-input').addClass('hide');
		}
	});

	$('[js-event="showOrderFooter"]').click(function() {
		$(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
		$(this).parents('.order-grid-footer').find('.order-footer-farm, .order-footer-payment').toggleClass('hidden-xs');
	});

	if ($('[js-event="orderWhenSelect"]').val() == 2) {
		oOrderType['type'] = 'deliver_scheduled';
		oOrderType['date'] = $('[js-element="delivery-date"]').val();
	} else if ($('[js-event="orderWhenSelect"]').val() == 1) {
		oOrderType['type'] = 'deliver_now';
	}

	$('[js-element="checkout-data"]').bind('click', function(e) {
		e.preventDefault();
		var oCheckoutData = $.parseJSON($(e.target).attr('js-json'));
		if (Object.keys(oCheckoutData).length) {
			$('[js-event="qty"]').each(function(i, elem) {
				$.each(oCheckoutData, function(x, data) {
					if (data.id == $(elem).attr('js-id')) {
						oCheckoutData[x].quantity = elem.value;
					}
				});
			});
			// console.log(oCheckoutData);
			var oData = {data: oCheckoutData};
			$.extend( oData, oOrderType );
			// console.log(oData);
			// $('button, a, input:submit').addClass('disabled').prop('disabled', true).attr('disabled', 'disabled');
			// simpleAjax('basket/verify/1', oData, $(e.target), true);
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
		if ($('[js-element*="item-id-"]').length == 0) {
			$('[js-element="baskets-panel"]').html('')
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
		var oThis = $(this), iVal = parseInt(oThis.val());
		oThis.val(iVal); /*no decimals allowed*/
		/*preventing changes done in console*/
		$.each(oSavedData, function(i, data) {
			// console.log(oThis.is(data.ui));
			if (oThis.is(data.ui)) {
				oThis.attr('min', data.min);
				oThis.attr('max', data.max);
				if (oThis.val() < data.min) {
					oThis.val(data.min);
				}
				if (oThis.val() > data.max) {
					oThis.val(data.max);
				}
				// var iPrice = Number(parseFloat(data.price) * parseInt(oThis.val())).toLocaleString();
				// $('[js-element="itemtotal-'+data.id+'"]').text(iPrice);
				$('[js-event="qty"]').prop('value', iVal).val(iVal);
			}
		});
	});
}