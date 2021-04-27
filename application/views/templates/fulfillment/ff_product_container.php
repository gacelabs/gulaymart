<div class="row hidden-xs ff-product-container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs" js-element="fulfill-panel">
		<?php if ($data['orders']): ?>
			<!-- per farm location -->
			<?php foreach ($data['orders'] as $key => $orders): ?>
				<?php $initial_total = 0; ?>
				<div class="order-table-item" data-merge-id="<?php echo $orders['id'];?>">
					<div class="order-grid-column order-labels">
						<div class="text-left">
							<p><small class="elem-block"><b>PRODUCT</b></small></p>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<p><small class="elem-block"><b>PRICE / UNIT</b></small></p>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<p><small class="elem-block"><b>QUANTITY</b></small></p>
						</div>
						<div class="text-right">
							<?php if ($data['status'] == 'placed') : ?>
							<p><small class="elem-block"><b>ACTION</b> <i class="fa fa-question-circle" tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-placement="left" title="Confirm or Cancelled" data-content="Let your customer know that you're ready to fulfill the order, otherwise, select the reason to cancel."></i></small></p>
							<?php else : ?>
							<p><small class="elem-block"><b>ACTION</b></small></p>
							<?php endif ; ?>
						</div>
					</div>

					<div class="order-item-list">
						<?php foreach ($orders['order_details'] as $index => $order): ?>
							<!-- per order -->
							<?php
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
								]);
							?>
							<div class="order-grid-column order-item<?php if ($data['status'] != 'cancelled'): ?><?php str_has_value_echo(5, $details['status'], ' was-cancelled');?><?php endif ?>" js-element="item-id-<?php echo $orders['id'];?>-<?php echo $product['id'];?>">
								<div class="media">
									<div class="media-left media-top">
										<img class="media-object" width="50" height="50" src="<?php echo $photo_url;?>">
									</div>
									<div class="media-body">
										<p class="zero-gaps media-heading text-ellipsis"><a target="_blank" href="<?php product_url($product, true);?>" class="text-link"><?php echo $product['name'];?></a></p>
										<div class="ellipsis-container">
											<p class="zero-gaps"><?php echo $product['description'];?></p>
										</div>
									</div>
								</div>
								<div class="text-right hidden-sm hidden-xs">
									<p class="zero-gaps">&#x20b1; <?php echo $order['price'];?> / <?php echo $order['measurement'];?></p>
								</div>
								<div class="text-right hidden-sm hidden-xs">
									<p class="zero-gaps"><?php echo $order['quantity'];?></p>
								</div>
								<div class="text-right" js-element="selectItems">
									<?php if ($details['status'] == 2) : ?>
										<select class="form-control" js-event="actionSelect">
											<option selected value="2">Placed</option>
											<option value="6">Confirm</option>
											<option value="5">Cancelled</option>
										</select>
										<select class="form-control hide" js-event="reasonSelect" style="margin-bottom:0;">
											<option value="Out Of Stock">Out Of Stock</option>
											<option value="Removed Product">Removed Product</option>
										</select>
									<?php else : ?>
										<p class="zero-gaps">
											<?php if ($details['status'] == 5): ?>
												<span class="text-capsule bg-danger">
													Removed by buyer
												</span>
											<?php else: ?>
												<span class="text-capsule bg-theme">
													Confirmed
												</span>
											<?php endif ?>
										</p>
									<?php endif ; ?>
								</div>

								<div class="visible-sm visible-xs">
									<ul class="spaced-list between">
										<li><p class="zero-gaps">&#x20b1; <?php echo $order['price'];?> / <?php echo $order['measurement'];?></p></li>
										<li class="icon-right"><p class="zero-gaps">x <?php echo $order['quantity'];?> QTY</p></li>
									</ul>
								</div>
							</div>
						<?php endforeach ?>
					</div>

					<?php
						$farm = $orders['seller'];
					?>

					<div class="order-grid-footer">
						<div class="order-footer-farm text-left hidden-xs">
							<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
							<p class="zero-gaps"><a target="farm_<?php echo $farm['id'];?>" href="<?php storefront_url($farm, true);?>" class="text-link"><?php echo ucwords($farm['name']);?></a></p>
							<p class="zero-gaps"><?php echo $farm['city_prov'];?></p>
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
								<button class="btn btn-sm btn-default" data-toggle="modal" data-target="#ff_invoice_modal">INVOICE<i class="fa fa-file-text-o icon-right"></i></button>
							<?php endif ; ?>
						</div>
						<?php endif ?>
						<div class="text-left hidden-xs">
							<?php if ($data['status'] == 'placed') : ?>
								<p style="margin-bottom:5px;" js-element="proceed-panel"><small class="elem-block"><b>PROCEED</b></small></p>
								<button class="btn btn-sm btn-contrast">READY FOR DELIVERY<i class="fa fa-angle-right icon-right"></i></button>
							<?php else : ?>
								<p style="margin-bottom:5px;"><small class="elem-block"><b>ORDER STATUS</b></small></p>
								<p class="zero-gaps"><span class="text-capsule status-pickup"><?php echo ucwords(urldecode($data['status']));?></span></p>
							<?php endif ; ?>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		<?php endif ?>
		<div class="no-records-ui<?php if (!empty($data['orders'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
			<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
			<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="/" class="btn btn-sm btn-contrast">Marketplace</a></p>
		</div>
	</div>
</div>