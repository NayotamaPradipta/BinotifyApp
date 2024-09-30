<?php
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
        echo 'Unauthorized access';
        exit;
    }
    ob_start();
    include 'navbar.php';
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    require 'vendor/autoload.php';
    $pdo = $connect_db();

    if (!isset($_GET['id'])) {
        echo 'No song ID provided';
        exit;
    }

    $song_id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT * FROM song WHERE song_id = :id');
    $stmt->execute(['id' => $song_id]);
    $song = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$song) {
        echo 'Song not found';
        exit;
    }

    $stmt_album = $pdo->query('SELECT album_id, judul FROM album');
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $album_id = htmlspecialchars($_POST['album']);
        $song_title = htmlspecialchars($_POST['song-title']);
        $singer = htmlspecialchars($_POST['singer']);
        $release_date = htmlspecialchars($_POST['release-date']);
        $genre = htmlspecialchars($_POST['genre']);

        $target_audio_dir = "./public/music/audio/";
        $target_cover_dir = "./public/music/visual/song/";

        $audio_file = !empty($_FILES["audio-upload"]["name"]) ? $target_audio_dir . basename($_FILES["audio-upload"]["name"]) : $song['audio_path'];
        $cover_file = !empty($_FILES["cover-upload"]["name"]) ? $target_cover_dir . basename($_FILES["cover-upload"]["name"]) : $song['image_path'];

        $allowed_audio_types = ['audio/mpeg', 'audio/wav', 'audio/mp3'];
        $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!is_dir($target_audio_dir)) {
            mkdir($target_audio_dir, 0777, true);
        }
        if (!is_dir($target_cover_dir)) {
            mkdir($target_cover_dir, 0777, true);
        }

        if (!empty($_FILES["audio-upload"]["tmp_name"])) {
            $audio_mime_type = mime_content_type($_FILES["audio-upload"]["tmp_name"]);
            if (!in_array($audio_mime_type, $allowed_audio_types)) {
                $error_message .= "Invalid audio file type. Only MP3, WAV, and MPEG files are allowed.\n";
            }
        }

        if (!empty($_FILES["cover-upload"]["tmp_name"])) {
            $cover_mime_type = mime_content_type($_FILES["cover-upload"]["tmp_name"]);
            if (!in_array($cover_mime_type, $allowed_image_types)) {
                $error_message .= "Invalid image file type. Only JPEG, PNG, GIF, and WebP files are allowed.\n";
            }
        }
        if (empty($error_message)) {
            if (!empty($_FILES["audio-upload"]["tmp_name"])) {
                move_uploaded_file($_FILES["audio-upload"]["tmp_name"], $audio_file);
            }
            if (!empty($_FILES["cover-upload"]["tmp_name"])) {
                move_uploaded_file($_FILES["cover-upload"]["tmp_name"], $cover_file);
            }
    
            $duration = $song['duration']; 
            if (!empty($_FILES["audio-upload"]["tmp_name"])) {
                $getID3 = new getID3;
                $file_info = $getID3->analyze($audio_file);
                $duration = isset($file_info['playtime_seconds']) ? round($file_info['playtime_seconds']) : 0;
            }
    
            $stmt = $pdo->prepare('UPDATE song SET judul = :judul, penyanyi = :penyanyi, tanggal_terbit = :tanggal_terbit, genre = :genre, duration = :duration, audio_path = :audio_path, image_path = :image_path, album_id = :album_id WHERE song_id = :song_id');
        
            if ($stmt->execute([
                ':judul' => $song_title,
                ':penyanyi' => $singer,
                ':tanggal_terbit' => $release_date,
                ':genre' => $genre,
                ':duration' => $duration,
                ':audio_path' => $audio_file,
                ':image_path' => $cover_file,
                ':album_id' => $album_id, 
                ':song_id' => $song_id
            ])) {
                $stmt_total_duration = $pdo->prepare('SELECT SUM(duration) AS total_duration FROM song WHERE album_id = :album_id');
                $stmt_total_duration->execute(['album_id' => $album_id]);
                $result = $stmt_total_duration->fetch(PDO::FETCH_ASSOC);
                $new_total_duration = $result['total_duration'] ?? 0;
    
                $stmt_update_album = $pdo->prepare('UPDATE album SET total_duration = :total_duration WHERE album_id = :album_id');
                $stmt_update_album->execute([
                    ':total_duration' => $new_total_duration,
                    ':album_id' => $album_id
                ]);
    
                header('Location: index.php');
                exit();
            } else {
                echo "Error updating song in the database.";
            }
        }

    }
    
    ob_end_flush();
?>

<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <title>Edit Song</title>
    <link rel="stylesheet" href="public/css/addSong.css" type="text/css">
    <script src="./src/scripts/dateUtil.js"></script>
</head>

<body>
    <div class="add-song-container">
        <div class="add-song-form">
            <h2>Edit Song</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="album">Album</label>
                    <select id="album" name="album" required> 
                        <option value="">Select an album</option>
                        <?php
                            while ($row = $stmt_album->fetch(PDO::FETCH_ASSOC)){
                                $selected = $song['album_id'] == $row['album_id'] ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row['album_id']) . '" ' . $selected . '>' . htmlspecialchars($row['judul']) . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="song-title">Song Title</label>
                    <input type="text" id="song-title" name="song-title" value="<?php echo htmlspecialchars($song['judul']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="singer">Singer</label>
                    <input type="text" id="singer" name="singer" value="<?php echo htmlspecialchars($song['penyanyi']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="release-date">Release Date</label>
                    <input type="date" id="release-date" name="release-date" value="<?php echo htmlspecialchars($song['tanggal_terbit']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($song['genre']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="audio-upload">Upload New Audio</label>
                    <div class="custom-cover-upload">
                        <input type="file" id="audio-upload" name="audio-upload" accept="audio/*">
                    </div>
                    <small>Current Audio: <?php echo htmlspecialchars(basename($song['audio_path'])); ?></small>
                </div>
                <div class="input-group">
                    <label for="cover-upload">Upload New Cover</label>
                    <input type="file" id="cover-upload" name="cover-upload" accept="image/*">
                    <br>        
                    <small>Current Cover: <img src="<?php echo htmlspecialchars($song['image_path']); ?>" alt="Cover" style="width: 50px; height: 50px;"></small>
                </div>
                <div>
                    <button type="submit">Update Song</button>
                </div>
            </form>
        </div>
    </div>
    <?php if (!empty($error_message)): ?>
        <script>
            alert("<?php echo addslashes(str_replace(array("\r", "\n"), '', htmlspecialchars($error_message))); ?>");
        </script>
    <?php endif; ?>
</body>
</html>
