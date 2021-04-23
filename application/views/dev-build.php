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
			<input type="radio" name="mode" value="clear">
			Clear All Tables (id incremental and data restored)
		</label>
		<br>
		<label>
			<input type="radio" name="mode" value="drop">
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