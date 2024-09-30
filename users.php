<?php
    include "navbar.php";
?>

<!DOCTYPE html>
<html lang='en'> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/navbar.css" type="text/css">
    <link rel="stylesheet" href="public/css/users.css" type="text/css">
    <title>Users</title>
</head>
<body>
    <?php 
        ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
        $pdo = $connect_db();
        $stmt = $pdo->query('SELECT user_id, username, email FROM binotify_user ORDER BY user_id ASC');
        while ($row = $stmt->fetch()){
            echo '<div class="users">';
                echo '<div class="user-id">';
                    echo '<h2>' . htmlspecialchars($row['user_id']) . '</h2>';
                echo '</div>';
                echo '<div class="user-detail">';
                    echo '<div class="user-info">';
                        echo '<span class="label">Username:</span>';
                        echo '<span class="value">' . htmlspecialchars($row['username']) . '</span>';
                    echo '</div>';
                    echo '<div class="user-info">';
                        echo '<span class="label">Email:</span>';
                        echo '<span class="value">' . htmlspecialchars($row['email']) . '</span>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';  
        }
    ?>
</body>

</html>
