<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="login_modalLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<div id="login_body">
					<div class="login-top">
						<ul class="spaced-list between">
							<li data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></li>
							<li><a href="register" class="btn btn-sm btn-default text-contrast" style="border:0;">Register</a></li>
						</ul>
					</div>

					<div class="login-middle login-detail">
						<h1>Log In</h1>
						<p>Find the freshest vegetables grown by the farmers in your community.</p>
					</div>

					<div class="login-middle reset-detail hide">
						<h1>Forgot Password</h1>
						<p>We'll send your current password to the email address you've used to register.</p>
					</div>

					<div class="login-middle fb-login-panel hide">
						<h1>Logging in thru Facebook</h1>
						<p id="fb-good">We've detected your Facebook login session, we are logging you in now, please wait.</p>
						<p id="fb-bad" class="hide" style="color: #e43232; font-weight: 500;">It seems like your Email address has restricted access permission on Facebook?<br>Please enter the Email address to proceed into your account with GulayMart.</p>
					</div>

					<div class="login-footer">
						<div class="login-form-body">
							<form method="post" action="sign-in" class="form-validate sign-in-form">
								<div class="form-group">
									<input type="email" name="email_address" class="form-control" placeholder="Email address">
								</div>
								<div class="form-group" style="margin-bottom: 5px; position: relative;">
									<i class="bi bi-eye-slash toggle-password"></i>
									<input type="password" name="password" id="log-password" class="form-control password" placeholder="Password">
								</div>
								<div class="checkbox<?php if ($this->agent->is_mobile()): ?> hide<?php endif ?>">
									<label class="pull-left remember-tick">
										<input type="checkbox" name="remember_me"<?php if ($this->agent->is_mobile()): ?> checked<?php endif ?> />
										<span>Remember me</span>
									</label>
								</div>
								<div style="text-align: right;margin-bottom: 10px;">
									<a href="javascript:;" class="resetpass-btn" style="font-size: 11px;">Forgot Password?</a>
								</div>
								<button type="submit" class="btn btn-theme btn-block">Log In</button>
							</form>
							<form method="post" action="authenticate/recover" class="form-validate resetpass-form hide" data-ajax="1">
								<div class="form-group">
									<input type="email" name="email_address" class="form-control" placeholder="Email address">
								</div>
								<button type="submit" class="btn btn-danger btn-block resetpass-submit">Send Password</button>
							</form>
							<form action="no-action" method="post" id="invalid-fb-email-form" class="form-validate invalid-fb-email hide">
								<div class="form-group">
									<input type="email" name="email" class="form-control" placeholder="Email address" required="required">
								</div>
								<button type="submit" class="btn btn-success btn-block fbemail-submit">Set your Email Address</button>
							</form>
						</div>

						<div class="login-with-social">
							<a href="javascript:;" class="btn btn-block fb-login-btn" scope="public_profile,email">
								<ul class="spaced-list between">
									<li><i class="fa fa-facebook-official" style="color:#4267B2;"></i></li>
									<li id="fb-status">Facebook</li>
									<li><i class="fa fa-chevron-right" style="color:#aaa;"></i></li>
								</ul>
							</a>
							<!-- <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_KEY;?>" data-size="invisible"></div> -->
							<button class="btn btn-default btn-block ask-sign-in hide"><i class="fa fa-chevron-left pull-left" style="color:#aaa;margin-top:3px;"></i>back to normal <b>Log In</b></button>
							<button class="btn btn-default btn-block fb-signing-in hide"><span class="spinner-border spinner-border-sm"></span>&nbsp;Signing you in with Facebook...</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

