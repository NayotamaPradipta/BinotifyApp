CREATE TABLE IF NOT EXISTS album(
    album_id INT NOT NULL,
    penyanyi VARCHAR(128) NOT NULL, 
    total_duration INT NOT NULL DEFAULT 0,
    judul VARCHAR(64) NOT NULL, 
    image_path VARCHAR(256) NOT NULL, 
    tanggal_terbit DATE NOT NULL,
    genre VARCHAR(64),
    PRIMARY KEY (album_id)
);

CREATE TABLE IF NOT EXISTS song(
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

CREATE TABLE IF NOT EXISTS binotify_user (
    user_id VARCHAR(256) NOT NULL,
    password VARCHAR(256) NOT NULL,
    username VARCHAR(256) NOT NULL,
    isadmin BOOLEAN NOT NULL
);
