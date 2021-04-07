<?php if (isset($data['category'])): ?>
	<?php /*debug($data, 'stop');*/ ?>
	<div class="product-item panel" data-category="<?php echo $id;?>">
		<div class="product-item-info">
			<a href="">
				<?php if (isset($data['photos']) AND isset($data['photos']['main'])): ?>
					<div class="product-item-top" style="background-image: url('<?php echo base_url($data['photos']['main']['url_path']);?>');">
				<?php else: ?>
					<div class="product-item-top" style="background-image: url('https://place-hold.it/50x50.png?text=No+Image&fontsize=7');">
				<?php endif ?>
				<?php
				$driving_distance = get_driving_distance([
					['lat' => $this->latlng['lat'], 'lng' => $this->latlng['lng']],
					['lat' => $data['latlng']['lat'], 'lng' => $data['latlng']['lng']],
				]);
				// debug($driving_distance, 'stop');
				?>
					<?php if ($driving_distance['distance'] AND $driving_distance['duration']): ?>
						<ul class="spaced-list between">
							<li><kbd class="product-tags"><small><i class="fa fa-map-marker"></i> <?php echo $driving_distance['duration'];?></small></kbd></li>
							<li><kbd class="product-type"><small><i class="fa fa-pagelines"></i> </small></kbd></li>
						</ul>
					<?php endif ?>
				</div>
				<div class="product-item-middle">
					<h1 class="product-title"><?php echo $data['name'];?></h1>
					<p class="product-price">Php <?php echo $data['price'];?> / <?php echo $data['measurement'];?></p>
				</div>
			</a>
		</div>
	</div>
<?php endif ?>