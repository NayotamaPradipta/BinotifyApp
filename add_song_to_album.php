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

$pdo->beginTransaction();

$stmt = $pdo->prepare('SELECT song_id, duration, album_id, judul, penyanyi FROM song WHERE song_id = :song_id');
$stmt->execute(['song_id' => $song_id]);
$song = $stmt->fetch();

if (!$song) {
    echo json_encode(['status' => 'error', 'message' => 'Song not found']);
    exit;
}

$song_duration = intval($song['duration']);
$current_album_id = intval($song['album_id']);

if ($current_album_id !== null) {
    $stmt = $pdo->prepare('UPDATE album SET total_duration = total_duration - :song_duration WHERE album_id = :album_id');
    $stmt->execute(['song_duration' => $song_duration, 'album_id' => $current_album_id]);
}

$stmt = $pdo->prepare('UPDATE album SET total_duration = total_duration + :song_duration WHERE album_id = :album_id');
$stmt->execute(['song_duration' => $song_duration, 'album_id' => $album_id]);

// Update the song's album_id
$stmt = $pdo->prepare('UPDATE song SET album_id = :album_id WHERE song_id = :song_id');
$result = $stmt->execute(['album_id' => $album_id, 'song_id' => $song_id]);

if ($result) {
    $pdo->commit();

    $stmt = $pdo->prepare('SELECT total_duration FROM album WHERE album_id = :album_id');
    $stmt->execute(['album_id' => $album_id]);
    $album = $stmt->fetch();

    if ($album) {
        echo json_encode([
            'status' => 'success',
            'song' => [
                'song_id' => $song['song_id'],
                'judul' => $song['judul'],
                'penyanyi' => $song['penyanyi']
            ],
            'new_album_total_duration' => $album['total_duration']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Song not found']);
    }
} else {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Failed to add song to album']);
}

exit;