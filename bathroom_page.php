 
<?php 

session_start(); 
include_once('server_connect.php');
include_once('review_display_functions.php');
// Fetch data from bathroom
$bathroomID = $_GET['bathroomID'];
if($bathroomID != null) {
    $q = "SELECT userID,  reviewCleanliness, reviewSpaciousness, reviewAesthetics, reviewFeatures, reviewAccessability, reviewAvailability, reviewComments FROM review WHERE bathroomID = $bathroomID";
    $allReviews = mysqli_query($dbc, $q); // Gets a table of every review relating to this review.
    $allReviews = mysqli_fetch_all($allReviews, MYSQLI_BOTH);

    $q = "SELECT bathroomName FROM bathroom WHERE bathroomID = $bathroomID"; // Get the name of the bathroom
    $bathroomName = mysqli_fetch_assoc(mysqli_query($dbc, $q))['bathroomName'];
}


function AverageScores($reviewList) { // Obtains the average review score for each critera and returns it as an associative array.
    $rows = count($reviewList);
    $reviewAverages = array('reviewCleanliness' => 0, 'reviewSpaciousness' => 0, 'reviewAesthetics' => 0, 'reviewFeatures' => 0, 'reviewAccessability' => 0, 'reviewAvailability' => 0);
    $reviewProgressPointer = 0;
    while($reviewProgressPointer < $rows) {
        $currentRow = $reviewList[$reviewProgressPointer];
        foreach($reviewAverages as $key => $value) {
            $reviewAverages[$key] = $currentRow[$key] + $value;
        }
        $reviewProgressPointer += 1;
    }

    $total =0;
    foreach($reviewAverages as $key => $value) {
        $reviewAverages[$key] /= $rows;
        $value = $value / $rows;
        $total = $value + $total; ;
    }
    $total /= 6; // Divided by six as this is the number of categories
    $reviewAverages['meanScore'] = $total; 

    return $reviewAverages;
}
if($bathroomID != null) {
    $thisBathroomReviewAverages = AverageScores($allReviews);
}

?>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'Navbar.php' ;
?>
    <div id="body">
        <div id='locationName'> 
        <h1 style="text-align:center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 20px" id='locationName'><?php if($bathroomID != null ) {echo$bathroomName;}?></h1>
        </div> 
        <div id="averageScore" class="averageScore">
            <h1 style="color: red; font-size:12vw; margin: auto auto 0 0; text-align: center;" id="formText"><?php if($bathroomID != null) {echo round((float)$thisBathroomReviewAverages['meanScore'], 1);} 
            else {echo 'N/A';}?></h1> 
        </div>
        <div style="position: absolute; margin-top: 28vh; margin-left: 60vw">
        <?php 
        if($bathroomID != null) {
            include 'tag_display.php'; 
            GenerateTags('bathroom', $bathroomID, $dbc);
        }    
        ?>
        </div>
        <div id="bathroomScoreSpecifics" class="bathroomScoreSpecifics">
            <div id="bathroomScoreCategories" class="bathroomScoreCategories">
                <p style="margin-top: 10px; margin-bottom: 10px">Cleanliness</p>
                <p style="margin-top: 10px; margin-bottom: 10px">Spaciousness</p>
                <p style="margin-top: 10px; margin-bottom: 10px">Aesthetics</p>
                <p style="margin-top: 10px; margin-bottom: 10px">Features</p>
                <p style="margin-top: 10px; margin-bottom: 10px">Accessability</p>
                <p style="margin-top: 10px; margin-bottom: 10px">Availability</p>
            </div>
            <div id="bathroomScores" class="bathroomScores">
                <?php
                    if($bathroomID != null) {
                        foreach($thisBathroomReviewAverages as $key => $value) {
                            echo '<progress style="margin-top: 10px; margin-bottom: 7px" value="'.($value).'" min="1" max="5"></progress>';
                        }
                    }
                ?>
            </div>
        </div>
        <?php 
        if($bathroomID == null) {
            echo'<h2 id="formText" style="margin-left: 15px"><a href="create_review.php">Create Review</a></h2>';
        }
        else {
            $reviewed = false;
            foreach($allReviews as $review) { // Output special preview review towards the top when the review belongs to the current user.
                if($review['userID'] == $_SESSION['userIndex']) {
                    $id = $_SESSION['userIndex'];
                    $q = "SELECT userName FROM user WHERE userID = $id";
                    $thisUserName = mysqli_fetch_assoc(mysqli_query($dbc, $q))['userName'];
                    echo'<h2 id="formText" style="margin-left: 15px">Your Review</h2>';
                    $reviewed = true;
                    echo ConstructReview($thisUserName, $review['reviewComments'],round((($review['reviewCleanliness'] + $review['reviewSpaciousness'] +$review[ 'reviewAesthetics']+$review['reviewFeatures']+$review['reviewAccessability']+$review['reviewAvailability']) / 6), 1));
                }
            }
            if($reviewed == false) { // If no user review is found, output a link to create a review.
                echo'<h2 id="formText" style="margin-left: 15px"><a href="create_review.php">Create Review</a></h2>';
            }
        }
        ?>
        <div class="personalReviewPreview" id="personalReviewPreview">
        </div>
        <div class="reviewPreviews" style="width: 55vw">
        <?php
        if($bathroomID == null) {
            echo 'No reviews yet.';
        }
        else {
            $reviewCountIndex = 0; 
            while($reviewCountIndex < count($allReviews)) {
                $currentRow = $allReviews[$reviewCountIndex]; 
                $thisUserID = $currentRow['userID'];
                $total = 0; 
                for($i = 1; $i < 7; $i++) {
                    $total += $currentRow[$i];
                }
                $total /= 6;
                $q = "SELECT userName FROM user WHERE userID = $thisUserID;";
                $thisUserName = mysqli_fetch_assoc(mysqli_query($dbc, $q))['userName'];
                if($thisUserID != $_SESSION['userIndex']) {
                    echo ConstructReview($thisUserName, $currentRow['reviewComments'], round((string)$total, 1),  $bathroomID, $dbc, $thisUserID);
                }
                $reviewCountIndex += 1;
            }  
        }
        ?>
        </div>
        <script>
            function formatAddress(adr) { // Removes <span> elements from a text address, as these conflict with text input boxes.
                formattedAddress = adr.replace(/<\/?span[^>]*>/g,"");
                return formattedAddress;
            }
            var url = window.location.search;
            url = url.slice(-1);
            if (url == '=') {
                console.log(sessionStorage.getItem('placeName'));
                document.getElementById('locationName').innerHTML += "<h1 id='formText' style='text-align:center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 20px'>"+sessionStorage.getItem('placeName')+"</h1>";
            }
            document.getElementById('locationName').innerHTML += "<a href='http://maps.google.com/?q=" + formatAddress(sessionStorage.getItem('placeAddress')) +"'style='margin-right:auto; margin-left:auto' id='formText'/>Navigate Me!</a>";
        </script>
    </div>
</body>
</html>
