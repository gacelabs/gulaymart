<?php if ($data AND isset($data['farm']) AND $data['farm']): ?>
	<div class="cover_image" id="storefront_page_container">
		<div class="storefront-top">
			<div class="storefront-img-bg" style="background-image: url(<?php echo $data['farm']['cover_pic'];?>);">
				<div id="farm_identity">
					<ul class="grid-list half">
						<li class="text-left">
							<h1 class="farm_name"><a href="store/<?php echo $data['farm']['id'];?>/<?php nice_url($data['farm']['name']);?>"><?php echo $data['farm']['name'];?></a></h1>
							<h4 class="tagline"><?php echo $data['farm']['tagline'];?></h4>
						</li>
						<li class="text-right"><div class="profile_photo" style="background-image: url(<?php echo $data['farm']['profile_pic'];?>);"></div></li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="storefront-middle">
			<ul class="spaced-list around" id="storefront_navbar">
				<li class="sf-navbar-btn active" container-name="#product_item_container"><a>PRODUCTS</a></li>
				<li class="sf-navbar-btn" container-name="#stories_item_container"><a>STORIES</a></li>
				<li class="sf-navbar-btn" container-name="#galleries_item_container"><a>GALLERY</a></li>
				<li class="sf-navbar-btn" container-name="#about_item_container"><a>ABOUT</a></li>
			</ul>

			<div id="storefront_product_container">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 toggle-container" id="product_item_container">
					<div class="product-item-body zero-gaps">
						<?php if (isset($data['products']) AND !empty($data['products'])) {
							foreach ($data['products'] as $key => $product) {
								$this->view('looping/product_card', ['data'=>$product, 'id'=>$product['category_id']]);
							}
						} else {?>
							<?php if ($current_profile) : ?>
								<h4 class="zero-gaps">Add a new product <a href="farm/my-veggies" class="btn btn-contrast">New Veggy</a></h4>
								<hr class="carved">
								<p class="color-grey text-center" style="margin: 30px 0;">&#8212; NO PRODUCTS TO DISPLAY &#8212;</p>
							<?php endif; ?>
						<?php } ?>
						<div class="banner-section">
							<img src="assets/images/banner/<?php echo $data['farm']['banner'];?>" class="img-responsive" style="width: 100%;">
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 toggle-container hide" id="stories_item_container">
					<!-- GINAWA KO LANG TO PARA MAG SIMULATE KUNG MAY STORIES NA OR WALA PA -->
					<?php if (isset($data['kunyare'])) : ?>
						<div class="store-item-body">
							<div id="composer_post_container">
								<form action="" method="post">
									<div class="composer-grid">
										<div class="profile_photo hidden-xs" style="background-image: url(<?php echo $data['farm']['profile_pic'];?>);"></div>
										<div class="composer-form-body">
											<div class="form-group" style="margin-bottom:5px;">
												<textarea class="form-control" placeholder="Share your farm stories..." rows="2"></textarea>
											</div>
											<div class="composer-footer">
												<ul class="inline-list">
													<li class="text-link" data-toggle="modal" data-target="#media_modal"><i class="fa fa-picture-o"></i> Media</li>
												</ul>
											</div>
										</div>
										<div>
											<button class="btn btn-contrast"><i class="fa fa-send"></i> Post</button>
										</div>
									</div>
								</form>
							</div>
							<div class="store-item-body-grid">
								<div class="store-item-body-left">
									left
								</div>
								<div class="store-item-body-right">
									<div class="store-content-list">
										<div class="store-content-item">
											<div class="store-content-top">
												store-content-top
											</div>
											<div class="store-content-middle">
												store-content-middle
											</div>
											<div class="store-content-footer">
												store-content-footer
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php else: ?>
						<p class="color-grey text-center" style="margin: 30px 0;">&#8212; NO STORIES &#8212;</p>
					<?php endif ?>
				</div>
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 toggle-container hide" id="galleries_item_container">
					<!-- GINAWA KO LANG TO PARA MAG SIMULATE KUNG MAY GALLERIES NA OR WALA PA -->
					<?php if (isset($data['kunyare'])) : ?>
						<!-- ETO YUNG GALLERIES CARD -->
						<?php $this->view('looping/product_card'); ?>
					<?php else: ?>
						<p class="color-grey text-center" style="margin: 30px 0;">&#8212; NO GALLERIES &#8212;</p>
					<?php endif ?>
				</div>
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 toggle-container hide" id="about_item_container">
					<!-- GINAWA KO LANG TO PARA MAG SIMULATE KUNG MAY ABOUT NA OR WALA PA -->
					<?php if (isset($data['kunyare'])) : ?>
						<!-- ETO YUNG ABOUT CARD -->
						<?php $this->view('looping/product_card'); ?>
					<?php else: ?>
						<p class="color-grey text-center" style="margin: 30px 0;">&#8212; NO ABOUT &#8212;</p>
					<?php endif ?>
				</div>
			</div>
		</div>

		<div class="storefront-footer">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<h4 class="farm_name" style="margin-top: 0;"><?php echo $data['farm']['name'];?></h4>
					<p class="zero-gaps" style="color: #a7a7a7;">By <a href="">Gulaymart</a></p>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<?php if (!empty($data['farm']['facebook']) OR !empty($data['farm']['instagram']) OR !empty($data['farm']['youtube']) OR !empty($data['farm']['messenger'])) : ?>
					<h4 style="color: #fff;margin: 0 0 5px 0;">Connect with us!</h4>
					<ul class="inline-list social_acct">
						<?php if (strlen($data['farm']['facebook'])): ?><li><h4 class="zero-gaps"><a id="storefront-facebook" href="<?php echo $data['farm']['facebook'];?>"><i class="fa fa-facebook-square"></i></a></h4></li><?php endif ?>
						<?php if (strlen($data['farm']['instagram'])): ?><li><h4 class="zero-gaps"><a id="storefront-instagram" href="<?php echo $data['farm']['instagram'];?>"><i class="fa fa-instagram"></i></a></h4></li><?php endif ?>
						<?php if (strlen($data['farm']['youtube'])): ?><li><h4 class="zero-gaps"><a id="storefront-youtube" href="<?php echo $data['farm']['youtube'];?>"><i class="fa fa-youtube-play"></i></a></h4></li><?php endif ?>
						<?php if (strlen($data['farm']['messenger'])): ?><li><h4 class="zero-gaps"><a id="storefront-messenger" href="<?php echo $data['farm']['messenger'];?>"><i class="fa fa-comment-o"></i></a></h4></li><?php endif ?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
	<p class="color-grey text-center" style="margin: 30px 0;">&#8212; STORE NOT FOUND &#8212;</p>
<?php endif ?>