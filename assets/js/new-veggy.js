$(document).ready(function() {

	$('#prod_name_checker').click(function() {

		if ($('#product_name').val().length > 3) {
			$('#product_name').removeClass('error');
			$('#category_container').removeClass('hide');
		} else {
			$('#product_name').addClass('error');
		}
	});

	$('#product_name').on('keyup', function() {
		if ($(this).val().length == 0) {
			$('#sub_category, #category_container').addClass('hide');
			$('[id*="category-"], [id*="subcategory-"]').prop('checked', false);
			$('.input-capsule').removeClass('active');
			$('#product_name').addClass('error');
		} else {
			$('#product_name').removeClass('error');
		}
	}).on('change', function() {
		$('#prod_name_checker').trigger('click');
	});



	/*$('.input-capsule').click(function() {
		$('.input-capsule').removeClass('active');
		$('.input-capsule').find('input').prop('checked', false);

		var cat = $(this).find('input').val();
			leafySubCat = ['Lettuce', 'Spinach', 'Other'],
			rootSubCat = ['Carrot', 'Potato', 'Turnip', 'Radish', 'Other'],
			cruSubCat = ['Cabbage', 'Cauliflower', 'Broccoli', 'Brussels Sprout', 'Other'],
			marSubCat = ['Squash', 'Cucumber', 'Zucchini', 'Other'],
			steSubCat = ['Celery', 'Asparagus', 'Rosemary', 'Other'],
			allSubCat = ['Onion', 'Garlic', 'Other'];


		$(this).find('input').prop('checked', true);
		$(this).addClass('active');

		switch(cat) {
			case 'leafy':
				var subCatShow = leafySubCat;
				break;
			case 'root':
				var subCatShow = rootSubCat;
				break;
			case 'cruciferous':
				var subCatShow = cruSubCat;
				break;
			case 'marrow':
				var subCatShow = marSubCat;
				break;
			case 'stem':
				var subCatShow = steSubCat;
				break;
			case 'allium':
				var subCatShow = allSubCat;
				break;
		}

		$('#sub_category').removeClass('hide');
		$('#sub_category>ul').children('li').remove();

		$.each(subCatShow, function(i, v) {
			$('#sub_category>ul').append("<li class='input-capsule'><input type='radio' name='sub_category' id='"+v+"' value='"+v+"' required><label for='"+v+"'>"+v+"</label></li>")
		});
	});

	$(document).on('click', '[type="radio"]', function() {
		$('#category_container').append("<div id='basic_btn_container' style='margin-top:15px;text-align:right;'><button class='btn btn-theme normal-radius'>Next<i class='fa fa-chevron-right icon-right'></i></button></div>");
	});*/


	$('#prod_attribute .dropdown-menu li a').click(function() {
		var preText = $(this).text();
		$(this).parents().eq(3).prev('input').addClass('has-value').val('');
		$(this).parents().eq(3).prev('input').removeClass('error');
		$(this).parents().eq(3).prev('input').val(preText);

		showBtn();
	});

	$('#prod_attribute input').on('keyup', function() {
		var attVal = $(this).val();

		if ($.trim(attVal).length > 5) {
			$(this).addClass('has-value');
			$(this).removeClass('error');
		} else {
			$(this).addClass('error');
			$(this).removeClass('has-value');
		}

		showBtn();
	});

	function showBtn() {
		setTimeout(function() {
			if ($('#prod_attribute input.has-value').length > 4) {
				$('#attr_btn_container').remove();

				$('#prod_attribute').append("<div id='attr_btn_container' style='margin-top:15px;text-align:right;'><button class='btn btn-theme normal-radius'>Next<i class='fa fa-chevron-right icon-right'></i></button></div>");
			} else {
				$('#attr_btn_container').remove();
			}
		},100);
	}

	$('.input-capsule [id^="category-"]').click(function() {
		var uiParent = $(this).parent('li');
		$('.input-capsule').removeClass('active');
		$('.input-capsule').find('input').prop('checked', false);

		var catid = $(this).attr('id');
		$(this).prop('checked', true);
		uiParent.addClass('active');

		$('#sub_category').removeClass('hide');
		$('#sub_category').find('ul[class*=category-]').addClass('hide');
		$('#sub_category').find('ul.'+catid).removeClass('hide');
		
		$('#basic_btn_container').hide();
		$('#sub_category').find('ul.'+catid).find('input:radio').off('click').on('click', function() {
			$('#basic_btn_container').show();
		});
	});

	runMediaUploader(function(elem) {
		var url_path = $(elem).next('input[type="radio"]').data('url-path');
		if (url_path != undefined) {
			$('.order-photo').removeAttr('style');
			$('.order-photo').attr({'style': 'background-image: url("'+url_path+'")'});
		}
	});

});


$(window).on('load resize change scroll', function() {
	var conWidth = $('#score_detail_container').width();

	$('#score-detail-panel').css('width', conWidth);
});

