<div class="container" id="register_container">
	<?php if (empty($current_profile)): ?>
	<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
		<div id="register_welcome">
			<h1 style="margin-bottom:20px;"><a href=""><i class="fa fa-leaf my-logo"></i></a> <b>GulayMart</b></h1>
			<h3>Find the freshest vegetables grown by the farmers within your community.</h3>
		</div>
		<div class="hidden-xs">
			<div id="register_farmers_images">
				<div><img src="assets/images/product-item/microgreens.jpg"></div>
				<div><img src="assets/images/product-item/pechay.jpg"></div>
				<div><img src="assets/images/product-item/lettuce.jpg"></div>
			</div>
			<p>Buying vegetables from local farmers help them live a life for a day. For the longest time, we've been supporting big brands, it's time to make a difference and touch the lives of many urban and rural farmers in the country.</p>
			<p>Let's help support locals, buy at <a href="" class="text-link">Marketplace</a></p>
		</div>
	</div>
	<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
		<div id="register_form">
			<div class="register-top">
				<h3><b>Support local farmers.<br>Sign up!</b></h3>
			</div>
			<form action="/sign-up/" method="post" class="form-validate">
				<div class="register-middle">
					<div class="form-group">
						<input type="email" class="form-control" name="email_address" placeholder="Email address" />
					</div>
					<div class="form-group" style="position: relative;">
						<i class="bi bi-eye-slash toggle-password"></i>
						<input type="password" class="form-control password" name="password" id="reg-password" placeholder="Password" />
					</div>
					<div class="form-group" style="position: relative;">
						<i class="bi bi-eye-slash toggle-password"></i>
						<input type="password" class="form-control password" name="re_password" id="reg-re-password" placeholder="Confirm" />
					</div>
					<div class="form-group">
						<p id="character_challenge" class="zero-gaps hide text-danger text-center" style="font-size: x-small;">Password requires atleast 8 letters, 1 numeric, 1 special character.</p>
					</div>
					<div class="form-group" style="margin-bottom: 0;">
						<button type="submit" data-keep-loading="5000" loading-text="Signing up..." class="btn btn-theme btn-lg btn-block">Sign Up</button>
						<div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_KEY;?>" data-size="invisible"></div>
						<p class="text-center h6">This site is protected by reCAPTCHA and the Google <a href="https://www.google.com/intl/en/policies/privacy/"<?php if (!$this->agent->is_mobile()): ?> target="_blank"<?php endif ?>>Privacy Policy</a> and <a href="https://www.google.com/intl/en/policies/terms/"<?php if (!$this->agent->is_mobile()): ?> target="_blank"<?php endif ?>>Terms of Service</a> apply.</p>
					</div>
				</div>
				<div class="register-footer">
					<ul class="spaced-list between">
						<li><a href="terms-of-use/" class="text-link" style="border:none;">Terms of Use</a></li>
						<?php if ($current_profile): ?>
							<li><a href="profile/" class="text-link" style="border:none;">Dashboard</a></li>
						<?php else: ?>
							<li><a class="text-link" data-toggle="modal" data-target="#login_modal" style="border:none;">Log In</a></li>
						<?php endif ?>
					</ul>
				</div>
			</form>
		</div>
	</div>
	<?php else: ?>
	<div style="max-width:290px; margin:20px auto;">
		<div class="text-step-basic">
			<p class="text-center"><i class="fa fa-info-circle"></i></p>
			<div>
				<p>Howdy<?php echo ", <b>".$current_profile['firstname']; ?></b>!</p>
				<p>It looks like you've already logged in.</p>
				<ul class="spaced-list between">
					<li><small><a href="/" class="text-link">Home</a></small></li>
					<li><small><a href="basket/" class="text-link">Basket</a></small></li>
				</ul>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>