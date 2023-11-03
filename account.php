<?php
session_start();
// Connect user to SQLi database
require_once("server_connect.php"); // Get information refarding the user account.
require_once('review_display_functions.php');
$thisUserIndex = $_SESSION['userIndex']; // Gets the user index from session storage.
$thisUserData = mysqli_query($dbc, "SELECT * FROM user WHERE userID = $thisUserIndex"); // Fetches user infomation at this index from the server. 
$thisUserData = mysqli_fetch_assoc($thisUserData); // Gets user data as an associative array so that it can be manipulated with php scripting. Only called once as there should only be one user at a given index. 
?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'Navbar.php' ?>
    <div id="body">
        <h1 style="text-align:center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 10px; margin-top: 10px">My Account</h1>
        <div style="position: fixed; top: 80; left: 400px">
            <?php include 'tag_display.php'; 
            GenerateTags('user', $thisUserIndex, $dbc);
            ?> 
        </div>
        <div id="formText" style="display:inline-block; position: absolute; margin-left: 20vw; margin-top: 20vh;">
            <p><b>Name:</b> <?php echo $thisUserData['userName'] // Outputs the user's name and email onto the page?></p> 
            <p><b>Email:</b> <?php echo $thisUserData['userEmail']?></p>
            <form action="log_out_function.php" method="post" id="form">
                <input type="submit" value="Log Out" name="confirmBtn" id="logOutBtn">
            </form>
        </div>
        <h2 id="formText" style="margin-left: 2vw; margin-top: 40vh"><u>Your Reviews</u></h2>
        <div class="reviewPreviews" style="width: 80vw; margin-top: 20px">
         <?php
         // A complex query which gets all the reviews created by the current user and returns the average for each review accross all categories, the name of the locatio being reviewed, and the comments for said review. 
         $q = "SELECT ((reviewCleanliness + reviewSpaciousness + reviewAesthetics + reviewFeatures + reviewAccessability + reviewAvailability) / 6) AS averageScore, reviewComments, review.bathroomID, bathroomName FROM review JOIN bathroom ON (bathroom.bathroomID = review.bathroomID) WHERE review.userID = $thisUserIndex";
         $thisUserReviews = mysqli_fetch_all(mysqli_query($dbc, $q), MYSQLI_BOTH); // Gets a list of all reviews made by the user as both an associative and numeric array.
         $reviewCount = count($thisUserReviews) - 1; // Gets amount of reviews-1
         while($reviewCount > -1) { // Reverse linear search which outputs all of the user's reviews by recency.
            $review = $thisUserReviews[$reviewCount];
            echo ConstructReview($review['bathroomName'], $review['reviewComments'], round($review['averageScore'], 1), $review['bathroomID']); // Average score is rounded to prevent the score text from going out of bounds and to procvide concistency. 
            $reviewCount -= 1;
         }
        ?>
        </div>
    </div>
</body>
