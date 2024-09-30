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
Binotify setup can be done using `XAMPP` or `Docker`. It is highly recommended to install one of the two. If you choose to use XAMPP, clone this repository in the `/xampp/htdocs` folder. <br>

### Database Connection and Initialization

The database used in this project is PostGreSQL, so make sure to also install it before running the program. Create `config.php` inside `src/db` with the following content: 
```
    <?php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', '<db_username>');
        define('DB_PASSWORD', '<password>');
        define('DB_NAME', '<db_name>');
    ?>
```
The default value of DB_USERNAME is `postgres`. Change `<password>` with your database password. Make sure to create a new database and change the `<db_name>` with the name of the database you've just created. Run `http://localhost:<port>/BinotifyApp/src/db/db_init.php` to initialize the database connection and fill the database with dummy data. <br>
Finally, execute `composer install` in the root folder



#### Run via XAMPP
You can run the program by accessing `http://localhost:<port>/BinotifyApp` (Change `<port>` with the Apache port value in your XAMPP control panel) in your browser. Make sure to turn on the Apache server in the control panel.

#### Run via Docker

1. Create a `.env` file in the root folder with the following content:

    ```
    POSTGRES_DB=<db_name>
    POSTGRES_USER=<db_user> 
    POSTGRES_PASSWORD=<db_password>
    ```
    Change the values to the actual database, user, and password values.
<br><br>
2. Execute the following command in the root folder: 

    ```
    $ docker-compose up --build
    ```
<br>

3. Access the project through `localhost:8080`




