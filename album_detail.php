<?php 
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    if (!isset($_GET['id'])) { 
        echo 'No album ID provided';
        exit;
    }

    $id = intval($_GET['id']);
    $stmt = $pdo->prepare('SELECT * FROM album WHERE album_id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if (!$row){ 
        echo 'Album not found';
        exit;
    }

    // Fetch songs 

    $stmt = $pdo->prepare('SELECT * FROM song WHERE album_id = :id');
    $stmt->execute(['id' => $id]);
    $songs = $stmt->fetchAll();

    if (!$songs) { 
        echo 'Songs not found';
        exit;
    }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['judul']); ?></title>
    <linK rel="stylesheet" href="public/css/album_detail.css" type="text/css">
</head>

<body>
    <?php 
        include 'navbar.php';
    ?>
    <div class="album-detail-wrapper">
        <div class="album-detail">
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Album cover">
            <div>
                <h2><?php echo htmlspecialchars($row['judul']); ?></h2>
                <div class="album-details">
                    <span>ALBUM BY <?php echo htmlspecialchars($row['penyanyi']); ?></span>
                </div>
            </div>
            <h2>Songs</h2>
            <div class="song-container">
                <?php foreach ($songs as $song): ?>
                    <div class="song">
                        <a href="song_detail.php?id=<?php echo htmlspecialchars($song['song_id']); ?>">
                            <div>
                                <span class="song-title"><?php echo htmlspecialchars($song['judul']); ?></span>
                                <div class="song-details">
                                    <span><?php echo htmlspecialchars($song['penyanyi']); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</body>

</html>
