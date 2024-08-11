<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="public/css/songs.css" type="text/css">
</head>

<body>
<?php 
    include 'navbar.php';
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();

    $search_query = $_GET['query'];
    
    $search_term = "%" . strtolower($search_query) . "%";
    $stmt = $pdo->prepare("SELECT * FROM song WHERE LOWER(judul) LIKE :search_term OR LOWER(penyanyi) LIKE :search_term");
    $stmt->bindParam(':search_term', $search_term);
    $stmt->execute(); 
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h2>Search Results for '" . htmlspecialchars($search_query) . "'</h2>";
    if ($result) { 
        foreach($result as $row){
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
    } else { 
        echo "<p> No results found :(</p>";
    }

?> 
</body>
</html>