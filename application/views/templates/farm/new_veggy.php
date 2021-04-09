<div id="dashboard_panel_right">
	<?php $this->view('global/mobile_note'); ?>

		<div class="row hidden-xs" id="new_veggy">
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" id="score_detail_container">
				<div class="dashboard-panel" id="score-detail-panel">
					<div class="dashboard-panel-top">
						<h3 class="zero-gaps">Product Details Score</h3>
					</div>
					<div class="dashboard-panel-middle">
						<ul class="spaced-list around">
							<li><p class="text-capsule">10<small style="display:inline;">%</small></p></li>
							<li><p class="text-capsule">30<small style="display:inline;">%</small></p></li>
							<li><p class="text-capsule">60<small style="display:inline;">%</small></p></li>
							<li><p class="text-capsule">80<small style="display:inline;">%</small></p></li>
							<li><p class="text-capsule"><i class="fa fa-check"></i></h3></li>
						</ul>
						<div class="timeline-border"></div>
						<div class="timeline-border-progress" data-percent="0"></div>
					</div>
				</div>

				<div class="dashboard-panel theme score-0">
					<div class="dashboard-panel-middle">
						<form action="" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="basic_prod_info">
							<input type="hidden" name="pos" value="0">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="input-container">
								<label for="product_name">Product name</label>
								<div class="input-group">
									<input type="text" class="form-control" id="product_name" name="products[name]" required="required" placeholder="Example: Fresh & Organic Romaine Lettuce [Per kilo]">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" id="prod_name_checker"><i class="fa fa-chevron-right color-theme"></i></button>
									</span>
								</div>
								<small class="text-gray"><i class="fa fa-exclamation-circle"></i> Customers like a short yet concise Product name that tells essential details upfront.</small>
							</div>
							<div class="input-container hide" id="category_container" style="margin-bottom:0;">
								<label for="product_name">Category</label>
								<?php if ($this->categories): ?>
									<ul class="inline-list">
										<?php foreach ($this->categories as $key => $category): ?>
											<li class="input-capsule"><input type="checkbox" name="products[category_id]" id="category-<?php echo $category['id'];?>" value="<?php echo $category['id'];?>"><label for="category-<?php echo $category['id'];?>"><?php echo $category['label'];?></label></li>
										<?php endforeach ?>
									</ul>
								<?php endif ?>
								<div class="hide arrow_box" id="sub_category">
									<p>Sub Category</p>
									<?php if ($this->subcategories): 
										$reset = array_keys($this->subcategories);
										$first = reset($reset);
										?>
										<?php foreach ($this->subcategories as $cat_id => $subcategory): ?>
											<ul class="inline-list hide category-<?php echo $cat_id;?>">
												<?php foreach ($subcategory as $key => $sub): ?>
													<li class="input-capsule"><input type="radio" name="products[subcategory_id]" id="subcategory-<?php echo $sub['id'];?>" value="<?php echo $sub['id'];?>"><label for="subcategory-<?php echo $sub['id'];?>"><?php echo $sub['label'];?></label></li>
												<?php endforeach ?>
											</ul>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div id='basic_btn_container' style='margin-top:15px;text-align:right;display:none;'><button class='btn btn-theme '>Next<i class='fa fa-chevron-right icon-right'></i></button></div>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme hide score-1">
					<div class="dashboard-panel-middle">
						<div style="margin-bottom:15px;">
							<label>Product attributes</label>
							<small class="text-gray"><i class="fa fa-exclamation-circle"></i> Use preset to select an attribute or enter your own. Only letters and numbers are allowed.</small>
						</div>
						<form action="" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="prod_attribute">
							<input type="hidden" name="pos" value="1">
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle text-gray"></i></small></span>
								<input type="text" class="form-control" name="products_attribute[0][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 ]*$'" required="required" placeholder="How do you grow your plant?">
								<div class="input-group-btn">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											Preset <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;">Home or commercially grown organically.</a></li>
											<li><a href="javascript:;">Traditional soil-based plant.</a></li>
											<li><a href="javascript:;">Grown using Hydrophonic technology.</a></li>
											<li><a href="javascript:;">Utilized Acquaphonic technology.</a></li>
											<li><a href="javascript:;">Used food-grade formulated plant grower.</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle text-gray"></i></small></span>
								<input type="text" class="form-control" name="products_attribute[1][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 ]*$'" required="required" placeholder="Sold ripe or unripe?">
								<div class="input-group-btn">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											Preset <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;">Riped organically, tasty and juicy.</a></li>
											<li><a href="javascript:;">Sold unripe with roots intact.</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle text-gray"></i></small></span>
								<input type="text" class="form-control" name="products_attribute[2][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 ]*$'" required="required" placeholder="Is the product in good shape?">
								<div class="input-group-btn">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											Preset <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;">In good shape, smell, texture, and color.</a></li>
											<li><a href="javascript:;">Slightly deformed, but presentable.</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle text-gray"></i></small></span>
								<input type="text" class="form-control" name="products_attribute[3][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 ]*$'" required="required" placeholder="Freshness detail">
								<div class="input-group-btn">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											Preset <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;">Picked same day upon order.</a></li>
											<li><a href="javascript:;">Freshly refrigirated.</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle text-gray"></i></small></span>
								<input type="text" class="form-control" name="products_attribute[4][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 ]*$'" required="required" placeholder="How do you package the product?">
								<div class="input-group-btn">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											Preset <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;">Delivered in an eco-friendly pouch.</a></li>
											<li><a href="javascript:;">Packaged in a regular plastic bag.</a></li>
										</ul>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme hide score-2">
					<div class="dashboard-panel-middle">
						<div style="margin-bottom:15px;">
							<label>Pricing</label>
							<small class="text-gray"><i class="fa fa-exclamation-circle"></i> Be honest with pricing and never put a stock you don't have on hand.</small>
						</div>
						<form action="" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="prod_price">
							<input type="hidden" name="pos" value="2">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="input-group">
										<span class="input-group-addon"><span class=" hidden-sm hidden-xs">&#x20b1;</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="Price"></i></span>
										<input type="text" class="form-control" name="products[price]" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required="required">
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
										<select type="text" class="form-control" name="products[measurement]" required="required">
											<?php if ($this->measurements): ?>
												<?php foreach ($this->measurements as $key => $measurement): ?>
													<option value="<?php echo $measurement['value'];?>"><?php echo $measurement['label'];?></option>
												<?php endforeach ?>
											<?php endif ?>
										</select>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="input-group">
										<span class="input-group-addon"><span class=" hidden-sm hidden-xs">Stocks</span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Stocks"></i></span>
										<input type="number" maxlength="3" class="form-control" name="products[stocks]" required="required">
									</div>
								</div>
							</div>
							<div id="price_btn_container" style="text-align:right;">
								<button class="btn btn-theme ">Next<i class="fa fa-chevron-right icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme hide score-3">
					<div class="dashboard-panel-middle">
						<div style="margin-bottom:15px;">
							<label>Short description</label>
							<small class="text-gray"><i class="fa fa-exclamation-circle"></i> Write your product description here. Limit 300 characters.</small>
						</div>
						<form action="" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="prod_desc">
							<input type="hidden" name="pos" value="3">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="form-group">
								<textarea class="form-control" rows="5" name="products[description]" required="required"></textarea>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class=" hidden-sm hidden-xs">What's in the bag?</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="What's in the bag?"></i></span>
								<input type="text" class="form-control" name="products[inclusion]" required="required">
							</div>
							<div id="desc_btn_container" style="margin-top:15px;text-align:right;">
								<button class="btn btn-theme ">Next<i class="fa fa-chevron-right icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme hide score-4">
					<form action="" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="products_photo" data-notmedia="1">
						<input type="hidden" name="pos" value="4">
						<div class="dashboard-panel-middle">
							<div style="margin-bottom:15px;">
								<label>Images</label>
								<small class="text-gray"><i class="fa fa-exclamation-circle"></i> You can upload multiple images at once (max 5). Then select the main cover image of your product.</small>
							</div>
							<ul class="inline-list preview_images_list"></ul>
							<div class="input-group">
								<input type="file" class="form-control input_upload_images" name="products_photo[]" required="required" multiple>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Select<i class="fa fa-picture-o icon-right"></i></button>
								</span>
							</div>
						</div>
						<div class="dashboard-panel-footer text-right bg-grey">
							<button value="0" name="activity" class="btn btn-default">Draft<i class="fa fa-floppy-o icon-right"></i></button>
							<button value="0" name="activity" class="btn btn-contrast icon-right">Submit<i class="fa fa-check icon-right"></i></button>
						</div>
					</form>
				</div>
			</div>

			<!-- show after Publish -->
			<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 hide" id="preview_container">
				<div class="dashboard-panel">
					<div class="dashboard-panel-top">
						<ul class="spaced-list between">
							<li><p>Listing</p></li>
							<li><p><a href="#" target="_new" class="text-link" id="order-link">Product Page</a></p></li>
						</ul>
						<div class="product-list-card">
							<div class="product-list-photo" id="order-photo" style="background-image:url('https://via.placeholder.com/220x220?text=Product+photo+shows+here');">
							</div>
							<div class="product-desc-body">
								<div class="product-title-container ellipsis-container">
									<h1 class="zero-gaps" id="order-title">Product name shows here</h1>
								</div>
							</div>
							<div class="product-list-footer">
								<ul class="spaced-list between">
									<li><p class="product-price">₱ 120 / Kilogram</p></li>
									<li><p class="product-price"><i class="fa fa-clock-o"></i> 30 mins</p></li>
								</ul>
							</div>
						</div>
						<div class="new-veggy-status-container">
							<h5 class="zero-gaps text-center"><b>Status:</b> <span id="order-status">For review</span></h5>
						</div>
					</div>
					<div class="dashboard-panel-footer visible-xs">
						<a href="farm/new-veggy" class="btn btn-default btn-block" style="width:220px;margin:0 auto;">New Veggy</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>