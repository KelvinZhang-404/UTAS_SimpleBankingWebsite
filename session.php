<?php
session_start();

if(!isset($_SESSION['user'])){
    $_SESSION['user'] = '';
}

if(!isset($_SESSION['access_type'])){
    $_SESSION['access_type'] = '';
}

if(!isset($_SESSION['user_id'])){
    $_SESSION['user_id'] = 0;
}

if (!isset($_SESSION['last_access'])){
    $_SESSION['last_access'] = '';
}

