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
	<title>404 Page Not Found</title>  

	<meta name="robots" content="noindex, nofollow">
	<meta property="og:type" content="article">
	<meta property="og:url" content="https://gulaymart.com">
	<meta property="og:title" content="GulayMart Marketplace">
	<meta property="og:description" content="GulayMart is your neighborhood veggies supplier.">
	<meta property="og:image" content="https://gulaymart.com/assets/images/logo.png">
	<meta name="description" content="GulayMart is your neighborhood veggies supplier.">

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
				<?php include APPPATH.'views'.DIRECTORY_SEPARATOR.'global/error_footer.php'; ?>
			</div>
		</section>
	</div>
</body>
</html>