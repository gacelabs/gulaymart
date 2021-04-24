<div class="checkout-product-item">
	<div class="checkout-product-info">
		<div class="checkout-product-image" style="background-image: url('<?php echo $basket['rawdata']['photos']['main']['url_path'];?>');"></div>
		<div class="text-left">
			<div class="ellipsis-container">
				<p class="zero-gaps"><?php echo $basket['rawdata']['name'];?></p>
			</div>
			<p class="text-gray zero-gaps"><i class="fa fa-map-marker"></i> <?php echo $basket['rawdata']['farm_location']['address_2'];?></p>
			<div class="visible-sm visible-xs">
				<p class="zero-gaps">&#x20b1; <span class="text-contrast"><?php echo number_format((int)$basket['quantity'] * (float)$basket['rawdata']['basket_details']['price']);?></span> x Qty: <?php echo $basket['quantity'];?></p>
			</div>
		</div>
	</div>

	<div class="text-left hidden-sm hidden-xs">
		<p>&#x20b1; <span class="text-contrast"><?php echo number_format((int)$basket['quantity'] * (float)$basket['rawdata']['basket_details']['price']);?></span></p>
	</div>
	<div class="text-right hidden-sm hidden-xs">
		<p>Qty: <?php echo $basket['quantity'];?></p>
	</div>
</div>