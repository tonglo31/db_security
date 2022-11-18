<?php
$hostname = "db";
$user = "proj_docker";
$pwd = "password";
$db = "lamp_docker";
$conn = mysqli_connect($hostname, $user, $pwd, $db)
			or die(mysqli_connect_error());	
?>