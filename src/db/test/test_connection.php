<?php
    $connection = require('../db_connect.php');
    $connect_db = $connection['connect_db'];

    try {
        $pdo = $connect_db();
        echo "Connection successful";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage(); 
    };
    // Go to http://localhost:<port>/BinotifyApp/src/db/test/test_connection.php to test out the database connection
?>
