<?php error_reporting(E_ALL) ; ini_set('display_errors',1); ?>

<?php 
session_start(); 
// Connect user to SQLi database
require 'server_connect.php';

// Gets the ID of the current user
$userID = $_SESSION['userIndex']; // Fetch the ID of the user from SESSION. 

// Fetches all the inputed values input to the previous page
$bathroomName = (string)$_POST['bathroomName'];
$bathroomLng = (double)$_POST['bathroomLng'];
$bathroomLat = (double)$_POST['bathroomLat'];

$bathroomCleanliness = (int)$_POST['bathroomCleanliness'];
$bathroomSpaciousness = (int)$_POST['bathroomSpaciousness'];
$bathroomAesthetics = (int)$_POST['bathroomAesthetics'];
$bathroomFeatures = (int)$_POST['bathroomFeatures'];
$bathroomAccessability = (int)$_POST['bathroomAccessability'];
$bathroomAvailability = (int)$_POST['bathroomAvailability'];
$bathroomComments = (string)$_POST['bathroomComments'];
$bathroomNotes = (string)$_POST['bathroomNotes'];

$tags = array($_POST['tag1'], $_POST['tag2'], $_POST['tag3']);
foreach($tags as $tag) {
    echo $tag;
}

// Validates whether the appropriate fields have been filled out
if($bathroomLat == null) {
    $_SESSION['validationFailureID'] = 1;
    header("Location: create_review.php"); // If not, send the user back to the previous page and output error message.
    die(); 
} 
// Checks if the bathroom already exists - if not, create new one
$q = "SELECT bathroomID, bathroomName FROM bathroom WHERE bathroomName = '$bathroomName'";
$bathroomID = 0;
$result = mysqli_query($dbc, $q);
if(mysqli_num_rows($result) == 0){ // If no bathrooms were returned under the name of the reviewed bathrooms,insert a new bathroom
    $q = "INSERT INTO bathroom(bathroomName, bathroomLocationLng, bathroomLocationLat) VALUES('$bathroomName', $bathroomLat, $bathroomLng)";
    mysqli_query($dbc, $q);
    $q = "SELECT MAX(bathroomID) FROM bathroom";
    $bathroomID = mysqli_fetch_assoc(mysqli_query($dbc, $q))['MAX(bathroomID)']; // Get the ID of the newest bathroom-- the one just created.
}
else { // If there was a bathroom with the name inputted by the user 
    $bathroomID = mysqli_fetch_assoc($result)['bathroomID'];
    $q = "SELECT userID FROM review WHERE bathroomID = $bathroomID";
    $reviewersOfThisBathroom = mysqli_query($dbc, $q); // A list of the people who reviewed this bathroom
    while(true) { // Cycle through users who reviewed the current bathroom and check if any of them have the same user ID as the current user.
        if($userID == mysqli_fetch_assoc($reviewersOfThisBathroom)['userID']) {
            $_SESSION['validationFailureID'] = 2;
            header('Location: create_review.php'); // If this user has already reviewed this bathroom, direct them back to the review page.
            die(); 
        }
        elseif(mysqli_fetch_assoc($reviewersOfThisBathroom) == null) { // Break the while loop once it's clear that none of the users who had already reviewed this bathroom are the current user. 
            break;
        }
    }
}
// This section is effectively the same as the tag adding sequence in sign_up_function.php page- with some adjustments to accomodate the fact that tags are being added to a review.
$createReviewQuery = "INSERT INTO review(userID, bathroomID, reviewCleanliness, reviewSpaciousness, reviewAesthetics, reviewFeatures, reviewAccessability, reviewAvailability, reviewComments, reviewNotes";
$tagValues = $tags;
for($i = 0; $i < count($tags); $i++) {
    if(strlen($tags[$i]) > 0) { // If a tag is not null, try to fetch it's instance from the list of tags
        $q = "SELECT tagID FROM tags WHERE tag = '$tags[$i]'";
        $tags[$i] = (int)mysqli_fetch_assoc(mysqli_query($dbc, $q))['tagID'];
        if($tags[$i] == null) { // if no such instance exists, make a new tag with the value of the current tag being compared, store it's index in the SQL sheet, and replace the current tag with this new index value.
            $q = "INSERT INTO tags(tag) VALUES('$tagValues[$i]')";
            mysqli_query($dbc, $q);
            $q = "SELECT MAX(tagID) AS thisTagID FROM tags";
            $tags[$i] = mysqli_fetch_assoc(mysqli_query($dbc, $q))['thisTagID'];
        }   
        $createReviewQuery = $createReviewQuery.', reviewTagID'.$i+1; // Add the index of the current tag to the tag field in the SQL sheet and add it to the INSERT query as a reference to the currentTagID field. . 
    } 
    else {
        $tags[$i] == null;
    }
}
$createReviewQuery = $createReviewQuery.') '; // Complete the syntax for the SQL query and add the neccesary values for a review to be created.
$createReviewQuery = $createReviewQuery."VALUES ($userID, $bathroomID, $bathroomCleanliness, $bathroomSpaciousness,$bathroomAesthetics, $bathroomFeatures,$bathroomAccessability, $bathroomAvailability, '$bathroomComments', '$bathroomNotes'"; // Adds the neccessary values for review creation to the INSERT query.
for($i = 0; $i < count($tags); $i++) { //Cycle through the tagIDs- if there is one, make add it as a value to the INSERT query.   
    if($tags[$i] != null) { 
        $createReviewQuery = $createReviewQuery.', '.$tags[$i];
    }
}
$createReviewQuery = $createReviewQuery.')';
echo $createReviewQuery;
mysqli_query($dbc, $createReviewQuery); 
// Update the tags list to include any new tags which may now be associated with this bathroom.
$q = "SELECT * FROM tag WHERE (bathroomID = $bathroomID) AND tagID IN (".$tags[0].", ".$tags[1].", ".$tags[2].")";
echo $q;
$tagsUsedByBathroom = mysqli_fetch_all(mysqli_query($dbc, $q), MYSQLI_BOTH); 
foreach($tags as $tag) { // Loop through user submitted tags 
    $isExistingTag = false; // Flag variable to determine whether the currently evaluated tag matches any other tags existing under the ID for this bathroom.
    foreach($tagsUsedByBathroom as $currentTag) {
        if($currentTag['tagID'] == $tag) { // Set
            $isExistingTag = true; 
            break;
        }
    }
    if($isExistingTag == false) { // If entire loop is run without detecting a matching tag, add a new connection between the current tag and bathroom ID. 
        $q = "INSERT INTO tag(bathroomID, tagID) VALUES ($bathroomID, $tag)";
        echo $q;
        mysqli_query($dbc, $q);
    }
}


header('Location: bathroom_page.php?bathroomID='.$bathroomID); // Direct user to the review page of the bathroom they just reviewed. 
die(); 
?>