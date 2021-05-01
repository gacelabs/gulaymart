$(document).ready(function() {
	
});

var clearForm = function(obj) {
	// console.log(obj);
	if (Object.keys(obj).length) {
		$.each(obj, function(i, data) {
			if ($('#'+data.setting).find('input[to-clear]').length) $('#'+data.setting).find('input[to-clear]').val('');
			$.each(data.value, function(name, val) {
				$('#'+data.setting).find('name=['+name+']').prop('value', val).val(val);
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
		if (oUser/* && oUser.operator*/) {
			runAlertBox({type:'info', message: oData.message, unclose:true});
		
			var sampleCnt = 1, sampleTotal = 5;
			var uiBtn = $('[js-element="operator-bookings"]');
			uiBtn.prop('disabled', false).removeAttr('disabled')
				.attr('data-prev-ui', uiBtn.html())
				.html('BOOK NOW');
			
			runTimer();

			uiBtn.bind('click', function() {
				clearInterval(interval);
				uiBtn.html('<span class="spinner-border spinner-border-sm"></span> Booking ('+sampleCnt+' of '+sampleTotal+')').prop('disabled', true).attr('disabled', 'disabled');
				setTimeout(function() {
					sampleCnt++;
					$('[js-id="timer"]').text(90);
					if (sampleCnt != sampleTotal) {
						uiBtn.prop('disabled', false).removeAttr('disabled').html('BOOK NOW ('+sampleCnt+' of '+sampleTotal+')');
						runTimer();
					} else {
						runAlertBox({type:'success', message: 'All Delivery Booking Posted!'});
						uiBtn.html(uiBtn.data('prev-ui')).prop('disabled', true).attr('disabled', 'disabled');
					}
				}, 7000);
			});
		}
	});
}

var runTimer = function() {
	var timer = 89;
	interval = setInterval(function() {
		$('[js-id="timer"]').text(timer);
		if (timer == 0) {
			clearInterval(interval);
			var uiBtn = $('[js-element="operator-bookings"]');
			uiBtn.html(uiBtn.data('prev-ui')).prop('disabled', true).attr('disabled', 'disabled');
			$('.close-jq-toast-single:visible').trigger('click');
			$('[js-id="timer"]').text(90);
			uiBtn.unbind('click');
			/*update the database*/
		}
		timer--;
	}, 1000);
}