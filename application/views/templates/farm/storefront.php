<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<?php $this->view('static/mobile_note'); ?>
	<div class="dash-panel-right-container" id="storefront">
		<div class="dash-panel-right-canvas">
			<?php if ($current_profile AND (isset($current_profile['is_agreed_terms']) AND !$current_profile['is_agreed_terms'])): ?>
				<!-- Hide after AGREED on terms and privacy -->
				<div class="center-panel-md">
					<div class="dash-panel">
						<div class="dash-panel-top">
							<h3 style="margin:15px 0;">Welcome to Storefront!</h3>
							<p>On this page, you can build your very own online store! Showcase your products in a nice, clean and professional looking e-commerce website.</p>
							<p>Get discovered by the Gulaymart community by sharing your farm stories, posting videos or photos and more!</p>
							<div class="text-center">
								<img src="assets/images/gulaymar-storefront.png" class="img-responsive" style="margin:0 auto;width:100%;">
								<button class="btn btn-lg btn-theme normal-radius" data-toggle="modal" data-target="#farmer_terms_modal" style="margin-bottom:15px;">Let's get started<i class="fa fa-angle-right icon-right"></i></button>
							</div>
						</div>
					</div>
				</div>
			<?php else: ?>
				<?php /*debug($data['farms'], 'stop');*/ ?>
				<div class="col-lg-5 col-md-4 col-sm-12 hidden-xs" id="storefront_customs_parent">
					<div class="dash-panel theme">
						<form action="farm/storefront" method="post" class="form-validate" data-ajax="1">
							<div class="dash-panel-top">
								<h3>Storefront Customs</h3>
								<ul class="spaced-list between" style="margin-top: 15px;">
									<li class="text-sm">
										<?php if (count($data['farms'])): ?>
											<span class="color-grey">
												<i class="fa fa-calendar"></i> UPDATED
											</span><br>March 1, 2021
										<?php else: ?>
											<span class="color-grey">
												<i class="fa fa-calendar"></i> TODAY
											</span><br><?php echo date('F j, Y');?>
										<?php endif ?>
									</li>
									<li><button type="submit" class="btn btn-blue normal-radius">Create</button></li>
								</ul>
							</div>
							<div class="dash-panel-middle zero-gaps storefront_nav">
								<div class="custom-item-parent">
									<ul class="spaced-list between custom-item-btn">
										<li>FARM IDENTITY</li>
										<li><i class="fa fa-angle-right"></i></li>
									</ul>
									<div class="custom-item-child">
										<div class="form-group">
											<label for="farm_name">Farm name</label>
											<input type="text" name="user_farms[name]" id="farm_name" class="input-keyup form-control" placeholder="The Humble Farm" required="required">
											<small class="color-grey"><i class="fa fa-exclamation-circle"></i> No special characters. Max 30 characters.</small>
										</div>
										<div class="form-group">
											<label for="tagline">Tagline</label>
											<input type="text" name="user_farms[tagline]" id="tagline" class="input-keyup form-control" placeholder="Your friendly neighborhood farmer" required="required">
											<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Max 50 characters.</small>
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
											<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Minimum size: 800 x 200 pixels.</small>
											<input type="hidden" id="cover_pic" name="user_farms[cover_pic]" value="" />
										</div>
										<div class="form-group">
											<ul class="spaced-list between">
												<li><label>Profile photo</label></li>
												<li class="text-link" data-toggle="modal" data-target="#media_modal" data-change-ui=".profile_photo" data-field="profile_pic">Media</li>
											</ul>
											<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Minimum size: 60 x 60 pixels.</small>
											<input type="hidden" id="profile_pic" name="user_farms[profile_pic]" value="" />
										</div>
									</div>
								</div>

								<div class="custom-item-parent">
									<ul class="spaced-list between custom-item-btn">
										<li>LOCATIONS</li>
										<li><i class="fa fa-angle-right"></i></li>
									</ul>
									<div class="custom-item-child">
										<small class="elem-block color-grey"><i class="fa fa-exclamation-circle"></i> Where to pick up your products?</small>
										<div id="location_container">
											<div class="input-group hide" id="clone_me">
												<input type="text" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address" autocomplete="input">
												<span class="input-group-btn">
													<button type="button" class="btn btn-xs" id="remove_loc_btn"><i class="fa fa-minus text-danger"></i></button>
												</span>
											</div>

											<label style="margin-top:5px;">
												<input type="radio" class="pick-up-loc" name="farm_loc" id="same_loc" value="0"> Use my shipping address.
											</label>
											<div class="hide" id="same_loc_container">
												<div class="row">
													<?php foreach ($current_profile['shippings'] as $key => $shipping): ?>
														<div class="col-lg-12">
															<p class="zero-gaps"><b><?php echo $shipping['address_1'];?></b></p>
															<p class="zero-gaps"><small class="address_2"><?php echo $shipping['address_2'];?></small></p>
															<input type="hidden" name="user_farm_locations[0][]" value='<?php echo json_encode($shipping);?>'>
														</div>
														<?php if (is_last($current_profile['shippings'], $key) == false): ?><span>&nbsp;</span><?php endif ?>
													<?php endforeach ?>
												</div>
											</div>

											<label>
												<input type="radio" class="pick-up-loc" name="farm_loc" id="diff_loc" value="1"> Enter a different address.
											</label>
											<div id="location_list" class="hide">
												<div class="input-group">
													<input type="text" name="user_farm_locations[1][]" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address" autocomplete="input" required="required">
													<span class="input-group-btn">
														<button type="button" class="btn btn-xs" id="add_loc_btn"><i class="fa fa-plus color-blue"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="custom-item-parent">
									<ul class="spaced-list between custom-item-btn">
										<li>BANNER</li>
										<li><i class="fa fa-angle-right"></i></li>
									</ul>
									<div class="custom-item-child">
										<div class="form-group">
											<label for="banner_section">Select a banner</label>
											<select name="user_farms[banner]" id="banner_section" class="form-control chosen">
												<option value="steps.png">Step 1 2 3 (Default)</option>
												<option value="lettuce.png">Lettuce Fact</option>
												<option value="be-farmer.png">Deliver Fast</option>
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
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
											<input type="url" name="user_farms[facebook]" class="form-control social-url" placeholder="Facebook URL">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-instagram"></i></span>
											<input type="url" name="user_farms[instagram]" class="form-control social-url" placeholder="Instagram URL">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-youtube-play"></i></span>
											<input type="url" name="user_farms[youtube]" class="form-control social-url" placeholder="YouTube URL">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-comment-o"></i></span>
											<input type="url" name="user_farms[messenger]" class="form-control social-url" placeholder="Messenger URL">
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="dash-panel theme">
						<form action="farm/storefront" method="post" class="form-validate" data-ajax="1">
							<div class="dash-panel-top">
								<h3>Storefront Contents</h3>
								<ul class="spaced-list between" style="margin-top: 15px;">
									<li class="text-sm">
										<?php if (count($data['farms'])): ?>
											<span class="color-grey">
												<i class="fa fa-calendar"></i> UPDATED
											</span><br>March 1, 2021
										<?php else: ?>
											<span class="color-grey">
												<i class="fa fa-calendar"></i> TODAY
											</span><br><?php echo date('F j, Y');?>
										<?php endif ?>
									</li>
									<li><button type="submit" class="btn btn-blue normal-radius">Create</button></li>
								</ul>
							</div>
							<div class="dash-panel-middle zero-gaps storefront_nav">
								<div class="custom-item-parent">
									<ul class="spaced-list between custom-item-btn">
										<li>PRODUCTS</li>
										<li><i class="fa fa-angle-right"></i></li>
									</ul>
									<div class="custom-item-child">
										<div class="form-group">
											<?php if (isset($data['products']) AND $data['products']): ?>
												<small class="color-grey"><i class="fa fa-exclamation-circle"></i> What you want to sell </small>
												<select name="user_farm_contents[products]" id="products_section" class="form-control chosen" multiple="multiple" required="required">
													<?php foreach ($data['products'] as $key => $product): ?>
														<?php 
														$src = 'https://via.placeholder.com/50x50.png?text=No+Image';
														if ($product['photos']) {
															$src = $product['photos']['main']['url_path'];
														}
														?>
														<option value="<?php echo $product['id'];?>" data-img-src="<?php echo $src;?>"><?php echo $product['name'];?> | <?php echo $product['price'];?></option>
													<?php endforeach ?>
												</select>
											<?php else: ?>
												<p><a href="farm/new-veggy">Go here to add your products</a></p>
											<?php endif ?>
										</div>
									</div>
								</div>

								<div class="custom-item-parent">
									<ul class="spaced-list between custom-item-btn">
										<li>STORIES</li>
										<li><i class="fa fa-angle-right"></i></li>
									</ul>
									<div class="custom-item-child">
										<div class="form-group">
											<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Story Title: </small>
											<input type="text" name="user_farm_contents[story_title]" id="story_title" class="input-keyup form-control" placeholder="Title" required="required">
										</div>
										<div class="form-group">
											<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Write a story: </small>
											<textarea type="text" name="user_farm_contents[story_content]" class="form-control" placeholder="What keeps you going?" required="required"></textarea>
										</div>
									</div>
								</div>

								<div class="custom-item-parent">
									<ul class="spaced-list between custom-item-btn">
										<li>GALLERY</li>
										<li><i class="fa fa-angle-right"></i></li>
									</ul>
									<div class="custom-item-child">
										<div class="form-group">
											<?php if (isset($data['galleries']) AND $data['galleries']): ?>
												<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Share your great photos </small>
												<select name="user_farm_contents[galleries]" class="form-control chosen" multiple="multiple" required="required">
													<?php foreach ($data['galleries'] as $key => $gallery): ?>
														<option value="<?php echo $gallery['id'];?>" data-img-src="<?php echo $gallery['url_path'];?>"><?php echo $gallery['name'];?></option>
													<?php endforeach ?>
												</select>
											<?php else: ?>
												<ul class="spaced-list between">
													<li><label>Add photos</label></li>
													<li class="text-link" data-toggle="modal" data-target="#media_modal">Media</li>
												</ul>
												<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Minimum size: </small>
											<?php endif ?>
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
											<textarea type="text" name="user_farm_contents[about]" class="form-control" placeholder="About your farm." required="required"></textarea>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<iframe id="preview-store-page" class="col-lg-7 col-md-8 col-sm-12 hidden-xs" src="farm/storefront/preview" style="position: absolute; height: 120%; border: none; width: 57%;"></iframe>
			<?php endif ?>
		</div>
	</div>
</div>