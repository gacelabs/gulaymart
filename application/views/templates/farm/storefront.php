<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<?php $this->view('static/mobile_note'); ?>
	<div class="dash-panel-right-container" id="storefront">
		<div class="dash-panel-right-canvas">
			<!-- Hide after AGREED on terms and privacy -->
			<!--div class="center-panel-md">
				<div class="dash-panel">
					<div class="dash-panel-top">
						<h3 style="margin-bottom: 15px;">Welcome to Storefront!</h3>
						<p>On this page, you can build your very own online store! Get discovered by the Gulaymart community by sharing your farm stories, posting videos or photos and more!</p>
						<div class="text-center">
							<img src="assets/images/gulaymar-storefront.png" class="img-responsive" style="margin:0 auto;">
							<button class="btn btn-lg btn-theme normal-radius" data-toggle="modal" data-target="#farmer_terms_modal">Let's get started<i class="fa fa-angle-right icon-right"></i></button>
						</div>
					</div>
				</div>
			</div-->

			<div class="col-lg-3 col-md-4 col-sm-7 hidden-xs" id="storefront_customs_parent">
				<div class="dash-panel theme">
					<form action="" method="post">
						<div class="dash-panel-top">
							<h3>Storefront customs</h3>
							<ul class="spaced-list between" style="margin-top: 15px;">
								<li class="text-sm"><span class="color-grey"><i class="fa fa-calendar"></i> UPDATED</span><br>March 1, 2021</li>
								<li><button type="submit" class="btn btn-blue normal-radius">Publish</button></li>
							</ul>
						</div>
						<div class="dash-panel-middle zero-gaps" id="storefront_nav">
							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>FARM IDENTITY</li>
									<li><i class="fa fa-angle-right"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group">
										<label for="farm_name">Farm name</label>
										<input type="text" name="farm_name" id="farm_name" class="input-keyup form-control" placeholder="The Humble Farm" required="required">
										<small class="color-grey"><i class="fa fa-exclamation-circle"></i> No special characters. Max 30 characters.</small>
									</div>
									<div class="form-group">
										<label for="tagline">Tagline</label>
										<input type="text" name="tagline" id="tagline" class="input-keyup form-control" placeholder="Your friendly neighborhood farmer" required="required">
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
											<li><label for="cover_image">Cover image</label></li>
											<li class="text-link">Media</li>
										</ul>
										<input type="file" name="cover_image" id="cover_image" class="form-control" placeholder="The Humble Farm" required="required">
										<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Minimum size: </small>
									</div>
									<div class="form-group">
										<ul class="spaced-list between">
											<li><label for="profile_photo">Profile photo</label></li>
											<li class="text-link">Media</li>
										</ul>
										<input type="file" name="profile_photo" id="profile_photo" class="form-control" placeholder="The Humble Farm" required="required">
										<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Minimum size: </small>
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
										<textarea type="text" name="farm_story" class="form-control" placeholder="About your farm." rows="4" required="required"></textarea>
									</div>
									<label for="location">Location</label> <small class="color-grey"><i class="fa fa-exclamation-circle"></i> Not necessarily in order. Max 5.</small>
									<div id="location_container">
										<div class="input-group hide" id="clone_me">
											<input type="text" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address">
											<span class="input-group-btn">
												<button type="button" class="btn btn-xs" id="remove_loc_btn"><i class="fa fa-minus text-danger"></i></button>
											</span>
										</div>

										<div class="input-group">
											<input type="text" name="location" class="form-control" data-toggle="modal" data-target="#farm_location_modal" placeholder="Complete address">
											<span class="input-group-btn">
												<button type="button" class="btn btn-xs" id="add_loc_btn"><i class="fa fa-plus color-blue"></i></button>
											</span>
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
										<select name="banner_section" id="banner_section" class="form-control">
											<option value="steps.png">Step 1 2 3 (Default)</option>
											<option value="lettuce.png">Lettuce Fact</option>
											<option value="">Deliver Fast</option>
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
										<input type="url" name="social_acct_fb" class="form-control social-url" placeholder="Facebook URL">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-instagram"></i></span>
										<input type="url" name="social_acct_ig" class="form-control social-url" placeholder="Instagram URL">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-youtube-play"></i></span>
										<input type="url" name="social_acct_yt" class="form-control social-url" placeholder="YouTube URL">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-comment-o"></i></span>
										<input type="url" name="social_acct_msgr" class="form-control social-url" placeholder="Messenger URL">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-1 hidden-md hidden-sm hidden-xs"></div>			

			<div class="col-lg-7 col-md-8 col-sm-12 hidden-xs" id="storefront_preview_parent">
				<div class="cover_image" id="storefront_page_container">
					<div class="storefront-top">
						<div class="storefront-img-bg" style="background-image: url(assets/images/storefront-top.jpg);">
							<div id="farm_identity">
								<ul class="grid-list half">
									<li class="text-left">
										<h1 class="farm_name">The Humble Farm</h1>
										<h4 class="tagline">Your friendly neighborhood farmer</h4>
									</li>
									<li class="text-right"><div class="profile_photo" style="background-image: url(assets/images/noavatar.png);"></div></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="storefront-middle">
						<ul class="spaced-list around" id="storefront_navbar">
							<li class="active">PRODUCTS</a></li>
							<li>STORIES</a></li>
							<li>GALLERY</a></li>
							<li>ABOUT</a></li>
						</ul>

						<div id="storefront_product_container">
							<img src="assets/images/storefront-sample-listing.png" class="img-responsive">
							<img src="assets/images/banner/steps.png" class="banner_section img-responsive">
						</div>
					</div>

					<div class="storefront-footer">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
								<h4 class="farm_name" style="margin-top: 0;">The Humble Farm</h4>
								<p class="zero-gaps hidden-xs" style="color: #a7a7a7;">By <a href="">Gulaymart</a></p>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
								<h4 style="color: #fff;margin: 0 0 5px 0;">Connect with us!</h4>
								<ul class="inline-list social_acct">
									<li><h4 class="zero-gaps"><i class="fa fa-facebook-square"></i></h4></li>
									<li><h4 class="zero-gaps"><i class="fa fa-instagram"></i></h4></li>
									<li><h4 class="zero-gaps"><i class="fa fa-youtube-play"></i></h4></li>
									<li><h4 class="zero-gaps"><i class="fa fa-comment-o"></i></h4></li>
								</ul>
								<p class="zero-gaps visible-xs" style="color: #a7a7a7;">By <a href="">Gulaymart</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
