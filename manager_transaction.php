<?php
include 'session.php';
include 'logout.php';
include 'db_conn.php';
if($_SESSION['access_type'] != 'bank_manager'){
    header('location: ./index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Transaction</title>
    <link rel="import" href="manager_template.html">
    <link rel="stylesheet" type="text/css" href="styles/template.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
</head>
<body>
<div id="container">
    <script type="text/javascript" src="scripts/importTemplate.js"></script>

    <div class="formDiv">
        <h3>Welcome Manager <?php echo $_SESSION['user']; ?></h3>
        <form action="" method="post">
            <select id="" name="period" required>
                <option value="" disabled>Select Statement Period</option>
                <option id="" value="1">1 Day</option>
                <option id="" value="7">1 Week</option>
                <option id="" value="30">1 Month</option>
                <option id="" value="90">3 Months</option>
            </select>
            <input type="submit" name="filter" value="View Transaction">
        </form>
    </div>

    <div class="infoDiv">
        <?php if(!isset($_POST['filter'])){?>

        <?php } else {
            // select specific timestamp of a given period
            $period = $_POST['period'];
            $date = date('Y-m-d h:i:s',  strtotime("-$period day"));

            // query for statement selection for a given period
            $statement_result = $mysqli->query("SELECT * FROM statement WHERE transaction_date > '$date' ORDER BY transaction_date DESC"); ?>
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