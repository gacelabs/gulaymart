
<script type="text/javascript">
	var realtime = false;
	window.initSendData = function() {
		realtime = new SendData({
			afterInit: function() {
				realtime.connect(function() {
					console.log('gulaymart.com ready to communicate!');
					/*realtime.trigger('add-delivery', 'deliveries', {
						f_id: "",
						'pac-input': 'Orchids St, San Jose del Monte City, Bulacan, Philippines',
						'pac-input2': 'Santa Maria, Bulacan, Philippines',
						f_driver_id: "",
						f_sender_name: "Eddie Garcia",
						f_sender_mobile: "09172022385",
						f_sender_landmark: "Test",
						f_sender_address: 'Orchids St, San Jose del Monte City, Bulacan, Philippines',
						f_sender_address_lat: 14.8072588,
						f_sender_address_lng: 121.0366074,
						f_order_type_send: 1,
						f_sender_date: "",
						f_sender_datetime_from: "",
						f_sender_datetime_to: "",
						// f_order_type_send: 2, // if Order Type is SCHEDULED
						// f_sender_date: "03/02/2021",
						// f_sender_datetime_from: "02:13:23",
						// f_sender_datetime_to: "03:13:27",
						f_sen_add_in_city: "",
						f_sen_add_in_pro: "",
						f_sen_add_in_reg: "",
						f_sen_add_in_coun: "",
						f_recepient_name: "Eddie Garcia 1",
						f_recepient_mobile: "09172022385",
						f_recepient_landmark: "Test 1",
						f_recepient_address: 'Santa Maria, Bulacan, Philippines',
						f_recepient_address_lat: 14.847608,
						f_recepient_address_lng: 120.9808582,
						f_order_type_rec: 1,
						f_recepient_date: "",
						f_recepient_datetime_from: "",
						f_recepient_datetime_to: "",
						// f_order_type_rec: 2, // if Order Type is SCHEDULED
						// f_recepient_date: "03/02/2021",
						// f_recepient_datetime_from: "02:13:23",
						// f_recepient_datetime_to: "03:13:27",
						f_rec_add_in_city: "",
						f_rec_add_in_pro: "",
						f_rec_add_in_reg: "",
						f_rec_add_in_coun: "",
						f_collectFrom: "R",
						f_recepient_notes: "",
						f_cargo: "Food",
						f_cargo_others: "Food",
						f_is_cod: false,
						f_express_fee: false,
						// f_is_cod: "on",
						// f_recepient_cod: "400", // if COD is checked
						// f_express_fee: "on",
						// f_express_fee_hidden: 40.00 // if express fee is checked
					});*/
					if (oUser == false) {
						realtime.bind('session', 'auth-login', function(object) {
							if (object.data.device_id == DEVICE_ID) {
								runAlertBox({
									type:'confirm',
									message: 'Session Log-in detected from other browser, do you want to cancel all log-ins?',
									callback: function() { 
										window.location.href = 'sign-out';
									}
								});
							}
						});
					} else {
						realtime.bind('session', 'auth-logout', function(object) {
							if (object.data.device_id == DEVICE_ID) {
								runAlertBox({
									type:'warn',
									message: 'Log-out request detected from other browser, click ok to reload page.',
									callback: function() {
										window.location.reload(true);
									}
								});
							}
						});
					}
				});
			},
			afterConnect: function() {
				/*realtime.bind('return-delivery', 'returns', function(object) {
					console.log('received response from portal.toktok.ph', object.data);
				});*/
				if (oUser) {
					realtime.trigger('session', 'auth-login', oUser);
				} else {
					realtime.trigger('session', 'auth-logout', {device_id: DEVICE_ID});
				}
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