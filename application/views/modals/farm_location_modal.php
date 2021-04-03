<div class="modal fade" id="farm_location_modal" tabindex="-1" role="dialog" aria-labelledby="farm_location_modalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="margin-bottom: 15px;">Farm Location</h4>
				<p class="zero-gaps">Saving your farm's <b>exact location address</b> is important. It will be used as the <b>pick up point</b> of your listed products. Wrong address may result to <b class="text-danger">over charged</b> delivery fee.</p>
			</div>
			<div class="modal-body">
				<?php $this->view('static/map_location_form'); ?>
			</div>
		</div>
	</div>
</div>