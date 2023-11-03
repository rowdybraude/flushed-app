<?php
session_start(); 
?>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj4RBNo8cCAdY8yW7MgRCQLCaIJB92J1A&libraries=places&callback=initAutocomplete"> // Enables to google places API on the page. 
</script>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
    <?php include 'Navbar.php' ?>
        <div id="body">
        <h1 style="text-align:center; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; padding-top: 40px; padding-bottom: 100px;">Search Bathroom</h1>
            <div style="margin-left: 20vw">
                <input type="text" id="formText" style="width: 50vw; padding: 12px 20px; display: inline-block; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; text-align:center;">
            </div>
            <div id="searchBoxOptions">
                <div style="display: flex; flex-direction:row; text-align: center; margin-top: 10vh" id="bathroomLocationOptions">
                    <button style='width: 35%; margin-left: 10%; margin-right: 5%;' id='basicBtn' onclick='location.href = "bathroom_search_function.php?bathroomName=" + placeName;'>Reviews</button> <!-- Navigates the user to a page which will process their request to go to the review page for the inputted address. Takes the bathroom name as a $_GET variable because it is allowed to be visible and will only be used this once -->
                    <button style='width: 35%; margin-right: 10%; margin-left: 5%;' id='basicBtn' onclick='location.href = "http://maps.google.com/?q=" + document.getElementById("formText").value;'>Navigate Me</button> <!-- Navigates the user to a google maps page for the location provided by the autocomplete box for navigation. -->
                </div>
            </div>
            <div id='googleMap' style="margin-top: 30px; height: 200px"> <!-- empty div that will be filled by the google maps box upon the completion of the autocomplete box -->
            </div>
        </div>
    </body>
</html>
<script>
document.getElementById("searchBoxOptions").hidden = true; // Disables the options after entering a location on page load. 
document.getElementById("googleMap").hidden = true;
function initAutocomplete() { // Initializes a google places autocomplete box on the element with the 'formText' id (the text input box)
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('formText'),
        {
            types: ['establishment'], // Restricts the type of information the autocomplete search box allows to establishments inside of the United States.
            componentRestrictions: {'country' : ['US']},
            fields: ['place_id', 'geometry', 'name', 'adr_address'] // Sets the fields which will be returned when a location is selected by the user to the google maps Place ID,
                                                                    // geometry (location data), location name, and address, as this is all that is neccesary for the program to function. 
        }    
    );

    autocomplete.addListener('place_changed', onPlaceChanged); // Adds a listener which will call the onPlaceChanged function when the autocomplete box is completed. 
}
wait(1000);
function onPlaceChanged() { // Handles logic which occurs when the google places autocomplete box is updated. 
    var place = autocomplete.getPlace();
    if(!place.geometry) { // If the place entered has no location data, give the searchbox a placeholder value.
        document.getElementById('formText').placeholder = 'Enter a place'; 
        document.getElementById("searchBoxOptions").hidden = true; // Hides further options from the user until a valid locatopn is inputed.
    }
    else { // In the case that the entered location has location data, show the options avaialble to the user and stores the neccesary data in javascript session storage.
        document.getElementById("searchBoxOptions").hidden = false;  
        document.getElementById("googleMap").hidden = false;
        sessionStorage.setItem("placeName", place.name);
        sessionStorage.setItem("placeAddress", place.adr_address);
        sessionStorage.setItem("placeLat", place.geometry.location.lat());
        sessionStorage.setItem("placeLng", place.geometry.location.lng()); // Stores these items because they are neccesary for autocompleting the review page if there are no reviews, or the create review page if the user hasn't created a review for the searched location yet.
        myMap(place.geometry.location.lat(), place.geometry.location.lng());
    }
    placeName = place.name;
}


var map;
var mapProp

function PlotLabel(position, map, text) {
     // A function which takes in LatLng coordinates, the map being displayed, and text to display a labled marker at a given location on the map. Used when showing the user's position or the position of another location. 
        new google.maps.Marker( {
            position: position,
            map: map,
            shape: 'rectangle',
            label: {fontWeight: 'bold', fontSize: '14px', text: text} // Text is large and bold for clarity.
        });
}
function myMap(lat, lng) { // Constructor for the map - takes in latitude and longitude data and constructs a google maps Map.
    mapProp = { //
        center: new google.maps.LatLng(lat, lng),
        zoom: 15,
    };
    map = new google.maps.Map(document.getElementById("googleMap"), mapProp); // Injects created map into the webpage.
    PlotLabel(mapProp.center, map, 'Your Destination!'); // Plots a label at the user's position. 
    var currentPositi

}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj4RBNo8cCAdY8yW7MgRCQLCaIJB92J1A&callback=getLocation"></script>