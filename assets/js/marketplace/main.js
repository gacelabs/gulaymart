$(document).ready(function() {
	loadMore($('#load_more_btn'));

	$('#veggy_categories').animate({
		scrollLeft: $('.veggy-category-item-inner.active').offset().left
	},1000);

});