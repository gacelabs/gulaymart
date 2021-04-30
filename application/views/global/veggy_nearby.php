<?php if ($data['nearby_veggies']): ?>
<div id="veggy_nearby_container">
	<ul class="spaced-list between">
		<li><h3><b>Veggies nearby</b></h3></li>
		<li><a href="" class="text-link"><h3><b>More</b></h3></a></li>
	</ul>
	<div class="zero-gaps" id="veggy_nearby_body">
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