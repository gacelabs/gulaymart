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
						<form action="admin/stats/counts" method="post" class="input-group form-validate" data-ajax="1" data-disable="enter">
							<input type="hidden" name="tables" value="users,user_farms" />
							<select class="form-control" name="updated">
								<option value="alltime">All Time</option>
								<option value="today">Today (<?php echo date('M d');?>)</option>
								<option value="lastmonth">Last Month</option>
								<option value="yeartodate">Year To Date (<?php echo date('Y');?>)</option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">Apply</button>
							</span>
						</form>
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
						<form action="admin/bookings/counts" method="post" class="input-group form-validate" data-ajax="1" data-disable="enter">
							<input type="hidden" name="tables" value="baskets_merge" />
							<select class="form-control" name="updated">
								<option value="alltime">All Time</option>
								<option value="today">Today (<?php echo date('M d');?>)</option>
								<option value="lastmonth">Last Month</option>
								<option value="yeartodate">Year To Date (<?php echo date('Y');?>)</option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">Apply</button>
							</span>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="row" style="margin-bottom: 10px;">
			<div class="booking-parent col-sm-4 col-lg-6 col-md-12">
				<div class="booking-heading">
					<ul class="spaced-list between cron-infos">
						<li><small>Posting Orders...</small></li>
						<li><small js-date><?php echo date('l, F j, Y');?></small></li>
						<li><small>Time: <span js-time><?php echo date('g:i:s A');?></span></small></li>
					</ul>
				</div>
				<div class="booking-body">
					<div class="sender-info-form">
						<p><b>Pushing Information</b><b id="cron-sequence-next" class="pull-right hide">Next run: <i></i></b></p>
						<div id="cron-sequence" style="background-color: black; color: whitesmoke; padding: 10px; position: relative; height: 100px; overflow-y: scroll;">
							<?php print_cron_log('sequence');?>
						</div>
					</div>
				</div>
				<div class="booking-footer"></div>
			</div>

			<div class="booking-parent col-sm-4 col-lg-6 col-md-12">
				<div class="booking-heading">
					<ul class="spaced-list between cron-infos">
						<li><small>Receiving Orders...</small></li>
						<li><small js-date><?php echo date('l, F j, Y');?></small></li>
						<li><small>Time: <span js-time><?php echo date('g:i:s A');?></span></small></li>
					</ul>
				</div>
				<div class="booking-body">
					<div class="sender-info-form">
						<p><b>Pulling Information</b><b id="cron-returns-next" class="pull-right hide">Next run: <i></i></b></p>
						<div id="cron-returns" style="background-color: black; color: whitesmoke; padding: 10px; position: relative; height: 100px; overflow-y: scroll;">
							<?php print_cron_log('returns');?>
						</div>
					</div>
				</div>
				<div class="booking-footer"></div>
			</div>
		</div>
	</div>
</div>