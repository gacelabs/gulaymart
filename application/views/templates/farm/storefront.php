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

			<div class="col-lg-3 col-md-4 col-sm-5 hidden-xs" id="storefront_customs_parent">
				<div class="dash-panel theme">
					<form action="" method="post">
						<div class="dash-panel-top">
							<ul class="spaced-list between">
								<li><h3>Storefront customs</h3></li>
								<li><button class="btn btn-blue btn-sm normal-radius">Apply</button></li>
							</ul>
						</div>
						<div class="dash-panel-middle zero-gaps" id="storefront_nav">
						
							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>Farm identity</li>
									<li><i class="fa fa-angle-down"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group">
										<label for="farm_name">Farm name</label>
										<input type="text" name="farm_name" id="farm_name" class="form-control" placeholder="The Humble Farm" required="required">
									</div>
									<div class="form-group zero-gaps">
										<label for="tagline">Tagline</label>
										<input type="text" name="tagline" id="tagline" class="form-control" placeholder="Your friendly neighborhood farmer" required="required">
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>Masthead design</li>
									<li><i class="fa fa-angle-down"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group">
										<ul class="spaced-list between">
											<li><label for="cover_image">Cover image</label></li>
											<li class="text-link">Photos</li>
										</ul>
										<input type="file" name="cover_image" id="cover_image" class="form-control" placeholder="The Humble Farm" required="required">
										<small class="color-grey"><i class="fa fa-question-circle"></i> Recommended size: </small>
									</div>
									<div class="form-group zero-gaps">
										<ul class="spaced-list between">
											<li><label for="profile_photo">Profile photo</label></li>
											<li class="text-link">Photos</li>
										</ul>
										<input type="file" name="profile_photo" id="profile_photo" class="form-control" placeholder="The Humble Farm" required="required">
										<small class="color-grey"><i class="fa fa-question-circle"></i> Recommended size: </small>
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>My farm story</li>
									<li><i class="fa fa-angle-down"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group zero-gaps">
										<textarea type="text" name="farm_story" class="form-control" placeholder="About your farm." rows="4" required="required"></textarea>
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>Banner section</li>
									<li><i class="fa fa-angle-down"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="form-group zero-gaps">
										<label for="banner_section">Select a banner</label>
										<select name="banner_section" id="banner_section" class="form-control">
											<option img-file="">Deliver fast</option>
										</select>
									</div>
								</div>
							</div>

							<div class="custom-item-parent">
								<ul class="spaced-list between custom-item-btn">
									<li>Footer</li>
									<li><i class="fa fa-angle-down"></i></li>
								</ul>
								<div class="custom-item-child">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-facebook-square"></i></span>
										<input type="url" name="social_acct_fb" class="form-control" placeholder="Facebook URL">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-instagram"></i></span>
										<input type="url" name="social_acct_ig" class="form-control" placeholder="Instagram URL">
									</div>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-youtube-play"></i></span>
										<input type="url" name="social_acct_yt" class="form-control" placeholder="YouTube URL">
									</div>
									<div class="input-group zero-gaps">
										<span class="input-group-addon"><i class="fa fa-comment-o"></i></span>
										<input type="url" name="social_acct_msgr" class="form-control" placeholder="Messenger URL">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-sm-12 hidden-xs" id="storefront_preview_parent">
				<div class="cover_image" id="storefront_page_container">
					<div class="storefront-top ">
						<ul class="grid-list half">
							<li><h1 class="farm_name"></h1></li>
							<li class="text-center"><div class="profile_photo"></div></li>
						</ul>
					</div>
					
					<div class="storefront-middle">
						<ul class="spaced-list between" id="storefront_navbar">
							<li><a href="store/store-name/products/">Products</a></li>
							<li><a href="store/store-name/products/">Stories</a></li>
							<li><a href="store/store-name/products/">About</a></li>
						</ul>

						<div id="storefront_product_container">
							
						</div>
					</div>

					<div class="storefront-footer">
						<div class="banner_section"></div>
						<ul class="spaced-list between social_acct"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
