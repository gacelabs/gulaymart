$(document).ready(function() {

	$('[js-event="actionSelect"]').change(function() {
		var actionVal = $(this).val();
		if (actionVal == "5") {
			$(this).next('[js-event="reasonSelect"]').removeClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#ff7575');
		} else {
			$(this).next('[js-event="reasonSelect"]').addClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#799938');
		}
	});

	$('[js-event="actionApplyBtn"]').click(function() {
		var actionVal = $('[js-event="actionSelect"]').val(),
			cancelVal = $('[js-event="cancelReasonSelect"]').val();
		if (actionVal == "5" && !$.isNumeric(cancelVal)) {
			$('[js-event="cancelReasonSelect"]').addClass('error');
		}
	});

	$('[js-event="cancelReasonSelect"]').change(function() {
		if ($.isNumeric($(this).val())) {
			$(this).removeClass('error');
		}
	});

	$('[js-event="moreInfoBtn"]').click(function(e) {
		var thisParent = $(this).parents('.ff-item-container');

		$('.ff-item-container').removeClass('active').css('filter','blur(2px)');

		$(thisParent).addClass('active').css('filter','blur(0)');

		$('input[type="checkbox"]').prop('checked', false);

		$(thisParent).find('input[type="checkbox"]').prop('checked', true);
	});

	$('[data-dismiss="modal"]').click(function(e) {
		$('.ff-item-container').removeClass('active').css('filter','blur(0)');
		$('#more_info_container').removeClass('active');
	});

	$('#ff_invoice_modal').on('hidden.bs.modal', function () {
		$('.ff-item-container').removeClass('active').css('filter','blur(0)');
		$('#more_info_container').removeClass('active');
	});

	$('[js-event="checkAll"]').on('change', function() {
		var thisParent = $(this).parents('.ff-item-container');

		if ($(this).is(':checked')) {
			$(thisParent).find('input[type="checkbox"]').prop('checked', true);
		} else {
			$(thisParent).find('input[type="checkbox"]').prop('checked', false);
		}
	})

});

var removeOnFulfillment = function(obj) {
	// console.log(obj);
	if (Object.keys(obj).length) {
		$.each(obj, function(i, data) {
			var uiParent = $('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').parent('.order-item-list');
			$('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').addClass('removed-product').find('[js-element="selectItems"]').html('<p class="zero-gaps">Cancelled</p><p class="zero-gaps">Removed by buyer</p>');
		});

		$('.order-table-item').each(function(i, elem) {
			if ($(elem).find('[js-element*="item-id-"]:not(.removed-product)').length == 0) {
				$(elem).addClass('removed-product').fadeOut('slow');
			}
		});

		removeOnAllOrder(obj);
	}
}

var runFulfilllmentsRealtime = function(realtime) {
	// console.log(realtime);
	realtime.bind('remove-item', 'fulfilled-items', function(object) {
		// console.log('received response from remove-item:fulfilled-items', object.data);
		var oData = object.data;
		if (oData.all == 0) {
			removeOnFulfillment(oData.data);
		} else if (oData.all == 1) {
			removeOnAllOrder(oData);
		}
	});
}

var removeOnAllOrder = function(obj) {
	console.log(obj);
	if (obj.all != undefined) {
		$.each(obj.data, function(i, data) {
			if (Object.keys(data).length == 1) {
				$('[data-merge-id="'+data.merge_id+'"]').addClass('removed-product').fadeOut('fast');
			}
		});
	}
	var iCnt = $('.order-table-item:not(.removed-product)').length;
	if (iCnt == 0) {
		$('#nav-fulfill-count').remove();
		$('.ff-navbar-pill.active').find('kbd').text(0);
		$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
	} else {
		$('#nav-fulfill-count').text(iCnt);
		$('.ff-navbar-pill.active').find('kbd').text(iCnt);
		$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeOut('slow').addClass('hide');
	}
}