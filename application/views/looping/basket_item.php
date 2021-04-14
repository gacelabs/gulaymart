
<div class="order-item-grid">
	<ul class="spaced-list between">
		<li><input type="checkbox" class="add-basket-item-select" js-event="addBasketItemselect" data-id="<?php echo $basket['id'];?>" data-price="<?php echo $basket['rawdata']['basket_details']['price'];?>" data-name="<?php echo $basket['rawdata']['name'];?>" data-fee="<?php echo $basket['fee'];?>"<?php str_has_value_echo('1', $basket['status'], ' checked');?>></li>
		<li>
			<div class="order-item-image" style="background-image: url('<?php echo $basket['rawdata']['photos']['main']['url_path'];?>'); cursor: pointer;"></div>
		</li>
	</ul>
	<div class="order-info-container" js-element="order">
		<div class="order-item-title">
			<p><a href="<?php echo $basket['rawdata']['product_url'];?>" class="text-link"><?php echo ucwords($basket['rawdata']['name']);?></a></p>
		</div>
		<p class="zero-gaps">
			&#x20b1; <b><?php echo number_format($basket['rawdata']['basket_details']['price']);?></b> / <?php echo $basket['rawdata']['basket_details']['measurement'];?> 
			<span class="qty-divider">x Quantity: 
				<input type="number" name="order-qty-input" class="order-qty-input" value="<?php echo $basket['quantity'];?>" min="1" max="<?php echo $basket['rawdata']['basket_details']['stocks'];?>" js-event="qty" js-price="<?php echo $basket['rawdata']['basket_details']['price'];?>" js-id="<?php echo $basket['id'];?>" js-fee="<?php echo $basket['fee'];?>" />
			</span> Total: &#x20b1; <b js-element="itemtotal-<?php echo $basket['id'];?>"><?php echo number_format((int)$basket['quantity'] * (float)$basket['rawdata']['basket_details']['price']);?></b>
		</p>
	</div>
</div>