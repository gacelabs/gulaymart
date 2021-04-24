<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<base href="/">
	<meta charset="utf-8">
	<meta name="theme-color" content="#799938">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>DATABASE Error Encountered</title> 

	<meta name="robots" content="noindex, nofollow">
	<meta property="og:type" content="article">
	<meta property="og:url" content="https://gulaymart.com">
	<meta property="og:title" content="Gulay Mart Marketplace">
	<meta property="og:description" content="Gulay Mart is your neighborhood veggies supplier.">
	<meta property="og:image" content="https://gulaymart.com/assets/images/logo.png">
	<meta name="description" content="Gulay Mart is your neighborhood veggies supplier.">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/page-error.css">
	<link rel="stylesheet" type="text/css" href="assets/css/global/defaults.css">
	<link rel="stylesheet" type="text/css" href="assets/css/global/globals.css">
</head>
<body>
	<div id="content_body_wrapper">
		<section id="content__top"></section>
		<section class="container" id="content__middle">
			<div id="global_navbar">
				<div class="container">
					<div id="global_navbar_grid">
						<a href=""><i class="fa fa-leaf" id="global_navbar_logo"></i></a>
					</div>
				</div>
			</div>
			<div class="container-fluid text-center"><h1><?php echo $heading;?></h1><?php echo $message;?></div>
		</section>
		<section id="content__footer">
			<div id="footer_page_container">
				<div class="container">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<h3 style="font-size: 16px;"><b>Categories</b></h3>
						<p style="margin-bottom:5px;"><a href="">All veggies</a></p>
						<p style="margin-bottom:5px;"><a href="">Leafy</a></p>
						<p style="margin-bottom:5px;"><a href="">Root</a></p>
						<p style="margin-bottom:5px;"><a href="">Cruciferous</a></p>
						<p style="margin-bottom:5px;"><a href="">Marrow</a></p>
						<p style="margin-bottom:5px;"><a href="">Stem</a></p>
						<p style="margin-bottom:5px;"><a href="">Allium</a></p>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
						<h3 style="font-size: 16px;"><b>Help Center</b></h3>
						<p style="margin-bottom:5px;"><a href="">Become a farmer</a></p>
						<p style="margin-bottom:5px;"><a href="">How to buy</a></p>
						<p style="margin-bottom:5px;"><a href="">Logistics info</a></p>
						<p style="margin-bottom:5px;"><a href="">Report a bug</a></p>
						<p style="margin-bottom:5px;"><a href="">Contact Us</a></p>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
						<hr class="visible-xs" style="border-color:transparent;">
						<h3 style="font-size: 16px;"><b>Let's social</b></h3>
						<p style="margin-bottom:5px;"><a href="">Facebook</a></p>
						<p style="margin-bottom:5px;"><a href="">Instagram</a></p>
						<p style="margin-bottom:5px;"><a href="">Messenger</a></p>
						<p style="margin-bottom:5px;"><a href="">Email us</a></p>
						<p style="margin-bottom:5px;"><a href="">Blog articles</a></p>
						<p style="margin-bottom:5px;"><a href="">Gulaymart &copy; <?php echo date('Y'); ?></a></p>
					</div>
				</div>
				<!-- <div class="container text-center">
					<div class="col-lg-6">
						<h3 style="font-size: 16px;"><b>Facilities and Services</b></h3>
						<ul class="inline-list right-gap">
							<li><img src="assets/images/toktok.png" width="75"></li>
							<li><img src="assets/images/gcash.png" width="100"></li>
							<li><img src="assets/images/paypal.png" width="100"></li>
						</ul>
					</div>
					<div class="col-lg-6">
						<h3 style="font-size: 16px;"><b>Web Protection by</b></h3>
						<ul class="inline-list right-gap">
							<li><img src="assets/images/websecure.jpg" width="75"></li>
							<li><img src="assets/images/digitalocean.png" width="100"></li>
						</ul>
					</div>
				</div> -->
			</div>
		</section>
	</div>
</body>
</html>