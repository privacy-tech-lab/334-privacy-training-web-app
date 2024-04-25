# 334-privacy-training-web-app

Training web app for COMP 334: Information Security and Privacy at Wesleyan University.

- The app is deployed at <http://comp360.lovestoblog.com/>.
- The HTTPS version of the app is deployed at <https://comp360secure.lovestoblog.com/>. (Note that free certificates are only valid for 90 days.)

When deploying the app, add a config.php file to the root directory with the following content:

```php
<?php
/* Database credentials */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
```

You would need to change the database credentials for `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_NAME` according to yours. As they are given above, they work for a local setup but not for an online deployment.
