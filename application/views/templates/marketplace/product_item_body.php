<div class="product-item-body" id="product_item_body">
	<div class="product-item-inner">
		<?php
			$n = 20;
			for ($i=0; $i < $n; $i++) { 
				$this->view('looping/product_item');
			}
		?>
	</div>
	<nav class="pagination-container" aria-label="Page navigation">
		<ul class="pagination">
			<li>
				<a href="#" aria-label="Previous">
					<span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
				</a>
			</li>
			<li><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li><a href="#">5</a></li>
			<li>
				<a href="#" aria-label="Next">
					<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
				</a>
			</li>
		</ul>
	</nav>

	<div class="hide <?php echo $current_profile ? "" : "in"; ?>" id="bottom_nav_sm">
		<div class="bottom-nav-item onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>" data-toggle="tooltip" data-placement="top" title="Gulaymart"><a href=""><i class="fa fa-leaf"></i></a></div>
		<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>" data-toggle="tooltip" data-placement="top" title="Notifications"><a href="notifications"><i class="fa fa-bell-o"></i></a></div>
		<div class="bottom-nav-item" data-toggle="modal" data-target="#search_popup"><i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="Search"></i></div>
		<div data-toggle="tooltip" data-placement="top" title="Sign Up" class="bottom-nav-item onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>"><a href="register"><i class="fa fa-user-plus"></i></a></div>
		<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>" data-toggle="tooltip" data-placement="top" title="Basket"><a href="basket"><i class="fa fa-shopping-basket"></i></a></div>
		<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>" data-toggle="tooltip" data-placement="top" title="<?php echo $current_profile ? $current_profile['info']['firstname'] : '';?>"><a href="account"><div class="nav-avatar-body" style="background-image: url('assets/images/avatar.jpg');"></div></a></div>
		<div class="bottom-nav-item onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>" data-toggle="modal" data-target="#login_modal"><span data-toggle="tooltip" data-placement="top" title="Log In"><i class="fa fa-sign-in"></i></span></div>
		<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>"><span data-toggle="tooltip" data-placement="top" title="" data-original-title="Log Out"><a href="sign-out"><i class="fa fa-sign-out"></i></a></span></div>
	</div>
</div>