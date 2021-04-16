<div class="modal fade" id="check_loc_modal" tabindex="-1" role="dialog" aria-labelledby="check_loc_modalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Check Location</h4>
			</div>
			<div class="modal-body">
				<p class="zero-gaps">As part of Gulaymart's core values to <b>support local farmers</b>, we urge our everyone to filter the products based on their residing address. This way, we can be sure that the products shown are produced by the farmers within your local community.</p>
				<img src="assets/images/banner/check-location.png" class="img-responsive" style="margin: 20px 0;">
				<p>Please search the <b class="text-contrast">city</b> where you're living in.</p>
				<form action="" method="post">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1"><i class="fa fa-map-marker text-danger"></i></span>
						<input type="text" class="form-control" placeholder="Search your city..." aria-describedby="basic-addon1">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		setTimeout(function() {
			$('#check_loc_modal').modal('show');
		},1000);
	});
</script>