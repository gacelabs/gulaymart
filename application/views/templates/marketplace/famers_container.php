<div id="farmers_container">
	<?php if ($data['nearby_farms']): ?>
		<ul class="spaced-list between">
			<li><h3><b>Farmers nearby</b></h3></li>
			<!-- <li><a href="" class="text-link"><h3><b>More</b></h3></a></li> -->
		</ul>
		<div id="farmer_list_container">
			<?php foreach ($data['nearby_farms'] as $key => $farm): ?>
				<?php $this->view('looping/farmer_card', ['farm' => $farm]); ?>
			<?php endforeach ?>
		</div>
	<?php endif ?>
</div>