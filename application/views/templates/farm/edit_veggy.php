<?php if ($data['product']): ?>
	<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
		<div class="dash-panel-right-container">
			<div class="mobile-note visible-xs">
				<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
			</div>
			<div class="dash-panel-right-canvas">
				<div class="col-lg-6">
					<div class="dash-panel theme">
						<ul class="spaced-list between dash-panel-top">
							<li><h3>New Veggie</h3></li>
						</ul>
						<form action="farm/save-veggy/<?php echo $data['product']['id'];?>/<?php echo $data['product']['name'];?>" method="post" enctype="multipart/form-data" class="form-validate" data-ajax="1">
							<div class="dash-panel-middle">
								<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
								<div class="form-group">
									<input type="text" name="products[name]" placeholder="Name" required="required" value="<?php echo $data['product']['name'];?>">
								</div>
								<div class="form-group">
									<textarea name="products[description]" placeholder="Description"><?php echo $data['product']['description'];?></textarea>
								</div>
								<div class="form-group">
									<select name="products[category_id]" required="required">
										<option value="">Category</option>
										<?php if ($this->categories): ?>
											<?php foreach ($this->categories as $key => $category): ?>
												<option value="<?php echo $category['id'];?>"<?php in_array_echo($data['product']['category_id'], [$category['id']], ' selected');?>><?php echo $category['label'];?></option>
											<?php endforeach ?>
										<?php endif ?>
									</select>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<label>Product Farm Locations</label>
										<div class="form-group">
											<?php if ($this->farm_locations): ?>
												<?php foreach ($this->farm_locations as $location): ?>
													<div js-element="address-panel">
														<p class="zero-gaps address_1"><?php echo $location['address_1'];?></p>
														<p class="zero-gaps"><small class="address_2"><?php echo $location['address_2'];?></small></p>
														<label class="switch">
															<?php 
															$checked = $measure = $price = $stocks = '';
															if (isset($data['product']['latlng'][$location['id']])) {
																$farm_loc = $data['product']['latlng'][$location['id']];
																$checked = $farm_loc['checked'];
																$measure = $farm_loc['measurement'];
																$price = $farm_loc['price'];
																$stocks = $farm_loc['stocks'];
															}
															?>
															<input type="checkbox" name="products_location[<?php echo $location['id'];?>][farm_location_id]" required="required" value="<?php echo $location['id'];?>" js-event="add-set"<?php echo $checked;?> />
															<span class="slider round"></span>
														</label>
													</div>
													<div class="form-group <?php if ($checked == ''): ?>hide<?php endif ?>" js-element="products-location-set">
														<select name="products_location[<?php echo $location['id'];?>][measurement]" class="chosen" required="required">
															<option value="">Select Measurement</option>
															<?php if ($this->measurements): ?>
																<?php foreach ($this->measurements as $measurement): ?>
																	<option value="<?php echo $measurement['value'];?>"<?php str_has_value_echo($measurement['value'], $measure, ' selected');?>><?php echo $measurement['label'];?></option>
																<?php endforeach ?>
															<?php endif ?>
														</select>
														<div class="input-group">
															<input type="text" name="products_location[<?php echo $location['id'];?>][price]" placeholder="Price" required="required" value="<?php echo $price;?>" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'">
															<input type="number" name="products_location[<?php echo $location['id'];?>][stocks]" placeholder="Stocks" required="required" value="<?php echo $stocks;?>">
														</div>
													</div>
												<?php endforeach ?>
											<?php endif ?>
										</div>
									</div>
								</div>
								<div class="form-group" id="new_veggy">
									<ul class="inline-list preview_images_list"></ul>
									<div class="input-group">
										<input type="file" class="form-control input_upload_images" data-name="products_photo" name="products_photo[]" required="required" multiple>
										<span class="input-group-btn">
											<button class="btn btn-default" type="button">Select<i class="fa fa-picture-o icon-right"></i></button>
										</span>
									</div>
									<?php if (isset($data['product']['photos']) AND $data['product']['photos']): ?>
										<ul class="inline-list preview_images_selected">
											<?php foreach ($data['product']['photos'] as $key => $photo): ?>
												<?php if ($key == 'main'): ?>
													<li data-toggle="tooltip" data-placement="top" title="" data-original-title="Select Image">
														<div class="preview-image-item" style="background-image: url('<?php echo $photo['url_path'];?>')"></div>
														<input type="radio" name="selected" value="<?php echo $photo['url_path'];?>" data-url-path="<?php echo $photo['url_path'];?>" checked>
													</li>
												<?php else: ?>
													<?php foreach ($photo as $pic): ?>
													<li data-toggle="tooltip" data-placement="top" title="" data-original-title="Select Image">
														<div class="preview-image-item" style="background-image: url('<?php echo $pic['url_path'];?>')"></div>
														<input type="radio" name="selected" value="<?php echo $pic['url_path'];?>" data-url-path="<?php echo $pic['url_path'];?>">
													</li>
													<?php endforeach ?>
												<?php endif ?>
											<?php endforeach ?>
										</ul>
									<?php endif ?>
								</div>
							</div>
							<div class="dash-panel-footer">
								<button>Edit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>