<?php $initial_total = 0; ?>
<div class="order-table-item" data-merge-id="<?php echo $orders['id'];?>">
	<div class="order-grid-column order-labels">
		<div class="text-left">
			<p><small class="elem-block"><b>PRODUCT</b><i>- <?php echo date('M. j, Y | g:i a', strtotime($orders['updated']));?></i></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<p><small class="elem-block"><b>PRICE / UNIT</b></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<p><small class="elem-block"><b>QUANTITY</b></small></p>
		</div>
		<?php if (in_array($status_id, [GM_PLACED_STATUS])): ?>
		<div class="text-right">
			<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Cancel order?" js-element="remove-all" data-merge_id='<?php echo $orders['id'];?>' loading-text=""><span class="text-danger">&times;</span></button>
		</div>
		<?php endif ?>
	</div>

	<div class="order-item-list" js-data-count="<?php echo count($orders['order_details']);?>">
		<?php 
			$row_cnt = 0;
			$cancelled_items = false;
			foreach ($orders['order_details'] as $index => $order): ?>
			<!-- per order -->
			<?php
				$row_cnt++;
				// if ($order['status'] == 5 AND !in_array($status, ['placed','cancelled'])) continue;
				$photo_url = 'https://via.placeholder.com/50x50.png?text=No+Image';
				$product = $order['product'];
				if ($product['photos'] AND isset($product['photos']['main'])) {
					$photo_url = $product['photos']['main']['url_path'];
				}
				$initial_total += (float)$order['sub_total'];
				$details = $order; unset($details['product']);
				$details['merge_id'] = $orders['id'];
				$details['basket_ids'] = $orders['basket_ids'];
				$json = json_encode([
					'product_id'=>$order['product_id'],
					'location_id'=>$order['farm_location_id'],
					'merge_id'=>$orders['id'],
					'basket_id'=>$order['basket_id'],
					'sub_total'=>$order['sub_total'],
				], JSON_NUMERIC_CHECK);
				$status_array[] = $details['status'];
				if ($details['status'] == GM_CANCELLED_STATUS) {
					if ($cancelled_items == false) $cancelled_items = [];
					$cancelled_items[$order['basket_id']] = (float)$order['sub_total'];
				}
				$cancelled_class = ' was-cancelled';
			?>
			<div class="order-grid-column order-item<?php if ($status != 'cancelled'): ?><?php str_has_value_echo(GM_CANCELLED_STATUS, $details['status'], $cancelled_class);?><?php endif ?>" js-element="item-id-<?php echo $orders['id'];?>-<?php echo $product['id'];?>" data-basket-id="<?php echo $order['basket_id'];?>">
				<div class="media">
					<div class="media-left media-top">
						<img class="media-object" width="50" height="50" src="<?php echo $photo_url;?>">
					</div>
					<div class="media-body">
						<div class="ellipsis-container" style="height:20px;margin:0;-webkit-line-clamp:1;">
							<p class="zero-gaps media-heading"><a<?php if (!$this->agent->is_mobile()): ?> target="_blank"<?php endif ?> href="<?php product_url($product, true);?>" class="text-link"><?php echo ucwords($product['name']);?></a></p>
						</div>
						<div class="ellipsis-container">
							<p class="zero-gaps"><?php echo ucfirst($product['description']);?></p>
						</div>
					</div>
				</div>
				<div class="text-right hidden-sm hidden-xs">
					<p class="zero-gaps">&#x20b1; <?php echo format_number($order['price']);?> / <?php echo ucfirst($order['measurement']);?></p>
					<?php if ($details['status'] == GM_CANCELLED_STATUS): ?>
						<?php if ($order['cancel_by'] == $current_profile['id']): ?>
							<?php if ($order['reason'] != 'Out Of Stock'): ?>
								<p class="zero-gaps">
									<small class="text-capsule status-cancelled">Removed by You</small>
								</p>
							<?php else: ?>
								<p class="zero-gaps">
									<small class="text-capsule status-cancelled"><?php echo ($order['reason'] == 'None' ? 'Out Of Stock' : $order['reason']);?></small>
								</p>
							<?php endif ?>
						<?php elseif ($order['cancel_by'] > 0 AND $order['cancel_by'] != $current_profile['id']): ?>
							<p class="zero-gaps"><small class="text-capsule status-cancelled"><?php echo ($order['reason'] == 'None' ? 'Out Of Stock' : $order['reason']);?></small></p>
						<?php endif ?>
					<?php endif ?>
				</div>
				<div class="text-right hidden-sm hidden-xs">
					<p class="zero-gaps"><?php echo $order['quantity'];?></p>
				</div>
				<?php if ($details['status'] == GM_PLACED_STATUS AND in_array($status, ['placed'])): ?>
					<div class="text-right">
						<button class="btn btn-xs btn-default order-remove-btn<?php if (count($orders['order_details']) == 1): ?> hide<?php endif ?>" data-toggle="tooltip" data-placement="top" title="Cancel item?" js-element="remove-product" data-json='<?php echo $json;?>' loading-text=""><span class="text-danger">&times;</span></button>
					</div>
				<?php endif ?>

				<div class="visible-sm visible-xs">
					<ul class="spaced-list between">
						<li><p class="zero-gaps">&#x20b1; <?php echo format_number($order['price']);?> / <?php echo ucfirst($order['measurement']);?></p></li>
						<li class="icon-right"><p class="zero-gaps">x <?php echo $order['quantity'];?> QTY</p></li>
					</ul>
				</div>
			</div>
		<?php endforeach ?>
		<?php
		// debug($cancelled_items, 'stop');
		if ($cancelled_items AND in_array($status_id, [GM_PLACED_STATUS, GM_ON_DELIVERY_STATUS, GM_RECEIVED_STATUS])) {
			foreach ($cancelled_items as $amount) {
				$initial_total -= $amount;
			}
		}
		?>
		<div class="order-deliver-note">
			<ul class="spaced-list between">
				<li>
					<small class="elem-block">DELIVERY SCHEDULE:
						<?php
							if ($orders['order_type'] == GM_BUY_NOW) {
								echo " <b>SAME DAY</b>";
							} else {
								echo " <b>".strtoupper($orders['schedule']."</b>");
							}
						?>
					</small>
				</li>
				<?php if ($initial_total > 0): ?>
					<li>
						<a class="elem-block" style="cursor:pointer; font-weight: bold; font-size: 10px;" data-toggle="modal" data-target="#ff_invoice_modal" data-basket-merge-id="<?php echo $orders['id'];?>">INVOICE</a>
					</li>
				<?php endif ?>
			</ul>
		</div>
	</div>

	<?php
		$farm = $orders['seller'];
		$nospace_status = str_replace(" ", "", strtolower(urldecode($status)));
	?>

	<div class="order-grid-footer" js-element="farm-<?php echo $orders['id'];?>-<?php echo $farm['farm_location_id'];?>">
		<div class="order-footer-farm text-left hidden-xs">
			<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
			<p class="zero-gaps"><a<?php if (!$this->agent->is_mobile()): ?> target="farm_<?php echo $farm['id'];?>"<?php endif ?> href="<?php storefront_url($farm, true);?>" class="text-link"><?php echo ucwords($farm['name']);?></a></p>
			<p class="zero-gaps"><?php echo $farm['city_prov'];?></p>
		</div>
		<div class="order-footer-payment text-left hidden-xs">
			<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
			<p class="zero-gaps">Cash On Delivery</p>
			<p class="zero-gaps"></p>
		</div>
		<div class="text-left hidden-xs">
			<p style="margin-bottom:5px;"><small class="elem-block"><b>ORDER STATUS</b></small></p>
			<p class="zero-gaps">
				<span class="text-capsule status-<?php echo $nospace_status;?>"><?php echo ucwords(urldecode($status));?></span>
				<?php if ($status == 'on+delivery'): ?>
					<!-- <a class="btn text-capsule bg-theme" js-data="received" href="orders/receive/<?php echo $orders['id'];?>" data-ajax="1" data-json='<?php echo json_encode(['confirm' => 1], JSON_NUMERIC_CHECK);?>'>Order Received</a> -->
				<?php endif ?>
				<span class="text-capsule bg-theme<?php not_in_array_echo(6, $status_array, ' hide');?>" js-data="confirmed">Confirmed</span>
			</p>
		</div>
		<div class="order-footer-total">
			<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
			<p class="zero-gaps hidden-lg hidden-md hidden-sm text-left" style="font-size:11px;">
				<span class="text-capsule status-<?php echo $nospace_status;?>"><?php echo ucwords(urldecode($status));?></span>
				<span class="text-capsule hidden-lg hidden-md status-<?php echo $nospace_status;?>"><?php echo ($order['reason'] == 'None' ? 'Out Of Stock' : $order['reason']);?></span>
				<span class="text-capsule bg-theme<?php not_in_array_echo(6, $status_array, ' hide');?>" js-data="confirmed">Confirmed</span>
			</p>
			<div>
				<?php
				$fee = $orders['fee'];
				if ($initial_total <= 0) $fee = 0;
				?>
				<p class="hidden-xs" style="margin-bottom:3px;"><small class="elem-block"><b>TOTAL</b></small></p>
				<p class="zero-gaps"><i>Delivery Fee:</i> <span js-element="item-fee"><?php echo format_number($fee, 2);?></span> + &#x20b1; <span js-element="item-subtotal"><?php echo format_number($initial_total);?></span></p>
				<p class="item-final-total">&#x20b1; <b js-element="item-finaltotal"><?php echo format_number($initial_total + (float)$fee);?></b></p>
			</div>
		</div>
	</div>
</div>