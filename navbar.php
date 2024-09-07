<?php
    session_start();
    $isLogged = false;
    if (isset($_SESSION['username'])){
        $isLogged = true;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/navbar.css">
</head>


<div id="navbar" class="container-navbar">
    <a href="index.php" class="logo-title"> 
        <img src="./public/favicon.ico" class="logo-img">
        <div id="binotify-title" class="text-title large-title">
            Binotify
        </div>
    </a> 
    <?php
        $requestUri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if ($requestUri == "index.php"|| $requestUri == "" || $requestUri == "search.php"){
            echo '
                <div id="search-bar"> 
                    <form action="./search.php" method="GET">
                        <input type="text" name="query" id="searchInput" placeholder="Search for songs...">
                        <button type="submit">Search</button>
                    </form>
                </div>
            ';
        }
        if (!$isLogged && $requestUri != "login.php"){
            echo '
                <div id="login-button">
                    <a href="login.php" class="login-button">
                        Login
                    </a>
                </div>
            ';
        }
        if ($isLogged){
            echo 
            '<div class="user-info">
                <p class="user-name"> Hello, ' . htmlspecialchars($_SESSION['username']) .  '!</p>
                <form action="logout.php" method="post" class="logout-form">
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>';

        }
    ?>
</div>


