<?php

    // configuration
    require("../includes/global.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))		apologize("You must provide your username.");
        if (empty($_POST["password"]))		apologize("You must provide your password.");
		
        // if we found user, check password
        if ($user = get_user($_POST["username"]))
        {
			if ($user["failed_logins"] >= 3)
				apologize("account locked");

			log_attempt(	$user, 
							$_SERVER["REMOTE_ADDR"], 
							$success = password_verify($_POST["password"], $user["password"])
						);

			if ($success)
			{			
				// remember that the user is now logged in
				session_start();
				$_SESSION["user"] = $user;

				// redirect to homepage
				redirect("home.php");
			}
        }

        // else apologize
        apologize("invalid login credentials");
    }
    else
    {
        // else render form
        render("login_form.php", ["title" => "Log In"]);
    }

?>
