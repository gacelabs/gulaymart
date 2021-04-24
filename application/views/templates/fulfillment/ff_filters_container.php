<div class="row hidden-xs" id="ff_filters_container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
		<div class="ff-navbar-grid farm-order">
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>FARM LOCATIONS</b></small>
				<select class="form-control ff-navbar-pill">
					<option selected>All farms</option>
					<option >Antipolo City</option>
					<option >SJDM City, Bulacan</option>
					<option >Malabon City</option>
				</select>
			</div>
			<div class="grid-item">
				<small class="elem-block" style="margin-bottom:5px;"><b>ORDER SCHEDULE</b></small>
				<select class="form-control ff-navbar-pill">
					<option selected>Today</option>
					<option >Scheduled</option>
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
					<a href="fulfillment/placed/" class="ff-navbar-pill <?php in_array_echo("ff-placed", $middle['body_class'], "active");?>">Placed <kbd class="pull-right">15</kbd></a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/pick-up" class="ff-navbar-pill <?php in_array_echo("ff-pick-up", $middle['body_class'], "active");?>">For Pick Up <kbd class="pull-right">15</kbd></a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/delivery" class="ff-navbar-pill <?php in_array_echo("ff-delivery", $middle['body_class'], "active");?>">On Delivery <kbd class="pull-right">15</kbd></a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/received" class="ff-navbar-pill <?php in_array_echo("ff-received", $middle['body_class'], "active");?>">Received <kbd class="pull-right">15</kbd></a>
				</div>
				<div class="grid-item">
					<a href="fulfillment/cancelled" class="ff-navbar-pill <?php in_array_echo("ff-cancelled", $middle['body_class'], "active");?>">Cancelled <kbd class="pull-right">15</kbd></a>
				</div>
			</div>
		</div>
	</div>
</div>