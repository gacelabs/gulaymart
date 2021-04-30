$(document).ready(function() {

});

var renderHTML = function(obj) {
	// console.log(obj)
	var uiToPrint = $('[js-element="invoice-body"]').find('[js-element="to-print"]');
	uiToPrint.replaceWith(obj.html);
	
	$('[js-element="invoice-body"]').find('p[js-data="loader"]').addClass('hide');
	$('[js-element="to-print"]').removeClass('hide');
	// console.log($('[js-element="print-action"]'))
	$('[js-element="print-action"]').off('click').on('click', function(e) {
		var oThis = $(e.target);
		if (oThis.prop('tagName') != 'BUTTON') oThis = $(e.target).parent('[js-element="print-action"]');
		oThis.attr('prev-ui', oThis.html()).html('<span class="spinner-border spinner-border-sm"></span> Loading');
		html2canvas(document.querySelector('[js-element="to-print"]')).then(function(canvas) {
			oThis.html(oThis.attr('prev-ui'));
			canvas.toBlob(function(blob) {
				var url = URL.createObjectURL(blob);
				// console.log(url);
				printJS({printable:url, type:'image'});
			});
		});
	});
}