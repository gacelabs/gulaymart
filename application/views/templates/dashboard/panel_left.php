<div class="col-lg-2 affix" id="dash_panel_left">
	<div>
		<h3 class="text-center"><b>Hello, Poi!</b></h3>
		<div class="aside-nav-container">
			<div class="aside-nav-item">
				<a href="" class="<?php if (in_array("dashboard", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-tachometer"></i><span>Dashboard</span>
				</a>
			</div>
			<div class="aside-nav-item">
				<a href="javascript:;" class="<?php if (in_array("farm", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-leaf"></i><span>My Farm</span>
				</a>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("new-veggy", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-pencil"></i><span>New veggy</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("storefront", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-flag-o"></i><span>Storefront</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("inventory", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-cubes"></i><span>Inventory</span>
					</a>
				</div>
			</div>
			<div><hr></div>
			<div class="aside-nav-item">
				<a href="javascript:;" class="<?php if (in_array("transactions", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-exchange"></i><span>Transactions</span>
				</a>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("new-veggy", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-shopping-basket"></i><span>New orders</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("fulfilled", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-check"></i><span>Fulfilled</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("messages", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-comment-o"></i><span>Messages</span>
					</a>
				</div>
				<div class="aside-nav-child">
					<a href="" class="<?php if (in_array("settings", $middle['body_class'])) {echo "active";} ?>">
						<i class="fa fa-cog"></i><span>Settings</span>
					</a>
				</div>
			</div>
			<div><hr></div>
			<div class="aside-nav-item">
				<a href="" class="<?php if (in_array("farm", $middle['body_class'])) {echo "active";} ?>">
					<i class="fa fa-id-badge"></i><span>Profile</span>
				</a>
				<div class="aside-nav-child">
					<a href="">
						<i class="fa fa-sign-out"></i><span>Log out</span>
					</a>
				</div>
			</div>
		</div>
	</div>

</div>