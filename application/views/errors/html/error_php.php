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
	<title>PHP Error Encountered</title> 

	<meta name="robots" content="noindex, nofollow">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/page-error.css">
</head>
<body>

	<div id="container">

		<h4>A PHP Error was encountered</h4>

		<p>Severity: <?php echo $severity; ?></p>
		<p>Message:  <?php echo $message; ?></p>
		<p>Filename: <?php echo $filepath; ?></p>
		<p>Line Number: <?php echo $line; ?></p>

		<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

			<p>Backtrace:</p>
			<?php foreach (debug_backtrace() as $error): ?>

				<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

					<p style="margin-left:10px">
					File: <?php echo $error['file'] ?><br />
					Line: <?php echo $error['line'] ?><br />
					Function: <?php echo $error['function'] ?>
					</p>

				<?php endif ?>

			<?php endforeach ?>

		<?php endif ?>

	</div>

</body>
</html>