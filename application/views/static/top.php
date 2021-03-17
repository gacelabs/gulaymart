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
	<link rel="canonical" href="<?php echo base_url();?>">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
	<?php foreach ($top['metas'] as $value) { echo "<meta ".$value.">\r\n"; } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/global.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/toast.min.css'); ?>">
	<?php
	if (count($top['css'])) {
		foreach ($top['css'] as $value) {
			if (filter_var($value, FILTER_VALIDATE_URL)) {
				echo '<link rel="stylesheet" type="text/css" href="'.$value.'">';
			} elseif (in_array($value, ['main', 'global', 'rwd']) == false) {
				echo '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/'.$value.'').'.css">';
			}
			echo "\r\n";
		}
	}
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/rwd.css'); ?>">
	<?php
	if (count($top['js'])) {
		foreach ($js as $value) {
			if (filter_var($value, FILTER_VALIDATE_URL)) {
				echo '<script type="text/javascript" src="'.$value.'"></script>';
			} elseif (in_array($value, ['main', 'global', 'rwd']) == false) {
				echo '<script type="text/javascript" src="'.base_url('assets/js/'.$value.'').'.js"></script>';
			}
			echo "\r\n";
		}
	}?>
	<!-- <link rel="manifest" href="/manifest.json"> -->
	<script type="text/javascript">
		var fb_acc_response = false, oUser = <?php echo $current_profile ? json_encode($current_profile) : 'false';?>;
		var oValidationRules=<?php echo json_encode($this->session->userdata('valid_fields'));?>;
		var PROFILE_INFO_MESSAGE = '<?php echo PROFILE_INFO_MESSAGE;?>';
		var DEVICE_ID = '<?php echo $this->device_id;?>';
	</script>
</head>