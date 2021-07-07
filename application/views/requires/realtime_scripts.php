
<script type="text/javascript">
	var realtime = false;
	window.initSendData = function() {
		realtime = new SendData({
			afterInit: function() {
				realtime.connect(function() {
					// console.log('gulaymart.com ready to communicate!');
					/*realtime.trigger('add-delivery', 'deliveries', {});*/
					if (oUser) {
						/*communicate from orders tab to fulfillment tab*/
						if (typeof runOrdersToFulfillments == 'function') runOrdersToFulfillments(realtime);
						/*communicate from fulfillment tab to orders tab*/
						if (typeof runFulfillmentsToOrders == 'function') runFulfillmentsToOrders(realtime);
						/*communicate from operators booking page*/
						if (typeof runOperatorBookings == 'function') runOperatorBookings(realtime);
						
						/*listen for incomming on delivery fulfillments*/
						if (typeof fulfillmentProcess == 'function' && oSegments[1] == 'fulfillment') {
							fulfillmentProcess(runFulfillments);
						}
						/*listen for incomming on delivery orders*/
						if (typeof runOrders == 'function' && oSegments[1] == 'orders') {
							orderProcess(runOrders);
						}
					}
				});
			},
			afterConnect: function() {
			}
		});
	};
	(function(d, s, id) {
		var js, p = d.getElementsByTagName(s), me = p[p.length - 1];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.type = 'text/javascript';
		js.src = "<?php echo REALTIME_URL;?>";
		me.parentNode.insertBefore(js, me);
	}(document, "script", "sd-sdk"));
</script>