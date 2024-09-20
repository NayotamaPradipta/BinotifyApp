<?php 
    include 'navbar.php';
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db(); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Album</title>
    <link rel="stylesheet" href="public/css/addAlbum.css" type="text/css">
</head>

<body>
    <div class="add-album-container">
        <div class="add-album-form">
            <h2>Add Album</h2>
            <form method="post">
                <div class="input-group">
                    <label for="album-title">Album Title</label>
                    <input type="text" id="album-title" name="album-title" required>
                </div>
                <div class="input-group">
                    <label for="singer">Singer</label>
                    <input type="text" id="singer" name="singer" required>
                </div>
                <div class="input-group">
                    <label for="genre">Genre</label>
                    <input type="genre" id="genre" name="genre" required>
                </div>
                <div class="input-group">
                    <label for="release-date">Release Date</label>
                    <input type="date" id="release-date" name="release-date" required>
                </div>
                <div class="input-group">
                    <label for="upload-cover">Upload Cover</label>
                    <input type="file" id="album-cover" name="album-cover" required> 
                </div>
                <div>
                    <button type="submit">Add Album</button>
                </div>
            </form>
        </div>
    </div>
</body>




</html>