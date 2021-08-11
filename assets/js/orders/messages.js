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

var appendComment = function(obj) {
	// console.log(obj);
	$('#seller_content').addClass('hide');
	if (obj.under != undefined) {
		if (obj.under > 0) {
			$('#seller_content').removeClass('hide');
			$('#seller_comments').text(obj.content);
			// $('#seller_buyer_date').text($.format.date(obj.added, "- ddd, MMMM d, yyyy | hh:ss p"));
			$('#seller_farm_name').text(obj.farm.name);
			$('#seller_content').find('img.media-object').attr('src', obj.farm.profile_pic);
			$('#seller_buyer_date').text(timeZoneFormatDate(obj.added));
			$('#is_seller').text((obj.from_id == oUser.id ? '(You)' : ''));
			$('#seller_reply').val('');
			$('#reply_box').addClass('hide');
			$('#feedback-btn-id-'+obj.id).text('View');
			$('#feedback-btn-id-'+obj.id).attr('data-reply', JSON.stringify(obj));
		}
	}
};