<div id="dashboard_navbar">
	<div class="container-fluid">
		<div id="dashboard_navbar_grid">
			<a href="" class="hidden-xs"><i class="fa fa-leaf" id="global_navbar_logo"></i></a>
			<h3 class="text-great-user"><b class="visible-xs"><?php echo_message('Hello ', 'firstname');?></b></h3>
			<div id="dashboard_navbar_btn_list" class="zero-gaps">
				<a href="marketplace/" class="text-link hidden-xs" style="display: inline-block; margin: 0px 10px; font-size: 25px;">
					<i class="fa fa-home"></i>
				</a>
				<?php if ($current_profile['is_admin']): ?>
					<a href="admin/" class="text-link" style="display: inline-block; margin: 0px 10px; font-size: 25px;">
						<i class="fa fa-cogs"></i>
					</a>
				<?php endif ?>
				<a href="profile/" class="v-top" style="display: inline-block; margin: 0px; font-size: 25px;">
					<?php if ($current_profile['farms']): ?>
						<div id="global_navbar_avatar" style="background-image: url('<?php echo $current_profile['farms']['profile_pic'];?>');"></div>
					<?php else: ?>
						<div id="global_navbar_avatar" style="background-image: url('assets/images/avatar.jpg');"></div>
					<?php endif ?>
				</a>
			</div>
		</div>
	</div>
</div>