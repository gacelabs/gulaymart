<div class="row hidden-xs ff-product-container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs" js-element="fulfill-panel">
		<?php if ($data['orders']): ?>
			<!-- per farm location -->
			<?php foreach ($data['orders'] as $key => $orders): ?>
				<?php
					$initial_total = 0;
					$status_array = [];
				?>
				<div class="order-table-item" data-merge-id="<?php echo $orders['id'];?>">
					<div class="order-grid-column order-labels">
						<div class="text-left">
							<p><small class="elem-block"><b>PRODUCT</b><i>- <?php echo date('M. j, Y | g:i a', strtotime($orders['added']));?></i></small></p>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<p><small class="elem-block"><b>PRICE / UNIT</b></small></p>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<p><small class="elem-block"><b>QUANTITY</b></small></p>
						</div>
						<div class="text-right">
							<?php if ($data['status'] == 'placed') : ?>
							<p><small class="elem-block"><b tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-placement="left" title="Confirm or Cancelled" data-content="Let your customer know that you're ready to fulfill the order, otherwise, select the reason to cancel.">ACTION <i class="fa fa-question-circle"></i></b></small></p>
							<?php else : ?>
							<p><small class="elem-block"><b>ACTION</b></small></p>
							<?php endif ; ?>
						</div>
					</div>

					<div class="order-item-list" js-data-count="<?php echo count($orders['order_details']);?>">
						<div>
							<?php 
								$row_cnt = 0;
								foreach ($orders['order_details'] as $index => $order): ?>
								<!-- per order -->
								<?php
									$row_cnt++;
									// if ($order['status'] == GM_CANCELLED_STATUS AND !in_array($data['status'], ['placed','cancelled'])) continue;
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
										$reason = $data_product['reason'];
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
									if ($row_cnt == count($orders['order_details'])) {
										$cancelled_class = ' was-cancelled-last-child';
									}
								?>
								<div class="order-grid-column order-item<?php if ($data['status'] != 'cancelled'): ?><?php str_has_value_echo(GM_CANCELLED_STATUS, $details['status'], $cancelled_class);?><?php endif ?>" js-element="item-id-<?php echo $orders['id'];?>-<?php echo $product['id'];?>" data-basket-id="<?php echo $order['basket_id'];?>">
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
										<?php if (in_array($details['status'], [GM_PLACED_STATUS,GM_FOR_PICK_UP_STATUS]) AND $data['status'] == 'placed') : ?>
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
											<p class="zero-gaps">
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

						<div class="order-deliver-note">
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
						$farm = $orders['seller'];
						$buyer = $orders['buyer'];
						// debug($buyer, 'stop');
					?>

					<div class="order-grid-footer">
						<div class="order-footer-farm text-left hidden-xs">
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
						<div class="order-footer-payment text-left hidden-xs">
							<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
							<p class="zero-gaps">Cash On Delivery</p>
							<p class="zero-gaps"></p>
						</div>
						<?php if ($data['status'] != 'cancelled'): ?>
							<div class="text-left hidden-xs">
								<p class="zero-gaps"><small class="elem-block"><b>ORDER INVOICE</b></small></p>
								<?php if ($data['status'] == 'placed') : ?>
									Available upon Pick Up (Status)
								<?php else : ?>
									<button class="btn btn-xs btn-contrast" data-toggle="modal" data-target="#ff_invoice_modal" data-basket-merge-id="<?php echo $orders['id'];?>">INVOICE<i class="fa fa-file-text-o icon-right"></i></button>
								<?php endif ; ?>
							</div>
						<?php endif ?>
						<div class="text-left hidden-xs">
							<?php if ($data['status'] == 'placed') : ?>
								<p class="zero-gaps" js-element="proceed-panel"><small class="elem-block"><b>PROCEED</b></small></p>
								<button class="btn btn-sm btn-contrast" js-element="proceed-btn" data-merge_id="<?php echo $orders['id'];?>" data-default-html='READY FOR PICK UP<i class="fa fa-angle-right icon-right"></i>'>READY FOR PICK UP<i class="fa fa-angle-right icon-right"></i></button>
							<?php else : ?>
								<p class="zero-gaps"><small class="elem-block"><b>ORDER STATUS</b></small></p>
								<p class="zero-gaps"><span class="text-capsule status-<?php echo str_replace(" ", "", strtolower(urldecode($data['status'])));?>"><?php echo ucwords(urldecode($data['status']));?></span></p>
							<?php endif ; ?>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		<?php endif ?>
		<?php if (!isset($data['no_rec_ui'])): ?>
			<div class="no-records-ui<?php if (!empty($data['orders'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
			</div>
		<?php endif ?>
	</div>
</div>