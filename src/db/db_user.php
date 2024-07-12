<?php
    ['connect_db' => $connect_db] = require('db_connect.php');
    $db = $connect_db();
    // User Table 
    $user_query = '
        DROP TABLE IF EXISTS binotify_user CASCADE;
        CREATE TABLE IF NOT EXISTS binotify_user (
            user_id VARCHAR(256) NOT NULL,
            password VARCHAR(256) NOT NULL,
            username VARCHAR(256) NOT NULL,
            isadmin BOOLEAN NOT NULL
        );
    ';
    try { 
        $db->exec($user_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>