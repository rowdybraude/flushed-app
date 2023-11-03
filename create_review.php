<?php 
session_start();
if($_SESSION['loggedIn'] == false) { // Redirects the user to the sign up page if they have not alread logged in.
    header("Location:sign_up_page.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <?php include 'Navbar.php' ?>
        <div id="body">
            <h1 style="text-align:left; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 10px; margin-left:20px; margin-top: 0;">Create Review</h1>
            <form action="create_review_function.php" method="post" id="reviewInputField" style="width:62vw;">
                    <p id="formText">Bathroom Name</p><input type="text" name="bathroomName" style="width:60vw" id="bathroomName"> <br> 
                    <div style="position: fixed; margin-right:400">
                        <?php include 'tag_box.php';?>
                    </div>
                    <script>
                    var dropdownBox = document.getElementById("tagDropdown");
                    dropdownBox.setAttribute("style", "margin-left: 50vw")
                    </script>
                    <p id="formText">Address</p><input type="text" style="width:60vw" id="bathroomAddress"> <br>
                    <div style="display:flex; flex-direction:row; width:10px;flex-wrap:wrap; width:60vw;">
                        <p id="formText">Cleanliness</p>
                        <input type="range" min="0" max="5" step="1" style="width:60vw" name="bathroomCleanliness">
                        <p id="formText">Spaciousness</p>
                        <input type="range" min="0" max="5" step="1"style="width:60vw" name="bathroomSpaciousness">
                        <p id="formText">Aestetics</p>
                        <input type="range" min="0" max="5" step="1"style="width:60vw" name="bathroomAesthetics">
                        <p id="formText">Features</p>
                        <input type="range" min="0" max="5" step="1"style="width:60vw" name="bathroomFeatures">
                        <p id="formText">Accessability</p>
                        <input type="range" min="0" max="5" step="1"style="width:60vw" name="bathroomAccessability">
                        <p id="formText">Availability</p>
                        <input type="range" min="0" max="5" step="1"style="width:60vw" name="bathroomAvailability">
                        <input type="hidden" name="bathroomLat" id="bathroomLat">
                        <input type="hidden" name="bathroomLng" id="bathroomLng">
                    </div>
                    <p id="formText">Comment</p><input type="text" style="width:60vw" id="bathroomComments" name="bathroomComments" maxlength="130"> <br>
                    <p id="formText">Additional Notes</p><input type="text" style="width:60vw" id="bathroomComments" name="bathroomNotes" maxlength="20" placeholder="Extra info about the bathroom- e.g. 4th. floor."> <br>
                    <?php
                    if($_SESSION['validationFailureID'] == 1) { // Checks the validationFailureID returned from create_review_function.php and output the error accordingly. 
                        echo'<div class="errorBox">
                            <p>Missing name or address.</p>
                            </div>';
                    } 
                    if($_SESSION['validationFailureID'] == 2) {
                        echo'<div class="errorBox">
                            <p>You have already made a review for this location.</p>
                            </div>';
                    }
                    $_SESSION['validationFailureID'] = 0; // Resets the validationFailureID so that errors are not repeated in the futre. 
                    ?>
                    <input type="submit" value="Submit Review" name="confirmBtn" class="reviewSubmit" style="margin-left: 65vw">
                </form>         
            </div>
        </div>
    </body>
</html>
<script async  
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj4RBNo8cCAdY8yW7MgRCQLCaIJB92J1A&libraries=places&callback=initAutocomplete">
</script>
<script> 
function initAutocomplete() { // Initialize the google places autocomplete box on the 'bathroomName' text input box. 
    autocompleteName = new google.maps.places.Autocomplete(
        document.getElementById('bathroomName'),
        {
            // Specifies the type of building the autocomplete box will reccomend to the user.
            types: ['establishment'], // Only businesses can be accessed- public access. 
            componentRestrictions: {'country' : ['US']},
            fields: ['place_id', 'geometry', 'name', 'adr_address'] // Specifies the fields that the autocomplete box will return to the user upon completion.
        }    
    );
    autocompleteName.addListener('place_changed', onPlaceChanged); // Add a listener to the text input box which call onPlaceChanged when completed. 

    try { // Called when the user is coming from the bathroom reviews page to autocomplete the name and address of the bathroom. ONLY every used on the create review page.
        document.getElementById("bathroomName").value = sessionStorage.getItem("placeName");
        if(sessionStorage.getItem("bathroomAddress") == null) {
            document.getElementById("bathroomAddress").value = "";
        }
        document.getElementById("bathroomAddress").value = formatAddress(sessionStorage.getItem("placeAddress")); // Formats the address without the <span> attribute so that be displayed as regular text.
        document.getElementById("bathroomLat").value = sessionStorage.getItem("placeLat");
        document.getElementById("bathroomLng").value = sessionStorage.getItem("placeLng"); 
        sessionStorage.clear();  
    }
    catch {
        console.log("autocomplete failed");
    }

}
function onPlaceChanged() { // Stores location information upon entering or changing a location in the autocomplete box.
    var place = autocompleteName.getPlace(); // Get place info from autocomplete box. 
    if(!place.geometry) { // Check if the place entered by the user is a valid location, reset box if not.
        document.getElementById('bathroomName').placeholder = 'Enter a name';
    }
    else { // If the place is submitted by the user succesfully, neccesary information about the location will be stored in session storage in case the user decides to navigate to the review page or create a review. 
        document.getElementById('bathroomName').value = place.name;
        document.getElementById('bathroomAddress').value = formatAddress(place.adr_address);
        document.getElementById('bathroomLat').value = place.geometry.location.lat();
        document.getElementById('bathroomLng').value = place.geometry.location.lng();
    }
}
function formatAddress(adr) { // Removes <span> elements from a text address, as these conflict with text input boxes.
                formattedAddress = adr.replace(/<\/?span[^>]*>/g,"");
                return formattedAddress;
            }
</script>