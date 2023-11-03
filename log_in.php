<?php 
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div id="main">
        <?php include 'Navbar.php' ?>
    </div>
            <div id="body">  
                <h1 style="text-align:center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 40px; padding-bottom: 100px;">Log In</h1>
                <form action="log_in_function.php" method="post" id="form">
                    <p id="formText">Email</p>
                    <input type="email" name="emailText" id="formInput">
                    <p id="formText">Password</p>
                    <input type="password" name="passwordText" id="formInput">
                    <input type="submit" value="Log In" name="confirmBtn" id="formSubmit">
                </form>
                <?php
                if($_SESSION['validationFailureID'] == 1) { // If log_in_function.php returns a validation error, output accordingly. 
                    echo'<div class="errorBox">
                            <p>Email or password incorrect. Please try again.</p>
                        </div>';
                }
                ?>
            </div>
        </div>
    </body>
</html>