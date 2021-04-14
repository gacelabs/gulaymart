<div id="dashboard_navbar_aside">
	<h3 class="text-center hidden-xs"><b><?php echo_message('Hello,', 'firstname');?>!</b></h3>
	<div id="dashboard_nav_container">
		<a href="profile/" class="hidden-xs aside-nav-item <?php in_array_echo("profile", $middle['body_class'], "active");?>">
			<i class="fa fa-id-badge"></i><span class="hidden-xs">Profile</span>
		</a>
		<a href="" class="hidden-lg hidden-md hidden-sm aside-nav-item">
			<i class="fa fa-leaf"></i>
		</a>
		<div class="navbar-aside-divider"><hr></div>
		<div class="aside-nav-child">
			<a href="basket/" class="aside-nav-item <?php in_array_echo("basket", $middle['body_class'], "active");?>">
				<i class="fa fa-shopping-basket"></i><span class="hidden-xs">Basket <?php if($this->basket_empty) {echo "<kbd>New</kbd>";} ?></span>
			</a>
		</div>
		<div class="aside-nav-child">
			<a href="transactions/orders/" class="aside-nav-item <?php in_array_echo("orders", $middle['body_class'], "active");?>">
				<i class="fa fa-cart-arrow-down"></i><span class="hidden-xs">Orders</span>
			</a>
		</div>
		<div class="aside-nav-child">
			<a href="transactions/messages/" class="aside-nav-item <?php in_array_echo("messages", $middle['body_class'], "active");?>">
				<i class="fa fa-comment-o"></i><span class="hidden-xs">Messages</span>
			</a>
		</div>
		<div class="navbar-aside-divider"><hr></div>
		<div class="aside-nav-child hidden-lg hidden-md hidden-sm">
			<a href="javascript:;" class="aside-nav-item" id="farm_menu_trigger" js-event="farmMenuTrigger">
				<i class="fa fa-angle-up bg-contrast"></i>
			</a>
		</div>
		<div id="navbar_farm_menu_container" js-event="navbarFarmMenuContainer">
			<?php if (isset($current_profile['is_agreed_terms']) AND $current_profile['is_agreed_terms']): ?>
				<?php if (isset($current_profile['is_agreed_terms']) AND $current_profile['is_agreed_terms']): ?>
					<?php if (!empty($this->farms)): ?>
						<?php if ($this->products->count()): ?>
							<div class="aside-nav-child">
								<a href="farm/sales/" class="aside-nav-item <?php in_array_echo("sales", $middle['body_class'], "active");?>">
									<i class="fa fa-tachometer"></i>Sales
								</a>
							</div>
						<?php endif ?>
						<div class="aside-nav-child">
							<a href="farm/my-veggies/" class="aside-nav-item hidden-xs <?php in_array_echo("new-veggy", $middle['body_class'], "active");?>">
								<i class="fa fa-pencil"></i>My Veggies <?php if(empty($this->products->count())) {echo "<kbd>New</kbd>";} ?>
							</a>
						</div>
					<?php endif ?>
				<?php endif ?>
			<?php endif ?>
			<div class="aside-nav-child">
				<a href="farm/storefront" class="aside-nav-item hidden-xs <?php in_array_echo("storefront", $middle['body_class'], "active");?>">
					<i class="fa fa-flag-o"></i>Storefront <?php if(empty($this->farms)) {echo "<kbd>New</kbd>";} ?>
				</a>
			</div>
			<?php if (isset($current_profile['is_agreed_terms']) AND $current_profile['is_agreed_terms']): ?>
				<?php if (!empty($this->farms) AND $this->products->count()): ?>
					<div class="aside-nav-child">
						<a href="farm/inventory/" class="aside-nav-item <?php in_array_echo("inventory", $middle['body_class'], "active");?>">
							<i class="fa fa-cubes"></i>Inventory
						</a>
					</div>
				<?php endif ?>
				<?php if (!empty($this->farms)): ?>
					<div class="aside-nav-child">
						<a href="farm/settings/" class="aside-nav-item <?php in_array_echo("settings", $middle['body_class'], "active");?>">
							<i class="fa fa-cog"></i>Settings
						</a>
					</div>
				<?php endif ?>
			<?php endif ?>
			<a href="sign-out" class="aside-nav-item hidden-lg hidden-md hidden-sm">
				<i class="fa fa-sign-out" style="background-color:#cacaca;"></i>Sign out
			</a>
		</div>
		<div class="navbar-aside-divider"><hr></div>
		<a href="support/help-center/" class="aside-nav-item  hidden-xs">
			<i class="fa fa-question-circle"></i><span class="hidden-xs">Help Center</span>
		</a>
		<a href="sign-out" class="aside-nav-item hidden-xs">
			<i class="fa fa-sign-out" style="background-color:#cacaca;"></i><span class="hidden-xs">Sign out</span>
		</a>
	</div>
</div>