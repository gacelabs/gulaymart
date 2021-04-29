$(document).ready(function() {

});

var renderHTML = function(obj) {
	// console.log(obj)
	var uiToPrint = $('[js-element="invoice-body"]').find('[js-element="to-print"]');
	$('[js-element="invoice-body"]').find('p[js-data="loader"]').addClass('hide');
	uiToPrint.replaceWith(obj.html);
	
	// console.log($('[js-element="print-action"]'))
	$('[js-element="print-action"]').on('click', function(e) {
		var oThis = $(e.target);
		oThis.hide();

		html2canvas(document.querySelector('[js-element="to-print"]')).then(canvas => {
			window.onafterprint = function(e){
				$(window).off('mousemove', window.onafterprint);
				console.log(oThis);
				oThis.show();
			};
			setTimeout(function(){
				$(window).one('mousemove', window.onafterprint);
			}, 0);
			printJS(canvas.toDataURL(), 'image');
		});
	});
}