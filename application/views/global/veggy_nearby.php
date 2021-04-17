<?php if ($data['nearby_veggies']): ?>
<div id="veggy_nearby_container">
	<ul class="spaced-list between">
		<li><h3><b>Veggies nearby</b></h3></li>
		<li><a href="" class="text-link"><h3><b>More</b></h3></a></li>
	</ul>
	<div class="panel zero-gaps" id="veggy_nearby_body">
		<div id="veggy_nearby_left_container">
			<div class="v-center">
				<img src="assets/images/delivermotor.png" class="img-responsive hidden-sm hidden-xs">
				<h4 class="zero-gaps">At your kitchen in a matter of minutes!</h4>
			</div>
		</div>
		<div id="veggy_nearby_right_container">
			<?php
				foreach ($data['nearby_veggies'] as $key => $veggy) {
					$this->view('looping/veggy_card', ['data'=>$veggy]);
				}
			?>
		</div>
	</div>
</div>
<?php endif ?>