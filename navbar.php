<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditional Navbar</title>
<head>
    <link rel="stylesheet" href="./public/css/navbar.css">
</head>

<?php
    $isLogged = false;
?>


<div id="navbar" class="container-navbar">
    <a href="index.php" class="logo-title"> 
        <img src="./public/favicon.ico" class="logo-img">
        <div id="binotify-title" class="text-title large-title">
            Binotify
        </div>
    </a> 
    <?php
        if (!$isLogged && basename($_SERVER['REQUEST_URI'])!="login.php"){
            echo '
                <div id="login-button">
                    <a href="login.php" class="login-button">
                        Login
                    </a>
                </div>
            ';
        }
    ?>
</div>


