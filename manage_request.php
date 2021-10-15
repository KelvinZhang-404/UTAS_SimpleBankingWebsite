<?php
include 'session.php';
include 'logout.php';
include 'db_conn.php';
if($_SESSION['access_type'] != 'bank_manager'){
    header('location: ./index.php');
}

if(isset($_GET['message'])){
    $message = $_GET['message'];
    echo "<script>alert('$message');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Management</title>
    <link rel="import" href="manager_template.html">
    <link rel="stylesheet" type="text/css" href="styles/template.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">

</head>
<body>
<div id="container">
    <script type="text/javascript" src="scripts/importTemplate.js"></script>
    <div class="infoDiv">
        <h3>Request Management</h3>
        <?php
        $result = $mysqli->query("SELECT * FROM request_table");
        if($result->num_rows == 0) {
            echo "No requests!";
        } else { ?>
        <form id="btnForm" action="" method="post">
        <table>
            <tr>
                <th>Request ID</th><th>Client Number</th><th>Request Account</th><th>Request Amount</th><th>Reason</th>
            </tr>
            <?php
            // show all requests in a table
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                unset($row['request_date'], $row['request_type']);
                echo "<tr>";
                foreach ($row as $key => $value){
                    if($key == "request_account") {
                       $value = $value == "savings_account" ? "Savings Account" : "Business Account";
                    }
                    echo "<td>$value</td>";
                }
                echo "<td><button name='approve' value='".$row['request_id']."'>Approve</button></td>";
                echo "<td><button name='deny' value='".$row['request_id']."'>Deny</button></td>";
                echo "</tr>";
            }
            ?>
        </table>
        </form>
        <?php } ?>
    </div>
</div>

<?php
if(isset($_POST['approve'])){
    $request_id = $_POST['approve'];

    $request_result = $mysqli->query("SELECT * FROM request_table WHERE request_id = $request_id");
    $request = $request_result->fetch_array(MYSQLI_ASSOC);
    $account = $request['request_account'];
    $client = $request['request_user_id'];

    if($request['request_type'] == 1) { // if user sends a request for opening an account
        $balance = $account == "savings_account" ? 99950 : 99900;
        $mysqli->query("INSERT INTO $account (client_number, balance) VALUES ($client, $balance)");
        $mysqli->query("DELETE FROM request_table WHERE request_id = $request_id");
        $mysqli->query("UPDATE general_user SET $account = $client WHERE client_number = $client");
    } else { // if user sends a request for transaction amount over 25000
        $amount = $request['amount_request'];
        $request_account_result = $mysqli->query("SELECT * FROM $account WHERE client_number = $client");
        $request_account = $request_account_result->fetch_array(MYSQLI_ASSOC);
        $balance = $request_account['balance'];
        $balance -= $amount;
        // Update account balance
        $mysqli->query("UPDATE $account SET balance = $balance WHERE client_number = $client");
        // Insert statement
        $mysqli->query("INSERT INTO statement (client_number, account_type, outcome, balance) VALUES ($client, '$account', $amount, $balance)");
        $mysqli->query("DELETE FROM request_table WHERE request_id = $request_id");
    }
    header("location: ./manage_request.php");
}

if(isset($_POST['deny'])){
    $request_id = $_POST['deny'];
    $mysqli->query("DELETE FROM request_table WHERE request_id = $request_id");
    header("location: ./manage_request.php");
}

?>
</body>
</html>