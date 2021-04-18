<?php if ($data['product']): ?>
	<?php $product = $data['product']; ?>
	<div class="container">
		<div class="row" id="productpage_top">
			<div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
				<div class="product-imgs-container">
					<?php if ($product['photos']): ?>
					<div id="main_img_preview" style="background-image: url('<?php echo $product['photos']['main']['url_path'];?>');"></div>
					<div class="img-thumb-container">
						<ul class="inline-list" id="img_thumb_list">
							<li><div class="img-thumb-item active" style="background-image: url('<?php echo $product['photos']['main']['url_path'];?>');"></div></li>
							<?php if (isset($product['photos']['other']) AND $product['photos']['other']): ?>
								<?php foreach ($product['photos']['other'] as $key => $photo): ?>
									<li><div class="img-thumb-item" style="background-image: url('<?php echo $photo['url_path'];?>');"></div></li>
								<?php endforeach ?>
							<?php endif ?>
						</ul>
					</div>
					<?php endif ?>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-7 col-xs-12">
				<div class="productpage-basic-container">
					<div class="cat-breadcrumb">
						<p class="text-gray zero-gaps"><a href=""><?php echo $product['category'];?></a> <i class="fa fa-angle-right"></i> <a href=""><?php echo $product['subcategory'];?></a></p>
					</div>
					<h1 class="productpage-title"><?php echo $product['name'];?></h1>
					<h2 class="productpage-price">
						<span class="text-gray">&#x20b1;</span> <span class="text-contrast"><?php echo number_format($product['basket_details']['price']);?></span> 
						<!-- <span class="text-gray">-</span> <span class="text-contrast"></span> -->
					</h2>
					<hr>
					<form action="basket/add/<?php echo $product['id'];?>" method="post" class="form-validate" data-ajax="1" data-disable="enter">
					<!-- <form action="basket/add/<?php echo $product['id'];?>" method="post"> -->
						<input type="hidden" name="baskets[location_id]" value="<?php echo $product['basket_details']['farm_location_id'];?>" />
						<div class="productpage-basic-grid">
							<p class="text-gray zero-gaps">UNIT</p>
							<p><?php echo strtoupper($product['basket_details']['measurement']);?></p>
						</div>
						<?php $stocks = (int)$product['basket_details']['stocks'];?>
						<div class="productpage-basic-grid" id="quantity_container">
							<p class="text-gray zero-gaps"><span class="hidden-xs">QUANTITY</span><span class="visible-xs">QTY</span></p>
							<div class="productpage-variety" js-element="variety">
								<?php if ($stocks > 0): ?>
									<div class="variety-location">
										<p class="zero-gaps" style="margin-bottom:5px;"><i class="fa fa-cubes"></i> <span class="max-qty">Maximum of <?php echo $stocks;?></span></p>
										<p class="zero-gaps" style="margin-bottom:5px;"><i class="fa fa-map-marker"></i> <?php echo $product['farm_location']['city_prov'];?></p>
										<div class="input-group">
											<span class="input-group-addon addon-variety-input"><span class="text-gray">&#x20b1; <?php echo $product['basket_details']['price'];?></span></span>
											<input type="text" class="form-control input-number" value="1" min="1" max="<?php echo $stocks;?>" name="baskets[quantity]" required="required" />
											<span class="input-group-btn">
												<button class="btn btn-default btn-number dual-btn-left" disabled="disabled" data-type="minus" data-field="baskets[quantity]" type="button"><i class="fa fa-minus"></i></button>
											</span>
											<span class="input-group-btn">
												<button class="btn btn-default btn-number dual-btn-right" data-type="plus" data-field="baskets[quantity]" type="button"><i class="fa fa-plus"></i></button>
											</span>
										</div>
									</div>
								<?php else: ?>
									<p>NO STOCKS AVAILABLE</p>
								<?php endif ?>
							</div>
						</div>

						<?php if ($stocks > 0): ?>
							<div class="add-basket-btn" js-element="basket-btns">
								<button type="submit" class="btn btn-lg btn-default" id="add_product_btn" style="margin-right:5px;" data-keep-loading="3000"><i class="fa fa-shopping-basket icon-left text-theme"></i>Add to Basket</button>
								<a href="basket/add/<?php echo $product['id'];?>" class="btn btn-lg btn-contrast" id="buy_now_btn" style="width:125px;" data-location-id="<?php echo $product['basket_details']['farm_location_id'];?>">Buy Now</a>
							</div>
						<?php endif ?>
					</form>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="productpage-summary">
					<div class="productpage-summary-parent productpage-condition">
						<ul class="spaced-list between condition-collapser">
							<li><p style="margin-top:0;font-size:11px;" class="text-gray">PRODUCT CONDITION</p></li>
							<li class="visible-sm visible-xs"><i class="fa fa-angle-down text-gray"></i></li>
						</ul>
						<div class="productpage-summary-inner active">
							<?php foreach ($product['attribute'] as $key => $attrib): ?>
								<div class="productpage-summary-grid">
									<?php
									switch ($key) {
										case '0':?>
											<img src="assets/images/icons/planting.png" class="mini-img-icon" align="left">
										<?php break;
										case '1':?>
											<img src="assets/images/icons/ripe.png" class="mini-img-icon" align="left">
										<?php break;
										case '2':?>
											<img src="assets/images/icons/shape.png" class="mini-img-icon" align="left">
										<?php break;
										case '3':?>
											<img src="assets/images/icons/pick.png" class="mini-img-icon" align="left">
										<?php break;
										case '4':?>
											<img src="assets/images/icons/basket.png" class="mini-img-icon" align="left">
										<?php break;
									}
									?>
									<div>
										<p><?php echo $attrib['attribute'];?></p>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
					<div class="productpage-summary-parent productpage-method">
						<ul class="spaced-list between condition-collapser">
							<li><p style="margin-top:0;font-size:11px;" class="text-gray">PAYMENT</p></li>
							<li class="visible-sm visible-xs"><i class="fa fa-angle-down text-gray"></i></li>
						</ul>
						<div class="productpage-summary-inner">
							<div class="productpage-summary-grid">
								<img src="assets/images/icons/cash.png" class="mini-img-icon" align="left">
								<div>
									<p class="zero-gaps">Cash On Delivery</p>
									<small class="text-gray elem-block">Pay to upon deliver</small>
								</div>
							</div>
							<!-- <div class="productpage-summary-grid">
								<img src="assets/images/icons/transfer.png" class="mini-img-icon" align="left">
								<div>
									<p class="zero-gaps">Wire Transfer</p>
									<small class="text-gray elem-block">GCash, Paymaya</small>
								</div>
							</div> -->
						</div>
					</div>
					<div class="productpage-summary-parent productpage-delivery">
						<ul class="spaced-list between condition-collapser">
							<li><p style="margin-top:0;font-size:11px;" class="text-gray">DELIVERY</p></li>
							<li class="visible-sm visible-xs"><i class="fa fa-angle-down text-gray"></i></li>
						</ul>
						<div class="productpage-summary-inner">
							<div class="productpage-summary-grid">
								<img src="assets/images/icons/today.png" class="mini-img-icon" align="left">
								<div>
									<p class="zero-gaps">Today</p>
									<small class="text-gray elem-block">Earliest: <?php echo compute_eta($product['farm_location']['durationval'], true);?></small>
								</div>
							</div>
							<div class="productpage-summary-grid">
								<img src="assets/images/icons/calendar.png" class="mini-img-icon" align="left">
								<div>
									<p class="zero-gaps">Schedule</p>
									<small class="text-gray elem-block">For bulk orders</small>
								</div>
							</div>
						</div>
					</div>
					<?php if ($product['barns']): ?>
					<div class="productpage-summary-parent productpage-sold-by">
						<ul class="spaced-list between condition-collapser">
							<li><p style="margin-top:0;font-size:11px;" class="text-gray">FARM LOCATIONS</p></li>
							<li class="visible-sm visible-xs"><i class="fa fa-angle-down text-gray"></i></li>
						</ul>
						<div class="productpage-summary-inner" style="margin-top: 5px;">
							<?php foreach ($product['barns'] as $key => $barn): ?>
								<div class="productpage-summary-grid">
									<img src="assets/images/icons/farms.png" class="mini-img-icon" align="left">
									<div>
										<p class="zero-gaps"><?php echo $barn['city_prov'];?></p>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>