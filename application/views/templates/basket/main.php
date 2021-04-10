<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="link_panel_top">
				<ul class="inline-list dashboard-panel-top">
					<li><a href="basket/" class="hideshow-btn active"><h4 class="zero-gaps">My Basket</h4></a></li>
				</ul>
				<?php if ($data['baskets']): ?>
					<div class="dashboard-panel-middle hideshow-container">
						<div class="order-list-container">
							<?php foreach ($data['baskets'] as $timestamp => $baskets): ?>
								<div class="order-item">
									<div class="order-item-top">
										<p class="zero-gaps">ADDED: <b><?php echo $timestamp;?></b></p>
									</div>
									<div class="order-item-middle">
										<div class="order-item-list">
											<?php foreach ($baskets as $location_id => $basket): ?>
												<?php $this->view('looping/basket_item', ['product' => $basket]); ?>
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
														<small class="text-gray">(ETA: 30 mins)</small>
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
											<div>
												<div class="tender-amount-parent">
													<div class="tender-amount-body">
														<p class="product-amount zero-gaps">&#x20b1; <b>150</b></p>
														<p class="product-amount zero-gaps">+ &#x20b1; <b>350</b></p>
													</div>
													<hr style="border-color:#aaa;margin:5px 0;">
													<h4 class="total-amount text-contrast"><span>&#x20b1;</span> <b>500</b></h4>
												</div>
												<button class="btn btn-contrast btn-block"><b>CHECKOUT<i class="fa fa-angle-right icon-right"></i></b></button>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>