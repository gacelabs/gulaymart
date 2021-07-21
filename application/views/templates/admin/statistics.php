<div class="body-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<div class="panel panel-success">
					<div class="panel-heading">
						<ul class="spaced-list between">
							<li>
								<b>USER SIGN UPS</b></div>
							</li>
						</ul>
					<div class="panel-body">
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-id-badge"></i>Users
							</div>
							<div class="list-grid-item text-right">
								<span><b id="users-count"><?php echo (isset($data['users_count']) ? $data['users_count'] : 0);?></b></span>
							</div>
						</div>
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-leaf"></i>Farmers
							</div>
							<div class="list-grid-item text-right">
								<span><b id="farmers-count"><?php echo (isset($data['farmers_count']) ? $data['farmers_count'] : 0);?></b></span>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="input-group">
							<select class="form-control">
								<option>Today (<?php echo date('M d');?>)</option>
								<option>Last Month</option>
								<option>Year To Date (<?php echo date('Y');?>)</option>
								<option>All Time</option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Apply</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="panel panel-success">
					<div class="panel-heading">
						<ul class="spaced-list between">
							<li>
								<b>BOOKINGS</b>
							</li>
							<li>
								<a href="admin/bookings/"><i class="fa fa-angle-right"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-check"></i>Successful
							</div>
							<div class="list-grid-item text-right">
								<span><b id="bookings-succeeded"><?php echo (isset($data['bookings_count']) ? $data['bookings_count']['succeeded'] : 0);?></b></span>
							</div>
						</div>
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-times"></i>Failed
							</div>
							<div class="list-grid-item text-right">
								<span><b id="bookings-failed"><?php echo (isset($data['bookings_count']) ? $data['bookings_count']['failed'] : 0);?></b></span>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="input-group">
							<select class="form-control">
								<option>Today (<?php echo date('M d');?>)</option>
								<option>Last Month</option>
								<option>Year To Date (<?php echo date('Y');?>)</option>
								<option>All Time</option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Apply</button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>