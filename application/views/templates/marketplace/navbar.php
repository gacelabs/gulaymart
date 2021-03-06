<div id="global_navbar">
	<div class="container">
		<div id="global_navbar_grid">
			<a href=""><i class="fa fa-leaf" id="global_navbar_logo"></i></a>

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
				<li>
					<a href="basket/"><i class="fa fa-shopping-basket"></i></a>
				</li>
				<?php endif ; ?>

				<li <?php echo(empty($current_profile) ? 'data-toggle="modal" data-target="#login_modal"' : ''); ?>>
					<?php if ($current_profile) : ?>
						<a href="profile/">
							<?php if ($current_profile['farms']): ?>
								<div id="global_navbar_avatar" style="background-image: url('<?php echo $current_profile['farms']['profile_pic'];?>');"></div>
							<?php else: ?>
								<div id="global_navbar_avatar" style="background-image: url('assets/images/avatar.jpg');"></div>
							<?php endif ?>
							<i class="fa fa-gear global-navbar-badge"></i>
						</a>
					<?php else : ?>
						<span class="hidden-xs">Log In <span style="color:#c1c1c1;">|</span> Register</span>
						<span class="visible-xs"><i class="fa fa-sign-in"></i></span>
					<?php endif ; ?>
				</li>
			</ul>
		</div>
	</div>
</div>