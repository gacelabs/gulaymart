<div class="veggy-nearby-product-item">
	<a href="<?php echo $data['product_url'];?>">
		<div class="veggy-nearby-product-img" style="background-image: url('<?php identify_main_photo($data);?>')";>
			<div class="veggy-nearby-product-body">
				<p class="zero-gaps"><i class="fa fa-map-marker"></i><?php if ($data['durationval'] == 0): ?> Right Away<?php else: ?> About <?php echo $data['duration'];?><?php endif ?></p>
			</div>
		</div>
	</a>
</div>