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

});