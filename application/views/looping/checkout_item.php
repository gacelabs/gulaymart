
<?php if ($items): ?>
	<?php foreach ($items as $key => $item): ?>
		<?php
			$rawdata = $item['rawdata'];
			if (!isset($rawdata['product']['farm_location_id'])) $rawdata['product']['farm_location_id'] = $item['location_id'];
		?>
		<div class="checkout-product-item">
			<div class="checkout-product-info">
				<div class="checkout-product-image" style="background-image: url('<?php identify_main_photo($rawdata['product']);?>');"></div>
				<div class="text-left">
					<div class="ellipsis-container">
						<p class="zero-gaps"><?php echo $rawdata['product']['name'];?></p>
					</div>
					<p class="text-gray zero-gaps"><i class="fa fa-map-marker"></i> <?php echo $farm['address_2'];?></p>
					<div class="visible-sm visible-xs">
						<p class="zero-gaps">&#x20b1; <span class="text-contrast"><?php echo number_format((int)$item['quantity'] * (float)$rawdata['details']['price']);?></span> x Qty: <?php echo $item['quantity'];?></p>
					</div>
				</div>
			</div>

			<div class="text-left hidden-sm hidden-xs">
				<p>&#x20b1; <span class="text-contrast"><?php echo number_format((int)$item['quantity'] * (float)$rawdata['details']['price']);?></span></p>
			</div>
			<div class="text-right hidden-sm hidden-xs">
				<p>Qty: <?php echo $item['quantity'];?></p>
			</div>
		</div>
	<?php endforeach ?>
<?php endif ?>