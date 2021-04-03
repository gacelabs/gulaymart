//http://jsfiddle.net/laelitenetwork/puJ6G/
$('.btn-number').click(function(e){
	e.preventDefault();

	fieldName = $(this).attr('data-field');
	type      = $(this).attr('data-type');

	var input = $(this).parents().eq(1).find("input[name='"+fieldName+"']");
	var currentVal = parseInt(input.val());

	if (!isNaN(currentVal)) {
		if(type == 'minus') {

			if(currentVal > input.attr('min')) {
				input.val(currentVal - 1).change();
			} 
			if(parseInt(input.val()) == input.attr('min')) {
				$(this).attr('disabled', true);
			}

		} else if(type == 'plus') {

			if(currentVal < input.attr('max')) {
				input.val(currentVal + 1).change();
			}
			if(parseInt(input.val()) == input.attr('max')) {
				$(this).attr('disabled', true);
				$('.max-qty').removeClass('text-danger');
			}

		}
	} else {
		input.val(0);
	}
	$('.max-qty').removeClass('text-danger');
});

$('.input-number').focusin(function(){
	$(this).data('oldValue', $(this).val());
	$('.max-qty').removeClass('text-danger');
});

$('.input-number').change(function() {

	minValue =  parseInt($(this).attr('min'));
	maxValue =  parseInt($(this).attr('max'));
	valueCurrent = parseInt($(this).val());
	name = $(this).attr('name');
	eThis = $(this);	


	if(valueCurrent >= minValue) {
		$(this).parents('.variety-location').find(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled');
	} else {
		$(this).val($(this).data('oldValue'));
		$('.max-qty').removeClass('text-danger');
	}
	if(valueCurrent <= maxValue) {
		$(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled');
	} else {
		$(this).parents('.variety-location').find('.elem-block .max-qty').addClass('text-danger');
		$(this).val($(this).data('oldValue'));
	}

});

$(".input-number").keydown(function (e) {
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
		(e.keyCode == 65 && e.ctrlKey === true) || 
		(e.keyCode >= 35 && e.keyCode <= 39)) {
		return;
	}

	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}

	$('.max-qty').removeClass('text-danger');
});

// collapse summary
$('.condition-collapser').click(function() {
	$(this).next('.productpage-summary-inner').toggleClass('active');
});

$('#add_product_btn').click(function(e) {
	e.stopPropagation();
	$('#nav_basket').find('.notif-dot').remove();	
	$('#nav_basket').append('<i class="fa fa-circle text-success notif-dot"></i>');	
});

// thubmnail preview
$('#img_thumb_list>li').click(function() {
	var imgSrc = $(this).find('div.img-thumb-item').css('background-image'),
		bg = imgSrc.replace('url(','').replace(')','').replace(/\"/gi, "");

	$('.img-thumb-item').removeClass('active');
	$(this).find('.img-thumb-item').addClass('active');
	$('#main_img_preview').css({'background-image' : 'url('+bg+')'});
});