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
											<?php
												$last_location_id = 0; 
												foreach ($baskets as $key => $basket): ?>
												<?php $this->view('looping/basket_item', ['basket' => $basket, 'last_location_id' => $last_location_id]); 
												$last_location_id = $basket['location_id'];
												?>
											<?php endforeach ?>
										</div>

										<div class="tender-amount-grid">
											<div class="order-item-status">
												<div class="payment-method-list">
													<p><b class="text-contrast">PAYMENT METHOD</b></p>
													<!-- <ul class="inline-list">
														<li><label><input type="radio" class="zero-gaps" name="payment_method"> Cash On Delivery</label></li>
													</ul> -->
													<div>
														<label for="payment_method">
															<!-- <input type="checkbox" id="payment_method" class="zero-gaps" name="payment_method" js-event="payment_method" value="cod"> --> Cash On Delivery
															<!-- <input type="text" class="form-control input-xs" name="cod" disabled="disabled" placeholder="Enter item(s) price here" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" /> -->
														</label>
													</div>
													<!-- <p><b class="text-contrast">Rider will Collect Fee From</b></p>
													<div style="margin-bottom: 10px;">
														<label class="zero-gaps">
															<input type="radio" class="zero-gaps" name="collect_from" value="S"> Me
														</label>
														<label class="zero-gaps" style="padding-left: 10px !important;">
															<input type="radio" class="zero-gaps" name="collect_from" value="R"> Receiver
														</label>
													</div> -->
												</div>
											</div>
											<div class="order-item-status">
												<div class="order-schedule-list">
													<p><b class="text-contrast">ORDER SCHEDULE</b></p>
													<?php
														$etas = [];
														foreach ($baskets as $key => $basket) {
															$etas[$basket['location_id']] = (float) $basket['duration'];
														}
														$eta_duration = 0;
														foreach ($etas as $location_id => $duration) {
															$eta_duration += $duration;
														}
														$eta = $eta_duration;
													?>
													<div style="margin-bottom: 10px;">
														<label for="deliver_now" class="zero-gaps">
															<input type="radio" class="zero-gaps" id="deliver_now" js-event="deliveryDate" name="order_schedule" value="deliver_now" checked="checked"> Now
														</label>
														<small class="text-gray">(<?php echo compute_eta($eta);?>)</small>
													</div>
													<div>
														<label for="order_schedule">
															<input type="radio" id="order_schedule" class="zero-gaps" name="order_schedule" js-event="deliveryDate" value="deliver_scheduled"> Scheduled
														</label>
														<small class="text-gray"><!-- ETA: Unspecified --></small>
														<input type="date" class="form-control" name="delivery_date" min="<?php echo date("Y-m-d"); ?>" disabled="disabled" />
													</div>
												</div>
											</div>
											<div class="zero-gaps">
												<div class="tender-amount-parent">
													<div class="tender-amount-body">
														<?php
															$fees_per_farm = [];
															foreach ($baskets as $key => $basket) {
																$fees_per_farm[$basket['location_id']] = (float) $basket['fee'];
															}
															$shipping_fee = 0;
															foreach ($fees_per_farm as $location_id => $fee) {
																$shipping_fee += $fee;
															}
															$grand_total = $shipping_fee;
														?>
													</div>
													<hr style="border-color:#aaa;margin:5px 0;">
													<p class="product-amount zero-gaps"><small class="pull-left">Shipping fee</small>+ &#x20b1; <b js-elem="sub-itemtotal" js-check="shipping"><?php echo number_format($shipping_fee);?></b></p>
													<hr style="border-color:#aaa;margin:5px 0;">
													<h4 class="total-amount text-contrast"><span>&#x20b1;</span> <b js-element="grandtotal"><?php echo number_format($grand_total);?></b></h4>
												</div>
												<button class="btn btn-contrast btn-block disabled" js-element="checkout" disabled="disabled"><b>CHECKOUT<i class="fa fa-angle-right icon-right"></i></b></button>
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