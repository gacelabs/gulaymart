$(document).ready(function() {
	
	/*$('.product-item-inner').masonry({
		itemSelector: '.product-item',
		masonry: {
			horizontalOrder: true
		},
	});

	var backToLoginForm = function (data) {
		$('[name="email_address"]').val('');
		$('[name="password"]').val('');
		setTimeout(function() {
			$('.ask-sign-in:visible').trigger('click');
			$('#login_modal').modal('hide');
		}, 1000);
	}

	var renderMoreVeggies = function (data) {
		if (data && data.post) {
			var selector = $('['+data.post.selector+']').parent('.product-item-inner');
			if (selector.length) {
				// selector.append($(data.html)).isotope('appended', $(data.html)).isotope('reloadItems');
				// selector.isotope({filter: '['+data.post.selector+']'});
				selector.isotope('insert', $(data.html)).isotope('reloadItems');
			}
		}
	}

	// $('.veggy-category-item').click(function() {
	// 	$('.veggy-category-item').find('.veggy-category-item-inner').removeClass('active');
	// 	$(this).find('.veggy-category-item-inner').addClass('active');

	// 	$('.product-item-inner').masonry();
	// });

	// $(document.body).find('[data-category-group]').off('click').on('click', function(e) {
	// 	$('button[data-category*="loadmore-"]').parent().hide();
	// 	var a = $(e.target);
	// 	if (a.prop('tagName') != 'A') a = a.parents('a:first');
	// 	var id = a.data('category-group');
	// 	$('.product-item-inner').isotope({filter: '[data-category="'+id+'"]'});
	// 	$('button[data-category="loadmore-'+id+'"]').parent().show();
	// });

	// $(document.body).find('.product-item-inner').isotope({filter: '[data-category="all"]'});

	// $(document.body).find('button[data-category*="loadmore-"]').off('click').on('click', function(evt) {
	// 	var jsonPages = $(evt.target).data('json'), i = parseInt($(evt.target).val()), selector = $(evt.target).data('selector');
	// 	if (jsonPages[i] != undefined) {
	// 		$(evt.target).prop('value', i+1).val(i+1);
	// 		simpleAjax('/marketplace/loadmore/', {record: i, selector: selector, page: jsonPages[i]}, $(evt.target));

	// 		$('.product-item-inner').masonry();
			
		}
		if (jsonPages.length == i+1) $(evt.target).hide();
	});*/

});

// $(window).on('load change resize', function() {

// 	var winWidth = $(window).width(),
// 		elem = $('#navbar-carousel').offset().top;

// 	if (winWidth > 767) {
// 		$('#bottom_nav_sm').addClass('hide');
// 	}

// 	$(window).scroll(function() {
// 		if (winWidth < 767 && $(window).scrollTop() > elem) {
// 			$('#bottom_nav_sm').removeClass('hide');
// 		}
// 		else {
// 			$('#bottom_nav_sm').addClass('hide');
// 		}
// 	});
//});

