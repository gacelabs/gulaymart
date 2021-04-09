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
						<!-- GINAWA KO LANG TO PARA MAG SIMULATE KUNG MAY PORDUCT NA OR WALA PA -->
						<?php if (isset($data['kunyare'])) : ?>
							<!-- ETO YUNG PRODUCT CARD -->
							<?php $this->view('looping/product_card'); ?>
						<?php else: ?>
							<p class="color-grey text-center" style="margin: 30px 0;">&#8212; NO PRODUCTS TO DISPLAY &#8212;</p>
						<?php endif ?>
						<?php if (isset($data['products'])) {
							/*foreach ($data['products'] as $key => $product) {
								$this->view('looping/product_card', ['data'=>$product, 'id'=>$product['category_id']]);
							}*/
						} else {?>
							<!-- <p class="color-grey text-center" style="margin: 30px 0;">&#8212; NO PRODUCTS TO DISPLAY &#8212;</p> -->
						<?php } ?>
						<div class="banner-section">
							<img src="assets/images/banner/<?php echo $data['farm']['banner'];?>" class="img-responsive" style="width: 100%;">
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 toggle-container hide" id="stories_item_container">
					<!-- GINAWA KO LANG TO PARA MAG SIMULATE KUNG MAY STORIES NA OR WALA PA -->
					<?php if (isset($data['kunyare'])) : ?>
						<!-- ETO YUNG STORIES CARD -->
						<?php $this->view('looping/product_card'); ?>
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