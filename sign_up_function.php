<?php error_reporting(E_ALL) ; ini_set('display_errors',1); ?>

<?php 
session_start();
require 'databaseHandler.php';
function ValidateUserData($connection, $name, $email, $password, $repeatPassword) : bool { // Function which validates the user's account data - will return true if legitimate, will return false if not. 
    // -- VALIDATE THAT ALL ELEMENTS OF THE FORM WERE FILLED OUT -- 
    if($name == "") {
        $_SESSION['validationFailureID'] = 1; 
        return false;
    }
    if($email == "") {
        $_SESSION['validationFailureID'] = 1; 
        return false;
    }
    if($password == "") {
        $_SESSION['validationFailureID'] = 1; 
        return false;
    }
    if($repeatPassword == "") {
        $_SESSION['validationFailureID'] = 1; 
        return false;
    }

    // -- VALIDATE THAT THE REPEATED PASSWORD IS THE SAME AS THE ORIGINAL PASSWORD --
    if($password != $repeatPassword) {
        $_SESSION['validationFailureID'] = 2; 
        return false; 
    }

    // -- VALIDATE UNIQUENESS OF USER BY COMPARING EMAIL -- 
    
    $emailList = mysqli_query($connection, "SELECT userEmail FROM user"); // Pull list of all user emails
    while(true) {
        $em = mysqli_fetch_assoc($emailList);
        if($em['userEmail'] == $email) { // If an email that already exists is found, return false
            $_SESSION['validationFailureID'] = 3; 
            return false; 
        }
        if($em == null) {
            break;
        }
    }

    return true; // All of the validation tests pass, return true
}

// Get all of the data inputted by the user on the previous page. 
$userName = $_POST['nameText'];
$userEmail = $_POST['emailText'];
$userPassword = $_POST['passwordText'];
$userPasswordCheck = $_POST['passwordCheckText'];
$tags = array($_POST['tag1'], (string)$_POST['tag2'], $_POST['tag3']);

if(!ValidateUserData($dbc, $userName, $userEmail, $userPassword, $userPasswordCheck)) {
    header("Location: sign_up_page.php"); // IF the data inputted by the user is invalid, return to the previous page.
    die(); 
}

$createAccountQuery = "INSERT INTO user(userName, userEmail, userPassword"; // First part of INSERT query which includes the neccesary information that all accounts must have to function. 
for($i = 0; $i < count($tags); $i++) {
    if(strlen($tags[$i]) > 0) { // If a tag is not null, try to fetch it's instance from the list of tags
        $q = "SELECT tagID FROM tags WHERE tag = '$tags[$i]'";
        $tags[$i] = (int)mysqli_fetch_assoc(mysqli_query($dbc, $q))['tagID'];

        if($tags[$i] == null) { // if no such instance exists, make a new tag with the value of the current tag being compared, store it's index in the SQL sheet, and replace the current tag with this new index value.
            $q = "INSERT INTO tags(tag) VALUES('$tags[$i]')";
            mysqli_query($dbc, $q);
            $q = "SELECT MAX(tagID) AS thisTagID FROM tags";
            $tags[$i] = mysqli_fetch_assoc(mysqli_query($dbc, $q))['thisTagID'];
        }   

        $createAccountQuery = $createAccountQuery.', userTagID'.$i+1; // Add the index of the current tag to the tag field in the SQL sheet and add it to the INSERT query as a reference to the currentTagID field. . 
    } 
    else {
        $tags[$i] == null;
    }
}
$createAccountQuery = $createAccountQuery.') '; // Complete the syntax for the SQL query and add the neccesary values for an account to be created.
$createAccountQuery = $createAccountQuery."VALUES ('$userName', '$userEmail', '$userPassword'"; // Adds the neccessary values for account creation to the INSERT query.
for($i = 0; $i < count($tags); $i++) { //Cycle through the tagIDs- if there is one, make add it as a value to the INSERT query.   
    if($tags[$i] != null) { 
        $createAccountQuery = $createAccountQuery.', '.$tags[$i];
    }
}
$createAccountQuery = $createAccountQuery.') '; // Finish the syntax of the account creation INSERT query and run it through the SQL server
echo $createAccountQuery;
mysqli_query($dbc, $createAccountQuery); 

// Update current user id with newly created ID
$q = "SELECT MAX(userID) from user";
$currentID = mysqli_query($dbc, $q);
$currentID = mysqli_fetch_assoc($currentID);
$_SESSION['userIndex'] = $currentID['MAX(userID)']; // Reset all SESSION variables.
$_SESSION['loggedIn'] = true;
$_SESSION['validationFailureID'] = 0; 
//Move to the account page
header("Location: account.php");
die();
?>