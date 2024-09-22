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
        echo 'No album ID provided'; 
        exit;
    }

    $album_id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT * FROM album WHERE album_id = :id');
    $stmt->execute(['id' => $album_id]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$album) { 
        echo 'Album not found'; 
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $singer = htmlspecialchars($_POST['singer']);
        $album_title = htmlspecialchars($_POST['album-title']);
        $genre = htmlspecialchars($_POST['genre']);
        $release_date = htmlspecialchars($_POST['release-date']);

        $target_cover_dir = "./public/music/visual/album/";

        $cover_file = !empty($_FILES["cover-upload"]["name"]) ? $target_cover_dir . basename($_FILES["cover-upload"]["name"]) : $album['image_path'];

        if (!is_dir($target_cover_dir)) {
            mkdir($target_cover_dir, 0777, true);
        }

        if (!empty($_FILES["cover-upload"]["tmp_name"])) {
            move_uploaded_file($_FILES["cover-upload"]["tmp_name"], $cover_file);
        }

        $stmt = $pdo->prepare('UPDATE album SET penyanyi = :penyanyi, judul = :judul, genre = :genre, tanggal_terbit = :tanggal_terbit, image_path = :image_path WHERE album_id = :album_id');
        if ($stmt->execute([
            ':penyanyi' => $singer,
            ':judul' => $album_title, 
            'genre' => $genre,
            'tanggal_terbit' => $release_date,
            'image_path' => $cover_file,
            'album_id' => $album_id 
        ])) {
            header('Location: album.php');
            exit();
        } else {
            echo "Error updating album in the database.";
        }
    }

    ob_end_flush();

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
            <form method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="album-title">Album Title</label>
                    <input type="text" id="album-title" name="album-title" value="<?php echo htmlspecialchars($album['judul']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="singer">Singer</label>
                    <input type="text" id="singer" name="singer" value="<?php echo htmlspecialchars($album['penyanyi']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="genre">Genre</label>
                    <input type="genre" id="genre" name="genre" value="<?php echo htmlspecialchars($album['genre']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="release-date">Release Date</label>
                    <input type="date" id="release-date" name="release-date" value="<?php echo htmlspecialchars($album['tanggal_terbit']); ?>" required>
                </div>
                <div class="input-group">
                    <label for="cover-upload">Upload New Cover</label>
                    <input type="file" id="cover-upload" name="cover-upload" accept="image/*"> 
                    <br>
                    <small>Current Cover: <img src="<?php echo htmlspecialchars($album['image_path']); ?>" alt="Cover" style="width: 50px; height: 50px;"></small> 
                </div>
                <div>
                    <button type="submit">Add Album</button>
                </div>
            </form>
        </div>
    </div>
</body>




</html>
