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
    <title>Inter Transfer</title>
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
        <table>
            <?php
            if ($savings_account) { ?>
                <tr>
                    <th colspan="2">Your Savings Account</th>
                </tr>
                <tr>
                    <th>Account number: </th>
                    <td><?php echo $savings_account['savings_account_id'] ?></td>
                </tr>
                <tr>
                    <th>Account balance: </th>
                    <td><?php echo $savings_account['balance'] ?></td>
                </tr>
            <?php } ?>
            <tr><td><br></td></tr>
            <?php if ($business_account) { ?>
                <tr>
                    <th colspan="2">Your Business Account</th>
                </tr>
                <tr>
                    <th>Account number: </th>
                    <td><?php echo $business_account['business_account_id'] ?></td>
                </tr>
                <tr>
                    <th>Account balance: </th>
                    <td><?php echo $business_account['balance'] ?></td>
                </tr>
            <?php } ?>

        </table>
    </div>
    <div class="formDiv">
        <h3>Inter Transfer</h3>
            <?php if (!$business_account && !$savings_account) { ?>
                <span>You need at least 1 account to process your payment.</span>
                Send a <a href="request_form.php"><b>REQUEST</b></a> to apply for an account.
            <?php exit(); } ?>
        <form action="transaction_process.php" method="post">
            <table>
                <tr>
                    <th>From</th>
                    <td><input type="number" placeholder="DEFAULT BSB - 666666" disabled></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <select id="account" name="account_type" required>
                            <option value="" disabled>Select an account type</option>
                            <?php if ($savings_account) { ?>
                            <option id="savings" value="savings_account">Savings Account -- $<?php echo $savings_account['balance']; ?></option>
                            <?php } ?>
                            <?php if ($business_account) { ?>
                            <option id="business" value="business_account">Business Account -- $<?php echo $business_account['balance']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>To</th>
                    <td><input type="number" name="bsb" placeholder="BSB Number" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="text" name="bank_name" placeholder="Bank Name" required></td>
                </tr>
                <tr><td><br></td></tr>
                <tr>
                    <th>Amount</th>
                    <td><input type="number" name="amount" placeholder="Amount" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <select id="currency" name="currency" required>
                            <option value="" disabled>Select Currency</option>
                            <option id="aud" value="1.00">$AUD</option>
                            <option id="usd" value="1.44">$USD</option>
                            <option id="gbp" value="1.85">$GBP</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Purpose</th>
                    <td><input type="text" name="purpose" placeholder="Transaction purpose" required></td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Confirm" name="confirm_inter"></td>
                </tr>
            </table>

        </form>
    </div>
</div>
<script>
    // control disability on select changing
    $("#account").change(function () {
        if($(this).val() == "savings_account"){
            $("option#aud").prop("selected", true);
            $("option#usd").prop("disabled", true);
            $("option#gbp").prop("disabled", true);
        } else {
            $("option#usd").prop("disabled", false);
            $("option#gbp").prop("disabled", false);
        }
    }).trigger("change");
</script>
</body>
</html>