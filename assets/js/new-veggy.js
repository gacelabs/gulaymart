$(document).ready(function() {

	$('#prod_name_checker').click(function() {

		if ($('#product_name').val().length > 3) {
			$('#product_name').removeClass('error');
			$('#category_container').removeClass('hide');
		} else {
			$('#product_name').addClass('error');
		}
	});


	$('.input-capsule').click(function() {
		$('.input-capsule').removeClass('active');
		$('.input-capsule').find('input').prop('checked', false);

		var cat = $(this).find('input').val();
			leafySubCat = ['Lettuce', 'Spinach', 'Other'],
			rootSubCat = ['Carrot', 'Potato', 'Other'],
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
			$('#sub_category>ul').append("<li class='input-capsule'><input type='radio' name='sub_category' id='"+v+"' value='"+v+"'><label for='"+v+"'>"+v+"</label></li>")
		});
	});

});