<?php
    include "pagination.php";
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    $page = isset($_GET['page']) ? (int)($_GET['page']) : 1;

    $pagination = paginate($pdo, 'song', 'judul ASC', $page);
    $songs = $pagination['items'];
    $total_pages = $pagination['total_pages'];
    $current_page = $pagination['current_page'];
    foreach ($songs as $row){
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
    echo '<div class="pagination">';

    $start_page = max(1, $current_page - 1); 
    $end_page = min($total_pages, $current_page + 1);

    if ($current_page == 1) {
        $end_page = min($total_pages, 3);
    } elseif ($current_page == $total_pages) {
        $start_page = max(1, $total_pages - 2); 
    }

    for ($i = $start_page; $i <= $end_page; $i++) {
        echo '<a href="?page=' . $i . '"';
        if ($i == $current_page) {
            echo ' class="active"';
        }
        echo '>' . $i . '</a>';
    }
    
    echo '</div>';
?>