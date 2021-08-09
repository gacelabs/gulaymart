$(document).ready(function() {
	msgRunDomReady();
});

var msgRunDomReady = function() {
	$readMoreJS.init({
		target: '.notif-item-middle p',
		numOfWords: 15,
		toggle: true,
		moreLink: 'Read More',
		lessLink: 'Show Less'
	});

	$('.rm-link').off('click').on('click', function() {
		$(this).parents('.notif-item-middle').next('.notif-item-footer').removeClass('hide');
	});
}

var changeMessagesCount = function() {
	var sTabName = $($('[id^="msg_"]:visible').get(1)).attr('element-name');
	var uiTab = $('[data-nav="'+sTabName+'"]');
	var totalItems = parseInt(uiTab.find('kbd').text());
	if (!isNaN(totalItems) && totalItems != 0) totalItems = totalItems - 1;

	reCountMenuNavs('message', totalItems);
	reCountMenuNavs(sTabName, totalItems);
}

var readMessage = function(data, ui) {
	$(ui.target).parents('.notif-item').find('.notif-item-middle').html('');
	for(var x in data) {
		var item = data[x];
		$(ui.target).parents('.notif-item').find('.notif-item-middle').html(item.content);
	}
	changeMessagesCount();
}

var deleteMessage = function(data, ui) {
	var wait = new Promise((resolve, reject) => {
		data.forEach((item, index, array) => {
			var msg = $('[data-msg-id="'+item.id+'"]');
			msg.remove();
			if (index === array.length -1) resolve();
		});
	});
	wait.then(() => {
		if ($($('[id^="msg_"]:visible').get(0)).find('.no-records-ui').siblings().length == 0) {
			$($('[id^="msg_"]:visible').get(0)).find('.no-records-ui').removeClass('hide');
		}
		changeMessagesCount();
	});
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