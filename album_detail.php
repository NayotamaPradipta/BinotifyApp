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
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['judul']); ?></title>
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
                    <span><?php echo htmlspecialchars($row['penyanyi']); ?></span>
                    <!-- TODO: 
                        1. Count total duration
                        2. List of Songs    
                    --> 
                </div>
            </div>
        </div>
    

    </div>
</body>

</html>
