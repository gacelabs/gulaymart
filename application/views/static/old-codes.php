<!-- Storefront Contents -->
<div class="dash-panel theme">
	<form action="farm/storefront" method="post" class="form-validate storefront-forms" data-ajax="1">
		<input type="hidden" class="farm_id" name="user_farms[id]" value="<?php isset_echo($data['farms'], 'id');?>">
		<div class="dash-panel-top">
			<h3>Storefront Contents</h3>
			<ul class="spaced-list between" style="margin-top: 15px;">
				<li class="text-sm">
					<?php if (isset($data['farm_contents']) AND $data['farm_contents']): ?>
						<span class="color-grey">
							<i class="fa fa-calendar"></i> UPDATED
						</span><br><?php echo date('F j, Y', strtotime($data['farm_contents']['updated']));?>
					<?php else: ?>
						<span class="color-grey">
							<i class="fa fa-calendar"></i> TODAY
						</span><br><?php echo date('F j, Y');?>
					<?php endif ?>
				</li>
				<?php if (isset($data['farm_contents']) AND $data['farm_contents']): ?>
					<li><button type="submit" class="btn btn-blue normal-radius">Update</button></li>
				<?php else: ?>
					<li><button type="submit" class="btn btn-blue normal-radius">Create</button></li>
				<?php endif ?>
			</ul>
		</div>
		<div class="dash-panel-middle zero-gaps storefront_nav">
			<div class="custom-item-parent">
				<ul class="spaced-list between custom-item-btn">
					<li>PRODUCTS</li>
					<li><i class="fa fa-angle-right"></i></li>
				</ul>
				<?php
				$selected_products = [];
				if (isset($data['farm_contents']) AND isset($data['farm_contents']['products'])) {
					$selected_products = $data['farm_contents']['products'];
				}
				?>
				<div class="custom-item-child">
					<div class="form-group">
						<?php if (isset($data['products']) AND $data['products']): ?>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> What you want to sell </small>
							<label><input type="checkbox" class="select-all"<?php str_has_value_echo(count($selected_products), count($data['products']), ' checked');?>>Select All</label>
							<select name="user_farm_contents[products][]" id="products_section" class="form-control chosen" multiple="multiple" required="required">
								<?php foreach ($data['products'] as $key => $product): ?>
									<?php 
									$src = 'https://place-hold.it/50x50.png?text=No+Image&fontsize=7';
									if (isset($product['photos']) AND $product['photos']) {
										$src = base_url($product['photos']['main']['url_path']);
									}
									?>
									<option<?php in_array_echo($product['id'], $selected_products, ' selected');?> value="<?php echo $product['id'];?>" data-img-src="<?php echo $src;?>"><?php echo $product['name'];?> | <?php echo $product['price'];?></option>
								<?php endforeach ?>
							</select>
						<?php else: ?>
							<p><a href="farm/new-veggy">Go here to add your products</a></p>
						<?php endif ?>
					</div>
				</div>
			</div>

			<div class="custom-item-parent">
				<ul class="spaced-list between custom-item-btn">
					<li>STORIES</li>
					<li><i class="fa fa-angle-right"></i></li>
				</ul>
				<div class="custom-item-child">
					<div class="form-group">
						<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Story Title: </small>
						<input type="text" name="user_farm_contents[story_title]" id="story_title" class="input-keyup form-control" placeholder="Title" required="required" value="<?php isset_echo($data['farm_contents'], 'story_title');?>">
					</div>
					<div class="form-group">
						<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Write a story: </small>
						<textarea type="text" name="user_farm_contents[story_content]" class="form-control" placeholder="What keeps you going?" required="required"><?php isset_echo($data['farm_contents'], 'story_content');?></textarea>
					</div>
				</div>
			</div>

			<div class="custom-item-parent">
				<ul class="spaced-list between custom-item-btn">
					<li>GALLERY</li>
					<li><i class="fa fa-angle-right"></i></li>
				</ul>
				<?php
				$selected_galleries = [];
				if (isset($data['farm_contents']) AND isset($data['farm_contents']['galleries'])) {
					$selected_galleries = $data['farm_contents']['galleries'];
				}
				?>
				<div class="custom-item-child">
					<div class="form-group">
						<?php if (isset($data['galleries']) AND $data['galleries']): ?>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Share your great photos </small>
							<label><input type="checkbox" class="select-all"<?php str_has_value_echo(count($selected_galleries), count($data['galleries']), ' checked');?>>Select All</label>
							<select name="user_farm_contents[galleries][]" class="form-control chosen" multiple="multiple" required="required">
								<?php foreach ($data['galleries'] as $key => $gallery): ?>
									<option<?php in_array_echo($gallery['id'], $selected_galleries, ' selected');?> value="<?php echo $gallery['id'];?>" data-img-src="<?php echo base_url($gallery['url_path']);?>"><?php echo $gallery['name'];?></option>
								<?php endforeach ?>
							</select>
						<?php else: ?>
							<ul class="spaced-list between">
								<li><label>Add photos</label></li>
								<li class="text-link" data-toggle="modal" data-target="#media_modal">Media</li>
							</ul>
							<small class="color-grey"><i class="fa fa-exclamation-circle"></i> Minimum size: </small>
						<?php endif ?>
					</div>
				</div>
			</div>

			<div class="custom-item-parent">
				<ul class="spaced-list between custom-item-btn">
					<li>ABOUT</li>
					<li><i class="fa fa-angle-right"></i></li>
				</ul>
				<div class="custom-item-child">
					<div class="form-group">
						<textarea type="text" name="user_farm_contents[about]" class="form-control" placeholder="About your farm." required="required"><?php isset_echo($data['farm_contents'], 'about');?></textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>