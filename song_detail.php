<?php
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    if (!isset($_GET['id'])) {
        echo 'No song ID provided';
        exit;
    }

    $id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT * FROM song WHERE song_id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        echo 'Song not found';
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['judul']); ?></title>
    <link rel="stylesheet" href="public/css/song_detail.css" type="text/css">
</head>
<body>
    <?php 
        include 'navbar.php'; 
    ?>
    <div class="song-detail-wrapper">
        <div class="song-detail">
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Album cover">
            <div>
                <h2><?php echo htmlspecialchars($row['judul']); ?></h2>
                <div class="song-details">
                    <span><?php echo htmlspecialchars($row['penyanyi']); ?></span>
                    <span>&#8226;</span> 
                    <span><?php echo htmlspecialchars(substr($row['tanggal_terbit'], 0, 4)); ?></span>
                    <span>&#8226;</span> 
                    <span><?php echo htmlspecialchars($row['genre']); ?></span>
                </div>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($row['audio_path']); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
        </div>
    </div>
</body>
</html>
