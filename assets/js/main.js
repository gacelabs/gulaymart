$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover({
		'trigger': 'hover'
	});

	$(document.body).on('click', '.resetpass-btn', function(e) {
		$('form.sign-in-form').addClass('hide');
		$('form.resetpass-form').removeClass('hide');
		$('.ask-sign-in').removeClass('hide');
		$('.fb-login-btn').addClass('hide');
		$('.login-detail').addClass('hide');
		$('.reset-detail').removeClass('hide');
	});

	$(document.body).on('click', '.ask-sign-in', function(e) {
		$('form.sign-in-form').removeClass('hide');
		$('form.resetpass-form').addClass('hide');
		$('.ask-sign-in').addClass('hide');
		$('.fb-login-btn').removeClass('hide');
		$('.login-detail').removeClass('hide');
		$('.reset-detail').addClass('hide');
	});

	$('form:not([data-ajax])').on('submit', function(e) {
		var uiButtonSubmit = $(e.target).find('[type=submit]'), loadingText = 'Processing ...';
		if (uiButtonSubmit.length && $(e.target).find('.error').length == 0) {
			if (typeof uiButtonSubmit.attr('loading-text') == 'undefined') {
				loadingText = uiButtonSubmit.html();
			} else if (typeof uiButtonSubmit.attr('loading-text') != 'undefined') {
				loadingText = uiButtonSubmit.attr('loading-text');
			}
			uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> '+loadingText);
		}
	});

	/*for checkbox switch*/
	$('form[data-ajax="2"]').find('input, select').each(function(i, elem) {
		$(elem).bind('change', function(e) {
			$(e.target).parents('form[data-ajax="2"]:first').trigger('submit');
		});
	});
	/*end for checkbox switch*/

	var oPauseAjax = false;
	$('a[data-ajax]').off('click').on('click', function(e) {
		e.preventDefault();
		var oData = $(e.target).data('json') != undefined ? $(e.target).data('json') : {};
		var isJsonPCallback = $(e.target).data('call-jsonp') != undefined ? $(e.target).data('call-jsonp') : 1;
		var oSettings = {
			url: e.target.href,
			type: 'get',
			data: oData,
			dataType: 'json',
			success: function(response) {
				var bConfirmed = true;
				if (response && response.type && response.type.length && response.message.length) {
					bConfirmed = runAlertBox(response, undefined, bConfirmed);
				}
				if (bConfirmed) {
					setTimeout(function() {
						if (response && (typeof response.redirect == 'string')) {
							if (response.redirect) window.location = response.redirect;
						}
					}, 3000);
					if (response && (typeof response.callback == 'string')) {
						var fn = eval(response.callback);
						if (typeof fn == 'function') {
							// console.log(response.callback, 'function');
							fn(response.data, e);
						}
					}
				}
			},/*
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			}*/
		};
		if (isJsonPCallback) {
			oSettings.dataType = 'jsonp';
			oSettings.jsonpCallback = 'gmCall';
		}
		if (oPauseAjax != false && oPauseAjax.readyState !== 4) oPauseAjax.abort();
		oPauseAjax = $.ajax(oSettings);
	});

	if (oUser && oUser.is_profile_complete == 0) {
		runAlertBox({type:'info', message: PROFILE_INFO_MESSAGE});
	} else {
		function removeUrlParams() {
			var href = window.location.origin + window.location.pathname;
			window.history.replaceState({}, document.title, href);
		}
		var sSuccessMessage = getParameterByName('success');
		var sInfoMessage = getParameterByName('info');
		var sWarnMessage = getParameterByName('warn');
		var sConfirmMessage = getParameterByName('confirm');
		var sErrorMessage = getParameterByName('error');
		if ($.trim(sSuccessMessage).length) {
			runAlertBox({type:'success', message: sSuccessMessage});
			removeUrlParams();
		} else if ($.trim(sInfoMessage).length) {
			runAlertBox({type:'info', message: sInfoMessage});
			removeUrlParams();
		} else if ($.trim(sWarnMessage).length) {
			runAlertBox({type:'warn', message: sWarnMessage});
			removeUrlParams();
		} else if ($.trim(sConfirmMessage).length) {
			runAlertBox({type:'confirm', message: sConfirmMessage});
			removeUrlParams();
		} else if ($.trim(sErrorMessage).length) {
			runAlertBox({type:'error', message: sErrorMessage});
			removeUrlParams();
		}
	}
	
	$('#main-obj-script').remove();

	if ($('.render-datatable').length) {
		if ($.fn.dataTable != undefined) $.fn.dataTable.ext.errMode = 'none';
		$(document.body).find('table').each(function(i, elem) {
			var text = $.trim($(elem).find('tr:first th:first').text());
			// console.log(text);
			var blanks = {targets: []}, currency = {targets: []};
			/*if ($.inArray(text.toLowerCase(), ['actions','activity']) >= 0) {
				if (blanks.orderable == undefined) blanks.orderable = false;
				blanks.targets.push(i);
			}
			var text2 = $.trim($(elem).find('tr:first th:last').text());
			if ($.inArray(text2.toLowerCase(), ['photo','image','']) >= 0) {
				var last_cnt = $(elem).find('tr:first th').length - 1;
				blanks.targets.push(last_cnt);
			}*/
			var /*text3 = $.trim($(elem).find('tr:first th:last').text()),*/ aaSort = 1, aaSortDir = 'asc';
			/*if ($.inArray(text3.toLowerCase(), ['updated']) >= 0) {
				aaSort = $(elem).find('tr:first th').length - 1;
				aaSortDir = 'desc';
			}*/
			$(elem).find('tr:first th').each(function(i, unit) {
				var text = $.trim($(unit).text());
				if ($.inArray(text.toLowerCase(), ['actions','']) >= 0) {
					if (blanks.orderable == undefined) blanks.orderable = false;
					blanks.targets.push(i);
				}
				if ($.inArray(text.toLowerCase(), ['photo','image','']) >= 0) {
					var last_cnt = $(elem).find('tr:first th').length - 1;
					blanks.targets.push(last_cnt);
				}
				if ($.inArray(text.toLowerCase(), ['updated']) >= 0) {
					aaSort = $(elem).find('tr:first th').length - 1;
					aaSortDir = 'desc';
				}
			});
			// console.log(blanks, last_cnt);
			oSettings = {
				// stateSave: true,
				responsive: true,
				colReorder: true,
				aaSorting: [[ aaSort, aaSortDir ]],
				columnDefs: [blanks],
				language: {
					"search": "",
					"lengthMenu": "_MENU_ Records",
					"infoEmpty": "",
					"emptyTable": "No posts found.",
					"searchPlaceholder": "Search...",
					"info": "Total Records: <b>_TOTAL_</b>",
					"infoFiltered": " matched found",
					"paginate": {
						"first": "First",
						"last": "Last",
						"next": "Next",
						"previous": "Back"
					},
					"processing": "Getting entries...",
					"loadingRecords": "Loading entries...",
					/*ETO YUNG COMPLETE PROPERTIES NYA POI LABEL MO NALANG*/
					/*"decimal":        "",
					"emptyTable":     "No data available in table",
					"info":           "Showing _START_ to _END_ of _TOTAL_ entries",
					"infoEmpty":      "Showing 0 to 0 of 0 entries",
					"infoFiltered":   "(filtered from _MAX_ total entries)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Show _MENU_ entries",
					"loadingRecords": "Loading...",
					"processing":     "Processing...",
					"search":         "Search:",
					"zeroRecords":    "No matching records found",
					"paginate": {
						"first":      "First",
						"last":       "Last",
						"next":       "Next",
						"previous":   "Previous"
					},
					"aria": {
						"sortAscending":  ": activate to sort column ascending",
						"sortDescending": ": activate to sort column descending"
					}*/
				}
			};

			$(elem).find('tr:first th:not(:first):not(:last)').each(function(j, elemTR) {
				if ($.trim($(elemTR).text()).toLowerCase().indexOf('price') >= 0 || 
					$.trim($(elemTR).text()).toLowerCase().indexOf('rate') >= 0 || 
					$.trim($(elemTR).text()).toLowerCase().indexOf('amount') >= 0) {
					currency.targets.push(j);
				}
			});
			oSettings.columnDefs.push(currency);
			// console.log(blanks, currency, oSettings);
			$(elem).DataTable(oSettings);
		});
	}

	if (window.location.hash) {
		var hash = window.location.hash;
		if ($(hash).length) {
			$("html,body").stop().animate({ scrollTop: ($(hash).offset().top - $('#dashboard_navbar').height() - 25), scrollLeft: 0 }, 1000);
		}
	}
	
	autosize($('textarea'));

	$('form.sign-in-form').bind('submit', function() {
		if ($(this).find('[name].error').length == 0) {
			$('a,select,button,input:button,input:submit').addClass('stop').prop('disabled', true).attr('disabled', 'disabled');
			$('.login-with-social').addClass('hide');
			$('[data-dismiss="modal"]').css('visibility', 'hidden');
		}
	});

	// if (mobileAndTabletCheck()) runSwiper();
});