var setProductScore = function(obj) {
	// console.log(obj);
	var data_pos = obj.pos;
	var position = parseInt(data_pos) + 1;
	if ($('.dash-panel.score-'+(position)).length) {
		$('.dash-panel.score-'+(position)).removeClass('hide');
		var fromHeight = parseInt($('nav').height()) + 1;
		var iTop = ($('.dash-panel.score-'+position).offset().top - fromHeight);
		$("html,body").stop().animate({ scrollTop: iTop, scrollLeft: 0 }, 500);
	}
	if ($('.dash-panel.score-'+parseInt(data_pos)).length) {
		$('.dash-panel.score-'+parseInt(data_pos)).find('form').find('[name="passed"]').remove();
		$('.dash-panel.score-'+parseInt(data_pos)).find('form').prepend('<input type="hidden" name="passed" value="'+obj.passed+'"/>');
	}
	if (obj.product_id != undefined) {
		$('[name="product_id"]').remove();
		$('.dash-panel[class*=score-]').each(function(i, elem) {
			$(elem).find('form').prepend('<input type="hidden" name="product_id" value="'+obj.product_id+'"/>');
		});
	}
	var percent_gage = [10, 30, 60, 80, 100];
	var percent = parseInt($('.timeline-border-progress').attr('data-percent'));
	// console.log(percent, percent_gage[obj.pos]);
	if (percent < percent_gage[obj.pos]) {
		$('.timeline-border-progress').removeAttr('data-percent');
	}
	switch (obj.pos) {
		case '0':
			$('.order-title').text(obj.products.name);
			$('.order-link').attr('href', 'basket/view/'+obj.product_id+'/'+($.trim(obj.products.name).replace(/\s+/g, '-').toLowerCase()));
			if (percent < percent_gage[obj.pos]) {
				$('.timeline-border-progress').attr('data-percent', '10');
			}
		break;
		case '1':
			$.each(obj.products_attribute, function(i, data) {
				$('[name^="products_attribute"][value="'+data.id+'"]').remove();
				$('.dash-panel.score-'+parseInt(obj.pos))
				.find('form')
				.prepend('<input type="hidden" name="products_attribute['+i+'][id]" value="'+data.id+'" />');
			});
			if (percent < percent_gage[obj.pos]) {
				$('.timeline-border-progress').attr('data-percent', '30');
			}
		break;
		case '2':
			$('.order-price').text(obj.products.price);
			$('.order-unit').text(obj.products.measurement);
			if (percent < percent_gage[obj.pos]) {
				$('.timeline-border-progress').attr('data-percent', '60');
			}
		break;
		case '3':
			if (percent < percent_gage[obj.pos]) {
				$('.timeline-border-progress').attr('data-percent', '80');
			}
		break;
		case '4':
			if (percent < percent_gage[obj.pos]) {
				$('.timeline-border-progress').attr('data-percent', '100');
			}
		break;
	}

	$('h3.text-capsule:eq('+parseInt(obj.pos)+')').addClass('score');
}

var failedProductScore = function(obj) {
	// console.log(obj);
	var panelCnt = $('.dash-panel[class*=score-]').length;
	for (var position = panelCnt-1; position >= 1; position--) {
		if ($('.dash-panel.score-'+(position)).length) {
			$('h3.text-capsule:eq('+position+')').removeClass('score');
		}
	}
}

var redirectNewProduct = function(obj) {
	var sMessage = 'Product successfully created, It is now queued up for product review.';
	if (obj.updated == 1) {
		sMessage = 'Product successfully updated.';
	} else {
		$('h3.text-capsule:eq('+parseInt(obj.pos)+')').addClass('score');
		$('.timeline-border-progress').attr('data-percent', '100');
	}
	runAlertBox({type:'success', message: sMessage, unclose: true});
	// console.log(obj);
	$('.order-title').text(obj.products.name);
	$('.order-link').attr('href', 'basket/view/'+obj.product_id+'/'+($.trim(obj.products.name).replace(/\s+/g, '-').toLowerCase()));
	$.each(obj.file_photos, function(i, data) {
		if (data.is_main == 1) {
			// console.log(data);
			if ($('.order-photo').length) {
				$('.order-photo').removeAttr('style');
				$('.order-photo').attr({'style': 'background-image: url("'+data.url_path+'")'});
			}
			if ($('.order-photo').length) {
				$('.order-photo').removeAttr('style');
				$('.order-photo').attr({'style': 'background-image: url("'+data.url_path+'")'});
			}
		}
	});
	var cnt = 0;
	$.each(obj.products_location, function(i, data) {
		if ($('.order-price').eq(cnt).length) {
			$('.order-price').eq(cnt).text(data.price);
			$('.order-unit').eq(cnt).text(data.measurement);
			$('.order-duration').eq(cnt).text(data.duration);
		}
		cnt++;
	});
	$('#preview_container').removeClass('hide');
}

var lastScrollTop = 0;
$(window).on('scroll', function(){
	var st = $(this).scrollTop();
	if (st == 0) {
		$('#score-detail-panel').removeClass('score-detail-scroll');
	}
	if (st > lastScrollTop){
		// downscroll code
		$('#score-detail-panel').addClass('score-detail-scroll');
	}
	lastScrollTop = st;
});