<div id="dashboard_panel_right">
	<div class="row">

		<?php if (isset($current_profile['shippings']) AND $current_profile['shippings']): ?>
		<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
			<?php if (empty($this->farms)) : ?>
				<h4>Hoorah! You're all set. <br class="visible-xs"><br class="visible-xs">Selling veggies in Gulaymart is easy, be a <a href="farm/storefront/" class="text-link">veggie seller now!</a></h4>
			<?php endif ?>
			
			<?php if (empty($data['baskets'])): ?>
				<h4>Enjoy shopping fresh veggies at <a href="marketplace/" class="text-link">Marketplace</a></h4>
			<?php endif ?>
			<hr class="carved">
		</div>
		<?php elseif ($this->farms AND $data['baskets']) : ?>
		<?php endif ?>

		<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">

			<?php if ($current_profile['profile']) : ?>
			<div class="dashboard-panel">
				<div class="dashboard-panel-middle">
					<ul class="spaced-list between panel-inner-header">
						<li><small><b>ACCOUNT INFORMATION <i class="fa fa-question-circle text-gray" data-toggle="tooltip" data-placement="right" title="Will also be used for deliveries."></i></b></small></li>
						<li><small class="text-contrast" js-target="profile_info_panel">EDIT</small></li>
					</ul>
					<div class="profile-grid">
						<div class="text-center"><i class="text-theme fa fa-id-badge"></i></div>
						<div class="text-left"><p class="zero-gaps"><?php echo $current_profile['fullname']; ?></p></div>
					</div>
					<div class="profile-grid">
						<div class="text-center"><i class="text-theme fa fa-calendar"></i></div>
						<div class="text-left">
							<p class="zero-gaps">
								<?php
									$month_name = date("F", mktime(0, 0, 0, $current_profile['profile']['birth_month']+1, 10));
									echo ucfirst($month_name)." ".$current_profile['profile']['birth_year'];
								?>
							</p>
						</div>
					</div>
					<div class="profile-grid">
						<div class="text-center"><i class="text-theme fa fa-phone"></i></div>
						<div class="text-left">
							<p class="zero-gaps"><?php echo $current_profile['profile']['phone']; ?></p>
						</div>
					</div>
					<div class="profile-grid">
						<div class="text-center"><i class="text-theme fa fa-envelope-o"></i></div>
						<div class="text-left">
							<p class="zero-gaps"><?php echo $current_profile['email_address']; ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="dashboard-panel theme <?php echo(empty($current_profile['profile']['phone']) ? "" : "hide"); ?>" id="profile_info_panel">
				<ul class="spaced-list between dashboard-panel-top">
					<li><h4 class="zero-gaps">What's your name?</h4></li>
					<li><small><a href="profile/" class="text-link">EXIT</a></small></li>
				</ul>
				<form action="api/save_info" method="post" data-ajax="1" class="form-validate">
					<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
					<div class="dashboard-panel-middle">
						<?php if ($current_profile['profile']): ?>
							<input type="hidden" name="id" value="<?php echo $current_profile['profile']['id'];?>">
						<?php endif ?>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="text" name="firstname" class="form-control" placeholder="First name" required="required" value="<?php check_value('firstname', [], true);?>">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="text" name="lastname" class="form-control" placeholder="Last name" value="<?php check_value('lastname', [], true);?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label>Birthday <small class="fa fa-question-circle text-gray" data-toggle="tooltip" data-placement="right" title="Your birth month and year will be used to curate contents."></small></label>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<?php $months = unserialize(MONTHS);?>
									<select name="birth_month" class="form-control" placeholder="Birth Month" required="required">
										<?php foreach ($months as $id => $month): ?>
											<?php if ($current_profile['profile']): ?>
												<option value="<?php echo $id;?>"<?php in_array_echo($current_profile['profile']['birth_month'], [$id], ' selected');?>><?php echo $month;?></option>
											<?php else: ?>
												<option value="<?php echo $id;?>"><?php echo $month;?></option>
											<?php endif ?>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<?php if ($current_profile['profile']): ?>
										<input type="text" name="birth_year" class="form-control" data-inputmask="'mask': '9999'" placeholder="Birth Year" required="required" value="<?php echo $current_profile['profile']['birth_year'];?>">
									<?php else: ?>
										<input type="text" name="birth_year" class="form-control" data-inputmask="'mask': '9999'" placeholder="Birth Year" required="required" />
									<?php endif ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label>Registered email</label>
								<div class="form-group email-copy" data-toggle="tooltip" data-placement="right" data-trigger="click" title="Copied!">
									<input type="email" class="form-control copy" placeholder="<?php check_value('email_address', [], true);?>" disabled>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<label>Cellphone <small class="fa fa-question-circle text-gray" data-toggle="tooltip" data-placement="right" title="A valid contact number for deliveries."></small></label>
								<div class="form-group zero-gaps">
									<?php if ($current_profile['profile']): ?>
										<input type="text" class="form-control" name="phone" data-inputmask="'mask': '0999-999-9999'" placeholder="09xx-xxx-xxxx" required="required" value="<?php echo $current_profile['profile']['phone'];?>">
									<?php else: ?>
										<input type="text" class="form-control" name="phone" data-inputmask="'mask': '0999-999-9999'" placeholder="09xx-xxx-xxxx" required="required" />
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>
					<div class="dashboard-panel-footer text-right">
						<button type="submit" class="btn btn-contrast">Update</button>
					</div>
				</form>
			</div>

			<?php if (isset($current_profile['shippings']) AND $current_profile['shippings']) : ?>
			<div class="dashboard-panel theme">
				<ul class="spaced-list between dashboard-panel-top">
					<li><h4 class="zero-gaps">Notify me with</h4></li>
				</ul>
				<div class="dashboard-panel-middle">
					<form action="api/save_notif" method="post" data-ajax="2" class="form-validate">
						<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
						<?php if (isset($current_profile['settings']) AND $current_profile['settings']): ?>
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
					</form>
				</div>
			</div>
			<?php endif; ?>

			<div class="dashboard-panel theme">
				<ul class="spaced-list between dashboard-panel-top">
					<li><h4 class="zero-gaps">Toktok Operator Details</h4></li>
				</ul>
				<div class="dashboard-panel-middle">
					<form action="api/save_toktok" method="post" data-ajax="2" class="form-validate" id="form_toktok">
						<input type="hidden" name="user_id" value="<?php echo $current_profile['id'];?>">
						<?php if (isset($current_profile['operator']) AND $current_profile['operator']): ?>
							<input type="hidden" name="id" value="<?php echo $current_profile['operator']['id'];?>">
							<ul class="spaced-list between">
								<li>
									<div class="input-group">
										<span class="input-group-btn">
											<label class="btn" for="referral_code">Referral code</label>
										</span>
										<input type="text" class="form-control" id="referral_code" name="referral_code"<?php echo $current_profile['operator']['active'] ? ' required': ' readonly';?> value="<?php isset_echo($current_profile['operator'], 'referral_code');?>" />
									</div>
								</li>
								<li>
									<label class="switch">
										<input type="checkbox" id="toktok-active" name="active"<?php echo $current_profile['operator']['active'] ? ' checked': '';?> value="1" />
										<span class="slider round"></span>
									</label>
								</li>
							</ul>
							<hr>
							<div class="row" js-element="rider-template">
								<div class="col-lg-6">
									<input type="text" class="form-control" name="rider[0][name]" placeholder="Rider Name"<?php echo $current_profile['operator']['active'] ? ' required': ' readonly';?>>
								</div>
								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" class="form-control" name="rider[0][mobile]" placeholder="Mobile Number"<?php echo $current_profile['operator']['active'] ? ' required': ' readonly';?>>
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-plus"></i></button>
										</span>
									</div>
								</div>
							</div>
						<?php else: ?>
							<ul class="spaced-list between">
								<li>
									<input type="text" class="form-control" name="referral_code" readonly="readonly" required="required" placeholder="Enter Referral Code" >
								</li>
								<li>
									<label class="switch">
										<input type="checkbox" id="toktok-active" name="active" value="1" />
										<span class="slider round"></span>
									</label>
								</li>
							</ul>
							<hr>
							<div class="row" js-element="rider-template">
								<div class="col-lg-6">
									<input type="text" class="form-control" name="rider[0][name]" readonly placeholder="Rider Name">
								</div>
								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" class="form-control" name="rider[0][mobile]" readonly placeholder="Mobile Number">
										<span class="input-group-btn">
											<button class="btn btn-default" readonly type="button"><i class="fa fa-plus"></i></button>
										</span>
									</div>
								</div>
							</div>
						<?php endif ?>
					</form>
				</div>
			</div>
		</div>

		<?php if (isset($current_profile['profile']['phone'])) : ?>
		<div class="col-lg-7 col-md-6 col-sm-12 col-xs-12 shipping-address-panel">

			<?php if ($current_profile['shippings']) : ?>
			<div class="dashboard-panel">
				<div class="dashboard-panel-middle">
					<ul class="spaced-list between panel-inner-header">
						<li><small><b>SHIPPING INFORMATION</b></small></li>
						<li><small class="text-contrast" js-target="shipping_address_panel">EDIT/ ADD</small></li>
					</ul>
					<?php foreach ($current_profile['shippings'] as $key => $shipping): ?>
					<div class="profile-grid">
						<div class="text-center"><i class="text-theme fa fa-map-marker"></i></div>
						<div class="text-left" style="padding-top: 0;">
							<p class="zero-gaps"><?php echo ucwords($shipping['address_1']);?></p>
							<small class="elem-block address_2"><?php echo $shipping['address_2'];?></small>
							<small class="elem-block text-gray pull-right"><?php str_has_value_echo('1', $shipping['active'], ' PRIMARY');?></small>
						</div>
					</div>
					<?php endforeach ?>
				</div>
			</div>
			<?php endif; ?>

			<div class="dashboard-panel theme <?php echo(empty($current_profile['shippings']) ? "" : "hide"); ?>" id="shipping_address_panel">
				<ul class="spaced-list between dashboard-panel-top">
					<li><h4 class="zero-gaps">Delivery address</h4></li>
					<li><small><a href="profile/" class="text-link">EXIT</a></small></li>
				</ul>
				<div class="dashboard-panel-middle">
					<div class="saved-shipping-container">
						<?php if (isset($current_profile['shippings']) AND $current_profile['shippings']): ?>
							<?php foreach ($current_profile['shippings'] as $key => $shipping): ?>
								<div class="saved-shipping-item" id="shipping-item-<?php echo $shipping['id'];?>">
									<div class="text-left">
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
							<div class="text-step-basic">
								<p class="zero-gaps text-center"><i class="fa fa-exclamation-circle"></i></p>
								<p class="zero-gaps">Follow the <b class="text-contrast">Steps</b> bellow to save a <b>delivery address</b>.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php $this->view('static/map_location_form', ['url' => 'api/save_shipping']); ?>
			</div>
		</div>
		<?php endif; ?>
		
		<?php if (isset($current_profile['shippings']) AND $current_profile['shippings']): ?>
		<hr class="carved clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#f8f8f8;margin-bottom:-15px;padding:20px 0;">
			<a href="farm/storefront/">
				<img src="assets/images/banner/be-farmer.png" class="img-responsive" style="margin:0 auto;">
			</a>
		</div>
		<?php endif; ?>
	</div>
</div>