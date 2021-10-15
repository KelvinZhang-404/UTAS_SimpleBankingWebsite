<?php
include 'session.php';
//include 'logout.php';
include 'db_conn.php';
if($_SESSION['access_type'] != 'general_user'){
    header('location: ./index.php');
}

$account_type = $_POST['account_type'];
$amount = $_POST['amount'];
$currency = isset($_POST['currency']) ? $_POST['currency'] : 1;
$amount *= $currency;
$daily_limitation = ($account_type == "business_account") ? 50000 : 10000;

$origin_result = $mysqli->query("SELECT * FROM $account_type WHERE client_number = ".$_SESSION['user_id']);
$origin_user = $origin_result->fetch_array(MYSQLI_ASSOC);

$daily_usage_result = $mysqli->query("SELECT SUM(outcome) FROM statement WHERE account_type = '$account_type' AND client_number = ".$_SESSION['user_id']." AND to_days(transaction_date) = to_days(now())");
$daily_usage = $daily_usage_result->fetch_array(MYSQLI_ASSOC);

$current_usage = $daily_usage['SUM(outcome)'];
$total_amount = $amount + $current_usage;

// if amount exceeds the balance, display error message
if($amount > $origin_user['balance']) {
    header("location: ./make_payment.php?error=Insufficient funds!");
    exit();
}
// if amount exceeds the daily limitation, display error message
if($total_amount > $daily_limitation){
    header("location: ./make_payment.php?error=You have exceeded your daily transaction limitation for this account!");
    exit();
}
// if the amount is over 25000, send request to manager and insert request into request table
if($amount > 25000 && $account_type == "business_account"){
    $mysqli->query("INSERT INTO `request_table` (request_user_id, request_account, amount_request, reason, request_type) VALUES (".$_SESSION['user_id'].", '$account_type', $amount, 'transactions over $25000', 2)");
    header("location: ./make_payment.php?error=Your transaction amount is over 25000! Your transaction needs to be approved before being processed!");
    exit();
}

// inter transfer
if(isset($_POST['confirm_inter'])){
    $bsb = $_POST['bsb'];
    $bank_name = $_POST['bank_name'];
    $purpose = $_POST['purpose'];
    $balance = $origin_user['balance'] - $amount;

    $transaction = array("From Account: "=>$account_type, "To BSB: "=>$bsb, "To Bank: "=>$bank_name, "Amount: "=>$amount, "Currency: "=>$currency, "Purpose: "=>$purpose);
    $_SESSION['transaction'] = $transaction;

    // Update account balance
    $mysqli->query("UPDATE $account_type SET balance = $balance WHERE client_number = ".$_SESSION['user_id']);
    // Insert statement
    $mysqli->query("INSERT INTO statement (client_number, account_type, to_bsb, to_bank, outcome, purpose, balance) VALUES ({$_SESSION['user_id']}, '$account_type', '$bsb', '$bank_name', $amount, '$purpose', $balance)");
    // Insert transaction record
    $mysqli->query("INSERT INTO `transaction` (client_number, from_account, to_bsb, to_bank, amount, currency, purpose) VALUES ({$_SESSION['user_id']}, '$account_type', '$bsb', '$bank_name', $amount, $currency, '$purpose')");

    header("location: ./make_payment.php");
}

// intra transfer
if(isset($_POST['confirm_intra'])){
    $to_client_number = $_POST['client_number'];
    $to_account = $_POST['to_account'];
    $currency = $_POST['currency'];
    $purpose = $_POST['purpose'];

    if($to_client_number == $_SESSION['user_id'] && $to_account == $account_type) {
        header("location: ./make_payment.php?error=Cannot transfer between the same accounts. Try another account!");
        exit();
    }

    $origin_result = $mysqli->query("SELECT * FROM $account_type WHERE client_number = ".$_SESSION['user_id']);
    $origin_user = $origin_result->fetch_array(MYSQLI_ASSOC);

    $dest_result = $mysqli->query("SELECT * FROM $to_account WHERE client_number = ".$to_client_number);
    $dest_user = $dest_result->fetch_array(MYSQLI_ASSOC);

    if(!$dest_user){
        header("location: ./make_payment.php?error=Cannot find this account. Try another account!");
        exit();
    }

    $origin_balance = $origin_user['balance'] - $amount;
    $dest_balance = $dest_user['balance'] + $amount;

    $transaction = array("From Account: "=>$account_type, "To Client: "=>$to_client_number, "To Account: "=>$to_account, "Amount: "=>$amount, "Currency: "=>$currency, "Purpose: "=>$purpose);
    $_SESSION['transaction'] = $transaction;

    // Update origin account balance
    $mysqli->query("UPDATE $account_type SET balance = $origin_balance WHERE client_number = ".$_SESSION['user_id']);
    // Update destination account balance
    $mysqli->query("UPDATE $to_account SET balance = $dest_balance WHERE client_number = ".$to_client_number);
    // Insert statement
    $mysqli->query("INSERT INTO `statement` (client_number, account_type, to_bsb, to_bank, outcome, purpose, balance) VALUES ({$_SESSION['user_id']}, '$account_type', '666666', 'SECURE BANK', $amount, '$purpose', $origin_balance)");
    $mysqli->query("INSERT INTO `statement` (client_number, account_type, to_bsb, to_bank, income, purpose, balance) VALUES ($to_client_number, '$to_account', '666666', 'SECURE BANK', $amount, '$purpose', $dest_balance)");
    // Insert transaction record
    $mysqli->query("INSERT INTO `transaction` (client_number, from_account, to_bsb, to_bank, amount, currency, purpose) VALUES ({$_SESSION['user_id']}, '$account_type', '666666', 'SECURE BANK', $amount, $currency, '$purpose')");
    $mysqli->query("INSERT INTO `transaction` (client_number, from_account, to_bsb, to_bank, amount, currency, purpose) VALUES ($to_client_number, '$to_account', '666666', 'SECURE BANK', $amount, $currency, '$purpose')");

    header("location: ./make_payment.php");
}

// bill payment
if(isset($_POST['confirm_bill'])){
    $bill_type = $_POST['bill_type'];
    $origin_balance = $origin_user['balance'] - $amount;

    $transaction = array("From Account: "=>$account_type, "Bill Type: "=>$bill_type, "Amount: "=>$amount);
    $_SESSION['transaction'] = $transaction;

    // Update origin account balance
    $mysqli->query("UPDATE $account_type SET balance = $origin_balance WHERE client_number = ".$_SESSION['user_id']);
    // Insert statement
    $mysqli->query("INSERT INTO `statement` (client_number, account_type, to_bsb, to_bank, outcome, balance, purpose) VALUES ({$_SESSION['user_id']}, '$account_type', 'Bill', '$bill_type', $amount, $origin_balance, 'Bill')");
    // Insert transaction record
    $mysqli->query("INSERT INTO `transaction` (client_number, from_account, to_bsb, to_bank, amount, currency, purpose) VALUES ({$_SESSION['user_id']}, '$account_type', 'Bill', '$bill_type', $amount, 1, 'Bill')");

    header("location: ./make_payment.php");
}

$mysqli->close();
