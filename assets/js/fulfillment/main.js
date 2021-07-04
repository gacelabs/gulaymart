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
			$(window).off('mousemove', window.onafterprint);
			$(window).off('touchmove', window.onafterprint);
			$(window).off('click', window.onafterprint);
			oThis.html(oThis.attr('prev-ui'));
			printableWin.close();
		};
		setTimeout(function(){
			$(window).one('mousemove', window.onafterprint);
			$(window).one('touchmove', window.onafterprint);
			$(window).one('click', window.onafterprint);
		});
		printableWin.print();

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
	var segment2 = oSegments[2], sStatus = null;
	switch (segment2) {
		case 'on-delivery':
			sStatus = 'for+pick+up';
		break;
		case 'received':
			sStatus = 'on+delivery';
		break;
	}

	if (sStatus != null) {
		if (fulfillment_process != false && fulfillment_process.readyState !== 4) fulfillment_process.abort();
		fulfillment_process = $.ajax({
			url: 'api/fulfillment_process/',
			type: 'post',
			data: {status: sStatus, segment: segment2},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (data.success == true) callback(data);
			}
		});
	}
}

function runFulfillments(data) {
	var method = data.event;
	$.ajax({
		url: 'fulfillment/'+method+'/',
		type: 'post',
		dataType: 'json',
		data: { ids: data.ids },
		success: function(response) {
			if (response.html.length) {
				if ($('.ff-product-container').find('.no-records-ui:visible').length) {
					$('.ff-product-container').replaceWith(response.html);
				} else {
					var newHtml = $(response.html).find('[js-element="fulfill-panel"]').html();
					newHtml = $(newHtml).find('.no-records-ui').remove();
					newHtml.insertBefore($('.ff-product-container').find('.no-records-ui'));
				}
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
				var prev = isNaN(parseInt(uiCurrNav.find('kbd').text())) ? 0 : parseInt(uiCurrNav.find('kbd').text());
				var dataCnt = parseInt(response.total_items);
				uiCurrNav.find('kbd').text(prev + dataCnt);
				/*set count for pickup*/
				if (uiPrevNav.find('kbd').length == 0) {
					uiPrevNav.find('div').append($('<kbd>'));
				}
				var prev = isNaN(parseInt(uiPrevNav.find('kbd').text())) ? 0 : parseInt(uiPrevNav.find('kbd').text());
				var dataCnt = parseInt(response.total_items);
				if (prev > dataCnt) {
					uiPrevNav.find('kbd').text(prev - dataCnt);
				} else if (prev >= 0) {
					uiPrevNav.find('kbd').remove();
				}
			}
		}
	});
}