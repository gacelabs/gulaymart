<div class="col-lg-10 col-md-9 col-sm-9 col-xs-10 left-affix-content" id="dash_panel_right">
	<div class="dash-panel-right-container">
		<?php $this->view('static/mobile_note'); ?>
		<div class="dash-panel-right-canvas">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dash-panel theme">
					<ul class="spaced-list between dash-panel-top">
						<li><h3>Sales Metrics</h3></li>
					</ul>
					<div class="dash-panel-middle" id="sales_metrics">
						<div class="panel-grid-item">
							<div class="panel-grid-top">
								<ul class="spaced-list between">
									<li><h4 class="zero-gaps">Revenue</h4></li>
									<li class="color-grey">March 1, 2021</li>
								</ul>
							</div>
							<div class="panel-grid-middle">
								<h3 class="zero-gaps"><span class="color-grey">&#x20b1;</span> 5,492</h3>
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
									<li class="color-grey">&nbsp;</li>
								</ul>
							</div>
							<div class="panel-grid-middle">
								<h3 class="zero-gaps"><i class="fa fa-line-chart color-grey"></i> 1,083</h3>
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
									<li class="color-grey">&nbsp;</li>
								</ul>
							</div>
							<div class="panel-grid-middle">
								<h3 class="zero-gaps"><span class="color-grey">&#x20b1;</span> 376</h3>
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
									<li class="color-grey">&nbsp;</li>
								</ul>
							</div>
							<div class="panel-grid-middle">
								<h3 class="zero-gaps ellipsis">Lorem ipsum dolor sit amet, consectetur adipisicing elit</h3>
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
									<li class="color-grey">&nbsp;</li>
								</ul>
							</div>
							<div class="panel-grid-middle">
								<h3 class="zero-gaps"><i class="fa fa-line-chart color-grey"></i> 5,492</h3>
							</div>
							<div class="panel-grid-footer">
								<ul class="spaced-list between">
									<li>vs yesterday</li>
									<li>2,945</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="dash-panel theme">
					<canvas id="sales_metrics_chart"></canvas>
				</div>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="dash-panel theme">
					<canvas id="best_seller_chart"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>