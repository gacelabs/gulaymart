<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<div class="dash-panel-right-canvas">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dash-panel theme">
					<ul class="inline-list dash-panel-top">
						<li><a href="transactions/orders/" class="hideshow-btn <?php in_array_echo("orders", $middle['body_class'], "active");?>"><h3>My Orders</h3></a></li>
						<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_placed"><h3>Placed</h3></a></li>
						<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_delivery"><h3>On Delivery</h3></a></li>
						<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_received"><h3>Received</h3></a></li>
						<li><a href="javascript:;" class="hideshow-btn" hideshow-target="order_cancelled"><h3>Cancelled</h3></a></li>
					</ul>
					<div class="dash-panel-middle hideshow-container">
						<div class="order-list-container">
							<div class="order-item-container">
								<div style="margin-bottom: 15px;">
									<p class="zero-gaps"><span class="color-grey">Oder ID:</span> 5g4h3jk</p>
									<p class="color-grey"><small>March 1, 2021 @ 3:30pm</small></p>
								</div>
								<div class="order-item">
									<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
									<div class="order-info-container">
										<div class="order-item-title">
											<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
										</div>
										<div class="order-item-qty">
											<p><span class="color-grey">Qty:</span> 1 <span class="color-grey">/ kilo</span></p>
										</div>
										<div class="order-item-price">
											<p><span class="color-grey">&#x20b1;</span> 100</p>
										</div>
										<div class="order-item-status">
											<p class="text-capsule">Placed</p>
										</div>
									</div>
								</div>
							</div>
							<div class="order-item-container">
								<div style="margin-bottom: 15px;">
									<p class="zero-gaps"><span class="color-grey">Oder ID:</span> 5g4h3jk</p>
									<p class="color-grey"><small>March 1, 2021 @ 3:30pm</small></p>
								</div>
								<div class="order-item">
									<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
									<div class="order-info-container">
										<div class="order-item-title">
											<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
										</div>
										<div class="order-item-qty">
											<p><span class="color-grey">Qty:</span> 1 <span class="color-grey">/ kilo</span></p>
										</div>
										<div class="order-item-price">
											<p><span class="color-grey">&#x20b1;</span> 100</p>
										</div>
										<div class="order-item-status">
											<p class="text-capsule on-delivery">On Delivery</p>
											<p><small class="color-grey">March 1, 2020 @ 3:36pm</small></p>
										</div>
									</div>
								</div>
							</div>
							<div class="order-item-container">
								<div style="margin-bottom: 15px;">
									<p class="zero-gaps"><span class="color-grey">Oder ID:</span> 5g4h3jk</p>
									<p class="color-grey"><small>March 1, 2021 @ 3:30pm</small></p>
								</div>
								<div class="order-item">
									<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
									<div class="order-info-container">
										<div class="order-item-title">
											<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
										</div>
										<div class="order-item-qty">
											<p><span class="color-grey">Qty:</span> 1 <span class="color-grey">/ kilo</span></p>
										</div>
										<div class="order-item-price">
											<p><span class="color-grey">&#x20b1;</span> 100</p>
										</div>
										<div class="order-item-status">
											<p class="text-capsule received">Received</p>
											<p><small class="color-grey">March 1, 2020 @ 3:54pm</small></p>
										</div>
									</div>
								</div>
							</div>
							<div class="order-item-container">
								<div style="margin-bottom: 15px;">
									<p class="zero-gaps"><span class="color-grey">Oder ID:</span> 5g4h3jk</p>
									<p class="color-grey"><small>March 1, 2021 @ 3:30pm</small></p>
								</div>
								<div class="order-item">
									<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
									<div class="order-info-container">
										<div class="order-item-title">
											<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
										</div>
										<div class="order-item-qty">
											<p><span class="color-grey">Qty:</span> 1 <span class="color-grey">/ kilo</span></p>
										</div>
										<div class="order-item-price">
											<p><span class="color-grey">&#x20b1;</span> 100</p>
										</div>
										<div class="order-item-status">
											<p class="text-capsule cancelled">Cancelled</p>
											<p><small class="color-grey">No riders found.</small></p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php $this->view('templates/transactions/placed'); ?>

						<?php $this->view('templates/transactions/delivery'); ?>

						<?php $this->view('templates/transactions/received'); ?>

						<?php $this->view('templates/transactions/cancelled'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>