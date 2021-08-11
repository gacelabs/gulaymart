$(document).ready(function() {

});

var oReceivedAjax = false;
var moveToReceiveOrders = function(post) {
	// console.log(post);
	if (Object.keys(post).length) {
		var oSettings = {
			url: 'orders/receive/',
			type: 'post',
			data: {data: post, todo: 1},
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
		if (oReceivedAjax != false && oReceivedAjax.readyState !== 4) oReceivedAjax.abort();
		oReceivedAjax = $.ajax(oSettings);
	}
}