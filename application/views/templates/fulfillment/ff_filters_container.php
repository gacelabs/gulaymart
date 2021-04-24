<div class="row hidden-xs" id="ff_filters_container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs">
		<div>
			<div class="ff-navbar-grid">
				<div>
					<small class="elem-block" style="margin-bottom:5px;"><b>FARM LOCATIONS</b></small>
					<div class="input-group">
						<select class="form-control" aria-describedby="basic-addon1">
							<option selected>All farms</option>
							<option >Antipolo City</option>
							<option >SJDM City, Bulacan</option>
							<option >Malabon City</option>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Filter</button>
						</span>
					</div>
				</div>
				<div>
					<small class="elem-block" style="margin-bottom:5px;"><b>ORDER SCHEDULE</b></small>
					<div class="input-group">
						<select class="form-control" aria-describedby="basic-addon1">
							<option selected>Now</option>
							<option >Scheduled</option>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">Filter</button>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div>
			<small class="elem-block"><b>FILTER STATUS</b></small>
			<div class="ff-navbar-grid">
				<div>
					<a href="fulfillment/placed/">
						<div class="ff-navbar-pill <?php in_array_echo("ff-placed", $middle['body_class'], "active");?>">Placed <kbd>15</kbd></div>
					</a>
				</div>
				<div>
					<a href="fulfillment/pick-up">
						<div class="ff-navbar-pill <?php in_array_echo("ff-pick-up", $middle['body_class'], "active");?>">For Pick Up <kbd>15</kbd></div>
					</a>
				</div>
				<div>
					<a href="fulfillment/delivery">
						<div class="ff-navbar-pill <?php in_array_echo("ff-delivery", $middle['body_class'], "active");?>">On Delivery <kbd>15</kbd></div>
					</a>
				</div>
				<div>
					<a href="fulfillment/received">
						<div class="ff-navbar-pill <?php in_array_echo("ff-received", $middle['body_class'], "active");?>">Received <kbd>15</kbd></div>
					</a>
				</div>
				<div>
					<a href="fulfillment/cancelled">
						<div class="ff-navbar-pill <?php in_array_echo("ff-cancelled", $middle['body_class'], "active");?>">Cancelled <kbd>15</kbd></div>
					</a>
				</div>
			</div>
		</div>

		<div class="zero-gaps">
			<small class="elem-block"><b>ACTIONS</b></small>
			<div class="ff-navbar-grid-2">
				<div>
					<div class="input-group">
						<select class="form-control" aria-describedby="basic-addon1" js-event="actionSelect">
							<option value="0" selected disabled>Bulk action</option>
							<option value="1">For Pick Up</option>
							<option value="2">Cancelled</option>
						</select>
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" js-event="actionApplyBtn">Apply</button>
						</span>
					</div>
				</div>
				<div>
					<div class="form-group zero-gaps hide" id="ff_cancel_select_parent">
						<select class="form-control" aria-describedby="basic-addon1" js-event="cancelReasonSelect">
							<option value="0" selected disabled>Select a cancellation reason.</option>
							<option value="1">Out of stock product.</option>
							<option value="2">Delivery man missed the schedule.</option>
							<option value="3">Destination out of delivery range.</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>