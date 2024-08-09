<?php
    ['connect_db' => $connect_db] = require('db_connect.php');
    $db = $connect_db();

    // Create Album Table 
    $album_query = "
        CREATE TABLE IF NOT EXISTS album(
            album_id SERIAL PRIMARY KEY,
            penyanyi VARCHAR(128) NOT NULL, 
            total_duration INT NOT NULL DEFAULT 0,
            judul VARCHAR(64) NOT NULL, 
            image_path VARCHAR(256) NOT NULL, 
            tanggal_terbit DATE NOT NULL,
            genre VARCHAR(64)
        );


    ";
    try {
        $db->exec($album_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $album_init_query = "
        INSERT INTO album (penyanyi, total_duration, judul, image_path, tanggal_terbit, genre)
        SELECT 'LXNGVX', 88, 'Sigma', 'public/music/visual/album/phonkalbum.jpeg', '2023-01-01', 'Phonk'
        WHERE NOT EXISTS (SELECT 1 FROM album WHERE judul = 'Sigma' AND penyanyi = 'LXNGVX');
    ";

    try {
        $db->exec($album_init_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }


    // Create Song Table 
    $song_query = "
        CREATE TABLE IF NOT EXISTS song(
            song_id SERIAL PRIMARY KEY, 
            judul VARCHAR(64) NOT NULL,
            penyanyi VARCHAR(128) NOT NULL,
            tanggal_terbit DATE NOT NULL, 
            genre VARCHAR(64),
            duration INT NOT NULL, 
            audio_path VARCHAR(256) NOT NULL,
            image_path VARCHAR(256),
            album_id INT,
            CONSTRAINT FK1 FOREIGN KEY (album_id)
                REFERENCES album (album_id) MATCH SIMPLE 
                ON UPDATE CASCADE
                ON DELETE SET NULL
        );

        INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
        SELECT 'DNA', 'LXNGVX', '2023-01-01', 'Phonk', 88, 'public/music/audio/DNA.mp3', 'public/music/visual/song/dna.jpg', 
        (SELECT album_id FROM album WHERE judul = 'Sigma' AND penyanyi = 'LXNGVX' LIMIT 1)
        WHERE NOT EXISTS (SELECT 1 FROM song WHERE judul = 'DNA' AND penyanyi = 'LXNGVX');

        INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
        SELECT 'Montagem Mysterious Game', 'LXNGVX', '2022-02-02', 'Phonk', 103, 'public/music/audio/MMG.mp3', 'public/music/visual/song/mmg.jpg', 
        (SELECT album_id FROM album WHERE judul = 'Sigma' AND penyanyi = 'LXNGVX' LIMIT 1)
        WHERE NOT EXISTS (SELECT 1 FROM song WHERE judul = 'Montagem Mysterious Game' AND penyanyi = 'LXNGVX');
    ";

    try {
        $db->exec($song_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $user_query = "
        CREATE TABLE IF NOT EXISTS binotify_user (
            user_id SERIAL PRIMARY KEY,
            email VARCHAR(256) NOT NULL,
            password VARCHAR(256) NOT NULL,
            username VARCHAR(256) NOT NULL,
            isadmin BOOLEAN NOT NULL
        );

        INSERT INTO binotify_user (email, password, username, isadmin)
        SELECT 'binotimin@gmail.com', 'admin', 'admin', true
        WHERE NOT EXISTS (SELECT 1 FROM binotify_user WHERE username = 'admin');
    ";

    try {
        $db->exec($user_query);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>