var runSwiper = function() {
	var pageLinks = ['/basket/','/orders/','/orders/messages/','/farm/inventory/'];
	var currentPage = $.inArray(window.location.pathname, pageLinks);
	var xDown = null;
	var yDown = null;

	function getTouches(evt) {
		return (
			evt.touches || evt.originalEvent.touches // browser API
		); // jQuery
	}
	function handleTouchStart(evt) {
		const firstTouch = getTouches(evt)[0];
		xDown = firstTouch.clientX;
		yDown = firstTouch.clientY;
	}
	function handleTouchMove(evt) {
		if (!xDown || !yDown) {
			return;
		}
		var xUp = evt.touches[0].clientX;
		var yUp = evt.touches[0].clientY;

		var xDiff = xDown - xUp;
		var yDiff = yDown - yUp;

		if (Math.abs(xDiff) > Math.abs(yDiff)) {
			/*most significant*/
			if (xDiff > 0) {
				/* right swipe */
				console.log("right swipe");
				if (pageLinks[currentPage+1] != undefined) {
					window.location = pageLinks[currentPage+1];
				}
			} else {
				/* left swipe */
				console.log("left swipe");
				if (pageLinks[currentPage-1] != undefined) {
					window.location = pageLinks[currentPage-1];
				}
			}
		} else {
			if (yDiff > 0) {
				/* down swipe */
				console.log("down swipe");
			} else {
				/* up swipe */
				console.log("up swipe");
			}
		}
		/* reset values */
		xDown = null;
		yDown = null;
	}

	document.addEventListener("touchstart", handleTouchStart, false);
	document.addEventListener("touchmove", handleTouchMove, false);
}

