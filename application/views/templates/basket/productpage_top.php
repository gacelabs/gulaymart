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
							<?php foreach ($product['photos']['other'] as $key => $photo): ?>
								<li><div class="img-thumb-item" style="background-image: url('<?php echo $photo['url_path'];?>');"></div></li>
							<?php endforeach ?>
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
					<?php
						$prices = $measures = $city = $stocks = [];
						foreach ($product['latlng'] as $location_id => $location) {
							$prices[] = $location['price'];
							$measures[] = $location['measurement'];
							$city[] = $location['city'];
							$stocks[] = $location['stocks'];
						}

						$units = array_unique($measures);

						$to_price = end($prices);
						$from_price = reset($prices);
						if ($to_price == $from_price) $to_price = '';
					?>
					<h2 class="productpage-price">
						<span class="text-gray">&#x20b1;</span> <span class="text-contrast"><?php echo $from_price;?></span> 
						<?php if ($to_price != ''): ?>
							<span class="text-gray">-</span> <span class="text-contrast"><?php echo $to_price;?></span>
						<?php endif ?>
					</h2>
					<hr>
					<form action="basket/add/<?php echo $product['id'];?>" method="post" class="form-validate" data-ajax="1" data-disable="enter">
					<!-- <form action="basket/add/<?php echo $product['id'];?>" method="post"> -->
						<div class="productpage-basic-grid">
							<p class="text-gray zero-gaps">UNIT</p>
							<p><?php echo strtoupper(implode(', ', $units));?></p>
						</div>
						<div class="productpage-basic-grid" id="quantity_container">
							<p class="text-gray zero-gaps"><span class="hidden-xs">QUANTITY</span><span class="visible-xs">QTY</span></p>
							<div class="productpage-variety">
								<?php foreach ($product['latlng'] as $location_id => $location): ?>
								<div class="variety-location">
									<p class="zero-gaps" style="margin-bottom:5px;"><i class="fa fa-map-marker"></i> <?php echo $location['city'];?> - <span class="max-qty">Max quantity <?php echo $location['stocks'];?></span></p>
									<div class="input-group">
										<span class="input-group-addon addon-variety-input"><span class="text-gray">&#x20b1; <?php echo $location['price'];?></span></span>
										<input type="text" class="form-control input-number" value="1" min="1" max="<?php echo $location['stocks'];?>" name="baskets[<?php echo $location_id;?>][quantity]" required="required" />
										<span class="input-group-btn">
											<button class="btn btn-default btn-number dual-btn-left" disabled="disabled" data-type="minus" data-field="baskets[<?php echo $location_id;?>][quantity]" type="button"><i class="fa fa-minus"></i></button>
										</span>
										<span class="input-group-btn">
											<button class="btn btn-default btn-number dual-btn-right" data-type="plus" data-field="baskets[<?php echo $location_id;?>][quantity]" type="button"><i class="fa fa-plus"></i></button>
										</span>
									</div>
								</div>
								<?php endforeach ?>
							</div>
						</div>

						<div class="add-basket-btn">
							<button type="submit" class="btn btn-lg btn-default" id="add_product_btn" style="margin-right:5px;"><i class="fa fa-shopping-basket icon-left"></i>Add to Basket</button>
							<a href="basket" class="btn btn-lg btn-contrast" style="width:125px;">Buy Now</a>
						</div>
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
							<div class="productpage-summary-grid">
								<img src="assets/images/icons/transfer.png" class="mini-img-icon" align="left">
								<div>
									<p class="zero-gaps">Wire Transfer</p>
									<small class="text-gray elem-block">GCash, Paymaya</small>
								</div>
							</div>
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
									<small class="text-gray elem-block">Earliest: 30 minutes</small>
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
					<div class="productpage-summary-parent productpage-sold-by">
						<ul class="spaced-list between condition-collapser">
							<li><p style="margin-top:0;font-size:11px;" class="text-gray">FARM LOCATIONS</p></li>
							<li class="visible-sm visible-xs"><i class="fa fa-angle-down text-gray"></i></li>
						</ul>
						<div class="productpage-summary-inner" style="margin-top: 5px;">
							<?php foreach ($product['latlng'] as $location_id => $location): ?>
								<div class="productpage-summary-grid">
									<img src="assets/images/icons/farms.png" class="mini-img-icon" align="left">
									<div>
										<p class="zero-gaps"><?php echo $location['city_prov'];?></p>
									</div>
								</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>