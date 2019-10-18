<?php

    // configuration
    require("../includes/global.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["username"]))						apologize("You must provide a username.");
        if (empty($_POST["password"]))						apologize("You must provide a password.");
        if (empty($_POST["confirmation"]))					apologize("You need to confirm your password by re-typing it.");
        if ($_POST["password"] != $_POST["confirmation"])	apologize("Password and confirmation do not match.");
		
		// create new user account
		if (make_user($_POST["username"], $_POST["password"]) === false)
			apologize("Username already exists.");		
		
		redirect("login.php");
    }
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }
    
?>
