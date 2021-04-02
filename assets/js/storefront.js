$(document).ready(function() {

	$('.custom-item-btn').click(function() {
		$('#storefront_nav').find('div.custom-item-child').removeClass('active');
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
				$('.'+id).text(val);
				$(this).next().css('color', '#aaa');
			} else if (val.length <= 30) {
				$('.'+id).text(val);
				$(this).next().css('color', '#aaa');
			} else {
				$(this).next().css('color', '#b92525');
			}

			if (id == "farm_name" && val.length == 0) {
				$('.farm_name').text('The Humble Farm');
			}
			if (id == "tagline" && val.length == 0) {
				$('.tagline').text('Your friendly neighborhood farmer');
			}
		});
	});

	// clone/ append loc input
	$(function() {
		$('#add_loc_btn').click(function() {
			var limit = $('#location_container').children('.input-group:visible').length;

			if (limit <= 4) {
				$('#clone_me').clone().removeClass('hide').appendTo('#location_container');
			}
		});

		$(document).on('click', '#remove_loc_btn', function() {
			$(this).parents().eq(1).remove();
		});
	});

	// banner section
	$(function() {
		$('#banner_section').change(function() {
			$('.banner_section').removeClass('hide');
			$('.banner_section').attr('src', 'assets/images/banner/'+$(this).val());
		});
	});

	// social media url
	$(function() {
		$('.social-url').keyup(function() {

		});
	});

	$('#agree-terms-form').find('input:checkbox').on('change', function(e) {
		if ($('#agree-terms-form').find('input:checkbox:checked').length == $('#agree-terms-form').find('input:checkbox').length) {
			$('#agree-terms-form').find('button:submit').removeAttr('disabled');
		} else {
			$('#agree-terms-form').find('button:submit').attr('disabled', 'disabled');
		}
	});

});
