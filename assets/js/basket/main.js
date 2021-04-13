$(document).ready(function() {
	var oOrderType = {};
	$('.order-item-image').click(function() {
		$(this).parent().prev().find('[js-event="addBasketItemselect"]').trigger('click');
	});
	$('[js-event="deliveryDate"]').click(function() {
		oOrderType['type'] = $(this).val();
		if ($(this).val() == "deliver_now") {
			$('input[name="delivery_date"]').attr('disabled', true).attr('value', '').val('');
		} else {
			$('input[name="delivery_date"]').removeAttr('disabled').prop('required', true);
			$('input[name="delivery_date"]').off('change').on('change', function(e) {
				oOrderType['date'] = $(e.target).val();
			});
		}
	});
	if ($('[js-event="deliveryDate"]').val() == "deliver_now") {
		oOrderType['type'] = 'deliver_now';
	} else if ($('[js-event="deliveryDate"]').val() == "deliver_scheduled") {
		oOrderType['type'] = 'deliver_scheduled';
		oOrderType['date'] = $('input[name="delivery_date"]').val();
	}

	var oOrdersChecked = {};
	$('[js-event="addBasketItemselect"]').bind('change', function() {
		$(this).parents('.order-item-grid').toggleClass('active');
		var uiQtyField = $(this).parents('.order-item-grid').find('[js-element="order"] input[js-event="qty"]');

		var	prodPrice = $(this).parents('.order-item-grid').find('.product-item-price').text(),
			prodQty = $(this).parents('.order-item-grid').find('.order-qty-input').val(),
			shipFee = $(this).parents('.order-item-grid').find('.shipping-fee').text();

		if ($('[js-event="addBasketItemselect"]:checked').length > 0) {
			$('[js-event="removeBasketItemBtn"]').removeClass('hide');
			$('[js-element="checkout"]').removeClass('disabled').removeAttr('disabled');
		} else {
			$('[js-event="removeBasketItemBtn"]').addClass('hide');
			$('[js-element="checkout"]').addClass('disabled').attr('disabled', 'disabled');
		}

		$('[js-element="itemtotal-'+this.dataset.id+'"]').parent('.product-amount').remove();
		if (this.checked) {
			oOrdersChecked[this.dataset.id] = {id: parseInt(this.dataset.id), checked: 1, quantity: parseInt(uiQtyField.val())};
			// $(this).parents('.order-item-grid').find('.selected-product-price').text((parseInt(prodPrice) * prodQty) + parseInt(shipFee));
			var iPrice = Number(parseFloat(this.dataset.price)).toLocaleString();
			$('.tender-amount-body').append('<p class="product-amount zero-gaps">&#x20b1; <b js-elem="sub-itemtotal" js-element="itemtotal-'+this.dataset.id+'">'+iPrice+'</b></p>');
		} else {
			if (oOrdersChecked[this.dataset.id] != undefined) {
				oOrdersChecked[this.dataset.id].checked = 0;
			}
		}
		uiQtyField.trigger('input');
		$('[js-element="checkout"]').trigger('click');
	});
	setTimeout(function() {
		$('[js-event="addBasketItemselect"]').trigger('change');
	}, 700);

	var oSavedData = {};
	$('[js-event="qty"]').each(function(i, elem) {
		oSavedData[i] = {
			ui: elem,
			id: $(elem).attr('js-id'),
			min: parseInt($(elem).attr('min')),
			max: parseInt($(elem).attr('max')),
			price: parseFloat($(elem).attr('js-price')),
			fee: parseFloat($(elem).attr('js-fee')),
		}
	});

	$('[js-event="qty"]').bind('input', function() {
		var oThis = $(this);
		oThis.val(parseInt(oThis.val())); /*no decimals allowed*/
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
				var iPrice = Number(parseFloat(data.price) * parseInt(oThis.val())).toLocaleString();
				$('[js-element="itemtotal-'+data.id+'"]').text(iPrice);

				if (oOrdersChecked[data.id] != undefined) {
					oOrdersChecked[data.id].quantity = parseInt(oThis.val());
				}
			}
		});
		var iTotal = 0; // parseFloat(data.fee)
		$('[js-elem="sub-itemtotal"]').each(function(i, elem) {
			var x = parseFloat($(elem).text().replace(',', ''));
			iTotal += x;
		});
		// console.log(iTotal);
		if (iTotal == 0) {
			$('[js-element="grandtotal"]').text('0.00');
		} else {
			$('[js-element="grandtotal"]').text(Number(iTotal).toLocaleString());
		}
	});

	var oRemoveBasketAjax = false;
	$('[js-event="removeBasketItemBtn"]').bind('click', function(e) {
		var arData = [];
		$('[js-event="addBasketItemselect"]:checked').each(function(i, elem) {
			arData.push({id : $(elem).data('id')});
		});
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
				uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> Busy...');
			},
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			},
			complete: function(xhr, status) {
				uiButtonSubmit.html(uiButtonSubmit.data('orig-ui'));
				uiButtonSubmit.removeAttr('disabled');
			}
		};
		if (oRemoveBasketAjax != false && oRemoveBasketAjax.readyState !== 4) oRemoveBasketAjax.abort();
		oRemoveBasketAjax = $.ajax(oSettings);
	});

	$('[js-element="checkout"]').bind('click', function(e) {
		e.preventDefault();
		if (Object.keys(oOrdersChecked).length) {
			var oData = {data: oOrdersChecked};
			$.extend( oData, oOrderType );
			console.log(oData);
			simpleAjax('basket/verify', oData, $(e.target));
		}
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
			var ui = $('[js-event="addBasketItemselect"][data-id="'+data.id+'"]:checked');
			$('[js-element="itemtotal-'+data.id+'"]').parent('.product-amount').remove();
			ui.parents('.add-basket-item-container.order-item-inner').fadeOut();
			$('[js-event="qty"]').trigger('input');
		});
	}
}