<!DOCTYPE html>
<html>
<head>
	<base href="<?php echo base_url();?>">
	<title>Gulaymart | Add to Home Screen</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index, follow">
	<meta name="description" content="GulayMart Add to Home Screen NOW!">

	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo current_full_url();?>">
	<meta property="og:title" content="GulayMart Marketplace » Add to Home Screen">
	<meta property="og:description" content="GulayMart Add to Home Screen NOW!">
	<meta property="og:image" content="<?php echo base_url('assets/images/landing/0.png');?>">
	<meta property="og:image" content="<?php echo base_url('assets/images/landing/1.png');?>">
	<meta property="og:image" content="<?php echo base_url('assets/images/landing/2.png');?>">
	<meta property="og:image" content="<?php echo base_url('assets/images/landing/3.png');?>">

	<link href="assets/images/favicon.png" rel="icon" type="image/x-icon">
	<link rel="canonical" href="<?php echo base_url();?>">
	<link rel="stylesheet" type="text/css" href="assets/css/landing/landing.css">
	<link rel="manifest" href="/public/manifest.webmanifest.json">
	<script type="text/javascript">
		var MAIN_URL = '<?php echo base_url();?>';
	</script>
</head>
<body>
	<section id="wrapper">
		<div class="btn-box a">
				<button id="add_home_btn" class="add-pwa">ADD TO HOME SCREEN</button>
				<p><small>*For Android only</small></p>
			</div>
		<div class="grid-parent">
			<div class="grid-child" id="bg_box">
				<div id="first" style="background-image: url('assets/landing/images/0.jpg');">
					<h1 class="grid-subtitle">When you buy from GulayMart,<br>you helped our local <span id="subphrase">farmers to produce more.</span></h1>
				</div>
			</div>
			<div class="grid-child" id="preview">
				<img src="assets/images/landing/preview.png" style="width:100%">
				<div class="btn-box">
					<button id="add_home_btn" class="add-pwa">ADD TO HOME SCREEN</button>
					<p><small>*For Android only</small></p>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript" src="assets/js/landing/jquery.js"></script>
	<script type="text/javascript" src="assets/js/landing/landing.js"></script>
</body>
</html>