
<?php if ($items): ?>
	<?php foreach ($items as $key => $item): ?>
		<?php
			$rawdata = $item['rawdata'];
			$rawdata['product']['farm_location_id'] = $item['location_id'];
		?>
		<div class="order-item-grid">
			<ul class="spaced-list between">
				<li><input type="checkbox" class="add-basket-item-select" js-event="addBasketItemselect" data-id="<?php echo $item['id'];?>" data-price="<?php echo $rawdata['details']['price'];?>" data-name="<?php echo $rawdata['product']['name'];?>"<?php str_has_value_echo('1', $item['status'], ' checked');?>></li>
				<li>
					<?php
						$main_photo = 'https://place-hold.it/280x280.png?text=No+Image&fontsize=14';
						if (!isset($rawdata['product']['photos']['main']) AND isset($rawdata['product']['photos']['other'])) {
							$main_photo = $rawdata['product']['photos']['other'][0]['url_path'];
						} elseif (isset($rawdata['product']['photos']['main'])) {
							$main_photo = $rawdata['product']['photos']['main']['url_path'];
						} 
					?>
					<div class="order-item-image" style="background-image: url('<?php echo $main_photo;?>'); cursor: pointer;"></div>
				</li>
			</ul>
			<div class="order-info-container" js-element="order">
				<div class="order-item-title">
					<p><a href="<?php product_url($rawdata['product'], true);?>" class="text-link"><?php echo ucwords($rawdata['product']['name']);?></a></p>
				</div>
				<p class="zero-gaps">
					&#x20b1; <b><?php echo number_format($rawdata['details']['price']);?></b> / <?php echo $rawdata['details']['measurement'];?> 
					<span class="qty-divider">x Quantity: 
						<input type="number" name="order-qty-input" class="order-qty-input" value="<?php echo $item['quantity'];?>" min="1" max="<?php echo $rawdata['details']['stocks'];?>" js-event="qty" js-price="<?php echo $rawdata['details']['price'];?>" js-id="<?php echo $item['id'];?>" />
					</span> Total: &#x20b1; <b js-element="itemtotal-<?php echo $item['id'];?>"><?php echo number_format((int)$item['quantity'] * (float)$rawdata['details']['price']);?></b>
				</p>
			</div>
		</div>
		
	<?php endforeach ?>
<?php endif ?>