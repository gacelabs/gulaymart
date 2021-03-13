<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="mobile-note visible-xs">
			<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
		</div>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<form action="" method="post">
					<div class="dash-panel theme">
						<ul class="spaced-list between dash-panel-top">
							<li><h3>What's your name?</h3></li>
						</ul>
						<div class="dash-panel-middle">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="first_name" class="form-control" placeholder="First name">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="last_name" class="form-control" placeholder="Last name">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h5>Birthday</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<select name="month" class="form-control" placeholder="First name">
											<option>January</option>
											<option>February</option>
										</select>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="number" name="birth_year" class="form-control" placeholder="Birth Year">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<h5>Registered email</h5>
									<div class="form-group">
										<input type="text" class="form-control" placeholder="email@domain.com" disabled>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<h5>Cellphone</h5>
									<div class="form-group">
										<input type="number" class="form-control" placeholder="09xx-xxx-xxxx">
									</div>
								</div>
							</div>
						</div>
						<div class="dash-panel-footer text-right">
							<button class="btn btn-theme normal-radius">Submit</button>
						</div>
					</div>
				</form>

				<form action="" method="post">
					<div class="dash-panel theme">
						<ul class="spaced-list between dash-panel-top">
							<li><h3>Notify me with</h3></li>
						</ul>
						<div class="dash-panel-middle">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<ul class="spaced-list between">
										<li><p>Order status</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_email" checked>
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
									<ul class="spaced-list between">
										<li><p>Messages</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_cp" checked>
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="dash-panel-footer text-right">
							<button class="btn btn-theme normal-radius">Save settings</button>
						</div>
					</div>
				</form>
			</div>

			<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
				<form action="api/save_shipping" method="post" data-ajax="1" class="form-validate">
					<div class="dash-panel theme">
						<ul class="spaced-list between dash-panel-top">
							<li><h3>Shipping address</h3></li>
						</ul>
						<div class="dash-panel-middle">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="input-group">
										<input type="text" class="form-control" id="search-place" placeholder="Search location">
										<span class="input-group-btn">
											<button class="btn btn-default normal-radius" id="search-place-btn" type="button"><i class="fa fa-search"></i></button>
										</span>
									</div>
								</div>
								<!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<select name="barangay" class="form-control" placeholder="Select Barangay">
											<option>Barangay 1</option>
											<option>Barangay 2</option>
										</select>
									</div>
								</div> -->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">
									<div id="map-box" style="width: 100%; height: 220px; margin-bottom: 15px;"></div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
									<?php if (isset($current_profile['profile']) AND $current_profile['profile']): ?>
										<input type="hidden" name="id" value="<?php echo $current_profile['profile']['id'];?>">
										<input type="hidden" name="latlng" id="latlng" value='<?php echo $current_profile['profile']['latlng'];?>' required="required">
										<div class="form-group">
											<input type="text" class="form-control" name="address_1" id="address_1" placeholder="House #, Street name, Village..." required="required" value="<?php echo $current_profile['profile']['address_1'];?>">
										</div>
										<div class="form-group">
											<input type="text" readonly="readonly" name="address_2" id="address_2" class="form-control" placeholder="Barangay, City name, Country..." required="required" value="<?php echo $current_profile['profile']['address_2'];?>">
										</div>
									<?php else: ?>
										<input type="hidden" name="latlng" id="latlng" value="" required="required">
										<div class="form-group">
											<input type="text" class="form-control" name="address_1" id="address_1" placeholder="House #, Street name, Village..." required="required">
										</div>
										<div class="form-group">
											<input type="text" readonly="readonly" name="address_2" id="address_2" class="form-control" placeholder="Barangay, City name, Country..." required="required">
										</div>
									<?php endif ?>
								</div>
							</div>
						</div>
						<div class="dash-panel-footer">
							<?php if (isset($current_profile['profile']) AND $current_profile['profile']): ?>
								<button type="button" class="btn btn-danger normal-radius" id="reset-to-prev-btn">Reset to Previous</button>
							<?php endif ?>
							<button type="submit" class="btn btn-theme normal-radius pull-right">Save Address</button>
						</div>
					</div>
				</form>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				Be a farmer banner here!
			</div>
		</div>
	</div>
</div>