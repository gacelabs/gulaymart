<!-- LOOP HERE -->
<div class="farmer-list-card">
	<a href="<?php echo $farm['storefront'];?>"<?php if (!$this->agent->is_mobile()): ?> target="<?php echo $farm['name'];?>"<?php endif ?>>
		<div class="farmer-list-cover" style="background-image: url('<?php echo $farm['cover_pic'];?>');"></div>
	</a>
	<div class="farmer-list-profile-photo" style="background-image: url('<?php echo $farm['profile_pic'];?>')"></div>
	<div class="farmer-list-footer">
		<div class="ellipsis-container" style="margin-bottom:5px;">
			<p class="zero-gaps text-caps"><b><?php echo $farm['name'];?></b></p>
		</div>
		<!-- <p class="zero-gaps"><small class="elem-block"><?php echo $farm['username'];?></small></p> -->
		<p class="zero-gaps">
			<small class="elem-block"><img src="assets/images/icons/farms.png" class="mini-img-icon" style="width: 16px; margin: 0 5px 5px 0;"><?php echo $farm['city_prov'];?></small>
		</p>
		<p><small class="elem-block"><i class="fa fa-map-marker"></i> <?php if ($farm['durationval'] == 0): ?> Right Away<?php else: ?> <?php echo $farm['duration'];?> away<?php endif ?></small></p>
		<a href="<?php echo $farm['storefront'];?>"<?php if (!$this->agent->is_mobile()): ?> target="tabFarm<?php echo $farm['farm_location_id'];?>"<?php endif ?> class="btn btn-theme btn-sm">VISIT FARM</a>
	</div>
</div>