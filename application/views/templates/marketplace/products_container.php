<div id="products_container">
	<div id="product_list_container">
		<?php
			if ($data['nearby_products']) {
				foreach ($data['nearby_products'] as $key => $product) {
					$this->view('looping/product_card', ['data'=>$product, 'id'=>$product['category_id']]);
				}
			} else {
				?><h3 class="text-center">No Product(s) Found</h3><?php
			}
		?>
	</div>
	<div class="clearfix"></div>
	<?php if ($data['nearby_products_count'] AND count($data['nearby_products_count']) > MARKETPLACE_MAX_ITEMS): ?>
		<div id="load_more_container">
			<button class="btn btn-lg" id="load_more_btn" data-url="<?php echo current_url();?>" data-items="#product_list_container .product-list-card">LOAD MORE VEGGIES</button>
		</div>
	<?php endif ?>
</div>
<hr class="carved clearfix">