$(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();

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
		var uiButtonSubmit = $(e.target).find('[type=submit]');
		if (uiButtonSubmit.length && $(e.target).find('.error').length == 0) {
			uiButtonSubmit.attr('disabled', 'disabled').html('<span class="spinner-border spinner-border-sm"></span> Processing ...');
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
		var oSettings = {
			url: e.target.href,
			type: 'get',
			data: oData,
			dataType: 'jsonp',
			jsonpCallback: 'gmCall',
			error: function(xhr, status, thrown) {
				console.log(status, thrown);
			}
		};
		if (oPauseAjax != false && oPauseAjax.readyState !== 4) oPauseAjax.abort();
		oPauseAjax = $.ajax(oSettings);
	});

	if (oUser && oUser.is_profile_complete == 0) {
		runAlertBox({type:'info', message: PROFILE_INFO_MESSAGE});
	} else {
		var sSuccessMessage = getParameterByName('success');
		var sInfoMessage = getParameterByName('info');
		var sWarnMessage = getParameterByName('warn');
		var sConfirmMessage = getParameterByName('confirm');
		var sErrorMessage = getParameterByName('error');
		if ($.trim(sSuccessMessage).length) {
			runAlertBox({type:'success', message: sSuccessMessage});
		} else if ($.trim(sInfoMessage).length) {
			runAlertBox({type:'info', message: sInfoMessage});
		} else if ($.trim(sWarnMessage).length) {
			runAlertBox({type:'warn', message: sWarnMessage});
		} else if ($.trim(sConfirmMessage).length) {
			runAlertBox({type:'confirm', message: sConfirmMessage});
		} else if ($.trim(sErrorMessage).length) {
			runAlertBox({type:'error', message: sErrorMessage});
		}
	}
	
	$('#main-obj-script').remove();

	if ($('.render-datatable').length) {
		$(document.body).find('table').each(function(i, elem) {
			if ($.fn.dataTable != undefined) $.fn.dataTable.ext.errMode = 'none';
			var text = $.trim($(elem).find('tr:first th:first').text());
			// console.log(text);
			var blanks = {targets: []}, currency = {targets: []};
			if ($.inArray(text.toLowerCase(), ['action','activity']) >= 0) {
				if (blanks.orderable == undefined) blanks.orderable = false;
				blanks.targets.push(i);
			}
			var text2 = $.trim($(elem).find('tr:first th:last').text());
			if ($.inArray(text2.toLowerCase(), ['photo','image','']) >= 0) {
				var last_cnt = $(elem).find('tr:first th').length - 1;
				blanks.targets.push(last_cnt);
			}
			var text3 = $.trim($(elem).find('tr:first th:last').text()), aaSort = 1, aaSortDir = 'asc';
			if ($.inArray(text3.toLowerCase(), ['updated']) >= 0) {
				aaSort = $(elem).find('tr:first th').length - 1;
				aaSortDir = 'desc';
			}
			// console.log(blanks, last_cnt);
			oSettings = {
				// stateSave: true,
				responsive: true,
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
});