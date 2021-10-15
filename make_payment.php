<?php
include 'session.php';
include 'logout.php';

if($_SESSION['access_type'] != 'general_user'){
    header('location: ./index.php');
}

// display any error messages
if(isset($_GET['error'])){
    $error_message = $_GET['error'];
    echo "<script>alert('$error_message');</script>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
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
        <form action="./inter_transfer.php">
            <input type="submit" value="Inter Transfer">
        </form>
        <form action="./intra_transfer.php">
            <input type="submit" value="Intra Transfer">
        </form>
        <form action="./bill_payment.php">
            <input type="submit" value="Bill Payment">
        </form>
    </div>
    <div class="infoDiv">
        <?php if (!isset($_SESSION['transaction'])) { ?>
        <?php } else {?>
            <h3>Payment Successful</h3>
            <table>
                <?php // display transaction details after a successful transaction
                foreach ($_SESSION['transaction'] as $key => $value){
                    echo "<tr><th>$key</th><td>$value</td></tr>";
                }
                ?>
            </table>
        <?php unset($_SESSION['transaction']); // destroy the transaction details.
        }?>
    </div>
</div>

</body>
</html>