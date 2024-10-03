<?php 
    include "navbar.php";
    include "pagination.php";
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

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $pagination = paginate($pdo, 'album', 'judul ASC', $page);
        $albums = $pagination['items'];
        $total_pages = $pagination['total_pages'];
        $current_page = $pagination['current_page'];

        foreach ($albums as $row){
            echo '<a href="album_detail.php?id=' . htmlspecialchars($row['album_id']) . '" class="album-link">';
                echo '<div class="album">';
                    echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Album Cover">';
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
    <div class="pagination">
        <?php 
        $start_page = max(1, $current_page - 1); 
        $end_page = min($total_pages, $current_page + 1);

        if ($current_page == 1) {
            $end_page = min($total_pages, 3);
        } elseif ($current_page == $total_pages) {
            $start_page = max(1, $total_pages - 2); 
        }

        for ($i = $start_page; $i <= $end_page; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $current_page) echo 'class="active"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</body>


</html>