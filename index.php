<?php
include "session.php";

if($_SESSION['user_id'] != 0){
    header("location: ./engine.php");
}

if(isset($_GET['error'])) {
    $errormessage=$_GET['error'];
    echo "<script>alert('$errormessage');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="import" href="template.html">
	<link rel="stylesheet" type="text/css" href="styles/template.css">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
</head>
<body>
	<div id="container">
		<script type="text/javascript" src="scripts/importTemplate.js"></script>

		<div class="formDiv">
			<h3>Log on to Secure Bank</h3>
			<form action="login.php" method="post">
				<div><input type="number" onkeydown="return event.keyCode !== 69" name="client_number" placeholder="Client number" required></div>
				<br>
				<div><input type="password" name="password" placeholder="Password" required></div>
				<br>
				<div><input type="submit" name="login" value="Log on"></div>
			</form>
			
			<a href="register.php">Sign up for Secure Bank</a>
		</div>
		<div class="infoDiv">

		</div>
	</div>

</body>
</html>