<?php if (isset($data['category'])): ?>
	<div class="product-list-card" data-category="<?php echo $id;?>">
		<a href="<?php echo $data['product_url'];?>">
			<?php if (isset($data['photos']) AND isset($data['photos']['main'])): ?>
				<div class="product-list-photo" style="background-image: url(<?php echo base_url($data['photos']['main']['url_path']);?>);"></div>
			<?php else: ?>
				<div class="product-list-photo" style="background-image: url(https://place-hold.it/360x360.png?text=Product+Image+Unavailable&fontsize=14);"></div>
			<?php endif ?>
			<div class="product-desc-body">
				<div class="product-title-container ellipsis-container">
					<h1 class="zero-gaps"><?php echo $data['name'];?></h1>
				</div>
				<div class="product-list-footer">
					<ul class="spaced-list between">
						<li><p class="product-price">&#x20b1; <?php echo $data['price'];?> / <?php echo $data['measurement'];?></p></li>
						<?php if (isset($data['distance']) AND isset($data['duration'])): ?>
							<li><p class="product-distance" data-toggle="tooltip" data-placement="top" title="Estimated distance from you."><i class="fa fa-clock-o"></i> <?php echo $data['duration'];?></p></li>
						<?php endif ?>
					</ul>
				</div>
			</div>
		</a>
	</div>
<?php endif ?>