<?php

session_start();  
if ($_SESSION['loggedIn'] == null) {

    $_SESSION['loggedIn'] = false; // Instantiates the SESSION loggedIn variable if the 
}
$_SESSION['validationFailureID'] = 0; 
?> <!DOCTYPE html>
 <html>
    <script src="index.php"></script>
    <head>
        <header title="Flush'd.net"></header>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <?php include 'navbar.php'; // Add the navigation bar to the page.?> 
        <div id="googleMap" style="height: 400px"></div>
        <script> 
        var map;
        var mapProp;

        function showPosition(position) { // Takes  position and creates a google map instance with a pin at said position.
            myMap(position.coords.latitude, position.coords.longitude);
        }

        function PlotLabel(position, map, text) { // A function which takes in LatLng coordinates, the map being displayed, and text to display a labled marker at a given location on the map. Used when showing the user's position or the position of another location. 
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
            this.map = new google.maps.Map(document.getElementById("googleMap"), mapProp); // Injects created map into the webpage.
            PlotLabel(mapProp.center, map, 'Your Location!'); // Plots a label at the user's position. 
        }

        function getLocation() {  // Gets the data for the user's current position and passes it to a function.
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        }
        getLocation(); // Get user location via geocoding API.

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAj4RBNo8cCAdY8yW7MgRCQLCaIJB92J1A&callback=myMap"> // Instance the google maps API onto the page</script>

        <div style="display: flex; margin-top: 30px;" >
            <button style="width: 35%; margin-left: 10%; margin-right: 5%;" id="basicBtn" onclick="location.href = 'create_review.php';">Create Review</button> <!-- Button which takes the user to the create review page -->
            <button style="width: 35%; margin-right: 10%; margin-left: 5%;" id="basicBtn" onclick="location.href = 'bathroom_search.php';">Find Restroom</button> <!-- Button which takes the user to the bathrom search -->
        </div>
    </body>
 </html>
