<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="link_panel_top">
				<ul class="inline-list dashboard-panel-top">
					<li><a href="javascript:;" class="hideshow-btn active" hideshow-target="order_placed"><h4 class="zero-gaps">Placed</h4></a></li>
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

						$delivery = false;
						if ($data['orders'] AND isset($data['orders']['delivery'])) $delivery = $data['orders']['delivery'];
						$this->view('templates/transactions/delivery', ['delivery' => $delivery]);

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
								<div class="order-item">
								<?php foreach ($bydate as $date => $order): ?>
									<div class="order-item-top">
										<p class="zero-gaps">ORDER ID: <a href="" class="text-link order-id">5g4h3jk</a></p>
										<p class="zero-gaps"><?php echo strtoupper(ucwords($status));?>: <b><?php echo $date;?></b></p>
									</div>
									<?php $this->view('looping/order_item', ['order'=>$order, 'large_status'=>strtoupper(ucwords($status)), 'status_class'=>$status]);?>
								<?php endforeach ?>
								</div>
							<?php endforeach ?>
						<?php else: ?>
							<h4 style="padding:15px;margin:0;">Fresh veggies at your doorstep in minutes, <a href="marketplace/" class="text-link">shop now!</a></h4>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>