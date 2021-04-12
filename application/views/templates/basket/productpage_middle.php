
<?php if ($data['product']): ?>
	<?php 
		$product = $data['product'];
		$farm = $product['farm'];
		$farm_location = $product['farm_location'];
		$feedbacks = $product['feedbacks'];
	?>
	<div class="container">
		<div class="row" id="productpage_middle">
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
				<div class="panel productpage-desc">
					<div class="panel-heading">
						<p style="font-size:11px;" class="text-gray">DESCRIPTION</p>
					</div>
					<div class="panel-body">
						<p><?php echo $product['description'];?></p>
					</div>
				</div>

				<?php if ($feedbacks): ?>
					<div class="panel productpage-feedback">
						<div class="panel-heading">
							<ul class="spaced-list between">
								<li><p style="font-size:11px;" class="text-gray">FEEDBACK</p></li>
								<li><a href="transactions/messages/" style="font-size:11px;" class="text-link">VIEW</a></li>
							</ul>
						</div>
						
						<div class="panel-body">
							<div class="productpage-desc-inner">
								<div class="media">
									<div class="media-left">
										<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
									</div>
									<div class="media-body">
										<ul class="spaced-list between">
											<li><p class="media-heading">Ema Margaret</p></li>
											<li><small class="text-gray">March 1, 2021</small></li>
										</ul>
										<p>Media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
										<div class="media">
											<div class="media-left">
												<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
											</div>
											<div class="media-body">
												<ul class="spaced-list between">
													<li><p class="media-heading">Ava Francine</p></li>
													<li><small class="text-gray">March 1, 2021</small></li>
												</ul>
												<p>Nested media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
											</div>
										</div>
									</div>
								</div>

								<div class="media">
									<div class="media-left">
										<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
									</div>
									<div class="media-body">
										<ul class="spaced-list between">
											<li><p class="media-heading">Ema Margaret</p></li>
											<li><small class="text-gray">March 1, 2021</small></li>
										</ul>
										<p>Media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
										<div class="media">
											<div class="media-left">
												<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
											</div>
											<div class="media-body">
												<ul class="spaced-list between">
													<li><p class="media-heading">Ava Francine</p></li>
													<li><small class="text-gray">March 1, 2021</small></li>
												</ul>
												<p>Nested media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
											</div>
										</div>
									</div>
								</div>

								<div class="media">
									<div class="media-left">
										<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
									</div>
									<div class="media-body">
										<ul class="spaced-list between">
											<li><p class="media-heading">Ema Margaret</p></li>
											<li><small class="text-gray">March 1, 2021</small></li>
										</ul>
										<p>Media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
										<div class="media">
											<div class="media-left">
												<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
											</div>
											<div class="media-body">
												<ul class="spaced-list between">
													<li><p class="media-heading">Ava Francine</p></li>
													<li><small class="text-gray">March 1, 2021</small></li>
												</ul>
												<p>Nested media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
											</div>
										</div>
									</div>
								</div>

								<div class="media">
									<div class="media-left">
										<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
									</div>
									<div class="media-body">
										<ul class="spaced-list between">
											<li><p class="media-heading">Ema Margaret</p></li>
											<li><small class="text-gray">March 1, 2021</small></li>
										</ul>
										<p>Media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
										<div class="media">
											<div class="media-left">
												<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
											</div>
											<div class="media-body">
												<ul class="spaced-list between">
													<li><p class="media-heading">Ava Francine</p></li>
													<li><small class="text-gray">March 1, 2021</small></li>
												</ul>
												<p>Nested media heading Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel-footer text-right">
							<nav aria-label="Page navigation example">
								<ul class="pagination">
									<li class="page-item"><a class="page-link" href="#"><i class="fa fa-chevron-left"></i></a></li>
									<li class="page-item"><a class="page-link" href="#">1</a></li>
									<li class="page-item"><a class="page-link" href="#">2</a></li>
									<li class="page-item"><a class="page-link" href="#">3</a></li>
									<li class="page-item"><a class="page-link" href="#"><i class="fa fa-chevron-right"></i></a></li>
								</ul>
							</nav>
						</div>
					</div>
				<?php endif ?>

				<?php if ($farm): ?>
					<div class="panel productpage-farm-info">
						<div class="panel-heading bg-white">
							<p style="font-size:11px;" class="text-gray">SOLD BY</p>
						</div>
						<div class="storefront-top">
							<div class="storefront-img-bg" style="background-image: url(<?php echo $farm['cover_pic'];?>);">
								<div id="farm_identity">
									<ul class="grid-list half">
										<li class="text-left">
											<h1 class="farm_name"><?php echo $farm['name'];?></h1>
											<h4 class="tagline"><?php echo $farm['tagline'];?></h4>
										</li>
										<li class="text-right">
											<div class="profile_photo" style="background-image: url(<?php echo $farm['profile_pic'];?>);"></div>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="panel-footer bg-white">
							<div class="productpage-farm-middle">
								<div class="row">
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
										<div class="productpage-summary-grid">
											<img src="assets/images/icons/farms.png" class="mini-img-icon" align="left">
											<div>
												<p class="zero-gaps"><?php echo $farm_location['city_prov'];?></p>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<p class="zero-gaps"><a href="<?php echo $farm['storefront'];?>" class="text-link visit-farm-link" target="storefrontTab"><i class="fa fa-external-link-square icon-left"></i>Visit farm</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			</div>
		</div>
	</div>
<?php endif ?>