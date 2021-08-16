<div class="basket-container" id="dashboard_panel_right">
	<input type="hidden" id="min-date" value="<?php echo date("Y-m-d");?>">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" js-element="baskets-panel">
			<!-- per farm location -->
			<?php 
			if ($data['baskets']) {
				$this->load->view('templates/basket/b_item_container', $data);
			}
			?>
			<div class="no-records-ui<?php if (!empty($data['baskets'])): ?> hide<?php endif ?>" style="text-align:center;background-color:#fff;padding:40px 10px;">
				<img src="assets/images/helps/no-orders-found.png" class="img-responsive text-center" style="margin:0 auto 15px auto;">
				<p class="zero-gaps">Find the freshest veggies grown by your community at <a href="" class="btn btn-sm btn-contrast">Marketplace <i class="fa fa-leaf" style="border:1px solid #fff;padding:4px;border-radius:100px;"></i></a></p>
			</div>
			<?php  ?>
		</div>
	</div>
</div>