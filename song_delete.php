<?php 
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
        echo 'Unauthorized access';
        exit;
    }

    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    if (!isset($_POST['song_id'])) {
        echo 'No song ID provided'; 
        exit;
    }

    $id = intval($_POST['song_id']);

    $stmt = $pdo->prepare('SELECT album_id FROM song WHERE song_id = :id');
    $stmt->execute(['id' => $id]);
    $song = $stmt->fetch();

    if (!$song) {
        echo 'Song not found';
        exit;
    }

    $album_id = $song['album_id'];

    $stmt = $pdo->prepare('DELETE FROM song WHERE song_id = :id');
    $stmt->execute(['id' => $id]);

    $stmt = $pdo->prepare('SELECT SUM(duration) AS total_duration FROM song WHERE album_id = :album_id');
    $stmt->execute(['album_id' => $album_id]);
    $result = $stmt->fetch();

    $new_total_duration = $result['total_duration'] ?? 0;

    $stmt = $pdo->prepare('UPDATE album SET total_duration = :total_duration WHERE album_id = :album_id');
    $stmt->execute([
        'total_duration' => $new_total_duration,
        'album_id' => $album_id
    ]);

    header('Location: index.php');
    exit;

?>