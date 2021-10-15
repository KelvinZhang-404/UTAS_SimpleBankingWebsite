<?php
include 'session.php';
include 'db_conn.php';
// click on 'Log on' button, validate user, display account information
if(isset($_POST['login'])) {
    $client_number = $mysqli->real_escape_string($_POST["client_number"]);
    $password = $_POST["password"];

    userValidation($client_number, $password);
}
// validate client number first, then valide password
function userValidation($client_number, $password) {
    global $mysqli;
    $result = $mysqli->query("SELECT * FROM bank_user WHERE client_number = $client_number");
    $user = $result->fetch_array(MYSQLI_ASSOC);
    if(!is_null($user)) {

        $object = $mysqli->query("SELECT * FROM {$user['access_type']} WHERE client_number = $client_number");

        $row = $object->fetch_array(MYSQLI_ASSOC);
        if(hash_equals ($row['password'], crypt($password, $row['password']))) {
            $_SESSION['last_access'] = $row['last_access'];
            $_SESSION['user'] = $row['firstname'];
            $_SESSION['access_type'] = $user['access_type'];
            $_SESSION['user_id'] = $client_number;

            $current_time = date("Y/m/d H:i:s");
            $mysqli->query("UPDATE {$user['access_type']} SET last_access = '$current_time' WHERE client_number = $client_number");
            header("location: ./engine.php");
        } else {
            echo "Wrong password!";
            $mysqli->close();
            session_destroy();
            header('location: ./index.php?error=invalid_password');
        }
    } else {
        echo "User not exists!!";
        $mysqli->close();
        session_destroy();
        header('location: ./index.php?error=invalid_username');
    }
}
?>
