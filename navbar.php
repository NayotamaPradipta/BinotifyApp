<?php
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
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
        if ($isLogged && $_SESSION['username'] == 'admin') {
            echo '
                <div class="user-list link-item">
                    <a href="./users.php" class="user-list-button">
                        <img src="./public/image/user.png" alt="Add User" class="navbar-img"/>
                        <span id="icon-text">Users</span>
                    </a>
                </div>
            ';
        }
        $requestUri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        if ($requestUri == "index.php"|| $requestUri == "" || $requestUri == "search.php"){
            echo '
                <div id="search-bar"> 
                    <form action="./search.php" method="GET" id="search-form">
                        <input type="text" name="query" id="searchInput" placeholder="Search for songs...">
                        <button id="search-button" type="submit"><img src="./public/image/search.png"/></button>
                    </form>
                </div>
                <div class="album-list link-item">
                    <a href="./album.php" class="album-list">
                        <img src="./public/image/album.png" alt="Album List" class="navbar-img"/>  
                        <span id="icon-text">Album</span>
                    </a>
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
            if ($_SESSION['username'] == 'admin') {
                echo
                '
                <div class="add-song link-item">
                    <a href="addSong.php" class="add-song-button">
                        <img src="./public/image/addSong.png" alt="Add Song" class="navbar-img"/>
                        <span id="icon-text">Add Song</span>
                    </a>
                </div>
                <div class="add-album link-item">
                    <a href="addAlbum.php" class="add-album-button">
                        <img src="./public/image/addAlbum.png" alt="Add Album" class="navbar-img"/>
                        <span id="icon-text">Add Album</span>
                    </a>
                </div>

                ';
            }
            echo 
            '<div class="user-logout">
                <p class="user-name" id="user-text"> Hello, ' . htmlspecialchars($_SESSION['username']) .  '!</p>
                <form action="logout.php" method="post" class="logout-form">
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>';

        }
    ?>
</div>


