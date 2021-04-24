$(document).ready(function() {
	// $('#storefront_navbar').find('li').click(function(e) {
	// 	var oThis = $(e.target);
	// 	if (oThis.prop('tagName') != 'LI') oThis = oThis.closest('li');
	// 	var id = oThis.data('id');
	// 	$('#storefront_navbar').find('li').removeClass('active');
	// 	oThis.addClass('active');
	// 	$('#storefront_product_container').find('[id]').addClass('hide');
	// 	$(id).removeClass('hide');
	// });

	$('.sf-navbar-btn').click(function() {
		$('.sf-navbar-btn').removeClass('active');
		$('.toggle-container').addClass('hide');
		$(this).addClass('active');

		var coName = $(this).attr('container-name');

		$('#storefront_product_container').find('div'+coName).removeClass('hide');
	});

});