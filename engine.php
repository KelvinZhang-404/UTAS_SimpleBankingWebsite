<?php

include "session.php";

if($_SESSION['access_type'] == 'bank_manager'){
    header("location: ./manager_homepage.php");
} elseif($_SESSION['access_type'] == 'general_user'){
    header("location: ./user_homepage.php");
}

echo "if you can see this page, it means there is an error.";