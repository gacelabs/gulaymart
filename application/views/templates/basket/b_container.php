<div class="basket-container" id="dashboard_panel_right">
	<input type="hidden" id="min-date" value="<?php echo date("Y-m-d");?>">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" js-element="baskets-panel">
			<?php if ($data['baskets']): ?>
				<!-- per farm location -->
				<?php foreach ($data['baskets'] as $location_by_sched => $baskets): ?>
					<?php
						$key_data = explode('|', $location_by_sched);
						$location_id = $key_data[0];
						$sched = $key_data[1];
					?>
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
							<div class="text-right hidden-sm hidden-xs">
								<p><small class="elem-block"><b>WHEN</b> <i class="fa fa-question-circle text-gray" data-toggle="tooltip" data-placement="top" title="When do you need the product?"></i></small></p>
							</div>
							<div class="text-right">
								<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Remove all?" js-element="remove-all"><span class="text-danger">&times;</span></button>
							</div>
						</div>

						<div class="order-item-list">
							<?php foreach ($baskets['products'] as $index => $item): ?>
								<!-- per product -->
								<?php
									$product = $item['rawdata']['product'];
									$details = $item['rawdata']['details'];
									$photo_url = 'https://via.placeholder.com/50x50.png?text=No+Image';
									if ($product['photos'] AND isset($product['photos']['main'])) {
										$photo_url = $product['photos']['main']['url_path'];
									}
								?>
								<div class="order-grid-column order-item" js-element="item-id-<?php echo $item['id'];?>">
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
										<p class="zero-gaps">&#x20b1; <?php echo number_format($details['price']);?> / <?php echo $details['measurement'];?></p>
									</div>
									<div class="text-right hidden-sm hidden-xs">
										<div class="quantity buttons_added">
											<input type="button" value="-" class="minus" js-element="qty-btns"><input type="number" step="1" min="1" max="<?php echo $details['stocks'];?>" name="quantity" value="<?php echo $item['quantity'];?>" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" js-event="qty" js-id="<?php echo $item['id'];?>" js-price="<?php echo $details['price'];?>" /><input type="button" value="+" class="plus" js-element="qty-btns">
										</div>
									</div>
									<div class="text-right hidden-sm hidden-xs">
										<select class="form-control elem-block" js-event="orderWhenSelect">
											<option value="1"<?php str_has_value_echo(1, $item['order_type'], ' selected');?>>Today</option>
											<option value="2"<?php str_has_value_echo(2, $item['order_type'], ' selected');?>>Schedule</option>
										</select>
										<input type="date" class="form-control date-input<?php str_has_value_echo(1, $item['order_type'], ' hide');?>" js-element="delivery-date" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $item['schedule'];?>" />
									</div>
									<div class="text-right">
										<button class="btn btn-xs btn-default order-remove-btn" js-event="removeBasketItemBtn" data-id="<?php echo $item['id'];?>" data-location="<?php echo $location_id;?>"><span class="text-danger">&times;</span></button>
									</div>

									<div class="visible-sm visible-xs">
										<ul class="spaced-list between">
											<li>
												<ul class="spaced-list between">
													<li><p class="zero-gaps">&#x20b1; <?php echo number_format($details['price']);?> / <?php echo $details['measurement'];?></p></li>
													<li class="icon-right">
														<div class="quantity buttons_added">
															<input type="button" value="-" class="minus" js-element="qty-btns"><input type="number" step="1" min="1" max="<?php echo $details['stocks'];?>" name="quantity" value="<?php echo $item['quantity'];?>" title="Qty" class="input-text qty text" size="4" pattern="" inputmode="" js-event="qty" js-id="<?php echo $item['id'];?>" js-price="<?php echo $details['price'];?>" /><input type="button" value="+" class="plus" js-element="qty-btns">
														</div>
													</li>
												</ul>
											</li>
											<li>
												<select class="form-control elem-block" js-event="orderWhenSelect">
													<option value="1"<?php str_has_value_echo(1, $item['order_type'], ' selected');?>>Today</option>
													<option value="2"<?php str_has_value_echo(2, $item['order_type'], ' selected');?>>Schedule</option>
												</select>
												<input type="date" class="form-control date-input<?php str_has_value_echo(1, $item['order_type'], ' hide');?>" js-element="delivery-date" min="<?php echo date("Y-m-d"); ?>" value="<?php echo $item['schedule'];?>">
											</li>
										</ul>
									</div>
								</div>
							<?php endforeach ?>
						</div>

						<?php $farm = $baskets['farm'];?>

						<div class="order-grid-footer" js-element="location-id-<?php echo $location_id;?>">
							<div class="text-left order-footer-farm hidden-xs">
								<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
								<p class="zero-gaps"><a target="farm_<?php echo $farm['id'];?>" href="<?php storefront_url($farm, true);?>" class="text-link"><?php echo $farm['name'];?></a></p>
								<p class="zero-gaps"><?php echo $farm['city_prov'];?></p>
							</div>
							<div class="text-left order-footer-payment hidden-xs">
								<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
								<p class="zero-gaps">Cash On Delivery</p>
							</div>
							<div class="order-footer-total">
								<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
								<p class="hidden-xs" style="margin-bottom:3px;">
									<small class="elem-block">
										<b>
										<?php if ($item['status'] == 1): ?>
											PROCEED
										<?php else: ?>
											VERIFY
										<?php endif ?>
										</b>
									</small>
								</p>
								<div class="checkout-btn-container">
									<button class="btn btn-contrast btn-sm" js-element="checkout-data" js-json='<?php echo json_encode($baskets['checkout_data']);?>'>CHECKOUT<i class="fa fa-angle-right icon-right"></i></button>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif ?>
			<div class="no-records-ui<?php if (!empty($data['baskets'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="/" class="btn btn-sm btn-contrast">Marketplace</a></p>
			</div>
		</div>
	</div>
</div>