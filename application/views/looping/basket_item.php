<div class="add-basket-item-container order-item-inner">
	<p class="zero-gaps text-caps"><?php echo $product['rawdata']['farm']['name'];?></p>
	<div class="order-item-grid">
		<ul class="spaced-list between">
			<li><input type="checkbox" class="add-basket-item-select" js-event="addBasketItemselect"></li>
			<li><div class="order-item-image" style="background-image: url('<?php echo $product['rawdata']['photo']['url_path'];?>');"></div></li>
		</ul>
		<div class="order-info-container" js-element="order">
			<div class="order-item-title">
				<p><a href="<?php echo $product['rawdata']['product_url'];?>" class="text-link"><?php echo $product['rawdata']['description'];?></a></p>
			</div>
			<p class="zero-gaps">
				&#x20b1; <b><?php echo number_format($product['rawdata']['basket_details']['price']);?></b> / <?php echo $product['rawdata']['basket_details']['measurement'];?> 
				<span class="qty-divider">x Quantity: 
					<input type="number" name="order-qty-input" class="order-qty-input" value="<?php echo $product['quantity'];?>" min="1" max="<?php echo $product['rawdata']['basket_details']['stocks'];?>" js-event="qty" js-price="<?php echo $product['rawdata']['basket_details']['price'];?>" js-id="<?php echo $product['id'];?>" js-fee="<?php echo $product['fee'];?>" />
				</span> + Shipping fee: &#x20b1; <b><?php echo $product['fee'];?></b>
			</p>
			<p class="product-total">
				Total &#x20b1; <b js-element="itemtotal-<?php echo $product['id'];?>"><?php echo number_format(((int)$product['quantity'] * (float)$product['rawdata']['basket_details']['price']) + (int)$product['fee']);?></b>
			</p>
		</div>
	</div>
</div>