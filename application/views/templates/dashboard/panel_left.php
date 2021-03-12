<div class="col-lg-2 col-md-3 col-sm-3 col-xs-2 affix" id="dash_panel_left">
	<div class="dash-panel-left-container">
		<h3 class="text-center hidden-xs"><b><?php echo_message('Hello', 'firstname');?>!</b></h3>
		<div class="aside-nav-container">
			<div class="aside-nav-item">
				<a href="dashboard/" class="<?php in_array_echo("dashboard", $middle['body_class'], "active");?>">
					<i class="fa fa-tachometer"></i><span class="hidden-xs">Dashboard</span>
				</a>
			</div>
			<div class="aside-nav-item">
				<a href="farm/new-veggy/" class="<?php in_array_echo("farm", $middle['body_class'], "active");?>">
					<i class="fa fa-leaf"></i><span class="hidden-xs">My Farm</span>
				</a>
				<div class="aside-nav-child">
					<a href="farm/new-veggy/" class="<?php in_array_echo("new-veggy", $middle['body_class'], "active");?>">
						<i class="fa fa-pencil"></i><span class="hidden-xs">New veggy</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="farm/storefront" class="<?php in_array_echo("storefront", $middle['body_class'], "active");?>">
						<i class="fa fa-flag-o"></i><span class="hidden-xs">Storefront</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="farm/inventory" class="<?php in_array_echo("inventory", $middle['body_class'], "active");?>">
						<i class="fa fa-cubes"></i><span class="hidden-xs">Inventory</span>
					</a>
				</div>
			</div>
			<div><hr></div>
			<div class="aside-nav-item">
				<a href="transactions/orders/" class="<?php in_array_echo("transactions", $middle['body_class'], "active");?>">
					<i class="fa fa-exchange"></i><span class="hidden-xs">Transactions</span>
				</a>
				<div class="aside-nav-child">
					<a href="transactions/orders/" class="<?php in_array_echo("orders", $middle['body_class'], "active");?>">
						<i class="fa fa-shopping-basket"></i><span class="hidden-xs">Orders</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="transactions/messages/" class="<?php in_array_echo("messages", $middle['body_class'], "active");?>">
						<i class="fa fa-comment-o"></i><span class="hidden-xs">Messages</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="transactions/settings/" class="<?php in_array_echo("settings", $middle['body_class'], "active");?>">
						<i class="fa fa-cog"></i><span class="hidden-xs">Settings</span>
					</a>
				</div>
			</div>
			<div><hr></div>
			<div class="aside-nav-item">
				<a href="profile/" class="<?php in_array_echo("profile", $middle['body_class'], "active");?>">
					<i class="fa fa-id-badge"></i><span class="hidden-xs">Profile</span>
				</a>
				<div class="aside-nav-child">
					<a href="sign-out">
						<i class="fa fa-sign-out"></i><span class="hidden-xs">Sign out</span>
					</a>
				</div>
			</div>
			<div class="aside-nav-item">
				<a href="support/help-center/" class="<?php in_array_echo("help-center", $middle['body_class'], "active");?>">
					<i class="fa fa-question-circle"></i><span class="hidden-xs">Help Center</span>
				</a>
			</div>
		</div>
	</div>
</div>