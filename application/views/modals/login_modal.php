<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="login_modalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="login_body">
					<div class="login-top">
						<ul class="spaced-list between">
							<li data-dismiss="modal" aria-label="Close"><i class="fa fa-chevron-left"></i></li>
							<li><a href="register">Register</a></li>
						</ul>
					</div>

					<div class="login-middle">
						<h1>Log In</h1>
						<p>Find the freshest vegetables grown by the famers in your community.</p>
					</div>

					<div class="login-footer">
						<div class="login-form-body">
							<form method="post" action="profile/sign_in">
								<div class="form-group">
									<input type="email" name="email_address" class="form-control" id="email" placeholder="Email address">
								</div>
								<div class="form-group" style="margin-bottom: 5px;">
									<input type="password" name="password" class="form-control" id="password" placeholder="Password">
								</div>
								<div style="text-align: right;margin-bottom: 10px;">
									<a href="password-reset" style="font-size: 11px;">Forgot Password?</a>
								</div>
								<button class="btn btn-success btn-block">Log In</button>
							</form>
						</div>

						<div class="login-with-social">
							<a href="javascript:;" class="btn btn-block fb-login-btn" scope="public_profile,email">
								<ul class="spaced-list between">
									<li><i class="fa fa-facebook-official" style="color:#4267B2;"></i></li>
									<li>Facebook</li>
									<li><i class="fa fa-chevron-right" style="color:#aaa;"></i></li>
								</ul>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

