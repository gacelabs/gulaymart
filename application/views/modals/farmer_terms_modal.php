<div class="modal fade" id="farmer_terms_modal" tabindex="-1" role="dialog" aria-labelledby="farmer_terms_modalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Howdy, farmer!</h4>
			</div>
			<div class="modal-body">
				<div>
					<?php $this->view('global/terms'); ?>
				</div>

				<div>
					<?php $this->view('global/privacy'); ?>
				</div>
			</div>
			<div class="modal-footer" style="text-align:left !important;">
				<form action="api/agree_terms" method="post" class="form-validate text-center" data-ajax="1" id="agree-terms-form">
					<h4>Sounds good?</h4>
					<ul class="inline-list checkbox" style="margin-bottom:10px;">
						<li style="margin-right: 5px;">
							<label>
								<input type="checkbox" name="farmer_terms" style="margin-top:3px;"> Terms &amp; Condition
							</label>
						</li>
						<li style="margin-left: 5px;">
							<label>
								<input type="checkbox" name="farmer_policy" style="margin-top:3px;"> Privacy Policy
							</label>
						</li>
					</ul>
					<button type="submit" class="btn btn-theme normal-radius" data-orig-ui="Agreement Signed" disabled>Create My Farm</button>
				</form>
			</div>
		</div>
	</div>
</div>