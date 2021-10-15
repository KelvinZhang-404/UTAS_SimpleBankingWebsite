<?php
// destroy whole session and head to index.php
if(isset($_GET['logout'])){
    session_destroy();
    header("location: ./index.php");
}
