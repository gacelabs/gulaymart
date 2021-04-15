<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="link_panel_top">
				<ul class="inline-list dashboard-panel-top">
					<li><a href="transactions/orders/" class="hideshow-btn active" hideshow-target="order_placed"><h4 class="zero-gaps">Placed</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_delivery"><h4 class="zero-gaps">On Delivery</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_received"><h4 class="zero-gaps">Received</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_cancelled"><h4 class="zero-gaps">Cancelled</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="all_orders"><h4 class="zero-gaps">All Orders</h4></a></li>
				</ul>
				<div class="dashboard-panel-middle hideshow-container">
					<?php
						$placed = false;
						if ($data['orders'] AND isset($data['orders']['placed'])) $placed = $data['orders']['placed'];
						$this->view('templates/transactions/placed', ['placed' => $placed]);

						$on_delivery = false;
						if ($data['orders'] AND isset($data['orders']['on_delivery'])) $on_delivery = $data['orders']['on_delivery'];
						$this->view('templates/transactions/delivery', ['on_delivery' => $on_delivery]);

						$received = false;
						if ($data['orders'] AND isset($data['orders']['received'])) $received = $data['orders']['received'];
						$this->view('templates/transactions/received', ['received' => $received]);

						$cancelled = false;
						if ($data['orders'] AND isset($data['orders']['cancelled'])) $cancelled = $data['orders']['cancelled'];
						$this->view('templates/transactions/cancelled', ['cancelled' => $cancelled]);
					?>

					<div class="order-list-container hide" id="all_orders">
						<?php if ($data['orders']): ?>
							<?php foreach ($data['orders'] as $status => $bydate): ?>
								<?php foreach ($bydate as $date => $order): ?>
									<div class="order-item">
										<div class="order-item-top">
											<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
											<p class="zero-gaps"><?php echo strtoupper(ucwords($status));?>: <b><?php echo $date;?></b></p>
										</div>
										<div class="order-item-middle">
											<div class="order-item-list">
												<?php foreach ($order as $farm_name => $items): ?>
													<div class="order-item-inner">
														<p class="zero-gaps"><?php echo $farm_name;?> (Shipping fee: &#x20b1; <b><?php echo $items[0]['fee'];?></b>)</p>
														<div class="order-item-grid">
															<?php foreach ($items as $key => $item): ?>
																<div class="order-item-image" style="background-image: url('<?php echo $item['rawdata']['photos']['main']['url_path'];?>');"></div>
																<div class="order-info-container">
																	<div class="order-item-title">
																		<p><a href="<?php echo $item['rawdata']['product_url'];?>"><?php echo $item['rawdata']['name'];?></a></p>
																	</div>
																	<p class="zero-gaps">&#x20b1; <b><?php echo $item['rawdata']['basket_details']['price'];?></b> / <?php echo $item['rawdata']['basket_details']['measurement'];?> <span class="qty-divider">x Quantity: <b><?php echo $item['quantity'];?></b></span></p>
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
														<li class="text-capsule icon-left status-<?php echo $status;?>"><?php echo $status;?></li>
													</ul>
												</div>
												<div class="tender-amount-parent">
													<div class="tender-amount-body">
														<?php $total_amount = $shipping_fee = 0; ?>
														<?php foreach ($order as $farm_name => $items): ?>
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
									</div>
								<?php endforeach ?>
							<?php endforeach ?>
						<?php else: ?>
							<p>No Orders</p>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>