var reCountMenuNavs = function(sNav, totalItems) {
	if (totalItems.toString().length > 4) totalItems = Number(totalItems).toExponential();
	switch (sNav) {
		case 'fulfill': case 'fulfills': case 'fulfillments':
			if ($('#nav-fulfill-count').length == 0) $('[data-menu-nav=""fulfillments]]').find('span').append('<kbd id="nav-fulfill-count"></kbd>');
			if (totalItems) {
				$('#nav-fulfill-count').removeClass('hide').text(totalItems);
			} else {
				$('#nav-fulfill-count').addClass('hide').text('');
			}
			if ($('kbd.nav-fulfill-count').length == 0) $('[data-menu-nav="fulfills"]').find('span').append('<kbd class="nav-fulfill-count hidden-lg hidden-md hidden-sm"></kbd>');
			if (totalItems) {
				$('kbd.nav-fulfill-count').removeClass('hide').text(totalItems);
			} else {
				$('kbd.nav-fulfill-count').addClass('hide').text('');
			}
		break;
		case 'basket': case 'baskets':
			if ($('#nav-basket-count').length == 0) $('[data-menu-nav="baskets"]').find('span').append('<kbd id="nav-basket-count"></kbd>');
			if (totalItems) {
				$('#nav-basket-count').removeClass('hide').text(totalItems);
			} else {
				$('#nav-basket-count').text('Buy now!');
			}
			if ($('kbd.nav-basket-count').length == 0) $('[data-menu-nav="baskets"]').find('span').append('<kbd class="nav-basket-count hidden-lg hidden-md hidden-sm"></kbd>');
			if (totalItems) {
				$('kbd.nav-basket-count').removeClass('hide').text(totalItems);
			} else {
				$('kbd.nav-basket-count').text('Buy now!');
			}
		break;
		case 'order': case 'orders':
			if ($('#nav-order-count').length == 0) $('[data-menu-nav="orders"]').find('span').append('<kbd id="nav-order-count"></kbd>');
			if (totalItems) {
				$('#nav-order-count').removeClass('hide').text(totalItems);
			} else {
				$('#nav-order-count').addClass('hide').text('');
			}
			if ($('kbd.nav-order-count').length == 0) $('[data-menu-nav="orders"]').find('span').append('<kbd class="nav-order-count hidden-lg hidden-md hidden-sm"></kbd>');
			if (totalItems) {
				$('kbd.nav-order-count').removeClass('hide').text(totalItems);
			} else {
				$('kbd.nav-order-count').addClass('hide').text('');
			}
		break;
		case 'message': case 'messages':
			if ($('#nav-messages-count').length == 0) $('[data-menu-nav="messages"]').find('span').append('<kbd id="nav-messages-count"></kbd>');
			if (totalItems) {
				$('#nav-messages-count').removeClass('hide').text(totalItems);
			} else {
				$('#nav-messages-count').addClass('hide').text('');
			}
			if ($('kbd.nav-messages-count').length == 0) $('[data-menu-nav="messagess"]').find('span').append('<kbd class="nav-messages-count hidden-lg hidden-md hidden-sm"></kbd>');
			if (totalItems) {
				$('kbd.nav-messages-count').removeClass('hide').text(totalItems);
			} else {
				$('kbd.nav-messages-count').addClass('hide').text('');
			}
		break;
	}
}

