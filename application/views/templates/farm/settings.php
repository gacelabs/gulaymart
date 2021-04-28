<?php /*debug($data, 'stop');*/ ?>
<div id="dashboard_panel_right">
	<?php $this->view('global/mobile_note'); ?>

	<div class="row hidden-xs">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme">
				<div class="dashboard-panel-top">
					<ul class="spaced-list between">
						<li><h4 class="zero-gaps">Farm Settings</h4></li>
					</ul>
				</div>
				<div class="dashboard-panel-middle">
					<div class="settings-item">
						<small class="elem-block"><b tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="right" title="Farm Status" data-content="We understand that sometimes you need a holiday, by setting your farm (location based) to Vacation, all your products will be tagged as Out Of Stock. While Inactive will hide all your farm (location based) and its products.">FARM STATUS <i class="fa fa-question-circle text-gray"></i></b></small>

						<div class="settings-grid">
							<div class="settings-inner-grid">
								<div class="text-center">
									<i class="fa fa-map-marker text-theme"></i>
								</div>
								<div>
									<p class="zero-gaps">Farm Location 1</p>
									<small class="elem-block"><b>Active</b></small>
								</div>
							</div>
							<div class="input-group">
								<select class="form-control">
									<option>Active</option>
									<option>Vacation</option>
									<option>Inactive</option>
								</select>
								<span class="input-group-btn">
									<button class="btn btn-contrast" type="button">Save</button>
								</span>
							</div>
						</div>
					</div>

					<hr>

					<div class="settings-item">
						<small class="elem-block"><b tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="right" title="Farm Contact Details" data-content="If you have employee/s who takes care of the farm other than you, please save their contact information below. It will be used as the contact person for product pick up purposes.">FARM CONTACT DETAILS <i class="fa fa-question-circle text-gray"></i></b></small>

						<div class="settings-grid">
							<div class="settings-inner-grid">
								<div class="text-center">
									<i class="fa fa-map-marker text-theme"></i>
								</div>
								<div>
									<p class="zero-gaps">Farm Location 1</p>
									<small class="elem-block"><b>Marcus Garcia</b></small>
									<small class="elem-block"><b>0917-123-4567</b></small>
								</div>
							</div>
							<div>
								<div class="form-group zero-gaps">
									<input type="text" class="form-control" placeholder="Complete Name" style="border-bottom:none;">
								</div>
								<div class="input-group">
									<input type="number" class="form-control" placeholder="0919-xxx-xxxx">
									<span class="input-group-btn">
										<button class="btn btn-contrast" type="button">Save</button>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme">
				<div class="dashboard-panel-top">
					<ul class="spaced-list between">
						<li><h4 class="zero-gaps">Product Settings</h4></li>
					</ul>
				</div>
				<div class="dashboard-panel-middle">
					
				</div>
			</div>
		</div>
	</div>
</div>