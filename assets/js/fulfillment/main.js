$(document).ready(function() {

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

	$('[js-event="actionApplyBtn"]').click(function() {
		var actionVal = $('[js-event="actionSelect"]').val(),
			cancelVal = $('[js-event="cancelReasonSelect"]').val();
		if (actionVal == "5" && !$.isNumeric(cancelVal)) {
			$('[js-event="cancelReasonSelect"]').addClass('error');
		}
	});

});

var renderHTML = function(obj) {
	// console.log(obj)
	var uiToPrint = $('[js-element="invoice-body"]');
	// uiToPrint.find('[js-element="to-print"]').remove();
	uiToPrint.html(obj.html);
	
	$('p[js-data="loader"]').addClass('hide');
	$('[js-element="to-print"]').removeClass('hide');
	// console.log($('[js-element="print-action"]'))
	$('[js-element="print-action"]').off('click').on('click', function(e) {
		var oThis = $(e.target);
		if (oThis.prop('tagName') != 'BUTTON') oThis = $(e.target).parent('[js-element="print-action"]');
		oThis.attr('prev-ui', oThis.html()).html('<span class="spinner-border spinner-border-sm"></span> Loading');
		
		var printableWin = window.open(obj.printable_link);
		window.onafterprint = function(e){
			$(printableWin).off('mousemove touchmove', window.onafterprint);
			oThis.html(oThis.attr('prev-ui'));
			printableWin.close();
		};
		setTimeout(function(){
			$(printableWin).one('mousemove touchmove', window.onafterprint);
			printableWin.print();
		}, 1000);

		/*html2canvas(document.querySelector('[js-element="to-print"]')).then(function(canvas) {
			oThis.html(oThis.attr('prev-ui'));
			canvas.toBlob(function(blob) {
				var url = URL.createObjectURL(blob);
				// console.log(url);
				printJS({printable:url, type:'image'});
			});
		});*/
	});
}

var fulfillment_process = false;
function fulfillmentProcess(callback) {
	var sSegment2 = oSegments[2];
	if (sSegment2 == undefined) sSegment2 = 'placed';
	realtime.bind(sSegment2+'-fulfillment', 'incoming-fulfillment', function(object) {
		var oData = object.data;
		// console.log(oData);
		if (oData.success) {
			if (Object.keys(oData.seller_id).length) {
				if ($.inArray(oUser.id, oData.seller_id) >= 0) runFulfillments(oData);
			} else {
				if (oData.seller_id == oUser.id) runFulfillments(oData);
			}
		}
	});
}

function runFulfillments(data) {
	var method = data.event, oSettings = {
		url: 'fulfillment/'+method+'/',
		type: 'post',
		dataType: 'json',
		data: { ids: data.ids, seller_id: data.seller_id },
		success: function(response) {
			// console.log(response);
			if (response.html.length) {
				if (data.remove == false) {
					/*just add it*/
					if ($('.ff-product-container').find('.no-records-ui:visible').length) {
						$('.ff-product-container').replaceWith(response.html);
					} else {
						$('.ff-product-container').find('[js-element="fulfill-panel"]').prepend(response.html);
					}
					runDomReady();
				} else {
					var oArr = [];
					if (Object.keys(data.ids).length) {
						oArr = data.ids;
					} else if (!isNaN(data.ids)) {
						oArr = [data.ids];
					}
					if (typeof oArr == 'object') {
						for (var x in oArr) {
							var id = oArr[x];
							var item = $('[data-merge-id="'+id+'"]');
							if (item.length) {
								item.fadeOut('slow', function() {
									$(this).remove();
									setTimeout(function() {
										if ($('[data-merge-id]').length == 0) {
											$('.ff-product-container [js-element="fulfill-panel"]')
												.find('.no-records-ui').removeClass('hide');
										}
									}, 300);
								});
							}
						}
					}
				}
			}
		}
	};
	$.ajax(oSettings);
}

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


var runDomReady = function() {
	$(document.body).find('[js-event="actionSelect"]').off('change').on('change', function() {
		var actionVal = $(this).val();
		if (actionVal == "5") {
			$(this).next('[js-event="reasonSelect"]').removeClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#ff7575');
		} else {
			$(this).next('[js-event="reasonSelect"]').addClass('hide');
			$(this).parent('[js-element="selectItems"]').find('select').css('color', '#799938');
			$(this).next('[js-event="reasonSelect"]').val('None');
		}
		isAllSelected($(this).parents('.order-table-item').data('merge-id'));
	});

	$(document.body).find('[js-event="cancelReasonSelect"]').off('change').on('change', function() {
		if ($.isNumeric($(this).val())) {
			$(this).removeClass('error');
		}
	});

	$('[js-element="proceed-btn"]').prop('disabled', true).attr('disabled', 'disabled');
	$('[data-merge-id]').each(function(i, elem) {
		isAllSelected($(elem).data('merge-id'));
	});

	$(document.body).find('[js-element="proceed-btn"]').off('click').on('click', function(e) {
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
}