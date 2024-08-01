<?php
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    $stmt = $pdo->query('SELECT * FROM song ORDER BY judul ASC');

    while ($row = $stmt->fetch()){
        echo '<div class="song">';
            echo '<a href="song_detail.php?id=' . htmlspecialchars($row['song_id']) . '">';
            echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Album cover">';
            echo '<div>';
                echo '<h2>' . htmlspecialchars($row['judul']) . '</h2>';
                    echo '<div class="song-details">';
                        echo '<span>' . htmlspecialchars($row['penyanyi']) . '</span>';
                        echo '<span>&#8226;</span>'; 
                        echo '<span>' . htmlspecialchars(substr($row['tanggal_terbit'], 0, 4)) . '</span>';
                        echo '<span>&#8226;</span>'; 
                        echo '<span>' . htmlspecialchars($row['genre']) . '</span>';
                    echo '</div>';
            echo '</div>';
        echo '</div>';
    }
?>