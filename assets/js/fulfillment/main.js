$(document).ready(function() {

	$('[js-event="actionSelect"]').change(function() {
		var actionVal = $(this).val();
		if (actionVal == "5") {
			$(this).next('[js-event="reasonSelect"]').removeClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#ff7575');
		} else {
			$(this).next('[js-event="reasonSelect"]').addClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#799938');
			$(this).next('[js-event="reasonSelect"]').val('None');
			$(this).next('[js-event="reasonSelect"]').trigger('change');
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

	$('[js-event="reasonSelect"]').change(function() {
		var oData = $(this).data('json');
		oData.reason = $(this).val();
		oData.status = $(this).prev('[js-event="actionSelect"]').val();
		// $(this).parents('.order-table-item').find('a,button,input:submit,input:button,input:text').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
		simpleAjax('fulfillment/change_status/', {data:oData}, false);
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
	});

	$('[js-element="proceed-btn"]').prop('disabled', true).attr('disabled', 'disabled');
	$('[data-merge-id]').each(function(i, elem) {
		var iRecordCount = parseInt($(elem).find('.order-item-list').attr('js-data-count'));
		var iRecordHave = $(elem).find('.order-item-list [js-element="selectItems"] select:not(.hide) option[value="6"]:selected').length;
		var iRecordOrHave = $(elem).find('.order-item-list [js-element="selectItems"] [js-data="confirmed"]').length;
		// console.log(iRecordCount == iRecordHave);
		if ((iRecordCount == iRecordHave) || iRecordCount == iRecordOrHave) {
			$(elem).find('[js-element="proceed-btn"]').prop('disabled', false).removeAttr('disabled');
		}
	});

	$('[js-element="proceed-btn"]').bind('click', function(e) {
		var id = $(this).data('merge-id');
		if (id) {
			$(this).parents('.order-table-item').find('a,select.button,input:submit,input:button,input:text').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
			simpleAjax('fulfillment/ready/', {id:id}, $(this), 12000);
		}
	});

	$('[js-element="move-trash"]').bind('click', function(e) {
		var id = $(this).data('merge-id');
		if (id) {
			$(this).parents('.order-table-item').find('a,select.button,input:submit,input:button,input:text').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
			simpleAjax('fulfillment/trash/', {id:id}, $(this));
		}
	});

});

if ($('body').hasClass('ff-placed')) {
	var removeOnFulfillment = function(obj) {
		// console.log(obj);
		if (Object.keys(obj).length) {
			$.each(obj, function(i, data) {
				$('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').addClass('was-cancelled').find('[js-element="selectItems"]').html('<p class="zero-gaps">Cancelled</p><p class="zero-gaps">Removed by buyer</p>');
			});

			$('.order-table-item').each(function(i, elem) {
				if ($(elem).find('[js-element*="item-id-"]:not(.was-cancelled)').length == 0) {
					// $(elem).addClass('was-cancelled').fadeOut('slow');
					$(elem).addClass('was-cancelled').find('select,button,input:button,input:submit').remove();
					$(elem).addClass('was-cancelled').find('[js-element="proceed-panel"]').find('b').text('CANCELLED');
					$('<p>'+($.format.date(new Date().getTime(), 'MMM d, yyyy'))+'</p>').insertAfter($(elem).addClass('was-cancelled').find('[js-element="proceed-panel"]'));
				}
			});

			removeOnAllOrder(obj);
		}
	}

	var runOrdersToFulfillments = function(realtime) {
		console.log('Listening from Orders activity!');
		// console.log(realtime);
		realtime.bind('remove-item', 'fulfilled-items', function(object) {
			var oData = object.data;
			if (oData.all == 0) {
				removeOnFulfillment(oData.data);
			} else if (oData.all == 1) {
				removeOnAllOrder(oData);
				setTimeout(function() {
					runAlertBox({type:'info', message: 'Order added to Cancelled Fulfillments', unclose:true});
					$.each(oData.data, function(i, obj) {
						if (Object.keys(obj).length == 1) {
							$('[data-merge-id="'+obj.merge_id+'"]').fadeOut('slow');
							setTimeout(function() {
								$('[data-merge-id="'+obj.merge_id+'"]').remove();
							}, 1000);
						}
					});
				}, 3000);
			}
		});
	}

	var removeOnAllOrder = function(obj) {
		// console.log(obj);
		if (obj && obj.all != undefined) {
			$.each(obj.data, function(i, data) {
				if (Object.keys(data).length == 1) {
					// $('[data-merge-id="'+data.merge_id+'"]').addClass('was-cancelled').fadeOut('fast');
					$('[data-merge-id="'+data.merge_id+'"]').find('[js-element*="item-id-"]').removeClass('was-cancelled');
					$('[data-merge-id="'+data.merge_id+'"]').addClass('was-cancelled').find('select,button,input:button,input:submit').remove();
					$('[data-merge-id="'+data.merge_id+'"]').find('[js-element="selectItems"]').html('<p class="zero-gaps">Cancelled</p><p class="zero-gaps">Removed by buyer</p>');
					$('[data-merge-id="'+data.merge_id+'"]').find('[js-element="proceed-panel"]').find('b').text('CANCELLED');
					$('<p>'+($.format.date(new Date().getTime(), 'MMM d, yyyy'))+'</p>').insertAfter($('[data-merge-id="'+data.merge_id+'"]').find('[js-element="proceed-panel"]'));
				}
			});
		}
		var iCnt = $('.order-table-item:not(.was-cancelled)').length,
		iInitCancelCnt = parseInt($('.ff-navbar-pill.cancelled').find('kbd').text()),
		iFinalCancelCnt = $('.order-table-item.was-cancelled').length;
		if (iCnt == 0) {
			$('#nav-fulfill-count').remove();
			$('.ff-navbar-pill.active').find('kbd').text(0);
			$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
			setTimeout(function() {
				runAlertBox({type:'info', message: 'Order added to Cancelled Fulfillments', unclose:true});
				$('.order-table-item.was-cancelled').fadeOut('slow');
				setTimeout(function() {
					$('.order-table-item.was-cancelled').remove();
				}, 1000);
			}, 3000);
		} else {
			$('#nav-fulfill-count').text(iCnt);
			$('.ff-navbar-pill.active').find('kbd').text(iCnt);
			$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeOut('slow').addClass('hide');
		}
		$('.ff-navbar-pill.cancelled').find('kbd').text(iInitCancelCnt+iFinalCancelCnt);
	}

	var changeOnFulfillment = function(obj) {
		var iRecordCount = parseInt($('[data-merge-id="'+obj.merge_id+'"]').find('.order-item-list').attr('js-data-count'));
		var iRecordHave = $('[data-merge-id="'+obj.merge_id+'"]').find('.order-item-list [js-element="selectItems"] select:not(.hide) option[value="6"]:selected').length;
		var iRecordOrHave = $('[data-merge-id="'+obj.merge_id+'"]').find('.order-item-list [js-element="selectItems"] [js-data="confirmed"]').length;
		// console.log(obj, iRecordCount == iRecordHave);
		if ((iRecordCount == iRecordHave) || iRecordCount == iRecordOrHave) {
			$('[data-merge-id="'+obj.merge_id+'"]').find('[js-element="proceed-btn"]').prop('disabled', false).removeAttr('disabled');
		}
	}
}