<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="farmers_container">
	<?php if ($data['nearby_farms']): ?>
		<h3><b>Farmers nearby</b></h3>
		<div class="farmers-card-container">
			<?php foreach ($data['nearby_farms'] as $key => $farm): ?>
				<div class="farmers-card-item">
					<a href="<?php echo $farm['storefront'];?>" target="<?php echo $farm['name'];?>"><div class="card-top" style="background-image: url('<?php echo $farm['cover_pic'];?>');"></div></a>
					<div class="card-middle">
						<a href="<?php echo $farm['storefront'];?>" target="<?php echo $farm['name'];?>"><div class="farmer-avatar" style="background-image: url('<?php echo $farm['profile_pic'];?>')"></div></a>
					</div>
					<div class="card-footer">
						<p class="zero-gaps"><b><?php echo $farm['name'];?></b></p>
						<p class="zero-gaps"><small class="elem-block"><?php echo $farm['username'];?></small></p>
						<p><small class="elem-block"><img src="assets/images/icons/farms.png" class="mini-img-icon" align="left"> <?php echo $farm['address'];?></small></p>
						<p><small class="elem-block"><i class="fa fa-map-marker"></i> <?php echo $farm['duration'];?> away</small></p>
						<a href="<?php echo $farm['storefront'];?>" target="<?php echo $farm['name'];?>" class="btn btn-theme btn-sm">Visit farm</a>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
</div>