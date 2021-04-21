
<div class="order-item-middle">
	<div class="order-item-list">
		<?php foreach ($order as $farm => $items): ?>
			<div class="order-item-inner">
				<p class="zero-gaps"><?php echo $farm;?> (Shipping fee: &#x20b1; <b><?php echo $items[0]['fee'];?></b>)</p>
				<div class="order-item-grid">
				<?php foreach ($items as $key => $item): ?>
					<?php
						$main_photo = 'https://place-hold.it/228x268.png?text=No+Image&fontsize=14';
						if (!isset($item['rawdata']['photos']['main']) AND isset($item['rawdata']['photos']['other'])) {
							$main_photo = $item['rawdata']['photos']['other'][0]['url_path'];
						} elseif (isset($item['rawdata']['photos']['main'])) {
							$main_photo = $item['rawdata']['photos']['main']['url_path'];
						} 
					?>
					<div class="order-item-image" style="background-image: url('<?php echo $main_photo;?>');"></div>
					<div class="order-info-container">
						<div class="order-item-title">
							<p><a href="<?php echo $item['rawdata']['product_url'];?>"><?php echo $item['rawdata']['name'];?></a></p>
						</div>
						<p class="zero-gaps">&#x20b1; <b><?php echo $item['rawdata']['basket_details']['price'];?></b> / <?php echo $item['rawdata']['basket_details']['measurement'];?> x <span class="qty-divider">Quantity: <b><?php echo $item['quantity'];?></b></span></p>
						<p class="product-total">Total &#x20b1; <b><?php echo $item['quantity'] * $item['rawdata']['basket_details']['price'];?></b></p>
					</div>
				<?php endforeach ?>
				</div>
			</div>
		<?php endforeach ?>
	</div>

	<div class="tender-amount-grid">
		<div class="order-item-status">
			<ul class="inline-list">
				<li class="text-capsule icon-left status-<?php echo $status_class;?>"><?php echo ucwords(strtolower($large_status));?></li>
			</ul>
		</div>
		<div class="tender-amount-parent">
			<div class="tender-amount-body">
				<?php $total_amount = $shipping_fee = 0; ?>
				<?php foreach ($order as $farm => $items): ?>
					<?php $shipping_fee += $items[0]['fee'];?>
					<?php foreach ($items as $key => $item): ?>
						<?php
							$sub_total = $item['quantity'] * $item['rawdata']['basket_details']['price'];;
							$total_amount += $sub_total;
						?>
						<p class="product-amount zero-gaps">&#x20b1; <b><?php echo number_format($sub_total);?></b></p>
					<?php endforeach ?>
				<?php endforeach ?>
				<hr style="border-color:#aaa;margin:5px 0;">
				<p class="product-amount zero-gaps"><small class="pull-left">Shipping fee</small>+ &#x20b1; <b><?php echo number_format($shipping_fee);?></b></p>
			</div>
			<hr style="border-color:#aaa;margin:5px 0;">
			<h4 class="total-amount text-contrast zero-gaps"><span>&#x20b1;</span> <b><?php echo number_format($total_amount + $shipping_fee);?></b></h4>
		</div>
	</div>
</div>