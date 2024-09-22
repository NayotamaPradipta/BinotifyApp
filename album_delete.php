<?php
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
        echo 'Unauthorized access';
        exit;
    }

    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    if (!isset($_POST['album_id'])){ 
        echo 'No Album ID provided'; 
        exit;
    }

    $album_id = intval($_POST['album_id']);

    $stmt = $pdo->prepare('DELETE FROM album WHERE album_id = :album_id');
    $stmt->execute(['album_id' => $album_id]);

    header('Location: album.php');
    exit;
?>