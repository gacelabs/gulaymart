
var oRemoveAjax = false;
$(document).ready(function() {
	runDomReady();
});

if ($('body').hasClass('orders-placed')) {
	var updateOrdersCounts = function(noAlert, isConfirmed) {
		if (noAlert == undefined) noAlert = false;
		if (isConfirmed == undefined) isConfirmed = false;

		var iCnt = $('.order-table-item:visible').length,
		iInitCancelCnt = parseInt($('.trans-navbar-pill.cancelled').find('kbd').text()),
		iFinalCancelCnt = $('.order-table-item.was-cancelled').length,
		iNavCnt = parseInt($('#nav-order-count').text());

		if (iCnt == 0) {
			$('.trans-navbar-pill.active').find('kbd').addClass('no-count').text('');
			$('[js-element="orders-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
		} else {
			$('.trans-navbar-pill.active').find('kbd').removeClass('no-count').text(iCnt);
			$('[js-element="orders-panel"]').find('.no-records-ui').fadeOut('slow').addClass('hide');
		}

		if (noAlert == false) {
			setTimeout(function() {
				runAlertBox({type:'info', message: 'Order added to Cancelled Orders'/*, unclose:true*/});
				$('.order-table-item.was-cancelled').fadeOut('slow');
				setTimeout(function() {
					$('.order-table-item.was-cancelled').remove();
				}, 1000);
			}, 3000);
		}

		var iNavLastCnt = (iNavCnt-iFinalCancelCnt < 0) ? false : iNavCnt-iFinalCancelCnt;
		if (iNavLastCnt != false) {
			$('#nav-order-count').text(iNavLastCnt);
		} else {
			$('#nav-order-count').hide();
		}
		if (noAlert==false && $('#nav-fulfill-count').length) {
			var fulfillCount = parseInt($('#nav-fulfill-count').text());
			if (isNaN(fulfillCount)) fulfillCount = 0;
			$('#nav-fulfill-count').text(fulfillCount - 1);
		}

		var iCancelCnt = (iInitCancelCnt+iFinalCancelCnt < 0) ? 0 : iInitCancelCnt+iFinalCancelCnt;
		if (!isNaN(iCancelCnt)) {
			if (iCancelCnt != 0) {
				$('.trans-navbar-pill.cancelled').find('kbd').removeClass('no-count').text(iCancelCnt);
			} else {
				$('.trans-navbar-pill.cancelled').find('kbd').addClass('no-count').text('');
			}
		}

		if ($('.trans-navbar-pill.active').parent('[data-nav]').length && isConfirmed == true) {
			var tab = $('.trans-navbar-pill.active').parent('[data-nav]').data('nav');
			if (tab != undefined) {
				var kbd = $('.trans-navbar-pill.active').parent('[data-nav]').parent('div').next().find('[data-nav] kbd');
				var nextCount = parseInt(kbd.text());
				if (isNaN(nextCount)) nextCount = 0;
				kbd.removeClass('no-count').text(nextCount + 1);
			}
		}
	}

	/*start of own page js*/
	var removeAllOrderItem = function(post) {
		// console.log(post);
		if (Object.keys(post).length) {
			var oSettings = {
				url: 'orders/delete/1',
				type: 'post',
				data: {data: post},
				dataType: 'jsonp',
				jsonpCallback: 'gmCall',
				error: function(xhr, status, thrown) {
					console.log(status, thrown);
				},
				success: function(data) {}
			};
			if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
			oRemoveAjax = $.ajax(oSettings);
		}
	}
	var removeOrderItem = function(post) {
		// console.log(post);
		if (Object.keys(post).length) {
			var oSettings = {
				url: 'orders/delete/',
				type: 'post',
				data: {data: post},
				dataType: 'jsonp',
				jsonpCallback: 'gmCall',
				error: function(xhr, status, thrown) {
					console.log(status, thrown);
				},
				success: function(data) {}
			};
			if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
			oRemoveAjax = $.ajax(oSettings);
		}
	}
	var removeOnOrder = function(obj) {
		// console.log(obj);
		if (Object.keys(obj).length) {
			var bCanceledCnt = false;
			$.each(obj, function(i, data) {
				var uiItem = $('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]');
				uiItem.addClass('was-cancelled').find('[js-element="remove-product"]').hide();
				// uiItem.fadeOut('slow');
				var uiFarm = $('[js-element="farm-'+data.merge_id+'-'+data.location_id+'"]');

				var subTotal = uiFarm.find('[js-element="item-subtotal"]').text();
				subTotal = parseFloat(subTotal.replace(',', ''));
				subTotal -= parseFloat(data.sub_total);
				uiFarm.find('[js-element="item-subtotal"]').text(Number(subTotal).toLocaleString());

				var finalTotal = uiFarm.find('[js-element="item-finaltotal"]').text();
				finalTotal = parseFloat(finalTotal.replace(',', ''));
				finalTotal -= parseFloat(data.sub_total);
				uiFarm.find('[js-element="item-finaltotal"]').text(Number(finalTotal).toLocaleString());
				
				$('[data-merge-id="'+data.merge_id+'"]').each(function(i, elem) {
					if ($(elem).find('[js-element*="item-id-"]:not(.was-cancelled)').length == 0) {
						$(elem).find('[js-element*="item-id-"]').removeClass('was-cancelled');
						$(elem).addClass('was-cancelled').find('[js-element="remove-product"]').hide();
						bCanceledCnt = true;
					}
				});
			});

			if (bCanceledCnt) updateOrdersCounts();
		}
	}
	var removeOnAllOrder = function(obj) {
		// console.log(obj);
		if (obj != undefined) {
			$.each(obj, function(i, data) {
				$('[data-merge-id="'+data.merge_id+'"]').find('[js-element*="item-id-"]').removeClass('was-cancelled');
				$('[data-merge-id="'+data.merge_id+'"]').addClass('was-cancelled').find('[js-element="remove-product"]').hide();
			});
		}
		updateOrdersCounts();
	}
	/*end of own page js*/

	/*realtime js*/
	var runFulfillmentsToOrders = function(realtime) {
		console.log('Listening from Fulfillments activity!');
		// console.log(realtime);
		realtime.bind('status-ordered-items', 'change-order-status', function(object) {
			// console.log('received response from change-order-status:ordered-items', object.data);
			var oData = object.data;
			if (Object.keys(oData.buyer_id).length) {
				if ($.inArray(oUser.id, oData.buyer_id) >= 0) changeOnFulfillmentRealtime(oData.data);
			} else {
				if (oData.buyer_id == oUser.id) changeOnFulfillmentRealtime(oData.data);
			}
		});
	}
	var changeOnFulfillmentRealtime = function(obj) {
		if (obj != undefined) {
			if (Object.keys(obj.data).length) {
				var finalTotal = 0, arNotExists = [], arIsConfirmed = [];
				$.each(obj.data, function(i, res) {
					var oData = {
						'merge_id': obj.merge_id,
						'product_id': res.product_id,
						'location_id': res.location_id,
						'sub_total': res.sub_total,
					}
					var uiFarm = $('[js-element="farm-'+oData.merge_id+'-'+oData.location_id+'"]');
					uiFarm.find('[js-data="confirmed"]').removeClass('hide');
					finalTotal += parseFloat(oData.sub_total);

					if (res.status == 5) {
						$('[js-element="item-id-'+oData.merge_id+'-'+oData.product_id+'"]').addClass('was-cancelled').find('[js-element="remove-product"]').hide();
						finalTotal -= parseFloat(oData.sub_total);
						arNotExists.push($('[data-merge-id="'+oData.merge_id+'"]').length > 0);
						uiFarm.find('[js-data="confirmed"]').addClass('hide');
					} else {
						$('[js-element="item-id-'+oData.merge_id+'-'+oData.product_id+'"]').removeClass('was-cancelled').find('[js-element="remove-product"]').hide();
						$('[data-merge-id="'+oData.merge_id+'"]').removeClass('was-cancelled').find('[js-element="remove-all"]').hide();
						$('[data-merge-id="'+oData.merge_id+'"]').addClass('was-confirmed');
						arIsConfirmed.push(true);
					}

					var fee = parseFloat(uiFarm.find('[js-element="item-fee"]').text());
					if (obj.data.length == (i+1)) {
						uiFarm.find('[js-element="item-subtotal"]').text(Number(finalTotal).toLocaleString());
						uiFarm.find('[js-element="item-finaltotal"]').text(Number(finalTotal + fee).toLocaleString());
					}
				});

				// console.log(arNotExists);
				if ($.inArray(true, arNotExists) >= 0) {
					$('.order-table-item').each(function(i, elem) {
						if ($(elem).find('[js-element*="item-id-"]:not(.was-cancelled)').length == 0) {
							$(elem).addClass('was-cancelled');
						}
					});

					$('.order-table-item.was-cancelled').each(function(i, elem) {
						setTimeout(function() {
							runAlertBox({type:'info', message: 'Order added to Cancelled Orders'/*, unclose:true*/});
							$('.order-table-item.was-cancelled').fadeOut('slow');
						}, 3000);
					});

					updateOrdersCounts(true);
				}
				if ($.inArray(true, arIsConfirmed) >= 0) {
					$('.order-table-item.was-confirmed').each(function(i, elem) {
						setTimeout(function() {
							runAlertBox({type:'info', message: 'Order added to For Pick Up Orders'/*, unclose:true*/});
							$('.order-table-item.was-confirmed').fadeOut('slow', function() {
								$(this).remove();
								updateOrdersCounts(true, true);
							});
						}, 3000);
					});
				}
			}
		}
	}
	/*realtime js*/
}

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