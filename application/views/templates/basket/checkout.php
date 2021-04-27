<div id="checkout_container">
	<div class="container">
		<div class="row">
			<?php if ($data['baskets']): ?>
				<div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
					<div id="order_summary_list">
						<div class="order-summary-top hidden-sm hidden-xs">
							<div class="text-left">
								<p>PRODUCT</p>
							</div>
							<div class="text-left">
								<p>PRICE</p>
							</div>
							<div class="text-right">
								<p>QUANTITY</p>
							</div>
						</div>

						<div class="order-summary-middle">
							<?php foreach ($data['baskets'] as $location_id => $baskets): ?>
								<div class="checkout-item-container">
									<div class="checkout-item-top">
										<p class="zero-gaps text-ellipsis text-caps"><span class="text-gray">SOLD BY:</span> <?php echo ucwords($baskets['seller']['name']);?></p>
									</div>
									<div class="checkout-item-middle">
										<?php
											foreach ($baskets['order_details'] as $order_type => $orders) {
												$this->view('looping/checkout_item', ['items' => $orders, 'farm' => $baskets['seller']]);
											}
										?>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12" id="price_summary_container">
					<div class="price-summary-body">
						<form action="basket/place_order" method="post">
							<div class="price-summary-top hidden-xs">
								<button class="btn btn-block btn-cta"><b>PLACE ORDER NOW<i class="fa fa-chevron-right icon-right"></i></b></button>
							</div>
							<div class="price-summary-middle">
								<ul class="spaced-list between" style="margin-bottom:5px;">
									<li><small class="elem-block text-gray">DELIVERS TO:</small></li>
									<li><small class="elem-block"><b>TODAY</b></small></li>
								</ul>
						 		
							 	<div class="summary-shipping-address">
									<div class="summary-grid">
										<i class="fa fa-id-badge text-contrast text-center"></i>
										<p class="zero-gaps"><?php echo $current_profile['fullname'];?></p>
									</div>
									<div class="summary-grid">
										<i class="fa fa-map-marker text-contrast text-center"></i>
										<?php if ($current_profile['shippings']): ?>
											<?php foreach ($current_profile['shippings'] as $key => $shipping): ?>
												<?php if ($shipping['active'] == 1): ?>
													<div>
														<p class="zero-gaps"><?php echo $shipping['address_1'];?></p>
														<small class="text-gray elem-block"><?php echo $shipping['address_2'];?></small>
													</div>
													<?php break; ?>
												<?php endif ?>
											<?php endforeach ?>
										<?php endif ?>
									</div>
									<div class="summary-grid">
										<i class="fa fa-phone text-contrast text-center"></i>
										<p class="zero-gaps"><?php echo $current_profile['profile']['phone'];?></p>
									</div>
							 	</div>
							 	<div class="price-cta-container">
						 			<p class="zero-gaps text-center"><b class="text-contrast">SUMMARY</b></p>
							 		<div class="price-tally-container">
							 			<?php $final_total = 0; ?>
										<?php foreach ($data['baskets'] as $location_id => $baskets): ?>
							 				<?php $toktok_pricing = $baskets['toktok_details']['pricing']; ?>
											<div class="price-tally-per-seller">
												<div class="price-tally-grid">
													<div>
														<div class="ellipsis-container">
															<p class="zero-gaps text-caps"><?php echo $baskets['seller']['name'];?></p>
														</div>
														<small class="elem-block text-gray"><i>Inclusive of delivery fee (&#x20b1; <?php echo $toktok_pricing['price'];?>)</i></small>
													</div>
													<?php
														$sub_total = 0;
														foreach ($baskets['order_details'] as $order_type => $orders) {
															foreach ($orders as $index => $order) {
																$sub_total += $order['rawdata']['details']['price'] * $order['quantity'];
															}
														}
														$final_total += $sub_total + $toktok_pricing['price'];
													?>
													<div class="text-right">
														<p class="zero-gaps">&#x20b1; <b><?php echo number_format($sub_total + $toktok_pricing['price']);?></b></p>
													</div>
												</div>
											</div>
										<?php endforeach ?>
							 		</div>
							 	</div>
							</div>
							<div class="cta-bottom-container">
								<small class="price-tally-pull-up hidden-lg hidden-md hidden-sm" js-event="priceTallyPullUp"><i class="fa fa-angle-right"></i></small>
								<button class="btn btn-block btn-lg btn-cta"><b>PLACE ORDER <span style="font-family: sans-serif;">&#x20b1;</span> <?php echo number_format($final_total);?></b></button>
							</div>
						</form>
					</div>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>
