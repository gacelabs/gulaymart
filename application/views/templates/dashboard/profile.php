<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="mobile-note visible-xs">
			<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
		</div>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
				<form action="api/save_info" method="post" data-ajax="1" class="form-validate">
					<div class="dash-panel theme">
						<ul class="spaced-list between dash-panel-top">
							<li><h3>What's your name?</h3></li>
						</ul>
						<div class="dash-panel-middle">
						<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
						<?php if (isset($current_profile['profile']) AND $current_profile['profile']): ?>
							<input type="hidden" name="id" value="<?php echo $current_profile['profile']['id'];?>">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="firstname" class="form-control" placeholder="First name" required="required" value="<?php echo $current_profile['profile']['firstname'];?>">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="lastname" class="form-control" placeholder="Last name" value="<?php echo $current_profile['profile']['lastname'];?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h5>Birthday</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<?php $months = unserialize(MONTHS);?>
										<select name="birth_month" class="form-control" placeholder="Birth Month" required="required">
											<?php foreach ($months as $id => $month): ?>
												<option value="<?php echo $id;?>"<?php in_array_echo($current_profile['profile']['birth_month'], [$id], ' selected');?>><?php echo $month;?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="birth_year" class="form-control" data-inputmask="'mask': '9999'" placeholder="Birth Year" required="required" value="<?php echo $current_profile['profile']['birth_year'];?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<h5>Registered email</h5>
									<div class="form-group email-copy" data-toggle="tooltip" data-placement="top" data-trigger="click" title="" data-original-title="Copied!">
										<input type="text" class="form-control copy" placeholder="<?php echo $current_profile['email_address'];?>" disabled>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<h5>Cellphone</h5>
									<div class="form-group">
										<input type="text" class="form-control" name="phone" data-inputmask="'mask': '0999-999-9999'" placeholder="09xx-xxx-xxxx" required="required" value="<?php echo $current_profile['profile']['phone'];?>">
									</div>
								</div>
							</div>
						<?php else: ?>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="firstname" class="form-control" placeholder="First name" required="required">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="lastname" class="form-control" placeholder="Last name">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<h5>Birthday</h5>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<?php $months = unserialize(MONTHS);?>
										<select name="birth_month" class="form-control" placeholder="Birth Month" required="required">
											<?php foreach ($months as $id => $month): ?>
												<option value="<?php echo $id;?>"><?php echo $month;?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="text" name="birth_year" class="form-control" data-inputmask="'mask': '9999'" placeholder="Birth Year" required="required">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<h5>Registered email</h5>
									<div class="form-group">
										<input type="text" class="form-control" placeholder="<?php echo $current_profile['email_address'];?>" disabled>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<h5>Cellphone</h5>
									<div class="form-group">
										<input type="text" class="form-control" name="phone" data-inputmask="'mask': '0999-999-9999'" placeholder="09xx-xxx-xxxx" required="required">
									</div>
								</div>
							</div>
						<?php endif ?>
						</div>
						<div class="dash-panel-footer text-right">
							<button type="submit" class="btn btn-theme normal-radius">Submit</button>
						</div>
					</div>
				</form>

				<form action="api/save_notif" method="post" data-ajax="2" class="form-validate">
					<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
					<div class="dash-panel theme">
						<ul class="spaced-list between dash-panel-top">
							<li><h3>Notify me with</h3></li>
						</ul>
						<div class="dash-panel-middle">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<?php if (isset($current_profile['settings']) AND $current_profile['settings']): ?>
									<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
									<ul class="spaced-list between">
										<li><p>Order status</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_email" <?php is_set_echo($current_profile['settings'], 'notif_email');?>>
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
									<ul class="spaced-list between">
										<li><p>Messages</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_cp" <?php is_set_echo($current_profile['settings'], 'notif_cp');?>>
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
								<?php else: ?>
									<ul class="spaced-list between">
										<li><p>Order status</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_email">
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
									<ul class="spaced-list between">
										<li><p>Messages</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_cp">
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
								<?php endif ?>
								</div>
							</div>
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
										<input type="text" class="form-control" id="search-place" placeholder="Search your city">
										<span class="input-group-btn">
											<button class="btn btn-default normal-radius" id="undo-btn" type="button"><i class="fa fa-undo"></i></button>
										</span>
									</div>
								</div>
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
						<div class="dash-panel-footer text-right">
							<?php if (isset($current_profile['profile']) AND $current_profile['profile']): ?>
								<button type="button" class="btn btn-danger normal-radius" id="reset-to-prev-btn">Reset</button>
								<button type="submit" class="btn btn-theme normal-radius pull-right">Save Address</button>
							<?php else: ?>
								<button type="submit" class="btn btn-theme normal-radius">Save Address</button>
							<?php endif ?>
<<<<<<< HEAD:application/views/templates/dashboard/profile.php
							<button type="submit" class="btn btn-theme normal-radius">Save Address</button>
=======
>>>>>>> 37752fa3674c3cd57a0da0c15f48f6f75a4a5b04:application/views/templates/dashboard/panel_right.php
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