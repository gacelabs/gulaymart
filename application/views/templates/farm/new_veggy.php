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
					<form action="farm/new-veggy" method="post" enctype="multipart/form-data" class="form-validate" data-ajax="1">
						<div class="dash-panel-middle">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<input type="text" name="products[name]" placeholder="Name" required="required">
							<input type="text" name="products[old_price]" placeholder="Old Price" required="required">
							<input type="text" name="products[current_price]" placeholder="Price" required="required">
							<textarea name="products[description]" placeholder="Description"></textarea>
							<input type="text" name="products[procedure]" placeholder="Hydrophonic">
							<select name="products[measurement]" required="required">
								<option value="">Measurement</option>
								<option value="kg">Kilo</option>
							</select>
							<input type="number" name="products[stocks]" placeholder="Stocks" required="required">
							<select name="products[category_id]" required="required">
								<option value="">Category</option>
								<?php if ($this->categories): ?>
									<?php foreach ($this->categories as $key => $category): ?>
										<option value="<?php echo $category['id'];?>"><?php echo $category['label'];?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
							<label>Farm</label>
							<select name="products[location_id]" required="required">
								<?php if ($this->farms): ?>
									<?php foreach ($this->farms as $key => $farm): ?>
										<option value="<?php echo $farm['location_id'];?>"><?php echo $farm['farm_name'];?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
						</div>
						<div class="dash-panel-footer">
							<button>Add</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>