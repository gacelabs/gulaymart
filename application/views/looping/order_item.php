
<?php
	$photo_url = 'https://via.placeholder.com/50x50.png?text=No+Image';
	$product = $order['product'];
	if ($product['photos'] AND isset($product['photos']['main'])) {
		$photo_url = $product['photos']['main']['url_path'];
	}
	$details = $order; unset($details['product']);
	$details['merge_id'] = $orders['id'];
	$details['basket_ids'] = $orders['basket_ids'];
	$json = json_encode([
		'product_id'=>$order['product_id'],
		'location_id'=>$order['farm_location_id'],
		'merge_id'=>$orders['id'],
		'basket_id'=>$order['basket_id'],
	]);
?>
<div class="order-grid-column order-item<?php str_has_value_echo(5, $details['status'], ' was-cancelled');?>" js-element="item-id-<?php echo $orders['id'];?>-<?php echo $product['id'];?>">
	<div class="media">
		<div class="media-left media-top">
			<img class="media-object" width="50" height="50" src="<?php echo $photo_url;?>">
		</div>
		<div class="media-body">
			<p class="zero-gaps media-heading text-ellipsis"><a<?php if (!$this->agent->is_mobile()): ?> target="_blank"<?php endif ?> href="<?php product_url($product, true);?>" class="text-link"><?php echo $product['name'];?></a></p>
			<div class="ellipsis-container">
				<p class="zero-gaps"><?php echo $product['description'];?></p>
			</div>
		</div>
	</div>
	<div class="text-right hidden-sm hidden-xs">
		<p class="zero-gaps">&#x20b1; <?php echo format_number($order['price']);?> / <?php echo $order['measurement'];?></p>
	</div>
	<div class="text-right hidden-sm hidden-xs">
		<p class="zero-gaps"><?php echo $order['quantity'];?></p>
	</div>
	<?php if ($details['status'] == 2): ?>
		<div class="text-right">
			<button class="btn btn-xs btn-default order-remove-btn" js-element="remove-product" data-json='<?php echo $json;?>'><span class="text-danger">&times;</span></button>
		</div>
	<?php endif ?>

	<div class="visible-sm visible-xs">
		<ul class="spaced-list between">
			<li><p class="zero-gaps">&#x20b1; <?php echo format_number($order['price']);?> / <?php echo $order['measurement'];?></p></li>
			<li class="icon-right"><p class="zero-gaps">x <?php echo $order['quantity'];?> QTY</p></li>
		</ul>
	</div>
</div>