<?php
include 'session.php';
include 'logout.php';
include 'db_conn.php';
if($_SESSION['access_type'] != 'general_user'){
    header('location: ./index.php');
}

$savings_result = $mysqli->query("SELECT * FROM savings_account WHERE client_number = ".$_SESSION['user_id']);
$savings_account = $savings_result->fetch_array(MYSQLI_ASSOC);

$business_result = $mysqli->query("SELECT * FROM business_account WHERE client_number = ".$_SESSION['user_id']);
$business_account = $business_result->fetch_array(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>eStatements</title>
    <link rel="import" href="user_template.html">
    <link rel="stylesheet" type="text/css" href="styles/template.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
</head>
<body>
<div id="container">
    <script type="text/javascript" src="scripts/importTemplate.js"></script>

    <div class="formDiv">
        <h3>Welcome Account Holder <?php echo $_SESSION['user']; ?></h3>
        <form action="" method="post">
            <select id="" name="period" required>
                <option value="" disabled>Select Statement Period</option>
                <option id="" value="1">1 Month</option>
                <option id="" value="3">3 Month</option>
                <option id="" value="6">6 Month</option>
            </select>
            <input type="submit" name="order_statement" value="Order Statement">
        </form>
    </div>

    <div class="infoDiv">
        <?php if(!isset($_POST['order_statement'])){?>

        <?php } else {
            // select specific timestamp of a given period
            $period = $_POST['period'];
            $date = date('Y-m-d h:i:s',  strtotime("-$period month"));

            if($savings_account != NULL) {
                $account_type = "savings_account";
            } elseif($business_account != NULL) {
                $account_type = "business_account";
            } else {
                echo "you need at least 1 account to process your order";
                exit;
            }
            // define amount for user selection
            switch ($period) {
                case 1:
                    $amount = 2.5;
                    break;
                case 3:
                    $amount = 5;
                    break;
                case 6:
                    $amount = 7;
                    break;
            }

            $order_result = $mysqli->query("SELECT * FROM $account_type WHERE client_number = ".$_SESSION['user_id']);
            $order_row = $order_result->fetch_array(MYSQLI_ASSOC);
            $balance = $order_row['balance'] - $amount;

            echo "You have chosen $period Month statement and \$$amount has been deducted from your $account_type";
            // Deduct money from corresponding account and insert this transaction to transaction table
            $mysqli->query("UPDATE $account_type SET balance = $balance WHERE client_number = ".$_SESSION['user_id']);
            $mysqli->query("INSERT INTO statement (client_number, account_type, to_bsb, to_bank, outcome, purpose, balance) VALUES ({$_SESSION['user_id']}, '$account_type', '666666', 'SECURE BANK', $amount, 'statement order', $balance)");

            $statement_result = $mysqli->query("SELECT * FROM statement WHERE client_number = ".$_SESSION['user_id']." AND transaction_date > '$date' ORDER BY transaction_date DESC"); ?>
            <table>
                <tr>
                    <th>Transaction ID</th>
                    <th>From Account</th>
                    <th>To Account</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Purpose</th>
                    <th>Balance</th>
                    <th>Date</th>
                </tr>
                <?php
                while($statement = $statement_result->fetch_array(MYSQLI_ASSOC)){ ?>
                <tr>
                    <td><?php echo $statement['statement_id']; ?></td>
                    <td><?php echo $statement['account_type']; ?></td>
                    <td><?php echo $statement['to_bsb']." ".$statement['to_bank']; ?></td>
                    <td><?php echo $statement['outcome']; ?></td>
                    <td><?php echo $statement['income']; ?></td>
                    <td><?php echo $statement['purpose']; ?></td>
                    <td><?php echo "$".$statement['balance']; ?></td>
                    <td><?php echo $statement['transaction_date']; ?></td>
                </tr>
                <?php } ?>
            </table>
        <?php
            unset($_POST);
            $mysqli->close();
        } ?>
    </div>
</body>
</html>