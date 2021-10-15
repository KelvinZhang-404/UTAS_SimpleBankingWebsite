<?php
include 'session.php';
include 'logout.php';
include 'db_conn.php';
if($_SESSION['access_type'] != 'general_user'){
    header('location: ./index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Bank User Homepage</title>
	<link rel="import" href="user_template.html">
	<link rel="stylesheet" type="text/css" href="styles/template.css">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
</head>
<body>
	<div id="container">
		<script type="text/javascript" src="scripts/importTemplate.js"></script>

		<div class="formDiv">
            <h3>Welcome Account Holder <?php echo $_SESSION['user']; ?></h3>
            <table>
                <?php
                $result = $mysqli->query("SELECT * FROM general_user WHERE client_number = ".$_SESSION['user_id']);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $mysqli->close();
                ?>
                <tr><th>Client Number: </th><td><?php echo $row['client_number']; ?></td></tr>
                <tr><th>Mobile: </th><td><?php echo $row['mobile']; ?></td></tr>
                <tr><th>Email: </th><td><?php echo $row['email']; ?></td></tr>
                <tr><th>Date of Birth: </th><td><?php echo $row['date_of_birth']; ?></td></tr>
                <tr><th>Register Date: </th><td><?php echo $row['register_date']; ?></td></tr>
                <tr><th>Last access: </th><td><?php echo $_SESSION['last_access']; ?></td></tr>
            </table>
		</div>
		<div class="infoDiv">

        </div>
	</div>

</body>
</html>