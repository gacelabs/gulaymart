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
			removeOnAllOrder();
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

var oRemoveAjax = false;
if ($('body').hasClass('orders-placed')) {
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
			$.each(obj, function(i, data) {
				$('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').addClass('was-cancelled').find('[js-element="remove-product"]').hide();
				// $('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').fadeOut('slow');
				var subTotal = $('[js-element="farm-'+data.merge_id+'-'+data.location_id+'"]').find('[js-element="item-subtotal"]').text();
				subTotal = parseFloat(subTotal.replace(',', ''));
				subTotal -= parseFloat(data.sub_total);
				$('[js-element="farm-'+data.merge_id+'-'+data.location_id+'"]').find('[js-element="item-subtotal"]').text(Number(subTotal).toLocaleString());
				var finalTotal = $('[js-element="farm-'+data.merge_id+'-'+data.location_id+'"]').find('[js-element="item-finaltotal"]').text();
				finalTotal = parseFloat(finalTotal.replace(',', ''));
				finalTotal -= parseFloat(data.sub_total);
				$('[js-element="farm-'+data.merge_id+'-'+data.location_id+'"]').find('[js-element="item-finaltotal"]').text(Number(finalTotal).toLocaleString());
			});

			$('.order-table-item').each(function(i, elem) {
				if ($(elem).find('[js-element*="item-id-"]:not(.was-cancelled)').length == 0) {
					$(elem).addClass('was-cancelled')/*.fadeOut('slow')*/;
				}
			});

			removeOnAllOrder(obj);
		}
	}

	var removeOnAllOrder = function(obj) {
		// console.log(obj);
		if (obj != undefined) {
			$.each(obj, function(i, data) {
				if (Object.keys(data).length == 1) {
					$('[data-merge-id="'+data.merge_id+'"]').find('[js-element*="item-id-"]').removeClass('was-cancelled');
					$('[data-merge-id="'+data.merge_id+'"]').addClass('was-cancelled')/*.fadeOut('fast')*/;
					$('[data-merge-id="'+data.merge_id+'"]').addClass('was-cancelled').find('[js-element="remove-product"]').hide();
					setTimeout(function() {
						runAlertBox({type:'info', message: 'Order added to Cancelled Orders', unclose:true});
						if (Object.keys(obj).length == 1) {
							$('[data-merge-id="'+data.merge_id+'"]').fadeOut('slow');
							setTimeout(function() {
								$('[data-merge-id="'+data.merge_id+'"]').remove();
							}, 1000);
						}
					}, 3000);
				}
			});
		}

		var iCnt = $('.order-table-item:not(.was-cancelled)').length,
		iInitCancelCnt = parseInt($('.trans-navbar-pill.cancelled').find('kbd').text()),
		iFinalCancelCnt = $('.order-table-item.was-cancelled').length;
		if (iCnt == 0) {
			$('#nav-order-count').remove();
			$('.trans-navbar-pill.active').find('kbd').text(0);
			$('[js-element="orders-panel"]').find('.no-records-ui').fadeIn('slow').removeClass('hide');
			setTimeout(function() {
				runAlertBox({type:'info', message: 'Order added to Cancelled Orders', unclose:true});
				$('.order-table-item.was-cancelled').fadeOut('slow');
				setTimeout(function() {
					$('.order-table-item.was-cancelled').remove();
				}, 1000);
			}, 3000);
		} else {
			$('#nav-order-count').text(iCnt);
			$('.trans-navbar-pill.active').find('kbd').text(iCnt);
			$('[js-element="orders-panel"]').find('.no-records-ui').fadeOut('slow').addClass('hide');
		}
		$('.trans-navbar-pill.cancelled').find('kbd').text(iInitCancelCnt+iFinalCancelCnt);
	}

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
		if (Object.keys(obj).length) {
			console.log(obj);
			if (obj.status == 5) {
				removeOnOrder([obj]);
			} else {
				$('[js-element="item-id-'+obj.merge_id+'-'+obj.product_id+'"]').removeClass('was-cancelled').find('[js-element="remove-product"]').hide();
				$('[data-merge-id="'+obj.merge_id+'"]').removeClass('was-cancelled').find('[js-element="remove-all"]').hide();
				$('[js-element="farm-'+obj.merge_id+'-'+obj.location_id+'"]').find('[js-data="confirmed"]').removeClass('hide');
			}

			$('.order-table-item.was-cancelled').each(function(i, elem) {
				setTimeout(function() {
					runAlertBox({type:'info', message: 'Order added to Cancelled Orders', unclose:true});
					$('.order-table-item.was-cancelled').fadeOut('slow');
					setTimeout(function() {
						$('.order-table-item.was-cancelled').remove();
					}, 1000);
				}, 3000);
			});
		}
	}
}
