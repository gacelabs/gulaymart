$(document).ready(function() {

	$('.custom-item-btn').click(function() {
		$('.storefront_nav').find('div.custom-item-child').removeClass('active');
		$('.custom-item-btn').removeClass('active');
		$('.custom-item-btn').find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');

		$(this).addClass('active');

		$(this).next('div.custom-item-child').addClass('active');
		$(this).find('.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');
	});

	// farm identity
	$(function() {
		$('.input-keyup').keyup(function() {
			var val = $(this).val(),
				id 	 = $(this).attr('id');

			if (id == "tagline" && val.length <= 50) {
				$('#preview-store-page').contents().find('.'+id).text(val);
				$(this).next().css('color', '#aaa');
			} else if (val.length <= 30) {
				$('#preview-store-page').contents().find('.'+id).text(val);
				$(this).next().css('color', '#aaa');
			} else {
				$(this).next().css('color', '#b92525');
			}

			if (id == "farm_name" && val.length == 0) {
				$('#preview-store-page').contents().find('.farm_name').text('The Humble Farm');
			}
			if (id == "tagline" && val.length == 0) {
				$('#preview-store-page').contents().find('.tagline').text('Your friendly neighborhood farmer');
			}
		});
	});

	// clone/ append loc input
	$(function() {
		$('#add_loc_btn').click(function() {
			var limit = $('#location_list').children('.input-group:visible').length;
			if (limit <= 4) {
				var clone = $('#clone_me').clone();
				clone.removeClass('hide').removeAttr('id').appendTo('#location_list').find('input:first')
					.attr({'required':'required', 'id':'location-input-'+limit});
				$('<input />', {type:'hidden', name:'user_farm_locations[1][]'}).insertAfter(clone.find('input:first'));
			}
		});

		$(document).on('click', '#remove_loc_btn', function() {
			$(this).parents().eq(1).remove();
		});
	});

	// banner section
	$(function() {
		$('#banner_section').change(function() {
			$('#preview-store-page').contents().find('.banner_section').removeClass('hide');
			$('#preview-store-page').contents().find('.banner_section').attr('src', 'assets/images/banner/'+$(this).val());
		});
	});

	// social media url
	$(function() {
		$('.social-url').keyup(function() {
			var val = $(this).val(),
				id 	 = $(this).data('id');
			$('#preview-store-page').contents().find(id).attr('href', val);
		});
	});

	$('#agree-terms-form').find('input:checkbox').on('change', function(e) {
		if ($('#agree-terms-form').find('input:checkbox:checked').length == $('#agree-terms-form').find('input:checkbox').length) {
			$('#agree-terms-form').find('button:submit').removeAttr('disabled');
		} else {
			$('#agree-terms-form').find('button:submit').attr('disabled', 'disabled');
		}
	});

	runMediaUploader();
	// farm location toggle
	$(function() {
		$('.pick-up-loc').click(function() {
			if ($(this).attr('id') == "diff_loc") {
				$('#location_list').removeClass('hide');
				$('#same_loc_container').addClass('hide');
			} else {
				$('#location_list').addClass('hide');
				$('#same_loc_container').removeClass('hide');
			}
		})
	});

	$(function() {
		$('.select-all').click(function() {
			if ($(this).parent().next('select').length) {
				if ($(this).is(':checked')) {
					$(this).parent().next('select').find('option').prop('selected', true);
				} else {
					$(this).parent().next('select').find('option').prop('selected', false);
				}
				$(this).parent().next('select').trigger('chosen:updated');
			}
		});
	});

});

var changeUIImage = function(obj) {
	// console.log(obj);
	if (obj.selected != undefined && obj.ui != undefined && obj.col != undefined) {
		// console.log(obj.selected);
		var selected = '';
		if (typeof obj.selected == 'object' && Object.keys(obj.selected).length) {
			if (obj.selected[0] != undefined) {
				$('#preview-store-page').contents().find(obj.ui).removeAttr('style').attr('style', 'background-image: url('+obj.selected[0].url_path+');');
				selected = obj.selected[0].url_path;
			} else {
				$('#preview-store-page').contents().find(obj.ui).removeAttr('style').attr('style', 'background-image: url('+obj.selected.url_path+');');
				selected = obj.selected.url_path;
			}
		} else {
			$('#preview-store-page').contents().find(obj.ui).removeAttr('style').attr('style', 'background-image: url('+obj.selected+');');
			selected = obj.selected;
		}

		if ($('#'+obj.col).length) $('#'+obj.col).prop('value', selected).val(selected);
	}
	$('#media_modal').modal('hide');
}

var setStoreFarmLocation = function(obj) {
	console.log(obj);
	if (obj && obj.loc_input) {
		var input_id = obj.loc_input;
		if ($(input_id).length) {
			delete obj.loc_input;
			var address = obj.address_1 + ' ' + obj.address_2.replace(/\s+/g, ' ').trim();
			$(input_id).prop('value', address).val(address);
			$(input_id).get(0).setSelectionRange(0, 0);
			$(input_id).next('input:hidden').prop('value', JSON.stringify(obj)).val(JSON.stringify(obj));
			$('#farm_location_modal').modal('hide');
		}
	}
}

var refreshStorePreview = function(obj) {
	console.log(obj);
	if ($('form.storefront-forms').find('[name]').hasClass('error') == false) {
		$('form.storefront-forms').each(function(i, elem) {
			$(elem).find('.farm_id').val(obj.user_farms.id);
		});
		document.getElementById("preview-store-page").src = document.getElementById("preview-store-page").src;
	}
}