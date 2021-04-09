var oRemoveAjax = false;
var removeItem = function(id) {
	var oSettings = {
		url: 'farm/remove',
		type: 'post',
		data: {id: id},
		dataType: 'jsonp',
		error: function(xhr, status, thrown) {
			console.log(status, thrown);
		}
	};
	if (oRemoveAjax != false && oRemoveAjax.readyState !== 4) oRemoveAjax.abort();
	oRemoveAjax = $.ajax(oSettings);
}

var removeOnTable = function(data) {
	if (data && data.id) {
		$('tr[product_id='+data.id+']').fadeOut();
	}
}