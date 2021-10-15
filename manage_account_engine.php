<?php
include 'session.php';
//include 'logout.php';
include 'db_conn.php';
if($_SESSION['access_type'] != 'bank_manager'){
    header('location: ./index.php');
}


if(isset($_POST['savings_account'])){ // if manager clicks the add or remove savings account button
    $str = $_POST['savings_account'];
    $operation = substr($str,0, strrpos($str," "));
    $client_number = substr($str,strripos($str," ")+1);

    if($operation == "add") {
        $mysqli->query("UPDATE general_user SET savings_account = $client_number WHERE client_number = $client_number");
        $mysqli->query("INSERT INTO savings_account (client_number) VALUE ($client_number)");
    } else {
        $mysqli->query("UPDATE general_user SET savings_account = NULL WHERE client_number = $client_number");
        $mysqli->query("DELETE FROM savings_account WHERE client_number = $client_number");
    }
    unset($_POST['savings_account']);
}

if(isset($_POST['business_account'])){ // if manager clicks the add or remove business account button
    $str =  $_POST['business_account'];
    $operation = substr($str,0, strrpos($str," "));
    $client_number = substr($str,strripos($str," ")+1);

    if($operation == "add") {
        $mysqli->query("UPDATE general_user SET business_account = $client_number WHERE client_number = $client_number");
        $mysqli->query("INSERT INTO business_account (client_number) VALUE ($client_number)");
    } else {
        $mysqli->query("UPDATE general_user SET business_account = NULL WHERE client_number = $client_number");
        $mysqli->query("DELETE FROM business_account WHERE client_number = $client_number");
    }
    unset($_POST['business_account']);
}

if(isset($_POST['confirm'])) { // if manager clicks to confirm the access level change
    $str =  $_POST['confirm'];
    $client_number = substr($str,strripos($str," ")+1);
    $access_level = $_POST['access_selector'];
    echo "client num: ".$access_level;
    $mysqli->query("UPDATE general_user SET access_level = '$access_level' WHERE client_number = $client_number");
    unset($_POST);
}

$mysqli->close();
header("location: manage_account.php?message=operation success");

