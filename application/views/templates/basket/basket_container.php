<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="link_panel_top">
				<ul class="spaced-list between dashboard-panel-top">
					<li><a href="basket/" class="hideshow-btn active"><h4 class="zero-gaps">My Basket</h4></a></li>
					<li><button js-event="removeBasketItemBtn" class="btn btn-xs btn-danger hide">Delete</button></li>
				</ul>
				<?php if ($data['baskets']): ?>
					<div class="dashboard-panel-middle">
						<div class="order-list-container">
							<?php foreach ($data['baskets'] as $timestamp => $baskets): ?>
								<div class="order-item">
									<div class="order-item-top">
										<p class="zero-gaps">ADDED: <b><?php echo $timestamp;?></b></p>
									</div>
									<div class="order-item-middle">
										<div class="order-item-list">
											<?php foreach ($baskets as $location_id => $basket): ?>
												<?php foreach ($basket as $key => $product): ?>
													<?php $this->view('looping/basket_item', ['product' => $product]); ?>
												<?php endforeach ?>
											<?php endforeach ?>
										</div>

										<div class="tender-amount-grid">
											<div class="order-item-status">
												<div class="payment-method-list">
													<p><b class="text-contrast">PAYMENT METHOD</b></p>
													<ul class="inline-list">
														<li><label><input type="radio" class="zero-gaps" name="payment_method"> Cash On Delivery</label></li>
													</ul>
												</div>
											</div>
											<div class="order-item-status">
												<div class="order-schedule-list">
													<p><b class="text-contrast">ORDER SCHEDULE</b></p>
													<div style="margin-bottom: 10px;">
														<label for="deliver_now" class="zero-gaps">
															<input type="radio" class="zero-gaps" id="deliver_now" js-event="deliveryDate" name="order_schedule" value="deliver_now"> Now
														</label>
														<?php
															$driving_distance = [];
															foreach ($baskets as $location_id => $basket): ?>
															<?php foreach ($basket as $key => $product): ?>
																<?php
																	$farm_location = $product['rawdata']['farm_location'];
																	$driving_distance[$location_id] = get_driving_distance([
																		['lat' => $farm_location['lat'], 'lng' => $farm_location['lng']],
																		['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
																	]);
																?>
															<?php endforeach ?>
														<?php endforeach;
															$ETA = 0;
															if (count($driving_distance)) {
																foreach ($driving_distance as $key => $drive) {
																	$ETA += (float)$drive['durationval'];
																}
															}
														?>
														<?php if ($ETA > 0): ?>
															<small class="text-gray">(ETA: <?php echo round($ETA / 60, 1);?> mins)</small>
														<?php endif ?>
													</div>
													<div>
														<label for="order_schedule">
															<input type="radio" id="order_schedule" class="zero-gaps" name="order_schedule" js-event="deliveryDate"> Schedule
														</label>
														<small class="text-gray">ETA: Unspecified</small>
														<input type="date" class="form-control" name="delivery_date" min="<?php echo date("Y-m-d"); ?>" disabled="disabled" />
													</div>
												</div>
											</div>
											<div class="zero-gaps">
												<div class="tender-amount-parent">
													<div class="tender-amount-body">
														<?php
															$grand_total = 0;
															foreach ($baskets as $location_id => $basket): ?>
															<?php foreach ($basket as $key => $product): 
																$details = $product['rawdata']['basket_details'];
																$item_total = (int)$product['quantity'] * (float)$details['price'];
																$grand_total += $item_total;
																?>
																<p class="product-amount zero-gaps">&#x20b1; <b js-elem="sub-itemtotal" js-element="itemtotal-<?php echo $product['id'];?>"><?php echo number_format($item_total);?></b></p>
															<?php endforeach ?>
														<?php endforeach ?>
													</div>
													<hr style="border-color:#aaa;margin:5px 0;">
													<h4 class="total-amount text-contrast"><span>&#x20b1;</span> <b js-element="grandtotal"><?php echo number_format($grand_total);?></b></h4>
												</div>
												<button class="btn btn-contrast btn-block"><b>CHECKOUT<i class="fa fa-angle-right icon-right"></i></b></button>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				<?php else : ?>
					<h4 style="padding:15px;">Fresh veggies at your doorstep in minutes, <a href="marketplace/" class="text-link">shop now!</a></h4>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>