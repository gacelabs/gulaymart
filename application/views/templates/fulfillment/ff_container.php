<div class="fulfillment-container" id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="trans-navbar-container">
				<small class="elem-block"><b>FILTER STATUS</b></small>
				<div class="trans-navbar-grid ff-navbar-grid filter-status">
					<div class="trans-navbar-pill grid-item <?php in_array_echo("ff-placed", $middle['body_class'], "active");?>">
						<a href="fulfillment/placed/" data-menu="fulfillments" data-nav="placed" class="ff-navbar-pill">
							Placed
							<kbd class="pull-right<?php if (!$data['counts']['placed']): ?> no-count<?php endif; ?>"><?php if ($data['counts']['placed']): ?><?php echo $data['counts']['placed'];?><?php endif; ?></kbd>
						</a>
					</div>
					<div class="trans-navbar-pill grid-item <?php in_array_echo("ff-for+pick+up", $middle['body_class'], "active");?>">
						<a href="fulfillment/for-pick-up" data-menu="fulfillments" data-nav="for-pick-up" class="ff-navbar-pill">
							For Pick Up
							<kbd class="pull-right<?php if (!$data['counts']['for+pick+up']): ?> no-count<?php endif; ?>"><?php if ($data['counts']['for+pick+up']): ?><?php echo $data['counts']['for+pick+up'];?><?php endif; ?></kbd>
						</a>
					</div>
					<div class="trans-navbar-pill grid-item <?php in_array_echo("ff-on+delivery", $middle['body_class'], "active");?>">
						<a href="fulfillment/on-delivery" data-menu="fulfillments" data-nav="on-delivery" class="ff-navbar-pill">
							On Delivery
							<kbd class="pull-right<?php if (!$data['counts']['on+delivery']): ?> no-count<?php endif; ?>"><?php if ($data['counts']['on+delivery']): ?><?php echo $data['counts']['on+delivery'];?><?php endif; ?></kbd>
						</a>
					</div>
					<div class="trans-navbar-pill grid-item <?php in_array_echo("ff-received", $middle['body_class'], "active");?>">
						<a href="fulfillment/received" data-menu="fulfillments" data-nav="received" class="ff-navbar-pill">
							Received
							<kbd class="pull-right<?php if (!$data['counts']['received']): ?> no-count<?php endif; ?>"><?php if ($data['counts']['received']): ?><?php echo $data['counts']['received'];?><?php endif; ?></kbd>
						</a>
					</div>
					<div class="trans-navbar-pill grid-item <?php in_array_echo("ff-cancelled", $middle['body_class'], "active");?>">
						<a href="fulfillment/cancelled" data-menu="fulfillments" data-nav="cancelled" class="ff-navbar-pill cancelled">
							Cancelled
							<kbd style="background-color:#a9a9a9;" class="pull-right<?php if (!$data['counts']['cancelled']): ?> no-count<?php endif; ?>"><?php if ($data['counts']['cancelled']): ?><?php echo $data['counts']['cancelled'];?><?php endif; ?></kbd>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" js-element="fulfill-panel">
			<?php if ($data['orders']): ?>
				<!-- per farm location -->
				<?php foreach ($data['orders'] as $key => $orders): ?>
					<?php
						$this->view('templates/fulfillment/ff_fulfill_item', [
							'farm' => $data['farm'],
							'orders' => $orders,
							'status_text' => $data['status'],
							'status_id' => $data['status_id'],
						]);
					?>
				<?php endforeach ?>
			<?php endif ?>
			<div class="no-records-ui<?php if (!empty($data['orders'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace <i class="fa fa-leaf" style="border:1px solid #fff;padding:4px;border-radius:100px;"></i></a></p>
			</div>
		</div>
	</div>
</div>