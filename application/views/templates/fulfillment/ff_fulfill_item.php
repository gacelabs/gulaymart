<?php
	$initial_total = 0;
	$status_array = [];
?>
<div class="fulfillment-table-item" data-merge-id="<?php echo $orders['id'];?>">
	<div class="fulfillment-grid-column fulfillment-labels">
		<div class="text-left">
			<p><small class="elem-block"><b>PRODUCT</b><i>- <?php echo date('M. j, Y | g:i a', strtotime($orders['updated']));?></i></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<p><small class="elem-block"><b>PRICE / UNIT</b></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<p><small class="elem-block"><b>QUANTITY</b></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<?php if ($status_text == 'placed') : ?>
			<p><small class="elem-block"><b tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-placement="left" title="Confirm or Cancelled" data-content="Let your customer know that you're ready to fulfill the order, otherwise, select the reason to cancel.">ACTION <i class="fa fa-question-circle"></i></b></small></p>
			<?php else : ?>
			<p><small class="elem-block"><b>ACTION</b></small></p>
			<?php endif ; ?>
		</div>
	</div>

	<div class="fulfillment-item-list" js-data-count="<?php echo count($orders['order_details']);?>">
		<div>
			<?php 
				$row_cnt = 0;
				$cancelled_items = false;
				$reason_items = false;
				foreach ($orders['order_details'] as $index => $order): ?>
				<!-- per order -->
				<?php
					$row_cnt++;
					// if ($order['status'] == GM_CANCELLED_STATUS AND !in_array($status_text, ['placed','cancelled'])) continue;
					$photo_url = 'https://via.placeholder.com/50x50.png?text=No+Image';
					$product = $order['product'];
					if ($product['photos'] AND isset($product['photos']['main'])) {
						$photo_url = $product['photos']['main']['url_path'];
					}
					$initial_total += (float)$order['sub_total'];
					$details = $order; unset($details['product']);
					$details['merge_id'] = $orders['id'];
					$details['basket_ids'] = $orders['basket_ids'];

					$reason = '';
					if ($details['status'] == GM_CANCELLED_STATUS) {
						$data_product = $this->gm_db->get('baskets', ['id' => $order['basket_id']], 'row');
						$reason = $data_product ? ($data_product['reason'] == 'None' ? 'No reason selected' : $data_product['reason']) : 'No reason selected';
						if ($cancelled_items == false) $cancelled_items = [];
						$cancelled_items[$order['basket_id']] = (float)$order['sub_total'];
					}

					$json = json_encode([
						'product_id'=>$order['product_id'],
						'location_id'=>$order['farm_location_id'],
						'merge_id'=>$orders['id'],
						'basket_id'=>$order['basket_id'],
						'sub_total'=>$order['sub_total'],
					], JSON_NUMERIC_CHECK);

					$status_array[] = $details['status'];
					$cancelled_class = ' was-cancelled';
				?>
				<div class="fulfillment-grid-column fulfillment-item<?php if ($status_text != 'cancelled'): ?><?php str_has_value_echo(GM_CANCELLED_STATUS, $details['status'], $cancelled_class);?><?php endif ?>" js-element="item-id-<?php echo $orders['id'];?>-<?php echo $product['id'];?>" data-basket-id="<?php echo $order['basket_id'];?>">
					<div class="media">
						<div class="media-left media-top">
							<img class="media-object" width="50" height="50" src="<?php echo $photo_url;?>">
						</div>
						<div class="media-body">
							<p class="zero-gaps media-heading text-ellipsis"><a href="<?php product_url($product, true);?>" class="text-link"><?php echo ucwords($product['name']);?></a></p>
							<div class="ellipsis-container">
								<p class="zero-gaps"><?php echo ucfirst($product['description']);?></p>
							</div>
						</div>
					</div>
					<div class="text-right hidden-sm hidden-xs">
						<p class="zero-gaps">&#x20b1; <?php echo format_number($order['price']);?> / <?php echo $order['measurement'];?></p>
					</div>
					<div class="text-right hidden-sm hidden-xs">
						<p class="zero-gaps"><?php echo $order['quantity'];?></p>
					</div>
					<div class="text-right" js-element="selectItems">
						<?php if (in_array($details['status'], [GM_PLACED_STATUS,GM_FOR_PICK_UP_STATUS]) AND $status_text == 'placed') : ?>
							<select class="form-control" js-event="actionSelect"<?php str_has_value_echo(GM_FOR_PICK_UP_STATUS, $details['status'], ' style="color: rgb(121, 153, 56);"');?> data-basket_id="<?php echo $order['basket_id'];?>" data-location_id="<?php echo $order['farm_location_id'];?>" data-product_id="<?php echo $product['id'];?>" data-sub_total="<?php echo $order['sub_total'];?>">
								<option value="2">Select Action</option>
								<option value="6"<?php str_has_value_echo(GM_FOR_PICK_UP_STATUS, $details['status'], ' selected');?>>Confirm</option> <!-- for pick up -->
								<option value="5">Cancelled</option> <!-- cancelled -->
							</select>
							<select class="form-control hide" js-event="reasonSelect" style="margin-bottom:0;" data-json='<?php echo $json;?>'>
								<option value="None">Select Reason</option>
								<option value="Out Of Stock">Out Of Stock</option>
								<option value="Removed Product">Removed Product</option>
							</select>
						<?php else : ?>
							<p class="zero-gaps hidden-sm hidden-xs">
								<?php if ($details['status'] == GM_CANCELLED_STATUS): ?>
									<small class="text-capsule status-cancelled">
										<?php echo $reason;?>
									</small>
								<?php else: ?>
									<small class="text-capsule bg-theme" js-data="confirmed">
										Confirmed
									</small>
								<?php endif ?>
							</p>
						<?php endif ; ?>
					</div>

					<div class="visible-sm visible-xs">
						<ul class="spaced-list between">
							<li><p class="zero-gaps">&#x20b1; <?php echo format_number($order['price']);?> / <?php echo $order['measurement'];?></p></li>
							<li class="icon-right"><p class="zero-gaps">x <?php echo $order['quantity'];?> QTY</p></li>
						</ul>
					</div>
				</div>
			<?php endforeach ?>
		</div>

		<div class="fulfillment-deliver-note">
			<small class="elem-block">DELIVERY SCHEDULE:
				<?php
					if ($orders['order_type'] == GM_BUY_NOW) {
						echo " <b>SAME DAY</b>";
					} else {
						echo " <b>".strtoupper($orders['schedule']."</b>");
					}
				?>
			</small>
		</div>
	</div>

	<?php
		$buyer = $orders['buyer'];
		// debug($buyer, 'stop');
		// debug($cancelled_items, 'stop');
		if ($cancelled_items AND in_array($status_id, [GM_PLACED_STATUS, GM_ON_DELIVERY_STATUS, GM_RECEIVED_STATUS])) {
			foreach ($cancelled_items as $amount) {
				$initial_total -= $amount;
			}
		}
		$request_to_cancel = $orders['request_to_cancel'];
	?>

	<div class="fulfillment-grid-footer">
		<div class="fulfillment-footer-farm text-left">
			<p class="zero-gaps"><small class="elem-block"><b>BUYER</b></small></p>
			<p class="zero-gaps"><?php echo ucwords($buyer['fullname']);?></p>
			<p class="zero-gaps">
				<?php if ($buyer): ?>
					<?php foreach ($buyer['shippings'] as $key => $shipping): ?>
						<?php if ($shipping['active'] == 1): ?>
							<?php echo ucwords($shipping['address_2']);?>
						<?php endif ?>
					<?php endforeach ?>
				<?php endif ?>
			</p>
		</div>
		<div class="fulfillment-footer-payment text-left">
			<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
			<p class="zero-gaps">Cash On Delivery</p>
			<p class="zero-gaps"></p>
		</div>
		<?php if ($status_text != 'cancelled'): ?>
			<div class="text-left">
				<?php if ($initial_total > 0): ?>
					<p class="zero-gaps"><small class="elem-block"><b>ORDER INVOICE</b></small></p>
					<?php if ($status_text == 'placed') : ?>
						Available upon Pick Up (Status)
					<?php else : ?>
						<button class="btn btn-xs btn-contrast" data-toggle="modal" data-target="#ff_invoice_modal" data-basket-merge-id="<?php echo $orders['id'];?>">INVOICE<i class="fa fa-file-text-o icon-right"></i></button>
					<?php endif ; ?>
				<?php endif ?>
			</div>
		<?php endif ?>
		<div class="text-left">
			<?php if ($status_text == 'placed') : ?>
				<p class="zero-gaps" js-element="proceed-panel"><small class="elem-block"><b>PROCEED</b></small></p>
				<button class="btn btn-sm btn-contrast" js-element="proceed-btn" data-merge_id="<?php echo $orders['id'];?>" data-default-html='READY FOR PICK UP<i class="fa fa-angle-right icon-right"></i>'>READY FOR PICK UP<i class="fa fa-angle-right icon-right"></i></button>
			<?php else : ?>
				<p class="zero-gaps"><small class="elem-block"><b>ORDER STATUS</b></small></p>
				<p class="zero-gaps"><span class="text-capsule status-<?php echo str_replace(" ", "", strtolower(urldecode($status_text)));?>"><?php echo ucwords(urldecode($status_text));?></span></p>
				<?php if ($request_to_cancel): ?>
					<p class="zero-gaps"><span class="text-capsule status-cancelled">Requested for Cancellation</span></p>
				<?php endif ?>
			<?php endif ; ?>
		</div>
	</div>
</div>