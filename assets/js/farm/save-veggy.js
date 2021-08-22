$(document).ready(function() {

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
	});

	$('.input-capsule [id^="subcategory-"]').click(function() {
		var uiParent = $(this).parent('li');
		uiParent.siblings().removeClass('active');
		uiParent.siblings().find('input').prop('checked', false);

		$(this).prop('checked', true);
		uiParent.addClass('active');
	});

	$('#basic_btn_container').find('input:reset').bind('mouseup', function(e) {
		setTimeout(function() {
			$(e.target).parents('form:first').find('input:radio, input:checkbox').each(function(i, elem) {
				if ($(elem).is(':checked')) {
					$(elem).parent('li').siblings().removeClass('active');
					$(elem).parent('li').siblings().find('input').prop('checked', false);
					$(elem).parent('li').addClass('active');
					if ($(elem).attr('name') == 'products[category_id]') {
						var catid = $(elem).attr('id');
						$('#sub_category').removeClass('hide');
						$('#sub_category').find('ul[class*=category-]').addClass('hide');
						$('#sub_category').find('ul.'+catid).removeClass('hide');
					}
				}
			});
		}, 300);
	});

	$('#price_btn_container').find('input:reset').bind('mouseup', function(e) {
		setTimeout(function() {
			$(e.target).parents('form:first').find('input:checkbox').each(function(i, elem) {
				var uiParent = $(elem).parents('[id*="farmlocation-"]').first();
				// console.log(uiParent, uiParent.next('[js-element]'));
				if ($(elem).is(':checked')) {
					uiParent.next('[js-element]').removeClass('hide');
				} else {
					uiParent.next('[js-element]').addClass('hide');
				}
			});
		}, 300);
	});

	$('#prod_attribute .dropdown-menu li a').click(function() {
		var preText = $(this).text();
		$(this).parents().eq(3).prev('input').addClass('has-value').val('');
		$(this).parents().eq(3).prev('input').removeClass('error');
		$(this).parents().eq(3).prev('input').val(preText);
	});

	runMediaUploader(function(elem) {
		var url_path = $(elem).next('input[type="radio"]').data('url-path');
		if (url_path != undefined) {
			$('.order-photo').removeAttr('style');
			$('.order-photo').attr({'style': 'background-image: url("'+url_path+'")'});
		}
	});

});

var redirectNewProduct = function(obj) {
	var sMessage = 'Product successfully updated.';
	runAlertBox({type:'success', message: sMessage/*, unclose: true*/});

	// console.log(obj);
	$('.order-title').text(obj.products.name);
	$('.order-link').attr('href', 'basket/view/'+obj.product_id+'/'+($.trim(obj.products.name).replace(/\s+/g, '-').toLowerCase()));

	if (obj.file_photos) {
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
	}

	if (obj.products_location) {
		var cnt = 0;
		$.each(obj.products_location, function(i, data) {
			if ($('.order-price').eq(cnt).length) {
				$('.order-price').eq(cnt).text(data.price);
				$('.order-unit').eq(cnt).text(ucWords(data.measurement));
				$('.order-duration').eq(cnt).text(ucWords(data.duration));
			} else {
				if ($('.product-list-footer').find('ul:first').length > 1) {
					var ulUI = $('.product-list-footer').find('ul:first').clone();
				} else {
					var ulUI = $('.product-list-footer').find('ul:first');
					$('.product-list-footer').find('ul:first').remove();
				}
				ulUI.find('.order-price').text(data.price);
				ulUI.find('.order-unit').text(ucWords(data.measurement));
				ulUI.find('.order-duration').text(ucWords(data.duration));
				$('.product-list-footer').append(ulUI);
			}
			cnt++;
		});
	}

	if ($('.order-status').length && obj.activity != undefined) {
		var status = 'For review';
		switch (obj.activity) {
			case '1':
				status = 'Published';
			break;
			case '2':
				status = 'Rejected';
			break;
			case '3':
				status = 'Deleted';
			break;
		}
		$('.order-status').text(status);
	}
}