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
					$('#'+id+'-count').hide();
					$('#nav-messages-count').hide();
				}
			}
			$(this).remove();
		});
	}
}