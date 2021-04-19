<!DOCTYPE html>
<html>
<head>
	<base href="<?php echo base_url(); ?>">
	<title><?php echo APP_NAME;?> | Dev Builder</title>
</head>
<body>
	<form action="dev-build/run" method="post">
		<input type="password" name="password" value="" placeholder="Enter dev password" required="required">
		<br>
		<label>
			<input type="checkbox" name="drop" value="1">
			Drop All Tables
		</label>
		<br>
		<button>Run</button>
	</form>
	<br><br>
	<form action="dev-build/fetch-cities" method="post">
		<input type="password" name="password" value="" placeholder="Enter dev password" required="required">
		<br>
		<button>Fetch Cities</button>
	</form>
</body>
</html>