<div class="basket-container" id="dashboard_panel_right">
	<input type="hidden" id="min-date" value="<?php echo date("Y-m-d");?>">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" js-element="baskets-panel">
			<!-- per farm location -->
			<?php $this->view('templates/basket/basket_items', ['data_baskets' => $data['baskets']]); ?>
		</div>
	</div>
</div>