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
    $genre_filter = $_GET['genre'] ?? '';
    $sort_by = $_GET['sort_by'] ?? 'judul';
    $order_by = $_GET['order_by'] ?? 'ASC';

    $search_term = "%" . strtolower($search_query) . "%";
    $stmt = $pdo->prepare("
        SELECT * FROM song 
        WHERE (LOWER(judul) LIKE :search_term OR LOWER(penyanyi) LIKE :search_term)
        AND (:genre = '' OR genre = :genre)
        ORDER BY $sort_by $order_by"
        );
    $stmt->bindParam(':search_term', $search_term);
    $stmt->bindParam(':genre', $genre_filter);
    $stmt->execute(); 
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h2 id='search-text'>Search Results for '" . htmlspecialchars($search_query) . "'</h2>";
?> 

<div id="search-options">
    <form action="./search.php" method="GET">
        <input type="hidden" name="query" value="<?php echo htmlspecialchars($search_query); ?>">
        <select name="genre">
            <option value="">All Genres</option>
            <option value="Phonk" <?php if ($genre_filter == 'Phonk') echo 'selected'; ?>>Phonk</option>
            <option value="Electronic" <?php if ($genre_filter == 'Electronic') echo 'selected'; ?>>Electronic</option>
        </select>
        <select name="sort_by">
            <option value="judul" <?php if ($sort_by == 'judul') echo 'selected'; ?>>Sort by Title</option>
            <option value="tanggal_terbit" <?php if ($sort_by == 'tanggal_terbit') echo 'selected'; ?>>Sort by Year</option>
        </select>
        <select name="order_by">
            <option value="ASC" <?php if ($order_by == 'ASC') echo 'selected'; ?>>Ascending</option>
            <option value="DESC" <?php if ($order_by == 'DESC') echo 'selected'; ?>>Descending</option>
        </select>
        <button type="submit">Apply Filters</button>
    </form>
</div>

<?php 
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