var checkCountMenuNavs = function(oData) {
	var oSettings = {
		url: 'support/check_menunav_counts/',
		type: 'post',
		dataType: 'json',
		data: oData,
		success: function(response) {
			// console.log(response);
			if (response) {
				if (response.nav && response.id == oUser.id) {
					reCountMenuNavs(response.nav, response.total_items);
				}
			}
		}
	};
	$.ajax(oSettings);
}

var initMenuNavsCount = function() {
	realtime.bind('count-item-in-menu', 'incoming-menu-counts', function(object) {
		var oData = object.data;
		// console.log(oData);
		if (oData.success) {
			if (Object.keys(oData.id).length) {
				if ($.inArray(oUser.id, oData.id) >= 0) checkCountMenuNavs(oData);
			} else {
				if (oData.id == oUser.id) checkCountMenuNavs(oData);
			}
		}
	});
}

var reCountStatusTabs = function(sMenu, sTab, totalItems) {
	if (totalItems.toString().length > 4) totalItems = Number(totalItems).toExponential();
	var sClass = 'no-count';
	if (sMenu == 'messages') sClass = 'hide';
	if (totalItems) {
		$('[data-menu="'+sMenu+'"][data-nav="'+sTab+'"]').find('kbd').removeClass(sClass).text(totalItems);
	} else {
		$('[data-menu="'+sMenu+'"][data-nav="'+sTab+'"]').find('kbd').addClass(sClass).text('');
	}
}

var checkCountStatusTabs = function(oData) {
	var oSettings = {
		url: 'support/check_stattab_counts/',
		type: 'post',
		dataType: 'json',
		data: oData,
		success: function(response) {
			// console.log(response);
			if (response) {
				if (response.menu && response.tab && response.id == oUser.id) {
					reCountStatusTabs(response.menu, response.tab, response.total_items);
				}
			}
		}
	};
	$.ajax(oSettings);
}

var initStatusTabsCount = function() {
	realtime.bind('count-item-in-tab', 'incoming-tab-counts', function(object) {
		var oData = object.data;
		// console.log(oData);
		if (oData.success) {
			if (Object.keys(oData.id).length) {
				if ($.inArray(oUser.id, oData.id) >= 0) checkCountStatusTabs(oData);
			} else {
				if (oData.id == oUser.id) checkCountStatusTabs(oData);
			}
		}
	});
}