
<?php $farm = $orders['seller'];?>

<div class="order-grid-footer">
	<div class="order-footer-farm text-left hidden-xs">
		<p class="zero-gaps"><small class="elem-block"><b>FARM</b></small></p>
		<p class="zero-gaps"><a<?php if (!$this->agent->is_mobile()): ?> target="farm_<?php echo $farm['id'];?>"<?php endif ?> href="<?php storefront_url($farm, true);?>" class="text-link"><?php echo $farm['name'];?></a></p>
		<p class="zero-gaps"><?php echo $farm['city_prov'];?></p>
	</div>
	<div class="order-footer-payment text-left hidden-xs">
		<p class="zero-gaps"><small class="elem-block"><b>PAYMENT METHOD</b></small></p>
		<p class="zero-gaps">Cash On Delivery</p>
		<p class="zero-gaps"></p>
	</div>
	<div class="text-left hidden-xs">
		<p style="margin-bottom:5px;"><small class="elem-block"><b>ORDER STATUS</b></small></p>
		<p class="zero-gaps"><span class="text-capsule status-placed"><?php echo ucwords(urldecode($data['status']));?></span></p>
	</div>
	<div class="order-footer-total">
		<button class="btn btn-xs btn-default hidden-lg hidden-md hidden-sm" js-event="showOrderFooter" style="height:22px;"><i class="fa fa-angle-down"></i></button>
		<p class="hidden-lg hidden-md hidden-sm text-center" style="padding-top:3px;margin:0;"><span class="text-capsule status-placed">Placed</span></p>
		<div>
			<p class="hidden-xs" style="margin-bottom:3px;"><small class="elem-block"><b>TOTAL</b></small></p>
			<p class="zero-gaps"><i>Delivery Fee:</i> <?php echo format_number($orders['fee']);?> + &#x20b1; <?php echo format_number($initial_total);?></p>
			<p style="border-top:1px solid #888;display:inline-block;padding:0 0 0 35px;margin:0;">&#x20b1; <b><?php echo format_number($initial_total + (float)$orders['fee']);?></b></p>
		</div>
	</div>
</div>