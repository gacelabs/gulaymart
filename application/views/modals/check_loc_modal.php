<div class="modal fade" id="check_loc_modal" tabindex="-1" role="dialog" aria-labelledby="check_loc_modalLabel" <?php if (empty(get_cookie('prev_latlng', true))): ?> data-keyboard="false" data-backdrop="static"<?php endif ?>>
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php if (!empty(get_cookie('prev_latlng', true))): ?>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php endif ?>
				<h4 class="modal-title">Check Location</h4>
			</div>
			<div class="modal-body">
				<?php if ($this->agent->is_mobile()):?>
					<div class="text-step-basic">
						<p class="zero-gaps text-center"><i class="fa fa-exclamation-circle"></i></p>
						<p class="zero-gaps">Use the box below to search the <b class="text-contrast">city</b> where you live in.</p>
					</div>

					<div>
						<span id="basic-addon1" style="font-size:18px;">My City is</span>
						<input type="text" class="form-control" id="check-place" placeholder="Enter your current city here..." aria-describedby="basic-addon1" style="height:45px;font-size:18px;">
					</div>
					<br>
				<?php endif ?>

				<p class="zero-gaps">As part of Gulaymart's core values to <b>support local farmers</b>, we urge everyone to filter the products based on your residing city. This way, we can be sure that the products shown are produced by the farmers within your local community.</p>
				<img src="assets/images/banner/check-location.png" class="img-responsive" style="margin: 20px 0;">

				<?php if (!$this->agent->is_mobile()):?>
					<div class="text-step-basic">
						<p class="zero-gaps text-center"><i class="fa fa-exclamation-circle"></i></p>
						<p class="zero-gaps">Use the box below to search the <b class="text-contrast">city</b> where you live in.</p>
					</div>

					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1" style="font-size:18px;">My City is</span>
						<input type="text" class="form-control" id="check-place" placeholder="Enter your current city here..." aria-describedby="basic-addon1" style="height:45px;font-size:18px;">
					</div>
				<?php endif ?>
				<?php if (!$current_profile): ?>
					<div class="text-center" style="margin-top:20px;background-color:#eaeaea;padding:15px;border-radius:4px;">
						<p>━ OR ━</p>
						<ul class="inline-list">
							<li data-dismiss="modal" data-toggle="modal" data-target="#login_modal"><button class="btn btn-default btn-sm">Log In</button></li>
							<li><a href="register/" class="btn btn-default btn-sm icon-right">Register</a></li>
						</ul>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>