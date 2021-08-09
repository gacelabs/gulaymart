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

		$('form.invalid-fb-email').addClass('hide');
		$('.fb-login-panel').addClass('hide');
		$('[name="email"]').removeClass('error');
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

	if (typeof runATagAjax == 'function') runATagAjax();

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
			var aaSort = 1, aaSortDir = 'asc';

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

var toggleBlink = function (ui, callback) {
	if (ui != undefined && ui.length) {
		var removeMethod = new Promise((resolve, reject) => {
			[1,2,3].forEach((id, index, array) => {
				ui.fadeTo('slow', 0.5).fadeTo('slow', 1.0);
				if (index === array.length -1) resolve();
			});
		});
		removeMethod.then(() => {
			if (typeof callback == 'function') callback(ui);
			console.log('All done!');
		});
	}
}

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
	// if (totalItems.toString().length > 4) totalItems = Number(totalItems).toExponential();
	switch (sNav) {
		case 'fulfill': case 'fulfills': case 'fulfillment': case 'fulfillments':
			if ($('#nav-fulfill-count').length == 0) $('[data-menu-nav="fulfillments"]').find('span').append('<kbd id="nav-fulfill-count"></kbd>');
			if (totalItems) {
				$('#nav-fulfill-count').removeClass('hide').text(totalItems);
			} else {
				$('#nav-fulfill-count').addClass('hide').text('');
			}
			if ($('kbd.nav-fulfill-count').length == 0) $('[data-menu-nav="fulfillments"]').find('span').append('<kbd class="nav-fulfill-count hidden-lg hidden-md hidden-sm"></kbd>');
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
			if ($('kbd.nav-messages-count').length == 0) $('[data-menu-nav="messages"]').find('span').append('<kbd class="nav-messages-count hidden-lg hidden-md hidden-sm"></kbd>');
			if (totalItems) {
				$('kbd.nav-messages-count').removeClass('hide').text(totalItems);
			} else {
				$('kbd.nav-messages-count').addClass('hide').text('');
			}
		break;
	}
	/*tABS*/
	if (totalItems) {
		// console.log(sNav, totalItems);
		$('[data-nav="'+sNav+'"]').find('kbd').removeClass('hide').removeClass('no-count').text(totalItems);
	} else {
		$('[data-nav="'+sNav+'"]').find('kbd').addClass('hide').addClass('no-count').text('');
	}
}

var fetchOrderCycles = function(oData) {
	var oSettings = {
		url: 'support/fetch_order_cycles/',
		type: 'post',
		dataType: 'json',
		data: oData,
		success: function(oResponse) {
			if (oResponse) {
				// console.log(oResponse);
				if (oResponse) {
					if ($.inArray(oResponse.mode, ['basket', 'message']) >= 0) {
						if ($.inArray(oUser.id, oResponse.id) >= 0) {
							sendRequestOrderCycles(oResponse);
						}
					} else {
						if (oSegments[1] == 'fulfillment' && $.inArray(oUser.id, oResponse.seller) >= 0 && $.inArray(oUser.id, oResponse.buyer) >= 0) {
							if ($.inArray(oUser.id, oResponse.seller) >= 0) {
								sendRequestOrderCycles(oResponse, oUser.id, 'seller');
							}
						} else if (oSegments[1] == 'orders' && $.inArray(oUser.id, oResponse.seller) >= 0 && $.inArray(oUser.id, oResponse.buyer) >= 0) {
							if ($.inArray(oUser.id, oResponse.buyer) >= 0) {
								sendRequestOrderCycles(oResponse, oUser.id, 'buyer');
							}
						} else {
							if ($.inArray(oUser.id, oResponse.seller) >= 0) {
								sendRequestOrderCycles(oResponse, oUser.id, 'seller');
							}
							if ($.inArray(oUser.id, oResponse.buyer) >= 0) {
								sendRequestOrderCycles(oResponse, oUser.id, 'buyer');
							}
						}
					}
				}
			}
		}
	};
	$.ajax(oSettings);
}

