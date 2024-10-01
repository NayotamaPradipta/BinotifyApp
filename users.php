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
    <link rel="stylesheet" href="public/css/users.css" type="text/css">
    <title>Users</title>
</head>
<body>
    <?php 
        ['connect_db' => $connect_db] = require('./src/db/db_connect.php');
        $pdo = $connect_db();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $pagination = paginate($pdo, 'binotify_user', 'user_id ASC', $page);
        $users = $pagination['items'];
        $total_pages = $pagination['total_pages'];
        $current_page = $pagination['current_page'];
        foreach ($users as $row){
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
