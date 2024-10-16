<?php
$connect_db = function () {
    require_once('config.php');
    try {
        $pdo = new PDO(
            "pgsql:host=" . DB_SERVER . 
            ";dbname=" . DB_NAME,
            DB_USERNAME,
            DB_PASSWORD
        );

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        return $pdo;
    } catch (PDOException $error) { 
        throw $error;
    }   
};

return compact('connect_db');