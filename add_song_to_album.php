<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized Access']);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['song_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit;
}

$album_id = intval($_POST['id']);
$song_id = intval($_POST['song_id']);

['connect_db' => $connect_db] = require('./src/db/db_connect.php');
$pdo = $connect_db();

$stmt = $pdo->prepare('UPDATE song SET album_id = :album_id WHERE song_id = :song_id');
$result = $stmt->execute(['album_id' => $album_id, 'song_id' => $song_id]);

if ($result) {
    $stmt = $pdo->prepare('SELECT * FROM song WHERE song_id = :song_id');
    $stmt->execute(['song_id' => $song_id]);
    $song = $stmt->fetch();

    if ($song) {
        echo json_encode([
            'status' => 'success',
            'song' => [
                'song_id' => $song['song_id'],
                'judul' => $song['judul'],
                'penyanyi' => $song['penyanyi']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Song not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add song to album']);
}

exit;
