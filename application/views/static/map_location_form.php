<form action="<?php echo $url;?>" method="post" data-ajax="1" data-disable="enter" class="form-validate" id="shipping-form">
	<div class="dashboard-panel-middle">
		<?php if ($this->action != 'storefront'): ?>
			<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
		<?php endif ?>
		<input type="hidden" name="lat" id="lat" value="" required="required">
		<input type="hidden" name="lng" id="lng" value="" required="required">
		<div class="dash-panel-middle">
			<div class="input-group">
				<span class="input-group-addon"><span class="hidden-xs"><b>Step 1</b></span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Step 1. Search your barangay name."></i></span>
				<input type="text" class="form-control" id="search-place" placeholder="Search your barangay or a landmark near you.">
				<span class="input-group-btn">
					<button class="btn btn-default normal-radius" id="undo-btn" type="button"><i class="fa fa-undo"></i></button>
				</span>
			</div>
			<p style="padding:0 0 0 13px;margin-bottom:15px;"><b class="hidden-xs">Step 2</b><i class="fa fa-question-circle hidden-lg hidden-md hidden-sm" data-toggle="tooltip" data-placement="right" title="Step 2."></i> Drag and point the map marker <i class="fa fa-map-marker text-danger"></i> to your house.</p>
			<div id="map-box" style="width: 100%; height: 250px; margin-bottom: 15px;"></div>
			<div class="input-group">
				<span class="input-group-addon"><span><b class="hidden-xs">Step 3</b><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Step 2. Enter your House number Street Barangay City (Complete shipping address)"></i></span></span>
				<input type="text" class="form-control" name="address_1" id="address_1" placeholder="Enter your complete shipping address." required="required">
			</div>
			<div class="form-group zero-gaps">
				<input type="text" readonly="readonly" name="address_2" id="address_2" class="form-control" placeholder="Go to Step 1." required="required">
			</div>
		</div>
	</div>

	<div class="dashboard-panel-footer text-right">
		<?php if ((isset($current_profile['profile']) AND $current_profile['profile']) AND $this->action != 'storefront'): ?>
			<button type="button" class="btn normal-radius" id="reset-to-prev-btn">New</button>
		<?php endif ?>
		<button type="submit" class="btn btn-contrast icon-right" id="map-submit-btn"><?php if ($this->action != 'storefront'): ?>Save Address<?php else: ?>Set Address<?php endif ?></button>
	</div>
</form>