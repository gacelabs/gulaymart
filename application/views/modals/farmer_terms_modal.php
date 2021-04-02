<div class="modal fade" id="farmer_terms_modal" tabindex="-1" role="dialog" aria-labelledby="farmer_terms_modalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Howdy, farmer!</h4>
			</div>
			<div class="modal-body">
				<div>
					<h3>Terms &amp; Condition</h3>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				</div>

				<div>
					<h3>Privacy Policy</h3>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
					proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
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
					<button type="submit" class="btn btn-theme normal-radius" disabled>Create My Farm</button>
				</form>
			</div>
		</div>
	</div>
</div>