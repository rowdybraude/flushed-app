<?php
DEFINE('DB_USER', 'root'); // Establises generic constants to connect to the mySQL database.
DEFINE('DB_PASSWORD', 'root');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_NAME', 'flushed');

// Attempt to connect to database using constants- if failed, kill script and output error message.
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("connection to server failed.");
?>

