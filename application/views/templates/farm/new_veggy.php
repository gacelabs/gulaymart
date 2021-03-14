<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="mobile-note visible-xs">
			<small><i class="fa fa-info-circle"></i> <span>Some functions may not be available on mobile screens. Please use a desktop or a laptop.</span></small>
		</div>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-6">
				<div class="dash-panel theme">
					<ul class="spaced-list between dash-panel-top">
						<li><h3>New Veggy</h3></li>
					</ul>
					<form action="farm/new-veggy" method="post" enctype="multipart/form-data" class="form-validate" data-ajax="1">
						<div class="dash-panel-middle">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<div class="form-group">
								<input type="text" class="form-control" name="products[name]" placeholder="Name" required="required">
							</div>
							<div class="form-group">
								<textarea class="form-control" name="products[description]" placeholder="Description" rows="5"></textarea>
							</div>
							<div class="row">
								<div class="col-lg-3">
									<div class="form-group">
										<select class="form-control" name="products[category_id]" required="required">
											<option value="">Category</option>
											<?php if ($this->categories): ?>
												<?php foreach ($this->categories as $key => $category): ?>
													<option value="<?php echo $category['id'];?>"><?php echo $category['label'];?></option>
												<?php endforeach ?>
											<?php endif ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<input type="text" class="form-control" name="products[procedure]" placeholder="Hydrophonic">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<select class="form-control" name="products[location_id]" required="required">
											<?php if ($this->farms): ?>
												<?php foreach ($this->farms as $key => $farm): ?>
													<option value="<?php echo $farm['location_id'];?>"><?php echo $farm['farm_name'];?></option>
												<?php endforeach ?>
											<?php endif ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3">
									<div class="form-group">
										<select class="form-control" name="products[measurement]" required="required">
											<option value="">Measurement</option>
											<?php if ($this->measurements): ?>
												<?php foreach ($this->measurements as $key => $measurement): ?>
													<option value="<?php echo $measurement['value'];?>"><?php echo $measurement['label'];?></option>
												<?php endforeach ?>
											<?php endif ?>
										</select>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<input type="number" class="form-control" name="products[stocks]" placeholder="Stocks" required="required">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<input type="text" class="form-control" name="products[old_price]" placeholder="Old Price" required="required">
									</div>
								</div>
								<div class="col-lg-3">
									<div class="form-group">
										<input type="text" class="form-control" name="products[current_price]" placeholder="Price" required="required">
									</div>
								</div>
							</div>
						</div>
						<div class="dash-panel-footer text-right">
							<ul class="spaced-list between">
								<li>
									<a href="farm/new-veggy/" class="btn btn-default normal-radius"><i class="fa fa-trash icon-left"></i>Clear</a>
								</li>
								<li>
									<button class="btn btn-warning normal-radius icon-left"><i class="fa fa-pencil-square-o icon-left"></i>Draft</button>
									<button class="btn btn-theme normal-radius"><i class="fa fa-send icon-left"></i>Post</button>
								</li>
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>