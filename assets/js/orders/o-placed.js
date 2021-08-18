
var oRemoveAjax = false;
$(document).ready(function() {
	if (typeof ordersRunDomReady == 'function') ordersRunDomReady();
});

if ($('body').hasClass('orders-placed')) {
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
					if (thrown == 'Service Unavailable') {
						console.log('Debug called');
					} else {
						console.log(status, thrown);
					}
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
					if (thrown == 'Service Unavailable') {
						console.log('Debug called');
					} else {
						console.log(status, thrown);
					}
				},
				success: function(data) {}
			};
			if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
			oRemoveAjax = $.ajax(oSettings);
		}
	}
	/*end of own page js*/
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

	if (typeof navigator.share == 'function') {
		$('[js-element="share-action"]').show();
		var oLinkToShare = {title: 'Invoice', text: 'Order Summary', url: obj.printable_link};
		$('[js-element="share-action"]').off('click').on('click', async (e) => {
			try {
				await navigator.share(oLinkToShare);
				console.log(true);
			} catch(err) {
				console.log(false, err);
			}
		});
	} else {
		$('[js-element="share-action"]').hide();
	}
}