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
    <title>Request</title>
    <link rel="import" href="user_template.html">
    <link rel="stylesheet" type="text/css" href="styles/template.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <script>
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>
</head>
<body>
<div id="container">
    <script type="text/javascript" src="scripts/importTemplate.js"></script>

    <div class="formDiv">
        <h3>Welcome Account Holder <?php echo $_SESSION['user']; ?></h3>
        <table>
            <tr><th>Last Access: </th><td><?php echo $_SESSION['last_access']; ?></td></tr>
            <tr><td><br></td></tr>
        </table>
    </div>

    <div class="formDiv">
        <h3>Request for an account</h3>
        <form action="" method="post">
            <table>
                <tr>
                    <th></th>
                    <td><input type="text" placeholder="Select which account you want to apply" disabled></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <select id="account" name="account_type" required>
                            <option value="" disabled>Select an account type</option>
                            <?php if (!$savings_account) { ?>
                                <option id="savings" value="savings_account">Savings Account -- $50</option>
                            <?php } ?>
                            <?php if (!$business_account) { ?>
                                <option id="business" value="business_account">Business Account -- $100</option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" value="Send Request" name="send_request"
                               onclick="return confirm('Do you want to apply for the account? Request fees will be deducted from your account once approved.')"></td>
                </tr>
            </table>

        </form>
    </div>
</div>

<?php
if (isset($_POST['send_request'])) {
    $account_type = $_POST['account_type'];
    $result = $mysqli->query("SELECT * FROM `request_table` WHERE request_user_id = ".$_SESSION['user_id']." AND request_account = '$account_type' AND request_type = 1");
    if(!$result->fetch_array(MYSQLI_ASSOC)) {
        $mysqli->query("INSERT INTO `request_table` (request_user_id, request_account, reason) VALUES (".$_SESSION['user_id'].", '$account_type', 'open account')");
    }
    header("location: ./user_homepage.php");
}
?>
</body>
</html>