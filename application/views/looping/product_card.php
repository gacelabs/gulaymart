<?php if (isset($data['category'])): ?>
	<div class="product-list-card" data-category="<?php echo $id;?>">
		<a href="<?php echo $data['product_url'];?>">
			<div class="product-list-photo" style="background-image: url(<?php identify_main_photo($data);?>);"></div>
			<div class="product-desc-body">
				<div class="product-title-container ellipsis-container">
					<h1 class="zero-gaps"><?php echo $data['name'];?></h1>
				</div>
				<div class="product-list-footer">
					<ul class="spaced-list between">
						<li><p class="product-price">&#x20b1; <?php echo $data['price'];?> / <?php echo $data['measurement'];?></p></li>
						<?php if (isset($data['distance']) AND isset($data['duration'])): ?>
							<li><p class="product-distance" data-toggle="tooltip" data-placement="top" title="Estimated distance from you."><i class="fa fa-clock-o"></i> <?php echo ucwords($data['duration']);?></p></li>
						<?php endif ?>
					</ul>
				</div>
			</div>
		</a>
	</div>
<?php endif ?>