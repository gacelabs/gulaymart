
<div class="veggy-nearby-product-item">
	<a href="<?php echo $data['product_url'];?>">
		<?php if (isset($data['photos']) AND isset($data['photos']['main'])): ?>
			<div class="veggy-nearby-product-img" style="background-image: url('<?php echo base_url($data['photos']['main']['url_path']);?>'); background-size: 228px 268px;">
		<?php else: ?>
			<div class="veggy-nearby-product-img" style="background-image: url('https://place-hold.it/228x268.png?text=Product+Image+Unavailable&fontsize=14');">
		<?php endif ?>
			<div class="veggy-nearby-product-body">
				<p class="zero-gaps"><i class="fa fa-map-marker"></i><?php if ($data['durationval'] == 0): ?> Right Away<?php else: ?> About <?php echo $data['duration'];?><?php endif ?></p>
			</div>
		</div>
	</a>
</div>