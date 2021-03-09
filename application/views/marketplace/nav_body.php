<div class="panel" id="marketplace_nav_body">
	<div class="" id="search_with_nav_btns">
		<div class="my-logo">
			<a href=""><i class="fa fa-leaf"></i></a>
		</div>
		<?php $this->view('forms/search_form'); ?>
		<ul id="search_nav_btns">
			<li class="login-button onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>" data-toggle="modal" data-target="#login_modal"><a href="javascript:;"><span class="hidden-xs">Log In</span><i class="fa fa-sign-in visible-xs"></i></a></li>
			<li class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><a href="basket"><i class="fa fa-shopping-basket"></i></a></li>
			<li class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><a href="notifications"><i class="fa fa-bell-o"></i></a></li>
			<li class="nav-avatar-li onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><a href="account"><div class="nav-avatar-body" style="background-image: url('assets/images/avatar.jpg');"></div></a></li>
		</ul>

	</div>
	
	<div id="veggy_categories">
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner active">
				<img src="assets/images/all.png" />
				<p>All</p>
			</a>
		</div>
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner">
				<img src="assets/images/leafy.png" />
				<p>Leafy</p>
			</a>
		</div>
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner">
				<img src="assets/images/root.png" />
				<p>Root</p>
			</a>
		</div>
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner">
				<img src="assets/images/cruciferous.png" />
				<p>Cruciferous</p>
			</a>
		</div>
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner">
				<img src="assets/images/marrow.png" />
				<p>Marrow</p>
			</a>
		</div>
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner">
				<img src="assets/images/plant-stem.png" />
				<p>Stem Plant</p>
			</a>
		</div>
		<div class="veggy-category-item">
			<a href="" class="veggy-category-item-inner">
				<img src="assets/images/allium.png" />
				<p>Allium</p>
			</a>
		</div>
	</div>
</div>