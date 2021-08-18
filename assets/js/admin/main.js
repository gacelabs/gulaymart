$(document).ready(function() {
	$('.product-list-card').on('click', function (e) {
		$(this).toggleClass('selected');
		if ($('#product_list_container').find('.product-list-card.selected').length > 1) {
			$('.approval-send-all').show();
		} else {
			$('.approval-send-all').hide();
		}
	});
	$('.send-all-approval').on('click', function (e) {
		e.stopPropagation();
		var oData = [];
		$('#product_list_container').find('.product-list-card.selected').map(function() {
			oData.push($(this).data('id'));
		});
		// console.log(oData, $(this).data('url'));
		simpleAjax($(this).data('url'), {id: oData}, $(this), false, true);
	});
	$('.send-approval').on('click', function (e) {
		e.stopPropagation();
		var iID = $(this).parents('.product-list-card').data('id');
		// console.log(iID, $(this).data('url'));
		simpleAjax($(this).data('url'), {id: iID}, $(this), false, true);
	});

	if ($('.cron-infos').length) {
		$('.cron-infos').each(function(i, elem) {
			setInterval(function() {
				var today = new Date();
				$(elem).find('[js-date]').text($.format.date(today, "ddd, MMMM d, yyyy"));
				$(elem).find('[js-time]').text($.format.date(today, "h:mm:ss a"));
			}, 1000);
		});
		$('#cron-sequence').get(0).scrollTo(0,$('#cron-sequence').get(0).scrollHeight);
		$('#cron-returns').get(0).scrollTo(0,$('#cron-returns').get(0).scrollHeight);
	}
});

var drawDataUpdatedLogs = function(obj) {
	// console.log(obj);
	if ($('#cron-'+obj.name).length) {
		$('#cron-'+obj.name).html(obj.logs);
		$('#cron-sequence').get(0).scrollTo(0,$('#cron-sequence').get(0).scrollHeight);
		$('#cron-returns').get(0).scrollTo(0,$('#cron-returns').get(0).scrollHeight);
		$('#cron-'+obj.name+'-next').removeClass('hide').find('i').text(obj.next);
	}
};

var removeItem = function(data) {
	// console.log(data);
	if (data.elem) {
		if (typeof data.messages == 'object') {
			$('#product_list_container').find('.product-list-card.selected').each(function(i, elem) {
				$(elem).find('.messages').text(data.messages[$(elem).data('id')]);
				$(elem).fadeOut(1000, function() {
					if ($('#product_list_container').find('.product-list-card.selected:visible').length == 0) {
						$('.approval-send-all').hide();
						$('#no-items').removeClass('hide');
						// window.location.reload();
					}
				});
			});
		} else {
			$(data.elem).parents('.product-list-card').find('.messages').text(data.messages);
			$(data.elem).parents('.product-list-card').fadeOut(1000, function() {
				if ($('#product_list_container').find('.product-list-card:visible').length == 0) {
					$('#no-items').removeClass('hide');
					// window.location.reload();
				}
			});
		}
	}
}

var drawDataCounts = function(obj, ui) {
	// console.log(obj);
	var btn = $('.active-ajaxed-form').find('button:submit');
	if (btn.length) {
		$.each(obj, function(index, data) {
			$('#'+index).text(data);
		});
	}
}