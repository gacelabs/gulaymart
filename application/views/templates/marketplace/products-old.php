<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="product_item_container">
	<div class="product-item-body">
		<div class="product-item-inner">
			<?php
				// debug($data['products'], 'stop');
				if ($data['products']) {
					$all = $data['products']['all'];
					foreach ($all['data_page'] as $key => $product) {
						$this->view('looping/product_item', ['data'=>$product, 'id'=>'all']);
					}
					$categories = $data['products']['categories'];
					foreach ($categories as $category_id => $row) {
						$category = $row['data_page'];
						foreach ($category as $key => $product) {
							$this->view('looping/product_item', ['data'=>$product, 'id'=>$category_id]);
						}
					}
				}
				// debug($current_profile);
			?>
		</div>
		<?php
		if ($data['products']) {
			$all = $data['products']['all'];
			if (isset($all['next_page']) AND $all['next_page'] != 0) {
				$next_page = json_encode($all['next_page']);?>
				<div class="btn-generic-container">
					<button data-json='<?php echo $next_page;?>' value="0" data-selector='data-category="all"' data-category="loadmore-all">More Veggies</button>
				</div><?php
			}
			$categories = $data['products']['categories'];
			foreach ($categories as $category_id => $row) {
				if (isset($row['next_page']) AND $row['next_page'] != 0) {
					$next_page = json_encode($row['next_page']);?>
					<div class="btn-generic-container" style="display: none;">
						<button data-json='<?php echo $next_page;?>' value="0" data-selector='data-category="<?php echo $category_id;?>"' data-category="loadmore-<?php echo $category_id;?>">More Veggies</button>
					</div><?php
				}
			}
		}
		?>

		<div class="hide <?php echo $current_profile ? "in" : ""; ?>" id="bottom_nav_sm">
			<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>">
				<span data-toggle="tooltip" data-placement="top" title="Sign Out"><a href="sign-out"><i class="fa fa-sign-out"></i></a></span>
			</div>
			
			<div class="bottom-nav-item onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>" data-toggle="tooltip" data-placement="top" title="Gulaymart">
				<a href=""><i class="fa fa-leaf"></i></a>
			</div>

			<div class="bottom-nav-item" data-toggle="modal" data-target="#search_popup">
				<i class="fa fa-search" data-toggle="tooltip" data-placement="top" title="Search"></i>
			</div>
			
			<div data-toggle="tooltip" data-placement="top" title="Sign Up" class="bottom-nav-item onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>">
				<a href="register"><i class="fa fa-user-plus"></i></a>
			</div>
			
			<div class="bottom-nav-item onlogged-out-btn<?php echo $current_profile ? ' hide' : '';?>" data-toggle="modal" data-target="#login_modal">
				<span data-toggle="tooltip" data-placement="top" title="Log In"><i class="fa fa-sign-in"></i></span>
			</div>
			
			
			<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>" data-toggle="tooltip" data-placement="top" title="Notifications">
				<a href="notifications"><i class="fa fa-bell-o"></i></a>
			</div>
			
			<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>" data-toggle="tooltip" data-placement="top" title="Basket">
				<a href="basket"><i class="fa fa-shopping-basket"></i></a>
			</div>
			
			<div class="bottom-nav-item onlogged-in-btns<?php echo $current_profile ? '' : ' hide';?>" data-toggle="tooltip" data-placement="top" title="<?php echo $current_profile ? $current_profile['fullname'] : '';?>">
				<a href="farm/"><div class="nav-avatar-body" style="background-image: url('assets/images/avatar.jpg');"></div></a>
			</div>
		</div>
	</div>
</div>