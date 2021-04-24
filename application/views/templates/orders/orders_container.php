<div class="orders-container" id="dashboard_panel_right">
	<div class="row">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<div class="trans-navbar-container">
				<small class="elem-block"><b>FILTER STATUS</b></small>
				<div class="trans-navbar-grid">
					<div>
						<a href="orders/placed/">
							<div class="trans-navbar-pill <?php in_array_echo("orders-placed", $middle['body_class'], "active");?>">Placed <kbd>15</kbd></div>
						</a>
					</div>
					<div>
						<a href="orders/delivery">
							<div class="trans-navbar-pill <?php in_array_echo("orders-delivery", $middle['body_class'], "active");?>">On Delivery <kbd>15</kbd></div>
						</a>
					</div>
					<div>
						<a href="orders/received">
							<div class="trans-navbar-pill <?php in_array_echo("orders-received", $middle['body_class'], "active");?>">Received <kbd>15</kbd></div>
						</a>
					</div>
					<div>
						<a href="orders/cancelled">
							<div class="trans-navbar-pill <?php in_array_echo("orders-cancelled", $middle['body_class'], "active");?>">Cancelled <kbd>15</kbd></div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="order-table-item">

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
						<div class="text-right">
							<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Cancel all?"><span class="text-danger">&times;</span></button>
						</div>
					</div>

					<div class="order-item-list">
						<!-- per order -->
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
								<p class="zero-gaps">3</p>
							</div>
							<div class="text-right">
								<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Cancel product?"><span class="text-danger">&times;</span></button>
							</div>

							<div class="visible-sm visible-xs">
								<ul class="spaced-list between">
									<li><p class="zero-gaps">&#x20b1; 140 / KILO</p></li>
									<li class="icon-right"><p class="zero-gaps">x 3 QTY</p></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="order-grid-footer">
						<div class="order-footer-farm text-left hidden-xs">
							<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
							<p class="zero-gaps"><a href="" class="text-link">Ema Margaret Farm</a></p>
							<p class="zero-gaps">Bagong Nayon, Antipolo City</p>
						</div>
						<div class="order-footer-payment text-left hidden-xs">
							<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
							<p class="zero-gaps">Cash On Delivery</p>
							<p class="zero-gaps"></p>
						</div>
						<div class="text-left hidden-xs">
							<p style="margin-bottom:5px;"><small class="elem-block"><b>ORDER STATUS</b></small></p>
							<p class="zero-gaps"><span class="text-capsule status-placed">Placed</span></p>
						</div>
						<div class="order-footer-total">
							<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
							<p class="hidden-lg hidden-md hidden-sm text-center" style="padding-top:3px;margin:0;"><span class="text-capsule status-placed">Placed</span></p>
							<div>
								<p class="hidden-xs" style="margin-bottom:3px;"><small class="elem-block"><b>TOTAL</b></small></p>
								<p class="zero-gaps"><i>Delivery Fee:</i> 80 + &#x20b1; 420</p>
								<p style="border-top:1px solid #888;display:inline-block;padding:0 0 0 35px;margin:0;">&#x20b1; <b>500</b></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>