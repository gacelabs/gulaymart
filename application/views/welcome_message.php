<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">
		::selection { background-color: #E13300; color: white; }
		::-moz-selection { background-color: #E13300; color: white; }

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#body {
			margin: 0 15px 0 15px;
		}

		p.footer {
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}

		#container {
			margin: 10px;
			border: 1px solid #D0D0D0;
			box-shadow: 0 0 8px #D0D0D0;
		}
	</style>

	<script type="text/javascript">
		javascript: (function(d, s, id) {
			var js, p = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "https://app.send-data.co/get/jsfile/A3193CF4AEC1ADD05F4B78C4E0C61C39";
			p.parentNode.insertBefore(js, p);
		}(document, "script", "sd-sdk"));
	</script>
</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>application/views/welcome_message.php</code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/Welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
	<script type="text/javascript">
		var realtime = false;
		var i = setInterval(function() {
			if (typeof SendData == 'function') {
				clearInterval(i);
				realtime = new SendData({
					afterInit: function() {
						realtime.connect(function() {
							console.log('gulaymart.com communicating and sending order to portal.toktok.ph...');
							realtime.trigger('add-delivery', 'deliveries', {
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
								/*f_order_type_send: 2, // if Order Type is SCHEDULED
								f_sender_date: "03/02/2021",
								f_sender_datetime_from: "02:13:23",
								f_sender_datetime_to: "03:13:27",*/
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
								/*f_order_type_rec: 2, // if Order Type is SCHEDULED
								f_recepient_date: "03/02/2021",
								f_recepient_datetime_from: "02:13:23",
								f_recepient_datetime_to: "03:13:27",*/
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
								/*f_is_cod: "on",
								f_recepient_cod: "400", // if COD is checked
								f_express_fee: "on",
								f_express_fee_hidden: 40.00 // if express fee is checked*/
							});
						});
					},
					afterConnect: function() {
						realtime.bind('return-delivery', 'returns', function(object) {
							console.log('received response from portal.toktok.ph', object.data);
						});
					}
				});
			}
		}, 1000);
	</script>
</body>
</html>