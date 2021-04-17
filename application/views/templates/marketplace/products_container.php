<div id="products_container">
	<div id="product_list_container">
		<?php
			if ($data['nearby_products']) {
				foreach ($data['nearby_products'] as $key => $product) {
					$this->view('looping/product_card', ['data'=>$product, 'id'=>$product['category_id']]);
				}
			}
		?>
	</div>
	<div class="clearfix"></div>
	<div id="load_more_container">
		<button class="btn btn-lg" id="load_more_btn">LOAD MORE VEGGIES</button>
	</div>
</div>
<hr class="carved clearfix">