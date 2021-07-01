
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
						if (typeof fulfillmentProcess == 'function') {
							// var event = oSegments[2];
							// realtime.bind(event+'-fulfillment', 'fulfillment-bookings', function(object) {
							// 	var oData = object.data;
							// 	/*oData has properties message and operator*/
							// 	realtimeBookings(oData);
							// });
							fulfillmentProcess(function(data) {
								var method = oSegments[2];
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
													sStatus = 'for+pick+up';
													var prev = isNaN(parseInt($('[data-nav="'+method+'"]').find('kbd').text())) ? 0 : parseInt($('[data-nav="'+method+'"]').find('kbd').text());
													var dataCnt = parseInt(response.total_items);
													$('[data-nav="'+method+'"]').find('kbd').text(prev + dataCnt);
												break;
												case 'received':
													sStatus = 'on+delivery';
												break;
											}
										}
									}
								});
							});
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