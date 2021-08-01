<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?php echo base_url(); ?>">
	<meta charset="utf-8">
	<meta name="theme-color" content="#799938">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php if ($top['page_title']) { echo $top['page_title'];} ?></title> 

	<meta name="robots" content="<?php echo $top['index_page'] == 'yes' ? "index, follow" : "noindex, nofollow"; ?>">
	<link href="assets/images/favicon.png" rel="icon" type="image/x-icon">
	<link rel="apple-touch-icon" href="assets/images/favicon.png">
	<link rel="canonical" href="<?php echo base_url();?>">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
	
	<?php foreach ($top['metas'] as $value) { echo "<meta ".$value.">\r\n"; } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/toast.min.css'); ?>">

	<?php
	$this->minify->css([ 'global/defaults.css', 'global/globals.css']);
	if (count($top['css'])) {
		foreach ($top['css'] as $value) {
			if (filter_var($value, FILTER_VALIDATE_URL)) {
				echo '<link rel="stylesheet" type="text/css" href="'.$value.'">';
			} elseif ((bool)strstr($value, '.min') == true) {
				echo '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/'.$value.'').'.css">';
			} elseif (in_array($value, ['main', 'global', 'rwd']) == false) {
				$this->minify->add_css($value.'.css');
			} 
			echo "\r\n";
		}
		echo $this->minify->deploy_css(false);
	}
	?>
	<link rel="manifest" href="/public/manifest.webmanifest.json">
	
	<script type="text/javascript" id="main-obj-script">
		var fb_acc_response = false, oUser = <?php echo $current_profile ? json_encode($current_profile) : 'false';?>;
		var oValidationRules=<?php echo json_encode($this->valid_fields);?>;
		var PROFILE_INFO_MESSAGE = '<?php echo PROFILE_INFO_MESSAGE;?>';
		var DEVICE_ID = '<?php echo $this->device_id;?>';
		var oSegments = <?php echo json_encode($this->uri->segment_array()); ?>;
		var APPNAME = '<?php echo APP_NAME;?>';
	</script>
</head>