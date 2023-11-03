<?php
require 'server_connect.php';
?>
<script>
        function generateTagDropdown() { // Makes visible the row of tag buttons.
        document.getElementById("myTagDropdown").hidden = !document.getElementById("myTagDropdown").hidden;
    }
</script>
<head>
    <link rel="stylesheet" href="styles.css">
</head>
<html> <!-- Skeleton for dropdown is derived from w3Schools. Retrofitted for use in selecting tags via the tag system.  -->
    <div class='tagDropdown' id="tagDropdown">
        <button onclick="generateTagDropdown()" class="dropbtn" type="button">Add Tag</button>
        <div id="myTagDropdown" class="dropdown-content">
            <input type="text" placeholder="Select a tag" id="myTagInput" onkeyup="filterFunction()" maxlength="20">
            <div style="overflow-y: auto; max-height: 100px; overflow-x: hidden;" id="tagList">
            <?php 
            $q = 'SELECT * FROM tags';
            $tagList = mysqli_fetch_all(mysqli_query($dbc, $q), MYSQLI_BOTH); // Get an array of all tags currently avialable from the database.
            foreach($tagList as $tag) {
                echo '<button id='.$tag['tag'].' onclick="AddTag('."'".$tag['tag']."'".')" style="margin: 0" class="tagBtn" type="button">'.$tag['tag'].'</button><br>'; // Print said tags as a column of buttons. 
            }
            ?>
            </div>
        <button onclick="CreateNewTag()" class="tagCreateBtn" type="button">Create New Tag</button>
    </div>
    <div id="selectedTags" class="selectedTags">
    </div>
    <form method='post'>
        <input type="hidden" id='tag1' name='tag1'> <!-- 3 hidden tags which are used to transfer the javascript tags over to php via from submit.-->
        <input type="hidden" id='tag2' name='tag2'>
        <input type="hidden" id='tag3' name='tag3'>
    </form>
</html>
<script>
    document.getElementById("myTagDropdown").hidden = true; // Sets the tag list visibility to hidden by default. 
    function PopTag(index, name) { // Remove tag from the currently selected tag box and return it to the tag dropdown.
        if(document.getElementById('tag'+index).value == name) { // Check if currently selected input box has the same value as the tag.
            document.getElementById('tag'+index).value = "";
            document.getElementById(name).hidden = false;
            var tag = document.getElementById("'"+name+"'temp"); // Create a temp tag which represents the instance of the tag in the currently selected tag box. 
            tag.remove();  
        }
        else {
            PopTag(index + 1, name);
        }
    }
    function RemoveTag(name) { // Remove tag from selected tag list and therefore the tag hidden input.
        PopTag(1, name);
    }
    function FillTag(index, name) { // Add tag to the most recently availale hidden tag input- replace the 3rd one if all inputs are full. 
        if(document.getElementById('tag' + index).value == "") {
            document.getElementById('tag' + index).value = name;
            return
        }
        else {
            if(index == 3) {
                RemoveTag(document.getElementById('tag' + index).value);
                AddTag(name);
            }
            FillTag(index + 1, name);
        }
    }
    function AddTag(name) { // Takes in the name of the tag, adds it to the tag pool beneath the tag list, and remove the tag from the tag list.
        FillTag(1, name);
        document.getElementById(name).hidden = true; 
        name = "'" + name + "'";
        document.getElementById('selectedTags').innerHTML += '<button onclick="RemoveTag('+name+')" id="'+name+'temp'+'">' + name + '</button>';
    }
    function CreateNewTag() { // Called when the 'create new tag' button clicked. 
        var currentInput = document.getElementById('myTagInput').value;
        if(currentInput.includes(" ")) { // Check if there are any spaces inputted into the tag field.
            alert('Spaces are not permitted. Please try again.');
            return; 
        }
        if(currentInput == "") { // Check if nothing was inputted into the tag field. 
            alert('No tag inputted. Please try again.');
            return;
        }
        try {
            if(currentInput == document.getElementById(currentInput).id)  // Check if the tag already exists in the list{
                alert("Tag already exists. Please try again.");
                return; 
            }
        }
        catch { // Make a completely new tag.
            // Add new tag to list of existing tags on the frontend.
            var dropdown = document.getElementById('tagList')
            dropdown.innerHTML += '<button id="' + currentInput +'" onclick="AddTag('+"'"+currentInput+"'"+')" style="margin: 0" class="tagBtn" type="button">'+ currentInput +'</button><br>'
            // Transfer tag over to list of selected tags
            document.getElementById(currentInput).click();
            // Reset list to show all other options.
            filterFunction(); 
            // If the file is submitted, save the new tag to the database.
        }
    }
    function filterFunction() { // Filter function adapted from w3Schools- carries out a string search accross the tag elements and deactivates the onees not related to the text search.
        var input = document.getElementById("myTagInput");
        var filter = input.value.toUpperCase();
        var div = document.getElementById("myTagDropdown");
        var tagBtn = div.getElementsByTagName("button");
        for (i = 0; i < tagBtn.length - 1; i++) {
            txtValue = tagBtn[i].textContent || tagBtn[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tagBtn[i].style.display = "";
            } 
            else {
                tagBtn[i].style.display = "none";
            }
        }
    }
</script>