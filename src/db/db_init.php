<?php
    ['connect_db' => $connect_db] = require('db_connect.php');
    $db = $connect_db();
    // Create Album Table 
    $album_query = '
        DROP TABLE IF EXISTS album CASCADE;
        CREATE TABLE album(
            album_id INT NOT NULL,
            penyanyi VARCHAR(128) NOT NULL, 
            total_duration INT NOT NULL DEFAULT 0,
            judul VARCHAR(64) NOT NULL, 
            image_path VARCHAR(256) NOT NULL, 
            tanggal_terbit DATE NOT NULL,
            genre VARCHAR(64),
            PRIMARY KEY (album_id)
        );
    ';
    try {
        $db->exec($album_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Create Song Table 
    $song_query = '
        DROP TABLE IF EXISTS song CASCADE; 
        CREATE TABLE song(
            song_id INT NOT NULL, 
            judul VARCHAR(64) NOT NULL,
            penyanyi VARCHAR(128) NOT NULL,
            tanggal_terbit DATE NOT NULL, 
            genre VARCHAR(64),
            duration INT NOT NULL, 
            audio_path VARCHAR(256) NOT NULL,
            image_path VARCHAR(256),
            album_id INT,
            PRIMARY KEY (song_id),
            CONSTRAINT "FK1" FOREIGN KEY (album_id)
                REFERENCES album (album_id) MATCH SIMPLE 
                ON UPDATE CASCADE
                ON DELETE SET NULL
        );
    ';

    try {
        $db->exec($song_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>