<div class="row hidden-xs" id="ff_filters_container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
		<div class="ff-navbar-grid farm-order">
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>FARM LOCATIONS</b></small>
				<select class="form-control ff-navbar-pill">
					<option selected>All farms</option>
					<?php if ($data['farm']): ?>
						<?php foreach ($data['farm']['farm_locations'] as $key => $location): ?>
							<option value="<?php echo $location['id'];?>"><?php echo $location['city'];?></option>
						<?php endforeach ?>
					<?php endif ?>
				</select>
			</div>
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>ORDER SCHEDULE</b></small>
				<select class="form-control ff-navbar-pill">
					<option selected value="1">Today</option>
					<option value="2">Scheduled</option>
				</select>
			</div>
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>&nbsp;</b></small>
				<button class="btn btn-default ff-navbar-pill">Filter <i class="fa fa-sort-amount-desc"></i></button>
			</div>
		</div>

		<div class="filter-status-container">
			<small class="elem-block"><b>FILTER STATUS</b></small>
			<div class="ff-navbar-grid filter-status">
				<div class="grid-item">
					<a href="fulfillment/placed/" class="ff-navbar-pill <?php in_array_echo("ff-placed", $middle['body_class'], "active");?>">
						Placed
						<?php if ($data['counts']['placed']): ?>
						<kbd class="pull-right"><?php echo $data['counts']['placed'];?></kbd>
						<?php endif; ?>
					</a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/for-pick-up" class="ff-navbar-pill <?php in_array_echo("ff-for+pick+up", $middle['body_class'], "active");?>">
						For Pick Up
						<?php if ($data['counts']['for+pick+up']): ?>
						<kbd class="pull-right"><?php echo $data['counts']['for+pick+up'];?></kbd>
						<?php endif; ?>
					</a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/delivery" class="ff-navbar-pill <?php in_array_echo("ff-on+delivery", $middle['body_class'], "active");?>">
						On Delivery
						<?php if ($data['counts']['on+delivery']): ?>
						<kbd class="pull-right"><?php echo $data['counts']['on+delivery'];?></kbd>
						<?php endif; ?>
					</a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/received" class="ff-navbar-pill <?php in_array_echo("ff-received", $middle['body_class'], "active");?>">
						Received
						<?php if ($data['counts']['received']): ?>
						<kbd class="pull-right"><?php echo $data['counts']['received'];?></kbd>
						<?php endif; ?>
					</a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/cancelled" class="ff-navbar-pill cancelled<?php in_array_echo("ff-cancelled", $middle['body_class'], " active");?>">
						Cancelled
						<?php if ($data['counts']['cancelled']): ?>
						<kbd style="background-color:#a9a9a9;" class="pull-right"><?php echo $data['counts']['cancelled'];?></kbd>
						<?php endif; ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>