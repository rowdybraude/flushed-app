<?php
function ConstructReview($reviewName, $reviewComments, $reviewScore, $reviewBathroomID = null, $isUserID = null, $database = null) { // A function which takes in information about the review and outputs it as a preview.
    $fwd = '<h3 id="formText" style="margin: 0 0 0 10px;">'.$reviewName.'</h3>'; // Sets the default way to output the reviewer name as simple text.
    if($reviewBathroomID != "") { //If a bathroom ID is given to the function, it will change the review title to direct the user to the review page under than bathroom index.
        $fwd = '<h3 id="formText" style="margin: 0 0 0 10px;"><a href="bathroom_page.php?bathroomID='.(string)$reviewBathroomID.'">'.$reviewName.'</a></h3>';
    }
    include_once 'tag_display.php';
    $review =
    '
    <div id="tags"
    <div id="reviewPreviewElement">
    <div id="reviewPreview" class="reviewPreviewContainer">
        <div id="reviewInfo" class="reviewInfo">'
            .$fwd.'
            <p id="formText" style="margin: 10px 0 0 10px; overflow: visible">'.$reviewComments.'</p>
        </div>
        <div id="reviewScore" class="reviewScore"> 
            <h1 style="color: red; font-size: 50px; margin-top: 45px; margin-bottom: 45px;" id="formText">'.(string)$reviewScore.'</h1> 
        </div>
    </div>
</div>
    ';
    return GenerateTags($reviewBathroomID, $database, $isUserID).$review; // This is returned as a string so that it can be coagulated between other strings if neccesary, improving web page flow. 
}
?>

