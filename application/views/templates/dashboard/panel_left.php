<div class="col-lg-2 col-md-3 col-sm-3 col-xs-2 affix" id="dash_panel_left">
	<div class="dash-panel-left-container">
		<h3 class="text-center hidden-xs"><b>Hello, Poi!</b></h3>
		<div class="aside-nav-container">
			<div class="aside-nav-item">
				<a href="" class="<?php if (in_array("dashboard", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-tachometer"></i><span class="hidden-xs">Dashboard</span>
				</a>
			</div>
			<div class="aside-nav-item">
				<a href="javascript:;" class="<?php if (in_array("farm", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-leaf"></i><span class="hidden-xs">My Farm</span>
				</a>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("new-veggy", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-pencil"></i><span class="hidden-xs">New veggy</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("storefront", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-flag-o"></i><span class="hidden-xs">Storefront</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("inventory", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-cubes"></i><span class="hidden-xs">Inventory</span>
					</a>
				</div>
			</div>
			<div><hr></div>
			<div class="aside-nav-item">
				<a href="javascript:;" class="<?php if (in_array("transactions", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-exchange"></i><span class="hidden-xs">Transactions</span>
				</a>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("orders", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-shopping-basket"></i><span class="hidden-xs">Orders</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("messages", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-comment-o"></i><span class="hidden-xs">Messages</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("settings", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-cog"></i><span class="hidden-xs">Settings</span>
					</a>
				</div>
			</div>
			<div><hr></div>
			<div class="aside-nav-item">
				<a href="" class="<?php if (in_array("farm", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-id-badge"></i><span class="hidden-xs">Profile</span>
				</a>
				<div class="aside-nav-child">
					<a href="sign-out">
						<i class="fa fa-sign-out"></i><span class="hidden-xs">Sign out</span>
					</a>
				</div>
			</div>
			<div class="aside-nav-item">
				<a href="" class="<?php if (in_array("farm", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-question-circle"></i><span class="hidden-xs">Help Center</span>
				</a>
			</div>
		</div>
	</div>
</div>