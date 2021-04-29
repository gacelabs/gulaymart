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
			// $(this).next('[js-event="reasonSelect"]').trigger('change');
		}
		isAllSelected($(this).parents('.order-table-item').data('merge-id'));
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

	/*$('[js-event="reasonSelect"]').change(function() {
		var oData = $(this).data('json');
		oData.reason = $(this).val();
		oData.status = $(this).prev('[js-event="actionSelect"]').val();
		// $(this).parents('.order-table-item').find('a,button,input:submit,input:button,input:text').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
		simpleAjax('fulfillment/change_status/', {data:oData}, false);
	});*/

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
		isAllSelected($(elem).data('merge-id'));
	});

	$('[js-element="proceed-btn"]').bind('click', function(e) {
		var id = $(this).data('merge_id');
		if (id) {
			var arFulfillments = [];
			$('[data-merge-id="'+id+'"]').find('[js-element="selectItems"]').each(function(i, elem) {
				var oData = $(elem).find('[js-event="actionSelect"]').data();
				if (oData != undefined) {
					oData.status = $(elem).find('[js-event="actionSelect"]').val();
					oData.reason = $(elem).find('[js-event="reasonSelect"]').val();
					arFulfillments.push(oData);
				}
			});
			// console.log({merge_id: id, data: arFulfillments});
			$('[data-merge-id="'+id+'"]').find('a,select.button,input:submit,input:button,input:text').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
			simpleAjax('fulfillment/ready/', {merge_id: id, data: arFulfillments}, $(this), 12000);
		}
	});

});

if ($('body').hasClass('ff-placed')) {
	var isAllSelected = function(merge_id) {
		$('[data-merge-id="'+merge_id+'"]').each(function(i, elem) {
			var iRecordCount = parseInt($(elem).find('.order-item-list [js-element="selectItems"] [js-event="actionSelect"]').length);
			var iRecordConfirmHave = $(elem).find('.order-item-list [js-element="selectItems"] select:not(.hide) option[value="6"]:selected').length;
			var iRecordCancelHave = $(elem).find('.order-item-list [js-element="selectItems"] select:not(.hide) option[value="5"]:selected').length;
			var iRecordOrHave = $(elem).find('.order-item-list [js-element="selectItems"] [js-data="confirmed"]').length;
			var iAll = iRecordConfirmHave + iRecordCancelHave + iRecordOrHave;
			// console.log(iRecordCount, iAll);
			if (iRecordCount == iAll) {
				$(elem).find('[js-element="proceed-btn"]').prop('disabled', false).removeAttr('disabled');
			} else {
				$(elem).find('[js-element="proceed-btn"]').prop('disabled', true).attr('disabled', 'disabled');
			}
			/*if all cancelled*/
			$(elem).find('[js-element="proceed-btn"]').removeClass('btn-danger btn-contrast');
			if (iRecordCancelHave == iRecordCount) {
				$(elem).find('[js-element="proceed-btn"]').addClass('btn-danger').html('MOVE TO CANCELLED<i class="fa fa-trash icon-right"></i>');
			} else {
				var uiTextSaved = $(elem).find('[js-element="proceed-btn"]').data('default-html');
				$(elem).find('[js-element="proceed-btn"]').addClass('btn-contrast').html(uiTextSaved);
			}
		});
	}

	var updateFulfillmentsCounts = function(noAlert) {
		if (noAlert == undefined) noAlert = false;

		var iCnt = $('.order-table-item:not(.was-cancelled)').length,
		iInitCancelCnt = parseInt($('.ff-navbar-pill.cancelled').find('kbd').text()),
		iFinalCancelCnt = $('.order-table-item.was-cancelled').length,
		iNavCnt = parseInt($('#nav-fulfill-count').text());

		if (iCnt == 0) {
			$('.ff-navbar-pill.active').find('kbd').hide();
			$('[js-element="fulfill-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
		} else {
			$('.ff-navbar-pill.active').find('kbd').text(iCnt);
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
			$('.ff-navbar-pill.cancelled').find('kbd').text(iCancelCnt);
		} else {
			$('.ff-navbar-pill.cancelled').find('kbd').hide();
		}
	}

	var runOrdersToFulfillments = function(realtime) {
		console.log('Listening from Orders activity!');
		// console.log(realtime);
		realtime.bind('remove-item', 'fulfilled-items', function(object) {
			var oData = object.data;
			// console.log(oData);
			removeOnFulfillment(oData);
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
}