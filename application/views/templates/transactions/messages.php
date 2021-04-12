<div id="dashboard_panel_right">
	<div class="row">
		<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-panel theme" id="link_panel_top">
				<ul class="inline-list dashboard-panel-top">
					<li><a href="transactions/messages" class="hideshow-btn active"><h4 class="zero-gaps">Notifications</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="msg_inquiries"><h4 class="zero-gaps">Inquiries</h4></a></li>
					<li><a href="javascript:;" class="hideshow-btn" hideshow-target="msg_feedbacks"><h4 class="zero-gaps">Feedbacks</h4></a></li>
				</ul>
				<div class="dashboard-panel-middle hideshow-container">
					<div class="notif-item">
						<div class="notif-item-top">
							<p class="zero-gaps"><i class="fa fa-leaf color-theme icon-left"></i><b>System Update</b> - <span class="color-grey">March 1, 2020</span> </p>
						</div>
						<div class="notif-item-middle">
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
							tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
							quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
							consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
							cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
							proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
						</div>
						<div class="notif-item-footer hide">
							<ul class="inline-list">
								<li><button class="btn btn-default normal-radius">Delete</button></li>
							</ul>
						</div>
					</div>

					<?php
						$this->view('templates/transactions/inquiries');
						$this->view('templates/transactions/feedbacks');
					?>
				</div>
			</div>
		</div>
	</div>
</div>