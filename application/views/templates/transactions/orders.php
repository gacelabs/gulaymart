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
						$this->view('templates/transactions/placed');
						$this->view('templates/transactions/delivery');
						$this->view('templates/transactions/received');
						$this->view('templates/transactions/cancelled');
					?>

					<div class="order-list-container hide" id="all_orders">
						<div class="order-item">
							<div class="order-item-top">
								<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
								<p class="zero-gaps">PLACED: <b>March 1, 2021 @ 3:30pm</b></p>
							</div>
							<div class="order-item-middle">
								<div class="order-item-list">
									<div class="order-item-inner">
										<p class="zero-gaps">Ema and Ava Farm</p>
										<div class="order-item-grid">
											<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
											<div class="order-info-container">
												<div class="order-item-title">
													<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
												</div>
												<p class="zero-gaps">&#x20b1; <b>50</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
												<p class="product-total">Total &#x20b1; <b>150</b></p>
											</div>
										</div>
									</div>

									<div class="order-item-inner">
										<p class="zero-gaps">Mavis and Marcus Plantation</p>
										<div class="order-item-grid">
											<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
											<div class="order-info-container">
												<div class="order-item-title">
													<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
												</div>
												<p class="zero-gaps">&#x20b1; <b>150</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
												<p class="product-total">Total &#x20b1; <b>350</b></p>
											</div>
										</div>
									</div>
								</div>

								<div class="tender-amount-grid">
									<div class="order-item-status">
										<ul class="inline-list">
											<li class="text-capsule icon-left status-placed">Placed</li>
										</ul>
									</div>
									<div class="tender-amount-parent">
										<div class="tender-amount-body">
											<p class="product-amount zero-gaps">&#x20b1; <b>150</b></p>
											<p class="product-amount zero-gaps">+ &#x20b1; <b>350</b></p>
										</div>
										<hr style="border-color:#aaa;margin:5px 0;">
										<h4 class="total-amount text-contrast zero-gaps"><span>&#x20b1;</span> <b>500</b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="order-item">
							<div class="order-item-top">
								<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
								<p class="zero-gaps">PLACED: <b>March 1, 2021 @ 3:30pm</b></p>
							</div>
							<div class="order-item-middle">
								<div class="order-item-inner">
									<p class="zero-gaps">Ema and Ava Farm</p>
									<div class="order-item-grid">
										<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
										<div class="order-info-container">
											<div class="order-item-title">
												<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
											</div>
											<p class="zero-gaps">&#x20b1; <b>50</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
											<p class="product-total">Total &#x20b1; <b>150</b></p>
										</div>
									</div>
								</div>

								<div class="order-item-inner">
									<p class="zero-gaps">Mavis and Marcus Plantation</p>
									<div class="order-item-grid">
										<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
										<div class="order-info-container">
											<div class="order-item-title">
												<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
											</div>
											<p class="zero-gaps">&#x20b1; <b>150</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
											<p class="product-total">Total &#x20b1; <b>350</b></p>
										</div>
									</div>
								</div>

								<div class="tender-amount-grid">
									<div class="order-item-status">
										<ul class="inline-list">
											<li class="text-capsule icon-left status-delivery">On Delivery</li>
											<li class="text-capsule icon-left blue">ETA 30 mins</li>
										</ul>
									</div>
									<div class="tender-amount-parent">
										<div class="tender-amount-body">
											<p class="product-amount zero-gaps">&#x20b1; <b>150</b></p>
											<p class="product-amount zero-gaps">+ &#x20b1; <b>350</b></p>
										</div>
										<hr style="border-color:#aaa;margin:5px 0;">
										<h4 class="total-amount text-contrast zero-gaps"><span>&#x20b1;</span> <b>500</b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="order-item">
							<div class="order-item-top">
								<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
								<p class="zero-gaps">PLACED: <b>March 1, 2021 @ 3:30pm</b></p>
							</div>
							<div class="order-item-middle">
								<div class="order-item-inner">
									<p class="zero-gaps">Ema and Ava Farm</p>
									<div class="order-item-grid">
										<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
										<div class="order-info-container">
											<div class="order-item-title">
												<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
											</div>
											<p class="zero-gaps">&#x20b1; <b>50</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
											<p class="product-total">Total &#x20b1; <b>150</b></p>
										</div>
									</div>
								</div>

								<div class="order-item-inner">
									<p class="zero-gaps">Mavis and Marcus Plantation</p>
									<div class="order-item-grid">
										<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
										<div class="order-info-container">
											<div class="order-item-title">
												<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
											</div>
											<p class="zero-gaps">&#x20b1; <b>150</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
											<p class="product-total">Total &#x20b1; <b>350</b></p>
										</div>
									</div>
								</div>

								<div class="tender-amount-grid">
									<div class="order-item-status">
										<ul class="inline-list">
											<li class="text-capsule icon-left status-received">Received</li>
											<li class="text-capsule icon-left blue">30 mins</li>
										</ul>
									</div>
									<div class="tender-amount-parent">
										<div class="tender-amount-body">
											<p class="product-amount zero-gaps">&#x20b1; <b>150</b></p>
											<p class="product-amount zero-gaps">+ &#x20b1; <b>350</b></p>
										</div>
										<hr style="border-color:#aaa;margin:5px 0;">
										<h4 class="total-amount text-contrast zero-gaps"><span>&#x20b1;</span> <b>500</b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="order-item">
							<div class="order-item-top">
								<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
								<p class="zero-gaps">PLACED: <b>March 1, 2021 @ 3:30pm</b></p>
							</div>
							<div class="order-item-middle">
								<div class="order-item-inner">
									<p class="zero-gaps">Ema and Ava Farm</p>
									<div class="order-item-grid">
										<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
										<div class="order-info-container">
											<div class="order-item-title">
												<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
											</div>
											<p class="zero-gaps">&#x20b1; <b>50</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
											<p class="product-total">Total &#x20b1; <b>150</b></p>
										</div>
									</div>
								</div>

								<div class="order-item-inner">
									<p class="zero-gaps">Mavis and Marcus Plantation</p>
									<div class="order-item-grid">
										<div class="order-item-image" style="background-image: url('assets/images/lettuce-house.jpg');"></div>
										<div class="order-info-container">
											<div class="order-item-title">
												<p><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a></p>
											</div>
											<p class="zero-gaps">&#x20b1; <b>150</b> / bundle <span class="qty-divider">x Quantity: <b>2</b></span> + Shipping fee: &#x20b1; <b>50</b></p>
											<p class="product-total">Total &#x20b1; <b>350</b></p>
										</div>
									</div>
								</div>

								<div class="tender-amount-grid">
									<div class="order-item-status">
										<ul class="inline-list">
											<li class="text-capsule icon-left status-cancelled">Cancelled</li>
											<li class="text-capsule icon-left blue">By You</li>
										</ul>
									</div>
									<div class="tender-amount-parent">
										<div class="tender-amount-body">
											<p class="product-amount zero-gaps">&#x20b1; <b>150</b></p>
											<p class="product-amount zero-gaps">+ &#x20b1; <b>350</b></p>
										</div>
										<hr style="border-color:#aaa;margin:5px 0;">
										<h4 class="total-amount text-contrast zero-gaps"><span>&#x20b1;</span> <b>500</b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>