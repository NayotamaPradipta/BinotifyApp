<?php
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    include 'navbar.php';   

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
    $album_id = $row['album_id'];
    $album_stmt = $pdo->prepare('SELECT judul FROM album WHERE album_id = :album_id');
    $album_stmt->execute(['album_id' => $album_id]);
    $album_row = $album_stmt->fetch();
    if (!$album_row){ 
        echo 'Album not found';
        exit;
    }
    $album_name = $album_row['judul'];

    // Logic to determine if audio should be disabled
    if (!$isLogged) {
        if (!isset($_SESSION['audio_play_count'])) {
            $_SESSION['audio_play_count'] = 0; 
        }

        if ($_SESSION['audio_play_count'] >= 3) {
            $audio_disabled = true; 
        } else {
            $audio_disabled = false; 
        }
    } else {
        $audio_disabled = false; 
    }
    $isAdmin = isset($_SESSION['username']) && $_SESSION['username'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['judul']); ?></title>
    <link rel="stylesheet" href="public/css/song_detail.css" type="text/css">
    <script src="src/scripts/play.js"></script>
</head>
<body>
    <div class="song-detail-wrapper">
        <div class="song-detail">
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Song cover">
            <div>
                <h2><?php echo htmlspecialchars($row['judul']); ?></h2>
                <div class="album-name">
                    <a href="album_detail.php?id=<?php echo htmlspecialchars($row['album_id']); ?>">
                        <?php echo htmlspecialchars($album_name); ?> 
                    </a>
                </div>
                <div class="song-details">
                    <span><?php echo htmlspecialchars($row['penyanyi']); ?></span>
                    <span>&#8226;</span> 
                    <span><?php echo htmlspecialchars(substr($row['tanggal_terbit'], 0, 4)); ?></span>
                    <span>&#8226;</span> 
                    <span><?php echo htmlspecialchars($row['genre']); ?></span>
                </div>
                <?php if ($audio_disabled): ?> 
                    <p>You have reached the maximum number of plays. Please log in to continue listening.</p>
                <?php else: ?>
                    <audio controls onplay="trackPlay(this)">
                        <source src="<?php echo htmlspecialchars($row['audio_path']); ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <div class="admin-action">
                        <a href="song_edit.php?id=<?php echo htmlspecialchars($row['song_id']); ?>" >
                            <button class="btn-edit">
                                <img src="./public/image/edit.png" alt="Edit Icon"/>
                                <span>Edit</span>
                            </button>
                        </a>
                        <form method="post" action="song_delete.php">
                            <input type="hidden" name="song_id" value="<?php echo htmlspecialchars($row['song_id']); ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this song?');" class="btn-delete">
                                <img src="./public/image/delete.png" alt="Delete Icon"/>
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                <?php endif; ?> 
            </div>

        </div>
    </div>
</body>
</html>
