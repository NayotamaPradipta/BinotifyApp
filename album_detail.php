<?php 
    ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
    $pdo = $connect_db();
    include 'navbar.php';
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

    // Fetch songs 

    $stmt = $pdo->prepare('SELECT * FROM song WHERE album_id = :id');
    $stmt->execute(['id' => $id]);
    $songs = $stmt->fetchAll();


    function formatDuration($seconds){ 
        $hours = floor($seconds / 3600);
        $minutes = floor (($seconds % 3600)/60);
        $seconds = $seconds % 60; 
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
    
    $isAdmin = isset($_SESSION['username']) && $_SESSION['username'] === 'admin';

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($row['judul']); ?></title>
    <linK rel="stylesheet" href="public/css/album_detail.css" type="text/css">
</head>

<body>
    <div class="album-detail-wrapper">
        <div class="album-detail">
            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Album cover">
            <div>
                <h2><?php echo htmlspecialchars($row['judul']); ?></h2>
                <div class="album-details">
                    <span><?php echo htmlspecialchars($row['penyanyi']); ?></span>
                    <span>&#8226;</span>
                    <span><?php echo formatDuration($row['total_duration']); ?></span>
                </div>
                
            </div>
            <h2>Songs</h2>
            <div class="song-container" id="album-song-container">
                <?php 
                    if (!$songs) { 
                        echo '<h3 id="no-songs-message">No songs in the album</h3>';
                    }
                ?>
                <?php foreach ($songs as $song): ?>
                    <div class="song">
                        <a href="song_detail.php?id=<?php echo htmlspecialchars($song['song_id']); ?>">
                            <div>
                                <span class="song-title"><?php echo htmlspecialchars($song['judul']); ?></span>
                                <div class="song-details">
                                    <span><?php echo htmlspecialchars($song['penyanyi']); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($isAdmin): ?>
                <div class="admin-action">
                    <button class="btn-edit" id="add-song-button">
                        <img src="./public/image/add.png" alt="Edit Song List"/>
                        <span>Add Song</span>
                    </button>
                    <a href="album_edit.php?id=<?php echo htmlspecialchars($row['album_id']); ?>" > 
                        <button class="btn-edit">
                            <img src="./public/image/edit.png" alt="Edit Album"/>
                            <span>Edit</span>
                        </button>
                    </a>
                    <?php if (count($songs) === 0): ?>
                        <form method="post" action="album_delete.php">
                            <input type="hidden" name="album_id" value="<?php echo htmlspecialchars($row['album_id']); ?>">
                            <button id="delete-album-button" type="submit" onclick="return confirm('Are you sure you want to delete this album?');" class="btn-delete">
                                <img src="./public/image/delete.png" alt="Delete Icon"/>
                                <span>Delete</span>
                            </button> 
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="song-container" id="add-song-container"></div>
        </div>
    </div>
    <script>
        document.getElementById('add-song-button').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_songs_to_add.php?id=<?php echo intval($id); ?>', true);
            xhr.onreadystatechange = function () { 
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('add-song-container').innerHTML = xhr.responseText;
                    addSongButtonsEventListeners();
                } 
            };
            xhr.send();
        });
        function addSongButtonsEventListeners() {
            var addButtons = document.getElementsByClassName('add-song-to-album-button');
            for (var i = 0; i < addButtons.length; i++) {
                addButtons[i].addEventListener('click', function() {
                    var songId = this.getAttribute('data-song-id'); 

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'add_song_to_album.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                var songDiv = document.getElementById('song-to-add-' + songId);
                                songDiv.parentNode.removeChild(songDiv);

                                var albumSongContainer = document.getElementById('album-song-container');
                                var songHtml = '<div class="song">' +
                                    '<a href="song_detail.php?id=' + response.song.song_id + '">' +
                                        '<div>' +
                                            '<span class="song-title">' + response.song.judul + '</span>' +
                                            '<div class="song-details">' +
                                                '<span>' + response.song.penyanyi + '</span>' +
                                            '</div>' +
                                        '</div>' +
                                    '</a>' +
                                '</div>';
                                albumSongContainer.innerHTML += songHtml;
                                
                                var noSongsMessage = document.getElementById('no-songs-message');
                                if (noSongsMessage) {
                                    noSongsMessage.remove();
                                }

                                var deleteButton = document.getElementById('delete-album-button');
                                if (deleteButton){
                                    deleteButton.remove();
                                }

                                var totalDurationSpan = document.querySelector('.album-details span:nth-child(3)');
                                if (totalDurationSpan && response.new_album_total_duration){
                                    totalDurationSpan.innerHTML = formatDuration(response.new_album_total_duration);
                                }

                            } else {
                                alert('Failed to add song: ' + response.message);
                            }
                        }
                    };

                    xhr.send('id=<?php echo intval($id); ?>&song_id=' + songId);
                });
            }
        }

        function formatDuration(seconds){
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            seconds = seconds % 60;
            return hours > 0
                ? hours + ':' + (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds
                : minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
        }

    </script>
</body>

</html>
