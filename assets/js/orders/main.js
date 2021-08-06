$(document).ready(function() {
	$('[js-event="showOrderFooter"]').click(function() {
		$(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
		$(this).parents('[js-element]:first').find('.order-footer-farm, .order-footer-payment').toggleClass('hidden-xs');
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

		/*console.log(document.querySelector('[js-element="to-print"]'));
		html2canvas(document.querySelector('[js-element="to-print"]')).then(function(canvas) {
			oThis.html(oThis.attr('prev-ui'));
			canvas.toBlob(function(blob) {
				var url = URL.createObjectURL(blob);
				console.log(url);
				printJS({printable:url, type:'image'});
			});
		});*/
	});
}

var order_process = false;
function orderProcess() {
	var sSegment2 = oSegments[2];
	if (sSegment2 == undefined) sSegment2 = 'placed';
	realtime.bind(sSegment2+'-order', 'incoming-orders', function(object) {
		var oData = object.data;
		// console.log(oData);
		if (oData.success) {
			if (Object.keys(oData.buyer_id).length) {
				if ($.inArray(oUser.id, oData.buyer_id) >= 0) runOrders(oData);
			} else {
				if (oData.buyer_id == oUser.id) runOrders(oData);
			}
		}
	});
}

function runOrders(data) {
	var method = data.event, oSettings = {
		url: 'orders/'+method+'/',
		type: 'post',
		dataType: 'json',
		data: { ids: data.ids, buyer_id: data.buyer_id },
		success: function(response) {
			// console.log(response);
			if (response.html.length) {
				if (data.remove == false) {
					/*just add it*/
					if ($('#dashboard_panel_right [js-element="orders-panel"]').find('.no-records-ui:visible').length) {
						$('#dashboard_panel_right [js-element="orders-panel"]').html(response.html);
					} else {
						$('#dashboard_panel_right [js-element="orders-panel"]').prepend(response.html);
					}
					runDomReady();
				} else {
					/*just remove it*/
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
											$('#dashboard_panel_right [js-element="orders-panel"]')
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

var runDomReady = function() {
	$(document.body).find('[js-element="remove-product"]').off('click').on('click', function(e) {
		var arData = [];
		arData.push($(this).data('json'));
		// console.log(arData);
		var uiButtonSubmit = $(e.target);
		var lastButtonUI = uiButtonSubmit.html();
		var oSettings = {
			url: 'orders/delete/',
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

	$(document.body).find('[js-element="remove-all"]').off('click').on('click', function(e) {
		if ($(e.target).parents('.order-table-item:first').hasClass('was-cancelled')) {
			$(e.target).parents('.order-table-item:first').fadeOut().remove();
			updateOrdersCounts();
		} else {
			var oToDeleteData = [];
			$(e.target).parents('.order-table-item:first').find('[js-element="remove-all"]').each(function(i, elem) {
				oToDeleteData.push({merge_id: $(elem).data('merge_id')});
			});
			// console.log(oToDeleteData);
			var uiButtonSubmit = $(e.target);
			var lastButtonUI = uiButtonSubmit.html();
			var oSettings = {
				url: 'orders/delete/1',
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
		}
	});
}