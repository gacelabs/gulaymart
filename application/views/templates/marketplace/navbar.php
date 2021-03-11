<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="marketplace_nav_body">
	<div class="panel">
		<div id="search_with_nav_btns">
			<div class="my-logo">
				<a href=""><i class="fa fa-leaf"></i></a>
			</div>
			<?php $this->load->view('forms/search_form'); ?>
			<ul id="search_nav_btns">
				<li class="login-button onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>" data-toggle="modal" data-target="#login_modal"><a href="javascript:;"><span class="hidden-xs">Log In</span><i class="fa fa-sign-in visible-xs"></i></a></li>
				<li class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><a href="notifications"><i class="fa fa-bell-o"></i></a></li>
				<li class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><a href="basket"><i class="fa fa-shopping-basket"></i></a></li>
				<li class="nav-avatar-li onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><a href="dashboard"><div class="nav-avatar-body" style="background-image: url('assets/images/avatar.jpg');"></div></a></li>
			</ul>
		</div>
	</div>
</div>