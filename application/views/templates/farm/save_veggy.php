<?php if ($data['product']): ?>
	<div id="dashboard_panel_right">
		<?php $this->view('global/mobile_note'); ?>
		
		<div class="row hidden-xs" id="new_veggy">
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" id="score_detail_container">
				<form action="farm/save-veggy/<?php echo $data['product']['id'];?>/<?php echo $data['product']['name'];?>" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data">
					<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">

					<div class="dash-panel theme score-0" id="basic_prod_info">
						<div class="dash-panel-middle">
							<div class="input-container">
								<label for="product_name">Product name</label>
								<div class="input-group">
									<input type="text" class="form-control" id="product_name" name="products[name]" required="required" placeholder="Example: Fresh & Organic Romaine Lettuce [Per kilo]" value="<?php echo $data['product']['name'];?>">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" id="prod_name_checker"><i class="fa fa-chevron-right color-theme"></i></button>
									</span>
								</div>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Customers like a short yet concise Product name that tells essential details upfront.</small>
							</div>
							<div class="input-container" id="category_container" style="margin-bottom:0;">
								<label for="product_name">Category</label>
								<?php if ($this->categories): ?>
									<ul class="inline-list">
										<?php foreach ($this->categories as $key => $category): ?>
											<li class="input-capsule<?php in_array_echo($data['product']['category_id'], [$category['id']], ' active');?>">
												<input type="checkbox" name="products[category_id]" id="category-<?php echo $category['id'];?>" value="<?php echo $category['id'];?>"<?php in_array_echo($data['product']['category_id'], [$category['id']], ' checked');?>>
												<label for="category-<?php echo $category['id'];?>"><?php echo $category['label'];?></label>
											</li>
										<?php endforeach ?>
									</ul>
								<?php endif ?>
								<div class="arrow_box" id="sub_category">
									<p>Sub Category</p>
									<?php if ($this->subcategories): 
										$reset = array_keys($this->subcategories);
										$first = reset($reset);
										?>
										<?php foreach ($this->subcategories as $cat_id => $subcategory): ?>
											<ul class="inline-list<?php not_in_array_echo($data['product']['category_id'], [$cat_id], ' hide');?> category-<?php echo $cat_id;?>">
												<?php foreach ($subcategory as $key => $sub): ?>
													<li class="input-capsule<?php in_array_echo($data['product']['subcategory_id'], [$sub['id']], ' active');?>">
														<input type="radio" name="products[subcategory_id]" id="subcategory-<?php echo $sub['id'];?>" value="<?php echo $sub['id'];?>"<?php in_array_echo($data['product']['subcategory_id'], [$sub['id']], ' checked');?>>
														<label for="subcategory-<?php echo $sub['id'];?>"><?php echo $sub['label'];?></label>
													</li>
												<?php endforeach ?>
											</ul>
										<?php endforeach ?>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>

					<div class="dash-panel theme score-1" id="prod_attribute">
						<div class="dash-panel-middle">
							<div style="margin-bottom:15px;">
								<label>Product attributes</label>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Use preset to select an attribute or enter your own. Only letters and numbers are allowed.</small>
							</div>
							<?php if ($data['product']['attribute']): ?>
								<?php foreach ($data['product']['attribute'] as $key => $attr): ?>
									<input type="hidden" name="products_attribute[<?php echo $key;?>][id]" value="<?php echo $attr['id'];?>" />
									<div class="input-group">
										<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
										<input type="text" class="form-control" name="products_attribute[<?php echo $key;?>][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 ]*$'" required="required" placeholder="How do you grow your plant?" value="<?php echo $attr['attribute'];?>" />
										<div class="input-group-btn">
											<div class="dropdown">
												<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
													Preset <i class="fa fa-angle-down"></i>
												</button>
												<?php
												switch ($key) {
													case '0':?>
														<ul class="dropdown-menu pull-right">
															<li><a href="javascript:;">Home or commercially grown organically.</a></li>
															<li><a href="javascript:;">Traditional soil-based plant.</a></li>
															<li><a href="javascript:;">Grown using Hydrophonic technology.</a></li>
															<li><a href="javascript:;">Utilized Acquaphonic technology.</a></li>
															<li><a href="javascript:;">Used food-grade formulated plant grower.</a></li>
														</ul>
													<?php break;?><?php
													case '1'?>
														<ul class="dropdown-menu pull-right">
															<li><a href="javascript:;">Riped organically, tasty and juicy.</a></li>
															<li><a href="javascript:;">Sold unripe with roots intact.</a></li>
														</ul>
													<?php break;?><?php
													case '2'?>
														<ul class="dropdown-menu pull-right">
															<li><a href="javascript:;">In good shape, smell, texture, and color.</a></li>
															<li><a href="javascript:;">Slightly deformed, but presentable.</a></li>
														</ul>
													<?php break;?><?php
													case '3'?>
														<ul class="dropdown-menu pull-right">
															<li><a href="javascript:;">Picked same day upon order.</a></li>
															<li><a href="javascript:;">Freshly refrigirated.</a></li>
														</ul>
													<?php break;?><?php
													case '4'?>
														<ul class="dropdown-menu pull-right">
															<li><a href="javascript:;">Delivered in an eco-friendly pouch.</a></li>
															<li><a href="javascript:;">Packaged in a regular plastic bag.</a></li>
														</ul>
													<?php break;?><?php
												}
												?>
											</div>
										</div>
									</div>
									<?php endforeach ?>
							<?php else: ?>
								<div class="input-group">
									<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
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
									<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
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
									<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
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
									<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
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
									<span class="input-group-addon"><small><i class="fa fa-circle color-grey"></i></small></span>
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
							<?php endif ?>
						</div>
					</div>

					<div class="dash-panel theme score-2" id="prod_price">
						<div class="dash-panel-middle">
							<div style="margin-bottom:15px;">
								<label>Product Location Pricing</label>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Be honest with pricing and never put a stock you don't have on hand.</small>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<?php if ($this->farm_locations): ?>
										<?php foreach ($this->farm_locations as $index => $location): ?>
											<div class="row" js-element="address-panel" id="farmlocation-<?php echo $location['farm_id'];?>">
												<div class="col-lg-8 text-left">
													<p class="zero-gaps address_1"><?php echo $location['address_1'];?></p>
													<p class="zero-gaps"><small class="address_2"><?php echo $location['address_2'];?></small></p>
												</div>
												<?php 
												$checked = $price = $measure = $stocks = '';
												if (isset($data['product']['latlng'][$location['id']])) {
													$farm_loc = $data['product']['latlng'][$location['id']];
													$checked = $farm_loc['checked'];
													$price = $farm_loc['price'];
													$measure = $farm_loc['measurement'];
													$stocks = $farm_loc['stocks'];
												}
												?>
												<div class="col-lg-4 text-right">
													<label class="switch">
														<input type="checkbox" name="products_location[<?php echo $location['id'];?>][farm_location_id]" value="<?php echo $location['id'];?>" js-event="add-set"<?php echo $checked;?> />
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											<div class="row<?php str_has_value_echo($checked, null, ' hide');?>" js-element="products-location-set">
												<br>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="input-group">
														<span class="input-group-addon"><span class=" hidden-sm hidden-xs">&#x20b1;</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="Price"></i></span>
														<input type="text" class="form-control" name="products_location[<?php echo $location['id'];?>][price]" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" required="required" value="<?php echo $price;?>">
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="form-group">
														<select type="text" class="form-control" name="products_location[<?php echo $location['id'];?>][measurement]" required="required">
															<?php if ($this->measurements): ?>
																<?php foreach ($this->measurements as $key => $measurement): ?>
																	<option value="<?php echo $measurement['value'];?>"<?php str_has_value_echo($measurement['value'], $measure, ' selected');?>><?php echo $measurement['label'];?></option>
																<?php endforeach ?>
															<?php endif ?>
														</select>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
													<div class="input-group">
														<span class="input-group-addon"><span class=" hidden-sm hidden-xs">Stocks</span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="right" title="Stocks"></i></span>
														<input type="number" maxlength="3" class="form-control" name="products_location[<?php echo $location['id'];?>][stocks]" required="required" value="<?php echo $stocks;?>">
													</div>
												</div>
											</div>
											<hr>
										<?php endforeach ?>
									<?php else: ?>
										<p>No Farms yet.</p>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>

					<div class="dash-panel theme score-3" id="prod_desc">
						<div class="dash-panel-middle">
							<div style="margin-bottom:15px;">
								<label>Short description</label>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Write your product description here. Limit 300 characters.</small>
							</div>
							<div class="form-group">
								<textarea class="form-control" rows="5" name="products[description]" required="required"><?php echo $data['product']['description'];?></textarea>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class=" hidden-sm hidden-xs">What's in the bag?</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="What's in the bag?"></i></span>
								<input type="text" class="form-control" name="products[inclusion]" required="required" value="<?php echo $data['product']['description'];?>" value="<?php echo $data['product']['inclusion'];?>" />
							</div>
						</div>
					</div>

					<div class="dash-panel theme score-4" id="products_photo">
						<div class="dash-panel-middle">
							<div style="margin-bottom:15px;">
								<label>Images</label>
								<small class="color-grey"><i class="fa fa-exclamation-circle"></i> You can upload multiple images at once (max 5). Then select the main cover image of your product.</small>
							</div>
							<ul class="inline-list preview_images_list"></ul>
							<div class="input-group">
								<input type="file" class="form-control input_upload_images" data-name="products_photo" name="products_photo[]" multiple>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Select<i class="fa fa-picture-o icon-right"></i></button>
								</span>
							</div>
							<?php if (isset($data['product']['photos']) AND $data['product']['photos']): ?>
								<br>
								<ul class="inline-list preview_images_selected">
									<?php foreach ($data['product']['photos'] as $key => $photo): ?>
										<?php if ($key == 'main'): ?>
											<li data-toggle="tooltip" data-placement="top" title="" data-original-title="Select Image">
												<div class="preview-image-item" style="background-image: url('<?php echo $photo['url_path'];?>')"></div>
												<input type="radio" name="products_photo[id]" value="<?php echo $photo['id'];?>" checked>
											</li>
										<?php else: ?>
											<?php foreach ($photo as $pic): ?>
											<li data-toggle="tooltip" data-placement="top" title="" data-original-title="Select Image">
												<div class="preview-image-item" style="background-image: url('<?php echo $pic['url_path'];?>')"></div>
												<input type="radio" name="products_photo[id]" value="<?php echo $pic['id'];?>">
											</li>
											<?php endforeach ?>
										<?php endif ?>
									<?php endforeach ?>
								</ul>
							<?php endif ?>
						</div>
						<div class="dash-panel-footer text-right bg-grey">
							<button value="upload" type="submit" class="btn btn-default normal-radius hide">Upload & Save<i class="fa fa-upload icon-right"></i></button>
							<button value="select" type="submit" class="btn btn-default normal-radius">Save<i class="fa fa-check-circle icon-right"></i></button>
						</div>
					</div>
				</form>
			</div>

			<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" id="preview_container">
				<div class="dash-panel">
					<?php if ($this->farm_locations): ?>
						<?php foreach ($this->farm_locations as $index => $location): ?>
							<?php 
							$checked = $price = $measure = $stocks = $duration = '';
							if (isset($data['product']['latlng'][$location['id']])) {
								$farm_loc = $data['product']['latlng'][$location['id']];
								$checked = $farm_loc['checked'];
								$price = $farm_loc['price'];
								$measure = $farm_loc['measurement'];
								$stocks = $farm_loc['stocks'];
								$duration = $farm_loc['duration'];
							} ?>
							<div class="dash-panel-top">
								<ul class="spaced-list between">
									<li><h6><?php echo $location['address_2'];?></h6></li>
									<li><h3><a href="basket/view/<?php echo $data['product']['id'].'/'.nice_url($data['product']['name'], true);?>" target="_new" class="text-link order-link">View Page</a></h3></li>
								</ul>
								<div class="product-item-info">
									<?php if (isset($data['product']['photos']) AND $data['product']['photos']): ?>
										<div class="product-item-top order-photo" style="background-image:url('<?php echo $data['product']['photos']['main']['url_path'];?>');">
									<?php else: ?>
										<div class="product-item-top order-photo" style="background-image:url('https://via.placeholder.com/220x220?text=Product+photo+shows+here');">
									<?php endif ?>
									<ul class="spaced-list between">
										<?php if ($duration != ''): ?>
											<li><kbd class="product-tags"><small><i class="fa fa-map-marker"></i> <span class="order-duration"><?php echo $duration;?></span></small></kbd></li>
										<?php endif ?>
										<!-- <li><kbd class="product-type"><small><i class="fa fa-pagelines"></i> <span class="order-type">Organic</span></small></kbd></li> -->
									</ul>
									</div>
									<div class="product-item-middle">
										<h1 class="product-title order-title"><?php echo $data['product']['name'];?></h1>
										<p class="product-price">&#x20b1; <span class="order-price"><?php echo $price;?></span> / <span class="order-unit"><?php echo $measure;?></span></p>
									</div>
									<h5 class="text-center" style="border:1px solid #ea9a2a;border-radius:3px;padding:10px;"><b>Status:</b> <span class="order-status">Published</span></h5>
								</div>
							</div>
						<?php endforeach ?>
					<?php else: ?>
						<p>No Farms yet.</p>
					<?php endif; ?>
					<div class="dash-panel-footer" style="background-color:#f7f7f7;border-bottom:1px solid #ccc;">
						<a href="farm/new-veggy" class="btn btn-info normal-radius btn-block" style="width:220px;margin:0 auto;">Create New Veggy</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>