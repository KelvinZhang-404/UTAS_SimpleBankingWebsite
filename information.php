<?php
include 'session.php';
include 'logout.php';
if($_SESSION['access_type'] != 'general_user'){
    header('location: ./index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Information</title>
    <link rel="import" href="user_template.html">
    <link rel="stylesheet" type="text/css" href="styles/template.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
</head>
<body>
<div id="container">
    <script type="text/javascript" src="scripts/importTemplate.js"></script>

    <div class="infoDiv">
        <video width="600" controls>
            <source src="./images/demo.mov" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>

        <p>
            Short video tutorial of HOW TO USE THIS SITE.
        </p>
    </div>
</div>

</body>
</html>