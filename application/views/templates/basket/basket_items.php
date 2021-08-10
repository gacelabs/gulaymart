
<div class="order-table-item">
	<div class="order-grid-column order-labels">
		<div class="text-left">
			<p><small class="elem-block"><b>PRODUCT</b><i>- <?php echo date('M. j, Y | g:i a', strtotime($baskets['updated']));?></i></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<p><small class="elem-block"><b>PRICE / UNIT</b></small></p>
		</div>
		<div class="text-right hidden-sm hidden-xs">
			<p><small class="elem-block"><b>QUANTITY</b></small></p>
		</div>
		<div class="text-right">
			<button class="btn btn-xs btn-default order-remove-btn" data-toggle="tooltip" data-placement="top" title="Remove all?" js-element="remove-all"><span class="text-danger">&times;</span></button>
		</div>
	</div>

	<div class="order-item-list">
		<?php 
			foreach ($baskets['products'] as $index => $item) {
				$this->view('templates/basket/basket_item', ['item' => $item]);
			}
		?>
	</div>

	<?php $farm = $baskets['farm'];?>

	<div class="order-grid-footer" js-element="location-id-<?php echo $location_id;?>">
		<div class="text-left order-footer-farm hidden-xs">
			<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
			<p class="zero-gaps"><a<?php if (!$this->agent->is_mobile()): ?> target="farm_<?php echo $farm['id'];?>"<?php endif ?> href="<?php storefront_url($farm, true);?>" class="text-link"><?php echo ucwords($farm['name']);?></a></p>
			<p class="zero-gaps"><?php echo $farm['city_prov'];?></p>
		</div>
		<div class="text-left order-footer-payment hidden-xs">
			<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
			<p class="zero-gaps">Cash On Delivery</p>
		</div>
		<div class="text-left hidden-xs">
			<p class="hidden-xs" style="margin-bottom:3px;"><small class="elem-block"><b>DELIVER DATE</b></small></p>
			<?php if ($order_type == 1): ?>
				<p class="zero-gaps">Today <span class="text-gray"><i><?php compute_eta($item['duration']);?></i></span></p>
			<?php else: ?>
			<input type="date" js-element="schedule-value" value="<?php echo $schedule;?>" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y')."-12-31"; ?>">
			<?php endif; ?>
		</div>
		<div class="order-footer-total">
			<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
			<div class="text-left hidden-lg hidden-md hidden-sm">
				<p class="zero-gaps hidden-xs"><small class="elem-block"><b>DELIVER DATE</b></small></p>
				<?php if ($order_type == 1): ?>
					<p class="zero-gaps">Today <span class="text-gray"><i><?php compute_eta($item['duration']);?></i></span></p>
				<?php else: ?>
				<input type="date" js-element="schedule-value" value="<?php echo $schedule;?>" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y')."-12-31"; ?>">
				<?php endif; ?>
			</div>
			<div>
				<p class="hidden-xs" style="margin-bottom:3px;">
					<small class="elem-block">
						<b>
						<?php if ($item['status'] == 1): ?>
							PROCEED
						<?php else: ?>
							VERIFY
						<?php endif ?>
						</b>
					</small>
				</p>
				<div class="checkout-btn-container">
					<button class="btn btn-contrast btn-sm" js-element="checkout-data" js-json='<?php echo json_encode($baskets['checkout_data'], JSON_NUMERIC_CHECK);?>'>CHECKOUT<i class="fa fa-angle-right icon-right"></i></button>
				</div>
			</div>
		</div>
	</div>
</div>