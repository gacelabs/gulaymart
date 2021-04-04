<?php if (isset($data['category'])): ?>
	<?php /*debug(get_driving_distance([
		['lat' => $this->accounts->profile['shippings'][0]['lat'], 'lng' => $this->accounts->profile['shippings'][0]['lng']],
		['lat' => $this->accounts->profile['shippings'][1]['lat'], 'lng' => $this->accounts->profile['shippings'][1]['lng']],
	]), 'stop');*/ ?>
	<div class="product-item panel" data-category="<?php echo $id;?>" <?php !isset($forajax) ? str_not_value_echo('all', $id, 'style="display: none;"') : '';?>>
		<div class="product-item-info">
			<a href="">
				<?php if (isset($data['photos']) AND isset($data['photos']['main'])): ?>
					<div class="product-item-top" style="background-image: url('<?php echo base_url($data['photos']['main']['url_path']);?>');">
				<?php else: ?>
					<div class="product-item-top" style="background-image: url('https://place-hold.it/50x50.png?text=No+Image&fontsize=7');">
				<?php endif ?>
					<ul class="spaced-list between">
						<li><kbd class="product-tags"><small><i class="fa fa-map-marker"></i> 26 mins</small></kbd></li>
						<li><kbd class="product-type"><small><i class="fa fa-pagelines"></i> </small></kbd></li>
					</ul>
				</div>
				<div class="product-item-middle">
					<h1 class="product-title"><?php echo $data['name'];?></h1>
					<p class="product-price">Php <?php echo $data['price'];?> / <?php echo $data['measurement'];?></p>
				</div>
			</a>
		</div>
	</div>
<?php endif ?>