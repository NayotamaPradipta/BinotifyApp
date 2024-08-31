<?php
    include "navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/navbar.css" type="text/css">
    <link rel="stylesheet" href="public/css/songs.css" type="text/css">
    <title>Binotify App</title>

</head>
<body>
    <div id="nav-container">

    </div>
    <div id="songs-container">
        <?php
            include "songs.php";
        ?>  
    </div>
</body>
</html>
