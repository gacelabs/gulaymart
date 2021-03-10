<base href="<?php echo base_url(); ?>">
<meta charset="utf-8">
<meta name="theme-color" content="#799938">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title><?php if ($page_title) { echo $page_title;} ?></title> 

<meta name="robots" content="<?php echo $index_page == 'yes' ? "index, follow" : "noindex, nofollow"; ?>">
<link rel="canonical" href="<?php echo base_url();?>">
<?php foreach ($metas as $value) { echo "<meta ".$value.">\r\n"; } ?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/global.css'); ?>">
<?php
	foreach ($css as $key => $values) {
		if ($key == "head") {
			foreach ($values as $value) {
				echo '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/'.$value.'').'.css">';
				echo "\r\n";
			}
		}
	}

	foreach ($js as $key => $values) {
		if ($key == "head") {
			foreach ($values as $value) {
				echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'').'.js"></script>';
				echo "\r\n";
			}
		}
	}
?>
<link rel="manifest" href="/manifest.json">