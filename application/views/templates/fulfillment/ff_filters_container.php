<div class="row" id="ff_filters_container">
	<div class="col-lg-12 col-md-12">
		<!-- <form class="ff-navbar-grid farm-order">
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>FARM LOCATIONS</b></small>
				<select class="form-control ff-navbar-pill" name="location-id">
					<option>All farms</option>
					<?php
					/*if ($data['farm']): 
						foreach ($data['farm']['farm_locations'] as $key => $location):*/ ?>
							<option value="<?php // echo $location['id'];?>"><?php // echo $location['city'];?></option>
					<?php /*endforeach 
					endif*/ ?>
				</select>
			</div>
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>ORDER SCHEDULE</b></small>
				<select class="form-control ff-navbar-pill" name="order-type">
					<option value="1">Today</option>
					<option value="2">Scheduled</option>
				</select>
			</div>
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>&nbsp;</b></small>
				<button class="btn btn-default ff-navbar-pill">Filter <i class="fa fa-sort-amount-desc"></i></button>
			</div>
		</form> -->
		<div class="filter-status-container">
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
</div>