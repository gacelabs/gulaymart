<div class="basket-container" id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<!-- per farm location -->
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
						<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Remove all?"><span class="text-danger">&times;</span></button>
					</div>
				</div>

				<div class="order-item-list">
					<!-- per product -->
					<div class="order-grid-column order-item">
						<div class="media">
							<div class="media-left media-top">
								<img class="media-object" src="https://via.placeholder.com/50x50.png?text=50x50">
							</div>
							<div class="media-body">
								<p class="zero-gaps media-heading text-ellipsis"><a href="" class="text-link">Fresh Sweet White Onions</a></p>
								<div class="ellipsis-container">
									<p class="zero-gaps">Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus. Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.</p>
								</div>
							</div>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<p class="zero-gaps">&#x20b1; 140 / KILO</p>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<div class="quantity buttons_added">
								<input type="button" value="-" class="minus"><input type="number" step="1" min="1" max="5" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="" inputmode=""><input type="button" value="+" class="plus">
							</div>
						</div>
						<div class="text-right hidden-sm hidden-xs">
							<select class="form-control elem-block" js-event="orderWhenSelect">
								<option value="1">Today</option>
								<option value="2">Schedule</option>
							</select>
							<input type="date" class="form-control date-input hide" min="<?php echo date("Y-m-d"); ?>">
						</div>
						<div class="text-right">
							<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Remove product?"><span class="text-danger">&times;</span></button>
						</div>

						<div class="visible-sm visible-xs">
							<ul class="spaced-list between">
								<li>
									<ul class="spaced-list between">
										<li><p class="zero-gaps">&#x20b1; 140 / KILO</p></li>
										<li class="icon-right">
											<div class="quantity buttons_added">
												<input type="button" value="-" class="minus"><input type="number" step="1" min="1" max="5" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" pattern="" inputmode=""><input type="button" value="+" class="plus">
											</div>
										</li>
									</ul>
								</li>
								<li>
									<select class="form-control elem-block" js-event="orderWhenSelect">
										<option value="1">Today</option>
										<option value="2">Schedule</option>
									</select>
									<input type="date" class="form-control date-input hide" min="<?php echo date("Y-m-d"); ?>">
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="order-grid-footer">
					<div class="text-left order-footer-farm hidden-xs">
						<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
						<p class="zero-gaps"><a href="" class="text-link">Ema Margaret Farm</a></p>
						<p class="zero-gaps">Bagong Nayon, Antipolo City</p>
					</div>
					<div class="text-left order-footer-payment hidden-xs">
						<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
						<p class="zero-gaps">Cash On Delivery</p>
					</div>
					<div class="order-footer-total">
						<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
						<p class="hidden-xs" style="margin-bottom:3px;"><small class="elem-block"><b>PROCEED</b></small></p>
						<div class="checkout-btn-container">
							<button class="btn btn-contrast btn-sm">CHECKOUT<i class="fa fa-angle-right icon-right"></i></button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>