<?php 
    session_start(); 

    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') { 
        echo 'Unauthorized access';
        exit;
    }

    if (!isset($_GET['id'])){
        echo 'No album id provided';
        exit;
    }

    $album_id = intval($_GET['id']);

    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    $stmt = $pdo->prepare('SELECT * FROM song WHERE album_id != :album_id');
    $stmt->execute(['album_id' => $album_id]);
    $songs = $stmt->fetchAll();

    if (!$songs){ 
        echo '<h3>No songs available</h3>';
    } else {
        echo '<div class="song-to-add-container">';
        echo '<h2>Available Songs</h2><br>';
        foreach ($songs as $song) {
            echo '<div class="song-to-add" id="song-to-add-' . htmlspecialchars($song['song_id']) . '">';
            echo '<span class="song-title">' . htmlspecialchars($song['judul']) . '</span>';
            echo '<span>&#8226;</span>';
            echo '<span class="singer">' . htmlspecialchars($song['penyanyi']) . '</span>';
            echo '<button class="add-song-to-album-button" data-song-id="' . htmlspecialchars($song['song_id']) . '">Add</button>';
            echo '</div>';
        }
        echo '</div>';
    }
?>