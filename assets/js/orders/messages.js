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
}

var deleteMessage = function(data, ui) {
	for(var x in data) {
		var item = data[x];
		$(ui.target).parents('.notif-item').fadeOut('fast', function() {
			$(this).remove();
		});
	}
}