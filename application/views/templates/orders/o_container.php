<div class="orders-container" id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="trans-navbar-container">
				<small class="elem-block"><b>FILTER STATUS</b></small>
				<div class="trans-navbar-grid">
					<div>
						<a href="orders/placed/" data-menu="orders" data-nav="placed">
							<div class="trans-navbar-pill <?php in_array_echo("orders-placed", $middle['body_class'], "active");?>">
								Placed
								<kbd class="<?php if(!$data['counts']['placed']): ?>no-count<?php endif; ?>"><?php if($data['counts']['placed']): ?><?php echo $data['counts']['placed'];?><?php endif; ?></kbd>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/for-pick-up" data-menu="orders" data-nav="for-pick-up">
							<div class="trans-navbar-pill <?php in_array_echo("orders-for+pick+up", $middle['body_class'], "active");?>">
								For Pick Up
								<kbd class="<?php if(!$data['counts']['for+pick+up']): ?>no-count<?php endif; ?>"><?php if($data['counts']['for+pick+up']): ?><?php echo $data['counts']['for+pick+up'];?><?php endif; ?></kbd>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/on-delivery" data-menu="orders" data-nav="on-delivery">
							<div class="trans-navbar-pill <?php in_array_echo("orders-on+delivery", $middle['body_class'], "active");?>">
								On Delivery
								<kbd class="<?php if(!$data['counts']['on+delivery']): ?>no-count<?php endif; ?>"><?php if($data['counts']['on+delivery']): ?><?php echo $data['counts']['on+delivery'];?><?php endif; ?></kbd>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/received" data-menu="orders" data-nav="received">
							<div class="trans-navbar-pill <?php in_array_echo("orders-received", $middle['body_class'], "active");?>">
								Received
								<kbd class="<?php if(!$data['counts']['received']): ?>no-count<?php endif; ?>"><?php if($data['counts']['received']): ?><?php echo $data['counts']['received'];?><?php endif; ?></kbd>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/cancelled" data-menu="orders" data-nav="cancelled">
							<div class="trans-navbar-pill cancelled<?php in_array_echo("orders-cancelled", $middle['body_class'], " active");?>">
								Cancelled
								<kbd class="<?php if(!$data['counts']['cancelled']): ?>no-count<?php endif; ?>" style="background-color:#a9a9a9;"><?php if($data['counts']['cancelled']): ?><?php echo $data['counts']['cancelled'];?><?php endif; ?></kbd>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" js-element="orders-panel">
			<?php if ($data['orders']): ?>
				<!-- per farm location -->
				<?php foreach ($data['orders'] as $key => $orders): ?>
					<?php
						$this->view('templates/orders/o_order_items', [
							'orders' => $orders,
							'status' => $data['status'],
							'status_id' => $data['status_id'],
						]);
					?>
				<?php endforeach ?>
			<?php endif ?>
			<div class="no-records-ui<?php if (!empty($data['orders'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a loader="1" href="" class="btn btn-sm btn-contrast">Marketplace <i class="fa fa-leaf" style="border:1px solid #fff;padding:4px;border-radius:100px;"></i></a></p>
			</div>
		</div>
	</div>
</div>