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
    <title>Account Management</title>
    <link rel="import" href="manager_template.html">
    <link rel="stylesheet" type="text/css" href="styles/template.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">

</head>
<body>
<div id="container">
    <script type="text/javascript" src="scripts/importTemplate.js"></script>
    <div class="infoDiv">
        <h3>Account Management</h3>
        <form id="btnForm" action="manage_account_engine.php" method="post">
            <table>
                <tr>
                    <th>Client Number</th><th>Username</th><th>Savings Account</th><th>Business Account</th><th>Access Level</th>
                </tr>

                <?php
                $result = $mysqli->query("SELECT client_number, firstname, savings_account, business_account, access_level FROM general_user");
                while($row = $result->fetch_array(MYSQLI_ASSOC)){
                    echo "<tr>";
                    foreach ($row as $key => $value){
                        if($key == "savings_account" || $key == "business_account"){
                            $button = ($value == NULL) ? "add ".$row['client_number'] : "remove ".$row['client_number'];
                            echo "<td><input name='$key' value='$button' type='submit'></td>";
                        } elseif($key == "access_level"){ ?>
                            <td>
                                <form action="manage_account_engine.php" method="post">
                                    <select id="access_selector" name="access_selector" onchange="">
                                        <?php if($value == "general_access") { ?>
                                            <option value="general_access">General Access</option>
                                            <option value="manager_access">Manager Access</option>
                                        <?php } else { ?>
                                            <option value="manager_access">Manager Access</option>
                                            <option value="general_access">General Access</option>
                                        <?php } ?>
                                    </select>
                                    <input type="submit" value="Confirm <?php echo $row['client_number']; ?>" name="confirm">
                                </form>
                            </td>
                        <?php }else{
                            echo "<td>$value</td>";
                        }
                    }
                    echo "</tr>";
                }
                $mysqli->close();
                ?>
            </table>
        </form>
    </div>
</div>
</body>
</html>