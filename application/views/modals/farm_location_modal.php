<div class="modal fade" id="farm_location_modal" tabindex="-1" role="dialog" aria-labelledby="farm_location_modalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" style="margin-bottom: 15px;">Farm Location</h4>
				<p class="zero-gaps">Saving your farm's <b>exact location address</b> is important. It will be used as the <b>pick up point</b> of your listed products. Wrong address may result to <b class="text-danger">over charged</b> delivery fee.</p>
			</div>
			<div class="modal-body">
				<div class="input-group">
					<span class="input-group-addon"><span class="hidden-xs">Step 1</span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Step 1. Search your barangay name."></i></span>
					<input type="text" class="form-control" id="search-place" placeholder="Search your barangay, subdivision or village...">
					<span class="input-group-btn">
						<button class="btn btn-default normal-radius" id="undo-btn" type="button"><i class="fa fa-undo"></i></button>
					</span>
				</div>

				<div id="map-box" style="width: 100%; height: 220px; margin-bottom: 15px;"></div>

				<div class="input-group">
					<span class="input-group-addon"><span class="hidden-xs">Step 2<i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Step 2. Enter your complete shipping address."></i></span></span>
					<input type="text" class="form-control" name="address_1" id="address_1" placeholder="Enter your complete shipping address." required="required">
				</div>
				<div class="form-group">
					<input type="text" readonly="readonly" name="address_2" id="address_2" class="form-control" placeholder="Search you barangay from Step 1." required="required">
				</div>
				
			</div>
			<div class="modal-footer">
				<button class="btn btn-default normal-radius" id="map-submit-btn">Apply</button>
			</div>
		</div>
	</div>
</div>