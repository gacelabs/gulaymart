$(document).ready(function() {
	$('[js-event="deliveryDate"]').click(function() {
		if ($(this).val() == "deliver_now") {
			$('input[name="delivery_date"]').attr('disabled', true).attr('value', '').val('');
		} else {
			$('input[name="delivery_date"]').removeAttr('disabled').prop('required', true);
		}
	});

	$('[js-event="addBasketItemselect"]').change(function() {
		$(this).parents('.order-item-grid').toggleClass('active');

		var	prodPrice = $(this).parents('.order-item-grid').find('.product-item-price').text(),
			prodQty = $(this).parents('.order-item-grid').find('.order-qty-input').val(),
			shipFee = $(this).parents('.order-item-grid').find('.shipping-fee').text();

		if ($('[js-event="addBasketItemselect"]:checked').length > 0) {
			$('[js-event="removeBasketItemBtn"]').removeClass('hide');
		} else {
			$('[js-event="removeBasketItemBtn"]').addClass('hide');
		}

		if (this.checked) {
			$(this).parents('.order-item-grid').find('.selected-product-price').text((parseInt(prodPrice) * prodQty) + parseInt(shipFee));
		}
	});

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


	var iGrandTotal = parseFloat($('[js-element="grandtotal"]').text());
	$('[js-event="qty"]').bind('input', function() {
		var oThis = $(this);
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
				var iPrice = Number((parseFloat(data.price) * parseInt(oThis.val()) + parseFloat(data.fee))).toLocaleString();
				$('[js-element="itemtotal-'+data.id+'"]').text(iPrice);
			}
		});
		var iTotal = 0;
		$('[js-elem="sub-itemtotal"]').each(function(i, elem) {
			var x = parseFloat($(elem).text().replace(',', ''));
			iTotal += x;
		});
		$('[js-element="grandtotal"]').text(iTotal.toLocaleString());
	});
});