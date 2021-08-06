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
	var uiParent = $(ui.target).parents('.notif-item').parent('[id^="msg_"]');
	console.log(uiParent);
	$(ui.target).parents('.notif-item').find('.notif-item-middle').html('');
	for(var x in data) {
		var item = data[x];
		$(ui.target).parents('.notif-item').find('.notif-item-middle').html(item.content);
	}
	if ($('#nav-messages-count').length == 0) $('[data-menu-nav="messages"]').find('span').append('<kbd id="nav-messages-count"></kbd>');
	var totalItems = parseInt($('#nav-messages-count').text());
	if (!isNaN(totalItems) && totalItems != 0) totalItems = totalItems - 1;
	if (totalItems) {
		$('#nav-messages-count').removeClass('hide').text(totalItems);
	} else {
		$('#nav-messages-count').addClass('hide').text('');
	}
	if ($('kbd.nav-messages-count').length == 0) $('[data-menu-nav="messagess"]').find('span').append('<kbd class="nav-messages-count hidden-lg hidden-md hidden-sm"></kbd>');
	if (totalItems) {
		$('kbd.nav-messages-count').removeClass('hide').text(totalItems);
	} else {
		$('kbd.nav-messages-count').addClass('hide').text('');
	}
}

var deleteMessage = function(data, ui) {
	if ($('#nav-messages-count').length == 0) $('[data-menu-nav="messages"]').find('span').append('<kbd id="nav-messages-count"></kbd>');
	if ($('kbd.nav-messages-count').length == 0) $('[data-menu-nav="messagess"]').find('span').append('<kbd class="nav-messages-count hidden-lg hidden-md hidden-sm"></kbd>');
	for(var x in data) {
		var item = data[x];
		$(ui.target).parents('.notif-item').fadeOut('fast', function() {
			if ($(this).parents('div[id]:first').length) {
				var id = $(this).parents('div[id]:first').attr('id');
				var lastCount = parseInt($('#'+id+'-count').text());
				if (!isNaN(lastCount) && lastCount != 0) lastCount = lastCount - 1;
				if (lastCount) {
					$('#'+id+'-count').removeClass('hide').text(lastCount);
					$('#nav-messages-count').removeClass('hide').text(lastCount);
					$('kbd.nav-messages-count').removeClass('hide').text(lastCount);
				} else {
					$('#'+id+'-count').addClass('hide').text('');
					$('#nav-messages-count').addClass('hide').text('');
					$('kbd.nav-messages-count').addClass('hide').text('');

					if ($('#'+id).find('.no-records-ui').length == 0) {
						var sText = capitalizeFirstLetter($.trim($('[hideshow-target="#msg_feedbacks"]').text().replace(/\d+/g, '').replace(/(\r\n\t|\n|\r|\t)/gm, "").replace(/(<([^>]+)>)/gi, "")));
						var notice = '<div class="no-records-ui" style="text-align:center;background-color:#fff;padding:40px 10px;"><h1>Empty '+sText+'</h1><p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p></div>';
					} else {
						var notice = $('#'+id).find('.no-records-ui').removeClass('hide');
					}
					$('#'+id).html(notice);
				}
			}
			$(this).remove();
		});
	}
}

var appendComment = function(obj) {
	// console.log(obj);
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