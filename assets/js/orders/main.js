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
		}, 1000);
		printableWin.print();

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
function orderProcess(callback) {
	var sSegment2 = oSegments[2], sStatus = null;
	switch (sSegment2) {
		case 'on-delivery':
			sStatus = 'for+pick+up';
		break;
		case 'received':
			sStatus = 'on+delivery';
		break;
	}

	if (sStatus != null) {
		if (order_process != false && order_process.readyState !== 4) order_process.abort();
		order_process = $.ajax({
			url: 'api/order_process/',
			type: 'post',
			data: {status: sStatus, segment: sSegment2},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (data.success == true) callback(data);
			}
		});
	} else {
		if (sSegment2 == undefined) sSegment2 = 'placed';
		realtime.bind(sSegment2+'-order', 'incoming-orders', function(object) {
			var oData = object.data;
			console.log(oData);
			if (oData.success) {
				if (Object.keys(oData.buyer_id).length) {
					if ($.inArray(oUser.id, oData.buyer_id) >= 0) runOrders(oData);
				} else {
					if (oData.buyer_id == oUser.id) runOrders(oData);
				}
			}
		});
	}
}

function runOrders(data) {
	var method = data.event;
	$.ajax({
		url: 'orders/'+method+'/',
		type: 'post',
		dataType: 'json',
		data: { ids: data.ids, buyer_id: data.buyer_id },
		success: function(response) {
			console.log(response);
			if (response.html.length) {
				if ($('#dashboard_panel_right [js-element="orders-panel"]').find('.no-records-ui:visible').length) {
					$('#dashboard_panel_right [js-element="orders-panel"]').html(response.html);
				} else {
					var newHtml = $(response.html).find('[js-element="orders-panel"]').html();
					var newOrderHtml = $(newHtml).find('.no-records-ui').remove();
					if (newOrderHtml.length == 0) {
						newOrderHtml = $(response.html);
					}
					newOrderHtml.insertBefore($('#dashboard_panel_right [js-element="orders-panel"]').find('.no-records-ui'));
				}
				runDomReady();
				switch (method) {
					case 'on-delivery':
						var sPrevNav = 'for-pick-up';
					break;
					case 'received':
						var sPrevNav = 'on-delivery';
					break;
				}
				var uiPrevNav = $('[data-nav="'+sPrevNav+'"]'), uiCurrNav = $('[data-nav="'+method+'"]');
				if (uiCurrNav.find('kbd').length == 0) {
					uiCurrNav.find('div').append($('<kbd>'));
				}
				var prev = isNaN(parseInt(uiCurrNav.find('kbd').text())) ? 0 : parseInt(uiCurrNav.find('kbd').removeClass('no-count').text());
				var dataCnt = parseInt(response.total_items);
				uiCurrNav.find('kbd').text(prev + dataCnt);
				/*set count for pickup*/
				if (uiPrevNav.find('kbd').length == 0) {
					uiPrevNav.find('div').append($('<kbd>'));
				}
				var prev = isNaN(parseInt(uiPrevNav.find('kbd').text())) ? 0 : parseInt(uiPrevNav.find('kbd').removeClass('no-count').text());
				var dataCnt = parseInt(response.total_items);
				if (prev > dataCnt) {
					uiPrevNav.find('kbd').text(prev - dataCnt);
				} else if (prev >= 0) {
					uiPrevNav.find('kbd').remove();
				}
				if ($('#nav-order-count').length) {
					var orderCount = parseInt($('#nav-order-count').text());
					if (isNaN(orderCount)) orderCount = 0;
					$('#nav-order-count').text(orderCount + 1);

					var fulfillCount = parseInt($('#nav-fulfill-count').text());
					if (isNaN(fulfillCount)) fulfillCount = 0;
					$('#nav-fulfill-count').text(fulfillCount + 1);
				}
			}
		}
	});
}
