
<div class="veggy-nearby-product-item">
	<a href="<?php echo $data['product_url'];?>">
		<?php
			$main_photo = 'https://place-hold.it/228x268.png?text=No+Image&fontsize=14';
			if (!isset($data['photos']['main']) AND isset($data['photos']['other'])) {
				$main_photo = $data['photos']['other'][0]['url_path'];
			} elseif (isset($data['photos']['main'])) {
				$main_photo = $data['photos']['main']['url_path'];
			} 
		?>
		<div class="veggy-nearby-product-img" style="background-image: url('<?php echo base_url($main_photo);?>'); background-size: 228px 268px;">
			<div class="veggy-nearby-product-body">
				<p class="zero-gaps"><i class="fa fa-map-marker"></i><?php if ($data['durationval'] == 0): ?> Right Away<?php else: ?> About <?php echo $data['duration'];?><?php endif ?></p>
			</div>
		</div>
	</a>
</div>