<?php if ($data['product']): ?>
	<div id="dashboard_panel_right">
		<?php $this->view('global/mobile_note'); ?>
	
		<div class="row" id="new_veggy">
			<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
				<div class="dashboard-panel theme">
					<ul class="spaced-list between dashboard-panel-top">
						<li><h3>New Veggie</h3></li>
					</ul>
					<form action="farm/edit/<?php echo $data['product']['id'];?>" method="post" enctype="multipart/form-data" class="form-validate" data-ajax="1">
						<div class="dashboard-panel-middle">
							<input type="hidden" name="products[user_id]" value="<?php echo $current_profile['id'];?>">
							<input type="text" name="products[name]" placeholder="Name" required="required" value="<?php echo $data['product']['name'];?>">
							<input type="text" name="products[old_price]" placeholder="Old Price" required="required" value="<?php echo $data['product']['old_price'];?>">
							<input type="text" name="products[price]" placeholder="Price" required="required" value="<?php echo $data['product']['price'];?>">
							<textarea name="products[description]" placeholder="Description"><?php echo $data['product']['description'];?></textarea>
							<select name="products[measurement]" required="required">
								<option value="">Measurement</option>
								<?php if ($this->measurements): ?>
									<?php foreach ($this->measurements as $key => $measurement): ?>
										<option value="<?php echo $measurement['value'];?>"<?php in_array_echo($data['product']['measurement'], [$measurement['value']], ' selected');?>><?php echo $measurement['label'];?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
							<input type="number" name="products[stocks]" placeholder="Stocks" required="required" value="<?php echo $data['product']['stocks'];?>">
							<select name="products[category_id]" required="required">
								<option value="">Category</option>
								<?php if ($this->categories): ?>
									<?php foreach ($this->categories as $key => $category): ?>
										<option value="<?php echo $category['id'];?>"<?php in_array_echo($data['product']['category_id'], [$category['id']], ' selected');?>><?php echo $category['label'];?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select>
							<!-- <label>Farm</label>
							<select name="products[location_id]" required="required">
								<?php if ($this->farms): ?>
									<?php foreach ($this->farms as $key => $farm): ?>
										<option value="<?php echo $farm['location_id'];?>"<?php in_array_echo($data['product']['location_id'], [$farm['location_id']], ' selected');?>><?php echo $farm['name'];?></option>
									<?php endforeach ?>
								<?php endif ?>
							</select> -->
						</div>
						<div class="dashboard-panel-footer">
							<button>Edit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>