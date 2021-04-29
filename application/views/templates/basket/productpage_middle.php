
<?php if ($data['product']): ?>
	<?php 
		$product = $data['product'];
		$farm = $product['farm'];
		$farm_location = $product['farm_location'];
		$feedbacks = $product['feedbacks'];
		$product['entity_id'] = $farm_location['id'];
		$can_comment = $product['can_comment'];
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

					<div class="panel productpage-feedback">
						<div class="panel-heading">
							<ul class="spaced-list between">
								<li><p style="font-size:11px;" class="text-gray">FEEDBACK</p></li>
							</ul>
						</div>
						
						<div class="panel-body">
							<div class="productpage-desc-inner" js-element="parent-comments-container">
								<?php if ($feedbacks): ?>
									<?php foreach ($feedbacks as $id => $feedback): ?>
										<div class="media">
											<div class="media-left">
												<img class="media-object" src="assets/images/noavatar.png" data-holder-rendered="true">
											</div>
											<div class="media-body">
												<ul class="spaced-list between">
													<li><p class="media-heading"><?php get_fullname($feedback['first']['profile']);?></p></li>
													<li><small class="text-gray"><?php echo date('F j, Y | g:ia', strtotime($feedback['first']['added']));?></small></li>
												</ul>
												<p><?php echo $feedback['first']['content'];?></p>
												<?php if (isset($feedback['replies'])): ?>
													<?php
													foreach ($feedback['replies'] as $key => $reply) {
														$reply['totalcnt'] = count($feedback['replies']) - 1;
														$reply['key'] = $key;
														$reply['id'] = $id;
														$reply['product'] = $product;
														$this->view('looping/reply_item', $reply);
													}?>
												<?php else: ?>
													<?php $this->view('looping/comment_item', ['placeholder'=>'Reply to this comment ...', 'under'=>$id, 'page'=>$product]); ?>
												<?php endif ?>
											</div>
										</div>
									<?php endforeach ?>
								<?php else: ?>
									Be the first to make a feedback. Buy Now!
								<?php endif ?>

								<?php if ($can_comment): ?>
								<div style="margin-left:-15px;margin-right:-15px;">
									<hr style="margin-bottom:10px;border-color:#d0d0d0;">
									<?php $this->view('looping/comment_item', ['placeholder'=>'Write a feedback ...', 'under'=>0, 'page'=>$product]); ?>
								</div>
								<?php endif ?>
							</div>
						</div>

						<?php if ($feedbacks): ?>
							<!-- <div class="panel-footer text-right">
								<nav aria-label="Page navigation example">
									<ul class="pagination">
										<li class="page-item"><a class="page-link" href="#"><i class="fa fa-chevron-left"></i></a></li>
										<li class="page-item"><a class="page-link" href="#">1</a></li>
										<li class="page-item"><a class="page-link" href="#">2</a></li>
										<li class="page-item"><a class="page-link" href="#">3</a></li>
										<li class="page-item"><a class="page-link" href="#"><i class="fa fa-chevron-right"></i></a></li>
									</ul>
								</nav>
							</div> -->
						<?php endif ?>
					</div>

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
											<div class="ellipsis-container">
												<h1 class="farm_name"><?php echo $farm['name'];?></h1>
											</div>
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
										<div class="productpage-summary-grid" style="margin-top:0;">
											<img src="assets/images/icons/farms.png" class="mini-img-icon" align="left">
											<div>
												<p class="zero-gaps"><?php echo $farm_location['city_prov'];?></p>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<p class="zero-gaps"><a href="<?php echo $farm['storefront'];?>" class="text-link visit-farm-link" target="storefrontTab<?php echo $farm['id'];?>"><i class="fa fa-external-link-square icon-left"></i>Visit farm</a></p>
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