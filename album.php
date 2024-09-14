<?php 
    include "navbar.php";
?>
<!DOCTYPE html>
<html lang='en'> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/navbar.css" type="text/css">
    <link rel="stylesheet" href="public/css/album.css" type="text/css">
    <title>Albums</title>
</head>
<body>
    <?php 
        ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
        $pdo = $connect_db();
        $stmt = $pdo->query('SELECT * FROM album ORDER BY judul ASC');

        while ($row = $stmt->fetch()){
            echo '<a href="album_detail.php?id=' . htmlspecialchars($row['album_id']) . '" class="album-link">';
                echo '<div class="album">';
                    echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Song Cover">';
                    echo '<div>';
                        echo '<h2>' . htmlspecialchars($row['judul']) . '</h2>';
                        echo '<div class="album-details">';
                            echo '<span>' . htmlspecialchars($row['penyanyi']) . '</span>';
                            echo '<span>&#8226;</span>';
                            echo '<span>' . htmlspecialchars(substr($row['tanggal_terbit'], 0, 4)) . '</span>';
                            echo '<span>&#8226;</span>';
                            echo '<span>' . htmlspecialchars($row['genre']) . '</span>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';  
            echo '</a>';
        }

    ?> 

</body>


</html>