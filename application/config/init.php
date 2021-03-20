<?php

/*if ((bool)strstr($_SERVER['HTTP_HOST'], 'local')) {
	$servername = "localhost";
	$username = "root";
	$password = (bool)strstr($_SERVER['HTTP_HOST'], 'local.') ? 'mmlcr266242g!' : '';
} else {
	$servername = "localhost";
	$username = "root";
	$password = "";
}
// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
// create DB if not exists
$sql = "CREATE DATABASE IF NOT EXISTS gulay_mart_db";
$conn->query($sql);
$conn->close();*/