$(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover({
		'trigger': 'hover'
	});

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
				$('#storefront_page_container').find('.'+id).text(val);
				$(this).next().css('color', '#aaa');
			} else if (val.length <= 30) {
				$('#storefront_page_container').find('.'+id).text(val);
				$(this).next().css('color', '#aaa');
			} else {
				$(this).next().css('color', '#b92525');
			}

			if (id == "farm_name" && val.length == 0) {
				$('#storefront_page_container').find('.farm_name').text('The Humble Farm');
			}
			if (id == "tagline" && val.length == 0) {
				$('#storefront_page_container').find('.tagline').text('Your friendly neighborhood farmer');
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
					.attr({'id':'location-input-'+limit});
				$('<input />', {required:'required', type:'hidden', name:'user_farm_locations[1][]', class:'user-farm-locations'}).insertAfter(clone.find('input:first'));
			}
		});

		$(document).on('click', '#remove_loc_btn', function() {
			$(this).parents().eq(1).remove();
		});
	});

	// banner section
	$(function() {
		$('#banner_section').change(function() {
			$('#storefront_page_container').find('.banner-section').removeClass('hide');
			$('#storefront_page_container').find('.banner-section img').attr('src', 'assets/images/banner/'+$(this).val());
		});
	});

	// social media url
	$(function() {
		$('.social-url').keyup(function() {
			var val = $(this).val(),
				id 	 = $(this).data('id');
			$('#storefront_page_container').find(id).attr('href', val);
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
			console.log($(this).attr('id'));
			if ($(this).attr('id') == "diff_loc") {
				$('#location_list').removeClass('hide');
				$('#location_list').find('input:text[id^="location-input-"]').attr({'required':'required'});
				$('#same_loc_container').addClass('hide');
			} else {
				$('#location_list').addClass('hide');
				$('#location_list').find('input:text[id^="location-input-"]').removeAttr('required');
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
				$('#storefront_page_container').find(obj.ui).removeAttr('style').attr('style', 'background-image: url('+obj.selected[0].url_path+');');
				selected = obj.selected[0].url_path;
			} else {
				$('#storefront_page_container').find(obj.ui).removeAttr('style').attr('style', 'background-image: url('+obj.selected.url_path+');');
				selected = obj.selected.url_path;
			}
		} else {
			$('#storefront_page_container').find(obj.ui).removeAttr('style').attr('style', 'background-image: url('+obj.selected+');');
			selected = obj.selected;
		}

		if ($('#'+obj.col).length) $('#'+obj.col).prop('value', selected).val(selected);
	}
	$('#media_modal').modal('hide');
}

var setStoreFarmLocation = function(obj) {
	// console.log(obj);
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
	// console.log(obj);
	if ($('form.storefront-forms').find('[name]').hasClass('error') == false) {
		$('form.storefront-forms').each(function(i, elem) {
			$(elem).find('.farm_id').val(obj.user_farms.id);
			$('#storefrontTab').remove();
			var a_tag = $('<a class="text-link btn btn-default normal-radius icon-left" id="storefrontTab" target="storefrontTab" href="store/'+obj.user_farms.id+'/'+obj.user_farms.name.replace(/\s+/g, '-').toLowerCase().trim()+'"><i class="fa fa-external-link-square"></i> View Store</a>');
			$(elem).find('button:submit').parent('li').addClass('text-right').prepend(a_tag);
			setTimeout(function() {
				$(elem).find('button:submit').text('Update');
			}, 900);
		});
		var farm_locations = (obj.user_farm_locations[1] != undefined ? obj.user_farm_locations[1] : false);
		$('#location_list').find('.user-farm-locations').each(function(i, elem) {
			if (farm_locations && farm_locations[i] != undefined) {
				elem.value = farm_locations[i];
			}
		});
	}
}

var agreementSigned = function(obj) {
	$('#agree-terms-form').find('button:submit').attr('disabled', 'disabled');
}