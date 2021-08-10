<div class="row hidden-xs ff-product-container">
	<div class="col-lg-12 col-md-12 hidden-sm hidden-xs" js-element="fulfill-panel">
		<?php if ($data['orders']): ?>
			<!-- per farm location -->
			<?php foreach ($data['orders'] as $key => $orders): ?>
				<?php
					$this->view('templates/fulfillment/ff_fulfill_item', [
						'farm' => $data['farm'],
						'orders' => $orders,
						'status_text' => $data['status'],
						'status_id' => $data['status_id'],
					]);
				?>
			<?php endforeach ?>
		<?php endif ?>
		<div class="no-records-ui<?php if (!empty($data['orders'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
			<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
			<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace</a></p>
		</div>
	</div>
</div>