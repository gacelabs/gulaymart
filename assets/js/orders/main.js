$(document).ready(function() {

	$('[js-event="showOrderFooter"]').click(function() {
		$(this).find('i.fa').toggleClass('fa-angle-down fa-angle-up');
		$('.order-footer-farm, .order-footer-payment').toggleClass('hidden-xs');
	});

	$('[js-element="remove-product"]').bind('click', function(e) {
		var arData = [];
		arData.push($(this).data('json'));
		console.log(arData);
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
		if ($(e.target).parents('.order-table-item:first').hasClass('removed-product')) {
			$(e.target).parents('.order-table-item:first').fadeOut().remove();
			removeOnAllOrder();
		} else {
			var oToDeleteData = [];
			$(e.target).parents('.order-table-item:first').find('[js-element="remove-all"]').each(function(i, elem) {
				oToDeleteData.push($(elem).data());
			});
			console.log(oToDeleteData);

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
			var uiParent = $('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').parent('.order-item-list');
			$('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').addClass('removed-product').find('[js-element="remove-product"]').remove();
			$('[js-element="item-id-'+data.merge_id+'-'+data.product_id+'"]').fadeOut('slow');
			$('[js-element="farm-'+data.merge_id+'-'+data.location_id+'"]').find('[js-element="item-subtotal"]')
		});

		$('.order-table-item').each(function(i, elem) {
			if ($(elem).find('[js-element*="item-id-"]:not(.removed-product)').length == 0) {
				$(elem).addClass('removed-product').fadeOut('slow');
				var iCnt = $('.order-table-item:not(.removed-product)').length;
				if (iCnt == 0) {
					$('#nav-order-count').remove();
					$('.trans-navbar-pill.active').find('kbd').remove();
				} else {
					$('#nav-order-count').text(iCnt);
					$('.trans-navbar-pill.active').find('kbd').text(iCnt);
				}
			}
		});
	}
}

var removeOnAllOrder = function(obj) {
	// console.log(obj);
	var iCnt = $('.order-table-item:not(.removed-product)').length;
	if (iCnt == 0) {
		$('#nav-order-count').remove();
		$('.trans-navbar-pill.active').find('kbd').remove();
	} else {
		$('#nav-order-count').text(iCnt);
		$('.trans-navbar-pill.active').find('kbd').text(iCnt);
	}
	if ($('.order-table-item').length == 0) {
		$('[js-element="orders-panel"]').find('.no-records-ui').removeClass('hide');
	}
}

var runOrdersRealtime = function(realtime) {
	// console.log(realtime);
	realtime.bind('remove-item', 'ordered-items', function(object) {
		console.log('received response from remove-item:ordered-items', object.data);
	});
}
