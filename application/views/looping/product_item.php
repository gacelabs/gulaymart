<?php if (isset($data['category'])): ?>
<div class="product-item panel" data-category="<?php echo $id;?>" <?php !isset($forajax) ? str_not_value_echo('all', $id, 'style="display: none;"') : '';?>>
	<div class="product-item-info">
		<a href="">
			<div class="product-item-top" style="background-image: url('assets/images/onions.jpg');">
				<ul class="spaced-list between">
					<li><kbd class="product-tags"><small><i class="fa fa-map-marker"></i> 26 mins</small></kbd></li>
					<li><kbd class="product-type"><small><i class="fa fa-pagelines"></i> <?php echo $data['procedure'];?></small></kbd></li>
				</ul>
			</div>
			<div class="product-item-middle">
				<h1 class="product-title"><?php echo $name;?></h1>
				<p class="product-price">&#x20b1; <?php echo $current_price;?> / <?php echo $measurement;?></p>
			</div>
		</a>
	</div>
</div>
<?php endif ?>