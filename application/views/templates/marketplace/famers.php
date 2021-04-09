<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="farmers_container">
	<?php if ($data['nearby_farms']): ?>
		<h3><b>Farmers nearby</b></h3>
		<div class="farmers-card-container">
			<?php foreach ($data['nearby_farms'] as $key => $farm): ?>
				<div class="farmer-list-card">
					<a href="<?php echo $farm['storefront'];?>" target="<?php echo $farm['name'];?>">
						<div class="farmer-list-cover" style="background-image: url('<?php echo $farm['cover_pic'];?>');"></div>
					</a>
					<div class="farmer-list-profile-photo" style="background-image: url('<?php echo $farm['profile_pic'];?>')"></div>
					<div class="farmer-list-footer">
						<div class="ellipsis-container" style="margin-bottom:5px;">
							<p class="zero-gaps"><b><?php echo $farm['name'];?></b></p>
						</div>
						<p class="zero-gaps"><small class="elem-block"><?php echo $farm['username'];?></small></p>
						<p><small class="elem-block"><i class="fa fa-map-marker"></i> <?php echo $farm['duration'];?> away</small></p>
						<a href="<?php echo $farm['storefront'];?>" target="<?php echo $farm['name'];?>" class="btn btn-theme btn-sm">VISIT FARM</a>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	<?php endif ?>
</div>