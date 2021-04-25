
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
					}
				});
			},
			afterConnect: function() {}
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