var sendRequestOrderCycles = function(oData, iUser, type) {
	switch (oData.mode) {
		case 'basket': case 'message':
			for (var x in oData.url) {
				let url = oData.url[x];
				var oSettings = {
					url: url,
					type: 'post',
					dataType: 'json',
					data: oData.params,
					success: function(oResponse) {
						reDrawOrderCycles(oData, oResponse);
					}
				};
				$.ajax(oSettings);
			}
			var oCounts = oData.counts;
			/*just count*/
			if (Object.keys(oCounts).length) {
				for (var y in oCounts) reCountMenuNavs(y, oCounts[y]);
			}
		break;
		default:
			// console.log(type);
			if (oData.counts && oData.counts[type]['user_'+iUser] != undefined) {
				/*just count*/
				var oCounts = oData.counts[type]['user_'+iUser];
				if ($.inArray(oData.mode, ['on-delivery']) >= 0) {
					oCounts = oData.counts['seller']['user_'+iUser];
				}
				for (let x in oData.requests) {
					if (x == type) {
						var oItem = oData.requests[x];
						if (oItem['user_'+iUser] != undefined) {
							var oPages = oItem['user_'+iUser];
							for (name in oPages) {
								let sName = name;
								var oPage = oPages[name];
								if ($.inArray(sName, Object.values(oSegments)) >= 0) {
									// console.log(oPage, x);
									var oSettings = {
										url: oPage.url,
										type: 'post',
										dataType: 'json',
										data: oPage.params,
										success: function(oResponse) {
											reDrawOrderCycles(oData, oResponse, oCounts, sName);
										}
									};
									$.ajax(oSettings);
								}
							}
						}
					}
				}
			}
		break;
	}
}

