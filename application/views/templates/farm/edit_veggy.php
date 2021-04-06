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
										<label>Product Locations</label>
										<div class="form-group">
											<label>Select Farm</label>
											<?php if ($this->farm_locations): ?>
												<?php foreach ($this->farm_locations as $farm): ?>
													<div js-element="address-panel">
														<p class="zero-gaps address_1"><?php echo $farm['address_1'];?></p>
														<p class="zero-gaps"><small class="address_2"><?php echo $farm['address_2'];?></small></p>
														<label class="switch">
															<?php 
															$checked = ''; $measure = $price = $stocks = '';
															if ($data['product']['farms']) {
																foreach ($data['product']['farms'] as $farm_loc) {
																	if ($farm_loc['farm_id'] == $farm['farm_id']) {
																		$checked = ' checked';
																		$measure = $farm_loc['measurement'];
																		$price = $farm_loc['price'];
																		$stocks = $farm_loc['stocks'];
																		break;
																	}
																}
															}
															?>
															<input type="checkbox" name="products_location[farm_id][]" required="required" value="<?php echo $farm['farm_id'];?>" js-event="add-set"<?php echo $checked;?> />
															<span class="slider round"></span>
														</label>
													</div>
													<div class="form-group <?php if ($checked == ''): ?>hide<?php endif ?>" js-element="products-location-set">
														<label>Select Measurement</label>
														<select name="products_location[measurement][<?php echo $farm['farm_id'];?>]" class="chosen" required="required">
															<option value="">Measurement</option>
															<?php if ($this->measurements): ?>
																<?php foreach ($this->measurements as $measurement): ?>
																	<option value="<?php echo $measurement['value'];?>"<?php str_has_value_echo($measurement['value'], $measure, ' selected');?>><?php echo $measurement['label'];?></option>
																<?php endforeach ?>
															<?php endif ?>
														</select>
														<div class="input-group">
															<input type="text" name="products_location[price][<?php echo $farm['farm_id'];?>]" placeholder="Price" required="required" value="<?php echo $price;?>">
															<input type="number" name="products_location[stocks][<?php echo $farm['farm_id'];?>]" placeholder="Stocks" required="required" value="<?php echo $stocks;?>">
														</div>
													</div>
												<?php endforeach ?>
											<?php endif ?>
										</div>
									</div>
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