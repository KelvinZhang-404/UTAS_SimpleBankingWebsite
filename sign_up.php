<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<link rel="import" href="template.html">
	<link rel="stylesheet" type="text/css" href="styles/template.css">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
</head>
<body>
	<div id="container">
		<script type="text/javascript" src="scripts/importTemplate.js"></script>
		<div class="formDiv">
		<?php
			// click on 'Sign up' button
			if(isset($_POST['sign_up'])) {
				$client_number = $_POST["client_number"];
				$firstname = $_POST["firstname"];
				$givenname = $_POST["lastname"];
				$password = $_POST["password"];
				// encrypt password
				$hashed_password = crypt($password);
				$mobile = $_POST["mobile"];
				$email = $_POST["email"];
				$dob = $_POST["dob"];
				$account_type = $_POST["account_type"];

				registerUser($client_number, $firstname, $givenname, $hashed_password, $mobile, $email, $account_type, $dob);
			}
			// pass the required data into database
			function registerUser($client_number, $firstname, $givenname, $password, $mobile, $email, $account_type, $dob){
				include 'db_conn.php';
				$insert_user = "INSERT INTO general_user (client_number, firstname, givenname, password, mobile, email, date_of_birth, $account_type) VALUES ($client_number, '$firstname', '$givenname', '$password', '$mobile', '$email', '$dob', $client_number)";
				if($mysqli->query($insert_user) === TRUE) {
                    $mysqli->query("INSERT INTO bank_user (client_number) VALUES ('$client_number')");
				    $mysqli->query("INSERT INTO $account_type (client_number) VALUES ('$client_number')");
					echo "You have successfully registered for a $account_type!<br>";
					echo "<a href='./index.php'>Login here</a>";
				} else {
					echo "This client number is not assigned to any Bank Managers.<br><br>";
					echo "Please contact a staff to get a valid number.<br><br>";
					echo "Register failed!!<br><br>";
					echo "<a href='./register.php'>Go back</a>";
				}
				$mysqli->close();
			}
		?>
		</div>
		<div class="infoDiv">

		</div>
	</div>
</body>
</html>

