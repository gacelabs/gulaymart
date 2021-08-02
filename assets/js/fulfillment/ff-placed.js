$(document).ready(function() {
	runDomReady();
});

var updateFulfillmentsCounts = function(noAlert) {
	if (noAlert == undefined) noAlert = false;

	var iCnt = $('.order-table-item:not(.was-cancelled)').length,
	iInitCancelCnt = parseInt($('.ff-navbar-pill.cancelled').find('kbd').text()),
	iFinalCancelCnt = $('.order-table-item.was-cancelled').length,
	iNavCnt = parseInt($('#nav-fulfill-count').text());

	if (iCnt == 0) {
		$('.ff-navbar-pill.active').find('kbd').addClass('no-count').text('');
		$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
	} else {
		$('.ff-navbar-pill.active').find('kbd').removeClass('no-count').text(iCnt);
		$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeOut('slow').addClass('hide');
	}

	if (noAlert == false) {
		setTimeout(function() {
			runAlertBox({type:'info', message: 'Order added to Cancelled Fulfillments'/*, unclose:true*/});
			$('.order-table-item.was-cancelled').fadeOut('slow');
			setTimeout(function() {
				$('.order-table-item.was-cancelled').remove();
			}, 1000);
		}, 3000);
	}

	var iNavLastCnt = (iNavCnt-iFinalCancelCnt < 0) ? false : iNavCnt-iFinalCancelCnt;
	if (iNavLastCnt != false) {
		$('#nav-fulfill-count').text(iNavLastCnt);
	} else {
		$('#nav-fulfill-count').hide();
	}

	var iCancelCnt = (iInitCancelCnt+iFinalCancelCnt < 0) ? false : iInitCancelCnt+iFinalCancelCnt;
	if (iCancelCnt != false) {
		$('.ff-navbar-pill.cancelled').find('kbd').removeClass('no-count').text(iCancelCnt);
	} else {
		$('.ff-navbar-pill.cancelled').find('kbd').addClass('no-count').text('');
	}
}

var runOrdersToFulfillments = function(realtime) {
	console.log('Listening from Orders activity!');
	// console.log(realtime);
	realtime.bind('remove-fulfilled-items', 'remove-item', function(object) {
		var oData = object.data;
		// console.log(oData);
		if (Object.keys(oData.seller_id).length) {
			if ($.inArray(oUser.id, oData.seller_id) >= 0) removeOnFulfillment(oData);
		} else {
			if (oData.seller_id == oUser.id) removeOnFulfillment(oData);
		}
	});
}

var removeOnFulfillment = function(obj) {
	// console.log(obj);
	if (Object.keys(obj).length) {
		var bCanceledCnt = false;
		$.each(obj.data, function(i, data) {
			if (obj.all == 0) {
				$('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').addClass('was-cancelled').find('[js-element="selectItems"]').html('<p class="zero-gaps">Cancelled</p><p class="zero-gaps">Removed by buyer</p>');
			} else {
				$('[data-merge-id="'+data.merge_id+'"]').find('[js-element*="item-id-"]').each(function(i, elem) {
					$(elem).addClass('was-cancelled').find('[js-element="selectItems"]').html('<p class="zero-gaps">Cancelled</p><p class="zero-gaps">Removed by buyer</p>');
				});
			}

			$('[data-merge-id="'+data.merge_id+'"]').each(function(i, elem) {
				if ($(elem).find('[js-element*="item-id-"]:not(.was-cancelled)').length == 0) {
					$(elem).addClass('was-cancelled').find('select,button,input:button,input:submit').remove();
					$(elem).find('[js-element*="item-id-"]').removeClass('was-cancelled');
					$(elem).find('[js-element="proceed-panel"]').find('b').text('CANCELLED');
					$('<p>'+($.format.date(new Date().getTime(), 'MMM d, yyyy'))+'</p>').insertAfter($(elem).find('[js-element="proceed-panel"]'));
					bCanceledCnt = true;
				}
			});
		});

		if (bCanceledCnt) updateFulfillmentsCounts();
	}
}