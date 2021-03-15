<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<?php $this->view('static/mobile_note'); ?>
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
										<input type="email" class="form-control copy" placeholder="<?php echo $current_profile['email_address'];?>" disabled>
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
									<div class="form-group email-copy" data-toggle="tooltip" data-placement="top" data-trigger="click" title="" data-original-title="Copied!">
										<input type="email" class="form-control" placeholder="<?php echo $current_profile['email_address'];?>" disabled>
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
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php if (isset($current_profile['settings']) AND $current_profile['settings']): ?>
									<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
									<ul class="spaced-list between">
										<li><p>Order status</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_email" <?php isset_echo($current_profile['settings'], 'notif_email');?>>
												<span class="slider round"></span>
											</label>
										</li>
									</ul>
									<ul class="spaced-list between">
										<li><p>Messages</p></li>
										<li>
											<label class="switch">
												<input type="checkbox" name="notif_cp" <?php isset_echo($current_profile['settings'], 'notif_cp');?>>
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
				<div class="dash-panel theme">
					<ul class="spaced-list between dash-panel-top">
						<li><h3>Shipping address</h3></li>
					</ul>
					<div class="dash-panel-middle">
						<div class="saved-shipping-container">
							<?php if (isset($current_profile['shippings']) AND $current_profile['shippings']): ?>
								<?php foreach ($current_profile['shippings'] as $key => $shipping): ?>
									<div class="saved-shipping-item" id="shipping-item-<?php echo $shipping['id'];?>">
										<div>
											<p class="zero-gaps address_1"><?php echo $shipping['address_1'];?></p>
											<p class="zero-gaps"><small class="address_2"><?php echo $shipping['address_2'];?></small></p>
											<p class="zero-gaps"><small><a href="javascript:;" class="edit-shp-btn" data-json='<?php echo json_encode($shipping);?>'>Edit</a></small></p>
										</div>	
										<div class="text-center">
											<p class="zero-gaps">Primary</p>
											<label class="switch">
												<input type="radio" data-ajax="1" name="active"<?php str_has_value_echo('1', $shipping['active'], ' checked');?> data-url="api/save_active_shipping" data-json='<?php echo json_encode(['id' => $shipping['id']]);?>'>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								<?php endforeach ?>
							<?php else: ?>
								<p>No Shipping Address yet. </p>
							<?php endif; ?>
						</div>
					</div>
					<form action="api/save_shipping" method="post" data-ajax="1" class="form-validate" id="shipping-form">
						<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
						<input type="hidden" name="lat" id="lat" value="" required="required">
						<input type="hidden" name="lng" id="lng" value="" required="required">
						<div class="dash-panel-middle">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="input-group">
										<input type="text" class="form-control" id="search-place" placeholder="Search your barangay, subdivision or village...">
										<span class="input-group-btn">
											<button class="btn btn-default normal-radius" id="undo-btn" type="button"><i class="fa fa-undo"></i></button>
										</span>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 15px;">
									<div id="map-box" style="width: 100%; height: 220px; margin-bottom: 15px;"></div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<input type="text" class="form-control" name="address_1" id="address_1" placeholder="House #, Street name, Village..." required="required">
									</div>
									<div class="form-group">
										<input type="text" readonly="readonly" name="address_2" id="address_2" class="form-control" placeholder="Barangay, City name, Country..." required="required">
									</div>
								</div>
							</div>
						</div>
						<div class="dash-panel-footer text-right">
							<?php if (isset($current_profile['profile']) AND $current_profile['profile']): ?>
								<button type="button" class="btn normal-radius" id="reset-to-prev-btn">Reset</button>
							<?php endif ?>
							<button type="submit" class="btn btn-theme normal-radius" id="map-submit-btn">Save Address</button>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				Be a farmer banner here!
			</div>
		</div>
	</div>
</div>