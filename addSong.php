<?php
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
        echo 'Unauthorized access';
        exit;
    }
    include 'navbar.php';
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    require 'vendor/autoload.php';
    $pdo = $connect_db();
    $stmt = $pdo->query('SELECT judul FROM album');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $album = htmlspecialchars($_POST['album']);
        $song_title = htmlspecialchars($_POST['song-title']);
        $singer = htmlspecialchars($_POST['singer']);
        $release_date = htmlspecialchars($_POST['release-date']);
        $genre = htmlspecialchars($_POST['genre']);

        $target_audio_dir = "./public/music/audio/";
        $target_cover_dir = "./public/music/visual/song/";
        $audio_file = $target_audio_dir . basename($_FILES["audio-upload"]["name"]);
        $cover_file = $target_cover_dir . basename($_FILES["cover-upload"]["name"]);

        if (!is_dir($target_audio_dir)){
            mkdir($target_audio_dir, 0777, true);
        }
        if (!is_dir($target_cover_dir)){
            mkdir($target_cover_dir, 0777, true);
        }
        if (move_uploaded_file($_FILES["audio-upload"]["tmp_name"], $audio_file) &&
            move_uploaded_file($_FILES["cover-upload"]["tmp_name"], $cover_file)) {
            
            $stmt_album = $pdo->prepare('SELECT album_id, total_duration FROM album WHERE judul = :judul');
            $stmt_album->execute([':judul' => $album]);
            $album_row = $stmt_album->fetch(PDO::FETCH_ASSOC);
            $album_id = $album_row['album_id'];
            $current_total_duration = $album_row['total_duration'];

            $getID3 = new getID3;
            $file_info = $getID3->analyze($audio_file);
            $duration = isset($file_info['playtime_seconds']) ? round($file_info['playtime_seconds']) : 0;

            $stmt = $pdo->prepare('INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id) 
            VALUES (:judul, :penyanyi, :tanggal_terbit, :genre, :duration, :audio_path, :image_path, :album_id)');

            if ($stmt->execute([
                ':judul' => $song_title,
                ':penyanyi' => $singer,
                ':tanggal_terbit' => $release_date,
                ':genre' => $genre,
                ':duration' => $duration,
                ':audio_path' => $audio_file,
                ':image_path' => $cover_file,
                ':album_id' => $album_id
            ])) {
                $new_total_duration = $current_total_duration + $duration;

                $update_album_stmt = $pdo->prepare('UPDATE album SET total_duration = :new_total_duration WHERE album_id = :album_id');
                $update_album_stmt->execute([
                    ':new_total_duration' => $new_total_duration,
                    ':album_id' => $album_id
                ]);
            } else {
                echo "Error adding song to the database.";
            }
        } else {
            echo "Error uploading files.";
        }
    }
?>

<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <title>Add New Song</title>
    <link rel="stylesheet" href="public/css/addSong.css" type="text/css">
</head>

<body>
    <div class="add-song-container">
        <div class="add-song-form">
            <h2>Add Song</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="album">Album</label>
                    <select id="album" name="album" required> 
                        <option value="">Select an album</option>
                        <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo '<option value="' . htmlspecialchars($row['judul']) . '">' . htmlspecialchars($row['judul']) . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="song-title">Song Title</label>
                    <input type="text" id="song-title" name="song-title" required>
                </div>
                <div class="input-group">
                    <label for="singer">Singer</label>
                    <input type="text" id="singer" name="singer" required>
                </div>
                <div class="input-group">
                    <label for="release-date">Release Date</label>
                    <input type="date" id="release-date" name="release-date" required>
                </div>
                <div class="input-group">
                    <label for="genre">Genre</label>
                    <input type="genre" id="genre" name="genre" required>
                </div>
                <div class="input-group">
                    <label for="audio-upload">Upload Audio</label>
                    <input type="file" id="audio-upload" name="audio-upload" accept="audio/*" required>
                </div>
                <div class="input-group">
                    <label for="cover-upload">Upload Cover</label>
                    <input type="file" id="cover-upload" name="cover-upload" accept="image/*" required>
                </div>
                <div>
                    <button type="submit">Add Song</button>
                </div>
            </form>

        </div>
    </div>

</body>



</html>

