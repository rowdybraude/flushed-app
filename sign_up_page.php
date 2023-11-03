<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
    </head>
    <?php include 'Navbar.php' ?>
    <body>
        <div id="main">
            <div id="body">  
                <h1 style="text-align:center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 10px; margin-top: 0">Sign Up</h1>
                <form action="sign_up_function.php" method="post" id="form" enctype="multipart/form-data">
                    <p id="formText">Name</p>
                    <input type="text" name="nameText" id="formInput">
                    <p id="formText">Email</p>
                    <input type="email" name="emailText" id="formInput">
                    <p id="formText">Password</p>
                    <input type="password" name="passwordText" id="formInput">
                    <p id="formText">Repeat Password</p>
                    <input type="password" name="passwordCheckText" id="formInput"><br>
                    <div>
                        <div>
                        <?php // Add the tag box to the form
                        include 'tag_box.php';?>
                        </div>
                        <div>
                        <p id="formText" style="margin-top: 0; margin-left: 40vw">Already have an account? <a href="log_in.php">Log in!</a></p>
                        </div>
                    </div>
                    <?php
                    if($_SESSION['validationFailureID'] != 0) { // Check if a validation failture ID has been returned via sessions and output an error accordingly.
                        echo '<div class="errorBox">';
                        switch( $_SESSION['validationFailureID']) {
                            case 1:
                                echo '<p>Fields are not completely filled. Please try again.</p>';
                                break;
                            case 2:
                                echo '<p>Passwords do not match. Please try again.</p>';
                                break;
                            case 3:
                                echo '<p>User already exists. Please use a different email.</p>';
                        }
                        echo '</div>';
                    }
                    ?>
                    <input type="submit" value="Sign Up" name="confirmBtn" action="sign_up.php" id="formSubmit" style="margin-top: 20vh">
                </form>
            </div>
        </div>
    </body>
</html>