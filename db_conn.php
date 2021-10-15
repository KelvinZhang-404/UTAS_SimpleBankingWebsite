<?php 
	//mysql connection (hostname, username, password, dbname);
	$mysqli = new mysqli('localhost', 'lianxuez', '516785', 'lianxuez');

	//check connection
	if (mysqli_connect_errno($mysqli)) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
 ?>