<div class="orders-container" id="dashboard_panel_right">
	<div class="row">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="trans-navbar-container">
				<small class="elem-block"><b>FILTER STATUS</b></small>
				<div class="trans-navbar-grid">
					<div>
						<a href="orders/placed/">
							<div class="trans-navbar-pill <?php in_array_echo("orders-placed", $middle['body_class'], "active");?>">Placed <kbd><?php echo $data['counts']['placed'];?></kbd></div>
						</a>
					</div>
					<div>
						<a href="orders/delivery">
							<div class="trans-navbar-pill <?php in_array_echo("orders-on+delivery", $middle['body_class'], "active");?>">On Delivery <kbd><?php echo $data['counts']['on+delivery'];?></kbd></div>
						</a>
					</div>
					<div>
						<a href="orders/received">
							<div class="trans-navbar-pill <?php in_array_echo("orders-received", $middle['body_class'], "active");?>">Received <kbd><?php echo $data['counts']['received'];?></kbd></div>
						</a>
					</div>
					<div>
						<a href="orders/cancelled">
							<div class="trans-navbar-pill <?php in_array_echo("orders-cancelled", $middle['body_class'], "active");?>">Cancelled <kbd><?php echo $data['counts']['cancelled'];?></kbd></div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<?php if ($data['orders']): ?>
				<!-- per farm location -->
				<?php foreach ($data['orders'] as $key => $orders): ?>
					<?php $initial_total = 0; ?>
					<div class="order-table-item">
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
								<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Cancel all?" js-element="remove-all"><span class="text-danger">&times;</span></button>
							</div>
						</div>

						<div class="order-item-list">
							<?php foreach ($orders['order_details'] as $index => $order): ?>
								<!-- per order -->
								<?php
									$photo_url = 'https://via.placeholder.com/50x50.png?text=No+Image';
									if ($order['product']['photos'] AND isset($order['product']['photos']['main'])) {
										$photo_url = $order['product']['photos']['main']['url_path'];
									}
									$initial_total += (float)$order['sub_total'];
									$details = $order; unset($details['product']);
									$details['merge_id'] = $orders['id'];
									$details['basket_ids'] = $orders['basket_ids'];
									$json = json_encode($details);
								?>
								<div class="order-grid-column order-item">
									<div class="media">
										<div class="media-left media-top">
											<img class="media-object" width="50" height="50" src="<?php echo $photo_url;?>">
										</div>
										<div class="media-body">
											<p class="zero-gaps media-heading text-ellipsis"><a target="_blank" href="<?php product_url($order['product'], true);?>" class="text-link"><?php echo $order['product']['name'];?></a></p>
											<div class="ellipsis-container">
												<p class="zero-gaps"><?php echo $order['product']['description'];?></p>
											</div>
										</div>
									</div>
									<div class="text-right hidden-sm hidden-xs">
										<p class="zero-gaps">&#x20b1; <?php echo $order['price'];?> / <?php echo $order['measurement'];?></p>
									</div>
									<div class="text-right hidden-sm hidden-xs">
										<p class="zero-gaps"><?php echo $order['quantity'];?></p>
									</div>
									<div class="text-right">
										<button class="btn btn-xs btn-default order-remove-btn" js-element="remove-product" data-json='<?php echo $json;?>'><span class="text-danger">&times;</span></button>
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
								<p class="zero-gaps"><a target="farm_<?php echo $farm['id'];?>" href="<?php storefront_url($farm, true);?>" class="text-link"><?php echo $farm['name'];?></a></p>
								<p class="zero-gaps"><?php echo $farm['city_prov'];?></p>
							</div>
							<div class="order-footer-payment text-left hidden-xs">
								<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
								<p class="zero-gaps">Cash On Delivery</p>
								<p class="zero-gaps"></p>
							</div>
							<div class="text-left hidden-xs">
								<p style="margin-bottom:5px;"><small class="elem-block"><b>ORDER STATUS</b></small></p>
								<p class="zero-gaps"><span class="text-capsule status-placed"><?php echo ucwords(urldecode($data['status']));?></span></p>
							</div>
							<div class="order-footer-total">
								<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
								<p class="hidden-lg hidden-md hidden-sm text-center" style="padding-top:3px;margin:0;"><span class="text-capsule status-placed">Placed</span></p>
								<div>
									<p class="hidden-xs" style="margin-bottom:3px;"><small class="elem-block"><b>TOTAL</b></small></p>
									<p class="zero-gaps"><i>Delivery Fee:</i> <?php echo number_format($orders['fee']);?> + &#x20b1; <?php echo number_format($initial_total);?></p>
									<p style="border-top:1px solid #888;display:inline-block;padding:0 0 0 35px;margin:0;">&#x20b1; <b><?php echo number_format($initial_total + (float)$orders['fee']);?></b></p>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif ?>
		</div>
	</div>
</div>