<?php
    include 'navbar.php';
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();
    $stmt = $pdo->query('SELECT judul FROM album');

?>

<!DOCTYPE html> 
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <title>Add New Song</title>
    <link rel="stylesheet" href="public/css/addSong.css" type="text/css">
</head>

<body>
    <div class="add-song-container">
        <div class="add-song-form">
            <h2>Add Song</h2>
            <form method="post">
                <div class="input-group">
                    <label for="album">Album</label>
                    <select id="album" name="album" required> 
                        <option value="">Select an album</option>
                        <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                echo '<option value="' . htmlspecialchars($row['judul']) . '">' . htmlspecialchars($row['judul']) . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <label for="song-title">Song Title</label>
                    <input type="text" id="song-title" name="song-title" required>
                </div>
                <div class="input-group">
                    <label for="singer">Singer</label>
                    <input type="text" id="singer" name="singer" required>
                </div>
                <div class="input-group">
                    <label for="release-date">Release Date</label>
                    <input type="date" id="release-date" name="release-date" required>
                </div>
                <div class="input-group">
                    <label for="genre">Genre</label>
                    <input type="genre" id="genre" name="genre" required>
                </div>
                <div class="input-group">
                    <label for="audio-upload">Upload Audio</label>
                    <div class="custom-file-upload">
                        <input type="file" id="audio-upload" name="audio-upload" required>
                    </div>
                    
                </div>
                <div class="input-group">
                    <label for="cover-upload">Upload Cover</label>
                    <input type="file" id="file-upload" name="file-upload" required>
                </div>
                <div>
                    <button type="submit">Add Song</button>
                </div>
            </form>

        </div>
    </div>

</body>



</html>

