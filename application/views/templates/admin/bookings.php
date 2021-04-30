<div class="body-wrapper">
	<div class="container">
		<div class="visible-sm visible-xs">
			<div class="text-step-basic">
				<p class="text-center"><i class="fa fa-info-circle"></i></p>
				<div>
					<p>We are working hard to make Gulaymart a mobile friendly web app. In case of missing features, please use a desktop/ laptop.</p>
					<small>Back to <a href="marketplace" class="text-link">Marketplace</a></small>
				</div>
			</div>
		</div>

		<div class="row hidden-sm hidden-xs">
			<div class="col-lg-4 col-md-4">
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
								<span><b>102</b></span>
							</div>
						</div>
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-wrench"></i>Manual
							</div>
							<div class="list-grid-item text-right">
								<span><b>102</b></span>
							</div>
						</div>
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-times"></i>Failed
							</div>
							<div class="list-grid-item text-right">
								<span><b>102</b></span>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="input-group">
							<select class="form-control">
								<option>Today (<?php echo date('M d'); ?>)</option>
								<option>Last Month</option>
								<option>Year To Date (<?php echo date('Y'); ?>)</option>
								<option>All Time</option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Apply</button>
							</span>
						</div>
					</div>
				</div>

				<div class="panel panel-success">
					<div class="panel-heading">
						<ul class="spaced-list between">
							<li>
								<b>AUTOMATION</b>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-cog"></i><span tabindex="0" data-toggle="popover" data-placement="bottom" title="Master Script" data-content="This sets the script either enabled or disabled, just like a master switch. Switching this off, will set the succeeding orders to manual.">Master script <i class="fa fa-question-circle text-gray"></i></span>
							</div>
							<div class="list-grid-item text-right">
								<label class="switch">
									<input type="checkbox" checked>
									<span class="slider round"></span>
								</label>
							</div>
						</div>
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-cog"></i><span tabindex="0" data-toggle="popover" data-placement="bottom" title="Auto Booking" data-content="Set the number of orders to automatically book to Toktok. Succeeding orders will be set to manual.">Auto booking <i class="fa fa-question-circle text-gray"></i></span>
							</div>
							<div class="list-grid-item text-right">
								<input type="number" class="form-control" value="100">
							</div>
						</div>
						<div class="list-grid-parent">
							<div class="list-grid-item text-center">
								<i class="fa fa-cog"></i><span tabindex="0" data-toggle="popover" data-placement="bottom" title="Manual Interval" data-content="This sets how many manual orders to reach before it goes back to Auto booking.">Manual interval <i class="fa fa-question-circle text-gray"></i></span>
							</div>
							<div class="list-grid-item text-right">
								<input type="number" class="form-control" value="50">
							</div>
						</div>
					</div>
					<div class="panel-footer text-right">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Admin password">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button">Apply</button>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-8 col-md-8">
				<div>
					<div class="booking-parent">
						<div class="booking-heading">
							<ul class="spaced-list between">
								<li><small>Pending Booking</small></li>
								<li><small>Quezon City to Bulacan</small></li>
								<li><small>Delivery Fee: &#x20b1; 100</small></li>
							</ul>
						</div>
						<div class="booking-body">
							<div class="sender-info-form">
								<p><b>Operator Information</b></p>
								<div class="booking-form-grid">
									<div class="booking-form-grid-item">
										<small class="elem-block" tabindex="0" data-toggle="popover" data-placement="bottom" title="Referral Code" data-content="Receive your commission, enter your Toktok Referral Code.">Referral Code <i class="fa fa-question-circle"></i></small>
										<div class="form-group zero-gaps">
											<input type="text" class="form-control">
										</div>
									</div>
									<div class="booking-form-grid-item">
										<small class="elem-block" tabindex="0" data-toggle="popover" data-placement="bottom" title="Toktok Password" data-content="Toktok authenticates every booking with a Referral Code. Please enter your Toktok Operator password below.">Password <i class="fa fa-question-circle"></i></small>
										<div class="form-group zero-gaps">
											<input type="text" class="form-control">
										</div>
									</div>
									<div class="booking-form-grid-item">
										<small class="elem-block"><i class="fa fa-clock-o"></i> 90 seconds</small>
										<button class="btn btn-contrast">BOOK NOW (1 of 5)</button>
									</div>
								</div>
							</div>
						</div>
						<div class="booking-footer"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>