
<form action="api/save_shipping" method="post" data-ajax="1" data-disable="enter" class="form-validate" id="shipping-form">
	<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
	<input type="hidden" name="lat" id="lat" value="" required="required">
	<input type="hidden" name="lng" id="lng" value="" required="required">
	<div class="dash-panel-middle">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="input-group">
					<span class="input-group-addon"><span class="hidden-xs"><b>Step 1</b></span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Step 1. Search your barangay name."></i></span>
					<input type="text" class="form-control" id="search-place" placeholder="Search your barangay or a landmark near you.">
					<span class="input-group-btn">
						<button class="btn btn-default normal-radius" id="undo-btn" type="button"><i class="fa fa-undo"></i></button>
					</span>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">
				<p><b>Step 2</b> Drag and point the map marker <i class="fa fa-map-marker"></i> to your house.</p>
				<div id="map-box" style="width: 100%; height: 250px; margin-bottom: 15px;"></div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="input-group">
					<span class="input-group-addon"><span class="hidden-xs"><b>Step 3</b><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Step 2. Enter your complete shipping address."></i></span></span>
					<input type="text" class="form-control" name="address_1" id="address_1" placeholder="Enter your complete shipping address." required="required">
				</div>
				<div class="form-group">
					<input type="text" readonly="readonly" name="address_2" id="address_2" class="form-control" placeholder="Go to Step 1." required="required">
				</div>
			</div>
		</div>
	</div>
	<div class="dash-panel-footer text-right">
		<?php if (isset($current_profile['profile']) AND $current_profile['profile']): ?>
			<button type="button" class="btn normal-radius" id="reset-to-prev-btn">New Address</button>
		<?php endif ?>
		<button type="submit" class="btn btn-theme normal-radius" id="map-submit-btn">Save Address</button>
	</div>
</form>