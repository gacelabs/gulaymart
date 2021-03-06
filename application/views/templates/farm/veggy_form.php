<div id="dashboard_panel_right">
	<?php // $this->view('global/mobile_note'); ?>

		<div class="row" id="new_veggy">

			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" id="score_detail_container">
				<?php if ($data['is_edit'] == false): ?>
					<div class="dashboard-panel" id="score-detail-panel">
						<div class="dashboard-panel-top">
							<h4 class="zero-gaps">Product Details Score</h4>
						</div>
						<div class="dashboard-panel-middle">
							<ul class="spaced-list around">
								<li><p class="text-capsule">10<small style="display:inline;">%</small></p></li>
								<li><p class="text-capsule">30<small style="display:inline;">%</small></p></li>
								<li><p class="text-capsule">60<small style="display:inline;">%</small></p></li>
								<li><p class="text-capsule">80<small style="display:inline;">%</small></p></li>
								<li><p class="text-capsule"><i class="fa fa-check"></i></p></li>
							</ul>
							<div class="timeline-border"></div>
							<div class="timeline-border-progress" data-percent="0"></div>
						</div>
					</div>
				<?php endif ?>

				<div class="dashboard-panel theme score-0" id="score-0">
					<div class="dashboard-panel-middle">
						<form action="<?php echo uri_string();?>/" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="basic_prod_info">
							<input type="hidden" name="pos" value="0">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="input-container">
								<div>
									<label for="product_name">Product name</label>
									<small class="text-gray" style="margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Customers like a short yet concise Product name that tells essential details upfront.</small>
								</div>
								<div class="input-group">
									<input type="text" class="form-control" id="product_name" name="products[name]" required="required" placeholder="Example: Fresh & Organic Romaine Lettuce [Per kilo]" value="<?php check_value('name', $data['product']);?>">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" id="prod_name_checker"><i class="fa fa-chevron-right color-theme"></i></button>
									</span>
								</div>
							</div>
							<div class="input-container<?php if ($data['is_edit'] == false): ?> hide<?php endif ?>" id="category_container" style="margin-bottom:0;">
								<label for="product_name">Category</label>
								<small class="text-gray" style="margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Not sure what category your veggy fits in, do you <span class="text-link" data-toggle="modal" data-target="#veggie_category_help">NEED HELP?</span></small>
								<?php if ($this->categories): ?>
									<ul class="inline-list">
										<?php foreach ($this->categories as $key => $category): ?>
											<?php if ($data['is_edit']): ?>
												<li class="input-capsule<?php in_array_echo($data['product']['category_id'], [$category['id']], ' active');?>">
													<input type="checkbox" name="products[category_id]" id="category-<?php echo $category['id'];?>" value="<?php echo $category['id'];?>"<?php in_array_echo($data['product']['category_id'], [$category['id']], ' checked');?>>
													<label for="category-<?php echo $category['id'];?>"><?php echo $category['label'];?></label>
												</li>
											<?php else: ?>
												<li class="input-capsule">
													<input type="checkbox" name="products[category_id]" id="category-<?php echo $category['id'];?>" value="<?php echo $category['id'];?>">
													<label for="category-<?php echo $category['id'];?>"><?php echo $category['label'];?></label>
												</li>
											<?php endif ?>
										<?php endforeach ?>
									</ul>
								<?php endif ?>
								<div class="<?php if ($data['is_edit'] == false): ?>hide<?php endif ?> arrow_box" id="sub_category">
									<p>Sub Category</p>
									<?php if ($this->subcategories): 
										$reset = array_keys($this->subcategories);
										$first = reset($reset);
										?>
										<?php foreach ($this->subcategories as $cat_id => $subcategory): ?>
											<?php if ($data['is_edit']): ?>
												<ul class="inline-list<?php not_in_array_echo($data['product']['category_id'], [$cat_id], ' hide');?> category-<?php echo $cat_id;?>">
													<?php foreach ($subcategory as $key => $sub): ?>
														<li class="input-capsule<?php in_array_echo($data['product']['subcategory_id'], [$sub['id']], ' active');?>">
															<input type="radio" name="products[subcategory_id]" id="subcategory-<?php echo $sub['id'];?>" value="<?php echo $sub['id'];?>"<?php in_array_echo($data['product']['subcategory_id'], [$sub['id']], ' checked');?>>
															<label for="subcategory-<?php echo $sub['id'];?>"><?php echo $sub['label'];?></label>
														</li>
													<?php endforeach ?>
												</ul>
											<?php else: ?>
												<ul class="inline-list hide category-<?php echo $cat_id;?>">
													<?php foreach ($subcategory as $key => $sub): ?>
														<li class="input-capsule"><input type="radio" name="products[subcategory_id]" id="subcategory-<?php echo $sub['id'];?>" value="<?php echo $sub['id'];?>"><label for="subcategory-<?php echo $sub['id'];?>"><?php echo $sub['label'];?></label></li>
													<?php endforeach ?>
												</ul>
											<?php endif ?>
										<?php endforeach ?>
									<?php endif ?>
								</div>
								<div id='basic_btn_container' style='margin-top:15px;text-align:right;<?php if ($data['is_edit'] == false): ?> display:none;<?php endif ?>'>
									<?php if ($data['is_edit']): ?>
										<input type="reset" class="btn btn-default icon-left" value="Reset" />
									<?php endif ?>
									<button class='btn btn-theme' type="submit" loading-text>Next<i class='fa fa-chevron-right icon-right'></i></button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme<?php if ($data['is_edit'] == false): ?> hide<?php endif ?> score-1" id="score-1">
					<div class="dashboard-panel-middle">
						<div>
							<label>Product attributes</label>
							<small class="text-gray" style="margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Use preset to select an attribute or enter your own. Only letters and numbers are allowed.</small>
						</div>
						<form action="<?php echo uri_string();?>/" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="prod_attribute">
							<input type="hidden" name="pos" value="1">
							<?php foreach ($this->attributes as $key => $attribute): ?>
								<?php
									$id = $attributevalue = '';
									if ($data['product'] AND isset($data['product']['attribute'][$key])) {
										$id = check_value('id', $data['product']['attribute'][$key], false, false);
										$attributevalue = check_value('attribute', $data['product']['attribute'][$key], false, false);
									}
								?>
								<input type="hidden" name="products_attribute[<?php echo $key;?>][id]" value="<?php echo $id;?>" />
								<div class="input-group">
									<span class="input-group-addon"><small><i class="fa fa-circle text-gray"></i></small></span>
									<input type="text" class="form-control" name="products_attribute[<?php echo $key;?>][attribute]" data-inputmask="'regex': '^[A-Za-z0-9 -]*$'" required="required" placeholder="<?php echo $attribute['placeholder']; ?>" value="<?php echo $attributevalue;?>" />
									<div class="input-group-btn">
										<div class="dropdown">
											<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
												Preset <i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu pull-right">
												<?php foreach ($attribute['data'] as $index => $attr): ?>
													<li><a href="javascript:;"><?php echo $attr['value'];?></a></li>
												<?php endforeach ?>
											</ul>
										</div>
									</div>
								</div>
							<?php endforeach ?>
							<?php if ($data['is_edit']): ?>
								<div id='attr_btn_container' style='margin-top:15px;text-align:right;'>
									<input type="reset" class="btn btn-default icon-left" value="Reset" />
									<button class='btn btn-theme normal-radius' type="submit" loading-text>Next<i class='fa fa-chevron-right icon-right'></i></button>
								</div>
							<?php endif ?>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme<?php if ($data['is_edit'] == false): ?> hide<?php endif ?> score-2" id="score-2">
					<div class="dashboard-panel-middle">
						<div style="margin-bottom:15px;">
							<label>Product Pricing (Location based)</label>
							<small class="text-gray" style="margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Be honest with pricing and never put a stock you don't have on hand. <span class="text-link" data-toggle="modal" data-target="#veggy_product_pricing_help">NEED HELP?</span></small>
						</div>
						<form action="<?php echo uri_string();?>/" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="prod_price">
							<input type="hidden" name="pos" value="2">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="row">
								<div class="col-lg-12 text-caps">
									<?php if ($this->farm_locations): ?>
										<?php foreach ($this->farm_locations as $location): ?>
											<div class="row" id="farmlocation-<?php echo $location['id'];?>">
												<div class="col-lg-7 col-xs-9 text-left">
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
												}?>
												<div class="col-lg-5 col-xs-3 text-right">
													<label class="switch">
														<?php if ($data['is_edit'] == false): ?>
															<input type="checkbox" name="products_location[<?php echo $location['id'];?>][farm_location_id]" value="<?php echo $location['id'];?>" js-event="new-set" />
														<?php else: ?>
															<input type="checkbox" name="products_location[<?php echo $location['id'];?>][farm_location_id]" value="<?php echo $location['id'];?>" js-event="add-set"<?php echo $checked;?> />
														<?php endif ?>
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											<?php if ($data['is_edit'] == false): ?>
											<div class="row hide" js-element="pricing-panel">
												<?php else: ?>
											<div class="row<?php str_has_value_echo($checked, null, ' hide');?>" js-element="products-location-set">
											<?php endif ?>
												<br>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-right: 0;">
													<div class="input-group">
														<span class="input-group-addon"><span class="hidden-sm hidden-xs">&#x20b1;</span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="top" title="Price"></i></span>
														<input type="text" class="form-control" name="products_location[<?php echo $location['id'];?>][price]" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" value="<?php echo $price;?>">
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-right: 0; padding-left: 5px;">
													<div class="input-group">
														<span class="input-group-addon"><span class="hidden-sm hidden-xs">Unit</span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="top" title="Unit"></i></span>
														<select type="text" class="form-control" name="products_location[<?php echo $location['id'];?>][measurement]" style="padding: 6px 10px;">
															<?php if ($this->measurements): ?>
																<?php foreach ($this->measurements as $key => $measurement): ?>
																	<?php if ($data['is_edit'] == false): ?>
																		<?php if ($this->agent->is_mobile()): ?>
																			<option value="<?php echo $measurement['value'];?>"><?php echo $measurement['abbrv'].' ('.$measurement['label'].')';?></option>
																		<?php else: ?>
																			<option value="<?php echo $measurement['value'];?>"><?php echo $measurement['label'];?></option>
																		<?php endif ?>
																	<?php else: ?>
																		<?php if ($this->agent->is_mobile()): ?>
																			<option value="<?php echo $measurement['value'];?>"<?php str_has_value_echo($measurement['value'], $measure, ' selected');?>><?php echo $measurement['abbrv'].' ('.$measurement['label'].')';?></option>
																		<?php else: ?>
																			<option value="<?php echo $measurement['value'];?>"<?php str_has_value_echo($measurement['value'], $measure, ' selected');?>><?php echo $measurement['label'];?></option>
																		<?php endif ?>
																	<?php endif ?>
																<?php endforeach ?>
															<?php endif ?>
														</select>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-left: 5px;">
													<div class="input-group">
														<span class="input-group-addon"><span class="hidden-sm hidden-xs">Stocks</span><i class="fa fa-question-circle visible-xs" data-toggle="tooltip" data-placement="top" title="Stocks"></i></span>
														<input type="number" maxlength="3" class="form-control" name="products_location[<?php echo $location['id'];?>][stocks]" value="<?php echo $stocks;?>">
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
							<div id="price_btn_container" style="text-align:right;">
								<?php if ($data['is_edit']): ?>
									<input type="reset" class="btn btn-default icon-left" value="Reset" />
								<?php endif ?>
								<button class="btn btn-theme" type="submit" loading-text>Next<i class="fa fa-chevron-right icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme<?php if ($data['is_edit'] == false): ?> hide<?php endif ?> score-3" id="score-3">
					<div class="dashboard-panel-middle">
						<div>
							<label>Short description</label>
							<small class="text-gray" style="margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Write your product description here. Limit 300 characters.</small>
						</div>
						<form action="<?php echo uri_string();?>/" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="prod_desc">
							<input type="hidden" name="pos" value="3">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="form-group">
								<textarea class="form-control" rows="5" name="products[description]" required="required"><?php check_value('description', $data['product']);?></textarea>
							</div>
							<div class="input-group">
								<span class="input-group-addon"><span class="hidden-sm hidden-xs">What's in the bag?</span><i class="fa fa-question-circle hidden-lg hidden-md" data-toggle="tooltip" data-placement="right" title="What's in the bag?"></i></span>
								<input type="text" class="form-control" name="products[inclusion]" required="required" value="<?php check_value('inclusion', $data['product']);?>">
							</div>
							<div id="desc_btn_container" style="margin-top:15px;text-align:right;">
								<?php if ($data['is_edit']): ?>
									<input type="reset" class="btn btn-default icon-left" value="Reset" />
								<?php endif ?>
								<button class="btn btn-theme" type="submit" loading-text>Next<i class="fa fa-chevron-right icon-right"></i></button>
							</div>
						</form>
					</div>
				</div>

				<div class="dashboard-panel theme<?php if ($data['is_edit'] == false): ?> hide<?php endif ?> score-4" id="score-4">
					<form action="<?php echo uri_string();?>/" method="post" class="form-validate" data-ajax="1" data-disable="enter" enctype="multipart/form-data" id="products_photo" data-notmedia="1">
						<input type="hidden" name="pos" value="4">
						<div class="dashboard-panel-middle">
							<div>
								<label>Images</label>
								<small class="text-gray" style="margin-bottom: 5px;"><i class="fa fa-exclamation-circle"></i> Upload multiple images at once (max 5). Then select the main photo for your product. Recommended size 600 x 600 pixels.</small>
							</div>
							<ul class="inline-list preview_images_list"></ul>
							<div class="input-group">
								<input type="file" class="form-control input_upload_images" name="products_photo[]"<?php if ($data['is_edit'] == false): ?> required="required"<?php endif ?> multiple>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Select<i class="fa fa-folder-open-o icon-right"></i></button>
								</span>
							</div>
							<?php if ($data['is_edit']): ?>
								<?php if (isset($data['product']['photos']) AND $data['product']['photos']): ?>
									<br>
									<ul class="inline-list preview_images_selected">
										<?php foreach ($data['product']['photos'] as $key => $photo): ?>
											<?php if ($key == 'main'): ?>
												<li data-toggle="tooltip" data-placement="top" title="" data-original-title="Select Image">
													<div class="preview-image-item" style="background-image: url('<?php echo $photo['url_path'];?>')"></div>
													<input type="radio" name="products_photo[id]" value="<?php echo $photo['id'];?>" checked data-url-path="<?php echo $photo['url_path'];?>">
												</li>
											<?php else: ?>
												<?php foreach ($photo as $pic): ?>
												<li data-toggle="tooltip" data-placement="top" title="" data-original-title="Select Image">
													<div class="preview-image-item" style="background-image: url('<?php echo $pic['url_path'];?>')"></div>
													<input type="radio" name="products_photo[id]" value="<?php echo $pic['id'];?>" data-url-path="<?php echo $pic['url_path'];?>">
												</li>
												<?php endforeach ?>
											<?php endif ?>
										<?php endforeach ?>
									</ul>
								<?php endif ?>
							<?php endif ?>
						</div>
						<div class="dashboard-panel-footer text-right bg-grey">
							<!-- <button value="0" name="activity" class="btn btn-default">Draft<i class="fa fa-floppy-o icon-right"></i></button> -->
							<button value="0" name="activity" type="submit" class="btn btn-contrast icon-right">Submit for Approval<i class="fa fa-check icon-right"></i></button>
						</div>
					</form>
				</div>
			</div>

			<?php $this->view('static/preview_listing', ['data' => $data]); ?>
		</div>
	</div>
</div>