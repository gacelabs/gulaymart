$(document).ready(function() {
	$readMoreJS.init({
		target: '.notif-item-middle p',
		numOfWords: 15,
		toggle: true,
		moreLink: 'Read More',
		lessLink: 'Show Less'
	});

	$('.rm-link').click(function() {
		$(this).parents('.notif-item-middle').next('.notif-item-footer').removeClass('hide');
	});
});

var readMessage = function(data, ui) {
	$(ui.target).parents('.notif-item').find('.notif-item-middle').html('');
	for(var x in data) {
		var item = data[x];
		$(ui.target).parents('.notif-item').find('.notif-item-middle').html(item.content);
	}
	var lastCount = parseInt($('#nav-messages-count').text());
	if (!isNaN(lastCount)) {
		lastCount = lastCount - 1;
	}
	if (lastCount) {
		$('#nav-messages-count').text(lastCount);
	} else {
		$('#nav-messages-count').hide();
	}
}

var deleteMessage = function(data, ui) {
	for(var x in data) {
		var item = data[x];
		$(ui.target).parents('.notif-item').fadeOut('fast', function() {
			if ($(this).parents('div[id]:first').length) {
				var id = $(this).parents('div[id]:first').attr('id');
				var lastCount = parseInt($('#'+id+'-count').text());
				if (!isNaN(lastCount)) {
					lastCount = lastCount - 1;
				}
				if (lastCount) {
					$('#'+id+'-count').text(lastCount);
					$('#nav-messages-count').text(lastCount);
				} else {
					var uiParent = $('#'+id+'-count').parent();
					$('#'+id+'-count').remove();
					$('#nav-messages-count').hide();
					var sText = capitalizeFirstLetter($.trim($('[hideshow-target="#msg_feedbacks"]').text()));
					var notice = '<div class="no-records-ui" style="text-align:center;background-color:#fff;padding:40px 10px;"><h1>Empty '+sText+'</h1><p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p></div>';
					$('#'+id).html(notice);
				}
			}
			$(this).remove();
		});
	}
}

var appendComment = function(obj) {
	console.log(obj);
	$('#seller_content').addClass('hide');
	if (obj.under != undefined) {
		if (obj.under > 0) {
			$('#seller_content').removeClass('hide');
			$('#seller_comments').text(obj.content);
			// $('#seller_buyer_date').text($.format.date(obj.added, "- ddd, MMMM d, yyyy | hh:ss p"));
			$('#seller_farm_name').text(obj.farm.name);
			$('#seller_buyer_date').text(timeZoneFormatDate(obj.added));
			$('#is_seller').text((obj.from_id == oUser.id ? '(You)' : ''));
			$('#seller_reply').val('');
			$('#reply_box').addClass('hide');
			$('#feedback-btn-id-'+obj.id).text('View');
			$('#feedback-btn-id-'+obj.id).attr('data-reply', JSON.stringify(obj));
		}
	}
};