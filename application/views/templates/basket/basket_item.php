
<?php
	$product = $item['rawdata']['product'];
	$details = $item['rawdata']['details'];
	$photo_url = 'https://via.placeholder.com/50x50.png?text=No+Image';
	if ($product['photos'] AND isset($product['photos']['main'])) {
		$photo_url = $product['photos']['main']['url_path'];
	}
?>
<div class="order-grid-column order-item" js-element="item-id-<?php echo $item['id'];?>" data-basket-id="<?php echo $item['id'];?>">
	<div class="media">
		<div class="media-left media-top">
			<img class="media-object" width="50" height="50" src="<?php echo $photo_url;?>">
		</div>
		<div class="media-body">
			<div class="ellipsis-container" style="height:20px;margin:0;-webkit-line-clamp:1;">
				<p class="zero-gaps media-heading"><a href="<?php product_url($product, true);?>"<?php if (!$this->agent->is_mobile()): ?> target="_blank"<?php endif ?> class="text-link"><?php echo ucwords($product['name']);?></a></p>
			</div>
			<div class="ellipsis-container">
				<p class="zero-gaps"><?php echo $product['description'];?></p>
			</div>
		</div>
	</div>
	<div class="text-right hidden-sm hidden-xs">
		<p class="zero-gaps">&#x20b1; <?php echo format_number($details['price']);?> / <?php echo $details['measurement'];?></p>
	</div>
	<div class="text-right hidden-sm hidden-xs">
		<div class="quantity buttons_added">
			<input type="button" value="-" class="minus" js-element="qty-btns"><input type="number" step="1" min="1" max="<?php echo $details['stocks'];?>" name="quantity" value="<?php echo $item['quantity'];?>" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" js-event="qty" js-id="<?php echo $item['id'];?>" js-price="<?php echo $details['price'];?>" /><input type="button" value="+" class="plus" js-element="qty-btns">
		</div>
	</div>
	<div class="text-right">
		<button class="btn btn-xs btn-default order-remove-btn<?php if (count($baskets['products']) == 1): ?> hide<?php endif ?>" js-event="removeBasketItemBtn" data-id="<?php echo $item['id'];?>" data-location="<?php echo $location_id;?>"><span class="text-danger">&times;</span></button>
	</div>

	<div class="visible-sm visible-xs">
		<ul class="spaced-list between">
			<li>
				<ul class="spaced-list between">
					<li><p class="zero-gaps">&#x20b1; <?php echo format_number($details['price']);?> / <?php echo $details['measurement'];?></p></li>
					<li class="icon-right">
						<div class="quantity buttons_added">
							<input type="button" value="-" class="minus" js-element="qty-btns"><input type="number" step="1" min="1" max="<?php echo $details['stocks'];?>" name="quantity" value="<?php echo $item['quantity'];?>" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" js-event="qty" js-id="<?php echo $item['id'];?>" js-price="<?php echo $details['price'];?>" /><input type="button" value="+" class="plus" js-element="qty-btns">
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</div>