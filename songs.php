<?php
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    $stmt = $pdo->query('SELECT * FROM song ORDER BY judul ASC');

    while ($row = $stmt->fetch()){
        echo '<div class="song">';
        echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Album cover">';
        echo '<h2>' . htmlspecialchars($row['judul']) . '</h2>';
        echo '<p>Singer: ' . htmlspecialchars($row['penyanyi']) . '</p>';
        echo '<p>Year Published: ' . htmlspecialchars($row['tanggal_terbit']) . '</p>';
        echo '<p>Genre: ' . htmlspecialchars($row['genre']) . '</p>';
        echo '</div>';
    }
?>