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
			$('#sub_category>ul').children('li').remove();
			$('[name="category"], [type="radio"]').prop('checked', false);
			$('.input-capsule').removeClass('active');
			$('#product_name').addClass('error');
		} else {
			$('#product_name').removeClass('error');
		}
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

	$('#input_upload_images').change(function(){

		var checked = "";

		if ($(this)[0].files.length == 1) {
			checked = "checked";
		}

        for (var i= 0; i < $(this)[0].files.length; i++) {

        	$('#preview_images_list').append('<li data-toggle="tooltip" data-placement="top" title="Set as main"><div class="preview-image-item" style="background-image: url('+window.URL.createObjectURL(this.files[i])+')"></div><input type="radio" name="products_photo[index]" '+checked+' value="'+i+'" required /></li>');
        }

    	$('[data-toggle="tooltip"]').tooltip();
    	var position = $('.dash-panel[class*=score-]').length - 1;
		var iTop = ($('.dash-panel.score-'+position).offset().top - ($('nav').height() + 1));
		$("html,body").stop().animate({
			scrollTop: iTop, scrollLeft: 0
		}, 500);
    });

    $(document).on('click', '.preview-image-item', function() {
    	$(this).next('input[type="radio"]').prop('checked', true);
    });

});

var setProductScore = function(obj) {
	console.log(obj);
	var position = parseInt(obj.pos) + 1;
	if ($('.dash-panel.score-'+(position)).length) {
		$('.dash-panel.score-'+(position)).removeClass('hide');
		var iTop = ($('.dash-panel.score-'+position).offset().top - ($('nav').height() + 1));
		$("html,body").stop().animate({
			scrollTop: iTop, scrollLeft: 0
		}, 500);
	}
	if ($('.dash-panel.score-'+parseInt(obj.pos)).length) {
		$('.dash-panel.score-'+parseInt(obj.pos)).find('form').find('[name="passed"]').remove();
		$('.dash-panel.score-'+parseInt(obj.pos)).find('form').prepend('<input type="hidden" name="passed" value="'+obj.passed+'"/>');
	}
	if (obj.product_id != undefined) {
		$('[name="product_id"]').remove();
		$('.dash-panel[class*=score-]').each(function(i, elem) {
			$(elem).find('form').prepend('<input type="hidden" name="product_id" value="'+obj.product_id+'"/>');
		});
	}
	if (obj.pos == '1') {
		$.each(obj.products_attribute, function(i, data) {
			$('[name^="products_attribute"][value="'+data.id+'"]').remove();
			$('.dash-panel.score-'+parseInt(obj.pos))
			.find('form')
			.prepend('<input type="hidden" name="products_attribute['+i+'][id]" value="'+data.id+'" />');
		});
	}
	$('h3.text-capsule:eq('+parseInt(obj.pos)+')').addClass('score');
}

var failedProductScore = function(obj) {
	console.log(obj);
	var panelCnt = $('.dash-panel[class*=score-]').length;
	for (var position = panelCnt-1; position >= 1; position--) {
		if ($('.dash-panel.score-'+(position)).length) {
			$('h3.text-capsule:eq('+position+')').removeClass('score');
		}
	}
}

var redirectNewProduct = function(obj) {
	runAlertBox({type:'info', message: 'Page will be reloaded after 5 seconds, You may view your inventories <a href="/farm/inventory/"><b>HERE</b></a>'});
	setTimeout(function() {
		if (obj.passed) window.location.reload();
	}, 5000);
}