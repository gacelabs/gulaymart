<div class="container" id="register_container">
	<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
		<div id="register_welcome">
			<h1 style="margin-bottom:20px;"><i class="fa fa-leaf my-logo"></i> <b>Gulaymart</b></h1>
			<h3>Find the freshest vegetables grown by the framers within your community.</h3>
		</div>
	</div>
	<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
		<div id="register_form">
			<div class="register-top">
				<h4><b>Support local farmers. Sign up!</b></h4>
			</div>
			<form action="sign-up" method="post" class="form-validate">
				<div class="register-middle">
					<div class="form-group">
						<input type="email" class="form-control" name="email_address" placeholder="Email address" />
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Password" />
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="re_password" placeholder="Confirm" />
					</div>
					<div class="form-group" style="margin-bottom: 0;">
						<button class="btn btn-theme btn-lg btn-block">Sign Up</button>
					</div>
				</div>
				<div class="register-footer">
					<ul class="spaced-list between">
						<li><a href="<?php echo base_url(); ?>" class="btn btn-default" style="border:none;">Marketplace</a></li>
						<?php if ($current_profile): ?>
							<li><a href="<?php echo base_url('dashboard');?>" class="btn btn-default" style="border:none;">Dashboard</a></li>
						<?php else: ?>
							<li><p class="btn btn-default" data-toggle="modal" data-target="#login_modal" style="border:none;">Log In</p></li>
						<?php endif ?>
					</ul>
				</div>
			</form>
		</div>
	</div>
</div>