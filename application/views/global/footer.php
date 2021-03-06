<div id="footer_page_container">
	<div class="container">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<h3 style="font-size: 16px;"><b>Categories</b></h3>
			<p style="margin-bottom:5px;"><a href="">All veggies</a></p>
			<?php foreach ($this->categories as $key => $category): ?>
				<p style="margin-bottom:5px;"><a href="marketplace/category/<?php echo $category['value'];?>"><?php echo $category['label'];?></a></p>
			<?php endforeach ?>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
			<h3 style="font-size: 16px;"><b>Help Center</b></h3>
			<p style="margin-bottom:5px;"><a href="">Become a farmer</a></p>
			<p style="margin-bottom:5px;"><a href="">How to buy</a></p>
			<p style="margin-bottom:5px;"><a href="">Logistics info</a></p>
			<p style="margin-bottom:5px;"><a href="">Report a bug</a></p>
			<p style="margin-bottom:5px;"><a href="">Contact Us</a></p>
			<p style="margin-bottom:5px;"><a href="terms-of-use/">Terms of Use</a></p>
			<p style="margin-bottom:5px;"><a href="privacy-policy/">Privacy Policy</a></p>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<hr class="visible-xs" style="border-color:transparent;">
			<h3 style="font-size: 16px;"><b>Let's social</b></h3>
			<p style="margin-bottom:5px;"><a href="">Facebook</a></p>
			<p style="margin-bottom:5px;"><a href="">Instagram</a></p>
			<p style="margin-bottom:5px;"><a href="">Messenger</a></p>
			<p style="margin-bottom:5px;"><a href="">Email us</a></p>
			<p style="margin-bottom:5px;"><a href="">Blog articles</a></p>
			<p style="margin-bottom:5px;"><a href="">GulayMart &copy; <?php echo date('Y'); ?></a></p>
		</div>
	</div>
	<hr class="visible-xs" style="clear:both;border-color:transparent;">
	<div class="container text-center">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<!-- <h3 style="font-size: 16px;"><b>Facilities and Services</b></h3>
			<ul class="inline-list right-gap">
				<li><img src="assets/images/toktok.png" width="75"></li>
				<li><img src="assets/images/gcash.png" width="100"></li>
				<li><img src="assets/images/paypal.png" width="100"></li>
			</ul> -->
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h3 style="font-size: 16px;"><b>Web Protection by</b></h3>
			<ul class="inline-list right-gap">
				<li id="install-app"><img src="assets/images/websecure.jpg" width="75"></li>
				<li><a href="https://www.digitalocean.com/?refcode=01b641693698&utm_campaign=Referral_Invite&utm_medium=Referral_Program&utm_source=badge"><img src="https://web-platforms.sfo2.cdn.digitaloceanspaces.com/WWW/Badge%201.svg" alt="DigitalOcean Referral Badge" width="100" /></a></li>
			</ul>
		</div>
	</div>
</div>
<button id="add-pwa" style="display: none;">Install App</button>
<!-- this is to determine if realtime is connected -->
<div class="container">
	<div class="text-right">
		<small id="is-connected" class="fa fa-link"></small>
	</div>
</div>