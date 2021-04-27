<div id="dashboard_panel_right">

	<?php $this->view('global/mobile_note'); ?>

	<div class="row hidden-xs" id="storefront">
		<?php if ($current_profile AND (isset($current_profile['is_agreed_terms']) AND !$current_profile['is_agreed_terms'])): ?>
			<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
				<div class="center-panel-md">
					<div class="dashboard-panel">
						<div class="dashboard-panel-top">
							<h3 style="margin:15px 0;">Welcome to Storefront!</h3>
							<p class="zero-gaps">This is your first step to becoming a seller on Gulaymart!</p>
							<p>Customize your farm's online store, upload photos and videos, share your stories and more!</p>
							<div style="margin-top:15px;">
								<button class="btn btn-lg btn-theme" data-toggle="modal" data-target="#farmer_terms_modal" style="margin-bottom:15px;">Create My Store<i class="fa fa-angle-right icon-right"></i></button>
								<img src="assets/images/gulaymar-storefront.png" class="img-responsive" style="margin:0 auto;width:100%;">
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<?php if ($this->farms AND empty($this->products->count())): ?>
				<div class="col-lg-12 col-md-12 col-sm-12 hidden-xs">
					<h4 class="zero-gaps">Add a new product <a href="farm/my-veggies" class="btn btn-contrast">My Veggies</a></h4>
					<hr class="carved">
				</div>
			<?php endif ?>

			<div class="col-lg-5 col-md-5 col-sm-12 hidden-xs" id="storefront_customs_parent">
				<div class="dashboard-panel theme">
					<form action="farm/storefront" method="post" class="form-validate storefront-forms" data-ajax="1">
						<input type="hidden" class="farm_id" name="user_farms[id]" value="<?php isset_echo($data['farms'], 'id');?>">
						<div class="dashboard-panel-top">
							<h3>Storefront Customs</h3>
							<ul class="spaced-list between" style="margin-top: 15px;">
								<li class="text-sm">
									<?php if (isset($data['farms']) AND $data['farms']): ?>
										<span class="text-gray">
											<i class="fa fa-calendar"></i> UPDATED
										</span><br><?php echo date('F j, Y', strtotime($data['farms']['updated']));?>
									<?php else: ?>
										<span class="text-gray">
											<i class="fa fa-calendar"></i> TODAY
										</span><br><?php echo date('F j, Y');?>
									<?php endif ?>
								</li>
								<?php if (isset($data['farms']) AND $data['farms']): ?>
									<li class="text-right">
										<a class="text-link btn btn-default icon-left" id="storefrontTab" target="storefrontTab<?php echo $data['farms']['id'];?>" href="<?php storefront_url($data['farms'], true);?>"><i class="fa fa-external-link-square"></i> View Store</a>
										<button type="submit" class="btn btn-contrast">Update</button>
									</li>
								<?php else: ?>
									<li><button type="submit" class="btn btn-contrast">Create</button></li>
								<?php endif ?>
							</ul>
						</div>
						<div class="dashboard-panel-middle zero-gaps storefront_nav">
							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>FARM IDENTITY</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group">
										<label for="farm_name">Farm name</label>
										<small class="elem-block text-gray"><i class="fa fa-exclamation-circle"></i> No special characters. Max 30 characters. <span class="text-link" data-toggle="modal" data-target="#farm_identity_help">NEED HELP?</span></small>
										<input type="text" name="user_farms[name]" id="farm_name" class="input-keyup form-control" placeholder="The Humble Farm" required="required" value="<?php isset_echo($data['farms'], 'name');?>">
									</div>
									<div class="form-group">
										<label for="tagline">Tagline</label>
										<small class="elem-block text-gray"><i class="fa fa-exclamation-circle"></i> Max 50 characters.</small>
										<input type="text" name="user_farms[tagline]" id="tagline" class="input-keyup form-control" placeholder="Your friendly neighborhood farmer" value="<?php isset_echo($data['farms'], 'tagline');?>">
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>ABOUT</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group">
										<small class="elem-block text-gray"><i class="fa fa-exclamation-circle"></i> Tell something about your farm.</small>
										<textarea type="text" name="user_farms[about]" class="form-control" placeholder="About your farm." rows="4" required="required"><?php isset_echo($data['farms'], 'about');?></textarea>
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>MASTHEAD</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group">
										<ul class="spaced-list between">
											<li><label>Cover image</label></li>
											<li class="text-link" data-toggle="modal" data-target="#media_modal" data-change-ui=".storefront-img-bg" data-field="cover_pic">Media</li>
										</ul>
										<small class="text-gray"><i class="fa fa-exclamation-circle"></i> Minimum size: 800 x 200 pixels.</small>
										<input type="hidden" id="cover_pic" name="user_farms[cover_pic]" value="<?php isset_echo($data['farms'], 'cover_pic');?>" required="required" />
									</div>
									<div class="form-group">
										<ul class="spaced-list between">
											<li><label>Profile photo</label></li>
											<li class="text-link" data-toggle="modal" data-target="#media_modal" data-change-ui=".profile_photo" data-field="profile_pic">Media</li>
										</ul>
										<small class="text-gray"><i class="fa fa-exclamation-circle"></i> Minimum size: 60 x 60 pixels.</small>
										<input type="hidden" id="profile_pic" name="user_farms[profile_pic]" value="<?php isset_echo($data['farms'], 'profile_pic');?>" required="required" />
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>LOCATIONS</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<div class="custom-item-child">
									<small class="elem-block text-gray"><i class="fa fa-exclamation-circle"></i> Where to pick up your products? <span class="text-link" data-toggle="modal" data-target="#farm_location_help">NEED HELP?</span></small>
									<div id="location_container">
										<div class="input-group hide" id="clone_me">
											<input type="text" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address" autocomplete="input" readonly="readonly">
											<span class="input-group-btn">
												<button type="button" class="btn btn-xs" id="remove_loc_btn"><i class="fa fa-minus text-danger"></i></button>
											</span>
										</div>
										<?php
										$farm_locations = false; $farm_loc = 0;
										if (isset($data['farm_locations']) AND $data['farm_locations']) {
											$farm_locations = $data['farm_locations'];
											$farm_loc = isset($farm_locations[0]) ? $farm_locations[0]['active'] : 0;
										}
										?>
										<label style="margin-top:5px;">
											<input type="radio" class="pick-up-loc" name="farm_loc" id="same_loc" value="0"<?php str_has_value_echo(0, $farm_loc, ' checked');?>> Use my shipping address.
										</label>
										<div class="<?php str_not_value_echo(0, $farm_loc, 'hide');?>" id="same_loc_container">
											<div class="row">
												<?php foreach ($current_profile['shippings'] as $key => $shipping): ?>
													<?php
													foreach (array_keys($shipping) as $value) {
														if (!in_array($value, unserialize(NON_LOCATION_KEYS))) {
															unset($shipping[$value]);
														}
													}
													if ($farm_locations) {
														$shipping['id'] = $farm_locations[$key]['id'];
													} else {
														$shipping['id'] = 0;
													}
													?>
													<div class="col-lg-12"<?php if (is_last($current_profile['shippings'], $key) == false AND count($current_profile['shippings']) > 1): ?> style="margin-bottom: 5px;"<?php endif ?>>
														<p class="zero-gaps"><b><?php echo $shipping['address_1'];?></b></p>
														<p class="zero-gaps"><small class="address_2"><?php echo $shipping['address_2'];?></small></p>
														<input type="hidden" name="user_farm_locations[0][]" value='<?php echo json_encode($shipping);?>' required="required">
													</div>
												<?php endforeach ?>
											</div>
										</div>

										<label class="icon-right">
											<input type="radio" class="pick-up-loc" name="farm_loc" id="diff_loc" value="1"<?php str_has_value_echo(1, $farm_loc, ' checked');?>> Enter a different address.
										</label>
										<div id="location_list" class="<?php str_not_value_echo(1, $farm_loc, 'hide');?>">
											<?php if ($farm_locations): ?>
												<?php foreach ($farm_locations as $key => $location): ?>
													<?php
													foreach (array_keys($location) as $value) {
														if (!in_array($value, unserialize(NON_LOCATION_KEYS))) {
															unset($location[$value]);
														}
													}
													?>
													<?php if ($farm_loc == 1): ?>
														<div class="input-group">
															<input type="text" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address" autocomplete="input" readonly="readonly" id="location-input-0" value="<?php remove_multi_space($location['address_1'] . ' ' . $location['address_2']);?>">
															<input type="hidden" name="user_farm_locations[1][]" value='<?php echo json_encode($location);?>' class="user-farm-locations" />
															<span class="input-group-btn">
															<?php if ($key == 0): ?>
																<button type="button" class="btn btn-xs" id="add_loc_btn"><i class="fa fa-plus color-contrast"></i></button>
															<?php else: ?>
																<button type="button" class="btn btn-xs" id="add_loc_btn"><i class="fa fa-minus text-danger"></i></button>
															<?php endif ?>
															</span>
														</div>
													<?php else: ?>
														<?php if ($key == 0): ?>
															<div class="input-group">
																<input type="text" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address" autocomplete="input" readonly="readonly" id="location-input-0" value="">
																<input type="hidden" name="user_farm_locations[1][<?php echo $key;?>]" value='' class="user-farm-locations" />
																<input type="hidden" name="locations[1][<?php echo $key;?>][id]" value='<?php echo $location['id'];?>' />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-xs" id="add_loc_btn"><i class="fa fa-plus color-contrast"></i></button>
																</span>
															</div>
														<?php endif ?>
													<?php endif ?>
												<?php endforeach ?>
											<?php else: ?>
												<div class="input-group">
													<input type="text" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address" autocomplete="input" readonly="readonly" id="location-input-0">
													<input type="hidden" name="user_farm_locations[1][]" class="user-farm-locations" />
													<span class="input-group-btn">
														<button type="button" class="btn btn-xs" id="add_loc_btn"><i class="fa fa-plus color-contrast"></i></button>
													</span>
												</div>
											<?php endif ?>
										</div>
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>BANNER</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<?php
								$banner = '';
								if (isset($data['farms']) AND isset($data['farms']['banner'])) {
									$banner = $data['farms']['banner'];
								}
								?>
								<div class="custom-item-child">
									<div class="form-group">
										<label for="banner_section">Select a banner</label>
										<select name="user_farms[banner]" id="banner_section" class="form-control chosen">
											<option value="steps.png"<?php str_has_value_echo('steps.png', $banner, ' selected');?>>Step 1 2 3 (Default)</option>
											<option value="lettuce.png"<?php str_has_value_echo('lettuce.png', $banner, ' selected');?>>Lettuce Fact</option>
											<option value="be-farmer.png"<?php str_has_value_echo('be-farmer.png', $banner, ' selected');?>>Deliver Fast</option>
										</select>
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>FOOTER</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="input-group" style="margin-bottom:10px;">
										<span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
										<input type="url" name="user_farms[facebook]" class="form-control social-url" data-id="#storefront-facebook" placeholder="Facebook URL" value="<?php isset_echo($data['farms'], 'facebook');?>">
									</div>
									<div class="input-group" style="margin-bottom:10px;">
										<span class="input-group-addon"><i class="fa fa-instagram"></i></span>
										<input type="url" name="user_farms[instagram]" class="form-control social-url" data-id="#storefront-instagram" placeholder="Instagram URL" value="<?php isset_echo($data['farms'], 'instagram');?>">
									</div>
									<div class="input-group" style="margin-bottom:10px;">
										<span class="input-group-addon"><i class="fa fa-youtube-play"></i></span>
										<input type="url" name="user_farms[youtube]" class="form-control social-url" data-id="#storefront-youtube" placeholder="YouTube URL" value="<?php isset_echo($data['farms'], 'youtube');?>">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-comment-o"></i></span>
										<input type="url" name="user_farms[messenger]" class="form-control social-url" data-id="#storefront-messenger" placeholder="Messenger URL" value="<?php isset_echo($data['farms'], 'messenger');?>">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-7 col-md-7 col-sm-12 hidden-xs">
				<div class="cover_image" id="storefront_page_container">
					<div class="storefront-top">
						<div class="storefront-img-bg" style="background-image: url(<?php echo(!empty($data['farms']['cover_pic']) ? $data['farms']['cover_pic'] : "assets/images/storefront-top.jpg"); ?>);">
							<div id="farm_identity">
								<ul class="grid-list half">
									<li class="text-left">
										<h1 class="farm_name"><?php echo(!empty($data['farms']['name']) ? $data['farms']['name'] : "The Humble Farm"); ?></h1>
										<h4 class="tagline"><?php echo(!empty($data['farms']['tagline']) ? $data['farms']['tagline'] : "Your friendly neighborhood farmer"); ?></h4>
									</li>
									<li class="text-right"><div class="profile_photo" style="background-image: url(<?php echo(!empty($data['farms']['profile_pic']) ? $data['farms']['profile_pic'] : "assets/images/noavatar.png"); ?>);"></div></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="storefront-middle">
						<ul class="spaced-list around" id="storefront_navbar">
							<li class="sf-navbar-btn active">PRODUCTS</a></li>
							<li class="sf-navbar-btn">STORIES</a></li>
							<li class="sf-navbar-btn">GALLERY</a></li>
							<li class="sf-navbar-btn">ABOUT</a></li>
						</ul>

						<div id="storefront_product_container">
							<img src="assets/images/storefront-sample-listing.png" class="img-responsive" style="width: 100%;">
							<div class="banner-section">
								<?php if (!empty($data['farms']['banner'])) : ?>
								<img src="assets/images/banner/<?php echo $data['farms']['banner']; ?>" class="img-responsive" style="width: 100%;">
								<?php else: ?>
								<img src="assets/images/banner/steps.png" class="img-responsive" style="width: 100%;">
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="storefront-footer">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<h4 class="farm_name" style="margin-top: 0;"><?php echo(!empty($data['farms']['name']) ? $data['farms']['name'] : "The Humble Farm"); ?></h4>
								<p class="zero-gaps" style="color: #a7a7a7;">By <a href="">Gulaymart</a></p>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
								<h4 style="color: #fff;margin: 0 0 5px 0;">Connect with us!</h4>
								<ul class="inline-list social_acct">
									<li><h4 class="zero-gaps"><i class="fa fa-facebook-square"></i></h4></li>
									<li><h4 class="zero-gaps"><i class="fa fa-instagram"></i></h4></li>
									<li><h4 class="zero-gaps"><i class="fa fa-youtube-play"></i></h4></li>
									<li><h4 class="zero-gaps"><i class="fa fa-comment-o"></i></h4></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif ?>
	</div>
</div>