var reDrawOrderCycles = function(oData, oResponse, oCounts, sPageName) {
	var sMode = oData.mode;
	switch (sMode) {
		case 'basket':
			// console.log(oResponse);
			var uiBasketPanel = $('#dashboard_panel_right [js-element="baskets-panel"]');
			if (oResponse.html.length) {
				var oArr = [];
				if (Object.keys(oData.post.basket_id).length) {
					oArr = oData.post.basket_id;
				} else if (!isNaN(oData.post.basket_id)) {
					oArr = [oData.post.basket_id];
				}
				if (typeof oArr == 'object') {
					var arrRemoved = [];
					for (var x in oArr) {
						var id = oArr[x];
						var item = $('[data-basket-id="'+id+'"]');
						if (item.length) {
							arrRemoved.push(1);
							item.parents('.order-table-item').remove();
						}
					}
					if (uiBasketPanel.find('.no-records-ui:visible').length) {
						uiBasketPanel.html(oResponse.html);
					} else {
						uiBasketPanel.prepend(oResponse.html);
						if (uiBasketPanel.find('.no-records-ui').length > 1) {
							uiBasketPanel.find('.no-records-ui.hide:last').remove();
						}
					}
					if (typeof basketsRunDomReady == 'function') basketsRunDomReady();
				}
			} else {
				uiBasketPanel.find('.no-records-ui').removeClass('hide');
			}
		break;
		case 'message':
			var uiMsgPanel = $('.hideshow-container');
			if (Object.keys(oResponse.html).length) {
				var oArr = [];
				if (Object.keys(oResponse.message_ids).length) {
					oArr = oResponse.message_ids;
				} else if (!isNaN(oResponse.message_ids)) {
					oArr = [oResponse.message_ids];
				}
				if (typeof oArr == 'object') {
					var arrToRemoved = [];
					for (var tab in oResponse.html) {
						var newUI = oResponse.html[tab], uiParent = $('#msg_'+tab);

						var removeMethod = new Promise((resolve, reject) => {
							oArr.forEach((id, index, array) => {
								var item = $('[data-msg-id="'+id+'"]');
								if (item.length) {
									console.log('found!', item);
									item.replaceWith(newUI.find('[data-msg-id="'+id+'"]'));
								} else if (newUI.length) {
									console.log('new ui!', newUI.length);
									uiParent.prepend(newUI);
								} else {
									console.log('not found!', item);
									arrToRemoved.push(true);
								}
								if (index === array.length -1) resolve();
							});
						});

						removeMethod.then(() => {
							if (typeof msgRunDomReady == 'function') msgRunDomReady();
							console.log(arrToRemoved);
							if (arrToRemoved.length == 0) {
								uiParent.find('.no-records-ui').addClass('hide');
							} else {
								uiParent.find('.no-records-ui').removeClass('hide');
							}
							console.log(tab, 'All done!');
						});
					}
				}
			} else {
				uiMsgPanel.find('.no-records-ui').removeClass('hide');
			}
		break;
		default:
			// console.log(oData, oResponse, oCounts, sPageName);
			var uiPanel = [], isActive = null;
			// console.log(sMode);
			switch (sPageName) {
				case 'fulfillment':
					uiPanel = $('.ff-product-container');
					isActive = $('[data-nav="'+sMode+'"]').hasClass('active');
				break;
				case 'orders':
					uiPanel = $('#dashboard_panel_right [js-element="orders-panel"]');
					isActive = $('[data-nav="'+sMode+'"]').find('.trans-navbar-pill').hasClass('active');
				break;
				case 'basket':
					uiPanel = $('#dashboard_panel_right [js-element="baskets-panel"]');
					isActive = sMode != 'placed';
				break;
			}

			if (oResponse.panel === 'messages') {
				uiPanel = $('.hideshow-container');
			}
			// console.log(isActive);
			if (uiPanel.length) {
				if (oResponse.html.length) {
					var oArr = [];
					if (oResponse.panel === 'messages') {
						if (Object.keys(oResponse.message_ids).length) {
							oArr = oResponse.message_ids;
						} else if (!isNaN(oResponse.message_ids)) {
							oArr = [oResponse.message_ids];
						}
					} else if (oResponse.panel === 'basket') {
						if (Object.keys(oResponse.ids).length) {
							oArr = oResponse.ids;
						} else if (!isNaN(oResponse.ids)) {
							oArr = [oResponse.ids];
						}
					} else {
						if (Object.keys(oData.post.merge_id).length) {
							oArr = oData.post.merge_id;
						} else if (!isNaN(oData.post.merge_id)) {
							oArr = [oData.post.merge_id];
						}
					}
					// console.log(oArr);
					if (typeof oArr == 'object') {
						var arrRemoved = [];
						var removeMethod = new Promise((resolve, reject) => {
							oArr.forEach((id, index, array) => {
								if (oResponse.panel === 'messages') {
									var item = $('[data-msg-id="'+id+'"]');
								} else if (oResponse.panel === 'basket') {
									var item = $('[data-basket-id="'+id+'"]').parents('.order-table-item');
								} else {
									var item = $('[data-merge-id="'+id+'"]');
								}
								// console.log(item, index);
								if (item.length) {
									arrRemoved.push(1);
									item.remove();
								}
								if (index === array.length -1) resolve();
							});
						});

						removeMethod.then(() => {
							// console.log(isActive);
							if (isActive == true) {
								if (uiPanel.find('.no-records-ui:visible').length) {
									if (oResponse.panel == 'messages' || sPageName == 'fulfillment') {
										uiPanel.replaceWith(oResponse.html);
									} else {
										uiPanel.html(oResponse.html);
									}
									console.log('rendered:', sMode);
								} else {
									if (sPageName == 'fulfillment') {
										var uiOrderItem = $(oResponse.html).find('[js-element="fulfill-panel"]').find('.order-table-item');
										uiPanel.find('[js-element="fulfill-panel"]').prepend(uiOrderItem);
									} else if (oResponse.panel === 'messages') {
										uiPanel.replaceWith(oResponse.html);
									} else {
										uiPanel.prepend(oResponse.html);
									}
									if (uiPanel.find('.no-records-ui').length > 1 && oResponse.panel != 'messages') {
										uiPanel.find('.no-records-ui.hide:last').remove();
									}
									console.log('re-rendered:', sMode);
								}
								if (typeof runATagAjax == 'function') runATagAjax();
								if (typeof runDomShowHide == 'function') runDomShowHide();
								switch (sPageName) {
									case 'fulfillment':
										if (typeof fulfillmentsRunDomReady == 'function') fulfillmentsRunDomReady();
									break;
									case 'orders':
										if (typeof ordersRunDomReady == 'function') ordersRunDomReady();
									break;
									case 'basket':
										if (typeof basketsRunDomReady == 'function') basketsRunDomReady();
									break;
								}
							} else {
								console.log('not in page:', sMode);
								if (uiPanel.find('.no-records-ui').siblings().length == 0 || sMode == 'cancelled') {
									uiPanel.find('.no-records-ui').removeClass('hide');
								}
							}
							console.log('All done!');
						});
					} else {
						uiPanel.find('.no-records-ui').removeClass('hide');
					}
				} else {
					uiPanel.find('.no-records-ui').removeClass('hide');
				}
			}
		break;
	}
	if (oCounts != undefined && Object.keys(oCounts).length) {
		for (var y in oCounts) reCountMenuNavs(y, oCounts[y]);
	}
}

var runATagAjax = function () {
	var oPauseAjax = false;
	$(document.body).find('a[data-ajax]').off('click').on('click', function(e) {
		e.preventDefault();
		var oData = $(e.target).data('json') != undefined ? $(e.target).data('json') : {};
		var isJsonPCallback = $(e.target).data('call-jsonp') != undefined ? $(e.target).data('call-jsonp') : 1;
		var oSettings = {
			url: $(e.target).data('href') == undefined ? e.target.href : $(e.target).data('href'),
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
}