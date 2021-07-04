<div class="orders-container" id="dashboard_panel_right">
	<div class="row">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="trans-navbar-container">
				<small class="elem-block"><b>FILTER STATUS</b></small>
				<div class="trans-navbar-grid">
					<div>
						<a href="orders/placed/" data-nav="placed">
							<div class="trans-navbar-pill <?php in_array_echo("orders-placed", $middle['body_class'], "active");?>">
								Placed
								<?php if($data['counts']['placed']): ?>
								<kbd><?php echo $data['counts']['placed'];?></kbd>
								<?php endif; ?>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/for-pick-up" data-nav="for-pick-up">
							<div class="trans-navbar-pill <?php in_array_echo("orders-for+pick+up", $middle['body_class'], "active");?>">
								For Pick Up
								<?php if($data['counts']['for+pick+up']): ?>
								<kbd><?php echo $data['counts']['for+pick+up'];?></kbd>
								<?php endif; ?>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/on-delivery" data-nav="on-delivery">
							<div class="trans-navbar-pill <?php in_array_echo("orders-on+delivery", $middle['body_class'], "active");?>">
								On Delivery
								<?php if($data['counts']['on+delivery']): ?>
								<kbd><?php echo $data['counts']['on+delivery'];?></kbd>
								<?php endif; ?>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/received" data-nav="received">
							<div class="trans-navbar-pill <?php in_array_echo("orders-received", $middle['body_class'], "active");?>">
								Received
								<?php if($data['counts']['received']): ?>
								<kbd><?php echo $data['counts']['received'];?></kbd>
								<?php endif; ?>
							</div>
						</a>
					</div>
					<div>
						<a href="orders/cancelled" data-nav="cancelled">
							<div class="trans-navbar-pill cancelled<?php in_array_echo("orders-cancelled", $middle['body_class'], " active");?>">
								Cancelled
								<?php if($data['counts']['cancelled']): ?>
								<kbd style="background-color:#a9a9a9;"><?php echo $data['counts']['cancelled'];?></kbd>
								<?php endif; ?>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" js-element="orders-panel">
			<?php $this->view('templates/orders/o_order_items'); ?>
		</div>
	</div>
</div>