<?php
session_start();
session_destroy(); //  Destroy the session and return the user to the front page. 
header('Location: index.php');
die(); 
?>