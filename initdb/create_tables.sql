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
SELECT 'LXNGVX', 191, 'Sigma', 'public/music/visual/album/phonkalbum.jpeg', '2023-01-01', 'Phonk'
WHERE NOT EXISTS (SELECT 1 FROM album WHERE judul = 'Sigma' AND penyanyi = 'LXNGVX');

INSERT INTO album (penyanyi, total_duration, judul, image_path, tanggal_terbit, genre)
SELECT 'Monster Cat', '555', 'Best of 2014', 'public/music/visual/album/monstercat.jpg', '2014-12-29', 'Electronic'
WHERE NOT EXISTS (SELECT 1 FROM album WHERE judul = 'Best of 2014' AND penyanyi = 'Monster Cat');


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

INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id)
SELECT 'Valkyrie', 'Varien', '2014-06-17', 'Electronic', 208, 'public/music/audio/Valkyrie.mp3', 'public/music/visual/song/valkyrie.jpg', 2
WHERE NOT EXISTS (SELECT 1 FROM song WHERE judul = 'Valkyrie' AND penyanyi = 'Varien');

INSERT INTO song (judul, penyanyi, tanggal_terbit, genre, duration, audio_path, image_path, album_id) 
SELECT 'Snowblind', 'Au5', '2014-09-11', 'Electronic', 347, 'public/music/audio/Snowblind.mp3', 'public/music/visual/song/snowblind.jpg', 2
WHERE NOT EXISTS (SELECT 1 FROM song WHERE judul = 'Snowblind' AND penyanyi = 'Au5');

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
