CREATE TABLE IF NOT EXISTS album(
    album_id SERIAL PRIMARY KEY,
    penyanyi VARCHAR(128) NOT NULL, 
    total_duration INT NOT NULL DEFAULT 0,
    judul VARCHAR(64) NOT NULL, 
    image_path VARCHAR(256) NOT NULL, 
    tanggal_terbit DATE NOT NULL,
    genre VARCHAR(64)
);

INSERT INTO album (penyanyi, total_duration, judul, image_path, tanggal_terbit, genre)
SELECT 'LXNGVX', 88, 'Sigma', 'public/music/visual/album/phonkalbum.jpeg', '2023-01-01', 'Phonk'
WHERE NOT EXISTS (SELECT 1 FROM album WHERE judul = 'Sigma' AND penyanyi = 'LXNGVX');

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
    CONSTRAINT "FK1" FOREIGN KEY (album_id)
        REFERENCES album (album_id) MATCH SIMPLE 
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
SELECT 'DNA', 'LXNGVX', '2023-01-01', 'Phonk', 88, 'public/music/audio/DNA.mp3', 'public/music/visual/song/dna.jpg', 1
WHERE NOT EXISTS (SELECT 1 FROM song WHERE judul = 'DNA' AND penyanyi = 'LXNGVX');

INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
SELECT 'Montagem Mysterious Game', 'LXNGVX', '2022-02-02', 'Phonk', 103, 'public/music/audio/MMG.mp3', 'public/music/visual/song/mmg.jpg', 1
WHERE NOT EXISTS (SELECT 1 FROM song WHERE judul = 'Montagem Mysterious Game' AND penyanyi = 'LXNGVX');

CREATE TABLE IF NOT EXISTS binotify_user (
    user_id SERIAL PRIMARY KEY,
    password VARCHAR(256) NOT NULL,
    username VARCHAR(256) NOT NULL,
    isadmin BOOLEAN NOT NULL
);

INSERT INTO binotify_user (password, username, isadmin)
SELECT 'admin', 'admin', true
WHERE NOT EXISTS (SELECT 1 FROM binotify_user WHERE username = 'admin');