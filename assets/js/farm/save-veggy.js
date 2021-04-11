$(document).ready(function() {

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
	runAlertBox({type:'success', message: sMessage, unclose: true});

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
				$('.order-unit').eq(cnt).text(data.measurement);
				$('.order-duration').eq(cnt).text(data.duration);
			}
			cnt++;
		});
	}
}