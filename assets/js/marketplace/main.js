$(document).ready(function() {
	loadMore($('#load_more_btn'));
	if ($('.simple-marquee-container').SimpleMarquee != undefined) {
		$('.simple-marquee-container').SimpleMarquee({
			duration: 40000,
		});
	}
});