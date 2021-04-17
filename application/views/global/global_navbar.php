<div id="global_navbar">
	<?php if (empty($current_profile) AND empty($this->session->userdata('prev_latlng'))) : ?>
	<div id="check_loc">
		<div class="container">
			<p class="zero-gaps">GULAYMART HELPS YOU FIND THE FRESHEST VEGGIES IN YOUR COMMUNITY. CHECK YOUR <span data-toggle="modal" data-target="#check_loc_modal">LOCATION HERE</span> TO SHOW SUPPORT FOR OUR LOCAL FARMERS.</p>
		</div>
	</div>
	<?php endif; ?>

	<div class="container">
		<div id="global_navbar_grid">
			<a href=""><i class="fa fa-leaf" id="global_navbar_logo"></i></a>

			<?php if (!in_array("checkout", $middle['body_class'])) : ?>
			<form action="" method="post" name="search" class="form-validate" id="global_search_form">
				<div class="input-group">
					<input type="text" class="form-control" name="search" placeholder="Search veggies...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" style="background-color:#fff;"><i class="fa fa-search" style="color:#a8d253;"></i></button>
					</span>
				</div>
			</form>
			
			<ul id="global_navbar_btn_list">
				<?php if ($current_profile) : ?>
				<li id="nav_basket">
					<a href="basket/"><i class="fa fa-shopping-basket"></i></a>
				</li>
				<?php endif ; ?>

				<li <?php echo(empty($current_profile) ? 'data-toggle="modal" data-target="#login_modal"' : ''); ?>>
					<?php if ($current_profile) : ?>
						<a href="profile/">
							<div id="global_navbar_avatar" style="background-image: url('assets/images/avatar.jpg');"></div>
							<i class="fa fa-gear global-navbar-badge"></i>
						</a>
					<?php else : ?>
						<span class="hidden-xs">Log In <span style="color:#c1c1c1;">|</span> Register</span>
						<span class="visible-xs"><i class="fa fa-sign-in"></i></span>
					<?php endif ; ?>
				</li>
			</ul>
			<?php endif ; ?>
		</div>
	</div>
</div>