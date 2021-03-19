<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container" id="new_veggy">
		<div class="dash-panel-right-canvas">
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
				<div class="dash-panel">
					<div class="dash-panel-top">
						<h3>Product details score</h3>
					</div>
					<div class="dash-panel-middle">
						<ul class="spaced-list around">
							<li><h3 class="text-capsule">10<small style="display:inline;">%</small></h3></li>
							<li><h3 class="text-capsule">30<small style="display:inline;">%</small></h3></li>
							<li><h3 class="text-capsule">60<small style="display:inline;">%</small></h3></li>
							<li><h3 class="text-capsule">80<small style="display:inline;">%</small></h3></li>
							<li><h3 class="text-capsule"><i class="fa fa-check"></i></h3></li>
						</ul>
						<div class="timeline-border"></div>
					</div>
				</div>

				<div class="dash-panel theme">
					<div class="dash-panel-middle">
						<form action="" method="post" enctype="multipart/form-data" id="basic_prod_info">
							<div class="input-container">
								<label for="product_name">Product name</label>
								<div class="input-group">
									<input type="text" class="form-control" id="product_name" name="products[name]" required="required" placeholder="Example: Fresh & Organic Romaine Lettuce [Per kilo]">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" id="prod_name_checker"><i class="fa fa-chevron-right color-theme"></i></button>
									</span>
								</div>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Customers like a short yet concise Product name that tells essential details upfront.</small>
							</div>
							<div class="input-container hide" id="category_container" style="margin-bottom:0;">
								<label for="product_name">Category</label>
								<?php if ($this->categories): ?>
									<ul class="inline-list">
									<?php foreach ($this->categories as $key => $category): ?>
										<li class="input-capsule"><input type="checkbox" name="category" id="category-<?php echo $category['id'];?>" value="<?php echo $category['value'];?>"><label for="category-<?php echo $category['id'];?>"><?php echo $category['label'];?></label></li>
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
												<li class="input-capsule"><input type="radio" name="subcategory" id="subcategory-<?php echo $sub['id'];?>" value="<?php echo $sub['value'];?>"><label for="subcategory-<?php echo $sub['id'];?>"><?php echo $sub['label'];?></label></li>
											<?php endforeach ?>
										</ul>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div id='basic_btn_container' style='margin-top:15px;text-align:right;display:none;'><button class='btn btn-theme normal-radius'>Next<i class='fa fa-chevron-right icon-right'></i></button></div>
							</div>
						</form>
					</div>
				</div>

				<div class="dash-panel theme">
					<div class="dash-panel-middle">
						<div style="margin-bottom:15px;">
							<p class="zero-gaps">Product attributes</p>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Use preset to select an attribute or enter your own. Only letters and numbers are allowed.</small>
						</div>
						<form action="" method="post" enctype="multipart/form-data" id="prod_attribute">
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
								<input type="text" class="form-control" name="products[attribute]" required="required" placeholder="How do you grow your plant?">
								<div class="input-group-btn">
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											Preset <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu pull-right">
											<li><a href="javascript:;">Home grown organically.</a></li>
											<li><a href="javascript:;">Traditional soil-based plant.</a></li>
											<li><a href="javascript:;">Grown using Hydrophonic technology.</a></li>
											<li><a href="javascript:;">Utilized Acquaphonic technology.</a></li>
											<li><a href="javascript:;">Used food-grade formulated plant grower.</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
								<input type="text" class="form-control" name="products[attribute]" required="required" placeholder="Sold ripe or unripe?">
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
								<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
								<input type="text" class="form-control" name="products[attribute]" required="required" placeholder="Is the product in good shape?">
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
								<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
								<input type="text" class="form-control" name="products[attribute]" required="required" placeholder="Freshness detail">
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
								<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
								<input type="text" class="form-control" name="products[attribute]" required="required" placeholder="How do you package the product?">
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

				<div class="dash-panel theme">
					<div class="dash-panel-middle">
						<div style="margin-bottom:15px;">
							<p class="zero-gaps">Pricing</p>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Be honest with pricing and never put a stock you don't have on hand.</small>
						</div>
						<form action="" method="post" enctype="multipart/form-data" id="prod_price">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="input-group">
										<span class="input-group-addon"><span class=" hidden-sm hidden-xs">Stocks</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="Stocks"></i></span>
										<input type="number" maxlength="3" class="form-control" name="products[stocks]" required="required">
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="input-group">
										<span class="input-group-addon"><span class=" hidden-sm hidden-xs">&#x20b1;</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="Price"></i></span>
										<input type="text" class="form-control" name="products[price]" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required="required">
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
										<select type="text" class="form-control" name="products[price]" required="required">
											<?php if ($this->measurements): ?>
												<?php foreach ($this->measurements as $key => $measurement): ?>
													<option value="<?php echo $measurement['value'];?>"><?php echo $measurement['label'];?></option>
												<?php endforeach ?>
											<?php endif ?>
										</select>
									</div>
								</div>
							</div>
							<div id="price_btn_container" style="text-align:right;">
								<button class="btn btn-theme normal-radius">Next<i class="fa fa-chevron-right icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>


				<div class="dash-panel theme">
					<div class="dash-panel-middle">
						<div style="margin-bottom:15px;">
							<p class="zero-gaps">Short description</p>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Write your product description here. Limit 300 characters.</small>
						</div>
						<form action="" method="post" enctype="multipart/form-data" id="prod_desc">
							<div class="form-group">
								<textarea class="form-control" rows="5" required="required"></textarea>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class=" hidden-sm hidden-xs">What's in the bag?</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="What's in the bag?"></i></span>
								<input type="text" class="form-control" name="products[inclusion]" required="required">
							</div>
							<div id="desc_btn_container" style="margin-top:15px;text-align:right;">
								<button class="btn btn-theme normal-radius">Next<i class="fa fa-chevron-right icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>

				<div class="dash-panel theme">
					<div class="dash-panel-middle">
						<div style="margin-bottom:15px;">
							<p class="zero-gaps">Images</p>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> You can upload multiple images at once (max 5). Then select the main cover image of your product.</small>
						</div>
						<form action="" method="post" enctype="multipart/form-data" id="prod_image">
							<div class="input-group">
								<input type="file" class="form-control" required="required">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Upload<i class="fa fa-upload icon-right"></i></button>
								</span>
							</div>
							<div id="image_btn_container" style="margin-top:15px;text-align:right;">
								<button class="btn btn-theme normal-radius">Preview<i class="fa fa-eye icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>