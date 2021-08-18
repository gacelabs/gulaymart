$(document).ready(function() {
});

var clearForm = function(obj) {
	// console.log(obj);
	if (Object.keys(obj).length) {
		$.each(obj, function(i, data) {
			if ($('#'+data.setting).find('input[to-clear]').length) $('#'+data.setting).find('input[to-clear]').val('');
			$.each(data.value, function(name, val) {
				$('#'+data.setting).find('[name="admin_settings['+i+'][value]['+name+']"]').prop('value', val).val(val);
			});
		});
	}
};

var interval; 
var runOperatorBookings = function(realtime) {
	console.log('Listening from Operators booking!');
	// console.log(realtime);
	realtime.bind('operator-bookings', 'send-bookings', function(object) {
		var oData = object.data;
		/*oData has properties message and operator*/
		realtimeBookings(oData);
	});
}

var noAvailableBookings = function(oData) {
	window.onbeforeunload = null;
	clearInterval(interval);
	$('[js-id="timer"]').text(90);
	var uiBtn = $('[js-form="booking-form"]').find('[js-element="operator-bookings"]');
	uiBtn.html('<span class="spinner-border spinner-border-sm"></span> Awaiting bookings...')
		.prop('disabled', true).attr('disabled', 'disabled');

	$('[js-event="awaiting-code"]').find('p').text('Awaiting...');
	$('[js-event="awaiting-rider"]').removeClass('hide');
	$('[js-element="riders-list"]').addClass('hide');
	$('[js-event="selected-rider"]').addClass('hide')
	$('[js-farm]').text('...');
	$('[js-fee]').text(0);
}

var runTimer = function() {
	var timer = 89;
	interval = setInterval(function() {
		$('[js-id="timer"]').text(timer);
		if (timer == 0) {
			clearInterval(interval);
			var uiBtn = $('[js-form="booking-form"]').find('[js-element="operator-bookings"]');
			uiBtn.html(uiBtn.data('prev-ui')).prop('disabled', true).attr('disabled', 'disabled');
			$('.close-jq-toast-single:visible').trigger('click');
			$('[js-id="timer"]').text(90);
			// uiBtn.off('click');
			/*update the database*/
		}
		timer--;
	}, 1000);
}

var populateOrderDetails = function(oData) {
	// console.log(oData);
	$('[js-form="booking-form"]').find('input[name="id"]').remove();
	$('[js-form="booking-form"]').prepend($('<input>', {type: 'hidden', name: 'id', value: oData.delivery.id}));
	$('[js-form="booking-form"]').find('input[name="user_id"]').remove();
	$('[js-form="booking-form"]').prepend($('<input>', {type: 'hidden', name: 'user_id', value: oUser.id}));

	$('[js-event="awaiting-code"]').find('p').text(oData.delivery.order_id);
	$('[js-event="awaiting-rider"]').addClass('hide');
	$('[js-element="riders-list"]').removeClass('hide');
	$('[js-event="selected-rider"]').addClass('hide')
	$('[js-farm]').text(oData.delivery.seller.city_prov);
	$('[js-fee]').text(Number(oData.delivery.fee).toLocaleString());
}

var bookDelivery = function(oData) {
	clearInterval(interval);
	var Cnt = parseInt(oData.count), totalCount = oData.total;
	var uiBtn = $('[js-form="booking-form"]').find('[js-element="operator-bookings"]');
	uiBtn.prop('disabled', true).attr('disabled', 'disabled');
	setTimeout(function() {
		$('[js-id="timer"]').text(90);
		if (Cnt >= totalCount) {
			runAlertBox({type:'success', message: 'All Delivery Booking Posted!'});
			noAvailableBookings(oData);
		} else {
			populateOrderDetails(oData);
			uiBtn.attr('loading-text', 'Booking ('+(Cnt+1)+' of '+totalCount+')')
				.prop('disabled', false).removeAttr('disabled').html('BOOK NOW ('+(Cnt+1)+' of '+totalCount+')');
			runTimer();
		}
	}, 1000);
}

var realtimeBookings = function(oData) {
	if (oUser && oUser.operator) {
		if (oUser.operator.id == oData.operator_id) {
			var Cnt = parseInt(oData.count)+1, totalCount = oData.total;
			var uiBtn = $('[js-form="booking-form"]').find('[js-element="operator-bookings"]');
			uiBtn.prop('disabled', false).removeAttr('disabled')
				.attr('data-prev-ui', uiBtn.html())
				.attr('loading-text', 'Booking ('+Cnt+' of '+totalCount+')')
				;

			uiBtn.html('BOOK NOW');
			runAlertBox({type:'info', message: oData.message, unclose:true});
			window.onbeforeunload = function() {
				return "Leaving the page will lose bookings given to you.";
			}
			populateOrderDetails(oData);
			runTimer();
			uiBtn.bind('click', function() {
				clearInterval(interval);
				$('[js-event="selected-rider"]').removeClass('hide')
					.text($('[name="rider_mobile"]').find('option:selected').text());
				$('[js-element="riders-list"]').addClass('hide');
			});
		}
	}
}