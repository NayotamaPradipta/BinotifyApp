## Binotify
### Web App Description
**Binotify** is a web-based music application that allows the users to listen and explore music albums available in the app. This app has several features including: 
<br>
- Register
- Login
- Song/Album Search
- Album Lists
- Add/Edit Album
- Add/Edit Song
- User lists
<br> <br>
Listen to the best music **without ads** in **Binotify**!

### Installation Requirements 
To run this program, you are advised to install XAMPP, and clone this repository in the `/xampp/htdocs` folder. <br>
You can run the program by accessing `http://localhost:<port>/BinotifyApp` (Change `<port>` with the Apache port value in your XAMPP control panel) in your browser.

The database used in this project is PostGreSQL, so make sure to also install it before running the program. Create `config.php` inside `src/db` with the following content: 
```
    <?php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', '<db_username>');
        define('DB_PASSWORD', '<password>');
        define('DB_NAME', '<db_name>');
    ?>
```
The default value of DB_USERNAME is `postgres`. Change `<password>` with your database password. Make sure to create a new database and change the `<db_name>` with the name of the database you've just created. 