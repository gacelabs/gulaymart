
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/validate-form.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/toast.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
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
					// responsive: true,
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
</script>