<?php
session_start(); 
// Connect user to SQLi database
require 'server_connect.php';

$userEmail = $_POST['emailText']; // Receives the inputted info about the user through the POST method to receive data from the form.
$userPassword = $_POST['passwordText'];

function ValidateUserData($connection,$email, $password) : bool { // Function which validates the user's account data - will return true if legitimate, will return false if not. 
    $goodEmail = false; // Flag variables which indicate whether the user input is legitimate. 
    $goodPassword = false; 

    if($email == null || $password == null) { // Checks whether the user filled out both fields on the login page.
        return false;
    }
    
    $list = mysqli_query($connection, "SELECT userEmail, userID FROM user"); // Pull list of all user emails
    while(true) { // Cycles through the list of emails returned by the database - if one matches, get the userID associated with the email and get the password associated with that userID. Compare the passwords. if they do not match, return false.
        $em = mysqli_fetch_assoc($list);
        if($em['userEmail'] == $email) {
            $goodEmail = true; 
            $id = $em['userID'];
            $userPassword = mysqli_fetch_assoc(mysqli_query($connection, "SELECT userPassword FROM user WHERE userID = $id"))['userPassword'];
            if($password == $userPassword){
                $goodPassword = true;
            }
            $_SESSION['loggedIn'] = true; // Set variables which identify that the user is signed in (and which user).
            $_SESSION['userIndex'] = $id;
            break;
        }
        elseif($em == null) { // Stops checking for emails once msqli has reached the end of the list and can't fetch any more emails.
            break;
        }
    }

    if($goodEmail && $goodPassword) { // Confirm that both the email and password have bee validated.
        return true; 
    }
    return false;
}

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("connection to server failed.");
if(ValidateUserData($dbc, $userEmail, $userPassword)) { // Run function to check whether or not the user data is legitimate.
    $_SESSION['validationFailureID'] = 0; // Reset validationFailureID to 0 and send the user to the account page.
    header("Location: account.php");
    die();
}
$_SESSION['validationFailureID'] = 1; // Sets the validationFailureID to 1 so that log_in.php understands which error message to output and routes the user backwards.
header("Location: log_in.php"); // Forwards user back to the log in page.
die(); 
?>