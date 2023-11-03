<div id="navBar">
    <p style="margin-top: 0; margin-bottom: 0;"><a href="index.php"><img src="Resources/Site_Art/flushedlogo.png" style="width:50px; height:70px; margin-top: 5px; margin-left:10px;"></p>
    <?php
    if($_SESSION['loggedIn'] == true) { // Checks if the user has been logged in. If they have, add the hyperlink to the account page. If not, keep the hyperlink to the sign up page. 
        echo '<a href="account.php" style="margin-left: auto;"><h1>Account</h1></a>';
    }
    else {
        echo '<a href="sign_up_page.php" style="margin-left: auto;"><h1>Sign Up</h1></a>';
    }
    ?>
    <a href="create_review.php" style="margin-left: 20px;" ><h1 id="navBarLink">Create Review</h1></a>
    <a href="bathroom_search.php" style="margin-left: 20px"><h1 id="navBarLink">Location Search</h1></a>
</div>