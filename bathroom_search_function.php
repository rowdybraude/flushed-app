
<?php 
require_once('server_connect.php');
session_start(); 

// This script outlines the logic needed to get the bathroomID from the user inputted data, or present a null instance of a bathroom with no reviews yet. 
$placeName = $_GET['bathroomName']; // Fetch name of bathroom from the URL
$q = "SELECT bathroomID FROM bathroom WHERE bathroomName = '$placeName'"; // Try to select bathroom with same name as the one given by the user.
$bathroomID = mysqli_fetch_assoc(mysqli_query($dbc, $q));
$id=""; // Set a variable to represent the bathroom id as "" is case there is not bathroom instance given by the server. 
if($bathroomID != null) {
    $id = $bathroomID['bathroomID']; 
}
header("Location: bathroom_page.php?bathroomID=".$id); // Add the potential bathroom id as a $_GET and redirect the user to the review page.
die(); 

?>