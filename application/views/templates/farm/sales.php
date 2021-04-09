<div id="dashboard_panel_right">
	<?php $this->view('static/mobile_note'); ?>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme">
				<ul class="spaced-list between dashboard-panel-top">
					<li><h3 class="zero-gaps">Sales Metrics</h3></li>
				</ul>
				<div class="dashboard-panel-middle" id="sales_metrics">
					<div class="panel-grid-item">
						<div class="panel-grid-top">
							<ul class="spaced-list between">
								<li><h4 class="zero-gaps">Revenue</h4></li>
								<li class="text-gray">March 1, 2021</li>
							</ul>
						</div>
						<div class="panel-grid-middle">
							<h3 class="zero-gaps"><span class="text-gray">&#x20b1;</span> 5,492</h3>
						</div>
						<div class="panel-grid-footer">
							<ul class="spaced-list between">
								<li>vs yesterday</li>
								<li>2,945</li>
							</ul>
						</div>
					</div>
					<div class="panel-grid-item">
						<div class="panel-grid-top">
							<ul class="spaced-list between">
								<li><h4 class="zero-gaps">Visitors</h4></li>
								<li class="text-gray">&nbsp;</li>
							</ul>
						</div>
						<div class="panel-grid-middle">
							<h3 class="zero-gaps"><i class="fa fa-line-chart text-gray"></i> 1,083</h3>
						</div>
						<div class="panel-grid-footer">
							<ul class="spaced-list between">
								<li>vs yesterday</li>
								<li>2,945</li>
							</ul>
						</div>
					</div>
					<div class="panel-grid-item">
						<div class="panel-grid-top">
							<ul class="spaced-list between">
								<li><h4 class="zero-gaps">Revenue per buyer</h4></li>
								<li class="text-gray">&nbsp;</li>
							</ul>
						</div>
						<div class="panel-grid-middle">
							<h3 class="zero-gaps"><span class="text-gray">&#x20b1;</span> 376</h3>
						</div>
						<div class="panel-grid-footer">
							<ul class="spaced-list between">
								<li>vs yesterday</li>
								<li>2,945</li>
							</ul>
						</div>
					</div>
					<div class="panel-grid-item">
						<div class="panel-grid-top">
							<ul class="spaced-list between">
								<li><h4 class="zero-gaps">Best seller</h4></li>
								<li class="text-gray">&nbsp;</li>
							</ul>
						</div>
						<div class="panel-grid-middle ellipsis-container">
							<h3 class="zero-gaps">Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
						</div>
						<div class="panel-grid-footer">
							<ul class="spaced-list between">
								<li>units sold</li>
								<li>75</li>
							</ul>
						</div>
					</div>
					<div class="panel-grid-item">
						<div class="panel-grid-top">
							<ul class="spaced-list between">
								<li><h4 class="zero-gaps">Farm views</h4></li>
								<li class="text-gray">&nbsp;</li>
							</ul>
						</div>
						<div class="panel-grid-middle">
							<h3 class="zero-gaps"><i class="fa fa-line-chart text-gray"></i> 5,492</h3>
						</div>
						<div class="panel-grid-footer">
							<ul class="spaced-list between">
								<li>vs yesterday</li>
								<li>2,945</li>
							</ul>
						</div>
					</div>
					<div class="panel-grid-item">
						<div class="panel-grid-top">
							<ul class="spaced-list between">
								<li><h4 class="zero-gaps">Milestone</h4></li>
								<li class="text-gray">&nbsp;</li>
							</ul>
						</div>
						<div class="panel-grid-middle">
							<h3 class="zero-gaps"><span class="text-gray">&#x20b1;</span> 5,492</h3>
						</div>
						<div class="panel-grid-footer">
							<ul class="spaced-list between">
								<li>previous</li>
								<li>2,945</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="sales_performance">
				<canvas id="sales_metrics_chart"></canvas>
			</div>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="best_selling">
				<canvas id="best_seller_chart"></canvas>
			</div>
		</div>
	</div>
</div>