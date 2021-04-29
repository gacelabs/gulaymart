
var oRemoveAjax = false;
$(document).ready(function() {
	$('[js-event="showOrderFooter"]').click(function() {
		$(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
		$('.order-footer-farm, .order-footer-payment').toggleClass('hidden-xs');
	});

	$('[js-element="remove-product"]').bind('click', function(e) {
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

	$('[js-element="remove-all"]').bind('click', function(e) {
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
});

if ($('body').hasClass('orders-placed')) {
	var updateOrdersCounts = function(noAlert) {
		if (noAlert == undefined) noAlert = false;

		var iCnt = $('.order-table-item:not(.was-cancelled)').length,
		iInitCancelCnt = parseInt($('.trans-navbar-pill.cancelled').find('kbd').text()),
		iFinalCancelCnt = $('.order-table-item.was-cancelled').length,
		iNavCnt = parseInt($('#nav-order-count').text());

		if (iCnt == 0) {
			$('.trans-navbar-pill.active').find('kbd').hide();
			$('[js-element="orders-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
		} else {
			$('.trans-navbar-pill.active').find('kbd').text(iCnt);
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

		var iCancelCnt = (iInitCancelCnt+iFinalCancelCnt < 0) ? false : iInitCancelCnt+iFinalCancelCnt;
		if (iCancelCnt != false) {
			$('.trans-navbar-pill.cancelled').find('kbd').text(iCancelCnt);
		} else {
			$('.trans-navbar-pill.cancelled').find('kbd').hide();
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
		realtime.bind('change-order-status', 'ordered-items', function(object) {
			// console.log('received response from change-order-status:ordered-items', object.data);
			var oData = object.data;
			changeOnFulfillmentRealtime(oData.data);
		});
	}
	var changeOnFulfillmentRealtime = function(obj) {
		if (obj != undefined) {
			if (Object.keys(obj.data).length) {
				var finalTotal = 0, arNotExists = [];
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
						arNotExists.push($('[data-merge-id="'+oData.merge_id+'"]').length > 0)
						uiFarm.find('[js-data="confirmed"]').addClass('hide');
					} else {
						$('[js-element="item-id-'+oData.merge_id+'-'+oData.product_id+'"]').removeClass('was-cancelled').find('[js-element="remove-product"]').hide();
						$('[data-merge-id="'+oData.merge_id+'"]').removeClass('was-cancelled').find('[js-element="remove-all"]').hide();
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
							setTimeout(function() {
								$('.order-table-item.was-cancelled').remove();
							}, 1000);
						}, 3000);
					});

					updateOrdersCounts(true);
				}
			}
		}
	}
	/*realtime js*/
}
