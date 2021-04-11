
<div class="order-item-inner">
	<p class="zero-gaps">
		<b>
			<a href="<?php echo $product['rawdata']['farm']['storefront'];?>"><?php echo $product['rawdata']['farm']['name'];?></a>
		</b>
	</p>
	<div class="order-item-grid">
		<div class="order-item-image" style="background-image: url('<?php echo $product['rawdata']['photo']['url_path'];?>');"></div>
		<div class="order-info-container">
			<div class="order-item-title">
				<p><a href="<?php echo $product['rawdata']['product_url'];?>" class="text-link"><?php echo $product['rawdata']['description'];?></a></p>
			</div>
			<p class="zero-gaps">&#x20b1; <b><?php echo $product['rawdata']['basket_details']['price'];?></b> / <?php echo $product['rawdata']['basket_details']['measurement'];?> <span class="qty-divider">x Quantity: <input type="number" name="order-qty-input" class="order-qty-input" value="<?php echo $product['quantity'];?>"></span> + Shipping fee: &#x20b1; <b>50</b></p>
			<p class="product-total">Total &#x20b1; <b>150</b></p>
		</div>
	</div>
</div>