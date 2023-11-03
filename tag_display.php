
<?php

    // A function which generates a immutable list of tags on the page where needed- works for every use case through a comparison of the type parameter.
    function GenerateTags($type, $id, $database, $otherID = null) { // Gets the id of the object whose tags are being pulled
        if($type == "review") { // The event in which the tag box is being placed under a review.
            $q = "SELECT reviewTagID1, reviewTagID2, reviewTagID3 FROM review WHERE bathroomID = $id AND userID = $otherID"; // Get the tag input for said review and fetch the array.
            $tags = mysqli_fetch_all(mysqli_query($database, $q), MYSQLI_BOTH);
            foreach($tags[0] as $tag => $key) { // Cycles through associative array- if the reviewTag is isn't null, print the tag into the box. 
                if($tag[$key] != null) {
                    return '<p style="border: 2px solid black; border-radius: 2px; font-size:10px; margin: 0; display: inline">'.$tag[$key].'</p><nobr>';;
                }
            }

        }
        elseif ($type == "bathroom"){ // The event in which the box will be placed underneath the total score for a bathroom and supply every tag associated with that bathroom.
            $q = "SELECT tag FROM tag JOIN tags ON (tag.tagID = tags.tagID) WHERE (tag.bathroomID = $id)";
            $tagList = mysqli_fetch_all(mysqli_query($database, $q), MYSQLI_BOTH);
            foreach($tagList as $tag) {
                echo '<p style="border: 2px solid black; border-radius: 2px; font-size:10px; margin: 0; display: inline">'.$tag['tag'].'</p><nobr>';
            }
        }
    }
?>
