<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="import" href="template.html">
	<link rel="stylesheet" type="text/css" href="styles/template.css">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
	<!-- <script type="text/javascript" src="scripts/validation.js"></script> -->
</head>
<body>
	<div id="container">
		<script type="text/javascript" src="scripts/importTemplate.js"></script>
<!--		a sign up form with password check and pattern design-->
		<div class="formDiv">
			<h3>Sign up for Secure Bank</h3>
			<form action="sign_up.php" method="post">
				<div><input type="number" onkeydown="return event.keyCode !== 69" name="client_number" placeholder="Client number" required></div>
				<br>
				<div><input type="text" name="firstname" placeholder="First name" required></div>
				<div><input type="text" name="lastname" placeholder="Given name" required></div>
				<br>
				<div><input id="pwd" type="password" name="password" 
					pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[~!#$]).{8,12}" 
					placeholder="Password" 
					title="Your password should be 8 to 12 characters in length.&emsp;
					Must contain at least 1 lower case letter, 1 uppercase letter, 1 number and one of the following special characters ~!#$" required></div>
				<div><input id="pwd_match" type="password" name="password_match" 
					placeholder="Re-enter password" required></div>
				<br>
				<div><input type="tel" name="mobile" placeholder="Mobile number" required></div>
				<br>
				<div><input type="email" name="email" placeholder="Email address" required></div>
				<br>
				<div><input type="date" name="dob" placeholder="Date of birth" required></div>
				<br>
				<div>
					<select name="account_type" required>
						<option value="" disabled>Select an account type</option>
						<option value="savings_account">Savings Account</option>
						<option value="business_account">Business Account</option>
						<!--<option value="bank_manager">Bank Manager</option>-->
					</select>
				</div>
				<div><input id="sign_up" type="submit" name="sign_up" value="Sign up"></div>
			</form>
		</div>
		<div class="infoDiv">

		</div>
	</div>

<!--	javaScript used to match the password-->
	<script type="text/javascript">
		var password = document.getElementById("pwd");
		var password_match = document.getElementById("pwd_match");
		function validatePassword() {
			if(password.value != password_match.value) {
				// console.log("ddd");
				password_match.setCustomValidity("Password does not Match");
			}else {
				password_match.setCustomValidity('');
				}
		}
		password_match.onkeyup = validatePassword;
	</script>
</body>
</html>